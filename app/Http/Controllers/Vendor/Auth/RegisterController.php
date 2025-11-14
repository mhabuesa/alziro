<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Contracts\Repositories\VendorWalletRepositoryInterface;
use App\Enums\SessionKey;
use App\Enums\ViewPaths\Vendor\Auth;
use App\Events\VendorRegistrationMailEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\VendorAddRequest;
use App\Services\ShopService;
use App\Services\VendorService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;

class RegisterController extends BaseController
{
    public function __construct(
        private readonly VendorRepositoryInterface $vendorRepo,
        private readonly VendorWalletRepositoryInterface $vendorWalletRepo,
        private readonly ShopRepositoryInterface $shopRepo,
        private readonly VendorService $vendorService,
        private readonly ShopService $shopService,
    )
    {
    }

    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        return $this->getView();
    }
    public function getView():View|RedirectResponse
    {
        $businessMode = getWebConfig(name:'business_mode');
        $vendorRegistration = getWebConfig(name:'seller_registration');
        if((isset($businessMode) && $businessMode=='single') || (isset($vendorRegistration) && $vendorRegistration==0))
        {
            Toastr::warning(translate('access_denied').'!!');
            return redirect('/');
        }
        return view(VIEW_FILE_NAMES[Auth::VENDOR_REGISTRATION[VIEW]]);
    }
    public function add(VendorAddRequest $request):JsonResponse
    {
        $recaptcha = getWebConfig('recaptcha');
        if (isset($recaptcha) && $recaptcha['status'] == 1) {
            try {
                $request->validate([
                    'g-recaptcha-response' => [
                        function ($attribute, $value, $fail) {
                            $secret_key = getWebConfig('recaptcha')['secret_key'];
                            $response = $value;
                            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                            $response = \file_get_contents($url);
                            $response = json_decode($response);
                            if (!$response->success) {
                                $fail(translate('ReCAPTCHA_Failed'));
                            }
                        },
                    ],
                ]);
            } catch (\Exception $exception) {
                return response()->json(['error'=>translate('Captcha_Failed')]);
            }
        } else {
            if (strtolower($request['default_recaptcha_id_seller_regi']) != strtolower(Session(SessionKey::VENDOR_RECAPTCHA_KEY))) {
                Session::forget(SessionKey::VENDOR_RECAPTCHA_KEY);
                return response()->json(['error'=>translate('Captcha_Failed')]);
            }
        }
        $vendor = $this->vendorRepo->add(data: $this->vendorService->getAddData($request));
        $this->shopRepo->add($this->shopService->getAddShopDataForRegistration(request:$request,vendorId: $vendor['id']));
        $this->vendorWalletRepo->add($this->vendorService->getInitialWalletData(vendorId:$vendor['id']));

        $data = [
            'name' => $request['f_name'],
            'status' => 'pending',
            'subject' => translate('Vendor_Registration_Successfully_Completed'),
            'title' => translate('registration_Complete').'!',
            'message' => translate('congratulation').'!'.translate('Your_registration_request_has_been_send_to_admin_successfully').'!'.translate('Please_wait_until_admin_reviewal').'.',
        ];
        event(new VendorRegistrationMailEvent($request['email'],$data));
        return response()->json([
                'redirectRoute'=>route('vendor.auth.login')
            ]
        );
    }
}
