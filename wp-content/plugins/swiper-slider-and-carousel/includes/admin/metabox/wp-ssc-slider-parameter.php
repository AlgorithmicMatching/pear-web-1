<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$prefix = WP_SSC_META_PREFIX; // Metabox prefix

$style 						= get_post_meta( $post->ID, $prefix.'design_style', true );

// Slider Variables
$arrow_slider 				= get_post_meta( $post->ID, $prefix.'arrow_slider', true );
$pagination_slider 			= get_post_meta( $post->ID, $prefix.'pagination_slider', true );
$autoplay_slider 			= get_post_meta( $post->ID, $prefix.'autoplay_slider', true );
$autoplay_speed_slider 		= get_post_meta( $post->ID, $prefix.'autoplay_speed_slider', true );
$auto_stop_slider 			= get_post_meta( $post->ID, $prefix.'auto_stop_slider', true );
$speed_slider 				= get_post_meta( $post->ID, $prefix.'speed_slider', true );
$animation_slider 			= get_post_meta( $post->ID, $prefix.'animation_slider', true );
$height_slider 				= get_post_meta( $post->ID, $prefix.'height_slider', true );
$autoheight_slider 			= get_post_meta( $post->ID, $prefix.'autoheight_slider', true );
$direction_slider 			= get_post_meta( $post->ID, $prefix.'direction_slider', true );
$pagination_type_slider 	= get_post_meta( $post->ID, $prefix.'pagination_type_slider', true );
$space_between_slider 		= get_post_meta( $post->ID, $prefix.'space_between_slider', true );
$loop_slider 				= get_post_meta( $post->ID, $prefix.'loop_slider', true );

// Carousel Variables
$slide_to_show_carousel 	= get_post_meta( $post->ID, $prefix.'slide_to_show_carousel', true );
$slide_to_column_carousel 	= get_post_meta( $post->ID, $prefix.'slide_to_column_carousel', true );
$arrow_carousel 			= get_post_meta( $post->ID, $prefix.'arrow_carousel', true );
$pagination_carousel 		= get_post_meta( $post->ID, $prefix.'pagination_carousel', true );
$speed_carousel 			= get_post_meta( $post->ID, $prefix.'speed_carousel', true );
$autoplay_carousel 			= get_post_meta( $post->ID, $prefix.'autoplay_carousel', true );
$autoplay_speed_carousel	= get_post_meta( $post->ID, $prefix.'autoplay_speed_carousel', true );
$auto_stop_carousel 		= get_post_meta( $post->ID, $prefix.'auto_stop_carousel', true );
$pagination_type_carousel 	= get_post_meta( $post->ID, $prefix.'pagination_type_carousel', true );
$space_between_carousel 	= get_post_meta( $post->ID, $prefix.'space_between_carousel', true );
$centermode_carousel 		= get_post_meta( $post->ID, $prefix.'centermode_carousel', true );
$loop_carousel 				= get_post_meta( $post->ID, $prefix.'loop_carousel', true );
?>

