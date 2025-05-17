@push('css_or_js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css"
        integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ============ BANNNER SLIDE ============== */

        .banner_items {
            background-position: center !important;
            background-size: cover !important;
            width: 100%;
            height: 385px;
            background-repeat: no-repeat !important;
            position: relative;
        }

        .banner_part {
            position: relative;
        }

        .arrow-lt {
            font-size: 25px;
            width: 50px;
            height: 50px;
            border: 1px solid #ffffffc6;
            z-index: 9;
            border-radius: 50%;
            color: #ffffff;
            position: absolute;
            left: 5%;
            top: 40%;
            transition: all linear 0.4s;
            line-height: 50px;
            text-align: center;
        }

        .arrow-lt:hover {
            background: #ffffffab;
        }

        .arrow-rt {
            font-size: 25px;
            width: 50px;
            height: 50px;
            border: 1px solid #ffffffc6;
            z-index: 9;
            border-radius: 50%;
            color: #ffffff;
            position: absolute;
            right: 5%;
            top: 40%;
            transition: all linear 0.4s;
            line-height: 50px;
            text-align: center;
        }

        .arrow-rt:hover {
            background: #ffffffab;
        }

        .banner_part .slick-dots {
            position: absolute;
            left: 50%;
            bottom: 50px;
            z-index: 9999;
            transform: translateX(-50%);
        }

        .banner_part .slick-dots li {
            height: 15px;
            width: 15px;
            border-radius: 50%;
            background: rgb(255, 255, 255);
            margin: 5px;
            display: inline-block;
            margin: 10px;
        }

        .banner_part .slick-dots li button {
            opacity: 0;
        }

        .banner_part .slick-dots li.slick-active {
            background: #000000;
        }




        /*======= Product Slider ====== */
        .crd_style {
            padding: 80px 20px;
            background: #ddd;
        }

        .crd_style .single-card {
            background: #ffffff;
            margin: 0 10px;
        }

        .crd_style .single-card .card-img img {
            width: 100%;
            height: 100%;
        }

        .crd_style .single-card .card-cont {
            padding: 0 1.25rem 2rem 1.25rem;
        }

        .crd_style .single-card .card-cont>div>span:last-child {
            margin-left: 1rem;
        }

        .pro-arrow-lt {
            font-size: 25px;
            width: 50px;
            height: 50px;
            border: 1px solid #ffffffc6;
            z-index: 9999;
            border-radius: 50%;
            color: #ffffff;
            position: absolute;
            left: 0%;
            margin-top: 13%;
            transition: all linear 0.4s;
            line-height: 50px;
            text-align: center;
            background: rgb(197, 197, 197);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .pro-arrow-lt:hover {
            background: #ffffffab;
            cursor: pointer;
        }

        .pro-arrow-rt {
            font-size: 25px;
            width: 50px;
            height: 50px;
            border: 1px solid #ffffffc6;
            z-index: 9999;
            border-radius: 50%;
            color: #ffffff;
            position: absolute;
            right: 0%;
            margin-top: 13%;
            transition: all linear 0.4s;
            line-height: 50px;
            text-align: center;
            background: rgb(197, 197, 197);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .pro-arrow-rt:hover {
            background: #d0d0d0ab;
            cursor: pointer;
        }

        .category_slick_slider {
            position: relative;
            width: 70%;
            margin: 0 auto;
        }


        .product_slide .slick-dots {
            position: absolute;
            left: 50%;
            /* bottom: 50px; */
            z-index: 9999;
            transform: translateX(-50%);
        }

        .product_slide .slick-dots li {
            height: 15px;
            width: 15px;
            border-radius: 50%;
            background: rgb(255, 255, 255);
            margin: 5px;
            display: inline-block;
            margin: 10px;
        }

        .product_slide .slick-dots li button {
            opacity: 0;
        }

        .product_slide .slick-dots li.slick-active {
            background: #000000;
        }

        @media (max-width: 767px) {
            .category_slick_slider {
                position: relative;
                width: 100%;
                margin: 0 auto;
            }
        }

        /*======= Product Slider ====== */


        /*======= Category list ====== */
        .custom-height {
            width: 70% !important;
            margin: 0 auto !important;
        }


        .category_header {
            background-color: #2D2F93;
            padding: 10px 15px;
            color: white;
            border-radius: 0;
            /* Make square so no gap appears */
            margin: 0;
            /* Remove margin */
            position: sticky;
            top: 0;
            z-index: 10;
        }


        .category-sidebar {
            max-height: 385px;
            overflow-y: auto;
            position: relative;
            padding: 0 !important;
        }

        .category li {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            transition: all linear 0.3s;
        }

        .category li i {
            margin-right: 10px;
            font-size: 16px;
            font-weight: 500;
            color: #2D2F93;
        }

        .category li a {
            text-decoration: none;
            color: black;
            font-size: 16px;
            font-weight: 500;
            display: block;
            width: 100%;
            padding: 10px 15px;
            margin: 0;
            line-height: 20px;
            cursor: pointer;
        }


        .featured_header {
            background-color: #2D2F93;
            padding: 10px 15px;
            color: white;
            border-radius: 0;
            /* Make square so no gap appears */
            margin: 0;
            /* Remove margin */
            position: sticky;
            top: 0;
            z-index: 10;
        }


        .featured-sidebar {
            max-height: 385px;
            overflow-y: auto;
            position: relative;
            padding: 0 !important;
        }

        .featured_item {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 7px;
            overflow: hidden;
            padding: 5px;
            margin: 10px 10px;
            filter: drop-shadow(3px 4px 6px #eee);
            width: 100%;
        }

        .featured_item_img img {
            border-radius: 5px;
        }

        .featured_item:hover {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .featured_item_text {
            text-align: center;
            margin: 0 auto;
        }

        .featured_item_text h5,
        .featured_item_text h6 {
            font-size: 15px;
        }

        @media (max-width: 767px) {
            .hide-on-mobile {
                display: none !important;
            }

            .custom-height {
                width: 100% !important;
            }

            .banner_items {
                height: 263px !important;
            }

            .banner_part .slick-dots {
                bottom: 10px !important;
            }

            .arrow-lt {
                font-size: 14px;
                width: 40px;
                height: 40px;
                line-height: 40px;
            }

            .arrow-rt {
                font-size: 14px;
                width: 40px;
                height: 40px;
                line-height: 40px;
            }
        }
    </style>
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
                            <li><a
                                    href="{{ route('products', ['id' => $category['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $category->name }}</a>
                                <i class="bi bi-chevron-right"></i>
                            </li>
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
                                        <h5> {{ \App\Utils\Helpers::currency_converter($product->unit_price) }} </h5>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"
        integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            $('.benner_slide').slick({
                dots: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 500,
                margin: 50,
                prevArrow: '.arrow-lt',
                nextArrow: '.arrow-rt',
                slidesToShow: 1,
                slidesToScroll: 1,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

            $('.product_slide').slick({
                dots: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 500,
                margin: 50,
                prevArrow: '.pro-arrow-lt',
                nextArrow: '.pro-arrow-rt',
                slidesToShow: 5,
                slidesToScroll: 2,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });


        });
    </script>
@endpush
