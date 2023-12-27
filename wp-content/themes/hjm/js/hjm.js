jQuery(document).ready(function($){

    if(jQuery('.owl-carousel').length){
      jQuery('.owl-carousel').owlCarousel({
        stagePadding: 400,
        loop:true,
        margin:50,
        navText : ["<i class='fa fa-arrow-left'></i> Previous","Next <i class='fa fa-arrow-right'></i>"],
        nav:true,
        items:1,
         responsive: {
            0: {
                stagePadding: 0
            },
            768: {
                stagePadding: 400
            }
        }
    })
    }



  jQuery(window).scroll(function(){
    if (jQuery(this).scrollTop() > 120) {
      jQuery('header').addClass('fixed');
    } else {
      jQuery('header').removeClass('fixed');
    }
});


    jQuery(".fwdlabs-toggle").click(function(e) {
		e.preventDefault();
		var selector = $(this).data('toggle');
        var $target = $(selector);
        $target.toggle();
    });

    jQuery('.carousel').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        dots: true
    });

    jQuery('.carousel-multiple').slick({
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 5000,
        dots: true,
        responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 1,
              }
            }
          ]
    });

    jQuery(document).on('click', '[data-lity-close]', function (e) {
        e.preventDefault();
    });

    $( "#bd article a" ).has( "img" ).attr('data-lity', '');

    var windowsize = $(window).width();
    $(window).resize(function() {
      windowsize = $(window).width();
      if (windowsize > 850) {
        if($("#search-bar").is(":hidden")){
          $("#search-bar").show();
        }
      }
    });

    var recaptchaTerms = '<small>This form is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank" rel="nofollow">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank" rel="nofollow">Terms of Service</a> apply.</small>';
	jQuery("form.wpcf7-form").append(recaptchaTerms);

});