<?php
/**
 * Image Data Popup
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>

<div class="wp-igsp-img-data-wrp wp-igsp-hide">
	<div class="wp-igsp-img-data-cnt">

		<div class="wp-igsp-img-cnt-block">
			<div class="wp-igsp-popup-close wp-igsp-popup-close-wrp"><img src="<?php echo WP_SSC_URL; ?>assets/images/close.png" alt="<?php _e('Close (Esc)', 'swiper-slider-and-carousel'); ?>" title="<?php _e('Close (Esc)', 'swiper-slider-and-carousel'); ?>" /></div>

			<div class="wp-igsp-popup-body-wrp">
			</div><!-- end .wp-igsp-popup-body-wrp -->
			
			<div class="wp-igsp-img-loader"><?php _e('Please Wait', 'swiper-slider-and-carousel'); ?> <span class="spinner"></span></div>

		</div><!-- end .wp-igsp-img-cnt-block -->

	</div><!-- end .wp-igsp-img-data-cnt -->
</div><!-- end .wp-igsp-img-data-wrp -->
<div class="wp-igsp-popup-overlay"></div>