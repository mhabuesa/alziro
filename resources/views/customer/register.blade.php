@extends('theme-views.layouts.app')
@section('title', translate('registration') . ' | ' . $web_config['name']->value . ' ' . translate('ecommerce'))
@section('content')
    <section class="seller-registration-section section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block">
                    <div
                        class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                        <div class="section-title w-100 text-center">
                            <h2 class="title text-capitalize">{{ translate('sign_up') }}</h2>
                        </div>
                        <div class="my-auto">
                            <img loading="lazy" width="300" src="https://cdn-icons-png.flaticon.com/512/11069/11069063.png"
                                class="mw-100 mb-auto d-none d-md-block" alt="img/icons">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ps-xl-5">
                        <div class="seller-registration-content">
                            <div class="seller-registration-content-top text-center">
                                <img loading="lazy" width="50" src="https://cdn-icons-png.flaticon.com/512/16470/16470225.png"
                                    class="mw-100" alt="{{ translate('icons') }}">
                                <div class="text-capitalize">
                                    {{ translate('please_fill_your_full_information_to_create_a_new_account') }}
                                </div>
                            </div>
                            <form action="{{ route('customer.auth.sign-up.submit') }}" method="POST" id="customer_sign_up_form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-sm-12 text-capitalize">
                                        <label class="form-label form--label" for="name"> Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter You Full Name" value="{{ old('name') }}" required />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label form--label" for="phone">{{ translate('phone') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" id="phone" value="{{ old('phone') }}" name="phone"
                                            class="form-control" placeholder="{{ translate('enter_phone_number') }}"
                                            required autocomplete="on" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label form--label" for="r_email">Email <span
                                                class="text-muted">(Optional)</span></label>
                                        <input type="email" id="r_email" value="{{ old('email') }}" name="email"
                                            class="form-control" placeholder="{{ translate('enter_email_address') }}"
                                            autocomplete="on" />
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="form-label form--label"
                                            for="address">{{ translate('address') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="address" value="{{ old('address') }}" name="address"
                                            class="form-control" placeholder="{{ translate('enter_address') }}"
                                            autocomplete="on" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label form--label"
                                            for="password">{{ translate('password') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="{{ translate('minimum_8_characters_long') }}" required />
                                            <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label form--label text-capitalize"
                                            for="confirm_password">{{ translate('confirm_password') }} <span
                                                class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" id="confirm_password" class="form-control"
                                                name="con_password"
                                                placeholder="{{ translate('minimum_8_characters_long') }}" required />
                                            <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 text-small">
                                        {{ translate('by_clicking_sign_up_you_are_agreed_with_our') }} <a
                                            href="{{ route('terms') }}"
                                            class="text-base text-capitalize">{{ translate('terms_&_policy') }}</a>
                                    </div>

                                    <div class="col-sm-12 text-small">
                                        <button type="submit"
                                            class="btn btn-block btn-base text-capitalize">{{ translate('sign_up') }}</button>
                                        <div class=" text-center">
                                            <div class="text-capitalize mt-3">
                                                {{ translate('have_an_account') }}?
                                                <a href="{{ route('customer.login') }}" class="text-base text-capitalize">
                                                    {{ translate('sign_in') }}
                                                </a>
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
