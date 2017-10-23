<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class WP_Ssc_Script {
	
	function __construct() {
		
		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wp_ssc_front_style') );
		
		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wp_ssc_front_script') );
		
		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wp_ssc_admin_style') );
		
		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array($this, 'wp_ssc_admin_script') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_front_style() {


		// Registring and enqueing slick css		
		wp_register_style( 'wpos-swiper-style', WP_SSC_URL.'assets/css/swiper.min.css', array(), WP_SSC_VERSION );
		wp_enqueue_style( 'wpos-swiper-style');
		
		
		// Registring and enqueing public css
		wp_register_style( 'wp-ssc-public-css', WP_SSC_URL.'assets/css/wp-ssc-public.css', null, WP_SSC_VERSION );
		wp_enqueue_style( 'wp-ssc-public-css' );
	}
	
	/**
	 * Function to add script at front side
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_front_script() {

		
		// Registring slick slider script
		
		wp_register_script( 'wpos-swiper-jquery', WP_SSC_URL.'assets/js/swiper.min.js', array('jquery'), WP_SSC_VERSION, true );
		

		// Registring public script
		wp_register_script( 'wp-ssc-public-js', WP_SSC_URL.'assets/js/wp-ssc-public.js', array('jquery'), WP_SSC_VERSION, true );
	}
	
	/**
	 * Enqueue admin styles
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_admin_style( $hook ) {

		global $post_type, $typenow;
		
		$registered_posts = array(WP_SSC_POST_TYPE); // Getting registered post types

		// If page is plugin setting page then enqueue script
		if( in_array($post_type, $registered_posts) ) {
			
			// Registring admin script
			wp_register_style( 'wp-ssc-admin-style', WP_SSC_URL.'assets/css/wp-ssc-admin.css', null, WP_SSC_VERSION );
			wp_enqueue_style( 'wp-ssc-admin-style' );
		}
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @package Swiper Slider and Carousel
	 * @since 1.0.0
	 */
	function wp_ssc_admin_script( $hook ) {
		
		global $wp_version, $wp_query, $typenow, $post_type;
		
		$registered_posts = array(WP_SSC_POST_TYPE); // Getting registered post types
		$new_ui = $wp_version >= '3.5' ? '1' : '0'; // Check wordpress version for older scripts
		
		if( in_array($post_type, $registered_posts) ) {

			// Enqueue required inbuilt sctipt
			wp_enqueue_script( 'jquery-ui-sortable' );

			// Registring admin script
			wp_register_script( 'wp-ssc-admin-script', WP_SSC_URL.'assets/js/wp-ssc-admin.js', array('jquery'), WP_SSC_VERSION, true );
			wp_localize_script( 'wp-ssc-admin-script', 'WpSscAdmin', array(
																	'new_ui' 				=>	$new_ui,
																	'img_edit_popup_text'	=> __('Edit Image in Popup', 'swiper-slider-and-carousel'),
																	'attachment_edit_text'	=> __('Edit Image', 'swiper-slider-and-carousel'),
																	'img_delete_text'		=> __('Remove Image', 'swiper-slider-and-carousel'),
																));
			wp_enqueue_script( 'wp-ssc-admin-script' );
			wp_enqueue_media(); // For media uploader
		}
	}
}

$wp_ssc_script = new WP_Ssc_Script();