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
                                    @if ($verification_by == 'email')
                                        {{ translate('please_enter_your_email_to_send_a_verification_code_for_forget_password') }}
                                    @elseif($verification_by == 'phone')
                                        {{ translate('please_enter_your_phone_number_to send_a_verification_code_for_forget_password') }}
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('customer.auth.login') }}" method="post" id="customer_login_modal"
                                autocomplete="off">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-sm-12">
                                        <label class="form-label form--label"
                                            for="login_phone">{{ translate('phone') }}</label>
                                        <input type="text" class="form-control" name="user_id" id="login_phone"
                                            value="{{ old('user_id') }}" placeholder="{{ translate('enter_phone_number') }}"
                                            required>
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="form-label form--label"
                                            for="login_password">{{ translate('password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" name="password" id="login_password"
                                                placeholder="{{ translate('ex_:_7+_character') }}" required>
                                            <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-small">
                                        <div class="d-flex justify-content-between gap-1">
                                            <label class="form-check m-0">
                                                <input type="checkbox" class="form-check-input" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ translate('remember_me') }}</span>
                                            </label>
                                            <a href="{{ route('customer.auth.recover-password') }}"
                                                class="text-base text-capitalize">{{ translate('forgot_password') }} ?</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-small">
                                        <button type="submit"
                                            class="btn btn-block btn-base text-capitalize">{{ translate('sign_in') }}</button>
                                        <div class="text-center">
                                            <div class="text-capitalize mt-3">
                                                {{ translate('enjoy_new_experience') }} <a href="javascript:"
                                                    class="text-base" data-bs-dismiss="modal" data-bs-target="#SignUpModal"
                                                    data-bs-toggle="modal">{{ translate('sign_up') }}</a>
                                            </div>
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
