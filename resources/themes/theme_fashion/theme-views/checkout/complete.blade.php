@extends('theme-views.layouts.app')

@section('title', translate('order_complete').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<main class="main-content d-flex flex-column gap-3 py-3 mb-3">
    <div class="container">
        <div class="py-5">
            <div class="bg-contain bg-center bg-no-repeat success-bg py-5"
                data-bg-img="{{theme_asset('assets/img/bg/success-bg.png')}}">
                <div class="row justify-content-center mb-5">
                    <div class="col-xl-6 col-md-10">
                        <div class="text-center d-flex flex-column align-items-center gap-3">
                            <img loading="lazy" width="46" src="{{ theme_asset('assets/img/icons/check.png') }}" class="dark-support"
                                alt="{{translate('order_placed_successfully')}}">
                            <h3 class="text-capitalize">{{translate('your_order_placed_successfully')}}</h3>
                            <p class="text-muted">{{ translate('thank_you_for_your_order!') }}
                                {{ translate('your_order_is_being_processed_and_will_be_completed_within_3_to_6 hours').' '.translate('you_will_receive_an_email_confirmation_when_your_order_is_completed')}}
                            </p>

                            <a href="{{route('home')}}" class="btn-base w-50 justify-content-center form-control">
                                {{translate('Continue_Shopping')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
