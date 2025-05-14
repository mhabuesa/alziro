@if ($featured_deals->count() > 0)
    <section class="signature-product-section pb-0">
        <div class="overflow-hidden">
            <div class="section-title-2">
                <span class="shapetitle text-capitalize">{{ translate('Feature_Deal') }}</span>
                <h2 class="title text-capitalize">{{ translate('Feature_Deal_for_this_season') }}</h2>
            </div>
        </div>
        <div class="signature-product-section-inner">
            <div class="container">
                <div class="signature-wrapper">
                    <div class="signature-products-slider-wrapper">
                        <div class="owl-theme owl-carousel signature-products-slider">
                            @foreach ($featured_deals as $key=>$product)
                                <div class="signature-product @if($key % 2 == 1) even-item @endif">

                                    <div class="product-card">
                                        <div class="product-card-inner">
                                            <div class="img">
                                                <a href="{{route('product',$product->slug)}}" class="d-block h-100">
                                                    <img loading="lazy" class="w-100" alt="{{ translate('product') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                                                </a>
                                                @if (isset($product->created_at) && $product->created_at->diffInMonths(\Carbon\Carbon::now()) < 1)
                                                    <span class="badge badge-title z-2">{{translate('new')}}</span>
                                                @endif
                                                <div class="hover-content d-flex justify-content-between">
                                                    <a href="javascript:">{{ \Illuminate\Support\Str::limit(isset($product->category) ? $product->category->name:'', 7) }}</a>
                                                    <div
                                                            class="d-flex flex-wrap justify-content-between align-items-center column-gap-3">
                                                        <a href="javascript:" data-id="{{$product->id}}"
                                                           class="d-inline-flex quickView_action">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        @php($wishlist = count($product->wishList)>0 ? 1 : 0)
                                                        <a href="javascript:"
                                                           class="d-inline-flex wish-icon addWishlist_function_view_page"
                                                           data-id="{{$product->id}}">
                                                            <i class="wishlist_{{$product->id}} bi {{($wishlist == 1?'bi-heart-fill text-danger':'bi-heart')}}"></i>
                                                        </a>
                                                        <div class="rating">
                                                            <i class="bi bi-star-fill text-star"></i>
                                                            <span>{{round($product->reviews->avg('rating'),1) ?? 0}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cont">
                                                <h6 class="title">
                                                    <a href="{{route('product',$product->slug)}}"
                                                       title="{{ $product['name'] }}">{{ Str::limit($product['name'], 18) }}</a>
                                                </h6>
                                                <div class="d-flex align-items-center justify-content-between column-gap-2">
                                                    <h4 class="price flex-wrap">
                                                        <span>{{\App\Utils\Helpers::currency_converter($product->unit_price-\App\Utils\Helpers::get_product_discount($product,$product->unit_price))}}</span>
                                                        @if($product->discount > 0)
                                                            <del>{{\App\Utils\Helpers::currency_converter($product->unit_price)}}</del>
                                                        @endif
                                                    </h4>
                                                    @if (json_decode($product->variation) != null)
                                                        <span class="btn add-to-cart-btn">
                                                    <a href="javascript:" data-id="{{$product['id']}}"
                                                       class="quickView_action">
                                                        <i class="bi bi-plus"></i>
                                                    </a>
                                                </span>
                                                    @else
                                                        @php($product_card_gen_id=rand(11111,99999))
                                                        <form class="cart add-to-cart-form-{{$product['id']}}"
                                                              action="{{ route('cart.add') }}"
                                                              id="add-to-cart-form-{{$product_card_gen_id}}"
                                                              data-errormessage="{{translate('please_choose_all_the_options')}}"
                                                              data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                                            <input type="number" name="quantity"
                                                                   value="{{ $product->minimum_order_qty ?? 1 }}"
                                                                   class="form-control product_quantity__qty" hidden>
                                                        </form>
                                                        <span class="btn add-to-cart-btn">
                                                    <a href="javascript:" class="store_vacation_check_function"
                                                       data-id="{{ $product['id'] }}"
                                                       data-added_by="{{ $product['added_by'] }}"
                                                       data-user_id="{{ $product['user_id'] }}"
                                                       data-action_url="{{ route('ajax-shop-vacation-check') }}"
                                                       data-product_cart_id="{{ $product_card_gen_id }}">
                                                        <i class="bi bi-plus"></i>
                                                    </a>
                                                </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if(($product['product_type'] == 'physical'))
                                                        @if ($product['current_stock'] <= 0)
                                                            <span
                                                                    class=" text-danger">{{ translate('out_of_stock') }}</span>
                                                        @elseif ($product['current_stock'] <= $web_config['products_stock_limit'])
                                                            <span>{{ translate('limited_Stock') }}</span>
                                                        @else
                                                            <span>{{ translate('in_stock') }}</span>
                                                        @endif
                                                    @else
                                                        <span>{{ translate('in_stock') }}</span>
                                                    @endif
                                                </div>
                                                @php($overallRating = getOverallRating($product->reviews))

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
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="signature-title text-md-end">
                        <div class="pb-4">
                            <h2 class="title text-base text-capitalize mb-2">{{ translate('find_your_best_featured_deal_product') }}</h2>
                            <a href="{{route('products',['data_from'=>'featured_deal','page'=>1])}}"
                               class="text-base text-underline">
                                {{translate('see_all_products')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
