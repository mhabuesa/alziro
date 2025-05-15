@extends('theme-views.layouts.app')
@push('css_or_js')
    <style>
        .tracking-flow-wrapper .tracking-flow-item .img img {
            max-width: 57px;
        }
        .tracking-flow-item::after,
        .tracking-flow-item::before {
            content: "";
            position: absolute;
            height: 0.1428571429rem;
            background: #d9d9d9;
            width: 50%;
            top: 5.857143rem;
        }
    </style>
@endpush

@section('title', translate('my_order_details_track_order') . ' | ' . $web_config['name']->value . ' ' .
    translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="card bg-section border-0">
                <div class="card-body p-lg-4">
                    @include('theme-views.partials._order-details-head', ['order' => $orderDetails])
                    <div class="mt-4 card px-xl-5 border-0 bg-body">
                        <div class="card-body mb-xl-5">
                            @php
                                $statuses = [
                                    'pending' => 'Order Placed',
                                    'review_to_deliver' => 'On Review',
                                    'confirmed' => 'Order Confirmed',
                                    'out_for_delivery' => 'Out for Delivery',
                                    'delivered' => 'Delivered',
                                ];

                                // যদি scheduled_date থাকে তাহলে ঐ স্ট্যাটাস add করবো
                                if (!empty($orderDetails['scheduled_date'])) {
                                    $statuses =
                                        array_slice($statuses, 0, 2, true) + [
                                            'scheduled_delivery' => 'Delivery Scheduled',
                                        ] +
                                        array_slice($statuses, 2, null, true);
                                }

                                $statusIcons = [
                                    'pending' => 'https://cdn-icons-png.flaticon.com/512/3500/3500833.png',
                                    'review_to_deliver' => 'https://i.postimg.cc/bNK4hFmg/on-review.png',
                                    'confirmed' => 'https://i.postimg.cc/GtfV9Mdc/confirmed.png',
                                    'scheduled_delivery' => 'https://cdn-icons-png.flaticon.com/512/3213/3213058.png',
                                    'out_for_delivery' => 'https://cdn-icons-png.flaticon.com/512/8119/8119649.png',
                                    'delivered' => 'https://cdn-icons-png.flaticon.com/512/3847/3847902.png',
                                ];

                                $currentStatus = $orderDetails['order_status'];
                                $statusKeys = array_keys($statuses);
                            @endphp


                            @if (!in_array($currentStatus, ['returned', 'failed', 'canceled']))
                                <div class="pt-3">
                                    <div class="tracking-flow-wrapper pt-lg-3 text-capitalize">
                                        @foreach ($statuses as $key => $title)
                                            @php
                                                // only active if current status matches, or already passed
                                                $isActive =
                                                    $key === 'scheduled_delivery'
                                                        ? !empty($orderDetails['scheduled_date'])
                                                        : array_search($key, $statusKeys) <=
                                                            array_search($currentStatus, $statusKeys);
                                            @endphp

                                            <div class="tracking-flow-item {{ $isActive ? 'active' : '' }}">
                                                <div class="img">
                                                    <img loading="lazy" src="{{ $statusIcons[$key] }}"
                                                        alt="{{ $title }}">
                                                </div>
                                                <span class="icon"><i class="bi bi-check"></i></span>
                                                <span class="serial">{{ $loop->iteration }}</span>
                                                <div>
                                                    <span class="d-block text-title mb-2 mb-md-0">{{ $title }}</span>
                                                    @if ($isActive && \App\Utils\order_status_history($orderDetails['id'], $key))
                                                        <small class="d-block">
                                                            {{ date('d M, Y h:i A', strtotime(\App\Utils\order_status_history($orderDetails['id'], $key))) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mt-5">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <address class="media gap-2">
                                                <div class="media-body text-center">
                                                    <div class="badge font-regular badge-soft-danger">
                                                        {{ translate('order_' . $currentStatus) }} –
                                                        {{ translate('sorry_we_can`t_complete_your_order') }}
                                                    </div>
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
