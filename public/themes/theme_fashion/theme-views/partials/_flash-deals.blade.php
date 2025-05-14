<section class="flash-deals-slider section-gap pb-0">
    <div class="container">
        <div class="flash-deals-wrapper __bg-img mb-4 d-md-none" data-img="{{ theme_asset('assets/img/flash-deals/flash-deals.png') }}">
            <ul class="countdown" data-countdown="{{$web_config['flash_deals']?$web_config['flash_deals']['end_date']:''}}">
                <li>
                    <h6 class="days">00</h6>
                    <span class="text days_text">{{ translate('days') }}</span>
                </li>
                <li>
                    <h6 class="hours">00</h6>
                    <span class="text hours_text">{{ translate('hours') }}</span>
                </li>
                <li>
                    <h6 class="minutes">00</h6>
                    <span class="text minutes_text">{{ translate('minutes') }}</span>
                </li>
                <li>
                    <h6 class="seconds">00</h6>
                    <span class="text seconds_text">{{ translate('seconds') }}</span>
                </li>
            </ul>
        </div>

        <div class="section-title mb-3 mb-sm-4 pb-lg-1">
            <div class="d-flex flex-wrap justify-content-between row-gap-2 column-gap-4 align-items-center">

                <h2 class="title mb-0 me-auto text-capitalize">{{ translate('flash_deals') }}</h2>
                <div class="flash-deals-wrapper __bg-img d-none d-md-flex" data-img="{{ theme_asset('assets/img/flash-deals/flash-deals.png') }}">
                    <ul class="countdown" data-countdown="{{$web_config['flash_deals']?$web_config['flash_deals']['end_date']->format('m/d/Y').' 11:59:00 PM':''}}">
                        <li>
                            <h6 class="days">00</h6>
                            <span class="text days_text">{{ translate('days') }}</span>
                        </li>
                        <li>
                            <h6 class="hours">00</h6>
                            <span class="text hours_text">{{ translate('hours') }}</span>
                        </li>
                        <li>
                            <h6 class="minutes">00</h6>
                            <span class="text minutes_text">{{ translate('minutes') }}</span>
                        </li>
                        <li>
                            <h6 class="seconds">00</h6>
                            <span class="text seconds_text">{{ translate('seconds') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center column-gap-4 justify-content-end ms-auto ms-md-0">
                    @if($web_config['flash_deals']->products)
                        <div class="owl-prev flash-prev">
                            <i class="bi bi-chevron-left"></i>
                        </div>
                        <div class="owl-next flash-next">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    @endif
                    <a  href="{{route('flash-deals',[$web_config['flash_deals']?$web_config['flash_deals']['id']:0])}}" class="see-all text-capitalize">{{ translate('See_all') }}</a>
                </div>
            </div>
        </div>
        <div class="overflow-hidden">
            <div class="recommended-slider-wrapper">
                <div class="flash-deal-slider owl-theme owl-carousel">
                 @foreach($web_config['flash_deals']->products as $key=>$deal)
                    @if( $deal->product)
                        @include('theme-views.partials._product-medium-card',['product'=>$deal->product])
                     @endif
                @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
