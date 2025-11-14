@extends('theme-views.layouts.app')

@section('title', translate('cancellation_policy').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')

    <main class="main-content d-flex flex-column gap-3 pb-3">
        <div class="page_title_overlay py-5">
            <img loading="lazy" class="bg--img" alt="{{ translate('cancellation_policy') }}"
                 src="{{ $pageTitleBanner ? getValidImage(path: 'storage/app/public/banner/'.(json_decode($pageTitleBanner['value'])->image), type: 'banner', source: theme_asset('assets/img/page-title-bg.png')) : theme_asset('assets/img/page-title-bg.png') }}">

            <div class="container">
                <h1 class="text-center text-capitalize">{{translate('cancellation_policy')}}</h1>
            </div>
        </div>

        <div class="container">
            <div class="card my-4">
                <div class="card-body p-lg-4 text-dark page-paragraph">
                    {!! $cancellationPolicy['content']  !!}
                </div>
            </div>
        </div>
    </main>

@endsection
