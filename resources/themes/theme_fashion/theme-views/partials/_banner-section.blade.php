@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset('frontend') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ theme_asset('frontend') }}/css/slick.css">
    <link rel="stylesheet" href="{{ theme_asset('frontend') }}/css/style.css">
@endpush
@if ($main_banner->count() > 0)
    <section class=" custom-height">

        <div class="container-fluid">
            <div class="row">

                {{-- Sidebar Category --}}
                <div class=" col-md-2 border-end category-sidebar hide-on-mobile hidden-xs">
                    <h5 class="category_header">Categories</h5>
                    <ul class="category">

                        @foreach ($categories as $category)
                            <li><a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category->name}}</a> <i class="bi bi-chevron-right"></i></li>
                        @endforeach

                    </ul>
                </div>

                {{-- Main Banner --}}
                <div class="col-md-8 p-0">
                    <div id="mainSlider" class="carousel slide" data-bs-ride="carousel">
                        <div class="banner_part">
                            <i class="bi bi-chevron-left arrow-lt"></i>
                            <i class="bi bi-chevron-right arrow-rt"></i>
                            <div class="benner_slide">
                                @foreach ($main_banner as $banner)
                                    <a href="#">
                                        <div class="banner_items"
                                            style="background: url({{ getValidImage(path: 'storage/app/public/banner/' . $banner['photo'], type: 'product') }});">
                                        </div>
                                    </a>
                                @endforeach
                                {{-- <a href="#">
                                    <div class="banner_items"
                                        style="background: url({{ asset('frontend') }}/images/banner2.jpg);"></div>
                                </a>
                                <a href="#">
                                    <div class="banner_items"
                                        style="background: url({{ asset('frontend') }}/images/banner3.jpg);"></div>
                                </a>
                                <a href="#">
                                    <div class="banner_items"
                                        style="background: url({{ asset('frontend') }}/images/banner4.jpg);"></div>
                                </a>
                                <a href="#">
                                    <div class="banner_items"
                                        style="background: url({{ asset('frontend') }}/images/banner6.jpg);"></div>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Today's Deal --}}
                <div class=" col-md-2 border-end featured-sidebar hide-on-mobile hidden-xs">
                    <h5 class="featured_header">Featured Products</h5>
                    <div class="featured">
                        @foreach ($featured_products as $product)
                            <a href="{{ route('product', $product->slug) }}">
                                <div class="featured_item">
                                <div class="featured_item_img">
                                    <img loading="lazy" width="50"
                                        src="{{ getValidImage(path: 'storage/app/public/product/' . $product->thumbnail, type: 'product') }}"
                                        alt="product->name" class="img-fluid">
                                </div>
                                <div class="featured_item_text">
                                    <h6>{{ $product->name }}</h6>
                                    <h5> {{\App\Utils\Helpers::currency_converter($product->unit_price)}} </h5>

                                </div>
                            </div>
                            </a>
                        @endforeach


                        {{-- <div class="featured_item d-flex align-items-center mb-3 ">
                            <div class="featured_item_img">
                                <a href="{{ route('product', $product->slug) }}">
                                    <img loading="lazy" width="50" src="{{ getValidImage(path: 'storage/app/public/product/'.$product->thumbnail, type:'product') }}"
                                        alt="{{ $product->name }}" class="img-fluid">
                                </a>
                            </div>
                            <div class="featured_item_text">
                                <a href="{{ route('product', $product->slug) }}">
                                    <h6>{{ $product->name }}</h6>
                                </a>
                                <h5>${{ $product->unit_price }}</h5>
                            </div>
                        </div> --}}
                        {{-- @foreach ($featured_products as $product)
                        @endforeach --}}

                    </div>
                </div>

            </div>
        </div>

    </section>
@else
    <section class="promo-page-header">
        <div class="product_blank_banner"></div>
    </section>
@endif

@push('script')
    <script src="{{ theme_asset('frontend') }}/js/jquery-3.7.1.min.js"></script>
    <script src="{{ theme_asset('frontend') }}/js/owl.carousel.min.js"></script>
    <script src="{{ theme_asset('frontend') }}/js/slick.min.js"></script>
    <script src="{{ theme_asset('frontend') }}/js/coustom.js"></script>
@endpush
