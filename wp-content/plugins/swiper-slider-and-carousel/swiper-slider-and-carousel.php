<?php
/**
 * Plugin Name: Swiper Slider and Carousel
 * Plugin URI: http://www.wponlinesupport.com/
 * Description: Plugin create custom post type - swiper slider, add mulipule images and settings. 
 * Author: WP Online Support 
 * Text Domain: swiper-slider-and-carousel
 * Domain Path: /languages/
 * Version: 1.0
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author SP Technolab
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'WP_SSC_VERSION' ) ) {
	define( 'WP_SSC_VERSION', '1.0' ); // Version of plugin
}
if( !defined( 'WP_SSC_DIR' ) ) {
    define( 'WP_SSC_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WP_SSC_URL' ) ) {
    define( 'WP_SSC_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WP_SSC_POST_TYPE' ) ) {
    define( 'WP_SSC_POST_TYPE', 'wp_ssc_gallery' ); // Plugin post type
}
if( !defined( 'WP_SSC_META_PREFIX' ) ) {
    define( 'WP_SSC_META_PREFIX', '_wp_ssc_' ); // Plugin metabox prefix
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
function wp_ssc_load_textdomain() {
	load_plugin_textdomain( 'swiper-slider-and-carousel', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'wp_ssc_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wp_ssc_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wp_ssc_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * set default values for the plugin options.
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
function wp_ssc_install() {
    
    // Register post type function
    wp_ssc_register_post_type();

    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */
function wp_ssc_uninstall() {
    
    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

// Functions File
require_once( WP_SSC_DIR . '/includes/wp-ssc-functions.php' );

// Plugin Post Type File
require_once( WP_SSC_DIR . '/includes/wp-ssc-post-types.php' );

// Script File
require_once( WP_SSC_DIR . '/includes/class-wp-ssc-script.php' );

// Admin Class File
require_once( WP_SSC_DIR . '/includes/admin/class-wp-ssc-admin.php' );

// Shortcode File
require_once( WP_SSC_DIR . '/includes/shortcode/wp-ssc-gallery-slider.php' );
require_once( WP_SSC_DIR . '/includes/shortcode/wp-ssc-gallery-carousel.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( WP_SSC_DIR . '/includes/admin/ssc-how-it-work.php' );
}