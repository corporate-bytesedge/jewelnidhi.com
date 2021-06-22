$(".insta-images").slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    margin:0,
    arrows:false,
    variableWidth:true,
    responsive: [
        {
            breakpoint: 500,
            settings: {
                slidesToShow: 1
            }
        }
    ]
})


// testimonial cariusel active js
$('.testimonial-content-carousel').slick({
     arrows: false,
    slidesToShow: 1,
    slidesToScroll: 1,
      fade: true, 
    asNavFor: '.testimonial-thumb-carousel'
});


// product details slider nav active
$('.testimonial-thumb-carousel').slick({
    slidesToShow: 3,
    asNavFor: '.testimonial-content-carousel',
    centerMode: true,
    arrows: false,
    centerPadding: 0,
    focusOnSelect: true
});

// Background Image JS start
var bgSelector = $(".bg-img");
bgSelector.each(function (index, elem) {
    var element = $(elem),
        bgSource = element.data('bg');
    element.css('background-image', 'url(' + bgSource + ')');
});

// hero slider active js
$('.hero-slider-active').slick({
    fade: true,
    speed: 1000,
    dots: true,
    autoplay: true,
    arrows:false,
    responsive: [{
        breakpoint: 992,
        settings: {
            arrows: false,
            dots: true
        }
    }]
});

	

//nice select active start
$('select').niceSelect();

// tooltip active js
$('[data-toggle="tooltip"]').tooltip();

// prodct details slider active
$('.product-large-slider').slick({
    fade: true,
    arrows: false,
    speed: 1000,
    asNavFor: '.pro-nav'
});


// product details slider nav active
$('.pro-nav').slick({
    slidesToShow: 4,
    asNavFor: '.product-large-slider',
    centerMode: true,
    speed: 1000,
    centerPadding: 0,
    focusOnSelect: true,
    arrows:false,
    responsive: [{
        breakpoint: 576,
        settings: {
            slidesToShow: 3,
        }
    }]
});



	// product carousel active js
	$('.product-carousel-4').slick({
		speed: 1000,
		autoplay: true,
		slidesToShow: 4,
		adaptiveHeight: true,
		prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
        
		responsive: [{
			breakpoint: 992,
			settings: {
				slidesToShow: 3
			}
		},
		{
			breakpoint: 768,
			settings: {
				slidesToShow: 2,
				arrows: false
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 2,
				arrows: false
			}
		}]
    });
    
    // Image zoom effect
	// $('.img-zoom').zoom();

        /*  Single Product Image Slider
 /*----------------------------------------*/
    $('.sp-img_slider').slick({
       
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows:false,
        centerMode: true,
        centerPadding: 0,
        responsive: [{
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }
        ]
    });
    
    
        
    /*---------------------------- */
    $('.zoompro').elevateZoom({
        gallery: 'gallery',
        galleryActiveClass: 'active'
    });

   

