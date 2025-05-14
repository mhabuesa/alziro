@extends('theme-views.layouts.app')

@section('title', translate('my_order_details_track_order').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="card bg-section border-0">
                <div class="card-body p-lg-4">
                    @include('theme-views.partials._order-details-head',['order'=>$orderDetails])
                    <div class="mt-4 card px-xl-5 border-0 bg-body">
                        <div class="card-body mb-xl-5">
                            @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')
                                <div class="pt-3">
                                    <div class="tracking-flow-wrapper pt-lg-3 text-capitalize">
                                        <div class="tracking-flow-item active">
                                            <div class="img">
                                                <img loading="lazy" src="{{theme_asset('assets/img/track/placed.png')}}" alt="">
                                            </div>
                                            <span class="icon"><i class="bi bi-check"></i></span>
                                            <span class="serial">1</span>
                                            <div>
                                                <span class="d-block text-title mb-2 mb-md-0">{{translate('order_placed')}}&nbsp{{$orderDetails->order_type ==  "POS" ? translate('POS_order') :''}}</span>
                                                <small class="d-block">{{date('d M, Y h:i A',strtotime($orderDetails->created_at))}}</small>
                                            </div>
                                        </div>
                                        @if ($orderDetails->order_type !=  "POS")
                                            <div class="tracking-flow-item {{($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')?'active' : ''}}">
                                                <div class="img">
                                                    <img loading="lazy" src="{{theme_asset('assets/img/track/packaging.png')}}" alt="">
                                                </div>
                                                <span class="icon"><i class="bi bi-check"></i></span>
                                                <span class="serial">2</span>
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
                                                    <img loading="lazy" src="{{theme_asset('assets/img/track/on-the-way.png')}}" alt="">
                                                </div>
                                                <span class="icon"><i class="bi bi-check"></i></span>
                                                <span class="serial">3</span>
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
                                        @endif

                                        <div class="tracking-flow-item {{($orderDetails['order_status']=='delivered')?'active' : ''}}">
                                            <div class="img">
                                                <img loading="lazy" src="{{theme_asset('assets/img/track/delivered.png')}}" alt="">
                                            </div>
                                            <span class="icon"><i class="bi bi-check"></i></span>
                                            <span class="serial">4</span>
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
                                <div class="mt-5">
                                    <div class="row justify-content-center">

                                        @php($product_type = '')
                                        @foreach ($orderDetails->orderDetails as $item)
                                            @if (json_decode($item->product_details)->product_type == 'digital' && $product_type == '')
                                                @php($product_type = 'digital')
                                            @endif

                                            @if (json_decode($item->product_details)->product_type == 'physical')
                                                @php($product_type = 'physical')
                                            @endif
                                        @endforeach

                                        @if($product_type != 'digital' && isset($orderDetails->shippingAddress))
                                        <div class="col-lg-6 col-xl-5">
                                            <address class="media gap-2">
                                            <img loading="lazy" width="20" src="{{theme_asset('assets/img/track/location.png')}}" class="dark-support" alt="{{ translate('shipping_address') }}">
                                            <div class="media-body">
                                                <div class="mb-2 fw-bold fs-16">{{translate('shipping_address')}}</div>
                                                    @if($orderDetails->shippingAddress)
                                                        @php($shipping=$orderDetails->shippingAddress)
                                                    @else
                                                        @php($shipping=json_decode($orderDetails['shipping_address_data']))
                                                    @endif
                                                    <p>
                                                        @if($shipping)
                                                            {{$shipping->address}},<br> {{$shipping->city}}, {{$shipping->zip}}
                                                        @endif
                                                    </p>
                                                </div>
                                            </address>
                                        </div>
                                        @endif
                                        @if(isset($orderDetails->billingAddress))
                                            <div class="{{ $product_type == 'digital' ?'offset-lg-2':'col-lg-6 col-xl-5' }}">
                                                <address class="media gap-2">
                                                    <img loading="lazy" width="20" src="{{theme_asset('assets/img/track/location.png')}}" class="dark-support" alt="{{ translate('billing_address') }}">
                                                    <div class="media-body">
                                                        <div class="mb-2  fw-bold fs-16">{{translate('billing_address')}}</div>
                                                        @if($orderDetails->billingAddress)
                                                            @php($billing=$orderDetails->billingAddress)
                                                        @else
                                                            @php($billing=json_decode($orderDetails['billing_address_data']))
                                                        @endif
                                                        <p>
                                                            @if($billing)
                                                                {{$billing->address}}, <br>
                                                                {{$billing->city}}
                                                                , {{$billing->zip}}
                                                            @else
                                                                {{$shipping->address}},<br>
                                                                {{$shipping->city}}
                                                                , {{$shipping->zip}}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </address>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif($orderDetails['order_status']=='returned')
                                <div class="mt-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <address class="media gap-2">
                                                <div class="media-body text-center">
                                                    <div class="badge font-regular badge-soft-danger">{{translate('product_returned')}}</div>
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
                                                        <div class="badge font-regular badge-soft-danger">{{translate('order_'.$orderDetails['order_status'])}}</div>
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
                                                    <div class="badge font-regular badge-soft-danger">{{translate('order_'.$orderDetails['order_status'])}}!{{translate('sorry_we_can`t_complete_your_order')}}</div>
                                                </div>
                                            </address>
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
