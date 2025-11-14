<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="">
        <h5 class="mb-1">{{translate('order')}} # {{$order['id']}}
            @if ($order['order_status']=='pending')
                <a href="javascript:" class="btn btn-outline-danger btn-sm mx-3 route_alert_function"
                        data-routename="{{ route('order-cancel',[$order->id]) }}"
                        data-message="{{ translate('want_to_cancel_this_order?') }}"
                        data-typename="order-cancel">
                    {{ translate('cancel_order') }}
                </a>
            @endif

        </h5>
        <p class="fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</p>
    </div>

    <div>
        <div class="gap-3 d-flex justify-content-end text-capitalize mb-2">
            @if($order->order_status=='delivered' && $order->order_type == 'default_type')
                <a href="javascript:" data-orderid="{{ $order->id }}" class="btn btn-base rounded-pill order_again_function">{{ translate('reorder') }}</a>
            @endif
        </div>
        <div class="d-flex gap-3 justify-content-between align-items-center text-capitalize mb-2">
            <span>{{translate('order_status')}}</span>
            @if($order['order_status']=='failed' || $order['order_status']=='canceled' || $order['order_status']=='returned')
                <span class="badge font-regular badge-soft-danger ">
                    {{translate($order['order_status'] =='failed' ? 'failed_to_deliver' : $order['order_status'])}}
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
        </div>
        <div class="d-flex gap-3 justify-content-between align-items-center text-capitalize mb-2">
            <span>{{translate('Payment_Status')}}</span>

            <span class="badge font-regular {{ $order['payment_status']=='unpaid' ? 'badge-soft-warning':'badge-soft-success' }}">
                {{ translate($order['payment_status']) }}
            </span>

        </div>
        @if($order->order_type == 'default_type' && getWebConfig(name: 'order_verification'))
            <div class="d-flex gap-3 justify-content-between align-items-center text-capitalize mb-2">
                <span>{{translate('Verification_Code')}}</span>
                <span class="badge font-regular bg-base">
                    {{ $order['verification_code'] }}
                </span>
            </div>
        @endif

        @if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
        @foreach ($order->offlinePayments->payment_info as $key=>$item)
            @if ($key != 'method_id' && $key != 'method_name')
                <div class="d-flex gap-3 justify-content-between align-items-center text-capitalize mb-2">
                    <span>{{translate($key)}} :</span>
                    <span class="badge font-regular badge-soft-success">{{ $item }}</span>
                </div>
            @endif
        @endforeach
    @endif
    </div>
</div>
<div class="mt-4 text-capitalize">
    <ul class="nav nav-tabs nav--tabs-2" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}" class="nav-link {{Request::is('account-order-details')  ? 'active' :''}}">{{translate('order_summary')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('account-order-details-vendor-info', ['id'=>$order->id]) }}" class="nav-link {{Request::is('account-order-details-vendor-info')  ? 'active' :''}}">{{translate('vendor_info')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('account-order-details-delivery-man-info', ['id'=>$order->id]) }}" class="nav-link {{Request::is('account-order-details-delivery-man-info')  ? 'active' :''}}" >{{translate('delivery_man_info')}}</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('track-order.order-wise-result-view',['order_id'=>$order['id']])}}" class="nav-link {{Request::is('track-order/order-wise-result-view*')  ? 'active' :''}}" >{{translate('track_order')}}</a>
        </li>
    </ul>

</div>
