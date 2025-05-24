<?php

namespace App\Http\Controllers\Web;

use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Utils\CartManager;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $couponLimit = Order::where([
            'customer_id' => auth('customer')->id(),
            'coupon_code' => $request['code']
        ])->groupBy('order_group_id')->get()->count();

        $coupon_f = Coupon::where(['code' => $request['code']])
            ->where('status', 1)
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('expire_date', '>=', date('Y-m-d'))
            ->first();

        if (!$coupon_f) {
            Toastr::error(translate('invalid_coupon'));
            return back()->with('translate', translate('invalid_coupon'));
        }

        if ($coupon_f && $coupon_f->coupon_type == 'first_order') {
            $coupon = $coupon_f;
        } else {
            $coupon = $coupon_f->limit > $couponLimit ? $coupon_f : null;
        }

        if ($coupon && $coupon->coupon_type == 'first_order') {
            $orders = Order::where(['customer_id' => auth('customer')->id()])->count();
            if ($orders > 0) {
                Toastr::error(translate('sorry_this_coupon_is_not_valid_for_this_user') . '!');
                return back()->with('translate', translate('sorry_this_coupon_is_not_valid_for_this_user'));
            }
        }

        if ($coupon && (
            ($coupon->coupon_type == 'first_order') ||
            ($coupon->coupon_type == 'discount_on_purchase' &&
                ($coupon->customer_id == '0' || $coupon->customer_id == auth('customer')->id()))
        )) {
            $total = 0;
            foreach (CartManager::get_cart() as $cart) {
                if (
                    $coupon->seller_id == '0' ||
                    (is_null($coupon->seller_id) && $cart->seller_is == 'admin') ||
                    ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')
                ) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;
                }
            }

            if ($total >= $coupon['min_purchase']) {
                $discount = 0;
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']);
                    $discount = $discount > $coupon['max_discount'] ? $coupon['max_discount'] : $discount;
                } else {
                    $discount = $coupon['discount'];
                }

                session()->put('coupon_code', $request['code']);
                session()->put('coupon_type', $coupon->coupon_type);
                session()->put('coupon_discount', $discount);
                session()->put('coupon_bearer', $coupon->coupon_bearer);
                session()->put('coupon_seller_id', $coupon->seller_id);

                Toastr::success(translate('coupon_applied_successfully') . '!');
                return back()->with('translate', translate('coupon_applied_successfully'));
            }
        } elseif (
            $coupon && $coupon->coupon_type == 'free_delivery' &&
            ($coupon->customer_id == '0' || $coupon->customer_id == auth('customer')->id())
        ) {
            $total = 0;
            $shipping_fee = 0;
            foreach (CartManager::get_cart() as $cart) {
                if (
                    $coupon->seller_id == '0' ||
                    (is_null($coupon->seller_id) && $cart->seller_is == 'admin') ||
                    ($coupon->seller_id == $cart->seller_id && $cart->seller_is == 'seller')
                ) {
                    $product_subtotal = $cart['price'] * $cart['quantity'];
                    $total += $product_subtotal;

                    if (
                        is_null($coupon->seller_id) ||
                        $coupon->seller_id == '0' ||
                        $coupon->seller_id == $cart->seller_id
                    ) {
                        $shipping_fee += $cart['shipping_cost'];
                    }
                }
            }

            if ($total >= $coupon['min_purchase']) {
                session()->put('coupon_code', $request['code']);
                session()->put('coupon_type', $coupon->coupon_type);
                session()->put('coupon_discount', $shipping_fee);
                session()->put('coupon_bearer', $coupon->coupon_bearer);
                session()->put('coupon_seller_id', $coupon->seller_id);

                Toastr::success(translate('coupon_applied_successfully') . '!');
                return back()->with('translate', translate('coupon_applied_successfully'));
            }
        }

        Toastr::error(translate('invalid_coupon'));
        return back()->with('translate', translate('invalid_coupon'));
    }


    public function removeCoupon(Request $request): JsonResponse|RedirectResponse
    {
        session()->forget('coupon_code');
        session()->forget('coupon_type');
        session()->forget('coupon_bearer');
        session()->forget('coupon_discount');
        session()->forget('coupon_seller_id');

        if ($request->ajax()) {
            return response()->json(['messages' => translate('coupon_removed')]);
        }
        Toastr::success(translate('coupon_removed'));
        return back();
    }
}
