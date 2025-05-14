@extends('theme-views.layouts.app')

@section('title', translate('contact_us').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-5">
        <div class="container">
            <div class="card">
                <div class="card-body px-lg-5 text-capitalize">
                    <h2 class="text-center mb-5 mt-4 fs-30">{{ translate('get_in') }}<span class="text-base">{{ translate('touch') }}</span></h2>
                    <div class="row g-4 mb-5 pb-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="media gap-3">
                                <div class="px-3 py-2 bg-base rounded">
                                    <i class="bi bi-phone-fill fs-4 text-white"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-2">{{ translate('call_us') }}</h4>
                                    <a class="fs-18 text-dark" href="tel:{{$web_config['phone']->value}}">{{$web_config['phone']->value}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="media gap-3">
                                <div class="px-3 py-2 bg-base rounded">
                                    <i class="bi bi-envelope-fill fs-4 text-white"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-2">{{ translate('mail_us') }}</h4>
                                    <a class="text-dark" href="mailto:{{$web_config['email']->value}}">{{$web_config['email']->value}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="media gap-3">
                                <div class="px-3 py-2 bg-base rounded">
                                    <i class="bi bi-pin-map-fill fs-4 text-white"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-2">{{ translate('find_us') }}</h4>
                                    <p>{{ getWebConfig(name: 'shop_address') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pb-3">
                        <div class="col-lg-8 order-1">
                            <form action="{{route('contact.store')}}" method="POST" id="getResponse">
                                @csrf
                                <div class="row">

                                    <div class="col-sm-12 col-md-6 form-group mb-4">
                                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ translate('name') }}" required>
                                    </div>
                                    <div class="col-sm-12 col-md-6 form-group mb-4">
                                        <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{ translate('email_address') }}" required>
                                    </div>
                                    <div class="col-sm-12 col-md-6 form-group mb-4">
                                        <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control" placeholder="{{ translate('contact_number') }}">
                                    </div>
                                    <div class="col-sm-12 col-md-6 form-group mb-4">
                                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="{{ translate('short_title') }}">
                                    </div>

                                </div>
                                <div class="form-group mb-4">
                                    <textarea name="message" id="message" class="form-control" rows="6" placeholder="{{ translate('type_your_message_here') }}..." required>{{ old('message') }}</textarea>
                                </div>
                                @if(isset($recaptcha) && $recaptcha['status'] == 1)
                                    <div id="recaptcha_element_contact" class="w-100" data-type="image"></div>
                                    <br/>
                                @else
                                    <div class="row p-2">
                                        <div class="col-6 pr-0">
                                            <input type="text" class="form-control form-control-lg" name="default_captcha_value" value=""
                                                   placeholder="{{translate('enter_captcha_value')}}" autocomplete="off">
                                        </div>
                                        <div class="col-6 input-icons rounded">
                                            <span class="cursor-pointer" id="re_captcha_contact_page">
                                                <img loading="lazy" src="{{ URL('/contact/code/captcha/1') }}" class="input-field __h-40" id="default_recaptcha_id">
                                                <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-base rounded px-5 py-2">{{ translate('submit') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 mb-4 mb-lg-0 order-0 order-lg-2">
                            <div class="d-flex justify-content-lg-end">
                                @include('theme-views.partials.icons.contact-us-img')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    @if(isset($recaptcha) && $recaptcha['status'] == 1)
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script type="text/javascript">
            "use strict";

            var onloadCallback = function () {
                grecaptcha.render('recaptcha_element_contact', {
                    'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
                });
            };

            $("#getResponse").on('submit', function (e) {
                var response = grecaptcha.getResponse();

                if (response.length === 0) {
                    e.preventDefault();
                    toastr.error("{{ translate('please_check_the_recaptcha') }}");
                }
            });
        </script>
    @else
        <script type="text/javascript">
            "use strict";

            $('#re_captcha_contact_page').on('click', function(){
                let url = "{{ URL('/contact/code/captcha') }}";
                url = url + "/" + Math.random();
                document.getElementById('default_recaptcha_id').src = url;
            })
        </script>
    @endif
@endpush

