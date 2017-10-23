jQuery(document).ready(function($){

    $( '.wpssc-swiper-slider' ).each(function( index ) {

		var slider_id	  	= $(this).parent().attr('id');
		var slider_conf 	= $.parseJSON( $(this).closest('.wpssc-slider-wrap').find('.wpssc-slider-conf').text());

		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {

			var swiper = new Swiper('#'+slider_id, {
		        paginationHide		: (slider_conf.pagination) == "true" 			? false 		: true,
		        paginationType 		: (slider_conf.pagination_type == 'fraction') 	? 'fraction' 	: 'bullets',
		        autoplay 			: (slider_conf.autoplay) == "true" ? parseInt(slider_conf.autoplay_speed) : '' ,
		        spaceBetween 		: parseInt(slider_conf.space_between),
		        speed 				: parseInt(slider_conf.speed),
		        loop 				: (slider_conf.loop) == "true" 					? true 			: false,
		        autoplayStopOnLast 	: (slider_conf.auto_stop) == "true" 			? true 			: false,
		        effect 				: (slider_conf.animation) == "fade" 			? 'fade' 		: 'slide',
		        height 				: parseInt(slider_conf.vertical_height),
		        direction 			: (slider_conf.direction) == "vertical" 		? 'vertical' 	: 'horizontal',
		        pagination 			: '.swiper-pagination',
		        paginationClickable : true,
		        nextButton			: '.swiper-button-next',
		        prevButton 			: '.swiper-button-prev',
		        autoHeight 			: (slider_conf.autoheight) == "true" 			? true 			: false,
	    	});
	    }
	});

	// For Carousel Slider
	$( '.wpssc-swiper-carousel' ).each(function( index ) {
		
		var slider_id   = $(this).parent().attr('id');
		var slider_conf = $.parseJSON( $(this).closest('.wpssc-carousel-wrap').find('.wpssc-carousel-conf').text());
		
		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {
			
			var swiper = new Swiper('#'+slider_id, {
				slidesPerView 		: parseInt(slider_conf.slide_to_show),
				slidesPerGroup 		: parseInt(slider_conf.slide_to_column),
				centeredSlides		: (slider_conf.centermode) == "true" 			? true 			: false,
				paginationHide 		: (slider_conf.pagination) == "true" 			? false 		: true,
		        paginationType 		: (slider_conf.pagination_type == 'fraction') 	? 'fraction' 	: 'bullets',
		        autoplay 			: (slider_conf.autoplay) == "true" ? parseInt(slider_conf.autoplay_speed) : '' ,
		        spaceBetween 		: parseInt(slider_conf.space_between),
		        speed 				: parseInt(slider_conf.speed),
		        loop				: (slider_conf.loop) == "true" 					? true 			: false,
		        autoplayStopOnLast 	: (slider_conf.auto_stop) == "true" 			? true 			: false,
		        pagination 			: '.swiper-pagination',
		        paginationClickable : true,
		        nextButton 			: '.swiper-button-next',
		        prevButton 			: '.swiper-button-prev',
				breakpoints: {
				    // when window width is <= 320px
				    320: {
				      slidesPerView: 1,
				    },
				    // when window width is <= 480px
				    480: {
				      slidesPerView: 1,
				    },
				    // when window width is <= 640px
				    640: {
				      slidesPerView: 3,
				    }
				}
	    	});
		}
	});
});