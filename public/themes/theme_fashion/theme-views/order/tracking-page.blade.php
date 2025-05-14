@extends('theme-views.layouts.app')

@section('title', translate('track_order').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<section class="breadcrumb-section pt-20px">
    <div class="container">
        <div class="section-title mb-4">
            <div
                class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{route('home')}}">{{translate('home')}}</a>
                    </li>
                    <li>
                        <a href="{{route('track-order.index')}}">{{translate('track_order')}}</a>
                    </li>
                </ul>
                @if(auth('customer')->check())
                    <div class="ms-auto ms-md-0">
                        <a href="{{route('account-oder')}}" class="text-base text-capitalize">{{translate('check_all_order')}}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>


<section class="search-track-section pt-20px">
    <div class="container">
        <h3 class="mb-3 mb-lg-4">{{translate('track_order')}}</h3>
        @if(session()->has('Error'))
            <div class="media-body alert">
                <div class="badge font-regular badge-soft-danger tract-page-error-notification">{{ session()->get('Error') }}
                </div>
                <span class="close-btn" data-bs-dismiss="alert">x</span>
            </div>
        @endif
        <form action="{{route('track-order.result')}}" type="submit" method="post" >
            @csrf
            <div class="track-order-wrapper">
                <div class="track-order-input col--5">
                    <input type="text" class="form-control" name="order_id" value="{{ old('order_id') }}" placeholder="{{translate('order_ID')}}">
                </div>
                <div class="track-order-input col--5">
                    <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" placeholder="{{translate('phone')}}">
                </div>
                <div class="track-order-input col--2">
                    <button type="submit" class="form-control btn btn-base">{{translate('track_order')}}</button>
                </div>
            </div>
        </form>
        <div class="text-center">
            <div class="container">
                <div class="search-track-initials text-text-2">
                    <img loading="lazy" src="{{theme_asset('assets/img/track/truck.png')}}" alt="{{ translate('order') }}">
                    <p>{{translate('enter_your_order_ID_& _phone_to_get_delivery_updates')}}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
