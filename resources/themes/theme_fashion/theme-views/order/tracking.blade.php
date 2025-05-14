@extends('theme-views.layouts.app')

@section('title', translate('track_order_result').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')

    <section class="breadcrumb-section pt-20px">
        <div class="container">
            <div class="section-title mb-4">
                <div
                        class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('home')}}">{{translate('home')}}</a>
                        </li>
                        <li>
                            <a href="{{route('track-order.index')}}">{{translate('track_order')}}</a>
                        </li>
                    </ul>
                    <div class="ms-auto ms-md-0">
                        @if(auth('customer')->check())
                            <a href="{{route('account-oder')}}"
                               class="text-base custom-text-link">{{ translate('check_My_All_Orders') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="search-track-section pt-4 section-gap">
        <div class="container">
            <h3 class="mb-3 mb-lg-4">{{translate('track_order')}}</h3>
            <form action="{{route('track-order.result')}}" type="submit" method="post">
                @csrf
                <div class="track-order-wrapper">
                    <div class="track-order-input col--5">
                        <input type="text" class="form-control" name="order_id" value="{{$orderDetails->id}}"
                               placeholder="{{translate('order_ID')}}">
                    </div>
                    <div class="track-order-input col--5">
                        <input type="text" class="form-control" name="phone_number" value="{{ $user_phone }}"
                               placeholder="{{translate('phone')}}">
                    </div>
                    <div class="track-order-input col--2">
                        <button type="submit" class="form-control btn btn-base">{{translate('track_order')}}</button>
                    </div>
                </div>
            </form>
            <div class="pt-30px">
                <table class="table __table table-mobile">
                    <thead>
                    <tr>
                        <th class="--bg-4">{{translate('order_ID')}}</th>
                        <th class="--bg-4">{{translate('vendor')}}</th>
                        <th class="--bg-4">{{translate('item')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="border-0" data-label="{{translate('order_ID')}}">
                            @if(auth('customer')->check())
                                <span class="cursor-pointer custom-text-link thisIsALinkElement"
                                      data-linkpath="{{ route('account-order-details', ['id'=>$orderDetails->id]) }}">
                                    #{{$orderDetails->id}}
                                </span>
                            @else
                                <span class="cursor-pointer custom-text-link customer_login_register_modal">
                                    #{{$orderDetails->id}}
                                </span>
                            @endif
                        </td>
                        <td class="border-0" data-label="{{translate('vendor')}}">
                            <a href="{{route('shopView',['id'=>$orderDetails->seller->shop['id']])}}"
                               class="text-title custom-text-link">{{$orderDetails->seller->shop->name}}</a>
                        </td>
                        <td class="border-0" data-label="{{translate('item')}}">
                            <div class="ms-3">{{count($orderDetails->details)}}</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-2 mt-md-5 text-capitalize">
                <h3 class="mb-3 mb-lg-4 d-none d-sm-block">{{translate('tracking_status')}}</h3>
                @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                    <div class="pt-md-3">
                        <div class="tracking-flow-wrapper pt-lg-3">
                            <div class="tracking-flow-item active">
                                <div class="img">
                                    <img loading="lazy" src="{{theme_asset('assets/img/track/placed.png')}}"
                                         alt="{{ translate('track_Order') }}">
                                </div>
                                <span class="icon"><i class="bi bi-check"></i></span>
                                <span class="serial">{{translate('1')}}</span>
                                <div>
                                    <span class="d-block text-title mb-2 mb-md-0">{{translate('order_placed')}}</span>
                                    <small class="d-block">{{date('d M, Y h:i A',strtotime($orderDetails->created_at))}}</small>
                                </div>
                            </div>
                            <div class="tracking-flow-item {{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active' : ''}}">
                                <div class="img">
                                    <img loading="lazy" src="{{theme_asset('assets/img/track/packaging.png')}}"
                                         alt="{{ translate('track_Order') }}">
                                </div>
                                <span class="icon"><i class="bi bi-check"></i></span>
                                <span class="serial">{{translate('2')}}</span>
                                <div>
                                    <span class="d-block text-title mb-2 mb-md-0">{{translate('packaging_order')}}</span>
                                    @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                        <small class="d-block">
                                            @if(\App\Utils\order_status_history($orderDetails['id'],'processing'))
                                                {{date('d M, Y h:i A',strtotime(\App\Utils\order_status_history($orderDetails['id'],'processing')))}}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="tracking-flow-item {{($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')? 'active' : ''}}">
                                <div class="img">
                                    <img loading="lazy" src="{{theme_asset('assets/img/track/on-the-way.png')}}"
                                         alt="{{ translate('track_Order') }}">
                                </div>
                                <span class="icon"><i class="bi bi-check"></i></span>
                                <span class="serial">{{translate('3')}}</span>
                                <div>
                                    <span class="d-block text-title mb-2 mb-md-0">{{translate('order_is_on_the_way')}}</span>
                                    @if(($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                        <small class="d-block">
                                            @if(\App\Utils\order_status_history($orderDetails['id'],'processing'))
                                                {{date('d M, Y h:i A',strtotime(\App\Utils\order_status_history($orderDetails['id'],'out_for_delivery')))}}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="tracking-flow-item {{($orderDetails['order_status']=='delivered')?'active' : ''}}">
                                <div class="img">
                                    <img loading="lazy" src="{{theme_asset('assets/img/track/delivered.png')}}"
                                         alt="{{ translate('track_Order') }}">
                                </div>
                                <span class="icon"><i class="bi bi-check"></i></span>
                                <span class="serial">{{translate('4')}}</span>
                                <div>
                                    <span class="d-block text-title mb-2 mb-md-0">{{translate('order_delivered')}}</span>
                                    @if($orderDetails['order_status']=='delivered')
                                        <small class="d-block">
                                            @if(\App\Utils\order_status_history($orderDetails['id'],'processing'))
                                                {{date('d M, Y h:i A',strtotime(\App\Utils\order_status_history($orderDetails['id'],'delivered')))}}
                                            @endif
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tracking-order-details text-capitalize">
                        <div class="d-flex justify-content-between ">
                            <h4 class="title ">{{translate('order_details')}}</h4>
                            <button class="btn btn-base btn-sm text-capitalize h-25" data-bs-toggle="modal"
                                    data-bs-target="#order_details">
                                {{translate('view_order_details')}}
                            </button>
                        </div>
                        <ul>
                            <li>
                                <span>{{translate('order_ID')}}</span>
                                <strong>{{ $orderDetails['id'] }}</strong>
                            </li>
                            @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                <li>
                                    <span>{{translate('verification_code')}}</span>
                                    <strong>{{ $orderDetails['verification_code'] }}</strong>
                                </li>
                            @endif
                            <li>
                                <span>{{translate('order_status')}}</span>
                                @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                    <strong>{{translate($orderDetails['order_status'] =='failed' ? 'failed_to_deliver' : $orderDetails['order_status'])}}</strong>
                                @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                    <strong>
                                        {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                                    </strong>
                                @else
                                    <strong>
                                        {{translate($orderDetails['order_status'])}}
                                    </strong>
                                @endif
                            </li>
                            <li>
                                <span>{{translate('order_created_at')}}</span>
                                <strong>{{date('D ,d M, Y ',strtotime($orderDetails['created_at']))}}</strong>
                            </li>
                            <li>
                                <span>{{translate('payment_status')}}</span>
                                @if($orderDetails['payment_status']=="paid")
                                    <strong>{{ translate('paid') }}</strong>
                                @else
                                    <strong>{{ translate('unpaid') }}</strong>
                                @endif
                            </li>
                            @if($orderDetails->delivery_man_id && $orderDetails['order_status'] !="delivered")
                                <li>
                                    <span>{{translate('estimated_delivery_date')}}</span>
                                    <strong>
                                        @if($orderDetails['expected_delivery_date'])
                                            {{date('d M, Y ',strtotime($orderDetails['expected_delivery_date']))}}
                                        @endif
                                    </strong>
                                </li>
                            @endif
                        </ul>
                    </div>
                @elseif($orderDetails['order_status']=='returned')
                    <div class="mt-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <address class="media gap-2">
                                    <div class="media-body text-center">
                                        <div class="badge font-regular badge-soft-danger text-capitalize">{{translate('product_returned')}}</div>
                                    </div>
                                </address>
                            </div>
                        </div>
                    </div>
                @elseif($orderDetails['order_status']=='canceled')
                    <div class="mt-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <address class="media gap-2">
                                    <div class="media-body text-center">
                                        <div class="badge font-regular badge-soft-danger text-capitalize">{{translate('order_'.$orderDetails['order_status'])}}</div>
                                    </div>
                                </address>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-5">
                        <div class="row">
                            <div class="col-lg-12">
                                <address class="media gap-2">
                                    <div class="media-body text-center">
                                        <div class="badge font-regular badge-soft-danger text-capitalize">{{translate('order_'.$orderDetails['order_status'])}}
                                            !{{translate('sorry_we_can`t_complete_your_order')}}</div>
                                    </div>
                                </address>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <?php
            $order = \App\Models\OrderDetail::where('order_id', $orderDetails->id)->get();
            ?>

            <div class="modal fade" id="order_details" tabindex="-1" aria-labelledby="reviewModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header px-sm-5 border-0 mx-1">
                            <div>
                                <h1 class="modal-title fs-5">{{ translate('order')}} # {{$orderDetails['id']}}</h1>

                                @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                    <h5 class="small">{{translate('verification_code')}}
                                        : {{ $orderDetails['verification_code'] }}</h5>
                                @endif
                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-0 px-sm-5">
                            <div class="product-table-wrap">
                                <div class="table-responsive">
                                    <table class="table text-capitalize text-start align-middle">
                                        <thead class="mb-3">
                                        <tr>
                                            <th class="min-w-300 text-nowrap">{{translate('product_details')}}</th>
                                            <th>{{translate('QTY')}}</th>
                                            <th class="text-end text-nowrap">{{translate('sub_total')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php( $totalTax = 0)
                                        @php($sub_total=0)
                                        @php($total_tax=0)
                                        @php($total_shipping_cost=0)
                                        @php($total_discount_on_product=0)
                                        @php($extra_discount=0)
                                        @php($coupon_discount=0)
                                        @foreach($order as $key=>$order_details)
                                            @php($productDetails = $orderDetails?->product ?? json_decode($order_details->product_details) )
                                            <tr>
                                                <td>
                                                    <div class="media align-items-center gap-3">
                                                        <img loading="lazy" class="rounded border"
                                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$productDetails->thumbnail, type: 'product') }}"
                                                             width="100px" alt="{{ translate('product') }}">
                                                        <div>
                                                            <h6 class="title-color mb-2">{{Str::limit($productDetails->name,30)}}</h6>
                                                            <div class="d-flex flex-column">
                                                                <small>
                                                                    <strong>{{translate('unit_price')}} :</strong>
                                                                    {{\App\Utils\BackEndHelper::set_symbol(\App\Utils\BackEndHelper::usd_to_currency($order_details['price']))}}
                                                                    @if ($order_details->tax_model =='include')
                                                                        ({{translate('tax_incl.')}})
                                                                    @else
                                                                        ({{translate('tax').":".($productDetails->tax)}}{{$productDetails->tax_type ==="percent" ? '%' :''}})
                                                                    @endif
                                                                </small>
                                                                @if ($order_details->variant)
                                                                    <small><strong>{{translate('variation')}}
                                                                            :</strong> {{$order_details['variant']}}
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            @if($orderDetails->payment_status == 'paid' && $productDetails->digital_product_type == 'ready_product')
                                                                <a data-link="{{ route('digital-product-download', $order_details->id) }}"
                                                                   href="javascript:"
                                                                   class="digital_product_download_link btn btn-base rounded-pill mb-1 gap-2 mt-2"
                                                                   data-toggle="tooltip" data-placement="bottom"
                                                                   title="{{translate('download')}}">
                                                                    {{translate('download')}}
                                                                    <span class="small"><i
                                                                                class="bi bi-download"></i></span>
                                                                </a>
                                                            @elseif($orderDetails->payment_status == 'paid' && $productDetails->digital_product_type == 'ready_after_sell')
                                                                @if($order_details->digital_file_after_sell)
                                                                    <a data-link="{{ route('digital-product-download', $order_details->id) }}"
                                                                       href="javascript:"
                                                                       class="digital_product_download_link btn btn-base rounded-pill mb-1 gap-2 mt-2"
                                                                       data-toggle="tooltip" data-placement="bottom" title="{{translate('download')}}">
                                                                        {{translate('download')}}
                                                                        <span class="small"><i class="bi bi-download"></i></span>
                                                                    </a>
                                                                @else
                                                                    <span class="btn btn-base rounded-pill mb-1 gap-2 mt-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ translate('product_not_uploaded_yet') }}">
                                                                    {{translate('download')}}
                                                                    <span class="small"><i class="bi bi-download"></i></span>
                                                                </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{$order_details->qty}}
                                                </td>
                                                <td class="text-end">
                                                    {{\App\Utils\Helpers::currency_converter($order_details['price']*$order_details['qty'])}}
                                                </td>
                                            </tr>
                                            @php($sub_total+=$order_details['price']*$order_details['qty'])
                                            @php($total_tax+=$order_details['tax'])
                                            @php($total_discount_on_product+=$order_details['discount'])
                                        @endforeach
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                            @php($total_shipping_cost=$orderDetails['shipping_cost'])
                            <?php
                            if ($orderDetails['extra_discount_type'] == 'percent') {
                                $extra_discount = ($sub_total / 100) * $orderDetails['extra_discount'];
                            } else {
                                $extra_discount = $orderDetails['extra_discount'];
                            }
                            if (isset($orderDetails['discount_amount'])) {
                                $coupon_discount = $orderDetails['discount_amount'];
                            }
                            ?>

                            <div class="bg-light rounded border p3">
                                <div class="table-responsive">
                                    <table class="table __table text-end table-align-middle text-capitalize">
                                        <thead>
                                        <tr>
                                            <th class="text-muted text-nowrap">{{translate('sub_total')}}</th>
                                            @if ($orderDetails['order_type'] == 'default_type')
                                                <th class="text-muted">{{translate('shipping')}}</th>
                                            @endif
                                            <th class="text-muted">{{translate('tax')}}</th>
                                            <th class="text-muted">{{translate('discount')}}</th>
                                            <th class="text-muted text-nowrap">{{translate('coupon_discount')}}</th>
                                            @if ($orderDetails['order_type'] == 'POS')
                                                <th class="text-muted text-nowrap">{{translate('extra_discount')}}</th>
                                            @endif
                                            <th class="text-muted">{{translate('total')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-dark">
                                                {{\App\Utils\Helpers::currency_converter($sub_total)}}
                                            </td>

                                            @if ($orderDetails['order_type'] == 'default_type')
                                                <td class="text-dark">
                                                    {{\App\Utils\Helpers::currency_converter($orderDetails['is_shipping_free'] ? $total_shipping_cost-$orderDetails['extra_discount']:$total_shipping_cost)}}
                                                </td>
                                            @endif

                                            <td class="text-dark">
                                                {{\App\Utils\Helpers::currency_converter($total_tax)}}
                                            </td>
                                            <td class="text-dark">
                                                -{{\App\Utils\Helpers::currency_converter($total_discount_on_product)}}
                                            </td>
                                            <td class="text-dark">
                                                - {{\App\Utils\Helpers::currency_converter($coupon_discount)}}
                                            </td>
                                            @if ($orderDetails['order_type'] == 'POS')
                                                <td class="text-dark">
                                                    - {{\App\Utils\Helpers::currency_converter($extra_discount)}}
                                                </td>
                                            @endif
                                            <td class="text-dark">
                                                {{\App\Utils\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-($orderDetails->discount)-$total_discount_on_product - $coupon_discount - $extra_discount)}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="modal fade __sign-in-modal" id="digital_product_order_otp_verify" tabindex="-1"
         aria-labelledby="digital_product_order_otp_verifyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/tracking-page.js') }}"></script>
@endpush

