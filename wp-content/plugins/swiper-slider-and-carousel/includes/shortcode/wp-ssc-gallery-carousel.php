<?php
/**
 * 'meta_gallery_carousel' Shortcode
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function wp_ssc_gallery_carousel( $atts, $content ) {
	
	extract(shortcode_atts(array(
		'id'				=> '',
	), $atts));
	
	// Taking some globals
	global $post;

	// Taking some variables
	$unique 		= wp_ssc_get_unique();
	$gallery_id 	= !empty($id) ? $id	: $post->ID;

	$prefix = WP_SSC_META_PREFIX; // Metabox prefix

	$slide_to_show		= get_post_meta( $gallery_id, $prefix.'slide_to_show_carousel', true );
	$slide_to_show 		= (!empty($slide_to_show)) ? $slide_to_show : '2';
	
	$slide_to_column	= get_post_meta( $gallery_id, $prefix.'slide_to_column_carousel', true );
	$slide_to_column	= (!empty($slide_to_column)) ? $slide_to_column : '1';
	
	$arrow				= get_post_meta( $gallery_id, $prefix.'arrow_carousel', true );
	$arrow 				= ($arrow == 'false') ? 'false' : 'true';
	
	$pagination 		= get_post_meta( $gallery_id, $prefix.'pagination_carousel', true );
	$pagination 		= ($pagination == 'false') ? 'false' : 'true';

	$pagination_type 	= get_post_meta( $gallery_id, $prefix.'pagination_type_carousel', true );
	$pagination_type 	= ($pagination_type == 'fraction') ? 'fraction' : 'bullets';
	
	$speed 				= get_post_meta( $gallery_id, $prefix.'speed_carousel', true );
	$speed 				= (!empty($speed)) ? $speed : '300';

	$autoplay 			= get_post_meta( $gallery_id, $prefix.'autoplay_carousel', true );
	$autoplay 			= ($autoplay == 'false') ? 'false' : 'true';
	
	$autoplay_speed		= get_post_meta( $gallery_id, $prefix.'autoplay_speed_carousel', true );
	$autoplay_speed 	= (!empty($autoplay_speed)) ? $autoplay_speed : '3000';
	
	$auto_stop			= get_post_meta( $gallery_id, $prefix.'auto_stop_carousel', true );
	$auto_stop 			= ($auto_stop == 'true') ? 'true' : 'false';

	$loop				= get_post_meta( $gallery_id, $prefix.'loop_carousel', true );
	$loop 				= ($loop == 'true') ? 'true' : 'false';

	$centermode 		= get_post_meta( $gallery_id, $prefix.'centermode_carousel', true );
	$centermode 		= ($centermode == 'true') ? 'true' : 'false';

	$space_between   	= get_post_meta( $gallery_id, $prefix.'space_between_carousel', true );
	$space_between 		= (!empty($space_between)) ? $space_between : '0';

	// Slider configuration
	$slider_conf = compact('slide_to_show', 'slide_to_column', 'pagination','pagination_type', 'speed','autoplay','autoplay_speed','auto_stop','space_between','centermode','loop');

	// Enqueue required script
	wp_enqueue_script( 'wpos-swiper-jquery' );
	wp_enqueue_script( 'wp-ssc-public-js' );

	// Getting gallery images
	$images = get_post_meta($gallery_id, $prefix.'gallery_id', true);

	ob_start();
	
	if( $images ): ?>
		
		<div class="wpssc-carousel-wrap wpssc-row-clearfix">
		
			<div id="wpssc-carousel-<?php echo $unique; ?>" class="swiper-container wpssc-swiper-carousel-wrapper">
				
				<div class="swiper-wrapper wpssc-swiper-carousel">
					
					<?php foreach( $images as $image ): 
						
						$post_mata_data = get_post($image);?>
						
						<div class="swiper-slide">
						
						 	<?php $image_lsider = wp_get_attachment_image_src( $image, 'full' ); ?>
							<img src="<?php echo $image_lsider[0]; ?>" alt="slider image" />
						</div>
					<?php endforeach; ?>
				</div>
				
				<div class="wpssc-carousel-conf"><?php echo json_encode( $slider_conf ); ?></div><!-- end of-slider-conf -->
				
				<?php if($pagination == 'true'){ ?>
				
					<div class="swiper-pagination"></div>
				<?php } ?>
	        
		        <!-- Add Arrows -->
		        <?php if($arrow == 'true'){ ?>
			    
			        <div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
			    <?php } ?>
			</div><!-- end .msacwl-carousel -->
		</div><!-- end .msacwl-carousel-wrap -->
	<?php endif;
	
	$content .= ob_get_clean();
	return $content;
}

// 'meta_gallery_carousel' Shortcode
add_shortcode( 'swiper_carousel', 'wp_ssc_gallery_carousel' );