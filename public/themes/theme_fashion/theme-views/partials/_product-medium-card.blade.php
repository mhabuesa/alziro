@php($overallRating = getOverallRating($product->reviews))
<div class="product-card">
    <div class="img">
        <a href="{{route('product',$product->slug)}}" class="d-block h-100">
            <img loading="lazy" class="w-100" alt="{{ translate('product') }}"
                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
        </a>
        @if (isset($product->created_at) && $product->created_at->diffInMonths(\Carbon\Carbon::now()) < 1)
            <span class="badge badge-title z-2">{{translate('new')}}</span>
        @endif
        @php($url = Illuminate\Support\Str::startsWith(request()->url(), url('product/')))
        <div class="hover-content d-flex justify-content-{{$url == true ? 'between':'end'}}">
            @if (($url) == true )
                <a href="javascript:"
                   title="{{isset($product->category) ? $product->category->name : '' }}">{{ \Illuminate\Support\Str::limit(isset($product->category) ? $product->category->name:'', 16) }}</a>
            @endif
            <div class="d-flex flex-wrap column-gap-3">
                @if (($url) != true )
                    <a href="javascript:" data-id="{{$product->id}}" class="d-inline-flex quickView_action">
                        <i class="bi bi-eye"></i>
                    </a>
                @endif
                @php($wishlist = count($product->wishList)>0 ? 1 : 0)
                <a href="javascript:" class="d-inline-flex wish-icon addWishlist_function_view_page"
                   data-id="{{$product->id}}">
                    <i class="wishlist_{{$product->id}} bi {{($wishlist == 1?'bi-heart-fill text-danger':'bi-heart')}}"></i>
                </a>
                @php($compare_list = count($product->compareList)>0 ? 1 : 0)
                <a href="javascript:" class="d-inline-flex wish-icon addCompareList_view_page"
                   data-id="{{$product['id']}}">
                    <i class="bi bi-shuffle compare_list_icon-{{$product['id']}}"></i>
                </a>

                @if (($url) != true )
                    @if (json_decode($product->variation) != null)
                        <span class="btn add-to-cart-plus-btn wish-icon">
                            <a href="javascript:" data-id="{{$product['id']}}" class="quickView_action">
                                <i class="bi bi-plus"></i>
                            </a>
                        </span>
                    @else
                        <span class="btn add-to-cart-plus-btn wish-icon">
                            @php($product_card_gen_id=rand(11111,99999))
                            <form class="cart add-to-cart-form-{{$product['id']}}" action="{{ route('cart.add') }}"
                                  id="add-to-cart-form-{{$product_card_gen_id}}"
                                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                                  data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="number" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}"
                                       class="product_quantity__qty" hidden>
                            </form>
                            <a href="javascript:" class="store_vacation_check_function"
                               data-id="{{ $product['id'] }}"
                               data-added_by="{{ $product['added_by'] }}"
                               data-user_id="{{ $product['user_id'] }}"
                               data-action_url="{{ route('ajax-shop-vacation-check') }}"
                               data-product_cart_id="{{ $product_card_gen_id }}"
                            >
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
            <a href="{{route('product',$product->slug)}}"
               title="{{ $product['name'] }}">{{ \Illuminate\Support\Str::limit($product['name'], 18) }}</a>
        </h6>
        <div class="d-flex flex-wrap row-gap-1 align-items-center column-gap-2 text-capitalize">
            <h4 class="price">
                <span>{{\App\Utils\Helpers::currency_converter($product->unit_price-\App\Utils\Helpers::get_product_discount($product,$product->unit_price))}}</span>
                @if($product->discount > 0)
                    <del>{{\App\Utils\Helpers::currency_converter($product->unit_price)}}</del>
                @endif
            </h4>

            @if(($product['product_type'] == 'physical'))
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
                @if ($i <= (int)$overallRating[0])
                    <i class="bi bi-star-fill filled"></i>
                @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                    <i class="bi bi-star-half filled"></i>
                @else
                    <i class="bi bi-star-fill"></i>
                @endif
            @endfor
        </div>
    </div>
</div>

