@extends('theme-views.layouts.app')

@section('title', translate('forgot_password') . ' | ' . $web_config['name']->value . ' ' . translate('ecommerce'))

@section('content')
    <section class="seller-registration-section section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block">
                    <div
                        class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                        <div class="section-title w-100 text-center">
                            <h2 class="title text-capitalize">{{ translate('forget_password') }}</h2>
                        </div>
                        <div class="my-auto">
                            <img loading="lazy" src="{{ theme_asset('assets/img/forget-pass/forget-pass.png') }}"
                                class="mw-100 mb-auto d-none d-md-block" alt="img/icons">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ps-xl-5">
                        <div class="seller-registration-content">
                            <div class="seller-registration-content-top text-center">
                                <img loading="lazy" src="{{ theme_asset('assets/img/forget-pass/forget-icon.png') }}"
                                    class="mw-100" alt="{{ translate('icons') }}">
                                <div>
                                    {{ translate('please_enter_your_phone_number_to send_a_verification_code_for_forget_password') }}
                                </div>
                            </div>
                            <form action="{{ route('customer.auth.forgot-password') }}" class="forget-password-form"
                                method="post">
                                @csrf
                                <div class="row g-4">
                                    <div class="form-group">
                                        <label class="form--label mb-2" for="recover-phone">{{ translate('phone') }}</label>
                                        <input class="form-control" type="phone" name="identity" id="recover-phone"
                                            autocomplete="off" required>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                                            <button type="button"
                                                class="btn btn-base __btn-outline form-control w-auto min-w-180 thisIsALinkElement"
                                                data-linkpath="{{ route('customer.login') }}"
                                                class="btn btn-base __btn-outline form-control w-auto min-w-180">{{ translate('back_again') }}</button>
                                            <button type="submit"
                                                class="btn btn-base form-control w-auto min-w-180">{{ translate('verify') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
