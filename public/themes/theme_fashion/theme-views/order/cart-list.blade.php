@extends('theme-views.layouts.app')

@section('title', translate('cart_list').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="breadcrumb-section pt-20px">
        <div class="container">
            <div class="section-title mb-3">
                <div
                    class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{ route('home') }}">{{translate('home')}}</a>
                        </li>
                        <li>
                            <a href="{{ url()->current() }}" class="text-base custom-text-link">
                                {{ translate('cart') }}
                            </a>
                        </li>
                    </ul>
                    <div class="ms-auto ms-md-0">
                        @if(auth('customer')->check())
                            <a href="{{ route('wishlists') }}" class="text-base custom-text-link">
                                {{ translate('check_All_Wishlist') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content d-flex flex-column gap-3 py-3 mb-3" id="cart-summary">
        @include(VIEW_FILE_NAMES['products_cart_details_partials'])
    </main>

    @if ($web_config['business_mode'] == 'multi')
        @include('theme-views.partials._other-stores')
    @endif

    @include('theme-views.partials._how-to-section')

@endsection
