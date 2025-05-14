@extends('theme-views.layouts.app')

@section('title', 'Signin | ' . $web_config['name']->value . ' ' . translate('ecommerce'))

@section('content')
    <section class="seller-registration-section section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block">
                    <div
                        class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                        <div class="section-title w-100 text-center">
                            <h2 class="title text-capitalize">{{ translate('sign_in') }}</h2>
                        </div>
                        <div class="my-auto">
                            <img loading="lazy" width="200" src="https://cdn-icons-png.flaticon.com/512/15268/15268536.png"
                                class="mw-100 mb-auto d-none d-md-block" alt="img/icons">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ps-xl-5">
                        <div class="seller-registration-content">
                            <div class=" text-center">
                                <img loading="lazy" width="50"
                                    src="https://cdn-icons-png.flaticon.com/512/5645/5645053.png" class="mw-100"
                                    alt="{{ translate('icons') }}">
                                <div class="text-capitalize">
                                    {{ translate('please_fill_your_login_information') }}
                                </div>
                                <div class="text-capitalize mt-3 fs-6 px-5 text-danger">
                                    @if (session('from_others'))
                                        {{ session('from_others') }}
                                    @endif
                                </div>
                                <div class="text-capitalize px-5 text-danger">
                                    @if (session('from_others2'))
                                        {{ session('from_others2') }}
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('customer.auth.login.submit') }}" method="post"
                                id="customer_login_modal" autocomplete="off">
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
                                                {{ translate('enjoy_new_experience') }} <a
                                                    href="{{ route('customer.sign-up') }}"
                                                    class="text-base">{{ translate('sign_up') }}</a>
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
