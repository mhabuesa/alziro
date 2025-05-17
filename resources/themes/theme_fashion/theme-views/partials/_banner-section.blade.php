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
            background-size: 100% 100% !important;
        }

        .banner_part {
            position: relative;
        }

        .arrow-lt {
            font-size: 25px;
            width: 40px;
            height: 40px;
            border: 1px solid #ffffffc6;
            z-index: 9;
            border-radius: 50%;
            color: #ffffff;
            position: absolute;
            left: 5%;
            top: 40%;
            transition: all linear 0.4s;
            line-height: 40px;
            text-align: center;
            border: 1px solid #000000 !important;
        }

        .arrow-lt:hover {
            background: #ffffffab;
        }

        .arrow-rt {
            font-size: 25px;
            width: 40px;
            height: 40px;
            border: 1px solid #ffffffc6;
            z-index: 9;
            border-radius: 50%;
            color: #ffffff;
            position: absolute;
            right: 5%;
            top: 40%;
            transition: all linear 0.4s;
            line-height: 40px;
            text-align: center;
            border: 1px solid #000000 !important;
        }

        .banner_part i {
            color: #000000;
            font-size: 20px;
        }

        .arrow-rt:hover {
            background: #ffffffab;
        }

        .banner_part .slick-dots {
            position: absolute;
            left: 50%;
            bottom: 50px;
            z-index: 9;
            transform: translateX(-50%);
            text-align: center;
            width: 100%;
        }

        .banner_part .slick-dots li {
            height: 8px;
            width: 25px;
            background: rgb(255, 255, 255);
            display: inline-block;
            margin: 10px;
            border: 1px solid #000000 !important;
        }

        .banner_part .slick-dots li button {
            opacity: 0;
        }

        .banner_part .slick-dots li.slick-active {
            background: #000000;
        }

        .custom_width {
            width: 70% !important;
            margin: 0 auto !important;
        }



        @media (max-width: 767px) {
            .hide-on-mobile {
                display: none !important;
            }

            .custom_width {
                width: 100% !important;
            }

            .banner_items {
                height: 175px !important;
            }

            .banner_part .slick-dots {
                bottom: 10px !important;
            }

            .arrow-lt {
                font-size: 14px;
                width: 30px;
                height: 30px;
                line-height: 30px;
            }

            .arrow-rt {
                font-size: 14px;
                width: 30px;
                height: 30px;
                line-height: 30px;
            }
        }
    </style>
@endpush
@if ($main_banner->count() > 0)
    <section class="custom_width">

        <div class="container-fluid">
            <div class="row">

                {{-- Sidebar Category --}}
                {{-- <div class=" col-md-2 border-end category-sidebar hide-on-mobile hidden-xs">
                    <h5 class="category_header">Categories</h5>
                    <ul class="category">

                        @foreach ($categories as $category)
                            <li><a
                                    href="{{ route('products', ['id' => $category['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $category->name }}</a>
                                <i class="bi bi-chevron-right"></i>
                            </li>
                        @endforeach

                    </ul>
                </div> --}}

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
                            </div>
                        </div>
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
                adaptiveHeight: true,
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
