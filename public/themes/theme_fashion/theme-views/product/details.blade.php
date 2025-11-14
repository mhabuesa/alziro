@extends('theme-views.layouts.app')

@section('title', $product['name'].' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @if($product->added_by=='seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by=='admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif

    @if($product['meta_image'])
        <meta property="og:image" content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/app/public/product/meta")}}/{{$product->meta_image}}"/>
    @else
        <meta property="og:image" content="{{asset("storage/app/public/product/thumbnail")}}/{{$product->thumbnail}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/app/public/product/thumbnail/")}}/{{$product->thumbnail}}"/>
    @endif

    @if($product['meta_title'])
        <meta property="og:title" content="{{$product->meta_title}}"/>
        <meta property="twitter:title" content="{{$product->meta_title}}"/>
    @else
        <meta property="og:title" content="{{$product->name}}"/>
        <meta property="twitter:title" content="{{$product->name}}"/>
    @endif
    <meta property="og:url" content="{{route('product',[$product->slug])}}">

    @if($product['meta_description'])
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif
    <meta property="twitter:url" content="{{route('product',[$product->slug])}}">
@endpush

@section('content')

    <section class="product-single-section pt-20px">
        <div class="container">
            <div class="section-title mb-4">
                <div
                    class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('home')}}">{{ translate('home') }}</a>
                        </li>
                        <li>
                            <a href="{{route('products',['id'=> $product->category_id,'data_from'=>'category','page'=>1])}}">
                                {{translate('products')}}
                            </a>
                        </li>
                        <li>
                            <a href="javascript:" class="text-base">{{$product->name}}</a>
                        </li>
                    </ul>
                    <div class="text-capitalize">{{ translate('similar_category_product') }}
                        <span class="text-base cursor-pointer thisIsALinkElement"
                              data-linkpath="{{route('products',['id'=> $product->category_id,'data_from'=>'category','page'=>1])}}">
                    {{$relatedProducts}} {{ translate('item') }}</span>
                    </div>
                </div>
            </div>

            @if ( preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <iframe class="videoModalIframe" src="{{$product->video_url}}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="product-single-wrapper">
                @if($product->images!=null && json_decode($product->images)>0)
                    <div class="product-single-thumb">
                        @if(json_decode($product->colors) && $product->color_image)
                            <div class="overflow-hidden rounded">
                                <div class="product-share-icons">
                                    <a href="javascript:" class="share-icon" title="{{translate('share')}}">
                                        <i class="bi bi-share-fill"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="facebook.com/sharer/sharer.php?u="
                                            >
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="twitter.com/intent/tweet?text="
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                     fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="linkedin.com/shareArticle?mini=true&url="
                                            >
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="api.whatsapp.com/send?text="
                                            >
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="sync1" class="owl-carousel owl-theme product-single-main-slider">
                                    @foreach (json_decode($product->color_image) as $key => $photo)
                                        @if (count(json_decode($product->color_image)) > 1 && $key==1 && preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                            <div class="main-thumb border rounded overflow-hidden">
                                                <div class="" data-bs-toggle="modal" data-bs-target="#videoModal">
                                                    <a href="javascript:">
                                                        <img loading="lazy"
                                                             src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                             alt="{{ translate('products') }}"
                                                             class="onerror-placeholder-image"
                                                             height="380px">
                                                    </a>
                                                    <div class="play--icon">
                                                        <i class="bi bi-play-btn-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($photo->color != null)
                                            <div class="main-thumb border rounded overflow-hidden">
                                                <div class="easyzoom easyzoom--overlay">
                                                    <a href="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                        <img loading="lazy" alt="{{ translate('product') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @foreach (json_decode($product->color_image) as $key => $photo)
                                        @if($photo->color == null)
                                            <div class="main-thumb border rounded overflow-hidden">
                                                <div class="easyzoom easyzoom--overlay">
                                                    <a href="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                        <img loading="lazy" alt="{{ translate('product') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if (count(json_decode($product->color_image)) < 1 && preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                        <div class="main-thumb border rounded overflow-hidden">
                                            <div class="" data-bs-toggle="modal" data-bs-target="#videoModal">
                                                <a href="javascript:">
                                                    <img loading="lazy"
                                                         src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                         alt="{{ translate('products') }}"
                                                         class="onerror-placeholder-image"
                                                         height="380px">
                                                </a>
                                                <div class="play--icon">
                                                    <i class="bi bi-play-btn-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="overflow-hidden rounded">
                                <div class="product-share-icons">
                                    <a href="javascript:" class="share-icon" title="{{translate('share')}}">
                                        <i class="bi bi-share-fill"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="facebook.com/sharer/sharer.php?u=">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="twitter.com/intent/tweet?text=">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                     fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="linkedin.com/shareArticle?mini=true&url=">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:" class="social_share_function"
                                               data-url="{{route('product',$product->slug)}}"
                                               data-social="api.whatsapp.com/send?text=">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="sync1" class="owl-carousel owl-theme product-single-main-slider">
                                    @foreach (json_decode($product->images) as $key => $photo)
                                        @if (count(json_decode($product->images)) > 1 && $key==1 && preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                            <div class="main-thumb border rounded overflow-hidden">
                                                <div class="" data-bs-toggle="modal" data-bs-target="#videoModal">
                                                    <a href="javascript:">
                                                        <img loading="lazy"
                                                             src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                             alt="{{ translate('products') }}"
                                                             class="onerror-placeholder-image">
                                                    </a>
                                                    <div class="play--icon">
                                                        <i class="bi bi-play-btn-fill"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="main-thumb border rounded overflow-hidden">
                                            <div class="easyzoom easyzoom--overlay">
                                                <a href="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type:'product') }}">
                                                    <img loading="lazy" alt="{{ translate('product') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type:'product') }}">
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if (count(json_decode($product->images)) < 1 && preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                        <div class="main-thumb border rounded overflow-hidden">
                                            <div class="" data-bs-toggle="modal" data-bs-target="#videoModal">
                                                <a href="javascript:">
                                                    <img loading="lazy"
                                                         src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                         alt="{{ translate('products') }}"
                                                         class="onerror-placeholder-image">
                                                </a>
                                                <div class="play--icon">
                                                    <i class="bi bi-play-btn-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="overflow-hidden">
                            <div id="sync2" class="owl-carousel owl-theme product-single-thumbnails">
                                @if($product->images!=null && json_decode($product->images)>0)
                                    @if(json_decode($product->colors) && $product->color_image)
                                        @foreach (json_decode($product->color_image) as $key => $photo)
                                            @if ($key == 1)
                                                @if ( preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                                    <div class="thumb youtube_video">
                                                        <img loading="lazy"
                                                             src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                             class="w-100px onerror-placeholder-image" alt="{{ translate('products') }}">
                                                        <div class="play--icon">
                                                            <i class="bi bi-play-btn-fill"></i>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            @if($photo->color != null)
                                                <div class="thumb color_variants_preview-box-{{$photo->color}}">
                                                    <img loading="lazy" alt="{{ translate('product') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                                </div>
                                            @endif
                                        @endforeach

                                        @foreach (json_decode($product->color_image) as $key => $photo)
                                            @if($photo->color == null)
                                                <img loading="lazy" alt="{{ translate('product') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/product/'.$photo->image_name, type:'product') }}">
                                            @endif
                                        @endforeach
                                    @else
                                        @php($product_images = json_decode($product->images))
                                        @foreach ($product_images as $key => $photo)
                                            @if (count($product_images) > 1 && $key==1 && preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                                <div class="thumb youtube_video">
                                                    <img loading="lazy"
                                                         src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                         class="w-100px onerror-placeholder-image" alt="{{ translate('products') }}">
                                                    <div class="play--icon">
                                                        <i class="bi bi-play-btn-fill"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="thumb ">
                                                <img loading="lazy" src="{{ getValidImage(path: 'storage/app/public/product/'.$photo, type: 'product') }}"
                                                     alt="{{ translate('product') }}">
                                            </div>

                                        @endforeach
                                        @if (count($product_images) <= 1 && preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$product->video_url))
                                            <div class="thumb youtube_video">
                                                <img loading="lazy"
                                                     src="https://i.ytimg.com/vi/{{substr($product->video_url, strrpos($product->video_url, '/') + 1) }}/0.jpg"
                                                     class="w-100px onerror-placeholder-image" alt="{{ translate('products') }}">
                                                <div class="play--icon">
                                                    <i class="bi bi-play-btn-fill"></i>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                @endif

                <div class="product-single-content">
                    <form class="cart add_to_cart_form" action="{{ route('cart.add') }}" id="add-to-cart-form"
                          data-redirecturl="{{route('checkout-details')}}"
                          data-varianturl="{{ route('cart.variant_price') }}"
                          data-errormessage="{{translate('please_choose_all_the_options')}}"
                          data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                        @csrf
                        <h3 class="title">{{$product->name}}</h3>
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <div class="d-flex flex-wrap align-items-center column-gap-4">
                            @if ($product->reviews_count > 0)
                                <div class=" review position-relative">
                                    <i class="bi bi-star-fill"></i>
                                    <span>{{round($overallRating[0], 1)}} <small>({{$product->reviews_count}} {{translate('review')}})</small></span>

                                    <div class="review-details-popup z-3">
                                        <div class="mb-4px">{{ translate('rating') }}</div>
                                        <div class="review-items d-flex flex-column row-gap-1">
                                            <div class="d-flex column-gap-2 align-items-center">
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <span class="progress">
                                                <div class="progress-fill"
                                                     style="--fill:{{($rating[0] != 0?number_format($rating[0]*100 / array_sum($rating)):0)}}%"></div>
                                            </span>
                                                <span>({{$rating[0]}})</span>
                                            </div>
                                            <div class="d-flex column-gap-2 align-items-center">
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <span class="progress">
                                                <div class="progress-fill"
                                                     style="--fill:{{($rating[1] != 0?number_format($rating[1]*100 / array_sum($rating)):0)}}%"></div>
                                            </span>
                                                <span>({{$rating[1]}})</span>
                                            </div>
                                            <div class="d-flex column-gap-2 align-items-center">
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <span class="progress">
                                                <div class="progress-fill"
                                                     style="--fill:{{($rating[2] != 0?number_format($rating[2]*100 / array_sum($rating)):0)}}%"></div>
                                            </span>
                                                <span>({{$rating[2]}})</span>
                                            </div>
                                            <div class="d-flex column-gap-2 align-items-center">
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <span class="progress">
                                                <div class="progress-fill"
                                                     style="--fill:{{($rating[3] != 0?number_format($rating[3]*100 / array_sum($rating)):0)}}%"></div>
                                            </span>
                                                <span>({{$rating[3]}})</span>
                                            </div>
                                            <div class="d-flex column-gap-2 align-items-center">
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                                <span class="progress">
                                                <div class="progress-fill"
                                                     style="--fill:{{($rating[4] != 0?number_format($rating[4]*100 / array_sum($rating)):0)}}%"></div>
                                            </span>
                                                <span>({{$rating[4]}})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class=" review position-relative">
                                    <i class="bi bi-star-fill"></i>
                                    <span>{{round($overallRating[0], 1)}} <small class="text-capitalize">({{translate('no_review')}})</small></span>
                                </div>
                            @endif
                            @if($product['product_type'] == 'physical' )
                                <span class="badge badge-soft-success stock_status">
                                    <span class="in_stock_status">{{$product->current_stock}}</span> {{translate('stock_available')}}
                                </span>
                                <span
                                    class="badge badge-soft-danger d-none out_of_stock_status">{{translate('out_of_stock')}}</span>
                                <span
                                    class="badge badge-soft-secondary limited_status d-none">{{translate('limited_stock')}}</span>
                            @endif

                        </div>
                        <div class="categories">
                            <span class="text-capitalize">{{ translate('category_tag') }} :</span>
                            @if ($product->category_id)
                                <a href="{{route('products',['id'=> $product->category_id,'data_from'=>'category','page'=>1])}}"
                                   class="text-base">
                                    {{ ucwords(isset($product->category) ? $product->category->name:'') }}
                                </a>
                            @endif

                            @if ($product->sub_category_id)
                                <a href="{{route('products',['id'=> $product->sub_category_id,'data_from'=>'category','page'=>1])}}"
                                   class="text-base">
                                    {{ ucwords(\App\Utils\CategoryManager::get_category_name($product->sub_category_id)) }}
                                </a>
                            @endif

                            @if ($product->sub_sub_category_id)
                                <a href="{{route('products',['id'=> $product->sub_sub_category_id,'data_from'=>'category','page'=>1])}}"
                                   class="text-base">
                                    {{ ucwords(\App\Utils\CategoryManager::get_category_name($product->sub_sub_category_id)) }}
                                </a>
                            @endif
                        </div>
                        <hr>
                        <div class="price">
                            <h4>{!! getPriceRangeWithDiscount(product: $product) !!}
                                @if($product->discount > 0)
                                    @if ($product->discount_type === "percent")
                                        <span class="badge bg-base">-{{$product->discount}}%</span>
                                    @else
                                        <span class="badge bg-base">
                                            {{translate('save')}} {{ webCurrencyConverter(amount: $product->discount) }}
                                        </span>
                                    @endif
                                @endif
                            </h4>
                        </div>

                        @if (count(json_decode($product->colors)) > 0)
                            <div>
                                <label class="form-label">{{translate('color')}}</label>
                                <div class="check-color-group justify-content-start align-items-center">
                                    @foreach (json_decode($product->colors) as $key => $color)
                                        <label>
                                            <input type="radio" name="color"
                                                   value="{{ $color }}" {{ $key == 0 ? 'checked' : '' }}>
                                            <span style="--base:{{ $color }}" class="focus_preview_image_by_color"
                                                  data-colorid="preview-box-{{ str_replace('#','',$color) }}"
                                                  id="color_variants_preview-box-{{ str_replace('#','',$color) }}">
                                        <i class="bi bi-check"></i>
                                    </span>
                                        </label>
                                    @endforeach
                                    <span class="color_name"></span>
                                </div>
                            </div>
                        @endif

                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="mt-20px">
                                <label class="form-label">{{translate($choice->title)}}</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($choice->options as $key => $option)
                                        <label class="form-check-size">
                                            <input type="radio" name="{{ $choice->name }}" value="{{ $option }}"
                                                {{ $key == 0 ? 'checked' : '' }} >
                                            <span class="form-check-label">{{$option}}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex align-items-center row-gap-2 column-gap-4 mt-20px">
                            <span>{{ translate('quantity') }} :</span>
                            <div class="inc-inputs">
                                <input type="number" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}"
                                       class="form-control product_quantity__qty product_qty"
                                       min="{{ $product->minimum_order_qty ?? 1 }}"
                                       max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                            </div>
                        </div>
                        <div class="btn-grp">
                            @if(($product->added_by == 'seller' && ($sellerTemporaryClose || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >= $sellerVacationStartDate && $currentDate <= $sellerVacationEndDate))) ||
                            (   $product->added_by == 'admin' && ($inHouseTemporaryClose || ($inHouseVacationStatus && $currentDate >= $inHouseVacationStartDate && $currentDate <= $inHouseVacationEndDate))))
                                <button type="button" class="btn btn-base text-capitalize font-medium" disabled>
                                    @include('theme-views.partials.icons._cart-icon')
                                    {{translate('add_to_cart')}}</button>
                                <button type="button"
                                        class="buy_now_button btn btn-base __btn-outline-warning secondary-color fs-16 text-capitalize"
                                        disabled>
                                    @include('theme-views.partials.icons._buy-now')
                                    {{translate('buy_now')}}</span></button>

                            @else
                                <a href="javascript:"
                                   class="btn btn-base text-capitalize font-medium add_to_cart_button"
                                   data-form-id="add-to-cart-form">
                                    @include('theme-views.partials.icons._cart-icon')
                                    {{ translate('add_to_cart') }}
                                </a>
                                @php($guestCheckout = getWebConfig(name: 'guest_checkout'))
                                <a href="javascript:"
                                   class="btn btn-base btn-md __btn-outline-warning secondary-color text-capitalize buy_now_function"
                                   data-formid="add-to-cart-form"
                                   data-authstatus="{{($guestCheckout==1 || Auth::guard('customer')->check()?'true':'false')}}"
                                   data-route="{{route('shop-cart')}}"
                                >
                                    @include('theme-views.partials.icons._buy-now')
                                    {{ translate('buy_now') }}</a>
                            @endif

                            <a href="javascript:"
                               class="btn btn-base btn-sm __btn-outline addWishlist_function_view_page"
                               data-id="{{$product['id']}}">
                                <i class="wishlist_{{$product['id']}} bi {{($wishlistStatus == 1?'bi-heart-fill text-danger':'bi-heart')}} font--lg"></i>
                                <span
                                    class="product_wishlist_count_status">{{ \App\Utils\format_biginteger($countWishlist) }}</span>
                            </a>

                            @php($compareList = count($product->compareList)>0 ? 1 : 0)
                            <a href="javascript:"
                               class="addCompareList_view_page btn btn-base btn-sm __btn-outline text-base compare_list-{{$product['id']}} {{($compareList == 1?'compare_list_icon_active':'')}}"
                               data-id="{{$product['id']}}">
                                @include('theme-views.partials.icons._compare')
                            </a>
                        </div>

                        @if(($product->added_by == 'seller' && ($sellerTemporaryClose || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $currentDate >= $sellerVacationStartDate && $currentDate <= $sellerVacationEndDate))) ||
                        ($product->added_by == 'admin' && ($inHouseTemporaryClose || ($inHouseVacationStatus && $currentDate >= $inHouseVacationStartDate && $currentDate <= $inHouseVacationEndDate))))
                            <div class="alert alert-danger mt-3" role="alert">
                                {{translate('this_shop_is_temporary_closed_or_on_vacation')}}
                                .
                                {{translate('you_cannot_add_product_to_cart_from_this_shop_for_now')}}
                            </div>
                        @endif
                    </form>
                </div>

                <div class="product-single-pricing">
                    <div class="product-single-pricing-inner text-capitalize">
                        <h6 class="subtitle">{{ translate('total_price_for_this_product') }} :</h6>
                        <h3 class="price"><span
                                class="total_price">{{\App\Utils\Helpers::currency_converter($product->unit_price)}}</span>
                            <sub>( {{ translate('vat') }} <span
                                    class="tax_status">{{ $product->tax_model == 'include' ? 'incl.' : \App\Utils\Helpers::currency_converter($product->tax)}}</span>
                                )</sub></h3>

                        <div class="delivery-information mt-3">
                            <ul>
                                @if ( isset($product['product_type']) && $product['product_type']== 'physical'&& $deliveryInfo['shipping_type'] && $deliveryInfo['shipping_type'] != "order_wise")
                                    <li>
                                        <img loading="lazy"
                                             src="{{theme_asset('assets/img/products/icons/delivery-charge.png')}}"
                                             class="icons"
                                             alt="{{ translate('product') }}">
                                        <div class="cont">
                                            <div class="t-txt">{{translate('delivery_charge')}} -</div>
                                            <span class="mt-1"> {{ translate('start_from') }} <span
                                                    class="text-base product_delivery_cost"
                                                    id="product_delivery_cost">{{\App\Utils\Helpers::currency_converter($deliveryInfo['delivery_cost'])}}</span></span>
                                        </div>
                                    </li>
                                @elseif (isset($product['product_type']) && $product['product_type']== 'physical' && $deliveryInfo['shipping_type'] == "order_wise")
                                    <li>
                                        <img loading="lazy"
                                             src="{{theme_asset('assets/img/products/icons/delivery-charge.png')}}"
                                             class="icons"
                                             alt="{{ translate('product') }}">
                                        <div class="cont">
                                            <div class="t-txt">{{translate('delivery_charge')}} -</div>
                                            <span class="mt-1"> {{ translate('start_from') }}
                                            <span class="text-base">
                                                @if ($deliveryInfo['delivery_cost_max'] == $deliveryInfo['delivery_cost_min'] || $deliveryInfo['delivery_cost_max'] == 0)
                                                    {{\App\Utils\Helpers::currency_converter($deliveryInfo['delivery_cost_min'])}}
                                                @elseif ($deliveryInfo['delivery_cost_min'] == 0)
                                                    {{\App\Utils\Helpers::currency_converter($deliveryInfo['delivery_cost_max'])}}
                                                @else
                                                    {{\App\Utils\Helpers::currency_converter($deliveryInfo['delivery_cost_min'])}}
                                                    - {{\App\Utils\Helpers::currency_converter($deliveryInfo['delivery_cost_max'])}}
                                                @endif
                                            </span>
                                        </span>
                                        </div>
                                    </li>
                                @endif
                                @php($refundDayLimit = getWebConfig(name: 'refund_day_limit'))
                                @if(isset($web_config['refund_policy']['status']) && $web_config['refund_policy']['status'] == 1 && $refundDayLimit > 0)
                                    <li>
                                        <img loading="lazy"
                                             src="{{theme_asset('assets/img/products/icons/warranty.png')}}"
                                             class="icons" alt="{{ translate('product') }}">
                                        <div class="cont">
                                            <div class="t-txt">{{ translate('refund_policy') }}-</div>
                                            <span class="mt-1">{{$refundDayLimit}} {{translate('days')}} <span
                                                    class="text-base mx-1"><a href="{{route('terms')}}"
                                                                              target="_blank"><u>{{translate('refund_policy')}}</u></a></span></span>
                                        </div>
                                    </li>
                                @endif
                                @if ($product->added_by != "admin")
                                    <li>
                                        <div class="cont">
                                            <div class="d-flex gap-2">
                                                @if (auth('customer')->id() == '')
                                                    <a href="javascript:"
                                                       class="btn w-100 d-flex align-items-center gap-4px py-3 justify-content-center rounded btn-soft-base btn-sm customer_login_register_modal">
                                                        <img loading="lazy"
                                                             src="{{theme_asset('assets/img/products/icons/ask-about.png')}}"
                                                             class="icons" alt="{{ translate('product') }}">
                                                        <div
                                                            class="t-txt">{{ translate('ask_about_this_product') }}</div>
                                                    </a>
                                                @else
                                                    <a href="javascript:"
                                                       class="btn w-100 d-flex align-items-center gap-4px py-3 justify-content-center rounded btn-soft-base btn-sm"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#contact_sellerModal">
                                                        <img loading="lazy"
                                                             src="{{theme_asset('assets/img/products/icons/ask-about.png')}}"
                                                             class="icons" alt="{{ translate('product') }}">
                                                        <div
                                                            class="t-txt">{{ translate('ask_about_this_product') }}</div>
                                                    </a>
                                                @endif

                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if ($product->reviews_count >0)
                <div class="details-review row-gap-4 mt-32px">
                    <div class="details-review-item">
                        <h2 class="title">{{$overallRating[0]}}</h2>
                        <div class="text-star">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= (int)$overallRating[0])
                                    <i class="bi bi-star-fill"></i>
                                @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] > ((int)$overallRating[0]))
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span>{{$product->reviews_count}} {{translate('reviews')}}</span>
                    </div>
                    <div class="details-review-item">
                        <h2 class="title font-regular">{{ round($rattingStatus['positive']) }}%</h2>
                        <span class="text-capitalize">{{translate('positive_review')}}</span>
                    </div>
                    <div class="details-review-item details-review-info">
                        <div class="item">
                            <div class="form-label mb-3 d-flex justify-content-between">
                                <span>{{ translate('positive') }}</span>
                                <span>{{ round($rattingStatus['positive']) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-fill"
                                     style="--fill:{{ round($rattingStatus['positive']) }}%"></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="form-label mb-3 d-flex justify-content-between">
                                <span>{{ translate('good') }}</span>
                                <span>{{ round($rattingStatus['good']) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-fill" style="--fill:{{ round($rattingStatus['good']) }}%"></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="form-label mb-3 d-flex justify-content-between">
                                <span>{{translate('neutral')}}</span>
                                <span>{{ round($rattingStatus['neutral']) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-fill"
                                     style="--fill:{{ round($rattingStatus['neutral']) }}%"></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="form-label mb-3 d-flex justify-content-between">
                                <span>{{translate('negative')}}</span>
                                <span>{{ round($rattingStatus['negative']) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-fill"
                                     style="--fill:{{ round($rattingStatus['negative']) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($product->details != null || count($product->reviews) != 0)
                <div class="row g-2 mt-4">
                    <div class="col-xl-8 col-lg-7">
                        <div class="product-information">
                            <div class="product-information-inner">
                                <ul class="nav nav-tabs nav--tabs-2 justify-content-center">

                                    <li class="nav-item nav-item-ative">
                                        <a href="#general-info" class="nav-link active"
                                           data-bs-toggle="tab">{{ translate('general_info') }}</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#comments" class="nav-link"
                                           data-bs-toggle="tab">{{ translate('comment') }}
                                            <sup>{{$product->reviews_count}}</sup></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    @if ($product->details != null)
                                        <div class="tab-pane fade show active" id="general-info">
                                            <div class="general-information">
                                                {!! $product->details !!}
                                            </div>
                                            <a href="javascript:" class="product-information-view-more"
                                               data-view-more="{{translate('view_more')}}"
                                               data-view-less="{{translate('view_less')}}">
                                                {{translate('view_more')}}
                                            </a>
                                        </div>
                                    @else
                                        <div class="tab-pane fade show active" id="general-info">
                                            <div class="general-information">
                                                {{ translate('No_data_found') }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="tab-pane fade" id="comments">
                                        @if(count($product->reviews) > 0)
                                            <div class="comments-information">
                                                <ul id="product-review-list">
                                                    @include('theme-views.layouts.partials._product-reviews',['productReviews'=>$productReviews])
                                                </ul>
                                            </div>
                                            @if(count($product->reviews) > 2)
                                                <a href="javascript:" id="load_review_function"
                                                   class="product-information-view-more-custom see-more-details-review view_text"
                                                   data-productid="{{$product->id}}"
                                                   data-routename="{{route('review-list-product')}}"
                                                   data-afterextend="{{translate('see_less')}}"
                                                   data-seemore="{{translate('see_more')}}"
                                                   data-onerror="{{translate('no_more_review_remain_to_load')}}">{{translate('see_more')}}</a>
                                            @endif
                                        @else
                                            <div class="text-center w-100">
                                                <div class="text-center pt-5 mb-5">
                                                    <img loading="lazy"
                                                         src="{{ theme_asset('assets/img/icons/review.svg') }}"
                                                         alt="{{ translate('review') }}">
                                                    <h5 class="my-3 pt-2 text-muted">{{translate('not_reviewed_yet')}}
                                                        !</h5>
                                                    <p class="text-center text-muted">{{ translate('sorry_no_review_found_to_show_you') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($productsThisStoreTopRated->count() > 0)
                        <div class="col-xl-4 col-lg-5">
                            <div
                                class="border top-rated-product-from-store-wrapper p-3 p-md-18 d-flex flex-column justify-content-center border-light-base shadow-light-base">
                                <div class="section-title mb-4 pb-lg-1">
                                    <div
                                        class="d-flex justify-content-between row-gap-2 column-gap-4 align-items-center">
                                        <h4 class="mb-0 me-auto text-capitalize">{{ translate('top_rated_product_from_this_store') }}</h4>
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="side-column-slider">
                                        <div class="owl-theme owl-carousel slider">
                                            @foreach ($productsThisStoreTopRated as $relatedProduct)
                                                @include('theme-views.partials._similar-product-large-card', ['product'=>$relatedProduct])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                @if($productsThisStoreTopRated->count() > 0)
                    <div class="mt-3">
                        <div
                            class="border h-100 p-3 p-md-18 d-flex flex-column justify-content-center border-light-base shadow-light-base">
                            <div class="section-title mb-4 pb-lg-1">
                                <div class="d-flex justify-content-between row-gap-2 column-gap-4 align-items-center">
                                    <h4 class="mb-0 me-auto text-capitalize">{{ translate('top_rated_product_from_this_store') }}</h4>
                                    <div
                                        class="d-flex align-items-center column-gap-4 justify-content-end ms-auto ms-md-0">
                                        <div class="owl-prev top-rated-product-from-store-prev"><i
                                                class="bi bi-chevron-left"></i>
                                        </div>
                                        <div class="owl-next top-rated-product-from-store-next"><i
                                                class="bi bi-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <div class="side-column-slider">
                                    <div class="owl-theme owl-carousel top-rated-product-from-store-slider">
                                        @foreach ($productsThisStoreTopRated as $relatedProduct)
                                            @include('theme-views.partials._similar-product-large-card', ['product'=>$relatedProduct])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if($web_config['business_mode'] == 'multi')
                <div class="mt-4">
                    <div class="similler-product-slider-wrapper">
                        <div class="row g-0">
                            <div class="col-md-5 col-lg-4 col-xl-3">
                                <div class="p-3 ps-xl-4">
                                    @if($product->added_by == 'seller')
                                        @if(isset($product->seller->shop))
                                            <div class="others-store-card bg-white p-0">
                                                <div class="p-3 pt-4">
                                                    <div class="name-area">
                                                        <div class="position-relative ">
                                                            <div>
                                                                <img loading="lazy" class="rounded-full other-store-logo"
                                                                     src="{{ getValidImage(path: 'storage/app/public/shop/'.$product->seller->shop->image, type:'shop') }}"
                                                                     alt="{{ translate('others_store') }}">
                                                            </div>
                                                            @if($product->seller->shop->temporary_close || ($product->seller->shop->vacation_status && ($currentDate >= $product->seller->shop->vacation_start_date) && ($currentDate <= $product->seller->shop->vacation_end_date)))
                                                                <span
                                                                    class="temporary-closed position-absolute text-center h6 rounded-full">
                                                            <span>{{translate('closed_now')}}</span>
                                                        </span>
                                                            @endif
                                                        </div>
                                                        <div class="info">
                                                            <h6 class="name">{{$product->seller->shop->name}}</h6>
                                                            <span
                                                                class="offer-badge">{{round($ratingPercentage)}}% {{translate('positive_review')}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="info-area mb-2">
                                                        <div class="info-item">
                                                            <h6>{{$totalReviews}}</h6>
                                                            <span>{{ translate('reviews') }}</span>
                                                        </div>
                                                        <div class="info-item">
                                                            <h6>{{$productsCount}}</h6>
                                                            <span>{{ translate('products') }}</span>
                                                        </div>
                                                        <div class="info-item">
                                                            <h6>{{number_format($avgRating, 2)}}</h6>
                                                            <i class="bi bi-star-fill"></i>
                                                            <span>{{ translate('rating') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="btn-grp d-flex jusitfy-content-center bg-E2F0FF gap-2 p-3">
                                                    <a href="{{ route('shopView',[$product->seller->id]) }}"
                                                       class="btn bg-white __btn-outline">
                                                        <i class="bi bi-shop"></i> {{ translate('visit_shop') }}
                                                    </a>
                                                    @if (auth('customer')->id() == '')
                                                        <a href="javascript:"
                                                           class="btn bg-white __btn-outline customer_login_register_modal">
                                                            <i class="bi bi-chat-dots"></i> {{ translate('chat') }}
                                                        </a>
                                                    @else
                                                        <a href="javascript:" class="btn bg-white __btn-outline"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#contact_sellerModal">
                                                            <i class="bi bi-chat-dots"></i> {{ translate('chat') }}
                                                        </a>
                                                    @endif
                                                </div>
                                                @if (auth('customer')->id() != '')
                                                    @include('theme-views.layouts.partials.modal._chat-with-seller',['sellerId'=>$product->seller->id,'shopId'=>$product->seller->shop->id])
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="others-store-card bg-white p-0">
                                            <div class="p-3 pt-4">
                                                <div class="name-area">
                                                    <img loading="lazy" alt="{{ translate('logo') }}"
                                                         src="{{ getValidImage(path: "storage/app/public/company/".$web_config['fav_icon']->value, type:'logo') }}">
                                                    <div class="info">
                                                        <h6 class="name">{{$web_config['name']->value}}</h6>
                                                        <span class="offer-badge">
                                                        {{round($ratingPercentage)}}% {{translate('positive_review')}}
                                                    </span>
                                                    </div>
                                                </div>
                                                <div class="info-area mb-2">
                                                    <div class="info-item">
                                                        <h6>{{$totalReviews}}</h6>
                                                        <span>{{ translate('reviews') }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <h6>{{$productsCount}}</h6>
                                                        <span>{{ translate('products') }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <h6>{{number_format($avgRating, 2)}}</h6>
                                                        <i class="bi bi-star-fill"></i>
                                                        <span>{{ translate('rating') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-grp d-flex jusitfy-content-center bg-E2F0FF gap-2 p-3">
                                                <a href="{{ route('shopView',[0]) }}" class="btn bg-white __btn-outline">
                                                    <i class="bi bi-shop"></i> {{ translate('visit_shop') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>


                            <div class="col-md-7 col-lg-8 col-xl-9">
                                <div class="py-3 ps-3">
                                    <div class="section-title mb-4 pb-lg-1 pe-3">
                                        <div
                                            class="d-flex flex-wrap justify-content-between row-gap-2 column-gap-4 align-items-center text-capitalzie">
                                            <h6 class="mb-0 me-auto font-bold ">{{ translate('similar_product_from_this_store') }}
                                                <small
                                                    class="font-regular text-text-2">({{count($moreProductFromSeller)}} {{ translate('product') }}
                                                    )</small>
                                            </h6>
                                            @if($product->added_by=='seller')
                                                @if(isset($product->seller->shop))
                                                    <a href="{{ route('shopView',[$product->seller->id]) }}"
                                                       class="see-all">{{ translate('see_all') }}</a>
                                                @endif
                                            @else
                                                <a href="{{ route('shopView',[0]) }}"
                                                   class="see-all">{{ translate('see_all') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="overflow-hidden">
                                        @if ($moreProductFromSeller->count() > 0)
                                            <div class="similler-product-slider-area">
                                                <div class="similler-product-slider owl-theme owl-carousel">
                                                    @foreach($moreProductFromSeller as $product)
                                                        @include('theme-views.partials._product-small-card', ['product'=>$product])
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>{{translate('similar_product_not_available')}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <section class="recommended-product-section section-gap pb-0">
        <div class="container">
            <div class="section-title mb-4 pb-lg-1">
                <div
                    class="d-flex flex-column flex-md-row justify-content-md-between row-gap-2 column-gap-4 align-items-md-center single_section_dual_tabs text-capitalize">
                    <h2 class="title mb-0 me-auto text-capitalize">{{ translate('you_may_also_like') }}</h2>
                    <div class="d-flex column-gap-4 align-items-center justify-content-between">
                        <ul class="nav nav-tabs nav--tabs single_section_dual_btn text-capit">
                            <li data-targetbtn="0" role="tab">
                                <a href="#latest" class="active"
                                   data-bs-toggle="tab">{{ translate('latest_product') }}</a>
                            </li>
                            <li data-targetbtn="1" role="tab">
                                <a href="#top-rated-product" data-bs-toggle="tab">{{ translate('top_rated') }}</a>
                            </li>
                        </ul>
                        <div
                            class="d-flex align-items-center column-gap-3 column-gap-md-4 justify-content-end ms-auto ms-md-0">
                            <div class="owl-prev recommended-prev">
                                <i class="bi bi-chevron-left"></i>
                            </div>
                            <div class="owl-next recommended-nex">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                            <div class="single_section_dual_target">
                                <a href="{{route('products',['data_from'=>'latest','page'=>1])}}"
                                   class="see-all text-nowrap">{{ translate('see_all') }}</a>
                                <a href="{{route('products',['data_from'=>'top-rated','page'=>1])}}"
                                   class="see-all d-none">{{ translate('see_all') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="latest">
                        <div class="recommended-slider-wrapper">
                            <div class="recommended-slider owl-theme owl-carousel">
                                @foreach ($productsLatest as $singleProduct)
                                    @include('theme-views.partials._product-medium-card', ['product'=>$singleProduct])
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="top-rated-product">
                        <div class="recommended-slider-wrapper">
                            <div class="recommended-slider owl-theme owl-carousel">
                                @foreach ($productsTopRated as $singleProduct)
                                    @include('theme-views.partials._product-medium-card', ['product'=>$singleProduct])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if($web_config['business_mode'] == 'multi')
        @include('theme-views.partials._other-stores')
    @endif

    @include('theme-views.partials._how-to-section')

@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/product-details.js') }}"></script>
@endpush
