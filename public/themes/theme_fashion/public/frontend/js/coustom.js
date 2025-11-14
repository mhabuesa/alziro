$(function(){
    $('.benner_slide').slick({
        dots: true,
        infinite: true,
        autoplay:true,
        autoplaySpeed: 2000,
        speed:500,
        margin:50,
        prevArrow:'.arrow-lt',
        nextArrow:'.arrow-rt',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
          {
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
        autoplay:true,
        autoplaySpeed: 2000,
        speed:500,
        margin:50,
        prevArrow:'.pro-arrow-lt',
        nextArrow:'.pro-arrow-rt',
        slidesToShow: 5,
        slidesToScroll: 2 ,
        responsive: [
          {
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