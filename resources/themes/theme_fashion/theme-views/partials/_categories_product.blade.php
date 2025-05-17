
@foreach ($categoryWithProducts as $category)
<section class="recommended-product-section section-gap">
    <div class="container">
        <div class="section-title pb-lg-1 text-capitalize">
            <div class="d-flex flex-wrap justify-content-between row-gap-2 column-gap-4 align-items-center single_section_dual_tabs">
                <h2 class="title mb-0">{{ $category->name }}</h2>
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
                        <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="see-all">
                            {{ translate('see_all') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="latest">
                    <div class="recommended-slider-wrapper">
                        <div class="recommended-slider owl-theme owl-carousel">
                            @foreach($category->product as $product)
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
@endforeach
