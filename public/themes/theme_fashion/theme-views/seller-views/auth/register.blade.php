@extends('theme-views.layouts.app')

@section('title',translate('vendor_apply').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('assets/css/seller-registration.css') }}">
@endpush

@section('content')
<section class="seller-registration-section section-gap">
    <div class="container">
        <div class="row">
            <input type="checkbox" id="step-2" class="d-none">
            <div class="col-lg-5">
                <div
                    class="seller-registration-thumb h-100 d-flex flex-column justify-content-between align-items-start">
                    <div class="section-title">
                        <h2 class="title text-capitalize">{{translate('vendor_registration')}}</h2>
                        <p>{{translate('create_your_own_store.')}} {{translate('already_have_store?')}} <a href="{{route('vendor.auth.login')}}"
                                class="text-base text-underline"><strong>{{translate('login')}}</strong></a>
                        </p>
                    </div>
                    <div class="step-1-data">
                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/seller-reg-1.png') }}"
                            class="mw-100 mb-auto" alt="{{ translate('vendor') }}">
                    </div>
                    <div class="step-2-data">
                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/seller-reg-2.png') }}"
                            class="mw-100 mb-auto" alt="{{ translate('vendor') }}">
                    </div>
                    <div class="text-base mt-4 mb-auto">{{translate('open_your_shop_and_start_selling').'.'.translate('create_your_own_business')}}</div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ps-xl-5">
                    <div class="seller-registration-content">
                        <ul class="seller-reg-menu">
                            <li class="active go-step-1 cursor-pointer">
                                <span class="serial">1</span> <span>{{translate('vendor_information')}}</span>
                            </li>
                            <li class="divider"></li>
                            <li class="go-step-2 btn_disabled cursor-pointer">
                                <span class="serial">2</span> <span>{{translate('shop_information')}}</span>
                            </li>
                        </ul>
                        <form id="seller-registration" action="{{route('shop.apply')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="step-1-data">
                                <div class="row g-4 text-capitalize">
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="seller_f_name">{{translate('first_name')}}</label>
                                        <input class="form-control" type="text" id="seller_f_name" name="f_name" value="{{old('f_name')}}"
                                        placeholder="{{translate('ex')}} : {{translate('Jhon')}}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="seller_l_name">{{translate('last_name')}}</label>
                                        <input class="form-control" type="text" id="seller_l_name" name="l_name" value="{{old('l_name')}}"
                                        placeholder="{{translate('ex')}} : {{(translate('Doe'))}}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="seller_email">{{translate('email')}}</label>
                                        <input class="form-control" type="text" id="seller_email" name="email" value="{{old('email')}}"
                                        placeholder="{{translate('enter_email')}}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="seller_phone">{{translate('phone')}}</label>
                                        <input class="form-control" type="text" id="seller_phone" name="phone" value="{{old('phone')}}"
                                        placeholder="{{translate('enter_phone_number')}}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="seller_password">{{translate('password')}}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control padding-inline-end-40px" name="password" id="seller_password" placeholder="{{translate('enter_password')}}" required>
                                            <div class="js-password-toggle"><i class="bi bi-eye-slash-fill"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="seller_repeat_password">{{translate('confirm_password')}}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control padding-inline-end-40px" name="confirm_password" id="seller_repeat_password" placeholder="{{translate('confirm_password')}}" required>
                                            <div class="js-password-toggle"><i class="bi bi-eye-slash-fill"></i></div>
                                        </div>
                                        <div class="password_message_div">
                                            <small class="text-danger password_message d-none">{{translate("password_does_not_match")}}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 pt-lg-3">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="upload-wrapper">
                                                <div class="thumb">
                                                    <img loading="lazy" src="{{theme_asset('assets/img/upload-img.png')}}" alt="{{ translate('image') }}">
                                                </div>
                                                <div class="remove-img">
                                                    <i class="bi bi-x-lg"></i>
                                                </div>
                                                <label>
                                                    <input type="file" id="seller_profile_pic" class="profile-pic-upload" name="image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required hidden>
                                                </label>
                                            </div>
                                            <div class="ps-3 ps-sm-4 text-text-2 w-0 flex-grow-1">
                                                <h6 class="font-bold text-uppercase mb-2">{{translate('vendor_image')}}</h6>
                                                <small>{{translate('image_ratio')}} 1:1</small>
                                                <small class="font-italic">
                                                    {{translate('NB')}}: {{translate('image_size_must_be_within_2_MB')}} <br>
                                                    {{translate('NB')}}: {{translate('image_type_must_be_within_.jpg,.png,.jpeg,.gif')}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="d-flex justify-content-sm-end proceed-cart-btn">
                                            <button type="button" class="btn btn-base form-control flex-grow-1 flex-grow-sm-0 w-auto go-step-2 mx-1 px-5 btn_disabled">{{translate('next')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step-2-data">
                                <div class="row g-4 text-capitalize">
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="shop_name">{{translate('store_name')}}</label>
                                        <input type="text" id="shop_name" class="form-control" name="shop_name"
                                        placeholder="{{translate('ex')}} : {{translate('Halar')}}" value="{{old('shop_name')}}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form--label mb-2" for="shop_address">{{translate('store_address')}}</label>
                                        <input type="text" id="shop_address" class="form-control" name="shop_address" value="{{old('shop_address')}}"
                                        placeholder="{{translate('ex')}} : {{translate('Shop-12_Road-8')}}" required>
                                    </div>
                                    <div class="col-sm-6 pt-lg-3">
                                        <div class="d-flex flex-wrap flex-column align-items-center">
                                            <div class="upload-wrapper vertical">
                                                <div class="thumb">
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/upload-img.png') }}" alt="{{ translate('image') }}">
                                                </div>
                                                <div class="remove-img">
                                                    <i class="bi bi-x-lg"></i>
                                                </div>
                                                <label>
                                                    <input type="file" id="shop_banner" name="banner" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required hidden>
                                                </label>
                                            </div>
                                            <div class="text-text-2 w-100 text-center mt-3">
                                                <h6 class="font-bold text-uppercase"><small>{{translate('store_banner')}}</small></h6>
                                                <small>{{translate('image_ratio_3:1')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pt-lg-3">
                                        <div class="d-flex flex-wrap flex-column align-items-center">
                                            <div class="upload-wrapper">
                                                <div class="thumb">
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/upload-img.png') }}" alt="{{ translate('image') }}">
                                                </div>
                                                <div class="remove-img">
                                                    <i class="bi bi-x-lg"></i>
                                                </div>
                                                <label>
                                                    <input type="file" id="store_Logo" name="logo" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required hidden>
                                                </label>
                                            </div>
                                            <div class="text-text-2 w-100 text-center mt-3">
                                                <h6 class="font-bold text-uppercase"><small>{{translate('store_logo')}}</small></h6>
                                                <small>{{translate('image_ratio_1:1')}}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 pt-lg-3">
                                        <div class="d-flex flex-wrap flex-column align-items-center">
                                            <div class="upload-wrapper vertical">
                                                <div class="thumb">
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/upload-img.png') }}" alt="{{ translate('image') }}">
                                                </div>
                                                <div class="remove-img">
                                                    <i class="bi bi-x-lg"></i>
                                                </div>
                                                <label>
                                                    <input type="file" id="bottom_banner" name="bottom_banner" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required hidden>
                                                </label>
                                            </div>
                                            <div class="text-text-2 w-100 text-center mt-3">
                                                <h6 class="font-bold text-uppercase"><small>{{translate('Store_Secondary_Banner')}}</small></h6>
                                                <small>{{translate('image_ratio_3:1')}}</small>
                                            </div>
                                        </div>
                                    </div>

                                    @if($web_config['recaptcha']['status'] == 1)
                                    <div class="col-12">
                                        <div id="recaptcha_element_seller_regi" class="w-100 mt-4" data-type="image"></div>
                                        <br/>
                                    </div>
                                    @else
                                    <div class="col-12">
                                        <div class="row py-2 mt-4">
                                            <div class="col-6 pr-2">
                                                <input type="text" class="form-control border __h-40" name="default_recaptcha_id_seller_regi"
                                                    id="default_recaptcha_id_seller_regi" value=""
                                                    placeholder="{{translate('enter_captcha_value')}}" autocomplete="off" required>
                                            </div>
                                            <div class="col-6 input-icons mb-2 rounded bg-white">
                                                <span id="re_captcha_seller_regi" class="d-flex align-items-center align-items-center cursor-pointer"
                                                      data-link="{{ route('vendor.auth.recaptcha', ['tmp'=>':dummy-id']) }}">
                                                    <img loading="lazy" src="{{ route('vendor.auth.recaptcha', ['tmp'=>1]).'?captcha_session_id=sellerRecaptchaSessionKey' }}" class="input-field rounded __h-40" id="default_recaptcha_id_regi" alt="{{ translate('recaptcha') }}">
                                                    <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-12">
                                        <div class="d-flex justify-content-md-between align-items-md-center flex-column flex-lg-row">
                                            <div class="mb-3">
                                                <label class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="seller_terms_checkbox">
                                                    <span class="form-check-label">{{ translate('i_agree_with_the') }} <a
                                                            href="{{route('terms')}}" target="_blank">{{ translate('terms_&_conditions') }}</a> </span>
                                                </label>
                                            </div>
                                            <div class="">
                                                <div class="d-flex justify-content-center proceed-cart-btn justify-content-sm-end">
                                                    <button type="button" class="btn btn-base form-control flex-grow-1 flex-grow-sm-0 w-auto go-step-1 mx-1 px-5">{{ translate('previous') }}</button>
                                                    <button type="button" class="btn btn-base form-control flex-grow-1 flex-grow-sm-0 w-auto mx-1 px-5 btn_disabled" id="seller_apply_submit">{{ translate('submit') }}</button>
                                                </div>
                                            </div>
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

@push('script')
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<script>
    "use strict";

    @if($web_config['recaptcha']['status'] == '1')
        var onloadCallback = function () {
            let reg_id = grecaptcha.render('recaptcha_element_seller_regi', {'sitekey': '{{ $web_config['recaptcha']['site_key'] }}'});

            $('#recaptcha_element_seller_regi').attr('data-reg-id', reg_id);
        };
    @else
        $('#re_captcha_seller_regi').on('click', function(){
            let genUrl = $(this).data('link');
            genUrl = genUrl.replace(":dummy-id", Math.random()) + '?captcha_session_id=sellerRecaptchaSessionKey';
            document.getElementById('default_recaptcha_id_regi').src = genUrl;
        })
    @endif

    $('#seller_apply_submit').on('click', function(){
        @if($web_config['recaptcha']['status'] == '1')
            var response = grecaptcha.getResponse($('#recaptcha_element_seller_regi').attr('data-reg-id'));
            if (response.length === 0) {
                toastr.error("{{translate('please_check_the_recaptcha')}}");
            }else{
                $('#seller-registration').submit();
            }
        @else
            if ($('#default_recaptcha_id_seller_regi').val() != '') {
                $('#seller-registration').submit();
            } else {
                toastr.error("{{translate('please_check_the_recaptcha')}}");
            }
        @endif
    });
</script>
@endpush
