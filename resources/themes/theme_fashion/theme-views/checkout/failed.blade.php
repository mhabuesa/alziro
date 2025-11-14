@extends('theme-views.layouts.app')

@section('title', 'Order Failed'.' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<main class="main-content d-flex flex-column gap-3 py-3 mb-3">
    <div class="container">
        <div class="py-5">
            <div class="bg-contain bg-center bg-no-repeat success-bg py-5"
                data-bg-img="{{theme_asset('assets/img/bg/success-bg.png')}}">
                <div class="row justify-content-center mb-5">
                    <div class="col-xl-6 col-md-10">
                        <div class="text-center d-flex flex-column align-items-center gap-3">
                            <img loading="lazy" width="66" src="https://cdn-icons-png.flaticon.com/512/6659/6659895.png" class="dark-support"
                                alt="{{translate('order_failed')}}">
                            <h3 class="text-capitalize">{{$message}}</h3>
                            <p class="text-muted">
                                Please try again later! <br>
                                If you need any help, please contact us.
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
