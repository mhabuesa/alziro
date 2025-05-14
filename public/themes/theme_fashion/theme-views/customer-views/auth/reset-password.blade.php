@extends('theme-views.layouts.app')

@section('title', translate('reset_password').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<section class="seller-registration-section section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 d-none d-lg-block">
                <div
                    class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                    <div class="section-title w-100 text-center">
                        <h2 class="title">{{translate('reset_password')}}</h2>
                    </div>
                    <div class="my-auto">
                        <img loading="lazy" src="{{theme_asset('assets/img/forget-pass/reset-pass.png')}}" class="mw-100 mb-auto d-none d-md-block"
                            alt="img/icons">
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ps-xl-5">
                    <div class="seller-registration-content">
                        <div class="seller-registration-content-top text-center">
                            <img loading="lazy" src="{{theme_asset('assets/img/forget-pass/verification-icon.png')}}" class="mw-100" alt="{{ translate('icons') }}">
                            <div>{{translate('please_set_up_a_new_password')}}</div>
                        </div>
                        <form action="{{request('customer.auth.password-recovery')}}" class="reset-password-form" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <label class="form--label mb-2" for="email">{{translate('new_password')}}</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" min="8" class="form-control" placeholder="{{translate('ex_:_7+_character')}}">
                                        <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form--label mb-2 text-capitalize" for="email">{{translate('confirm_password')}}</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" name="confirm_password" min="8" placeholder="{{translate('ex_:_7+_character')}}">
                                        <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                    </div>
                                </div>
                                <input type="hidden" name="reset_token" value="{{$token}}" required>
                                <div class="col-sm-12">
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                                        <button type="submit" class="btn btn-base form-control w-auto min-w-180">
                                            {{translate('reset')}}
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
</section>
@endsection

