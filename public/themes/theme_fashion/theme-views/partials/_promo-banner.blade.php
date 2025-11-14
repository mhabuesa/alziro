<section class="promo-section d-none d-sm-block ">
    <div class="container">
        <div class="promo-wrapper">
            @if ($promo_banner_left)
                <a href="{{ $promo_banner_left['url'] }}" target="_blank" class="img1 overflow-hidden promo-1">
                    <img loading="lazy" src="{{ getValidImage(path: 'storage/app/public/banner/'.$promo_banner_left['photo'], type:'banner') }}"
                    alt="{{ translate('promo') }}" class="w-100">
                </a>
            @else
                <a href="javascript:void(0)" class="img2 overflow-hidden opacity-0">
                    <img loading="lazy" src="" alt="{{ translate('promo') }}">
                </a>
            @endif

            @if($promo_banner_middle_top || $promo_banner_middle_bottom)
                <div class="promo-2">
                    @if ($promo_banner_middle_top)
                        <a href="{{ $promo_banner_middle_top['url'] }}" target="_blank" class="img2 overflow-hidden">
                            <img loading="lazy" alt="{{ translate('promo') }}"
                                 src="{{ getValidImage(path: 'storage/app/public/banner/'.$promo_banner_middle_top['photo'], type:'banner') }}">
                        </a>
                    @else
                        <a href="javascript:void(0)" class="img2 overflow-hidden opacity-0">
                            <img loading="lazy" src="" alt="{{ translate('promo') }}">
                        </a>
                    @endif

                    @if ($promo_banner_middle_bottom)
                        <a href="{{ $promo_banner_middle_bottom['url'] }}" target="_blank" class="img3 overflow-hidden">
                            <img loading="lazy" alt="{{ translate('promo') }}"
                                 src="{{ getValidImage(path: 'storage/app/public/banner/'.$promo_banner_middle_bottom['photo'], type:'banner') }}">
                        </a>
                        @else
                        <a href="javascript:void(0)" class="img3 overflow-hidden opacity-0">
                            <img loading="lazy" src="" alt="{{ translate('promo') }}">
                        </a>
                    @endif
                </div>
            @endif

            @if ($promo_banner_right)
                <a href="{{ $promo_banner_right['url'] }}" target="_blank" class="img1 overflow-hidden promo-3 {{ $promo_banner_left || $promo_banner_middle_top || $promo_banner_middle_bottom != null ? '' :'w-100'}}">
                    <img loading="lazy" alt="{{ translate('promo') }}"
                         src="{{ getValidImage(path: 'storage/app/public/banner/'.$promo_banner_right['photo'], type:'banner') }}">
                </a>
            @endif

        </div>
    </div>
</section>
