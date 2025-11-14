@extends('theme-views.layouts.app')

@section('title', translate('my_order_details').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')

            <div class="card bg-section border-0">
                <div class="card-body p-lg-5">
                    <div class="mb-4">
                        <h1 class="modal-title fs-5 text-capitalize" id="refundModalLabel">{{ translate('refund_details')}}</h1>
                    </div>
                    <div class="modal-body span--inline">
                        <div class="d-flex flex-column flex-sm-row flex-wrap gap-4 justify-content-between mb-4">
                            <div class="media align-items-center gap-3">
                                <div class="cart-product">
                                    <label class="form-check">
                                        <img loading="lazy" alt="{{ translate('products') }}"
                                        src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                                    </label>
                                    <div class="cont">
                                        <a href="{{route('product',[$product['slug']])}}" class="name text-title">{{isset($product['name']) ? Str::limit($product['name'],40) : ''}}</a>
                                        <div class="d-flex column-gap-1">
                                            <span>{{translate('price')}}</span> <span>:</span> <strong>{{\App\Utils\currency_converter($order_details->price)}}</strong>
                                        </div>
                                        @if ($product['product_type'] == "physical" && !empty(json_decode($order_details['variation'])))
                                            <div class="d-flex flex-wrap column-gap-3">
                                                @foreach (json_decode($order_details['variation']) as $key => $value)
                                                <div class="d-flex column-gap-1">
                                                    <span>{{translate($key)}} </span> <span>:&nbsp;{{ucwords($value)}}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1 fs-12">
                                <span> <span>{{ translate('Qty')}}</span> <span>:</span> <span>{{$order_details->qty}}</span></span>
                                <span> <span>{{ translate('Price')}}</span> <span>:</span> <span>{{\App\Utils\currency_converter($order_details->price)}}</span></span>
                                <span> <span>{{ translate('Discount')}}</span> <span>:</span> <span>{{\App\Utils\currency_converter($order_details->discount)}}</span></span>
                                <span> <span>{{ translate('Tax')}}</span> <span>:</span> <span>{{\App\Utils\currency_converter($order_details->tax)}}</span></span>
                            </div>
                            <?php
                                $total_product_price = 0;
                                foreach ($order->details as $key => $or_d) {
                                    $total_product_price += ($or_d->qty * $or_d->price) + $or_d->tax - $or_d->discount;
                                }
                                $refund_amount = 0;
                                $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;
                                $coupon_discount = ($order->discount_amount * $subtotal) / $total_product_price;
                                $refund_amount = $subtotal - $coupon_discount;
                            ?>
                            <div class="d-flex flex-column gap-1 fs-12">
                                <span><span>{{translate('subtotal')}}</span> <span>:</span> <span> {{\App\Utils\currency_converter($subtotal)}}</span></span>
                                <span><span>{{translate('coupon_discount')}}</span> <span>:</span> <span> {{\App\Utils\currency_converter($coupon_discount)}}</span></span>
                                <span><span>{{translate('total_refundable_amount')}}</span> <span>:</span> <span>{{\App\Utils\currency_converter($refund_amount)}}</span></span>
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <label for="comment" class="form--label mb-2">{{translate('refund_reason')}}</label>
                            <p>{{$refund->refund_reason}}</p>
                        </div>
                        <div class="form-group">
                            <h6 class="mb-2">{{translate('attachment')}}</h6>
                            <div class="d-flex flex-column gap-3">
                                @if ($refund->images !=null)
                                    <div class="gallery">
                                        @foreach (json_decode($refund->images) as $key => $photo)
                                            <a href="{{asset('storage/app/public/refund')}}/{{$photo}}" class="custom-image-popup">
                                                <img loading="lazy" src="{{asset('storage/app/public/refund')}}/{{$photo}}" alt="{{ translate('refund') }}" class="img-w-h-100">
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-capitalize">{{translate('no_attachment_found')}}</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
