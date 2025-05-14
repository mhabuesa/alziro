@extends('theme-views.layouts.app')

@section('title', translate('my_loyalty_point').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-content">
                @include('theme-views.users-profile.user-wallet._partial-my-wallet-nav-tab')
                <div class="tab-content mb-3">
                    <div class="my-wallet-card mt-4 mb-32px">
                        <div class="row g-4 g-xl-5">
                            <div class="col-lg-7">
                                <h6 class="trx-title letter-spacing-0 font-bold mb-3">{{ translate('my_loyalty_point') }}</h6>
                                <div class="my-walllet-card-content-2">
                                    <div class="info">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/coin.png') }}"
                                             alt="">
                                        <div>{{ translate('total_point') }}</div>
                                    </div>
                                    <h3 class="price">{{ $totalLoyaltyPoint ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="my-wallet-card-content h-100">
                                    <h6 class="subtitle">{{ translate('how_to_use') }}</h6>
                                    <ul>
                                        <li>
                                            {{ translate('convert_your_loyalty_point_to_wallet_money.') }}
                                        </li>
                                        <li>
                                            {{ translate('minimum')}} {{ $loyaltyPointMinimumPoint }} {{ translate('_points_required_to_convert_into_currency') }}
                                        </li>
                                    </ul>
                                    @if ($walletStatus == 1 && $loyaltyPointStatus == 1)
                                    <div class="mt-3">
                                        <a href="#currency_convert" data-bs-toggle="modal"
                                           class="btn btn-base btn-sm text-capitalize">
                                            <i class="bi bi-currency-exchange"></i>
                                            {{ translate('convert_to_currency') }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="trx-table">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="trx-title letter-spacing-0 font-bold text-capitalize">{{ translate('point_transaction_history') }}</h6>

                            <div>
                                <ul class="header-right-icons">
                                    <li class="dropdown">
                                        <a href="javascript:" class="border rounded p-2 px-3" data-bs-toggle="dropdown">
                                            {{ request()->has('type') ? (request('type') == 'all'? translate('all_Transactions') : ucwords(translate(request('type')))):translate('all_Transactions')}}
                                            <i class="ms-1 text-small bi bi-chevron-down ps-1"></i>
                                        </a>
                                        <div class="dropdown-menu __dropdown-menu p-0">
                                            <ul class="order_transactions">
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'all'? 'active':'' }}"
                                                       href="{{route('loyalty')}}/?type=all">
                                                        {{translate('all_Transaction')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'all'? 'active':'' }}"
                                                       href="{{route('loyalty')}}/?type=order_place">
                                                        {{translate('order_Place')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'all'? 'active':'' }}"
                                                       href="{{route('loyalty')}}/?type=point_to_wallet">
                                                        {{translate('point_To_Wallet')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'all'? 'active':'' }}"
                                                       href="{{route('loyalty')}}/?type=refund_order">
                                                        {{translate('refund_order')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table __table table-borderless centered--table vertical-middle text-text-2">
                                <tbody>
                                @foreach($loyaltyPointList as $key => $item)
                                    <tr>
                                        <td class="bg-section rounded">
                                            <div class="trx-history-order">
                                                <h5 class="mb-2">{{$item['credit'] ?  :  $item['debit']}}</h5>
                                                <div>{{ ucfirst(str_replace('_', ' ',$item['transaction_type'])) }}</div>
                                            </div>
                                        </td>
                                        <td class="bg-section">
                                            <div
                                                class="date word-nobreak d-none d-md-block">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                        </td>
                                        <td class=" bg-section pe-md-5 text-end rounded">
                                            <div
                                                class="date word-nobreak d-md-none mb-2 small">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                            <div
                                                class="text-{{ $item['credit'] ?'success': 'danger'}}">{{ $item['credit'] ? 'Earned' : 'Exchange'}}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if ($loyaltyPointList->count() == 0)
                                <div class="w-100">
                                    <div class="text-center mb-5">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/data.svg') }}"
                                             alt="{{ translate('loyalty_point') }}">
                                        <h5 class="my-3">{{translate('no_Transaction_Found')}}</h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($loyaltyPointList->count()>0)
                        <div class="d-flex justify-content-end w-100 overflow-auto mt-3" id="paginator-ajax">
                            {{ $loyaltyPointList->links() }}
                        </div>
                    @endif
                </div>

                <div id="currency_convert" class="modal fade __modal">
                    <div class="modal-dialog modal-dialog-centered max-430">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0 pt-2 justify-content-end">
                                <button data-bs-dismiss="modal"
                                        class="border-0 p-0 m-0 border-0 text-text-2 bg-transparent">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('loyalty-exchange-currency')}}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-sm-12 text-center">
                                            <div class="mb-3 text-capitalize">
                                                {{translate('enters_point_amount')}}
                                            </div>
                                            <div class="shadow-sm p-3 rounded">
                                                <div class="text-base mb-2">
                                                    {{translate('convert_point_to_wallet_money')}}
                                                </div>
                                                <input class="form-control exchange-amount-input" type="number"
                                                       id="exchange-amount-input"
                                                       data-loyaltypointexchangerate="{{ $loyaltyPointExchangeRate }}"
                                                       data-route="{{ route('ajax-loyalty-currency-amount') }}"
                                                       name="point" required>
                                                <div class="text-base mt-2">
                                                <span class="converted_amount_text d-none">{{translate('converted_amount')}} =
                                                    <small class="converted_amount"></small>
                                                </span>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="exchange-note">
                                                <h6 class="text-base mb-1">{{translate('note')}} : </h6>
                                                <ul>
                                                    <li>
                                                        {{translate('only_earning_point_can_converted_to_wallet_money')}}
                                                    </li>
                                                    <li class="d-flex gap-4px">
                                                        <span> {{ $loyaltyPointExchangeRate }} </span>
                                                        <span> {{translate('point_is_equal_to')}} </span>
                                                        <span>{{\App\Utils\Helpers::currency_converter(1)}}</span>
                                                    </li>
                                                    <li>
                                                        {{translate('Once_you_convert_the_point_into_money_then_it_won`t_back_to_point')}}
                                                    </li>
                                                    <li>
                                                        {{translate('point_can_earn_by_placing_order_or_referral')}}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="btn--container justify-content-center">
                                                <button class="btn btn-base" type="submit">
                                                    <i class="bi bi-currency-exchange"></i>
                                                    {{ translate('convert_to_currency') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