<div class="wp-tsasp-mb-tabs-wrp">
	<table class="wp-tsasp-mdetails wp-tsasp-tab-cnt">
		<tr valign="top">
			<th scope="row">
				<label><?php _e('Slider/ Carousel Settings', 'swiper-slider-and-carousel'); ?></label>
			</th>
			<td scope="row">
				<select name="<?php echo $prefix; ?>design_style" class="wpssc-choose-design">
					<option value="slider" <?php selected( $style, 'slider'); ?>>Slider Settings</option>
					<option value="carousel" <?php selected( $style, 'carousel'); ?>>Carousel Settings</option>
				</select>
			</td>
		</tr>
	</table>
	
	<div id="wp-tsasp-mdetails" class="wp-tsasp-mdetails wpssc-slider" style="<?php if($style == 'carousel'){ echo 'display:none';  } ?>">
		<table class="form-table wp-tsasp-team-detail-tbl">
			<h3><?php _e('Choose your Settings for Slider', 'swiper-slider-and-carousel') ?></h3>
			<hr>
			<tbody>
				<tr valign="top">
					<h4><?php _e('Navigation & Pagination Settings', 'swiper-slider-and-carousel') ?></h4>
					<hr>
					<td scope="row">
						<label><?php _e('Arrow', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" value="true" name="<?php echo $prefix; ?>arrow_slider" <?php checked( 'true', $arrow_slider ); ?>>True
						<input type="radio" value="false" name="<?php echo $prefix; ?>arrow_slider" <?php checked( 'false', $arrow_slider ); ?>>False<br>
						<em style="font-size:11px;"><?php _e('Enable Arrows for slider','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Pagination', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>pagination_slider" value="true" <?php checked( 'true', $pagination_slider ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>pagination_slider" value="false" <?php checked( 'false', $pagination_slider ); ?>>False<br>
						<em style="font-size:11px;"><?php _e('String with CSS selector or HTML element of the container with pagination','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Pagination Type', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<select name="<?php echo $prefix; ?>pagination_type_slider">
							<option value="bullets" <?php selected( $pagination_type_slider, 'bullets'); ?>>Bullets</option>
							<option value="fraction" <?php selected( $pagination_type_slider, 'fraction'); ?>>Fraction</option>
						</select><br/>
						<em style="font-size:11px;"><?php _e('String with type of pagination. Can be "bullets", "fraction"','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table wp-tsasp-team-detail-tbl">
			<tbody>
				<tr valign="top">
					<h4><?php _e('General Settings', 'swiper-slider-and-carousel') ?></h4>
					<hr>
					<td scope="row">
						<label><?php _e('Autoplay', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>autoplay_slider" value="true" <?php checked( 'true', $autoplay_slider ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>autoplay_slider"  value="false" <?php checked( 'false', $autoplay_slider ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable Autoplay for Slider','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Autoplay Speed', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="1000" step="100" name="<?php echo $prefix; ?>autoplay_speed_slider" value="<?php echo wp_ssc_esc_attr($autoplay_speed_slider); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Space Between Slides', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="0" step="10" name="<?php echo $prefix; ?>space_between_slider" value="<?php echo wp_ssc_esc_attr($space_between_slider); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Distance between slides in px.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Speed', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="300" step="50" name="<?php echo $prefix; ?>speed_slider" value="<?php echo wp_ssc_esc_attr($speed_slider); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Duration of transition between slides (in ms)','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Loop', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>loop_slider" value="true" <?php checked( 'true', $loop_slider ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>loop_slider" value="false" <?php checked( 'false', $loop_slider ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Set to true to enable continuous loop mode','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Autoplay Stop On Last', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>auto_stop_slider" value="true" <?php checked( 'true', $auto_stop_slider ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>auto_stop_slider" value="false" <?php checked( 'false', $auto_stop_slider ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable this parameter and autoplay will be stopped when it reaches last slide','swiper-slider-and-carousel'); ?></em><br/>
						<em style="font-size:11px;color:#ff0808;"><?php _e('This will work when loop is false.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table wp-tsasp-team-detail-tbl">
			<tbody>
				<tr valign="top">
					<h4><?php _e('Other Settings', 'swiper-slider-and-carousel') ?></h4>
					<hr>
					<td scope="row">
						<label><?php _e('Effect', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<select name="<?php echo $prefix; ?>animation_slider">
							<option value="slide" <?php if($animation_slider == 'slide'){echo 'selected'; } ?>>Slide</option>
							<option value="fade" <?php if($animation_slider == 'fade'){echo 'selected'; } ?>>Fade</option>
						</select><br/>
						<em style="font-size:11px;"><?php _e('Could be "slide", "fade"','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('AutoHeight', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>autoheight_slider" value="true" <?php checked( 'true', $autoheight_slider ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>autoheight_slider" value="false" <?php checked( 'false', $autoheight_slider ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Set to true and slider wrapper will adopt its height to the height of the currently active slide','swiper-slider-and-carousel'); ?></em><br/>
						<em style="font-size:11px;color:#ff0808;"><?php _e('Set this parameter false if you want to use height for slider.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Direction', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>direction_slider" value="horizontal" <?php checked( 'horizontal', $direction_slider ); ?>>Horizontal
						<input type="radio" name="<?php echo $prefix; ?>direction_slider" value="vertical" <?php checked( 'vertical', $direction_slider ); ?>>Vertical<br/>
						<em style="font-size:11px;"><?php _e('Could be "horizontal" or "vertical" (for vertical slider).','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Height', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="1" class="wp-igsp-slider" name="<?php echo $prefix; ?>height_slider" value="<?php echo wp_ssc_esc_attr($height_slider); ?>">px<br/>
						<em style="font-size:11px;"><?php _e('Swiper height (in px). Parameter allows to force Swiper height. Useful only if you initialize Swiper when it is hidden.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
			</tbody>
		</table><!-- end .wtwp-tstmnl-table -->
	</div>

	<div id="wp-tsasp-sdetails" class="wp-tsasp-sdetails wp-tsasp-tab-cnt wpssc-carousel" style="<?php if($style == 'slider' || $style == '' ){ echo 'display:none';  } ?>">
		<table class="form-table wp-tsasp-sdetails-tbl">
		<h3><?php _e('Choose your Settings for Carousel', 'swiper-slider-and-carousel') ?></h3>
		<hr>	
			<tbody>
				<tr valign="top">
					<h4><?php _e('Slider Settings', 'swiper-slider-and-carousel') ?></h4>
					<hr>
					<td scope="row">
						<label><?php _e('Slide To Show', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
					<input type="number" min="1" step="1" name="<?php echo $prefix; ?>slide_to_show_carousel" value="<?php echo wp_ssc_esc_attr($slide_to_show_carousel); ?>"><br/>
					<em style="font-size:11px;"><?php _e('Number of slides per view (slides visible at the same time on slider container).','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Slide To Scroll', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="1" step="1" name="<?php echo $prefix; ?>slide_to_column_carousel" value="<?php echo wp_ssc_esc_attr($slide_to_column_carousel); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Set numbers of slides to define and enable group sliding. Useful to use with slidesPerView > 1','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Centermode', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>centermode_carousel" value="true" <?php checked( 'true', $centermode_carousel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>centermode_carousel" value="false" <?php checked( 'false', $centermode_carousel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('If true, then active slide will be centered, not always on the left side.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Space Between Slides', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="0" step="10" name="<?php echo $prefix; ?>space_between_carousel" value="<?php echo wp_ssc_esc_attr($space_between_carousel); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Distance between slides in px.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table wp-tsasp-sdetails-tbl">
			<tbody>
				<tr valign="top">
					<h4><?php _e('Navigation & Pagination Settings', 'swiper-slider-and-carousel') ?></h4>
					<hr>
					<td scope="row">
						<label><?php _e('Arrow', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>arrow_carousel" value="true" <?php checked( 'true', $arrow_carousel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>arrow_carousel" value="false" <?php checked( 'false', $arrow_carousel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable Arrows for slider','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Pagination', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>pagination_carousel" value="true" <?php checked( 'true', $pagination_carousel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>pagination_carousel" value="false" <?php checked( 'false', $pagination_carousel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('String with CSS selector or HTML element of the container with pagination','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Pagination Type', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<select name="<?php echo $prefix; ?>pagination_type_carousel">
							<option value="bullets" <?php selected( $pagination_type_carousel, 'bullets'); ?>>Bullets</option>
							<option value="fraction" <?php selected( $pagination_type_carousel, 'fraction'); ?>>Fraction</option>
						</select><br/>
						<em style="font-size:11px;"><?php _e('String with type of pagination. Can be "bullets", "fraction"','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table wp-tsasp-sdetails-tbl">
			<tbody>
				<tr valign="top">
					<h4><?php _e('Genaral Settings', 'swiper-slider-and-carousel') ?></h4>
					<hr>
					<td scope="row">
						<label><?php _e('Autoplay', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>autoplay_carousel" value="true" <?php checked( 'true', $autoplay_carousel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>autoplay_carousel"  value="false" <?php checked( 'false', $autoplay_carousel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable Autoplay for Slider','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">
						<label><?php _e('Autoplay Speed', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="1000" step="100" name="<?php echo $prefix; ?>autoplay_speed_carousel" value="<?php echo wp_ssc_esc_attr($autoplay_speed_carousel); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Speed', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="number" min="300" step="50" name="<?php echo $prefix; ?>speed_carousel" value="<?php echo wp_ssc_esc_attr($speed_carousel); ?>"><br/>
						<em style="font-size:11px;"><?php _e('Duration of transition between slides (in ms)','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Loop', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>loop_carousel" value="true" <?php checked( 'true', $loop_carousel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>loop_carousel" value="false" <?php checked( 'false', $loop_carousel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Set to true to enable continuous loop mode','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label><?php _e('Autoplay Stop On Last', 'swiper-slider-and-carousel'); ?></label>
					</td>
					<td>
						<input type="radio" name="<?php echo $prefix; ?>auto_stop_carousel" value="true" <?php checked( 'true', $auto_stop_carousel ); ?>>True
						<input type="radio" name="<?php echo $prefix; ?>auto_stop_carousel" value="false" <?php checked( 'false', $auto_stop_carousel ); ?>>False<br/>
						<em style="font-size:11px;"><?php _e('Enable this parameter and autoplay will be stopped when it reaches last slide','swiper-slider-and-carousel'); ?></em><br/>
						<em style="font-size:11px;color:#ff0808;"><?php _e('This will work when loop is false.','swiper-slider-and-carousel'); ?></em>
					</td>
				</tr>
			</tbody>
		</table><!-- end .wtwp-tstmnl-table -->
	</div>
</div>