"use strict";
const banner = $(".banner-slider")
    .owlCarousel({
        margin: 0,
        responsiveClass: true,
        nav: false,
        dots: false,
        loop: false,
        autoplay: false,
        autoplayTimeout: 2500,
        autoplayHoverPause: true,
        smartSpeed: 600,
        mouseDrag: false,
        touchDrag: false,
        responsive: {
            0: {items: 1,},
            480: {items: 1,},
            768: {items: 1,},
            992: {items: 1,},
            1200: {items: 1,},
        },
        onInitialized: function (property) {
            var current = property.item.index;
            $(".owl-item").removeClass("selected-item");
            var selected = $(property.target).find(".owl-item").eq(current);
            selected.addClass("selected-item custom-single-slider-fluid");
        },
    })
    .on("changed.owl.carousel", function (property) {
        var current = property.item.index;
        $(".owl-item").removeClass("selected-item");
        var selected = $(property.target)
            .find(".owl-item")
            .eq(current)
            .addClass("selected-item");
    });
