@extends('theme-views.layouts.app')

@section('title', translate('cart_list') . ' | ' . $web_config['name']->value . ' ' . translate('ecommerce'))

@push('css_or_js')
    <style>
        .cnt_full {
            display: block;
            margin: 20px 10px;
        }

        .cnt_min {
            display: inline-block;
            width: 100px;
            margin: 10px;
            height: 80px;
            position: relative;
            padding: 0 2%;
            border-radius: 30px;
        }

        .cnt_min input[type="radio"] {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .selected_img {
            pointer-events: none;
            width: 100%;
            height: 100%;
            border-radius: 25%;
        }

        .cnt_min input[type="radio"]:checked~.selected_img {
            border: solid 4px green;
            box-shadow: 0px 1px 4px 0px #ccc;
            border-radius: 20px;
        }

        .cursor-pointer {
            cursor: pointer !important;
        }
    </style>
@endpush

@section('content')
    <section class="breadcrumb-section pt-20px">
        <div class="container">
            <div class="section-title mb-3">
                <div
                    class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{ route('home') }}">{{ translate('home') }}</a>
                        </li>
                        <li>
                            <a href="{{ url()->current() }}" class="text-base custom-text-link">
                                {{ translate('cart') }}
                            </a>
                        </li>
                    </ul>
                    <div class="ms-auto ms-md-0">
                        @if (auth('customer')->check())
                            <a href="{{ route('wishlists') }}" class="text-base custom-text-link">
                                {{ translate('check_All_Wishlist') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content d-flex flex-column gap-3 py-3 mb-3" id="cart-summary">
        @php
            $shippingMethod = getWebConfig(name: 'shipping_method');
            $cart = \App\Models\Cart::where([
                'customer_id' => auth('customer')->check() ? auth('customer')->id() : session('guest_id'),
            ])
                ->with(['seller', 'allProducts.category'])
                ->get()
                ->groupBy('cart_group_id');
        @endphp

        @if ($cart->count() > 0)
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <div class="container">
                    <div class="row g-4">
                        <div class="col-lg-8 pe-lg-0">
                            <div class="cart-title-area text-capitalize mb-2">
                                <h6 class="title">{{ translate('all_cart_product_list') }}
                                    <span class="btn-status">({{ count(\App\Utils\CartManager::get_cart()) }})</span>
                                </h6>
                                <span type="button" class="text-text-2 route_alert_function"
                                    data-routename="{{ route('cart.remove-all') }}"
                                    data-message="{{ translate('want_to_clear_all_cart?') }}"
                                    data-typename="">{{ translate('remove_all') }}</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table __table cart-list-table-custom">
                                    <thead class="word-nobreak">
                                        <tr>
                                            <th>
                                                <label class="form-check m-0">
                                                    <span class="form-check-label">{{ translate('product') }}</span>
                                                </label>
                                            </th>
                                            <th class="text-center">
                                                {{ translate('discount') }}
                                            </th>
                                            <th class="text-center">
                                                {{ translate('quantity') }}
                                            </th>
                                            <th class="text-center">
                                                {{ translate('total') }}
                                            </th>
                                            <th class="text-center">
                                                x
                                            </th>
                                        </tr>
                                    </thead>
                                </table>

                                <span class="cart">
                                    <table class="table __table vertical-middle cart-list-table-custom">
                                        <tbody>

                                            @foreach ($cart as $group_key => $group)
                                                @php
                                                    $physical_product = false;
                                                    $total_shipping_cost = 0;
                                                    foreach ($group as $row) {
                                                        if ($row->product_type == 'physical') {
                                                            $physical_product = true;
                                                        }
                                                        if (
                                                            $row->product_type == 'physical' &&
                                                            $row->shipping_type != 'order_wise'
                                                        ) {
                                                            $total_shipping_cost += $row->shipping_cost;
                                                        }
                                                    }
                                                @endphp
                                                @php($total_amount = 0)
                                                @foreach ($group as $cart_key => $cartItem)
                                                    @php($product = $cartItem->allProducts)

                                                    @if (!$product)
                                                        @php($product_null_status = 1)
                                                    @endif
                                                    <tr id="cartRow_{{ $cartItem->id }}">
                                                        <td>
                                                            <input type="text" name="id" value="" hidden>
                                                            <input type="text" name="product_id" value="" hidden>
                                                            <div class="cart-product  align-items-center">
                                                                <label class="form-check position-relative overflow-hidden">
                                                                    @if ($product->status == 1)
                                                                        <img loading="lazy"
                                                                            alt="{{ translate('product') }}"
                                                                            src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/' . $product['thumbnail'], type: 'product') }}">
                                                                    @elseif($product->status == 0)
                                                                        <img loading="lazy"
                                                                            alt="{{ translate('product') }}"
                                                                            src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/' . $product['thumbnail'], type: 'product') }}">
                                                                        <span
                                                                            class="temporary-closed position-absolute text-center p-2">
                                                                            <span>{{ translate('not_available') }}</span>
                                                                        </span>
                                                                    @else
                                                                        <img loading="lazy"
                                                                            src="{{ theme_asset('assets/img/image-place-holder.png') }}"
                                                                            alt="{{ translate('product') }}">
                                                                        <span
                                                                            class="temporary-closed position-absolute text-center p-2">
                                                                            <span>{{ translate('not_available') }}</span>
                                                                        </span>
                                                                    @endif
                                                                </label>

                                                                <div class="cont  class="cont
                                                                    {{ $product->status == 0 ? 'blur-section' : '' }}"">
                                                                    <a href="{{ $product->status == 1 ? route('product', $product['slug']) : 'javascript:' }}"
                                                                        class="name text-title">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                    <div class="d-flex column-gap-1">
                                                                        <span>{{ translate('price') }}</span>
                                                                        <span>:</span>
                                                                        <strong
                                                                            class="product_price{{ $cartItem['id'] }}">{{ \App\Utils\Helpers::currency_converter($cartItem->price) }}</strong>
                                                                    </div>
                                                                    <div class="d-flex column-gap-1">
                                                                        @if (isset($product->category))
                                                                            <span>{{ translate('category') }} </span>
                                                                            <span>:</span>
                                                                            <strong>{{ isset($product->category) ? $product->category->name : '' }}</strong>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($cartItem['discount'] > 0)
                                                                <span id="item_discount_{{ $cartItem['id'] }}"
                                                                    class="badge badge-soft-base">
                                                                    -{{ \App\Utils\Helpers::currency_converter($cartItem['discount'] * $cartItem['quantity']) }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="badge text-capitalize badge-soft-secondary discount{{ $cartItem['id'] }}">{{ translate('no_discount') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @php($minimum_order = \App\Utils\ProductManager::get_product($cartItem['product_id']))

                                                            @if ($minimum_order)
                                                                <div class="quantity __quantity">
                                                                    <input type="number"
                                                                        class="quantity__qty cart-qty-input cart-quantity-web{{ $cartItem['id'] }} form-control cartQuantity{{ $cartItem['id'] }} updateCartQuantityList_cart_data"
                                                                        value="{{ $cartItem['quantity'] }}" name="quantity"
                                                                        id="cartQuantityWeb{{ $cartItem['id'] }}"
                                                                        data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                                        data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                                        data-action=""
                                                                        data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                                                    <div>
                                                                        <div class="quantity__plus cart-qty-btn updateCartQuantityList_cart_data"
                                                                            data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                                            data-cart="{{ $cartItem['id'] }}"
                                                                            data-value="1"
                                                                            data-price="{{ $cartItem['price'] }}"
                                                                            data-discount="{{ $cartItem['discount'] }}">
                                                                            <i class="bi bi-plus "></i>
                                                                        </div>

                                                                        <div class="quantity__minus cart-qty-btn"
                                                                            data-cart="{{ $cartItem['id'] }}"
                                                                            data-value="-1"
                                                                            data-price="{{ $cartItem['price'] }}"
                                                                            data-discount="{{ $cartItem['discount'] }}">
                                                                            <i id="minus_icon_{{ $cartItem['id'] }}"
                                                                                class="bi bi-dash-lg {{ $cartItem['quantity'] == 1 ? 'disabled' : '' }}"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="quantity __quantity">
                                                                    <input type="text"
                                                                        class="quantity__qty cart-qty-input cart-quantity-web{{ $cartItem['id'] }} form-control cartQuantity{{ $cartItem['id'] }}"
                                                                        name="quantity"
                                                                        id="cartQuantity{{ $cartItem['id'] }}"
                                                                        data-min="{{ $cartItem['quantity'] }}"
                                                                        value="{{ $cartItem['quantity'] }}" readonly>
                                                                    <div>
                                                                        <div class="cart-qty-btn disabled"
                                                                            title="{{ translate('product_not_available') }}">
                                                                            <i
                                                                                class="bi bi-exclamation-circle text-danger"></i>
                                                                        </div>
                                                                        <div class="cart-qty-btn updateCartQuantityList_cart_data"
                                                                            data-minorder="{{ $cartItem['quantity'] + 1 }}"
                                                                            data-cart="{{ $cartItem['id'] }}"
                                                                            data-value="-{{ $cartItem['quantity'] }}"
                                                                            data-action="delete">
                                                                            <i
                                                                                class="bi bi-trash3-fill text-danger fs-10}"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        @php($total_amount = $total_amount + ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'])
                                                        <td class="text-center" id="item_price_{{ $cartItem['id'] }}">
                                                            {{ \App\Utils\Helpers::currency_converter(($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity']) }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="cart-qty-btn" data-cart="{{ $cartItem['id'] }}"
                                                                data-value="0" data-action="delete">
                                                                <i id="delete_icon_{{ $cartItem['id'] }}"
                                                                    class="bi bi-trash3-fill text-danger fs-10"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </span>
                            </div>
                            <div class="card mt-5">
                                @php($shippingMethod = getWebConfig(name: 'shipping_method'))
                                @php($product_price_total = 0)
                                @php($total_tax = 0)
                                @php($total_shipping_cost = 0)
                                @php($order_wise_shipping_discount = \App\Utils\CartManager::order_wise_shipping_discount())
                                @php($total_discount_on_product = 0)
                                @php($cart = \App\Utils\CartManager::get_cart())
                                @php($cart_group_ids = \App\Utils\CartManager::get_cart_group_ids())
                                @php($shipping_cost = \App\Utils\CartManager::get_shipping_cost())
                                @php($get_shipping_cost_saved_for_free_delivery = \App\Utils\CartManager::get_shipping_cost_saved_for_free_delivery())
                                @php($coupon_dis = 0)
                                @if ($cart->count() > 0)
                                    @foreach ($cart as $key => $cartItem)
                                        @php($product_price_total += $cartItem['price'] * $cartItem['quantity'])
                                        @php($total_tax += $cartItem['tax_model'] == 'exclude' ? $cartItem['tax'] * $cartItem['quantity'] : 0)
                                        @php($total_discount_on_product += $cartItem['discount'] * $cartItem['quantity'])
                                    @endforeach

                                    @if (session()->missing('coupon_type') || session('coupon_type') != 'free_delivery')
                                        @php($total_shipping_cost = $shipping_cost - $get_shipping_cost_saved_for_free_delivery)
                                    @else
                                        @php($total_shipping_cost = $shipping_cost)
                                    @endif
                                @endif

                                <div class="my-3">
                                    <h4 class="text-capitalize text-center">Shipping Address</h4>
                                </div>

                                <div class="border-2 p-3">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Name of Recipient" name="name"
                                            value="{{ auth('customer')->user() ? auth('customer')->user()->name : '' }}"
                                            required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="d-flex">
                                        <div class="mb-3 w-50 me-3">
                                            <label for="phone" class="form-label">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="phone" class="form-control" id="phone"
                                                placeholder="Phone of Recipient" name="phone" required
                                                value="{{ auth('customer')->user() ? auth('customer')->user()->phone : '' }}"
                                                {{ !auth('customer')->user() ? '' : 'readonly' }}>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 w-50">
                                            <label for="email" class="form-label">Email <small
                                                    class="text-muted">(Optional)</small></label>
                                            <input type="email" class="form-control" id="email"
                                                placeholder="Email of Recipient" name="email">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="address" class="form-control" id="address"
                                            placeholder="Address of Recipient" name="address" required
                                            value="{{ auth('customer')->user() ? auth('customer')->user()->street_address : '' }}">
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Select Shipping Destination <span
                                                class="text-danger">*</span></label>
                                        <select name="destination" id="shipping_selector" class="form-control">
                                            <option value="inside_dhaka">Inside Dhaka</option>
                                            <option value="outside_dhaka">Outside Dhaka</option>
                                        </select>
                                        @error('destination')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="discount"
                                        value="{{ session()->has('coupon_discount') ? session('coupon_discount') : 0 }}">

                                    <input type="hidden" name="customer_id"
                                        value="{{ auth('customer')->check() ? auth('customer')->id() : session('guest_id') }}">

                                    <input type="hidden" name="total_amount"
                                        value="{{ $product_price_total + $total_tax + $total_shipping_cost - $coupon_dis - $total_discount_on_product - $order_wise_shipping_discount }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 ps-lg-4 ps-xl-5">
                            <div class="total-cost-wrapper">

                                @if ($cart->count() < 0)
                                    <span>{{ translate('empty_cart') }}</span>
                                @endif

                                <h6 class="text-center title font-medium letter-spacing-0 mb-20px text-capitalize">
                                    {{ translate('totals_cost') }}</h6>
                                <div class="total-cost-area">
                                    {{-- @if (auth('customer')->check() && !session()->has('coupon_discount'))
                                        @php($coupon_discount = 0)
                                        <div class="apply-coupon-form">
                                            <input type="text" class="form-control" id="promo-code"
                                                placeholder="{{ translate('apply_coupon_code') }}" autocomplete="off">
                                            <button type="button" class="btn badge-soft-base" id="apply-coupon-btn">
                                                {{ translate('apply') }}
                                            </button>
                                        </div>

                                        <!-- Hidden element to store route -->
                                        <span id="coupon-apply" data-url="{{ route('coupon.apply') }}"></span>

                                        @php($coupon_dis = 0)
                                    @endif --}}

                                    @if (auth('customer')->check() && !session()->has('coupon_discount'))
                                        @php($coupon_discount = 0)
                                        <div class="apply-coupon-form">
                                            <input type="text" class="form-control" id="promo-code"
                                                placeholder="{{ translate('apply_coupon_code') }}" autocomplete="off">
                                            <button type="button" class="btn badge-soft-base" id="apply-coupon-btn">
                                                {{ translate('apply') }}
                                            </button>
                                        </div>

                                        <!-- Hidden element to store route -->
                                        <span id="coupon-apply" data-url="{{ route('coupon.code.apply') }}"></span>

                                        @php($coupon_dis = 0)
                                    @endif

                                    <ul
                                        class="total-cost-info border-bottom-0 border-bottom-sm mt-20px mb-30px text-capitalize">
                                        <li>
                                            <span>Product Price</span>
                                            <span
                                                class="product_price">{{ \App\Utils\Helpers::currency_converter($product_price_total - $total_discount_on_product) }}</span>
                                        </li>

                                        @php($coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
                                        <li>
                                            <span>{{ translate('coupon_discount') }} </span>
                                            <span class="discount"
                                                data-coupon="{{ $coupon_discount + $order_wise_shipping_discount }}">{{ \App\Utils\Helpers::currency_converter($coupon_discount + $order_wise_shipping_discount) }}</span>

                                        </li>
                                        @php($coupon_dis = session('coupon_discount'))
                                        <li>
                                            <span>{{ translate('shipping_cost') }}</span>
                                            <span id="shipping_cost" data-cost="70">৳70.00</span>

                                        </li>
                                    </ul>
                                    <hr />
                                    <div class="">
                                        <h6
                                            class="d-flex justify-content-center gap-2 mb-2 justify-content-sm-between letter-spacing-0 font-semibold text-normal">
                                            <span>Total</span>
                                            <span
                                                class="total_price">{{ \App\Utils\Helpers::currency_converter($product_price_total + $total_tax + $total_shipping_cost - $coupon_dis - $total_discount_on_product - $order_wise_shipping_discount) }}</span>
                                        </h6>
                                        <ul class="total-cost-delivery-info mt-30px mb-32px">
                                            @php($refund_day_limit = getWebConfig(name: 'refund_day_limit'))
                                            @if ($refund_day_limit > 0)
                                                <li>
                                                    <img loading="lazy"
                                                        src="{{ theme_asset('assets/img/products/icons/delivery-charge.png') }}"
                                                        class="icons" alt="{{ translate('product') }}">
                                                    <div class="cont">
                                                        <div class="t-txt"><span
                                                                class="text-capitalize">{{ translate('product_refund_validity') }}</span>
                                                            <span>:</span>
                                                            <strong>{{ $refund_day_limit }}
                                                                {{ translate('days_after_delivery') }}</strong>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif

                                            <li>
                                                <img loading="lazy"
                                                    src="{{ theme_asset('assets/img/products/icons/warranty.png') }}"
                                                    class="icons" alt="{{ translate('product') }}">
                                                <div class="cont">
                                                    <div class="t-txt text-capitalize">
                                                        <span>{{ translate('Order_cancelation_availablity') }} </span>
                                                        <span>:</span>
                                                        <strong>{{ translate('before_the_vendor_confirms_the_order') }}
                                                        </strong>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="mt-3">
                                            <div class="space-y-4">
                                                <p class="text-lg font-semibold text-gray-700">Payment Method</p>

                                                <div class="cnt_full d-flex justify-content-around">
                                                    <div class="cnt_min">
                                                        <input type="radio" value="cod" name="payment_method"
                                                            checked /><img
                                                            src="https://www.shutterstock.com/image-vector/cash-on-delivery-tags-collection-600nw-1537188653.jpg"
                                                            alt="COD" class="selected_img cursor-pointer">
                                                    </div>
                                                    <div class="cnt_min">
                                                        <input type="radio" value="bkash" name="payment_method" /><img
                                                            src="https://downloadr2.apkmirror.com/wp-content/uploads/2022/08/84/62f92578037f0.png"
                                                            alt="BKash" class="selected_img cursor-pointer">
                                                    </div>
                                                    {{-- <div class="cnt_min">
                                                        <input type="radio" value="nagad" name="payment_method" /><img
                                                            src="https://play-lh.googleusercontent.com/EQC9NtbtRvsNcU1r_5Dr8pWm3hPfN3OjGjzkOqzCEPDJvqBGKyfU9-a2ajNtcrIg1rs"
                                                            alt="Nagad" class="selected_img cursor-pointer">
                                                    </div> --}}
                                                </div>
                                            </div>

                                        </div>
                                        <button type="submit"
                                            class="btn btn-base w-100 justify-content-center">{{ translate('proceed_to_checkout') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="d-flex justify-content-center align-items-center">
                <h4 class="text-danger text-capitalize">{{ translate('cart_empty') }}</h4>
            </div>
        @endif
    </main>


    <section class="py-4">
        <div class="container">

            @if (!empty($web_config['features_section']['features_section_top']))
                <div class="section-title text-center pt-xl-2">
                    <h2 class="title">{{ json_decode($web_config['features_section']['features_section_top'])->title }}
                    </h2>
                    <p>
                        {{ json_decode($web_config['features_section']['features_section_top'])->subtitle }}
                    </p>
                </div>
            @endif

            @if (!empty($web_config['features_section']['features_section_middle']))
                <div class="table-responsive">
                    <div class="how-we-work-grid">
                        @php($totalFeatures = count(json_decode($web_config['features_section']['features_section_middle'])))
                        @foreach (json_decode($web_config['features_section']['features_section_middle']) as $key => $item)
                            <div class="how-to-card max-width-unset-custom">
                                <div class="how-to-icon">
                                    {{ $key + 1 < 10 ? '0' . $key + 1 : $key + 1 }}
                                </div>
                                <div class="how-to-cont">
                                    <h5 class="title">{{ $item->title }}</h5>
                                    <div>{{ $item->subtitle }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if (!empty($web_config['features_section']['features_section_bottom']))
        <div class="support-section">
            <div class="container">
                <div class="support-wrapper">
                    @foreach (json_decode($web_config['features_section']['features_section_bottom']) as $item)
                        <div class="support-card mb-4">
                            <div class="support-card-inner">
                                <div class="icon">
                                    <img loading="lazy"
                                        src="{{ getValidImage(path: 'storage/app/public/banner/' . $item->icon, type: 'banner') }}"
                                        alt="{{ translate('banner') }}" class="icon">
                                </div>
                                <h6 class="title">{{ $item->title }}</h6>
                                <div>{{ $item->subtitle }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingSelector = document.getElementById('shipping_selector');
            const shippingCostEl = document.getElementById('shipping_cost');

            // Quantity change buttons
            document.querySelectorAll('.cart-qty-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    let cartId = this.dataset.cart;
                    let value = parseInt(this.dataset.value || 0);
                    let price = parseFloat(this.dataset.price || 0);
                    let discount = parseFloat(this.dataset.discount || 0);
                    let qtyInput = document.getElementById('cartQuantityWeb' + cartId);
                    let quantity = parseInt(qtyInput?.value || 0);

                    if (this.dataset.action === 'delete') {
                        let cartRowId = 'cartRow_' + cartId;

                        Swal.fire({
                            title: '{{ translate('are_you_sure') }}',
                            text: '{{ translate('are_you_sure_you_want_to_delete_this_item?') }}',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '{{ translate('yes_delete_it') }}',
                            cancelButtonText: '{{ translate('cancel') }}'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let rowElement = document.getElementById(cartRowId);
                                if (rowElement) {
                                    rowElement.remove();
                                }

                                updateSubtotal();
                                updateTotalPrice();
                                deleteCartAjax(cartId);
                            }
                        });

                        return;
                    }

                    quantity += value;
                    if (quantity < 1) return;

                    qtyInput.value = quantity;

                    let newPrice = (price - discount) * quantity;
                    let newDiscount = discount * quantity;

                    document.querySelector('#item_price_' + cartId).innerText = '৳' +
                        currencyFormat(newPrice);
                    document.querySelector('#item_discount_' + cartId).innerText = '- ৳' +
                        currencyFormat(newDiscount);

                    let minusIcon = document.querySelector(`#minus_icon_${cartId}`);
                    if (quantity === 1) {
                        minusIcon.classList.add('disabled');
                    } else {
                        minusIcon.classList.remove('disabled');
                    }

                    updateSubtotal();
                    updateTotalPrice();
                    updateCartAjax(cartId, quantity);
                });
            });

            if (shippingSelector) {
                shippingSelector.addEventListener('change', updateShippingCost);
                updateShippingCost(); // run on page load
            }

            function currencyFormat(amount) {
                return parseFloat(amount).toFixed(2);
            }

            function updateSubtotal() {
                let subtotal = 0;
                document.querySelectorAll('[id^="item_price_"]').forEach(function(el) {
                    let priceText = el.innerText.replace(/[^0-9.]/g, '');
                    subtotal += parseFloat(priceText) || 0;
                });

                let subTotalElement = document.querySelector('.product_price');
                if (subTotalElement) {
                    subTotalElement.innerText = '৳' + currencyFormat(subtotal);
                }
            }

            function updateTotalPrice() {
                let subtotal = 0;
                document.querySelectorAll('[id^="item_price_"]').forEach(function(el) {
                    subtotal += parseFloat(el.innerText.replace(/[^0-9.]/g, '')) || 0;
                });

                let couponDiscount = parseFloat(document.querySelector('.discount')?.dataset.coupon || 0);
                let shippingCost = parseFloat(shippingCostEl?.dataset.cost || 0);

                let total = subtotal - couponDiscount + shippingCost;

                let totalElement = document.querySelector('.total_price');
                if (totalElement) {
                    totalElement.innerText = '৳' + currencyFormat(total);
                }
            }

            function updateShippingCost() {
                let shippingCost = 0;

                if (shippingSelector.value === 'inside_dhaka') {
                    shippingCost = 70;
                } else if (shippingSelector.value === 'outside_dhaka') {
                    shippingCost = 120;
                }

                shippingCostEl.innerText = '৳' + currencyFormat(shippingCost);
                shippingCostEl.dataset.cost = shippingCost;

                updateTotalPrice();
            }

            function updateCartAjax(cartId, quantity) {
                fetch("{{ route('cart.updateQuantity') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            cart_id: cartId,
                            quantity: quantity
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to update cart: ' + data.message);
                        }
                        if (data.reload) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('AJAX error:', error);
                        alert('Something went wrong while updating the cart.');
                    });
            }

            function deleteCartAjax(cartId) {
                fetch("{{ route('cart.delete') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            cart_id: cartId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to delete item: ' + data.message);
                        }
                        if (data.reload) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Delete AJAX error:', error);
                        alert('Something went wrong while deleting the item.');
                    });
            }
        });
    </script>

    <script>
        document.getElementById("apply-coupon-btn").addEventListener("click", function() {
            let code = document.getElementById("promo-code").value.trim();
            let responseDiv = document.getElementById("coupon-response");
            let url = document.getElementById("coupon-apply").dataset.url;

            if (!code) {
                responseDiv.innerHTML = `<span class="text-danger">Please enter a coupon code.</span>`;
                return;
            }

            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 1) {
                        responseDiv.innerHTML = `<span class="text-success">${data.messages[0]}</span>`;

                        // Optionally update values
                        if (document.getElementById("coupon-discount")) {
                            document.getElementById("coupon-discount").innerText = data.discount;
                        }
                        if (document.getElementById("cart-total")) {
                            document.getElementById("cart-total").innerText = data.total;
                        }

                        // Optionally reload page to update other session-dependent data
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        responseDiv.innerHTML = `<span class="text-danger">${data.messages[0]}</span>`;
                    }
                })
                .catch(error => {
                    console.error("Coupon error:", error);
                    responseDiv.innerHTML = `<span class="text-danger">Something went wrong.</span>`;
                });
        });
    </script>
@endpush
