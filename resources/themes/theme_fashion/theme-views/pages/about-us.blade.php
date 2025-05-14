@extends('theme-views.layouts.app')

@section('title', translate('about_us').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="About {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{config('app.url')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="about {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{config('app.url')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')

<main class="main-content d-flex flex-column gap-3 pb-3">
    <div class="page_title_overlay py-5">
        <img loading="lazy" class="bg--img" alt="{{ translate('about_us') }}"
             src="{{ $pageTitleBanner ? getValidImage(path: 'storage/app/public/banner/'.(json_decode($pageTitleBanner['value'])->image), type: 'banner', source: theme_asset('assets/img/page-title-bg.png')) : theme_asset('assets/img/page-title-bg.png') }}">

        <div class="container">
            <h1 class="text-center text-capitalize">{{translate('about_our_company')}}</h1>
        </div>
    </div>

    <div class="container">
        <div class="card my-4">
            <div class="card-body p-lg-5 text-dark page-paragraph">
                {!! $aboutUs !!}
            </div>
        </div>
    </div>
</main>

@endsection
