@extends('theme-views.layouts.app')

@section('title', translate('my_order_details_vendor_info').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="card bg-section border-0">
                <div class="card-body p-lg-4">
                    @include('theme-views.partials._order-details-head',['order'=>$order])
                    <div class="mt-4 card border-0 bg-body">
                        <div class="card-body mb-xl-5">
                            @if($order->seller_is =='seller')
                            @if(isset($order->seller))
                                <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                    <div class="media align-items-center gap-3">
                                        <div class="width-7-312rem">
                                            <img loading="lazy" class="rounded w-100" alt="{{ translate('shop') }}"
                                                 src="{{ getValidImage(path: 'storage/app/public/shop/'.($order->seller->shop->image), type:'shop') }}" >
                                        </div>
                                        <div class="media-body d-flex flex-column gap-2">
                                            <h4>{{$order->seller->shop->name}}</h4>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="text-star">
                                                    @for($inc=1;$inc<=5;$inc++)
                                                        @if ($inc <= (int)$avg_rating)
                                                            <i class="bi bi-star-fill"></i>
                                                            @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                            <i class="bi bi-star-half"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-muted fw-semibold">({{number_format($avg_rating,1)}})</span>
                                            </div>
                                            <ul class="list-unstyled list-inline-dot fs-12">
                                                <li>{{$rating_count}} {{translate('reviews')}}  </li>
                                            </ul>
                                        </div>
                                    </div>
                                    @if(isset($order->seller->shop) && $order->seller->shop['id'] != 0)
                                    <div class="d-flex flex-column gap-3">
                                        <button class="btn btn-base text-capitalize" data-bs-toggle="modal" data-bs-target="#contact_sellerModal" class="btn btn-base">
                                            <i class="bi bi-chat-square-fill"></i>
                                            {{translate('chat_with_vendor')}}
                                        </button>
                                        @include('theme-views.layouts.partials.modal._chat-with-seller',['sellerId'=>$order->seller['id'],'shopId'=>$order->seller->shop['id']])
                                    </div>
                                    @endif
                                </div>
                                <div class="d-flex gap-3 flex-wrap mt-4">
                                    <div class="card flex-grow-1 bg-section border-0">
                                        <div class="card-body grid-center">
                                            <div class="text-center">
                                                <h2 class="fs-28 text-base fw-extra-bold mb-2">{{round($rating_percentage)}}%</h2>
                                                <p class="text-muted text-capitalize">{{translate('positive_review')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card flex-grow-1 bg-section border-0">
                                        <div class="card-body grid-center ">
                                            <div class="text-center">
                                                <h2 class="fs-28 text-base fw-extra-bold mb-2">{{$product_count}}</h2>
                                                <p class="text-muted">{{translate('products')}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center pt-5 w-100">
                                    <div class="text-center">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/empty-coupon.svg') }}" alt="{{ translate('empty') }}">
                                        <h5 class="my-3 pt-1 text-muted">
                                                {{translate('vendor_not_found').'!'}}
                                        </h5>
                                    </div>
                                </div>
                            @endif
                            @else
                            <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                <div class="media align-items-center gap-3">
                                    <div class="width-7-312rem">
                                        <img loading="lazy" alt="{{ translate('logo') }}" class="rounded w-100"
                                             src="{{ getValidImage(path: "storage/app/public/company/".$web_config['fav_icon']->value, type:'logo') }}">
                                    </div>
                                    <div class="media-body d-flex flex-column gap-2">
                                        <h4>{{$web_config['name']->value}}</h4>
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="text-star">
                                                @for($inc=1;$inc<=5;$inc++)
                                                    @if ($inc <= (int)$avg_rating)
                                                        <i class="bi bi-star-fill"></i>
                                                        @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-muted fw-semibold">({{number_format($avg_rating,1)}})</span>
                                        </div>
                                        <ul class="list-unstyled list-inline-dot fs-12">
                                            <li>{{$rating_count}} {{translate('reviews')}}  </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-3 flex-wrap mt-4">
                                <div class="card flex-grow-1 bg-section border-0">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h2 class="fs-28 text-base fw-extra-bold mb-2">{{round($rating_percentage)}}%</h2>
                                            <p class="text-muted text-capitalize">{{translate('positive_review')}}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card flex-grow-1 bg-section border-0">
                                    <div class="card-body grid-center ">
                                        <div class="text-center">
                                            <h2 class="fs-28 text-base fw-extra-bold mb-2">{{$product_count}}</h2>
                                            <p class="text-muted">{{translate('products')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
