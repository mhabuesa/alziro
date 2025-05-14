<div class="modal fade __sign-in-modal" id="SignInModal" tabindex="-1" aria-labelledby="SignInModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="logo">
                    <a href="javascript:">
                        <img loading="lazy" alt="{{ translate('logo') }}"
                             src="{{ getValidImage(path: 'storage/app/public/company/'.$web_config['web_logo']->value, type:'logo') }}">
                    </a>
                </div>
                <h3 class="title text-capitalize">{{ translate('sign_in') }}</h3>
                <form action="{{route('customer.auth.login')}}" method="post" id="customer_login_modal" autocomplete="off">
                    @csrf
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form-label form--label" for="login_email">
                            {{ translate('email') }} / {{ translate('phone') }}
                        </label>
                        <input type="text" class="form-control"
                        name="user_id" id="login_email" value="{{old('user_id')}}"
                        placeholder="{{translate('enter_email_or_phone_number')}}" required>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label form--label" for="login_password">{{ translate('password') }}</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" name="password" id="login_password"
                                   placeholder="{{ translate('ex_:_7+_character')}}" required>
                            <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-12 text-small">
                        <div class="d-flex justify-content-between gap-1">
                            <label class="form-check m-0">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span class="form-check-label">{{ translate('remember_me') }}</span>
                            </label>
                            <a href="{{route('customer.auth.recover-password')}}" class="text-base text-capitalize">{{ translate('forgot_password') }} ?</a>
                        </div>
                    </div>

                    @if($web_config['recaptcha']['status'] == 1)
                        <div id="recaptcha_element_customer_login" class="w-100 mt-4" data-type="image"></div>
                        <br/>
                    @else
                        <div class="row py-2 mt-4">
                            <div class="col-6 pr-2">
                                <input type="text" class="form-control border __h-40" name="default_recaptcha_id_customer_login" value=""
                                       placeholder="{{translate('enter_captcha_value')}}" autocomplete="off">
                            </div>
                            <div class="col-6 input-icons mb-2 rounded">
                                <span id="re_captcha_customer_login" class="d-flex align-items-center align-items-center">
                                    <img loading="lazy" src="{{ URL('/customer/auth/code/captcha/1?captcha_session_id=default_recaptcha_id_customer_login') }}" class="input-field rounded __h-40" id="customer_login_recaptcha_id" alt="{{ translate('captcha') }}">
                                    <i class="bi bi-arrow-repeat icon cursor-pointer p-2"></i>
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12 text-small">
                        <button type="submit" class="btn btn-block btn-base text-capitalize">{{translate('sign_in')}}</button>
                        <div class="text-center">
                            @if($web_config['social_login_text'])
                            <div class="mt-32px mb-3">
                                {{ translate('or_continue_with') }}
                            </div>
                            @endif
                            <div class="d-flex mb-32px justify-content-center gap-4">
                                @foreach ($web_config['socials_login'] as $socialLoginService)
                                    @if (isset($socialLoginService) && $socialLoginService['status']==true)
                                        <a href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/social/'.$socialLoginService['login_medium'].'.svg') }}" alt="{{ translate('social') }}">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                            <div class="text-capitalize">
                                {{ translate('enjoy_new_experience') }} <a href="javascript:" class="text-base" data-bs-dismiss="modal"
                                                        data-bs-target="#SignUpModal" data-bs-toggle="modal">{{translate('sign_up')}}</a>
                            </div>
                        </div>

                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


@if($web_config['recaptcha']['status'] == 1)
    <script type="text/javascript">
        "use strict";
        var onloadCallbackCustomerLogin = function () {
            var login_id = grecaptcha.render('recaptcha_element_customer_login', {
                'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
            });
            $('#recaptcha_element_customer_login').attr('data-login-id', login_id);
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallbackCustomerLogin&render=explicit" async
            defer></script>

@else
    <script type="text/javascript">
        "use strict";
        $('#re_captcha_customer_login').on('click', function(){
            var re_captcha = "{{ URL('/customer/auth/code/captcha') }}";
            re_captcha = re_captcha + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_customer_login';
            document.getElementById('customer_login_recaptcha_id').src = re_captcha;
        })
    </script>
@endif

<script>
    "use strict";
    $("#customer_login_modal").submit(function (e) {
        e.preventDefault();
        var customer_recaptcha = true;

        @if($web_config['recaptcha']['status'] == 1)
        var response_customer_login = grecaptcha.getResponse($('#recaptcha_element_customer_login').attr('data-login-id'));

        if (response_customer_login.length === 0) {
            e.preventDefault();
            toastr.error("{{translate('please_check_the_recaptcha')}}");
            customer_recaptcha = false;
        }
        @endif

        if(customer_recaptcha === true) {
            let form = $(this);
            $.ajax({
                type: 'POST',
                url:`{{route('customer.auth.login')}}`,
                data: form.serialize(),
                success: function (data) {
                    if (data.status === 'success') {
                        toastr.success(`{{translate('login_successful')}}`);
                        data.redirect_url !== '' ? window.location.href = data.redirect_url : location.reload();
                    } else if (data.status === 'error') {
                        data.redirect_url !== '' ? window.location.href = data.redirect_url : toastr.error(data.message);
                    }
                }
            });
        }
    });
</script>
