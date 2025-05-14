@php($cart=\App\Utils\CartManager::get_cart())
@if($cart->count() > 0)
    @php($sub_total=0)
    @php($total_tax=0)
    @foreach($cart as  $cartItem)
        @php($sub_total+=($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])
        @php($total_tax+=$cartItem['tax']*(int)$cartItem['quantity'])
    @endforeach
@endif
<div class="d-none d-md-block dropdown">
    <a href="javascript:" data-bs-toggle="dropdown">
        <div class="position-relative mt-1 px-8px">
            <i class="bi bi-cart-dash"></i>
            <span class="btn-status">{{$cart->count()}}</span>
        </div>
    </a>
    <div class="dropdown-menu __dropdown-menu __header-cart-menu">
        @if($cart->count() > 0)
            <ul class="header-cart custom-header-cart __table">
                @include('theme-views.layouts.partials._cart-data',['cart'=>$cart])
            </ul>
            <div class="header-cart-subtotal">
                <span class="text-base">{{translate('subtotal')}}</span>
                <span class="cart_total_amount">{{\App\Utils\Helpers::currency_converter($sub_total)}}</span>
            </div>
            @if($web_config['guest_checkout_status'] || auth('customer')->check())
                <div class="mx-8px">
                    <a href="{{route('checkout-details')}}"
                       class="btn header-cart-btn btn-base">{{translate('go_to_checkout')}}</a>
                </div>
                <div class="text-center">
                    <a href="{{route('shop-cart')}}"
                       class="view-all justify-content-center">{{translate('view_all_cart_items')}}</a>
                </div>
            @else
                <div class="px-2">
                    <a href="javascript:"
                       class="btn header-cart-btn btn-base customer_login_register_modal">{{translate('go_to_checkout')}}</a>
                </div>
                <div class="text-center">
                    <a href="javascript:"
                       class="view-all justify-content-center customer_login_register_modal">{{translate('View_all_cart_items')}}</a>
                </div>
            @endif
        @else
            <div class="widget-cart-item">
                <h6 class="text-danger text-center m-0 p-2">
                    <i class="fa fa-cart-arrow-down"></i>
                    {{ translate('empty_Cart') }}
                </h6>
            </div>
        @endif
    </div>
</div>
