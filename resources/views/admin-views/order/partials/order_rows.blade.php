@forelse ($orders as $key => $order)
    <tr class="status-{{ $order['order_status'] }} class-all">
        @if ($status == 'out_for_delivery')
            <td>
                <div class="icheck-primary d-inline">
                    <input type="checkbox" name="id[]" value="{{ $order->id }}" id="id_{{ $order->id }}">
                    <label for="id_{{ $order->id }}"></label>
                </div>
            </td>
        @endif
        <td>{{ ($page - 1) * $limit + $key + 1 }}</td>
        </td>
        <td>
            <a class="title-color" href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">{{ $order['id'] }}
                {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
        </td>
        <td>{{ $order['invoice_id'] }}</td>
        <td>
            <div>{{ date('d M Y', strtotime($order['created_at'])) }},</div>
            <div>{{ date('h:i A', strtotime($order['created_at'])) }}</div>
        </td>
        <td>
            @if ($order->is_guest)
                <strong class="title-name">{{ translate('guest_customer') }}</strong>
            @elseif($order->customer_id == 0)
                <strong class="title-name">{{ translate('walking_customer') }}</strong>
            @else
                @if ($order->customer)
                    <a class="text-body text-capitalize"
                        href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                        <strong
                            class="title-name">{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</strong>
                    </a>
                    @if ($order->customer['phone'])
                        <a class="d-block title-color"
                            href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                    @else
                        <a class="d-block title-color"
                            href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                    @endif
                @else
                    <label class="badge badge-danger fz-12">{{ translate('invalid_customer_data') }}</label>
                @endif
            @endif
        </td>
        <td>
            <span class="store-name font-weight-medium">
                @if ($order->seller_is == 'admin')
                    {{ translate('in_House') }}
                @else
                    Web
                @endif
            </span>
        </td>
        <td>
            <div>
                @php($discount = 0)
                @if (
                    $order->order_type == 'default_type' &&
                        $order->coupon_discount_bearer == 'inhouse' &&
                        !in_array($order['coupon_code'], [0, null]))
                    @php($discount = $order->discount_amount)
                @endif

                @php($free_shipping = 0)
                @if ($order->is_shipping_free)
                    @php($free_shipping = $order->shipping_cost)
                @endif
                @php($totalAmount = $order->order_amount + $discount + $free_shipping)

                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->advanced == 1 ? '0' : $totalAmount), currencyCode: getCurrencyCode()) }}
            </div>

            @if ($order->payment_status == 'paid')
                <span class="badge badge-soft-success">{{ translate('paid') }}</span>
            @else
                <span class="badge badge-soft-danger">{{ translate('unpaid') }}</span>
            @endif
        </td>
        @if ($status == 'all')
            <td class="text-center text-capitalize">
                @if ($order['order_status'] == 'pending')
                    <span class="badge badge-soft-info fz-12">
                        {{ translate($order['order_status']) }}
                    </span>
                @elseif($order['order_status'] == 'processing' || $order['order_status'] == 'out_for_delivery')
                    <span class="badge badge-soft-warning fz-12">
                        {{ str_replace('_', ' ', $order['order_status'] == 'processing' ? translate('packaging') : translate($order['order_status'])) }}
                    </span>
                @elseif($order['order_status'] == 'confirmed')
                    <span class="badge badge-soft-success fz-12">
                        {{ translate($order['order_status']) }}
                    </span>
                @elseif($order['order_status'] == 'failed')
                    <span class="badge badge-danger fz-12">
                        {{ translate('failed_to_deliver') }}
                    </span>
                @elseif($order['order_status'] == 'delivered')
                    <span class="badge badge-soft-success fz-12">
                        {{ translate($order['order_status']) }}
                    </span>
                @else
                    <span class="badge badge-soft-danger fz-12">
                        {{ translate($order['order_status']) }}
                    </span>
                @endif
            </td>
        @else
            <td class="text-capitalize">
                {{ str_replace('_', ' ', $order['payment_method']) }}
            </td>
        @endif
        @if ($status == 'scheduled_delivery')
            <?php
            $isDueOrToday = \Carbon\Carbon::parse($order->scheduled_date)->isSameDay(today()) || \Carbon\Carbon::parse($order->scheduled_date)->isPast();

            ?>

            <td class="text-center text-capitalize">
                <span class="badge badge-{{ $isDueOrToday ? 'danger' : 'soft-success' }} fz-12">
                    {{ $order->scheduled_date }}
                </span>
            </td>
        @endif
        <td class="text-center text-capitalize">
            @if ($order->seller_id != 0)
                <span class="badge badge-soft-success fz-12">
                    {{ App\Models\Admin::where('id', $order->seller_id)->value('name') }}
                </span>
            @else
                <span class="badge badge-success fz-12">
                    Customer
                </span>
            @endif
        </td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{ translate('view') }}"
                    href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/eye.svg') }}" class="svg"
                        alt="">
                </a>
                <a class="btn btn-outline-success square-btn btn-sm mr-1" target="_blank"
                    title="{{ translate('invoice') }}" href="{{ route('admin.invoice', [$order['id']]) }}">
                    <i class="tio-receipt"></i>
                </a>
                <button type="button" class="btn btn-outline-danger square-btn btn-sm mr-1 delete-confirm"
                    title="Transfer to delivery" data-id="{{ $order['id'] }}"
                    data-url="{{ route('admin.orderDelete') }}">
                    <i class="tio-delete"></i>
                </button>
                @if ($status == 'confirmed')
                    <div class="dropdown ">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="tio-truck"></i>
                        </button>
                        <div class="dropdown-menu p-0" style="width: 180px" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('admin.steadfast.page', $order['id']) }}">
                                <img src="https://i.postimg.cc/L5ngqsDS/images-removebg-preview.png"
                                    style="height: 20px" alt="">
                                Steadfast Courier
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.pathao.page', $order['id']) }}">
                                <img src="https://i.postimg.cc/0NFTgW4C/pathao-logo-png-seeklogo-504561-removebg-preview.png"
                                    style="height: 20px" alt="">
                                Pathao Courier
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="20">
            <div class="text-center pt-4">
                <h5>{{ translate('no_data_found') }}</h5>
            </div>
        </td>
    </tr>

@endforelse
