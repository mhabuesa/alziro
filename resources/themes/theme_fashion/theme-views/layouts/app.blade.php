<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->get('direction') }}">

<head>
    <meta charset="UTF-8">
    <meta name="base-url" content="{{ url('/') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') </title>

    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('storage/app/public/company') }}/{{ $web_config['fav_icon']->value }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('storage/app/public/company') }}/{{ $web_config['fav_icon']->value }}">

    <link rel="stylesheet" type="text/css"
        href="{{ theme_asset('assets/css/' . (session()->get('direction') == 'rtl' ? 'bootstrap-rtl.min.css' : 'bootstrap.css')) }}" />

    <link rel="stylesheet" media="screen"
        href="{{ theme_asset('assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/roboto-font.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/bootstrap-icons.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/owl.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/nouislider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ theme_asset('assets/plugins/magnific-popup-1.1.0/magnific-popup.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/toastr.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/main.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ theme_asset('assets/css/custom.css') }}" />

    <link rel="shortcut icon" href="{{ asset('storage/app/public/company') }}/{{ $web_config['fav_icon']->value }}"
        type="image/x-icon" />

    @stack('css_or_js')



    <style>
        :root {
            --base: {{ $web_config['primary_color'] }};
            --base-rgb: {{ \App\Utils\hex_to_rgb($web_config['primary_color']) }};
            --base-3: {{ $web_config['primary_color'] }};
            --base-4: {{ $web_config['primary_color_light'] }};
            --base-2: {{ $web_config['secondary_color'] }};
            --bs-2-rgb: {{ \App\Utils\hex_to_rgb($web_config['secondary_color']) }};

            --bs-btn-disabled-bg: {{ $web_config['primary_color'] }};
            --bs-btn-disabled-border-color: {{ $web_config['primary_color'] }};
            --bs-btn-disabled-color: #fff;
        }

        .secondary-color,
        .btn-base.secondary-color {
            --base: {{ $web_config['secondary_color'] }};
            --base-rgb: {{ \App\Utils\hex_to_rgb($web_config['secondary_color']) }};
            --border: {{ $web_config['secondary_color'] }};
            --bs-2-rgb: {{ \App\Utils\hex_to_rgb($web_config['secondary_color']) }};

            --bs-btn-disabled-color: #00000085;
            --bs-btn-disabled-bg: {{ $web_config['secondary_color'] }};
            --bs-btn-disabled-border-color: {{ $web_config['secondary_color'] }}75;
        }

        .__btn-outline-warning.secondary-color {
            --base: {{ $web_config['primary_color'] }};
            --base-rgb: {{ \App\Utils\hex_to_rgb($web_config['primary_color']) }};
            --base-3: {{ $web_config['primary_color'] }};
            --base-4: {{ $web_config['primary_color_light'] }};
            --base-2: {{ $web_config['secondary_color'] }};
            --bs-2-rgb: {{ \App\Utils\hex_to_rgb($web_config['secondary_color']) }};
            --warning: {{ $web_config['secondary_color'] }};
            --warning-rgb: {{ \App\Utils\hex_to_rgb($web_config['secondary_color']) }};

            --bs-btn-disabled-color: #00000085;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: {{ $web_config['secondary_color'] }}75;
        }

        @if (isset($web_config['announcement']) && $web_config['announcement']['status'] == 1)
            .offer-bar {
                background-color: {{ $web_config['announcement']['color'] }};
                color: {{ $web_config['announcement']['text_color'] }};
            }
        @endif

        .messenger-chat {
            position: fixed;
            inset-inline-end: 2%;
            inset-block-end: 50px;
            z-index: 9;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .listing {
            position: absolute;
            bottom: 60px;
            right: -6px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        
        @media (max-width: 480px) {
             .messenger-chat {
                inset-block-end: 90px;
            }
        }
    </style>

    <script src="{{ theme_asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    @php($googleTagManagerId = getWebConfig(name: 'google_tag_manager_id'))
    @if ($googleTagManagerId)
        <script>
            "use strict";
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $googleTagManagerId }}');
        </script>
    @endif

    @php($pixel_analytics_user_code = getWebConfig(name: 'pixel_analytics'))
    @if ($pixel_analytics_user_code)
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $pixel_analytics_user_code }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img loading="lazy" height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $pixel_analytics_user_code }}&ev=PageView&noscript=1" />
        </noscript>
    @endif
</head>

