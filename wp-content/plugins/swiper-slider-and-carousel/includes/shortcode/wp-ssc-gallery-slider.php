<?php
/**
 * 'meta_gallery_slider' Shortcode
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function wp_ssc_gallery_slider($atts, $content) {
	
	extract(shortcode_atts(array(
		'id'				=> '',
	), $atts));
	
	// Taking some globals
	global $post;

	// Taking some variables
	$unique 		= wp_ssc_get_unique();
	$gallery_id 	= !empty($id) ? $id	: $post->ID;

	$prefix = WP_SSC_META_PREFIX; // Metabox prefix
		
	$arrow 				= get_post_meta( $gallery_id, $prefix.'arrow_slider', true );
	$arrow 				= ($arrow == 'false') ? 'false' : 'true';
	
	$pagination 		= get_post_meta( $gallery_id, $prefix.'pagination_slider', true );
	$pagination 		= ($pagination == 'false') ? 'false' : 'true';

	$pagination_type 	= get_post_meta( $gallery_id, $prefix.'pagination_type_slider', true );
	$pagination_type 	= ($pagination_type == 'fraction') ? 'fraction' : 'bullets';
	
	$autoplay 			= get_post_meta( $gallery_id, $prefix.'autoplay_slider', true );
	$autoplay 			= ($autoplay == 'false') ? 'false' : 'true';
	
	$autoplay_speed 	= get_post_meta( $gallery_id, $prefix.'autoplay_speed_slider', true );
	$autoplay_speed 	= (!empty($autoplay_speed)) ? $autoplay_speed : '3000';
	
	$auto_stop 			= get_post_meta( $gallery_id, $prefix.'auto_stop_slider', true );
	$auto_stop 			= ($auto_stop == 'true') ? 'true' : 'false';
	
	$loop 				= get_post_meta( $gallery_id, $prefix.'loop_slider', true );
	$loop 				= ($loop == 'false') ? 'false' : 'true';

	$speed 				= get_post_meta( $gallery_id, $prefix.'speed_slider', true );
	$speed 				= (!empty($speed)) ? $speed : '300';
	
	$animation 			= get_post_meta( $gallery_id, $prefix.'animation_slider', true );
	$animation 			= ($animation == 'fade') ? 'fade' : 'slide';
	
	$autoheight 		= get_post_meta( $gallery_id, $prefix.'autoheight_slider', true );
	$autoheight 		= ($autoheight == 'true') ? 'true' : 'false';

	$height 			= get_post_meta( $gallery_id, $prefix.'height_slider', true );
	$height 			= (!empty($height) && $autoheight != 'true' ) ? $height : '';

	$direction 			= get_post_meta( $gallery_id, $prefix.'direction_slider', true );
	$direction 			= ($direction == 'vertical') ? 'vertical' : 'horizontal';

	$vertical_height 	= ($direction == 'vertical' && empty($height)) ? '500' : $height ;

	$space_between   	= get_post_meta( $gallery_id, $prefix.'space_between_slider', true );
	$space_between 		= (!empty($space_between)) ? $space_between : '0';

	$slider_height 		= (!empty($height)) ? 'style="height:'.$height.'px;"' : '';
	$slider_wrap_height = ($direction == 'vertical' && empty($height)) ? 'style="height:500px;"' : $slider_height;
	

	// Slider configuration
	$slider_conf = compact('pagination','pagination_type', 'autoplay', 'autoplay_speed', 'direction','auto_stop','speed','animation','vertical_height','autoheight','space_between','loop');

	// Enqueue required script
	wp_enqueue_script( 'wpos-swiper-jquery' );
	wp_enqueue_script( 'wp-ssc-public-js' );
	
	// Getting gallery images
	$images = get_post_meta($gallery_id, $prefix.'gallery_id', true);

	ob_start();

	if( $images ): ?>

		<div class="wpssc-slider-wrap msacwl-row-clearfix">

			<div id="msacwl-slider-<?php echo $unique; ?>" class="swiper-container wpssc-swiper-slider-wrapper" <?php echo $slider_wrap_height; ?>>
				
				<div class="swiper-wrapper wpssc-swiper-slider">
					
					<?php foreach( $images as $image ): 
						
						$post_mata_data = get_post($image);?>				
						
						<div class="swiper-slide">					 
						
						 	<?php $image_lsider = wp_get_attachment_image_src( $image, 'full' ); ?>
						
							<img src="<?php echo $image_lsider[0]; ?>" alt="slider image" />			
						</div>								
					<?php endforeach; ?>
				</div>

				<div class="wpssc-slider-conf"><?php echo json_encode( $slider_conf ); ?></div><!-- end of-slider-conf -->
				
				<?php if($pagination == 'true'){ ?>
					<div class="swiper-pagination"></div>
				<?php } ?>
	        
		        <!-- Add Arrows -->
		        <?php if($arrow == 'true'){ ?>

			        <div class="swiper-button-next"></div>
			        <div class="swiper-button-prev"></div>
		        <?php } ?>
			</div><!-- end .msacwl-slider -->
		</div><!-- end .msacwl-slider-wrap -->
	<?php endif;

	$content .= ob_get_clean();
	return $content;
}

// 'meta_gallery_slider' Shortcode
add_shortcode( 'swiper_slider', 'wp_ssc_gallery_slider' );