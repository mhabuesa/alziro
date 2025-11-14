<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Events\EmailVerificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CustomerRegistrationRequest;
use App\Models\BusinessSetting;
use App\Models\PhoneOrEmailVerification;
use App\Models\Wishlist;
use App\User;
use App\Utils\CartManager;
use App\Utils\Helpers;
use App\Utils\SMS_module;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Gateways\Traits\SmsGateway;

class RegisterController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function register()
    {
        session()->put('keep_return_url', url()->previous());

        return view('customer.register');
    }

    public function submit(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'nullable|unique:users,email',
            'phone' => 'required|digits:11',
            'address' => 'required',
            'password' => 'required|same:con_password',
        ]);

        $user = User::where('phone', $request->phone)
            ->first();


        if ($user && $user->from_others == 1) {

            $user->update([
                'name' => $request['name'],
                'f_name' => $request['name'],
                'email' => $request['email'],
                'street_address' => $request['address'],
                'password' => bcrypt($request['password']),
                'from_others' => 0,
            ]);


            // Auto login the user
            auth('customer')->login($user);

            // Move cart from cookie to DB
            CartManager::cart_to_db();

            Toastr::success(translate('registration_success_login_now'));
            return redirect(session('keep_return_url'));
        } else {
            $request->validate([
                'phone' => 'required|unique:users,phone',
            ]);
        }

        $user = User::create([
            'name' => $request['name'],
            'f_name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'street_address' => $request['address'],
            'password' => bcrypt($request['password']),
        ]);

        // Auto login the user
        auth('customer')->login($user);

        // Move cart from cookie to DB
        CartManager::cart_to_db();

        Toastr::success(translate('registration_success_login_now'));
        return redirect(session('keep_return_url'));
    }





    public static function login_process($user, $phone, $password)
    {
        if (auth('customer')->attempt(['phone' => $phone, 'password' => $password], true)) {
            $wish_list = Wishlist::whereHas('wishlistProduct', function ($q) {
                return $q;
            })->where('customer_id', $user->id)->pluck('product_id')->toArray();

            session()->put('wish_list', $wish_list);
            $company_name = BusinessSetting::where('type', 'company_name')->first();
            $message = translate('welcome_to') . ' ' . $company_name->value . '!';
            CartManager::cart_to_db();
        } else {
            $message = 'Credentials are not matched or your account is not active!';
        }

        return $message;
    }

    // Resend OTP Code Using Ajax
    public static function resend_otp(Request $request)
    {
        $user = User::find($request->user_id);
        $token = PhoneOrEmailVerification::where('phone_or_email', '=', $user['email'])->first();
        $otp_resend_time = Helpers::get_business_settings('otp_resend_time') > 0 ? Helpers::get_business_settings('otp_resend_time') : 0;

        // Time Difference in Minutes
        if ($token) {
            $token_time = Carbon::parse($token->created_at);
            $add_time = $token_time->addSeconds($otp_resend_time);
            $time_differance = $add_time > Carbon::now() ? Carbon::now()->diffInSeconds($add_time) : 0;
        } else {
            $time_differance = 0;
        }

        $new_token_generate = rand(1000, 9999);
        if ($time_differance == 0) {
            if ($token) {
                $token->token = $new_token_generate;
                $token->otp_hit_count = 0;
                $token->is_temp_blocked = 0;
                $token->temp_block_time = null;
                $token->created_at = now();
                $token->save();
            } else {
                $new_token = new PhoneOrEmailVerification();
                $new_token->phone_or_email = $user['email'];
                $new_token->token = $new_token_generate;
                $new_token->created_at = now();
                $new_token->updated_at = now();
                $new_token->save();
            }

            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$user->is_phone_verified) {

                $published_status = 0;
                $payment_published_status = config('get_payment_publish_status');
                if (isset($payment_published_status[0]['is_published'])) {
                    $published_status = $payment_published_status[0]['is_published'];
                }

                if ($published_status == 1) {
                    SmsGateway::send($user->phone, $new_token_generate);
                } else {
                    SMS_module::send($user->phone, $new_token_generate);
                }
            }

            if ($email_verification && !$user->is_email_verified) {
                $email_services_smtp = Helpers::get_business_settings('mail_config');
                if ($email_services_smtp['status'] == 0) {
                    $email_services_smtp = Helpers::get_business_settings('mail_config_sendgrid');
                }
                if ($email_services_smtp['status'] == 1) {
                    try {
                        EmailVerificationEvent::dispatch($user['email'], $new_token_generate);
                    } catch (\Exception $exception) {
                        return response()->json([
                            'status' => "0",
                        ]);
                    }
                }
            }
            return response()->json([
                'status' => "1",
                'new_time' => $otp_resend_time,
            ]);
        } else {
            return response()->json([
                'status' => "0",
            ]);
        }
    }
}
