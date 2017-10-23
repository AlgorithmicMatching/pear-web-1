<?php
/**
 * Register Post type functionality
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Function to register post type
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
function wp_ssc_register_post_type() {
	
	$wp_ssc_post_lbls = apply_filters( 'wp_ssc_post_labels', array(
								'name'                 	=> __('Swiper Slider', 'swiper-slider-and-carousel'),
								'singular_name'        	=> __('Swiper Slider', 'swiper-slider-and-carousel'),
								'add_new'              	=> __('Add Slider', 'swiper-slider-and-carousel'),
								'add_new_item'         	=> __('Add New Image Slider', 'swiper-slider-and-carousel'),
								'edit_item'            	=> __('Edit Image Slider', 'swiper-slider-and-carousel'),
								'new_item'             	=> __('New Image Slider', 'swiper-slider-and-carousel'),
								'view_item'            	=> __('View Image Slider', 'swiper-slider-and-carousel'),
								'search_items'         	=> __('Search Image Slider', 'swiper-slider-and-carousel'),
								'not_found'            	=> __('No Image Slider found', 'swiper-slider-and-carousel'),
								'not_found_in_trash'   	=> __('No Image Slider found in Trash', 'swiper-slider-and-carousel'),								
								'menu_name'           	=> __('Swiper Slider', 'swiper-slider-and-carousel')
							));

	$wp_ssc_slider_args = array(
		'labels'				=> $wp_ssc_post_lbls,
		'public'              	=> false,
		'show_ui'             	=> true,
		'query_var'           	=> false,
		'rewrite'             	=> false,
		'capability_type'     	=> 'post',
		'hierarchical'        	=> false,
		'menu_icon'				=> 'dashicons-format-gallery',
		'supports'            	=> apply_filters('wp_igsp_post_supports', array('title')),
	);

	// Register slick slider post type
	register_post_type( WP_SSC_POST_TYPE, apply_filters( 'wp_igsp_registered_post_type_args', $wp_ssc_slider_args ) );
}

// Action to register plugin post type
add_action('init', 'wp_ssc_register_post_type');

/**
 * Function to update post message for team showcase
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
function wp_ssc_post_updated_messages( $messages ) {
	
	global $post, $post_ID;
	
	$messages[WP_SSC_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Image Gallery updated.', 'swiper-slider-and-carousel' ) ),
		2 => __( 'Custom field updated.', 'swiper-slider-and-carousel' ),
		3 => __( 'Custom field deleted.', 'swiper-slider-and-carousel' ),
		4 => __( 'Image Gallery updated.', 'swiper-slider-and-carousel' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Image Gallery restored to revision from %s', 'swiper-slider-and-carousel' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Image Gallery published.', 'swiper-slider-and-carousel' ) ),
		7 => __( 'Image Gallery saved.', 'swiper-slider-and-carousel' ),
		8 => sprintf( __( 'Image Gallery submitted.', 'swiper-slider-and-carousel' ) ),
		9 => sprintf( __( 'Image Gallery scheduled for: <strong>%1$s</strong>.', 'swiper-slider-and-carousel' ),
		  date_i18n( __( 'M j, Y @ G:i', 'swiper-slider-and-carousel' ), strtotime( $post->post_date ) ) ),
		10 => sprintf( __( 'Image Gallery draft updated.', 'swiper-slider-and-carousel' ) ),
	);
	
	return $messages;
}

// Filter to update slider post message
add_filter( 'post_updated_messages', 'wp_ssc_post_updated_messages' );