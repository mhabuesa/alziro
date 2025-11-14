<section class="recommended-product-section section-gap">
    <div class="container">
        <div class="section-title mb-4 pb-lg-1 text-capitalize">
            <div class="d-flex flex-wrap justify-content-between row-gap-2 column-gap-4 align-items-center single_section_dual_tabs">
                <h2 class="title mb-0">{{ translate('recommended_for_you') }}</h2>
                <ul class="nav nav-tabs nav--tabs single_section_dual_btn order-1 ms-auto ">
                    <li data-targetbtn="0">
                        <a href="#latest" class="active" data-bs-toggle="tab">{{ translate('latest_product') }}</a>
                    </li>
                    <li data-targetbtn="1">
                        <a href="#most-searching" data-bs-toggle="tab">{{ translate('most_searching') }}</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center column-gap-4 justify-content-end ms-auto ms-md-0 order-0 order-sm-2">
                    <div class="d-flex align-items-center column-gap-2 column-gap-sm-4">
                        <div class="owl-prev recommended-prev">
                            <i class="bi bi-chevron-left"></i>
                        </div>
                        <div class="owl-next recommended-next">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>
                    <div class="single_section_dual_target">
                        <a href="{{route('products',['data_from'=>'latest','page'=>1])}}" class="see-all">{{ translate('see_all') }}</a>
                        <a href="{{route('products',['data_from'=>'best-selling','page'=>1])}}" class="see-all d-none">{{ translate('see_all') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="latest">
                    <div class="recommended-slider-wrapper">
                        <div class="recommended-slider owl-theme owl-carousel">
                            @foreach($latest_products as $product)
                                @if($product)
                                    @include('theme-views.partials._product-medium-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="most-searching">
                    <div class="recommended-slider-wrapper">
                        <div class="recommended-slider owl-theme owl-carousel">
                            @foreach($most_searching_product as $product)
                                @if($product)
                                    @include('theme-views.partials._product-medium-card',['product'=>$product])
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

