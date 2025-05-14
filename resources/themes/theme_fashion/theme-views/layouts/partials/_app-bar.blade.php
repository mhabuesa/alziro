<ul class="list-unstyled d-flex justify-content-around gap-3 mb-0 position-relative">
    <li>
        <a href="{{route('home')}}"
           class="d-flex align-items-center {{ (Request::is('/') || Request::is('home')) ? 'active':''}} flex-column gap-1 py-3">
            <i class="bi bi-house-door fs-18"></i>
            <span>{{translate('home')}}</span>
        </a>
    </li>
    @if(auth('customer')->check())
        <li>
            <a href="{{ route('wishlists') }}"
               class="d-flex align-items-center {{ Request::is('wishlists') ? 'active' : '' }} flex-column gap-1 py-3">
                <div class="position-relative">
                    <i class="bi bi-heart fs-18"></i>
                    <span class="app-count">
                        <span class="wishlist_count_status">
                            {{ session()->has('wish_list') ? count(session('wish_list')) : 0 }}
                        </span>
                    </span>
                </div>
                <span>{{ translate('wishlist') }}</span>
            </a>
        </li>
    @else
        <li>
            <a href="javascript:"
               class="d-flex align-items-center flex-column gap-1 py-3 customer_login_register_modal">
                <div class="position-relative">
                    <i class="bi bi-heart fs-18"></i>
                    <span class="app-count">{{ '0' }}</span>
                </div>
                <span>{{ translate('wishlist') }}</span>
            </a>
        </li>
    @endif

    <li>
        @php($cart=\App\Utils\CartManager::get_cart())
        @if($cart->count() > 0)
            @php($sub_total=0)
            @php($total_tax=0)
            @foreach($cart as  $cartItem)
                @php($sub_total+=($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])
                @php($total_tax+=$cartItem['tax']*(int)$cartItem['quantity'])
            @endforeach
        @endif
        <div class="dropup position-static d-xl-none">
            <a href="javascript:" class="d-flex align-items-center flex-column gap-1 py-3" data-toggle="collapse"
               data-target="cart_dropdown">
                <div class="position-relative">
                    <i class="bi bi-bag fs-18"></i>
                    <span class="btn-status app-count">{{$cart->count()}}</span>
                </div>
                <span>{{translate('cart')}}</span>
            </a>

            <ul class="dropdown-menu scrollY-60 p-3 min-vw-100" id="cart_dropdown">
                @if($cart->count() > 0)
                    @include('theme-views.layouts.partials._cart-data',['cart'=>$cart])
                    <li>
                        <div class="app-cart-subtotal">
                            <span class="text-base">{{translate('subtotal')}}</span>
                            <span class="cart_total_amount">{{\App\Utils\Helpers::currency_converter($sub_total)}}</span>
                        </div>

                        <div class="d-flex gap-3 mt-3">
                            @if($web_config['guest_checkout_status'] || auth('customer')->check())
                                <a href="{{route('shop-cart')}}"
                                   class="btn btn-outline-base flex-grow-1">{{translate('view_all_cart_items')}}</a>
                                <a href="{{route('checkout-details')}}"
                                   class="btn btn-base flex-grow-1">{{translate('go_to_checkout')}}</a>
                            @else
                                <a href="javascript:"
                                   class="btn btn-outline-base flex-grow-1 customer_login_register_modal">{{translate('view_all_cart_items')}}</a>
                                <a href="javascript:"
                                   class="btn btn-base flex-grow-1 customer_login_register_modal">{{translate('go_to_checkout')}}</a>
                            @endif
                        </div>
                    </li>
                @else
                    <div class="widget-cart-item">
                        <h6 class="text-danger text-center m-0 p-2">
                            <i class="fa fa-cart-arrow-down"></i> {{translate('empty_Cart')}}
                        </h6>
                    </div>
                @endif
            </ul>
        </div>
    </li>

    @if(auth('customer')->check())
        <li>
            <a href="{{ route('product-compare.index') }}"
               class="d-flex align-items-center {{ Request::is('compare-list') ? 'active' : '' }} flex-column gap-1 py-3">
                <div class="position-relative">
                    <i class="bi bi-repeat fs-18"></i>
                    <span class="app-count compare_list_count_status top-0">
                        {{ session()->has('compare_list') ? count(session('compare_list')) : 0}}
                    </span>
                </div>
                <span>{{ translate('compare') }}</span>
            </a>
        </li>
    @else
        <li>
            <a href="javascript:"
               class="d-flex align-items-center text-dark flex-column gap-1 py-3 customer_login_register_modal">
                <div class="position-relative">
                    <i class="bi bi-repeat fs-18"></i>
                </div>
                <span>{{ translate('compare') }}</span>
            </a>
        </li>
    @endif
</ul>
