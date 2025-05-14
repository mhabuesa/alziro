@extends('theme-views.layouts.app')

@section('title', 'Signin | ' . $web_config['name']->value . ' ' . translate('ecommerce'))

@section('content')
    <section class="seller-registration-section section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block">
                    <div
                        class="seller-registration-thumb h-100 d-flex flex-column align-items-center justify-content-between align-items-start">
                        <div class="section-title w-100 text-center">
                            <h2 class="title text-capitalize">Check Your Phone</h2>
                        </div>
                        <div class="my-auto">
                            <img loading="lazy" width="200" src="https://cdn-icons-png.flaticon.com/512/3088/3088872.png"
                                class="mw-100 mb-auto d-none d-md-block" alt="img/icons">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ps-xl-5">
                        <div class="seller-registration-thumb">
                            <div class=" text-center">
                                <div class="text-capitalize mt-3 fs-5 text-success">
                                    <h4>প্রিয় গ্রাহক!</h4>
                                    <p class="mt-3 fw-bold">আপনার মোবাইলে একটি অন-টাইম পাসওয়ার্ড প্ররণ করা হয়েছে।</p>
                                    <p class="fw-bold">অনুগ্রহ করে পাসওয়ার্ডটি দিয়ে আপনার একাউন্টে লগইন করুন,</p>
                                    <p class="fw-bold">এবং পরবর্তিতে পাসওয়ার্ড পরিবর্তন করে নিন।</p>
                                </div>
                                <a href="{{ route('customer.login') }}" class="btn btn-block btn-base text-capitalize mt-3 w-25">Sign In</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
