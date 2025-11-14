<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\ProductCompare;
use App\Models\Wishlist;
use App\User;
use App\Utils\CartManager;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public $company_name;

    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function captcha(Request $request, $tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if (Session::has($request->captcha_session_id)) {
            Session::forget($request->captcha_session_id);
        }
        Session::put($request->captcha_session_id, $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }


    public function login()
    {
        session()->put('keep_return_url', url()->previous());

        return view('customer.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('phone', $request->user_id)
            ->first();

        if ($user && $user->from_others != 0) {
            session()->put('old_customer_phone', $user->phone);
            return redirect()->route('old.customer');
        }

        $remember = $request->has('remember');

        if (!$user || !auth('customer')->attempt(['phone' => $user->phone, 'password' => $request->password], $remember)) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => translate('credentials_doesnt_match'),
                    'redirect_url' => ''
                ]);
            } else {
                Toastr::error(translate('credentials_doesnt_match'));
                return back()->withInput();
            }
        }


        // Save wish list & compare list to session
        $wish_list = Wishlist::whereHas('wishlistProduct', function ($q) {
            return $q;
        })->where('customer_id', $user->id)->pluck('product_id')->toArray();

        $compare_list = ProductCompare::where('user_id', $user->id)->pluck('product_id')->toArray();

        session()->put('wish_list', $wish_list);
        session()->put('compare_list', $compare_list);

        Toastr::info(translate('welcome_to') . ' ' . Helpers::get_business_settings('company_name') . '!');
        CartManager::cart_to_db();

        $redirect_url = "";
        $previous_url = url()->previous();

        if (
            strpos($previous_url, 'checkout-complete') !== false ||
            strpos($previous_url, 'offline-payment-checkout-complete') !== false ||
            strpos($previous_url, 'track-order') !== false
        ) {
            $redirect_url = route('home');
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => translate('login_successful'),
                'redirect_url' => $redirect_url,
            ]);
        } else {
            return redirect(session('keep_return_url'));
        }
    }


    public function logout(Request $request)
    {
        auth()->guard('customer')->logout();
        session()->forget('wish_list');
        Toastr::info(translate('come_back_soon') . '!');
        return redirect()->route('home');
    }

    public function get_login_modal_data(Request $request)
    {
        return response()->json([
            'login_modal' => view(VIEW_FILE_NAMES['get_login_modal_data'])->render(),
            'register_modal' => view(VIEW_FILE_NAMES['get_register_modal_data'])->render(),
        ]);
    }
}
