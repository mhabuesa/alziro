@php($overallRating = getOverallRating($product->reviews))
<div class="product-card">
    <div class="img">
        <a href="{{ route('product', $product->slug) }}" class="d-block h-100">
            <img loading="lazy" class="w-100" alt="{{ translate('product') }}"
                src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/' . $product['thumbnail'], type: 'product') }}">
        </a>
        @if (isset($product->created_at) && $product->created_at->diffInMonths(\Carbon\Carbon::now()) < 1)
            <span class="badge badge-title z-2">{{ translate('new') }}</span>
        @endif
        @php($url = Illuminate\Support\Str::startsWith(request()->url(), url('product/')))
        <div class="hover-content d-flex justify-content-{{ $url == true ? 'between' : 'end' }}">
            @if ($url == true)
                <a href="javascript:"
                    title="{{ isset($product->category) ? $product->category->name : '' }}">{{ \Illuminate\Support\Str::limit(isset($product->category) ? $product->category->name : '', 16) }}</a>
            @endif
            <div class="d-flex flex-wrap column-gap-3">
                @if ($url != true)
                    <a href="javascript:" data-id="{{ $product->id }}" class="d-inline-flex quickView_action">
                        <i class="bi bi-eye"></i>
                    </a>
                @endif
                @php($wishlist = count($product->wishList) > 0 ? 1 : 0)
                <a href="javascript:" class="d-inline-flex wish-icon addWishlist_function_view_page"
                    data-id="{{ $product->id }}">
                    <i
                        class="wishlist_{{ $product->id }} bi {{ $wishlist == 1 ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                </a>
                @php($compare_list = count($product->compareList) > 0 ? 1 : 0)
                <a href="javascript:" class="d-inline-flex wish-icon addCompareList_view_page"
                    data-id="{{ $product['id'] }}">
                    <i class="bi bi-shuffle compare_list_icon-{{ $product['id'] }}"></i>
                </a>

                @if ($url != true)
                    @if (json_decode($product->variation) != null)
                        <span class="btn add-to-cart-plus-btn wish-icon">
                            <a href="javascript:" data-id="{{ $product['id'] }}" class="quickView_action">
                                <i class="bi bi-plus"></i>
                            </a>
                        </span>
                    @else
                        <span class="btn add-to-cart-plus-btn wish-icon">
                            @php($product_card_gen_id = rand(11111, 99999))
                            <form class="cart add-to-cart-form-{{ $product['id'] }}" action="{{ route('cart.add') }}"
                                id="add-to-cart-form-{{ $product_card_gen_id }}"
                                data-errormessage="{{ translate('please_choose_all_the_options') }}"
                                data-outofstock="{{ translate('sorry') . ', ' . translate('out_of_stock') }}.">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="number" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}"
                                    class="product_quantity__qty" hidden>
                            </form>
                            <a href="javascript:" class="store_vacation_check_function" data-id="{{ $product['id'] }}"
                                data-added_by="{{ $product['added_by'] }}" data-user_id="{{ $product['user_id'] }}"
                                data-action_url="{{ route('ajax-shop-vacation-check') }}"
                                data-product_cart_id="{{ $product_card_gen_id }}">
                                <i class="bi bi-plus"></i>
                            </a>
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="cont">
        <h6 class="title">
            <a href="{{ route('product', $product->slug) }}"
                title="{{ $product['name'] }}">{{ \Illuminate\Support\Str::limit($product['name'], 18) }}</a>
        </h6>
        <div class="d-flex flex-wrap row-gap-1 align-items-center column-gap-2 text-capitalize">
            <h4 class="price">
                <span>{{ \App\Utils\Helpers::currency_converter($product->unit_price - \App\Utils\Helpers::get_product_discount($product, $product->unit_price)) }}</span>
                @if ($product->discount > 0)
                    <del>{{ \App\Utils\Helpers::currency_converter($product->unit_price) }}</del>
                @endif
            </h4>

            @if ($product['product_type'] == 'physical')
                @if ($product['current_stock'] <= 0)
                    <span class="status text-danger">{{ translate('out_of_stock') }}</span>
                @elseif ($product['current_stock'] <= $web_config['products_stock_limit'])
                    <span class="status">{{ translate('limited_Stock') }}</span>
                @else
                    <span class="status">{{ translate('in_stock') }}</span>
                @endif
            @else
                <span class="status">{{ translate('in_stock') }}</span>
            @endif
        </div>
        <div class="rating">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= (int) $overallRating[0])
                    <i class="bi bi-star-fill filled"></i>
                @elseif ($overallRating[0] != 0 && $i <= (int) $overallRating[0] + 1.1 && $overallRating[0] > ((int) $overallRating[0]))
                    <i class="bi bi-star-half filled"></i>
                @else
                    <i class="bi bi-star-fill"></i>
                @endif
            @endfor
        </div>
        <?php $product_card_gen_id = rand(11111, 99999); ?>
        <a href="javascript:" class="store_vacation_check_function btn btn-base w-100" data-id="{{ $product['id'] }}"
            data-added_by="{{ $product['added_by'] }}" data-user_id="{{ $product['user_id'] }}"
            data-action_url="{{ route('ajax-shop-vacation-check') }}"
            data-product_cart_id="{{ $product_card_gen_id }}">
            <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.5944 1.82891C2.52929 1.82401 2.43894 1.82357 2.2695 1.82357H1.16667C0.798477 1.82357 0.5 1.52509 0.5 1.1569C0.5 0.788714 0.798477 0.490238 1.16667 0.490238H2.2695C2.27578 0.490238 2.28207 0.490237 2.28835 0.490236C2.43146 0.490218 2.57315 0.490201 2.69458 0.49935C2.82971 0.509531 2.9867 0.533446 3.14911 0.605892C3.37767 0.707848 3.57293 0.872077 3.71254 1.07979C3.81174 1.22739 3.8622 1.37796 3.89538 1.50934C3.9252 1.62741 3.94946 1.76701 3.97397 1.908C3.97504 1.91419 3.97612 1.92039 3.9772 1.92658L4.13605 2.83996L12.812 3.09678C13.152 3.10683 13.4458 3.11552 13.6878 3.14217C13.9436 3.17034 14.1971 3.2233 14.4404 3.35289C14.809 3.54925 15.1068 3.85606 15.2922 4.23028C15.4145 4.47725 15.46 4.73227 15.4805 4.98876C15.5 5.23141 15.5 5.52534 15.5 5.86548V5.91464C15.5 6.23429 15.5 6.51156 15.4821 6.74149C15.4631 6.98533 15.4213 7.22752 15.3095 7.46483C15.1395 7.82604 14.8653 8.12809 14.5221 8.33212C14.2967 8.46617 14.0597 8.53118 13.8188 8.57357C13.5917 8.61353 13.3157 8.64023 12.9975 8.67101L6.50826 9.299C6.18213 9.33058 5.89956 9.35793 5.66381 9.36208C5.41403 9.36647 5.1636 9.34707 4.91219 9.25471C4.53514 9.1162 4.20854 8.86737 3.97491 8.54061C3.81913 8.32273 3.73395 8.08644 3.67188 7.84445C3.6133 7.61606 3.56467 7.33638 3.50854 7.01357L2.66358 2.15503C2.63455 1.9881 2.61864 1.89915 2.60265 1.83585C2.60208 1.8336 2.60153 1.83146 2.601 1.82944C2.59891 1.82926 2.59672 1.82909 2.5944 1.82891ZM4.36923 4.18078L4.81796 6.76097C4.8795 7.1148 4.9195 7.34199 4.96341 7.51319C5.00529 7.67648 5.03842 7.7356 5.05953 7.76512C5.1374 7.87404 5.24627 7.95699 5.37195 8.00316C5.40602 8.01567 5.47181 8.03192 5.64036 8.02895C5.81708 8.02584 6.04674 8.0041 6.40421 7.96951L12.8451 7.3462C13.1941 7.31243 13.4178 7.2903 13.5877 7.26041C13.7495 7.23194 13.8099 7.20438 13.8407 7.18607C13.9551 7.11806 14.0465 7.01737 14.1032 6.89697C14.1184 6.86454 14.1401 6.80177 14.1528 6.63798C14.1662 6.46603 14.1667 6.24115 14.1667 5.89057C14.1667 5.51824 14.1662 5.27833 14.1515 5.09544C14.1374 4.92044 14.1136 4.85483 14.0974 4.82211C14.0356 4.69737 13.9363 4.5951 13.8135 4.52964C13.7812 4.51247 13.7164 4.48671 13.5418 4.46749C13.3595 4.4474 13.1197 4.4398 12.7475 4.42879L4.36923 4.18078Z"
                    fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M6.16667 11.4902C5.79848 11.4902 5.5 11.7887 5.5 12.1569C5.5 12.5251 5.79848 12.8236 6.16667 12.8236C6.53486 12.8236 6.83333 12.5251 6.83333 12.1569C6.83333 11.7887 6.53486 11.4902 6.16667 11.4902ZM4.16667 12.1569C4.16667 11.0523 5.0621 10.1569 6.16667 10.1569C7.27124 10.1569 8.16667 11.0523 8.16667 12.1569C8.16667 13.2615 7.27124 14.1569 6.16667 14.1569C5.0621 14.1569 4.16667 13.2615 4.16667 12.1569Z"
                    fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12.5 11.4902C12.1318 11.4902 11.8333 11.7887 11.8333 12.1569C11.8333 12.5251 12.1318 12.8236 12.5 12.8236C12.8682 12.8236 13.1667 12.5251 13.1667 12.1569C13.1667 11.7887 12.8682 11.4902 12.5 11.4902ZM10.5 12.1569C10.5 11.0523 11.3954 10.1569 12.5 10.1569C13.6046 10.1569 14.5 11.0523 14.5 12.1569C14.5 13.2615 13.6046 14.1569 12.5 14.1569C11.3954 14.1569 10.5 13.2615 10.5 12.1569Z"
                    fill="currentColor"></path>
            </svg> Add to Cart
        </a>
    </div>
</div>
