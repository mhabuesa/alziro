<div class="modal fade __sign-up-modal" id="SignUpModal" tabindex="-1" aria-labelledby="SignUpModalLabel"
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
                <h3 class="title text-capitalize">{{translate('sign_up')}}</h3>
                <form action="{{ route('customer.auth.sign-up') }}" method="POST" id="customer_sign_up_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-12 text-capitalize">
                            <label class="form-label form--label" for="name"> Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Enter You Full Name" value="{{old('name')}}" required />
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form--label" for="phone">{{ translate('phone') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" id="phone" value="{{old('phone')}}" name="phone" class="form-control"
                                placeholder="{{ translate('enter_phone_number') }}" required autocomplete="on"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form--label" for="r_email">Email <span class="text-muted">(Optional)</span></label>
                            <input type="email" id="r_email" value="{{old('email')}}" name="email" class="form-control"
                                placeholder="{{ translate('enter_email_address') }}" autocomplete="on"/>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form--label" for="password">{{ translate('password') }}</label>
                            <div class="position-relative">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="{{ translate('minimum_8_characters_long') }}" required />
                                <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label form--label text-capitalize"
                                for="confirm_password">{{ translate('confirm_password') }}</label>
                            <div class="position-relative">
                                <input type="password" id="confirm_password" class="form-control" name="con_password"
                                    placeholder="{{ translate('minimum_8_characters_long') }}" required />
                                <div class="js-password-toggle"><i class="bi bi-eye-fill"></i></div>
                            </div>
                        </div>

                        <div class="col-sm-12 text-small">
                            {{translate('by_clicking_sign_up_you_are_agreed_with_our')}} <a href="{{route('terms')}}"
                                class="text-base text-capitalize">{{ translate('terms_&_policy') }}</a>
                        </div>

                        <div class="col-sm-12 text-small">
                            <button type="submit"
                                class="btn btn-block btn-base text-capitalize">{{ translate('sign_up') }}</button>
                            <div class=" text-center">
                                <div class="text-capitalize mt-3">
                                    {{translate('have_an_account')}}?
                                    <a href="javascript:" class="text-base text-capitalize" data-bs-dismiss="modal"
                                        data-bs-target="#SignInModal" data-bs-toggle="modal">
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


<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallbackCustomerRegi&render=explicit" async defer></script>

<script>
    "use strict";

    @if($web_config['recaptcha']['status'] == '1')
    var onloadCallbackCustomerRegi = function () {
        let reg_id = grecaptcha.render('recaptcha_element_customer_regi', {
            'sitekey': '{{ getWebConfig(name: 'recaptcha')['site_key'] }}'
        });
        $('#recaptcha_element_customer_regi').attr('data-reg-id', reg_id);
    };

    function recaptcha_f(){
        let response = grecaptcha.getResponse($('#recaptcha_element_customer_regi').attr('data-reg-id'));
        if (response.length === 0) {
            return false;
        }else{
            return true;
        }
    }
    @else
    $('#re_captcha_customer_regi').on('click', function(){
        let re_captcha_regi_url = "{{ URL('/customer/auth/code/captcha') }}";
        re_captcha_regi_url = re_captcha_regi_url + "/" + Math.random()+'?captcha_session_id=default_recaptcha_id_customer_regi';
        document.getElementById('customer_regi_recaptcha_id').src = re_captcha_regi_url;
    })
    @endif

    $('#customer_sign_up_form').submit(function(event) {
        event.preventDefault();
        let formData = $(this).serialize()
        let recaptcha = true;

        @if($web_config['recaptcha']['status'] == '1')
            recaptcha = recaptcha_f();
        @endif

        if(recaptcha === true) {
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i], {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                        @if($web_config['recaptcha']['status'] != '1')
                        $('#re_captcha_customer_regi').click();
                        @endif
                    } else {
                        toastr.success(
                            '{{translate('Customer_Added_Successfully')}}!', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        if (data.redirect_url !== '') {
                            window.location.href = data.redirect_url;
                        } else {
                            $('#SignUpModal').modal('hide');
                            $('#SignInModal').modal('show');
                        }
                    }
                }
            });
        } else{
            toastr.error("{{translate('Please_check_the_recaptcha')}}");
        }
    });
</script>
