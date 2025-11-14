@php use App\Utils\Helpers; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('payment').' | '.$web_config['name']->value.' '.translate('ecommerce'))

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
                            <a href="{{route('shop-cart')}}">{{translate('cart')}}</a>
                        </li>
                        <li>
                            <a href="{{route('checkout-details')}}">{{translate('checkout')}}</a>
                        </li>
                        <li>
                            <a href="{{url()->current()}}">{{translate('payment')}}</a>
                        </li>
                    </ul>
                    <div class="ms-auto ms-md-0">
                        @if(auth('customer')->check())
                            <a href="{{route('shop-cart')}}" class="text-base custom-text-link">
                                {{ translate('check_All_CartList') }}
                            </a>
                        @else
                            <a href="javascript:" class="text-base custom-text-link customer_login_register_modal">
                                {{ translate('check_All_CartList') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="payment-section section-gap pt-4">
        <div class="container">
            <h3 class="d-flex justify-content-center justify-content-sm-start mb-3 mb-lg-4">{{translate('payment')}}</h3>
            <div class="row g-4">
                <div class="col-lg-7">
                    <ul class="checkout-flow">
                        <li class="checkout-flow-item active">
                            <a href="{{route('shop-cart')}}">
                                <span class="serial">{{ translate('1') }}</span>
                                <span class="icon">
                                    <i class="bi bi-check"></i>
                                </span>
                                <span class="text">{{translate('cart')}}</span>
                            </a>
                        </li>
                        <li class="line"></li>
                        <li class="checkout-flow-item active">
                            <a href="{{route('checkout-details')}}">
                                <span class="serial">{{ translate('2') }}</span>
                                <span class="icon">
                                    <i class="bi bi-check"></i>
                                </span>
                                <span class="text text-capitalize">{{translate('shipping_details')}}</span>
                            </a>
                        </li>
                        <li class="line"></li>
                        <li class="checkout-flow-item current">
                            <a href="javascript:">
                                <span class="serial">{{ translate('3') }}</span>
                                <span class="icon">
                                    <i class="bi bi-check"></i>
                                </span>
                                <span class="text">{{translate('payment')}}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="delivery-information">
                        <h6 class="font-bold letter-spacing-0 title mb-20px text-capitalize">{{translate('select_payment_method')}}</h6>
                        <div class="payment-area">
                            @if(!$cod_not_show && $cash_on_delivery['status'])
                                <label class="payment-item">
                                    <form action="{{route('checkout-complete')}}" method="get">
                                        <input type="hidden" name="payment_method" value="cash_on_delivery">
                                        <div class="payment-item-card">
                                            <img loading="lazy" src="{{theme_asset('assets/img/checkout/cash-on.png')}}" class="icon"
                                                 alt="{{ translate('checkout') }}">
                                            <button class="content bg-white border-0">
                                                <h6 class="subtitle text-start">{{translate('cash_on_delivery')}}</h6>
                                                <p class="text-start">
                                                    {{translate('please_contact_with_deliveryman_to_confirm_your_pay_and_receive_your_order.')}}
                                                </p>
                                            </button>

                                        </div>
                                    </form>
                                </label>
                            @endif

                            @if ($digital_payment['status']==1)
                                <label class="payment-item">
                                    <input type="radio" name="payment-method">
                                    <div class="payment-item-card">
                                        <div class="digital_payment_btn">
                                            <img loading="lazy" src="{{theme_asset('assets/img/checkout/card-pos.png')}}" class="icon"
                                                 alt="{{ translate('checkout') }}">
                                        </div>
                                        <div class="content">
                                            <div class="digital_payment_btn">
                                                <h6 class="subtitle">{{translate('digital_payment')}}</h6>
                                                <p>
                                                    {{translate('please_confirm_your_verified_account_to_pay_through_your_bank_account')}}
                                                </p>
                                            </div>
                                            <div class="d--none" id="digital_payment">
                                                <div class="mt-3 row d-flex gap-2">
                                                    @if(auth('customer')->check() && $wallet_status==1)
                                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                                            <label class="digital-payment-card card">
                                                                <button
                                                                    class="bg-transparent border-0 h-100 w-100 d-flex justify-content-center"
                                                                    type="submit" data-bs-toggle="modal"
                                                                    data-bs-target="#wallet_submit_button">
                                                                    <img loading="lazy" width="25"
                                                                         src="{{ theme_asset('assets/img/icons/wallet-img.png') }}"
                                                                         class="dark-support" alt="{{ translate('wallet') }}">
                                                                    <span
                                                                        class="info text-title">{{ translate('wallet') }}</span>
                                                                </button>
                                                            </label>
                                                        </div>
                                                    @endif

                                                    @foreach ($payment_gateways_list as $payment_gateway)
                                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                                            <div
                                                                class="digital-payment-card card d-flex align-items-center justify-content-center overflow-hidden">
                                                                <form method="post"
                                                                      action="{{ route('customer.web-payment-request') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id"
                                                                           value="{{ auth('customer')->check() ? auth('customer')->user()->id : session('guest_id') }}">
                                                                    <input type="hidden" name="customer_id"
                                                                           value="{{ auth('customer')->check() ? auth('customer')->user()->id : session('guest_id') }}">
                                                                    <input type="hidden" name="payment_method"
                                                                           value="{{ $payment_gateway->key_name }}">
                                                                    <input type="hidden" name="payment_platform"
                                                                           value="web">

                                                                    @if ($payment_gateway->mode == 'live' && isset($payment_gateway->live_values['callback_url']))
                                                                        <input type="hidden" name="callback"
                                                                               value="{{ $payment_gateway->live_values['callback_url'] }}">
                                                                    @elseif ($payment_gateway->mode == 'test' && isset($payment_gateway->test_values['callback_url']))
                                                                        <input type="hidden" name="callback"
                                                                               value="{{ $payment_gateway->test_values['callback_url'] }}">
                                                                    @else
                                                                        <input type="hidden" name="callback" value="">
                                                                    @endif

                                                                    <input type="hidden" name="external_redirect_link"
                                                                           value="{{ url('/').'/web-payment' }}">
                                                                    @php($additional_data = $payment_gateway['additional_data'] != null ? json_decode($payment_gateway['additional_data']) : [])
                                                                    <button class="bg-transparent border-0 h-100 w-100"
                                                                            type="submit">
                                                                        <img loading="lazy" width="100" class="dark-support" alt="{{ translate('gateway') }}"
                                                                             src="{{ getValidImage(path: 'storage/app/public/payment_modules/gateway_image/'.($additional_data != null ? ($additional_data->gateway_image ?? '') : ''), type:'banner') }}">
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    @if(isset($offline_payment) && $offline_payment['status'])
                                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                                            <form
                                                                action="{{route('offline-payment-checkout-complete')}}"
                                                                method="get">
                                                                <div class="digital-payment-card card">
                                                                    <input type="hidden" name="weight">
                                                                    <span class="bg-transparent border-0 h-100 w-100"
                                                                          data-bs-toggle="modal"
                                                                          data-bs-target="#offline_payment_submit_button">
                                                                    <img loading="lazy" width="100"
                                                                         src="{{ theme_asset('assets/img/payment/pay-offline.png') }}"
                                                                         class="dark-support" alt="{{ translate('offline_payments') }}">
                                                                </span>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex digital_payment_btn flex-wrap flex-md-nowrap justify-content-between align-items-center mt-3 row-gap-3 column-gap-3">
                                                <span>{{translate('pay_with_secure_Digital_payment_gateways')}}</span>
                                                <img loading="lazy" src="{{theme_asset('assets/img/checkout/payment-methods.png')}}"
                                                     class="mw-100" alt="{{ translate('checkout') }}">
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endif

                            @if ($digital_payment['status']==1)
                                @if(auth('customer')->check() && $wallet_status==1)
                                    <div class="modal fade" id="wallet_submit_button">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header px-sm-5">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLongTitle">{{translate('wallet_payment')}}</h5>
                                                    <button type="button" class="btn-close outside"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                @php($customer_balance = auth('customer')->user()->wallet_balance)
                                                @php($remain_balance = $customer_balance - $amount)
                                                <form action="{{route('checkout-complete-wallet')}}" method="get"
                                                      class="needs-validation">
                                                    @csrf
                                                    <div class="modal-body px-sm-5">
                                                        <div class="form-group mb-2">
                                                            <label
                                                                class="form-label mb-2">{{translate('your_current_balance')}}</label>
                                                            <input class="form-control" type="text"
                                                                   value="{{Helpers::currency_converter($customer_balance)}}"
                                                                   readonly>
                                                        </div>

                                                        <div class="form-group mb-2">
                                                            <label
                                                                class="form-label mb-2">{{translate('order_amount')}}</label>
                                                            <input class="form-control" type="text"
                                                                   value="{{Helpers::currency_converter($amount)}}"
                                                                   readonly>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label
                                                                class="form-label mb-2">{{translate('remaining_balance')}}</label>
                                                            <input class="form-control" type="text"
                                                                   value="{{Helpers::currency_converter($remain_balance)}}"
                                                                   readonly>
                                                            @if ($remain_balance<0)
                                                                <label
                                                                    class="__color-crimson">{{translate('you_do_not_have_sufficient_balance_for_pay_this_order!!')}}</label>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer py-2 gap-1 px-sm-5">
                                                        <button type="button"
                                                                class="update_cart_button fs-16 btn btn-base secondary-color"
                                                                data-bs-dismiss="modal">
                                                            {{translate('close')}}
                                                        </button>
                                                        <button type="submit"
                                                                class="update_cart_button fs-16 btn btn-base" {{$remain_balance>0? '':'disabled'}}>
                                                            {{translate('submit')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="modal fade offline-payment-modal" id="offline_payment_submit_button">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header px-sm-5">
                                                <h5 class="modal-title"
                                                    id="exampleModalLongTitle">{{translate('offline_payment')}}</h5>
                                                <button type="button" class="btn-close outside" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <form action="{{route('offline-payment-checkout-complete')}}" method="post"
                                                  class="needs-validation">
                                                @csrf
                                                <div class="modal-body p-3 p-md-5">

                                                    <div class="text-center px-5">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/offline-payments.png') }}"
                                                             alt="{{ translate('offline_payments') }}">
                                                        <p class="py-2">
                                                            {{ translate('pay_your_bill_using_any_of_the_payment_method_below_and_input_the_required_information_in_the_form') }}
                                                        </p>
                                                    </div>

                                                    <div class="">
                                                        <select class="form-select" id="pay_offline_method"
                                                                name="payment_by" required>
                                                            <option
                                                                value="">{{ translate('select_Payment_Method') }}</option>
                                                            @foreach ($offline_payment_methods as $method)
                                                                <option
                                                                    value="{{ $method->id }}">{{ translate('payment_Method') }}
                                                                    :
                                                                    {{ $method->method_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="" id="method-filed__div">
                                                        <div class="text-center py-5">
                                                            <img loading="lazy" class="pt-5"
                                                                 src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}"
                                                                 alt="{{ translate('offline_payments') }}">
                                                            <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method_first') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    @include('theme-views.partials._order-summery')
                </div>
            </div>
        </div>
    </section>

    <span id="route-pay-offline-method-list" data-route="{{ route('pay-offline-method-list') }}"></span>
    <span id="text-redirecting-to-the-payment" data-text="{{ translate('Redirecting_to_the_payment') }}"></span>

@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/payment-page.js') }}"></script>
@endpush
