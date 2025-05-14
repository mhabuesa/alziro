@extends('theme-views.layouts.app')

@section('title', translate('my_wallet').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-content">
                @include('theme-views.users-profile.user-wallet._partial-my-wallet-nav-tab')
                @php($add_funds_to_wallet = getWebConfig(name: 'add_funds_to_wallet'))
                <div class="tab-content">
                    <div class="my-wallet-card mt-4 mb-32px">
                        <div class="row g-4 g-xl-5">
                            <div class="col-lg-6">
                                <div class="d-flex flex-wrap mb-3">
                                    <h6 class="trx-title letter-spacing-0 font-bold mb-3 w-100">
                                        {{ translate('my_wallet') }}
                                        @if($add_funds_to_wallet && count($add_fund_bonus_list) > 0)
                                            <span class="fs-18 float-end cursor-pointer" data-bs-toggle="modal"
                                                  data-bs-target="#howToUseModal">
                                        <span data-bs-toggle="tooltip"  data-bs-placement="left" title="{{translate('Click_here_to_see_instructions')}}">
                                                <i class="bi bi-info-circle"></i>
                                        </span>
                                    </span>
                                        @endif
                                    </h6>
                                    <div class="my-walllet-card-content-2 gap-20 w-100 ">
                                        <div class="info">
                                            <img loading="lazy"
                                                 src="{{ theme_asset('assets/img/icons/wallet-img.png') }}"
                                                 alt="{{ translate('wallet') }}">
                                            <div>{{ translate('total_balance') }}</div>
                                        </div>

                                        <div>
                                            @if ($add_funds_to_wallet)
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-base" data-bs-toggle="modal"
                                                            data-bs-target="#addFundToWallet">
                                                        <i class="bi bi-plus-circle-fill fs-18"></i>
                                                        {{ translate('add_Fund') }}
                                                    </button>
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="price">
                                                    @if ($add_funds_to_wallet)
                                                        <span class="fs-18" data-bs-toggle="tooltip"
                                                              data-bs-placement="bottom"
                                                              title="{{ translate('if_you_want_to_add_fund_to_your_wallet_then_click_add_fund_button') }}">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span>
                                                    @endif
                                                    {{\App\Utils\currency_converter($total_wallet_balance)}}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            @if($add_funds_to_wallet && count($add_fund_bonus_list) > 0)
                                <div class="col-lg-6">
                                    <div class="overflow-hidden h-100 d-flex align-items-end">
                                        <div class="recommended-slider-wrapper w-100">
                                            <div class="add-fund-slider owl-theme owl-carousel">
                                                @foreach ($add_fund_bonus_list as $bonus)
                                                    <div class="add-fund-swiper-card position-relative z-1 border border-primary rounded-3 py-4 px-5 ms-1 overflow-hidden">
                                                        <div class="item">
                                                            <div class="w-100">
                                                                <h4 class="mb-2 text-primary">{{ $bonus->title }}</h4>
                                                                <p class="mb-2 text-dark">{{ translate('valid_till') }} {{ date('d M, Y',strtotime($bonus->end_date_time)) }}</p>
                                                            </div>
                                                            <div>
                                                                @if ($bonus->bonus_type == 'percentage')
                                                                    <p>{{ translate('add_fund_to_wallet') }} {{ \App\Utils\currency_converter($bonus->min_add_money_amount) }} {{ translate('and_enjoy') }} {{ $bonus->bonus_amount }}
                                                                        % {{ translate('bonus') }}</p>
                                                                @else
                                                                    <p>{{ translate('add_fund_to_wallet') }} {{ \App\Utils\currency_converter($bonus->min_add_money_amount) }} {{ translate('and_enjoy') }} {{ \App\Utils\currency_converter($bonus->bonus_amount) }} {{ translate('bonus') }}</p>
                                                                @endif
                                                                <p class="fw-bold text-primary mb-0">{{ $bonus->description ? Str::limit($bonus->description, 50):'' }}</p>
                                                            </div>
                                                            <img loading="lazy" class="slider-card-bg-img" width="50"
                                                                 src="{{ theme_asset('assets/img/media/add_fund_vector.png') }}"
                                                                 alt="{{ translate('add_fund') }}">
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-6">
                                    <div class="my-wallet-card-content h-100">
                                        <h6 class="subtitle pt-4">{{ translate('how_to_use') }}</h6>
                                        <ul>
                                            <li>
                                                {{ translate('earn_money _o_your_wallet_by_completing_the_offer_&_challenged') }}
                                            </li>
                                            <li>
                                                {{ translate('convert_your_loyalty_points_into_wallet_money') }}
                                            </li>
                                            <li>
                                                {{ translate('admin_also_rewards_their_top_customers_with_wallet_money') }}
                                            </li>
                                            <li>
                                                {{ translate('send_your_wallet_money_while_order') }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="trx-table">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="trx-title letter-spacing-0 font-bold text-capitalize">{{ translate('transaction_history') }}</h6>

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
                                                       href="{{route('wallet')}}/?type=all">
                                                        {{translate('all_Transaction')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'order_transactions'? 'active':'' }}"
                                                       href="{{route('wallet')}}/?type=order_transactions">
                                                        {{translate('order_transactions')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'order_refund'? 'active':'' }}"
                                                       href="{{route('wallet')}}/?type=order_refund">
                                                        {{translate('order_refund')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'converted_from_loyalty_point'? 'active':'' }}"
                                                       href="{{route('wallet')}}/?type=converted_from_loyalty_point">
                                                        {{translate('converted_from_loyalty_point')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'added_via_payment_method'? 'active':'' }}"
                                                       href="{{route('wallet')}}/?type=added_via_payment_method">
                                                        {{translate('added_via_payment_method')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="text-truncate py-2 {{ request()->has('type') && request('type') == 'add_fund_by_admin'? 'active':'' }}"
                                                       href="{{route('wallet')}}/?type=add_fund_by_admin">
                                                        {{translate('add_fund_by_admin')}}
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
                                @foreach($wallet_transactio_list as $key=>$item)
                                    @if ($item['admin_bonus'] > 0)
                                        <tr>
                                            <td class="bg-section rounded">
                                                <div class="trx-history-order">
                                                    <h5 class="mb-1">{{ \App\Utils\currency_converter( $item['admin_bonus'])}}</h5>
                                                    <div>{{ translate('admin_bonus') }}</div>
                                                </div>
                                            </td>
                                            <td class="bg-section ">
                                                <div class="date word-nobreak d-none d-md-block">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                            </td>
                                            <td class=" bg-section pe-md-5 text-end rounded">
                                                <div class="date word-nobreak d-md-none">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                                <div class="text-{{ $item['credit'] ?'success': 'danger'}}">{{ $item['credit'] ? 'Credit' : 'Debit'}}</div>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="bg-section rounded">
                                            <div class="trx-history-order">
                                                <h5 class="mb-1">{{$item['credit'] ? \App\Utils\currency_converter( $item['credit']) : \App\Utils\currency_converter( $item['debit'])}}</h5>
                                                <div>
                                                    @if ($item['transaction_type'] == 'add_fund_by_admin')
                                                        {{translate('add_fund_by_admin')}}
                                                    @elseif($item['transaction_type'] == 'order_place')
                                                        {{translate('order_place')}}
                                                    @elseif($item['transaction_type'] == 'loyalty_point')
                                                        {{translate('converted_from_loyalty_point')}}
                                                    @elseif($item['transaction_type'] == 'add_fund')
                                                        {{translate('added_via_payment_method')}}
                                                    @else
                                                        {{ translate($item['transaction_type']) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="bg-section ">
                                            <div class="date word-nobreak d-none d-md-block">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                        </td>
                                        <td class=" bg-section pe-md-5 text-end rounded">
                                            <div class="date word-nobreak d-md-none">{{date('d-m-y, h:i A',strtotime($item['created_at']))}}</div>
                                            <div class="text-{{ $item['credit'] ?'success': 'danger'}}">{{ $item['credit'] ? 'Credit' : 'Debit'}}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if ($wallet_transactio_list->count() == 0)
                                <div class="w-100">
                                    <div class="text-center mb-5">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/data.svg') }}"
                                             alt="{{ translate('wallet_transaction') }}">
                                        <h5 class="my-3">{{translate('no_Transaction_Found')}}</h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if(count($wallet_transactio_list)>0)
                        <div class="d-flex justify-content-end w-100 overflow-auto mt-3" id="paginator-ajax">
                            {{$wallet_transactio_list->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($add_funds_to_wallet)
            <div class="modal fade" id="addFundToWallet" tabindex="-1" aria-labelledby="addFundToWalletModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="text-end p-3">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5">
                            <form action="{{ route('customer.add-fund-request') }}" method="post">
                                @csrf
                                <div class="pb-4">
                                    <h4 class="text-center pb-3">{{ translate('add_Fund_to_Wallet') }}</h4>
                                    <p class="text-center pb-3">{{ translate('add_fund_by_from_secured_digital_payment_gateways') }}</p>
                                    <input type="number" class="h-70 form-control text-center text-24 rounded-10"
                                           min="1" name="amount" id="add-fund-amount-input" autocomplete="off" required
                                           placeholder="{{ \App\Utils\BackEndHelper::currency_symbol() }}{{ translate('500') }}" {{ count($payment_gateways) == 0 ? 'disabled':'' }}>
                                    <input type="hidden" value="web" name="payment_platform" required>
                                    <input type="hidden" value="{{ request()->url() }}" name="external_redirect_link"
                                           required>
                                </div>

                                @if(count($payment_gateways) > 0)
                                    <div id="add-fund-list-area" class="d--none">
                                        <h5 class="mb-4">{{ translate('payment_Methods') }}
                                            <small>({{ translate('faster_&_secure_way_to_pay_bill') }})</small></h5>
                                        <div class="gatways_list">

                                            @foreach ($payment_gateways as $gateway)
                                                <label class="form-check form--check rounded">
                                                    <input type="radio" class="form-check-input d-none"
                                                           name="payment_method" value="{{ $gateway->key_name }}"
                                                           required>
                                                    <div class="check-icon">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="8" cy="8" r="8" fill="var(--bs-primary)"/>
                                                            <path d="M9.18475 6.49574C10.0715 5.45157 11.4612 4.98049 12.8001 5.27019L7.05943 11.1996L3.7334 7.91114C4.68634 7.27184 5.98266 7.59088 6.53004 8.59942L6.86856 9.22314L9.18475 6.49574Z"
                                                                  fill="white"/>
                                                        </svg>
                                                    </div>
                                                    @php( $payment_method_title = !empty($gateway->additional_data) ? (json_decode($gateway->additional_data)->gateway_title ?? ucwords(str_replace('_',' ', $gateway->key_name))) : ucwords(str_replace('_',' ', $gateway->key_name)) )
                                                    @php( $payment_method_img = !empty($gateway->additional_data) ? json_decode($gateway->additional_data)->gateway_image : '' )
                                                    <div class="form-check-label d-flex align-items-center">
                                                        <div class="gatways_list_img d-flex align-items-center">
                                                            <img loading="lazy" alt="{{ $payment_method_title }}"
                                                                 src="{{ getValidImage(path: 'storage/app/public/payment_modules/gateway_image/'.$payment_method_img, type:'banner') }}">
                                                        </div>
                                                        <span class="ms-3">{{ $payment_method_title }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center pt-5 pb-3">
                                        <button type="submit" class="btn btn-base"
                                                id="add_fund_to_wallet_form_btn">{{ translate('add_Fund') }}</button>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/data.svg') }}" alt="">
                                        <h6 class="text-muted">{{ translate('no_Configuration_Found') }}</h6>
                                    </div>
                                @endif

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="modal fade" id="howToUseModal" tabindex="-1" aria-labelledby="howToUseModalModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="my-wallet-card-content h-100 p-5">

                            <h6 class="subtitle pb-3">
                                {{ translate('how_to_use') }}
                                <span class="float-end">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </span>
                            </h6>
                            <ul>
                                <li>
                                    {{ translate('earn_money _o_your_wallet_by_completing_the_offer_&_challenged') }}
                                </li>
                                <li>
                                    {{ translate('convert_your_loyalty_points_into_wallet_money') }}
                                </li>
                                <li>
                                    {{ translate('admin_also_rewards_their_top_customers_with_wallet_money') }}
                                </li>
                                <li>
                                    {{ translate('send_your_wallet_money_while_order') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
