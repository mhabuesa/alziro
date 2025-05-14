@if ($method)

    <div class="payment-list-area">
            <?php
            $product_price_total = 0;
            $total_tax = 0;
            $total_shipping_cost = 0;
            $order_wise_shipping_discount = \App\Utils\CartManager::order_wise_shipping_discount();
            $total_discount_on_product = 0;
            $cart = \App\Utils\CartManager::get_cart();
            $cart_group_ids = \App\Utils\CartManager::get_cart_group_ids();
            $shipping_cost = \App\Utils\CartManager::get_shipping_cost();
            $get_shipping_cost_saved_for_free_delivery = \App\Utils\CartManager::get_shipping_cost_saved_for_free_delivery();
            $coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
            $coupon_dis = session()->has('coupon_discount') ? session('coupon_discount') : 0;
            if ($cart->count() > 0) {
                foreach ($cart as $key => $cartItem) {
                    $product_price_total += $cartItem['price'] * $cartItem['quantity'];
                    $total_tax += $cartItem['tax_model'] == 'exclude' ? ($cartItem['tax'] * $cartItem['quantity']) : 0;
                    $total_discount_on_product += $cartItem['discount'] * $cartItem['quantity'];
                }

                if (session()->missing('coupon_type') || session('coupon_type') != 'free_delivery') {
                    $total_shipping_cost = $shipping_cost - $get_shipping_cost_saved_for_free_delivery;
                } else {
                    $total_shipping_cost = $shipping_cost;
                }

                $total_offline_amount = $product_price_total + $total_tax + $total_shipping_cost - $coupon_dis - $total_discount_on_product - $order_wise_shipping_discount;
            }
            ?>

        <div class="p-3 my-3 rounded background-light-white">
            <h6 class="text-base mb-2">{{ $method->method_name }}</h6>
            <input type="hidden" value="offline_payment" name="payment_method">
            <input type="hidden" value="{{ $method->id }}" name="method_id">

            <div class="row">
                @foreach ($method->method_fields as $method_field)
                    <div class="col-6 pb-0">
                        <span class="font-medium">{{ translate($method_field['input_name']) }}</span> :
                        <span>{{ $method_field['input_data'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <h4 class="text-center py-3 font-medium">
            {{ translate('amount') }} : {{ \App\Utils\Helpers::currency_converter($total_offline_amount) }}
        </h4>

        <div class="row">
            @foreach ($method->method_informations as $informations)
                <div class="col-md-12 col-lg-6 mb-3">
                    <label class="mb-2 font-medium">
                        {{ translate($informations['customer_input']) }}
                        <span class="text-danger">{{ $informations['is_required'] == 1?'*':''}}</span>
                    </label>
                    <input type="text" class="form-control" name="{{ $informations['customer_input'] }}"
                           placeholder="{{ translate($informations['customer_placeholder']) }}" {{ $informations['is_required'] == 1?'required':''}}>
                </div>
            @endforeach

            <div class="col-12 mb-3">
                <label class="mb-2 font-medium">{{translate('payment_note')}}</label>
                <textarea name="payment_note" id="" class="form-control"
                          placeholder="{{translate('payment_note')}}"></textarea>
            </div>
        </div>

    </div>

    <div class="d-flex gap-10 justify-content-end pt-4">
        <button type="button" class="btn rounded btn-danger" data-bs-dismiss="modal">{{translate('close')}}</button>
        <button type="submit" class="btn rounded btn-base">{{translate('submit')}}</button>
    </div>

@else
    <div class="text-center py-5">
        <img loading="lazy" class="pt-5" src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}"
             alt="{{ translate('offline_payments') }}">
        <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method_first') }}</p>
    </div>
@endif
