@extends('theme-views.layouts.app')

@section('title', translate('my_order').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-pane">
                <div class="mb-4">
                    <ul class="nav nav-tabs nav--tabs-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="{{route('account-oder', ['show_order'=>'ongoing'])}}" class="nav-link {{ (request('show_order')=='ongoing' || request('show_order') == null) ? 'active' : '' }}">{{translate('ongoing')}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="{{route('account-oder', ['show_order'=>'previous'])}}" class="nav-link {{ request('show_order')=='previous' ? 'active' : '' }}">{{translate('previous')}}</a>
                        </li>
                    </ul>
                    <form action="{{route('account-oder')}}" method="GET">
                        <div class="search-form-2 search-form-mobile">
                            <span class="icon d-flex">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="hidden" name="show_order" value="{{ request('show_order')}}">
                            <input type="text"  name="search" value="{{ request('search') }}" class="form-control text-title" placeholder="{{ translate('search_by_order_ID') }}" autocomplete="off">
                            <button type="submit" class="clear border-0 text-title">
                                @if (request('search') != null)
                                    <a href="{{route('account-oder',['show_order'=>request('show_order')])}}" class="text-danger" >{{translate('clear')}}</a>
                                @else
                                    <span>{{translate('search')}}</span>
                                @endif
                            </button>
                        </div>
                    </form>
                </div>

                @if ($orders->count() > 0)

                    <div class="table-responsive d-none d-md-block">
                        <table class="table __table vertical-middle __table-style-two">
                            <thead class="word-nobreak">
                                <tr>
                                    <th class="text-center">
                                        {{ translate('sl') }}
                                    </th>
                                    <th>
                                        <label class="form-check m-0">
                                            <span class="form-check-label">{{ translate('order') }}</span>
                                        </label>
                                    </th>
                                    <th class="text-center">
                                        {{ translate('qty') }}
                                    </th>
                                    <th class="text-center ">
                                        {{ translate('status') }}
                                    </th>
                                    <th class="text-center text-capitalize">
                                        {{ translate('total_price') }}
                                    </th>
                                    <th class="text-center">
                                        {{ translate('action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($orders as $key=>$order)
                                    <tr class="bg-section">
                                        <td class="text-center"> {{$orders->firstItem()+$key}}</td>
                                        <td>
                                            <div class="cart-product ">
                                                <label class="form-check thisIsALinkElement" data-linkpath="{{ route('account-order-details', ['id'=>$order->id]) }}">
                                                    @if($order->seller_is == 'seller')
                                                        <img loading="lazy" alt="{{ translate('shop') }}"
                                                        src="{{ getValidImage(path: 'storage/app/public/shop/'.(isset($order->seller->shop) ? $order->seller->shop->image:''), type:'shop') }}">
                                                    @elseif($order->seller_is == 'admin')
                                                        <img loading="lazy" alt="{{ translate('logo') }}"
                                                        src="{{ getValidImage(path: "storage/app/public/company/".$web_config['fav_icon']->value, type:'logo') }}">
                                                    @endif

                                                </label>
                                                <div class="cont">
                                                    <div  class="name text-title text-capitalize cursor-pointer thisIsALinkElement" data-linkpath="{{ route('account-order-details', ['id'=>$order->id]) }}">
                                                        {{translate('order')}}# {{$order->id}}
                                                    </div>
                                                        @if($order->seller_is == 'seller')
                                                            <div class="text-base mb-1">{{ isset($order->seller->shop) ? $order->seller->shop->name : translate('shop_not_found') }}</div>
                                                        @elseif($order->seller_is == 'admin')
                                                        <div class="text-base mb-1">{{ $web_config['name']->value }}</div>
                                                        @endif
                                                    <div class="text-muted">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $order->order_details_sum_qty }}
                                        </td>
                                        <td class="text-center">
                                            @if($order['order_status']=='failed' || $order['order_status']=='canceled' ||$order['order_status']=='returned')
                                                <span class="badge font-regular badge-soft-danger ">
                                                    {{translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                                                </span>
                                            @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                <span class="badge font-regular badge-soft-success">
                                                    {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                </span>
                                            @else
                                                <span class="badge font-regular badge-soft-warning">
                                                    {{translate($order['order_status'])}}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center column-gap-2">
                                                <span class="text-base">{{\App\Utils\Helpers::currency_converter($order['order_amount'])}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-3 align-items-center">
                                                <a href="{{ route('account-order-details', ['id'=>$order->id]) }}" class="btn-circle">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <a href="{{route('generate-invoice',[$order->id])}}" class="btn-circle border-0">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="d-flex d-md-none gap-3 flex-column">
                        @foreach($orders as $key=>$order)
                            <div class="border-bottom d-flex align-items-center justify-content-between pb-3 gap-2">
                                <div class="cart-product ">
                                    <label class="form-check">
                                        @if($order->seller_is == 'seller')
                                            <img loading="lazy" alt="{{ translate('shop') }}"
                                            src="{{ getValidImage(path: 'storage/app/public/shop/'.(isset($order->seller->shop) ? $order->seller->shop->image:''), type:'shop') }}">
                                        @elseif($order->seller_is == 'admin')
                                            <img loading="lazy" alt="{{ translate('logo') }}"
                                            src="{{ getValidImage(path: "storage/app/public/company/".$web_config['fav_icon']->value, type:'logo') }}">
                                        @endif

                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled' ||$order['order_status']=='returned')
                                            <span class="badge font-regular badge-soft-danger mt-2">
                                                {{translate($order['order_status'] =='failed' ? 'failed_to_deliver' : $order['order_status'])}}
                                            </span>
                                        @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                            <span class="badge font-regular badge-soft-success mt-2">
                                                {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                            </span>
                                        @else
                                            <span class="badge font-regular badge-soft-warning mt-2">
                                                {{translate($order['order_status'])}}
                                            </span>
                                        @endif
                                    </label>
                                    <div class="cont">
                                        <div class="name text-title text-capitalize thisIsALinkElement" data-linkpath="{{ route('account-order-details', ['id'=>$order->id]) }}">{{translate('order')}}# {{$order->id}}</div>
                                            @if($order->seller_is == 'seller')
                                                <div class="text-base mb-1">{{ isset($order->seller->shop) ? $order->seller->shop->name : translate('shop_not_found') }}</div>
                                            @elseif($order->seller_is == 'admin')
                                            <div class="text-basa mb-1">{{ $web_config['name']->value }}</div>
                                            @endif
                                        <div class="text-muted mb-1">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>

                                        <div class="text-basa mb-1">
                                            <span>{{translate('total_price')}} :
                                                <strong>{{\App\Utils\currency_converter($order['order_amount'])}}</strong>
                                            </span>
                                        </div>
                                        <div class="text-basa mb-1">
                                            <span>{{translate('qty')}} :
                                                <strong>{{ $order->order_details_sum_qty }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-3 align-items-center">
                                    <a href="{{ route('account-order-details', ['id'=>$order->id]) }}" class="btn-circle">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{route('generate-invoice',[$order->id])}}" class="btn-circle border-0">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($orders->count()>0)
                        <div class="d-flex justify-content-end w-100 overflow-auto mt-3" id="paginator-ajax">
                            {{$orders->links() }}
                        </div>
                    @endif

                @else
                    <div class="text-center pt-5 w-100">
                        <div class="text-center mb-5">
                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/order.svg') }}" alt="{{ translate('order') }}">
                            <h5 class="pt-3 pb-2 text-muted">{{translate('no_Order_Found')}}!</h5>
                            <p class="text-center text-muted">{{ translate('you_have_not_placed_any_'.request('show_order').'_order_yet') }}</p>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </section>
@endsection

@push('script')
<script>
    "use strict";
    function cancel_message() {
        toastr.info("{{translate('order_can_be_canceled_only_when_pending.')}}", {
            CloseButton: true,
            ProgressBar: true
        });
    }
</script>
@endpush