<body>

    <script>
        "use strict";

        function setThemeMode() {
            if (localStorage.getItem('theme') === null) {
                document.body.setAttribute('theme', 'light');
            } else {
                document.body.setAttribute('theme', localStorage.getItem('theme'));
            }
        }
        setThemeMode();
    </script>

    @if ($googleTagManagerId)
        <noscript>
            <iframe src="{{ 'https://www.googletagmanager.com/ns.html?id=' . $googleTagManagerId }}" height="0"
                width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif

    <div class="overlay"></div>

    <div class="preloader d--none" id="loading">
        <img loading="lazy" width="200" alt="{{ translate('loader') }}"
            src="{{ getValidImage(path: 'storage/app/public/company/' . getWebConfig(name: 'loader_gif'), type: 'source', source: theme_asset('assets/img/loader.gif')) }}">
    </div>

    @include('theme-views.layouts.partials._header')
    @include('theme-views.layouts.partials.modal._quick-view')
    <div id="login-and-register-modal-section"></div>

    <div class="d-none d-md-block">
        @if ($web_config['guest_checkout_status'] || auth('customer')->check())
            <a href="{{ route('shop-cart') }}" class="floating-cart" id="floating_cart_items">
                @include('theme-views.layouts.partials._cart-floating')
            </a>
        @else
            <a href="javascript:" class="floating-cart floating-cart-custom-css customer_login_register_modal"
                id="floating_cart_items" data-bs-toggle="modal">
                @include('theme-views.layouts.partials._cart-floating')
            </a>
        @endif
    </div>


    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true) : null)
    @if ($cookie && $cookie['status'] == 1)
        <section class="cookie-section" id="cookie-section"></section>
    @endif


    @include('theme-views.layouts.partials.modal._initial')
    @include('theme-views.layouts.partials.modal._alert')

    @yield('content')

    <div class="app-bar px-sm-2 d-xl-none" id="mobile_app_bar">
        @include('theme-views.layouts.partials._app-bar')
    </div>

    @include('theme-views.layouts.partials._footer')


    <span id="get-login-modal-data" data-route="{{ route('customer.auth.get-login-modal-data') }}"></span>
    <span id="get-current-route-name" data-route="{{ Route::currentRouteName() }}"></span>
    <span id="update_nav_cart_url" data-url="{{ route('cart.nav-cart') }}"></span>
    <span id="update_floating_nav_cart_url" data-url="{{ route('cart.floating-nav-cart-items') }}"></span>
    <span id="update_quantity_url" data-url="{{ route('cart.updateQuantity.guest') }}"></span>
    <span id="update_nav_cart_url" data-url="{{ route('cart.nav-cart') }}"></span>
    <span id="remove_from_cart_url" data-url="{{ route('cart.remove') }}"></span>
    <span id="update_quantity_basic_url" data-url="{{ route('cart.updateQuantity') }}"></span>
    <span id="order_again_url" data-url="{{ route('cart.order-again') }}"></span>
    <span id="store_wishlist_url" data-url="{{ route('store-wishlist') }}"></span>
    <span id="quick_view_url" data-url="{{ route('quick-view') }}"></span>
    <span id="delete_wishlist_url" data-url="{{ route('delete-wishlist') }}"></span>
    <span id="checkout_details_url" data-url="{{ route('checkout-details') }}"></span>
    <span id="order_note_url" data-url="{{ route('order_note') }}"></span>
    <span id="store_compare_list_url" data-url="{{ route('product-compare.index') }}"></span>
    <span id="digital_product_download_otp_reset"
        data-route="{{ route('digital-product-download-otp-reset') }}"></span>
    <span id="set_shipping_url" data-url="{{ url('/') }}/customer/set-shipping-method"></span>
    <span id="authentication-status" data-auth="{{ auth('customer')->check() ? 'true' : 'false' }}"></span>
    <span class="cannot_use_zero" data-text="{{ translate('cannot_Use_0_only') }}"></span>
    <span class="out_of_stock" data-text="{{ translate('Out_Of_Stock') }}"></span>
    <span class="minimum_order_quantity_msg"
        data-text="{{ translate('minimum_order_quantity_cannot_be_less_than') }}"></span>
    <span class="item_has_been_removed_from_cart"
        data-text="{{ translate('item_has_been_removed_from_cart') }}"></span>
    <span class="please_fill_out_this_field" data-text="{{ translate('please_fill_out_this_field') }}"></span>
    <span class="text-wishList" data-text="{{ translate('wishlist') }}"></span>
    <span class="text-otp-related" data-otpsendagain="{{ translate('OTP_has_been_sent_again') }}"
        data-otpnewcode="{{ translate('please_wait_for_new_code') }}"></span>
    <span class="text-custom-storage" data-textno="{{ translate('no') }}" data-textyes="{{ translate('yes') }}"
        data-textnow="{{ translate('now') }}" data-textsuccessfullycopied="{{ translate('successfully_copied') }}"
        data-text-no-discount="{{ translate('no_discount') }}"
        data-stock-available="{{ translate('stock_available') }}"
        data-stock-not-available="{{ translate('stock_not_available') }}"
        data-update-this-address="{{ translate('update_this_Address') }}"
        data-password-characters-limit="{{ translate('your_password_must_be_at_least_8_characters') }}"
        data-password-not-match="{{ translate('password_does_not_Match') }}"
        data-textpleaseselectpaymentmethods="{{ translate('please_select_a_payment_Methods') }}"
        data-reviewmessage="{{ translate('you_can_review_after_the_product_is_delivered') }}"
        data-refundmessage="{{ translate('you_can_refund_request_after_the_product_is_delivered') }}"
        data-textshoptemporaryclose="{{ translate('This_shop_is_temporary_closed_or_on_vacation') . ' ' . translate('You_cannot_add_product_to_cart_from_this_shop_for_now') }}"></span>


    @php($whatsapp = getWebConfig(name: 'whatsapp'))
    <div class="messenger-chat mx-2">
        <div class="toggle-chat">
            <a href="javascript:void(0)" id="whatsappToggle">
                <img loading="lazy" src="https://cdn-icons-png.flaticon.com/512/9132/9132460.png" width="35"
                    class="chat-image-shadow" alt="Contact us">
            </a>
        </div>

        <ul class="listing" id="chatOptions" style="display: none;">
            <li class="Call mb-3">
                <a href="tel:{{ $web_config['phone']->value }}" class="text-dark">
                    <img loading="lazy" src="https://cdn-icons-png.flaticon.com/512/16076/16076069.png" width="35"
                        class="chat-image-shadow" alt="Call us">
                </a>
            </li>
            <li class="facebook mb-3">
                <a target="_blank" href="https://m.me/254631617724413" class="fb-xfbml-parse-ignore">
                    <img loading="lazy" src="https://cdn-icons-png.flaticon.com/512/5968/5968771.png" width="35"
                        class="chat-image-shadow" alt="Message us">
                </a>
            </li>

            <li class="whatsapp">
                <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                    <img loading="lazy" src="{{ theme_asset('assets/img/whatsapp.svg') }}" width="35"
                        class="chat-image-shadow" alt="{{ translate('Chat_with_us_on_WhatsApp') }}">
                </a>
            </li>
        </ul>
    </div>






    <script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/owl.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/countdown.js') }}"></script>
    <script src="{{ theme_asset('assets/js/nouislider.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/easyzoom.js') }}"></script>
    <script src="{{ theme_asset('assets/plugins/magnific-popup-1.1.0/jquery.magnific-popup.js') }}"></script>
    <script src="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.js') }}"></script>

    <script src="{{ theme_asset('assets/js/main.js') }}"></script>
    <script src="{{ theme_asset('assets/js/toastr.js') }}"></script>
    <script src="{{ theme_asset('assets/js/custom.js') }}"></script>


    {!! Toastr::message() !!}



    <script>
        "use strict";

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        @endif

        @if (Request::is('/') && \Illuminate\Support\Facades\Cookie::has('popup_banner') == false)
            $(window).on('load', function() {
                $('#initialModal').modal('show');
            });
            @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
        @endif

        @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true) : null)
        let cookie_content = `<div class="container">
                                <div class="d-flex flex-wrap align-items-center justify-content-between column-gap-4 row-gap-3">
                                    <div class="text-wrapper">
                                        <h5 class="title">{{ translate('Your_Privacy_Matter') }}</h5>
                                        <div>{{ $cookie ? $cookie['cookie_text'] : '' }}</div>
                                    </div>
                                    <div class="btn-wrapper">
                                        <button type="button" class="btn absolute-white btn-link" id="cookie-reject">{{ translate('no') }}, {{ translate('thanks') }}</button>
                                        <button type="button" class="btn btn-success cookie-accept" id="cookie-accept">{{ translate('yes') }}, {{ translate('accept_All_Cookies') }}</button>
                                    </div>
                                </div>
                            </div>`;

        @if (!auth('customer')->check())
            $(document).ready(function() {
                const currentUrl = new URL(window.location.href);
                const referralCodeParameter = new URLSearchParams(currentUrl.search).get("referral_code");

                if (referralCodeParameter) {
                    $('#SignUpModal').modal('show');
                    let referralCodeElement = $('#referral_code');
                    if (referralCodeElement.length) {
                        referralCodeElement.val(referralCodeParameter);
                    }
                }
            });
        @endif
    </script>

    <script>
        document.getElementById("whatsappToggle").addEventListener("click", function() {
            const chatOptions = document.getElementById("chatOptions");
            if (chatOptions.style.display === "none" || chatOptions.style.display === "") {
                chatOptions.style.display = "block";
            } else {
                chatOptions.style.display = "none";
            }
        });
    </script>


    @stack('script')
</body>

</html>
