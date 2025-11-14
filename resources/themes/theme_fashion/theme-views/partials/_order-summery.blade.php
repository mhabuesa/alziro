@php($shippingMethod=getWebConfig(name: 'shipping_method'))
@php($product_price_total=0)
@php($total_tax=0)
@php($total_shipping_cost=0)
@php($order_wise_shipping_discount=\App\Utils\CartManager::order_wise_shipping_discount())
@php($total_discount_on_product=0)
@php($cart=\App\Utils\CartManager::get_cart())
@php($cart_group_ids=\App\Utils\CartManager::get_cart_group_ids())
@php($shipping_cost=\App\Utils\CartManager::get_shipping_cost())
@php($get_shipping_cost_saved_for_free_delivery=\App\Utils\CartManager::get_shipping_cost_saved_for_free_delivery())
@php($coupon_dis=0)
@if($cart->count() > 0)
    @foreach($cart as $key => $cartItem)
        @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
        @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
        @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
    @endforeach

    @php($total_shipping_cost=$shipping_cost - $get_shipping_cost_saved_for_free_delivery)
@else
    <span>{{ translate('empty_cart') }}</span>
@endif
<div class="total-cost-wrapper">
    <div class="total-cost-area text-capitalize">
        <h5 class="mb-4">{{translate('order_summary')}} <small
                    class="text-base font-regular text-small">({{count(\App\Utils\CartManager::get_cart())}} {{translate('items')}}
                )</small></h5>

        <div class="overflow-y-auto h--28rem">
            @if(auth('customer')->check())
                @php($cart_list = \App\Models\Cart::where(['customer_id' => auth('customer')->id(), 'is_guest' => 0])->get()->groupBy('cart_group_id'))
            @elseif(getWebConfig(name: 'guest_checkout') && session()->has('guest_id') && session('guest_id'))
                @php($cart_list = \App\Models\Cart::where(['customer_id' => session('guest_id'), 'is_guest' => 1])->get()->groupBy('cart_group_id'))
            @endif
            @foreach($cart_list as $group_key=>$group)
                @foreach($group as $cart_key=>$cartItem)
                    @if ($cart_key == 0)
                        @if($cartItem->seller_is=='admin')
                            <h6 class="font-bold letter-spacing-0">{{ getWebConfig(name: 'company_name') }}</h6>
                        @else
                            <h6 class="font-bold letter-spacing-0">{{ \App\Utils\get_shop_name($cartItem['seller_id']) }}</h6>
                        @endif
                    @endif
                @endforeach
                <ul class="total-cost-info mt-20px mb-30px mx-4">
                    @php($product_null_status = 0)

                    @foreach($group as $key=>$cartItem)
                        @php($product = $cartItem->product)
                        @if (!$product)
                            @php($product_null_status = 1)
                        @endif
                        <li>
                            <span>{{ isset($product) ? Str::limit($product->name, 35) : translate('product_not_available') }}</span>
                            <span>{{ \App\Utils\Helpers::currency_converter($cartItem->price) }}</span>
                        </li>
                    @endforeach
                </ul>
            @endforeach
            <ul class="total-cost-info mt-20px mb-30px  mx-4">
                <li>
                    <span>{{ translate('product_discount') }}</span>
                    <span>-{{\App\Utils\Helpers::currency_converter($total_discount_on_product)}}</span>
                </li>
                <li>
                    <span>{{ translate('sub_total') }}</span>
                    <span>{{\App\Utils\Helpers::currency_converter($product_price_total - $total_discount_on_product)}}</span>
                </li>
                <li>
                    <span>{{ translate('shipping') }}</span>
                    <span>{{\App\Utils\Helpers::currency_converter($total_shipping_cost)}}</span>
                </li>
                @if(session()->has('coupon_discount'))
                    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
                    <li>
                        <span>{{ translate('coupon_discount') }} </span>
                        <span>-{{\App\Utils\Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}</span>
                    </li>
                    @php($coupon_dis=session('coupon_discount'))
                @endif
                <li>
                    <span>{{ translate('tax') }}</span>
                    <span>{{\App\Utils\Helpers::currency_converter($total_tax)}}</span>
                </li>
            </ul>
        </div>
        <div class="ps-sm-4">
            <hr class="d-none d-sm-block"/>
        </div>
        <div class="d-block d-md-none">
            <h6 class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                <span>{{translate('total')}}</span>
                <span>{{\App\Utils\Helpers::currency_converter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</span>
            </h6>
        </div>

        <div class="proceed-cart-btn">
            <h6 class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                <span>{{translate('total')}}</span>
                <span>{{\App\Utils\Helpers::currency_converter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</span>
            </h6>
            <div class="ps-sm-4">
                <hr class="d-none d-sm-block"/>
            </div>
            <button class="btn btn-base w-100 justify-content-center form-control mt-1 mb-1 h-42px text-capitalize {{ strstr(request()->url(), 'checkout-payment') ? 'd-none':''}}"
                    id="proceed_to_next_action" data-gotocheckout="{{route('customer.choose-shipping-address-other')}}"
                    data-checkoutpayment="{{ route('checkout-payment') }}"
                    {{ (isset($product_null_status) && $product_null_status == 1) ? 'disabled':''}}
                    type="button">{{translate('proceed_to_next')}}</button>
        </div>
    </div>
</div>
