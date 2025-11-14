@if ($main_banner->count() > 0)
<section class="banner-section custom-height">
    <div class="banner-slider owl-theme owl-carousel custom-single-slider">
        @foreach($main_banner as $banner)
        <div class="banner-slide" style="--base: {{ $banner['background_color'] }};">

            <img class="banner-slide-img" alt="{{ translate('banner') }}" loading="lazy"
                 src="{{ getValidImage(path: 'storage/app/public/banner/'.$banner['photo'], type:'product') }}">

            @if($banner['title'] && $banner['sub_title'])
                <div class="content">
                    <h1 class="title mb-3">{{ $banner['title'] }} <br><span class="subtxt">{{ $banner['sub_title'] }}</span> </h1>
                    @if($banner['button_text'])
                    <div class="info">
                        <a href="{{ $banner['url'] ?? "javascript:"}}" class="btn btn-base">{{ $banner['button_text'] }}</a>
                    </div>
                    @endif
                </div>
            @endif

            <svg width="16" height="44" viewBox="0 0 16 44" fill="none" xmlns="http://www.w3.org/2000/svg" class="shapes d-sm-none">
                <g filter="url(#filter0_b_3844_38351)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.987292 43.5471C2.37783 38.4513 6.40927 34.0997 10.2104 29.9969C10.7306 29.4354 11.2464 28.8785 11.7506 28.3251C12.3698 27.6454 12.9261 26.9375 13.4285 26.2154C15.7758 22.8419 15.7065 18.2693 13.2818 14.9509C12.1188 13.3593 10.7689 11.9386 9.18884 10.7511C5.58277 8.04099 1.99367 4.63569 0.853516 0.455078L0.987292 43.5471Z" fill="var(--base)"/>
                </g>
                <defs>
                <filter id="filter0_b_3844_38351" x="-46.9791" y="-47.3775" width="109.958" height="138.757" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feGaussianBlur in="BackgroundImageFix" stdDeviation="23.9163"/>
                <feComposite in2="SourceAlpha" operator="in" result="effect1_backgroundBlur_3844_38351"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_backgroundBlur_3844_38351" result="shape"/>
                </filter>
                </defs>
            </svg>
            @if ($main_banner->count() > 1)
            <img src="{{theme_asset('assets/img/arrow-icon.png')}}" class="banner-arrow d-sm-none" alt="{{ translate('banner') }}" loading="lazy">
            @endif
        </div>
        @endforeach
    </div>

</section>
@else
    <section class="promo-page-header">
        <div class="product_blank_banner"></div>
    </section>
@endif

