@extends('theme-views.layouts.app')

@section('title', translate('OTP_verification').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<section class="seller-registration-section section-gap">
    <div class="container" id="otp_form_section">
        <div class="row">
            <div class="col-lg-5 d-none d-lg-block">
                <div
                    class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                    <div class="section-title w-100 text-center">
                        <h2 class="title">{{translate('OTP_verification')}}</h2>
                        <p class="text-capitalize">
                        {{translate('please_verify_that_its_you.')}}
                        </p>
                    </div>
                    <div class="my-auto mx-auto">
                        <img loading="lazy" src="{{theme_asset('assets/img/forget-pass/verification.png')}}" class="mw-100 mb-auto d-none d-md-block"
                            alt="img/icons">
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ps-xl-5">
                    <div class="seller-registration-content mt-md-5">
                        <div class="seller-registration-content-top text-center max-w-400">
                            <img loading="lazy" src="{{theme_asset('assets/img/forget-pass/verification-icon.png')}}" class="mw-100" alt="{{ translate('icons') }}">
                            <input type="hidden" value="{{ request('identity') }}" id="request_identity">
                            <div>
                                {{translate('an_OTP')}} ({{translate('one_time_password')}}) {{translate('has_been_sent_to_')}} {{ request('identity') }}.
                                {{translate('please_enter_the_OTP_in_the_field_below_to_verify_your_phone')}}.
                            </div>
                            <div class="text-base mt-3 resend_otp_custom">
                                <div>{{translate('resend_code_within')}}</div>
                                <strong class="verifyCounter" data-second="{{$time_count}}"></strong>s
                            </div>
                        </div>
                        <form action="{{ route('customer.auth.otp-verification') }}" class="otp-form" method="POST"
                          id="customer_verify">
                            @csrf
                            <div class="d-flex gap-2 gap-sm-3 align-items-end justify-content-center">
                                <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                    autocomplete="off">
                                <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                    autocomplete="off">
                                <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                    autocomplete="off">
                                <input class="otp-field" type="text" name="opt-field[]" maxlength="1"
                                    autocomplete="off">
                            </div>

                            <input class="otp-value" type="hidden" name="otp">
                            <input class="identity" type="hidden" name="identity" value="{{ request('identity') }}">
                            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-5">
                                <button type="button" data-route="{{ route('customer.auth.resend-otp-reset-password') }}"
                                    class="btn btn-base __btn-outline form-control w-auto min-w-180 resend-otp-button" id="customer_auth_resend_otp_reset_password">{{translate('resend_OTP')}}</button>
                                <button type="submit" class="btn btn-base form-control w-auto min-w-180">{{translate('verify')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="{{ theme_asset('assets/js/customer-auth-reset-password.js') }}"></script>
@endpush
