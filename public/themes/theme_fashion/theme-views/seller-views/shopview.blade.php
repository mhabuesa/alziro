@extends('theme-views.layouts.app')

@section('title',translate('shop_page').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    @if($shop['id'] != 0)
        <meta property="og:image" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="og:title" content="{{ $shop->name}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="og:title" content="{{ $shop['name']}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    @if($shop['id'] != 0)
        <meta property="twitter:card" content="{{asset('storage/app/public/shop')}}/{{$shop->image}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="twitter:card"
              content="{{asset('storage/app/public/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')
    @if ($shop['id'] != 0 && auth('customer')->check())
        @include('theme-views.layouts.partials.modal._chat-with-seller',['sellerId'=>$seller_id,'shopId'=>$shop['id']])
    @endif

    <section class="seller-profile-section p-1">
        <div class="container">
            <div class="seller-profile-wrapper">
                <div class="seller-profile-info">
                    <div class="seller-profile">
                        @if($shop['id'] != 0)
                            <div class="seller-profile-top text-center text-capitalize">
                                <div class="position-relative img-area ">
                                    <div>
                                        <img loading="lazy" alt="{{ translate('shop') }}" src="{{ getValidImage(path: 'storage/app/public/shop/'.$shop->image, type: 'shop') }}">
                                    </div>
                                    @if($seller_temporary_close || $inhouse_temporary_close)
                                        <div class="shop_close_now_overly">
                            <span class="temporary-closed position-absolute">
                                <span>{{translate('closed_now')}}</span>
                            </span>
                                        </div>
                                    @elseif(($seller_id==0 && $inHouseVacationStatus && $current_date >=
                                    $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) ||
                                        $seller_id!=0 && $seller_vacation_status && $current_date>= $seller_vacation_start_date
                                        && $current_date <= $seller_vacation_end_date)
                                        <div class="shop_close_now_overly">
                                <span class="temporary-closed position-absolute">
                                    <span>{{translate('closed_now')}}</span>
                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="seller-profile-content">
                                    <h5 class="name mt-2">{{ $shop->name}}</h5>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <=$avg_rating)
                                                <i class="bi bi-star-fill"></i>
                                            @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating>=
                                                ((int)$avg_rating+.30))
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                        <span>({{round($avg_rating,1)}})</span>
                                    </div>
                                    <div class="d-flex justify-content-md-center">
                                        <span>{{ $total_order}} {{translate('orders')}}</span> <span>
                                    <span class="mx-1">|</span> </span>
                                        <span>{{ $total_review}} {{translate('reviews')}}</span>
                                    </div>
                                    @php($minimum_order_amount = getWebConfig(name: 'minimum_order_amount_status'))
                                    @php($minimum_order_amount_by_seller = getWebConfig(name: 'minimum_order_amount_by_seller'))
                                    @if ($minimum_order_amount ==1 && $minimum_order_amount_by_seller ==1)
                                        <div class="d-flex justify-content-md-center">
                                            <span>{{ \App\Utils\Helpers::currency_converter($shop->seller->minimum_order_amount)}} {{translate('minimum_order_amount')}}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-wrap btn-grp">
                                        @if (auth('customer')->id() == '')
                                            <button type="button" class="btn __btn-outline customer_login_register_modal">
                                                {{ translate('message') }}
                                            </button>
                                        @else
                                            <button type="button" class="btn __btn-outline" data-bs-toggle="modal"
                                                    data-bs-target="#contact_sellerModal">
                                                {{ translate('message') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="seller-profile-top text-center text-capitalize">
                                <div class="img-area position-relative mb-2">
                                    <img loading="lazy" alt="{{ translate('logo') }}" class="m-0"
                                        src="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['fav_icon']->value, type:'shop') }}">

                                    @if($seller_temporary_close || $inhouse_temporary_close)
                                        <span class="temporary-closed position-absolute">
                                <span>{{translate('closed_now')}}</span>
                            </span>
                                    @elseif(($seller_id==0 && $inHouseVacationStatus && $current_date >=
                                    $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date) || $seller_id!=0 &&
                                    $seller_vacation_status && $current_date>= $seller_vacation_start_date && $current_date <=
                                        $seller_vacation_end_date)
                                        <span class="temporary-closed position-absolute">
                                <span>{{translate('closed_now')}}</span>
                                </span>
                                    @endif
                                </div>
                                <div class="seller-profile-content">

                                    <h5 class="name">{{ $web_config['name']->value }}</h5>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <=$avg_rating)
                                                <i class="bi bi-star-fill"></i>
                                            @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating>=
                                                ((int)$avg_rating+.30))
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                        <span>({{round($avg_rating,1)}})</span>
                                    </div>
                                    <div class="d-flex justify-content-md-center">
                                        <span>{{ $total_order}} {{translate('orders')}}</span>
                                        <span> <span class="mx-1">|</span> </span>
                                        <span>{{ $total_review}} {{translate('reviews')}}</span>
                                    </div>
                                    <div class="mt-2">
                                        @php($minimum_order_amount_status = getWebConfig(name: 'minimum_order_amount_status'))
                                        @php($minimum_order_amount_by_seller = getWebConfig(name: 'minimum_order_amount_by_seller'))
                                        @if ($minimum_order_amount_status ==1 && $minimum_order_amount_by_seller ==1)

                                            @if($shop['id'] == 0)
                                                @php($minimum_order_amount = getWebConfig(name: 'minimum_order_amount'))
                                                <span
                                                    class="text-sm-nowrap">{{ \App\Utils\Helpers::currency_converter($minimum_order_amount)}} {{translate('minimum_order_amount')}}</span>
                                            @else
                                                <span
                                                    class="text-sm-nowrap">{{ \App\Utils\Helpers::currency_converter($shop->seller->minimum_order_amount)}} {{translate('minimum_order_amount')}}</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="seller-profile-hero">
                    @if($shop['id'] != 0)
                        <img loading="lazy" alt="{{ translate('banner') }}"
                             src="{{ getValidImage(path: 'storage/app/public/shop/banner/'.$shop->banner, type:'shop-banner') }}">
                    @else
                        @php($banner = getWebConfig(name: 'shop_banner'))
                        <img loading="lazy" alt="{{ translate('banner') }}" src="{{ getValidImage(path: "storage/app/public/shop/".$banner ?? "", type:'shop-banner') }}">
                    @endif
                </div>
            </div>
            <div class="mt-10px mb-10px ms-auto seller-profile-count-area">
                <div class="count-area">
                    <div class="item">
                        <h5>{{round($avg_rating*20)}}%</h5>
                        <div class="text-capitalize">{{translate("positive_review")}}</div>
                    </div>
                    <div class="item">
                        <h5>{{$products_for_review}}</h5>
                        <div>{{translate('products')}}</div>
                    </div>
                </div>
                @if ($shop['id'] != 0 && $shop->offer_banner)
                    <img loading="lazy" alt="{{ translate('banner') }}"
                         src="{{ getValidImage(path: 'storage/app/public/shop/banner/'.$shop->offer_banner, type:'shop-banner') }}">
                @elseif ($shop['id'] == 0)
                    @php($offer_banner = getWebConfig(name: 'offer_banner'))
                    <img loading="lazy"
                         alt="{{ translate('banner') }}" src="{{ getValidImage(path: 'storage/app/public/shop/'.$offer_banner, type:'shop-banner') }}">
                @else
                    <img loading="lazy" src="" alt="">
                @endif
            </div>
        </div>
    </section>

    @if ($featured_products->count() > 0)
        <section class="featured-product section-gap pb-0">
            <div class="container">
                <div class="section-title mb-4 pb-lg-1">
                    <div class="d-flex flex-wrap justify-content-between row-gap-2 column-gap-4 align-items-center">
                        <h2 class="title mb-0 me-auto text-base text-capitalize line-limit-1 w-0 flex-grow-1">{{ translate('featured_product_from_this_store') }}
                            <sup
                                class="font-regular text-small text-text-2 d-none d-sm-inline-block">({{$featured_products->count()}} {{translate('product')}}
                                )</sup>
                        </h2>
                        <div>
                            <a href="{{route('products',['data_from'=>'featured','shop_id'=>$shop['id'],'page'=>1])}}"
                               class="see-all">{{ translate('see_all') }}</a>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <div class="--bg-4 p-20px">
                        <div class="similler-product-slider-area">
                            <div class="similler-product-slider-2 owl-theme owl-carousel">
                                @foreach ($featured_products as $product)
                                    @include('theme-views.partials._product-small-card', ['product'=>$product])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <section class="seller-profile-details-section pt-32px pb-5 scroll_to_form_top">
        <div class="container">
            <ul class="nav nav-tabs nav--tabs-2 justify-content-center" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#products" class="nav-link active" data-bs-toggle="tab">{{ translate('all_product') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#comments" class="nav-link" data-bs-toggle="tab">{{translate('review')}}
                        <sup>{{ $total_review}}</sup></a>
                </li>
            </ul>
            <div class="tab-content pt-3">

                <div class="tab-pane fade show active" id="products">
                    <form action="{{ route('ajax-filter-products') }}" method="POST" id="fashion_products_list_form">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop['id'] }}">
                        <div
                            class="ms-auto ms-md-0 d-flex flex-wrap justify-content-between align-items-center column-gap-3 row-gap-2 mb-4 text-capitalize">
                            <div></div>
                            <div class="position-relative select2-prev-icon filter_select_input_div d-none d-md-block">
                                <i class="bi bi-sort-up"></i>
                                <select
                                    class="select2-init form-control size-40px filter_select_input filter_by_product_list_web"
                                    name="sort_by"
                                    data-primary_select="{{translate('sort_by')}} : {{translate('default')}}">
                                    <option value="default">{{translate('sort_by')}} : {{translate('default')}}</option>
                                    <option value="latest">{{translate('sort_by')}} : {{translate('latest')}}</option>
                                    <option value="a-z">{{translate('sort_by')}}
                                        : {{translate('a_to_z_order')}}</option>
                                    <option value="z-a">{{translate('sort_by')}}
                                        : {{translate('z_to_a_order')}}</option>
                                    <option value="low-high">{{translate('sort_by')}}
                                        : {{translate('low_to_high_price')}}</option>
                                    <option value="high-low">{{translate('sort_by')}}
                                        : {{translate('high_to_low_price')}}</option>
                                </select>
                            </div>
                            <div class="d-lg-none">
                                <button type="button" class="btn btn-soft-base border filter-toggle d-lg-none">
                                    <i class="bi bi-funnel"></i>
                                </button>
                            </div>
                        </div>
                        <main class="main-wrapper">

                            <aside class="sidebar">
                                @include('theme-views.partials.products._products-list-aside',['categories'=>$categories, 'brands'=>$brands,'colors'=>$colors_in_shop])
                            </aside>

                            <article class="article">
                                <div id="selected_filter_area">
                                    @include('theme-views.product._selected_filter_tags',['tags_category'=>null,'tags_brands'=>null,'rating'=>null])
                                </div>
                                <div id="ajax_products_section">
                                    @include('theme-views.product._ajax-products',['products'=>$products,'page'=>1,'paginate_count'=>$paginate_count])
                                </div>
                            </article>
                        </main>
                    </form>
                </div>

                <div class="tab-pane fade" id="comments">
                    <div class="product-information p-0 shadow-0 border-0">
                        <div class="product-information-inner single-page-height-800px">
                            <div class="details-review row-gap-4">
                                <div class="details-review-item">
                                    <h2 class="title">{{round($avg_rating, 1)}}</h2>
                                    <div class="text-star">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <=$avg_rating)
                                                <i class="bi bi-star-fill"></i>
                                            @elseif ($avg_rating != 0 && $i <= (int)$avg_rating + 1 && $avg_rating>=
                                                ((int)$avg_rating+.30))
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span>{{ $total_review}} {{translate('reviews')}}</span>
                                </div>
                                <div class="details-review-item">
                                    <h2 class="title font-regular">{{ round($ratting_status['positive']) }}%</h2>
                                    <span class="text-capitalize">{{ translate('positive_review') }}</span>
                                </div>
                                <div class="details-review-item details-review-info">
                                    <div class="item">
                                        <div class="form-label mb-3 d-flex justify-content-between">
                                            <span>{{ translate('positive') }}</span>
                                            <span>{{ round($ratting_status['positive']) }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-fill"
                                                 style="--fill:{{ round($ratting_status['positive']) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="form-label mb-3 d-flex justify-content-between">
                                            <span>{{ translate('good') }}</span>
                                            <span>{{ round($ratting_status['good']) }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-fill"
                                                 style="--fill:{{ round($ratting_status['good']) }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="form-label mb-3 d-flex justify-content-between">
                                            <span>{{ translate('neutral') }}</span>
                                            <span>{{ round($ratting_status['neutral']) }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-fill"
                                                 style="--fill:{{ round($ratting_status['neutral']) }}%"></div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="form-label mb-3 d-flex justify-content-between">
                                            <span>{{ translate('negative') }}</span>
                                            <span>{{ round($ratting_status['negative']) }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-fill"
                                                 style="--fill:{{ round($ratting_status['negative']) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="comments-information mt-32px">
                                <ul id="shop-review-list">
                                    @include('theme-views.layouts.partials._product-reviews',['productReviews'=>$reviews])
                                    @if($total_review == 0)
                                        <p class="text-muted">{{ translate('product_review_not_available') }}</p>
                                    @endif
                                </ul>
                            </div>
                            @if($total_review > 4)
                                <a href="javascript:" id="load_review_for_shop"
                                   class="product-information-view-more-custom see-more-details-review view_text"
                                   data-shopid="{{$shop['id']}}"
                                   data-routename="{{route('review-list-shop')}}"
                                   data-afterextend="{{translate('view_less')}}"
                                   data-seemore="{{translate('view_more')}}"
                                   data-onerror="{{translate('no_more_review_remain_to_load')}}">{{translate('view_more')}}</a>
                            @else
                                <a href="javascript:"
                                   class="product-information-view-more">{{translate('view_more')}}</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <span id="shop_follow_url" data-url="{{route('shop-follow')}}"></span>

@endsection
