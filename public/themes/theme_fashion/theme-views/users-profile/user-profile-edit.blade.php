@extends('theme-views.layouts.app')

@section('title', translate('edit_my_profile').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-content">

                <div class="tab-pane fade show active __chat-area">
                    <div class="personal-details mb-4">
                        <div
                            class="d-flex flex-wrap justify-content-between align-items-center column-gap-4 row-gap-2 mb-4 ">
                            <h4 class="subtitle m-0 text-capitalize">{{ translate('edit_personal_details') }}</h4>
                            <a href="{{route('user-profile')}}"
                               class="cmn-btn __btn-outline rounded-full align-content-center">
                                <i class="bi bi-chevron-left "></i>{{ translate('go_back') }}
                            </a>
                        </div>
                        <div>
                            <div class="">
                                <form action="{{route('user-update')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-4 ">
                                        <div class="col-sm-6 text-capitalize">
                                            <label class="form--label mb-2"
                                                   for="f-name">{{ translate('first_name') }}</label>
                                            <input type="text" value="{{$customerDetail['f_name']}}" name="f_name"
                                                   class="form-control"
                                                   placeholder="{{translate('ex')}} : {{translate('Jhone')}}">
                                        </div>
                                        <div class="col-sm-6 text-capitalize">
                                            <label class="form--label mb-2"
                                                   for="l-name">{{ translate('last_name') }}</label>
                                            <input type="text" id="l-name" value="{{$customerDetail['l_name']}}"
                                                   name="l_name" class="form-control"
                                                   placeholder="{{translate('ex')}} : {{translate('Doe')}}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form--label mb-2" for="email">{{ translate('phone') }}</label>
                                            <input type="tel" id="phone" name="phone" class="form-control"
                                                   value="{{$customerDetail['phone']}}"
                                                   placeholder="{{translate('enter_phone_number')}}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form--label mb-2" for="email">{{ translate('email') }}</label>
                                            <input type="text" id="email" class="form-control"
                                                   value="{{$customerDetail['email']}}"
                                                   placeholder="{{translate('enter_email_number')}}" readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form--label mb-2">{{translate('password')}}</label>
                                            <div class="position-relative">
                                                <input type="password" minlength="6" id="password2" class="form-control"
                                                       name="password" placeholder="{{translate('ex:_7+_characters')}}">
                                                <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 position-relative text-capitalize">
                                            <label class="form--label mb-2">{{translate('confirm_password')}}</label>
                                            <div class="position-relative">
                                                <input type="password" minlength="6" id="confirm_password2"
                                                       name="confirm_password" class="form-control"
                                                       placeholder="{{translate('ex:_7+_characters')}}">
                                                <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                                            </div>
                                            <div id='message'></div>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="upload-wrapper">
                                                <div class="thumb">
                                                    <img loading="lazy" src="{{theme_asset('assets/img/upload-img.png')}}" alt="{{ translate('upload') }}">
                                                </div>
                                                <div class="remove-img">
                                                    <i class="bi bi-x-lg"></i>
                                                </div>
                                                <label>
                                                    <input type="file" class="profile-pic-upload" name="image"
                                                           hidden="">
                                                </label>
                                            </div>
                                            <div class="ps-3 ps-sm-4 text-text-2 w-0 flex-grow-1">
                                                <small>{{translate('image_ration')}} {{ translate('1') }}
                                                    :{{ translate('1') }}</small>
                                                <small class="font-italic">
                                                    {{translate('NB')}}:{{translate('image_size_must_be_within_2MB')}}
                                                    <br>
                                                    {{translate('image_format_jpg_jpeg_png')}}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div
                                                class="d-flex flex-column flex-sm-row jusitfy-content-between align-items-center gap-3 ">
                                                <button type="reset"
                                                        class="btn btn-base __btn-outline form-control reset_button min-w-180 ms-auto go-step-3">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-base  form-control  min-w-180 ms-auto go-step-2 text-capitalize">
                                                    {{translate('update_profile')}}
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
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/user-profile-edit.js') }}"></script>

    <script>
        "use strict";

        function checkPasswordMatch() {
            var password = $("#password2").val();
            var confirmPassword = $("#confirm_password2").val();
            console.log(confirmPassword);
            $("#message").removeAttr("style");
            $("#message").html("");
            if (confirmPassword == "") {
                $("#message").attr("style", "color:black");
                $("#message").html("{{translate('please_retype_password')}}");
            } else if (password == "") {
                $("#message").removeAttr("style");
                $("#message").html("");
            } else if (password != confirmPassword) {
                $("#message").html("{{translate('passwords_do_not_match')}}!");
                $("#message").attr("style", "color:red");
            } else if (confirmPassword.length <= 7) {
                $("#message").html("{{translate('password_must_be_8_character')}}");
                $("#message").attr("style", "color:red");
            } else {
                $("#message").html("{{translate('passwords_match')}}.");
                $("#message").attr("style", "color:green");
            }
        }

        $(".reset_button").on('click', function () {
            $('.thumb').empty().html(`<img src="{{theme_asset('assets/img/upload-img.png')}}" alt="{{ translate('upload') }}">`);
            $('.remove-img').addClass('d-none')
        })
    </script>
@endpush

