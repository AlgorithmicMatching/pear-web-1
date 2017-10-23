<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Wp_ssc_Admin {

	function __construct() {
		
		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wp_ssc_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post', array($this, 'wp_ssc_save_metabox_value') );

		// Action to register plugin settings
		add_action ( 'admin_init', array($this,'wp_ssc_register_settings') );

		// Action to add custom column to Gallery listing
		add_filter( 'manage_'.WP_SSC_POST_TYPE.'_posts_columns', array($this, 'wp_ssc_posts_columns') );

		// Action to add custom column data to Gallery listing
		add_action('manage_'.WP_SSC_POST_TYPE.'_posts_custom_column', array($this, 'wp_ssc_post_columns_data'), 10, 2);

		// Filter to add row data
		add_filter( 'post_row_actions', array($this, 'wp_ssc_add_post_row_data'), 10, 2 );

		// Action to add Attachment Popup HTML
		add_action( 'admin_footer', array($this,'wp_ssc_image_update_popup_html') );

		// Ajax call to update option
		add_action( 'wp_ajax_wp_ssc_get_attachment_edit_form', array($this, 'wp_ssc_get_attachment_edit_form'));
		add_action( 'wp_ajax_nopriv_wp_ssc_get_attachment_edit_form',array( $this, 'wp_ssc_get_attachment_edit_form'));

		// Ajax call to update attachment data
		add_action( 'wp_ajax_wp_ssc_save_attachment_data', array($this, 'wp_ssc_save_attachment_data'));
		add_action( 'wp_ajax_nopriv_wp_ssc_save_attachment_data',array( $this, 'wp_ssc_save_attachment_data'));
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_post_sett_metabox() {
		
		// Getting all post types
		$all_post_types = array(WP_SSC_POST_TYPE);
	
		add_meta_box( 'wp-igsp-post-sett', __( 'Swiper Slider- Settings', 'swiper-slider-and-carousel' ), array($this, 'wp_igsp_post_sett_mb_content'), $all_post_types, 'normal', 'high' );
		
		add_meta_box( 'wp-igsp-post-slider-sett', __( 'Swiper Slider Parameter', 'swiper-slider-and-carousel' ), array($this, 'wp_igsp_post_slider_sett_mb_content'), $all_post_types, 'normal', 'default' );	
		
	}
	
	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_igsp_post_sett_mb_content() {
		include_once( WP_SSC_DIR .'/includes/admin/metabox/wp-ssc-sett-metabox.php');
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_igsp_post_slider_sett_mb_content() {
		include_once( WP_SSC_DIR .'/includes/admin/metabox/wp-ssc-slider-parameter.php');
	}
	
	/**
	 * Function to save metabox values
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_save_metabox_value( $post_id ) {

		global $post_type;

		$registered_posts = array(WP_SSC_POST_TYPE); // Getting registered post types

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                	// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )  	// Check Revision
		|| ( !current_user_can('edit_post', $post_id) )              			// Check if user can edit the post.
		|| ( !in_array($post_type, $registered_posts) ) )             			// Check if user can edit the post.
		{
		  return $post_id;
		}

		$prefix = WP_SSC_META_PREFIX; // Taking metabox prefix
		
		// Choosing Style
		$style 					= isset($_POST[$prefix.'design_style']) 		? wp_ssc_slashes_deep($_POST[$prefix.'design_style']) 				 : '';

		// Taking variables
		$gallery_imgs 	= isset($_POST['wp_igsp_img']) 							? wp_ssc_slashes_deep($_POST['wp_igsp_img']) : '';
		
		// Getting Slider Variables
		$arrow_slider 		  		= isset($_POST[$prefix.'arrow_slider']) 				? wp_ssc_slashes_deep($_POST[$prefix.'arrow_slider']) 				: '';
		$pagination_slider 			= isset($_POST[$prefix.'pagination_slider']) 			? wp_ssc_slashes_deep($_POST[$prefix.'pagination_slider']) 			: '';
		$autoplay_slider 			= isset($_POST[$prefix.'autoplay_slider']) 				? wp_ssc_slashes_deep($_POST[$prefix.'autoplay_slider']) 			: '';
		$autoplay_speed_slider 		= isset($_POST[$prefix.'autoplay_speed_slider']) 		? wp_ssc_slashes_deep($_POST[$prefix.'autoplay_speed_slider']) 		: '';
		$auto_stop_slider			= isset($_POST[$prefix.'auto_stop_slider']) 			? wp_ssc_slashes_deep($_POST[$prefix.'auto_stop_slider']) 			: '';
		$speed_slider 				= isset($_POST[$prefix.'speed_slider']) 				? wp_ssc_slashes_deep($_POST[$prefix.'speed_slider']) 				: '';
		$animation_slider 	  		= isset($_POST[$prefix.'animation_slider']) 			? wp_ssc_slashes_deep($_POST[$prefix.'animation_slider']) 			: '';
		$height_slider 	  			= isset($_POST[$prefix.'height_slider']) 				? wp_ssc_slashes_deep($_POST[$prefix.'height_slider']) 				: '';
		$autoheight_slider   		= isset($_POST[$prefix.'autoheight_slider']) 			? wp_ssc_slashes_deep($_POST[$prefix.'autoheight_slider']) 			: '';
		$direction_slider   		= isset($_POST[$prefix.'direction_slider']) 			? wp_ssc_slashes_deep($_POST[$prefix.'direction_slider']) 			: '';
		$pagination_type_slider 	= isset($_POST[$prefix.'pagination_type_slider']) 		? wp_ssc_slashes_deep($_POST[$prefix.'pagination_type_slider']) 	: '';
		$space_between_slider 		= isset($_POST[$prefix.'space_between_slider']) 		? wp_ssc_slashes_deep($_POST[$prefix.'space_between_slider']) 		: '';
		$loop_slider 				= isset($_POST[$prefix.'loop_slider']) 					? wp_ssc_slashes_deep($_POST[$prefix.'loop_slider']) 				: '';

		// Getting Carousel Variables
		$slide_to_show_carousel 	= isset($_POST[$prefix.'slide_to_show_carousel']) 		? wp_ssc_slashes_deep($_POST[$prefix.'slide_to_show_carousel']) 	: '';
		$slide_to_column_carousel 	= isset($_POST[$prefix.'slide_to_column_carousel']) 	? wp_ssc_slashes_deep($_POST[$prefix.'slide_to_column_carousel']) 	: '';
		$arrow_carousel 			= isset($_POST[$prefix.'arrow_carousel']) 				? wp_ssc_slashes_deep($_POST[$prefix.'arrow_carousel']) 			: '';
		$pagination_carousel 		= isset($_POST[$prefix.'pagination_carousel']) 			? wp_ssc_slashes_deep($_POST[$prefix.'pagination_carousel']) 		: '';
		$speed_carousel 			= isset($_POST[$prefix.'speed_carousel']) 				? wp_ssc_slashes_deep($_POST[$prefix.'speed_carousel']) 			: '';
		$autoplay_carousel 			= isset($_POST[$prefix.'autoplay_carousel']) 			? wp_ssc_slashes_deep($_POST[$prefix.'autoplay_carousel']) 			: '';
		$autoplay_speed_carousel	= isset($_POST[$prefix.'autoplay_speed_carousel']) 		? wp_ssc_slashes_deep($_POST[$prefix.'autoplay_speed_carousel']) 	: '';
		$auto_stop_carousel 	  	= isset($_POST[$prefix.'auto_stop_carousel']) 			? wp_ssc_slashes_deep($_POST[$prefix.'auto_stop_carousel']) 		: '';
		$pagination_type_carousel 	= isset($_POST[$prefix.'pagination_type_carousel']) 	? wp_ssc_slashes_deep($_POST[$prefix.'pagination_type_carousel']) 	: '';
		$centermode_carousel 		= isset($_POST[$prefix.'centermode_carousel']) 			? wp_ssc_slashes_deep($_POST[$prefix.'centermode_carousel']) 		: '';
		$space_between_carousel 	= isset($_POST[$prefix.'space_between_carousel']) 		? wp_ssc_slashes_deep($_POST[$prefix.'space_between_carousel']) 	: '';
		$loop_carousel 				= isset($_POST[$prefix.'loop_carousel']) 				? wp_ssc_slashes_deep($_POST[$prefix.'loop_carousel']) 				: '';
		// Style option update
		update_post_meta($post_id, $prefix.'design_style', $style);
		
		update_post_meta($post_id, $prefix.'gallery_id', $gallery_imgs);
		
		// Updating Slider settings
 		update_post_meta($post_id, $prefix.'arrow_slider', $arrow_slider);
 		update_post_meta($post_id, $prefix.'pagination_slider', $pagination_slider);
 		update_post_meta($post_id, $prefix.'autoplay_slider', $autoplay_slider);
 		update_post_meta($post_id, $prefix.'autoplay_speed_slider', $autoplay_speed_slider);
		update_post_meta($post_id, $prefix.'auto_stop_slider', $auto_stop_slider);
		update_post_meta($post_id, $prefix.'speed_slider', $speed_slider);
		update_post_meta($post_id, $prefix.'animation_slider', $animation_slider);
		update_post_meta($post_id, $prefix.'height_slider', $height_slider);
		update_post_meta($post_id, $prefix.'autoheight_slider', $autoheight_slider);
		update_post_meta($post_id, $prefix.'direction_slider', $direction_slider);
		update_post_meta($post_id, $prefix.'pagination_type_slider', $pagination_type_slider);
		update_post_meta($post_id, $prefix.'space_between_slider', $space_between_slider);
		update_post_meta($post_id, $prefix.'loop_slider', $loop_slider);

		// Updating Carousel settings
		update_post_meta($post_id, $prefix.'slide_to_show_carousel', $slide_to_show_carousel);
		update_post_meta($post_id, $prefix.'slide_to_column_carousel', $slide_to_column_carousel);
		update_post_meta($post_id, $prefix.'arrow_carousel', $arrow_carousel);
		update_post_meta($post_id, $prefix.'pagination_carousel', $pagination_carousel);
		update_post_meta($post_id, $prefix.'speed_carousel', $speed_carousel);
		update_post_meta($post_id, $prefix.'autoplay_carousel', $autoplay_carousel);
		update_post_meta($post_id, $prefix.'autoplay_speed_carousel', $autoplay_speed_carousel);
		update_post_meta($post_id, $prefix.'auto_stop_carousel', $auto_stop_carousel);
		update_post_meta($post_id, $prefix.'pagination_type_carousel', $pagination_type_carousel);
		update_post_meta($post_id, $prefix.'centermode_carousel', $centermode_carousel);
		update_post_meta($post_id, $prefix.'space_between_carousel', $space_between_carousel);
		update_post_meta($post_id, $prefix.'loop_carousel', $loop_carousel);
	}

	/**
	 * Function register setings
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_register_settings() {
		register_setting( 'wp_igsp_plugin_options', 'wp_igsp_options', array($this, 'wp_igsp_validate_options') );
	}
	
	/**
	 * Validate Settings Options
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_igsp_validate_options( $input ) {
		
		$input['default_img'] 	= isset($input['default_img']) 	? wp_ssc_slashes_deep($input['default_img']) 		: '';
		$input['custom_css'] 	= isset($input['custom_css']) 	? wp_ssc_slashes_deep($input['custom_css'], true) 	: '';
		
		return $input;
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_posts_columns( $columns ) {

	    $new_columns['wp_igsp_shortcode'] 	= __('Shortcode', 'swiper-slider-and-carousel');
	    $new_columns['wp_igsp_photos'] 		= __('Number of Photos', 'swiper-slider-and-carousel');

	    $columns = wp_ssc_add_array( $columns, $new_columns, 1, true );

	    return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variables
		$prefix = WP_SSC_META_PREFIX;

	    switch ($column) {
	    	case 'wp_igsp_shortcode':
	    		
	    		echo '<div class="wp-igsp-shortcode-preview">[swiper_slider id="'.$post_id.'"]</div> <br/>';
	    		echo '<div class="wp-igsp-shortcode-preview">[swiper_carousel id="'.$post_id.'"]</div>';
	    		break;

	    	case 'wp_igsp_photos':
	    		$total_photos = get_post_meta($post_id, $prefix.'gallery_id', true);
	    		echo !empty($total_photos) ? count($total_photos) : '--';
	    		break;
		}
	}

	/**
	 * Function to add custom quick links at post listing page
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_add_post_row_data( $actions, $post ) {
		
		if( $post->post_type == WP_SSC_POST_TYPE ) {
			return array_merge( array( 'wp_igsp_id' => 'ID: ' . $post->ID ), $actions );
		}
		
		return $actions;
	}

	/**
	 * Image data popup HTML
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_image_update_popup_html() {

		global $post_type;

		$registered_posts = array(WP_SSC_POST_TYPE); // Getting registered post types

		if( in_array($post_type, $registered_posts) ) {
			include_once( WP_SSC_DIR .'/includes/admin/settings/wp-ssc-img-popup.php');
		}
	}

	/**
	 * Get attachment edit form
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_get_attachment_edit_form() {

		// Taking some defaults
		$result 			= array();
		$result['success'] 	= 0;
		$result['msg'] 		= __('Sorry, Something happened wrong.', 'swiper-slider-and-carousel');
		$attachment_id 		= !empty($_POST['attachment_id']) ? trim($_POST['attachment_id']) : '';

		if( !empty($attachment_id) ) {
			$attachment_post = get_post( $_POST['attachment_id'] );

			if( !empty($attachment_post) ) {
				
				ob_start();

				// Popup Data File
				include( WP_SSC_DIR . '/includes/admin/settings/wp-ssc-img-popup-data.php' );

				$attachment_data = ob_get_clean();

				$result['success'] 	= 1;
				$result['msg'] 		= __('Attachment Found.', 'swiper-slider-and-carousel');
				$result['data']		= $attachment_data;
			}
		}

		echo json_encode($result);
		exit;
	}

	/**
	 * Get attachment edit form
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_save_attachment_data() {

		$prefix 			= WP_SSC_META_PREFIX;
		$result 			= array();
		$result['success'] 	= 0;
		$result['msg'] 		= __('Sorry, Something happened wrong.', 'swiper-slider-and-carousel');
		$attachment_id 		= !empty($_POST['attachment_id']) ? trim($_POST['attachment_id']) : '';
		$form_data 			= parse_str($_POST['form_data'], $form_data_arr);

		if( !empty($attachment_id) && !empty($form_data_arr) ) {

			// Getting attachment post
			$wp_ssc_attachment_post = get_post( $attachment_id );

			// If post type is attachment
			if( isset($wp_ssc_attachment_post->post_type) && $wp_ssc_attachment_post->post_type == 'attachment' ) {
				$post_args = array(
									'ID'			=> $attachment_id,
									'post_title'	=> !empty($form_data_arr['wp_igsp_attachment_title']) ? $form_data_arr['wp_igsp_attachment_title'] : $wp_ssc_attachment_post->post_name,
									'post_content'	=> $form_data_arr['wp_igsp_attachment_desc'],
									'post_excerpt'	=> $form_data_arr['wp_igsp_attachment_caption'],
								);
				$update = wp_update_post( $post_args, $wp_error );

				if( !is_wp_error( $update ) ) {

					update_post_meta( $attachment_id, '_wp_attachment_image_alt', wp_ssc_slashes_deep($form_data_arr['wp_igsp_attachment_alt']) );
					update_post_meta( $attachment_id, $prefix.'attachment_link', wp_ssc_slashes_deep($form_data_arr['wp_igsp_attachment_link']) );

					$result['success'] 	= 1;
					$result['msg'] 		= __('Your changes saved successfully.', 'swiper-slider-and-carousel');
				}
			}
		}
		echo json_encode($result);
		exit;
	}
}

$wp_ssc_admin = new Wp_Ssc_Admin();