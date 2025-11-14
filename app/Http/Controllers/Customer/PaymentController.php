<?php

namespace App\Http\Controllers\Customer;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Library\Payer;
use App\Library\Payment as PaymentInfo;
use App\Library\Receiver;
use App\Models\ShippingAddress;
use App\Models\ShippingType;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\CartShipping;
use App\Models\Currency;
use App\Traits\Payment;
use App\Utils\CartManager;
use App\Utils\Convert;
use App\Utils\OrderManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\Utils\currency_converter;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $user = Helpers::get_customer($request);
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required',
            'payment_platform' => 'required',
        ]);

        $validator->sometimes('customer_id', 'required', function ($input) {
            return in_array($input->payment_request_from, ['app', 'react']);
        });
        $validator->sometimes('is_guest', 'required', function ($input) {
            return in_array($input->payment_request_from, ['app', 'react']);
        });

        if ($validator->fails()) { //api
            $errors = Helpers::error_processor($validator);
            if(in_array($request->payment_request_from, ['app', 'react'])){
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }else{
                foreach ($errors as $value) {
                    Toastr::error(translate($value['message']));
                }
                return back();
            }
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        $carts = Cart::whereIn('cart_group_id', $cart_group_ids)->get();
        $product_stock = CartManager::product_stock_check($carts);
        if(!$product_stock && in_array($request->payment_request_from, ['app', 'react'])){
            return response()->json(['errors' => ['code' => 'product-stock', 'message' => 'The following items in your cart are currently out of stock']], 403);
        }elseif(!$product_stock){
            Toastr::error(translate('the_following_items_in_your_cart_are_currently_out_of_stock'));
            return redirect()->route('shop-cart');
        }

        $verifyStatus = OrderManager::minimum_order_amount_verify($request);
        if($verifyStatus['status'] == 0 && in_array($request->payment_request_from, ['app', 'react'])){
            return response()->json(['errors' => ['code' => 'Check the minimum order amount requirement']], 403);
        }elseif($verifyStatus['status'] == 0){
            Toastr::info('Check the minimum order amount requirement');
            return redirect()->route('shop-cart');
        }

        if(in_array($request->payment_request_from, ['app', 'react'])) {
            $shippingMethod = Helpers::get_business_settings('shipping_method');
            $physical_product = false;
            foreach ($carts as $cart) {
                if ($cart->product_type == 'physical') {
                    $physical_product = true;
                }

                if ($shippingMethod == 'inhouse_shipping') {
                    $admin_shipping = ShippingType::where('seller_id', 0)->first();
                    $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                } else {
                    if ($cart->seller_is == 'admin') {
                        $admin_shipping = ShippingType::where('seller_id', 0)->first();
                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                    } else {
                        $seller_shipping = ShippingType::where('seller_id', $cart->seller_id)->first();
                        $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                    }
                }

                if ($shipping_type == 'order_wise') {
                    $cart_shipping = CartShipping::where('cart_group_id', $cart->cart_group_id)->first();
                    if (!isset($cart_shipping) && $physical_product) {
                        return response()->json(['errors' => ['code' => 'shipping-method', 'message' => 'Data not found']], 403);
                    }
                }
            }
        }

        $redirect_link = $this->customer_payment_request($request);

        if(in_array($request->payment_request_from, ['app', 'react'])) {
            return response()->json(['redirect_link'=>$redirect_link], 200);
        }else{
            return redirect($redirect_link);
        }
    }

    public function success()
    {
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        return response()->json(['message' => 'Payment failed'], 403);
    }

    public function web_payment_success(Request $request)
    {
        if($request->flag == 'success') {
            if (session()->has('payment_mode') && session('payment_mode') == 'app') {
                return response()->json(['message' => 'Payment succeeded'], 200);
            } else {
                Toastr::success(translate('Payment_success'));
                return view(VIEW_FILE_NAMES['order_complete']);
            }
        }else{
            if(session()->has('payment_mode') && session('payment_mode') == 'app'){
                return response()->json(['message' => 'Payment failed'], 403);
            }else{
                Toastr::error(translate('Payment_failed').'!');
                return redirect(url('/'));
            }
        }

    }

    public function customer_payment_request(Request $request)
    {
        $additional_data = [
            'business_name' => BusinessSetting::where(['type' => 'company_name'])->first()->value,
            'business_logo' => asset('storage/app/public/company') . '/' . Helpers::get_business_settings('company_web_logo'),
            'payment_mode' => $request->has('payment_platform') ? $request->payment_platform : 'web',
        ];

        $user = Helpers::get_customer($request);
        if(in_array($request->payment_request_from, ['app', 'react'])){
            $additional_data['customer_id'] = $request->customer_id;
            $additional_data['is_guest'] = $request->is_guest;
            $additional_data['order_note'] = $request['order_note'];
            $additional_data['address_id'] = $request['address_id'];
            $additional_data['billing_address_id'] = $request['billing_address_id'];
            $additional_data['coupon_code'] = $request['coupon_code'];
            $additional_data['coupon_discount'] = $request['coupon_discount'];
            $additional_data['payment_request_from'] = $request->payment_request_from;
        }

        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $currency_code = 'USD';
        } else {
            $default = BusinessSetting::where(['type' => 'system_default_currency'])->first()->value;
            $currency_code = Currency::find($default)->code;
        }

        if(in_array($request->payment_request_from, ['app', 'react'])) {
            $cart_group_ids = CartManager::get_cart_group_ids($request);
            $cart_amount = 0;
            $shipping_cost_saved = 0;
            foreach ($cart_group_ids as $group_id) {
                $cart_amount += CartManager::api_cart_grand_total($request, $group_id);
                $shipping_cost_saved += CartManager::get_shipping_cost_saved_for_free_delivery($group_id);
            }
            $payment_amount = $cart_amount - $request['coupon_discount'] - $shipping_cost_saved;
        }else{
            $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
            $order_wise_shipping_discount = CartManager::order_wise_shipping_discount();
            $shipping_cost_saved = CartManager::get_shipping_cost_saved_for_free_delivery();
            $payment_amount = CartManager::cart_grand_total() - $discount - $order_wise_shipping_discount - $shipping_cost_saved;
        }

        $customer = Helpers::get_customer($request);

        if($customer == 'offline'){
            $address = ShippingAddress::where(['customer_id'=>$request->customer_id, 'is_guest'=>1])->latest()->first();
            if($address){
                $payer = new Payer(
                    $address->contact_person_name,
                    $address->email,
                    $address->phone,
                    ''
                );
            }else {
                $payer = new Payer(
                    'Contact person name',
                    '',
                    '',
                    ''
                );
            }
        }else{
            $payer = new Payer(
                $customer['f_name'] . ' ' . $customer['l_name'] ,
                $customer['email'],
                $customer['phone'],
                ''
            );
            if(empty($customer['phone'])){
                Toastr::error(translate('please_update_your_phone_number'));
                return route('checkout-payment');
            }
        }

        $payment_info = new PaymentInfo(
            success_hook: 'digital_payment_success',
            failure_hook: 'digital_payment_fail',
            currency_code: $currency_code,
            payment_method: $request->payment_method,
            payment_platform: $request->payment_platform,
            payer_id: $customer=='offline' ? $request->customer_id : $customer['id'],
            receiver_id: '100',
            additional_data: $additional_data,
            payment_amount: $payment_amount,
            external_redirect_link: $request->payment_platform == 'web' ? $request->external_redirect_link : null,
            attribute: 'order',
            attribute_id: idate("U")
        );

        $receiver_info = new Receiver('receiver_name','example.png');

        $redirect_link = Payment::generate_link($payer, $payment_info, $receiver_info);

        return $redirect_link;
    }

    public function customer_add_to_fund_request(Request $request)
    {
        if(Helpers::get_business_settings('add_funds_to_wallet') != 1)
        {
            if(in_array($request->payment_request_from, ['app', 'react'])){
                return response()->json(['message' => 'Add funds to wallet is deactivated'], 403);
            }

            Toastr::error(translate('add_funds_to_wallet_is_deactivated'));
            return back();
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_method' => 'required',
            'payment_platform' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = Helpers::error_processor($validator);
            if(in_array($request->payment_request_from, ['app', 'react'])){
                return response()->json(['errors' => $errors]);
            }else{
                foreach ($errors as $value) {
                    Toastr::error(translate($value['message']));
                }
                return back();
            }
        }

        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default_currency = Currency::find(Helpers::get_business_settings('system_default_currency'));
            $currency_code = $default_currency['code'];
            $current_currency = $request->current_currency_code ?? session('currency_code');
        } else {
            $default = BusinessSetting::where(['type' => 'system_default_currency'])->first()->value;
            $currency_code = Currency::find($default)->code;
            $current_currency = $currency_code;
        }


        $minimum_add_fund_amount = Helpers::get_business_settings('minimum_add_fund_amount') ?? 0;
        $maximum_add_fund_amount = Helpers::get_business_settings('maximum_add_fund_amount') ?? 0;

        if(!(Convert::usdPaymentModule($request->amount, $current_currency) >= Convert::usdPaymentModule($minimum_add_fund_amount, 'USD')) || !(Convert::usdPaymentModule($request->amount, $current_currency) <= Convert::usdPaymentModule($maximum_add_fund_amount, 'USD')))
        {
            $errors = [
                'minimum_amount' => $minimum_add_fund_amount ?? 0,
                'maximum_amount' => $maximum_add_fund_amount ?? 1000,
            ];
            if(in_array($request->payment_request_from, ['app'])){
                return response()->json($errors, 202);
            }elseif(in_array($request->payment_request_from, ['react'])){
                return response()->json($errors, 403);
            }else{
                Toastr::error(translate('the_amount_needs_to_be_between').' '.currency_converter($minimum_add_fund_amount).' - '.currency_converter($maximum_add_fund_amount));
                return back();
            }
        }

        $additional_data = [
            'business_name' => BusinessSetting::where(['type' => 'company_name'])->first()->value,
            'business_logo' => asset('storage/app/public/company') . '/' . Helpers::get_business_settings('company_web_logo'),
            'payment_mode' => $request->has('payment_platform') ? $request->payment_platform : 'web',
        ];

        $customer = Helpers::get_customer($request);

        if(in_array($request->payment_request_from, ['app', 'react'])){
            $additional_data['customer_id'] = $customer->id;
            $additional_data['payment_request_from'] = $request->payment_request_from;
        }

        $payer = new Payer(
            $customer->f_name . ' ' . $customer->l_name,
            $customer['email'],
            $customer->phone,
            ''
        );

        $payment_info = new PaymentInfo(
            success_hook: 'add_fund_to_wallet_success',
            failure_hook: 'add_fund_to_wallet_fail',
            currency_code: $currency_code,
            payment_method: $request->payment_method,
            payment_platform: $request->payment_platform,
            payer_id: $customer->id,
            receiver_id: '100',
            additional_data: $additional_data,
            payment_amount: Convert::usdPaymentModule($request->amount, $current_currency),
            external_redirect_link: $request->payment_platform == 'web' ? $request->external_redirect_link : null,
            attribute: 'add_funds_to_wallet',
            attribute_id: idate("U")
        );

        $receiver_info = new Receiver('receiver_name','example.png');

        $redirect_link = Payment::generate_link($payer, $payment_info, $receiver_info);

        if(in_array($request->payment_request_from, ['app', 'react'])) {
            return response()->json(['redirect_link'=>$redirect_link], 200);
        }else{
            return redirect($redirect_link);
        }
    }
}
