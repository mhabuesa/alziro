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
                        <label class="form-label form--label" for="login_phone">{{ translate('phone') }}</label>
                        <input type="text" class="form-control"
                        name="user_id" id="login_phone" value="{{old('user_id')}}"
                        placeholder="{{translate('enter_phone_number')}}" required>
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

                    <div class="col-sm-12 text-small">
                        <button type="submit" class="btn btn-block btn-base text-capitalize">{{translate('sign_in')}}</button>
                        <div class="text-center">
                            <div class="text-capitalize mt-3">
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
