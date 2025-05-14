@php use App\Utils\Helpers; @endphp

@extends('theme-views.layouts.app')

@section('title', translate('customer_verify').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="seller-registration-section section-gap">
        <div class="container {{($user_verify == 1?'d-none':'')}}" id="otp_form_section">
            @php($email_verify_status = Helpers::get_business_settings('email_verification'))
            @php($phone_verify_status = Helpers::get_business_settings('phone_verification'))
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block">
                    <div
                        class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                        <div class="section-title w-100 text-center">
                            <h2 class="title">{{translate('OTP_Verification')}}</h2>
                            <p>
                                {{translate('please_verify_that_its_you.')}}
                            </p>
                        </div>
                        <div class="my-auto mx-auto">
                            <img loading="lazy" src="{{theme_asset('assets/img/forget-pass/verification.png')}}"
                                 class="mw-100 mb-auto d-none d-md-block"
                                 alt="{{ translate('icons') }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ps-xl-5">
                        <div class="seller-registration-content mt-md-5">
                            <div class="seller-registration-content-top text-center max-w-400">
                                <img loading="lazy" src="{{theme_asset('assets/img/forget-pass/verification-icon.png')}}"
                                     class="mw-100" alt="{{ translate('icons') }}">
                                <input type="hidden" id="user_id" value="{{$user->id}}">

                                @if($phone_verify_status == 1 )
                                    <div>
                                        {{translate('an_OTP')}} ({{translate('one_time_password')}}
                                        ) {{translate('has_been_sent_to_')}} {{$user->phone}}.
                                        {{translate('Please_enter_the_OTP_in_the_field_below_to_verify_your_phone')}}.
                                    </div>
                                @endif
                                @if ($email_verify_status==1)
                                    <div>
                                        {{ translate('an_OTP_(one_time_password)_has_been_sent_to_your_email') }}.
                                        {{ translate('Please_enter_the_OTP_in_the_field_below_to_verify_your_email') }}.
                                    </div>
                                @endif
                                <div class="text-base mt-3 resend_otp_custom">
                                    <div>{{translate('resend_code_within')}}</div>
                                    <strong class="verifyCounter" data-second="{{$get_time}}"></strong>s
                                </div>
                            </div>
                            <form action="{{ route('customer.auth.ajax_verify') }}" class="otp-form" method="POST"
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

                                <input class="otp-value" type="hidden" name="token">
                                <input type="hidden" value="{{$user->id}}" name="id">
                                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-5">
                                    <button type="button" data-url="{{route('customer.auth.resend_otp')}}"
                                            class="btn btn-base __btn-outline form-control w-auto min-w-180 resend-otp-button"
                                            id="customer_auth_resend_otp">{{translate('resend_OTP')}}</button>
                                    <button type="submit"
                                            class="btn btn-base form-control w-auto min-w-180">{{translate('verify')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container {{($user_verify != 1?'d-none':'')}}" id="success_message">
            <div class="card border-0">
                <div class="card-body p-md-5">
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-md-10">
                            <div class="text-center d-flex flex-column align-items-center gap-3">
                                <img loading="lazy" width="46" src="{{theme_asset('assets/img/icons/check.png')}}" class="dark-support"
                                     alt="{{ translate('user_verify') }}">
                                <h3 class="text-capitalize">{{translate('verification_successfully_completed')}}</h3>
                                <p class="text-muted">{{ translate('thank_you_for_your_verification')}}
                                    ! {{ translate('now_you_can_login') }}
                                    , {{ translate('your_account_is_ready_to_use') }}</p>
                                <div class="d-flex flex-wrap justify-content-center gap-3">
                                    <button class="btn btn-base __btn-outline form-control w-auto min-w-180 resend-otp-button customer_login_register_modal">{{ translate('login') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/customer-auth.js') }}"></script>
@endpush
