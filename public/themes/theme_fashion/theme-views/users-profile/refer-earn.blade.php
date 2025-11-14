@extends('theme-views.layouts.app')

@section('title', translate('refer_&_Earn').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">

            @include('theme-views.partials._profile-aside')

            <div class="tab-content">
                <div class="tab-pane fade show active" >
                    <div class="personal-details mb-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center column-gap-4 row-gap-2 mb-4">
                            <h4 class="subtitle m-0 text-capitalize">{{ translate('refer_&_Earn') }}</h4>
                        </div>

                        <div class="refer_and_earn_section">
                            <div class="d-flex justify-content-center align-items-center py-2 mb-3">
                                <div class="banner-img">
                                    <img loading="lazy" class="img-fluid"
                                    src="{{ theme_asset('assets/img/refer-and-earn.png') }}" alt="{{ translate('refer_and_earn') }}" width="300">
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="primary-heading mb-2">{{ translate('invite_Your_Friends_&_Businesses') }}</h5>
                                <p class="secondary-heading">{{ translate('copy_your_code_and_share_your_friends') }}</p>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <h6 class="text-secondary-color">{{ translate('your_personal_code') }}</h6>
                                    <div class="refer_code_box">
                                        <div class="refer_code click_to_copy_function" data-copycode="{{ $customer_detail->referral_code }}">{{ $customer_detail->referral_code }}</div>
                                        <span class="refer_code_copy click_to_copy_function" data-copycode="{{ $customer_detail->referral_code }}">
                                            <img loading="lazy" class="w-100" src="{{ theme_asset('assets/img/icons/solar_copy-bold-duotone.png') }}" alt="{{ translate('copy') }}">
                                        </span>
                                    </div>

                                    <h4 class="share-icons-heading">{{ translate('oR_SHARE') }}</h4>
                                    <div class="d-flex justify-content-center align-items-center share-on-social">
                                        @php
                                            $text = "Greetings,".$web_config['name']->value." is the best e-commerce platform in the country.If you are new to this website dont forget to use " . $customer_detail->referral_code . " " ."as the referral code while sign up into 6valley.";
                                            $link = url('/');
                                        @endphp
                                        <a href="https://api.whatsapp.com/send?text={{$text}}.{{$link}}" target="_blank">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/whatsapp.png') }}" alt="{{ translate('whatsapp') }}">
                                        </a>
                                        <a href="mailto:recipient@example.com?subject=Referral%20Code%20Text&body={{$text}}%20Link:%20{{$link}}" target="_blank">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/gmail.png') }}" alt="{{ translate('gmail') }}">
                                        </a>
                                        <span class="click_to_copy_function" data-copycode="{{ route('home') }}?referral_code={{ $customer_detail->referral_code }}">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/share.png') }}" alt="{{ translate('share') }}">
                                        </span>
                                    </div>
                                </div>

                                <div class="information-section col-md-8">
                                    <h4 class="text-bold d-flex align-items-center"> <span class="custom-info-icon">i</span> {{ translate('how_you_it_works') }}?</h4>

                                    <ul>
                                        <li>
                                            <span class="item-custom-index">{{ translate('1') }}</span>
                                            <span class="item-custom-text">{{ translate('invite_your_friends_&_businesses') }}</span>
                                        </li>
                                        <li>
                                            <span class="item-custom-index">{{ translate('2') }}</span>
                                            <span class="item-custom-text">{{ translate('they_register').' '.$web_config['name']->value.' '.translate('with_special_offer') }}</span>
                                        </li>
                                        <li>
                                            <span class="item-custom-index">{{ translate('3') }}</span>
                                            <span class="item-custom-text">{{ translate('you_made_your_earning') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
