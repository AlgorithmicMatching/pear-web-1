<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;
use GeoIp2\Database\Reader;


/**
 * Theme setup
 */
function setup() {
	// Enable features from Soil when plugin is activated
	// https://roots.io/plugins/soil/
	add_theme_support( 'soil-clean-up' );
	add_theme_support( 'soil-nav-walker' );
	add_theme_support( 'soil-nice-search' );
	add_theme_support( 'soil-jquery-cdn' );
	add_theme_support( 'soil-relative-urls' );

	// Make theme available for translation
	// Community translations can be found at https://github.com/roots/sage-translations
	load_theme_textdomain( 'pear', get_template_directory() . '/lang' );

	// Enable plugins to manage the document title
	// http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
	add_theme_support( 'title-tag' );

	// Register wp_nav_menu() menus
	// http://codex.wordpress.org/Function_Reference/register_nav_menus
	register_nav_menus( [
		//'primary_navigation' => __( 'Primary Navigation', 'pear' )
	] );

	// Enable post thumbnails
	// http://codex.wordpress.org/Post_Thumbnails
	// http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
	// http://codex.wordpress.org/Function_Reference/add_image_size
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'medium', get_option( 'medium_size_w' ), get_option( 'medium_size_h' ), true );

	// Enable post formats
	// http://codex.wordpress.org/Post_Formats
	// add_theme_support( 'post-formats', [ 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ] );

	// Enable HTML5 markup support
	// http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
	add_theme_support( 'html5', [ 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ] );

	// Use main stylesheet for visual editor
	// To add custom styles edit /assets/styles/layouts/_tinymce.scss
	add_editor_style( Assets\asset_path( 'styles/main.css' ) );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Register sidebars
 */
function widgets_init() {

}

add_action( 'widgets_init', __NAMESPACE__ . '\\widgets_init' );

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
	static $display;

	isset( $display ) || $display = ! in_array( true, [
		// The sidebar will NOT be displayed if ANY of the following return true.
		// @link https://codex.wordpress.org/Conditional_Tags
		is_404(),
		is_front_page(),
		is_page_template( 'template-custom.php' ),
	] );

	return apply_filters( 'sage/display_sidebar', $display );
}

/**
 * Theme assets
 */
function assets() {
	wp_enqueue_style( 'sage/css', Assets\asset_path( 'styles/main.css' ), false, null );

	wp_enqueue_script( 'sage/js', Assets\asset_path( 'scripts/main.js' ), null, false, true );
	wp_enqueue_script( 'twitter', '//platform.twitter.com/widgets.js', [ 'sage/js' ], false, true );
	wp_enqueue_script( 'twitter', '//lightwidget.com/widgets/lightwidget.js', [ 'sage/js' ], false, true );

	wp_localize_script( 'sage/js', 'wp_theme_home', [ 'uri' => get_template_directory_uri() ] );
	wp_localize_script( 'sage/js', 'psf', [
		'ajax_url'            => admin_url( 'admin-ajax.php' ),
		'username_error'      => __( 'Please enter your name', 'pear' ),
		'useremail_error'     => __( 'Please enter your email address', 'pear' ),
		'useremail_wpdberror' => __( 'Email is already subscribed!', 'pear' ),
	] );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100 );

/**
 * Remove unnecessary wp scripts
 */
function remove_wp_trash() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10 ); // start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 ); // Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version

	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}

add_action( 'init', __NAMESPACE__ . '\\remove_wp_trash' );

function pear_menu_page_remove() {
	remove_menu_page( 'edit-comments.php' );
	if ( ! current_user_can( 'create_users' ) ) {
		remove_menu_page( 'tools.php' );
	}
}

add_action( 'admin_menu', __NAMESPACE__ . '\\pear_menu_page_remove' );

function pear_prevent_admin_access() {
	if ( ! current_user_can( 'create_users' ) ) {
		wp_die( "Sorry, you can't stay here." );
		exit();
	}
}

add_action( 'load-edit-comments.php', __NAMESPACE__ . '\\pear_prevent_admin_access' );
add_action( 'load-tools.php', __NAMESPACE__ . '\\pear_prevent_admin_access' );

function getGender($firstname) {
$myKey = 'ZHsJHhcMHCNKRAQDgg';
$data = json_decode(file_get_contents('https://gender-api.com/get?key=' . $myKey . '&name=' . urlencode($firstname)));
//$data = json_decode(file_get_contents('https://gender-api.com/get?name='.urlencode($firstname)));
return $data->gender;
}

function psfSubmissionHandler() {

	if ( ! isset( $_POST['psf_nonce'] ) || ! wp_verify_nonce( $_POST['psf_nonce'], 'psf_form_submit' ) ) {

		die( json_encode( [
			'status'  => 501,
			'title'   => 'Error',
			'message' => "Not authorized!"
		] ) );
	} else {
		global $wpdb;

		/*if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
			
		}
		
		$reader = new Reader( $_SERVER['DOCUMENT_ROOT'] . '/GeoLite2-City.mmdb' );
		try {
			$record = $reader->city( $ip );
		} catch ( \Exception $e ) {
			// geoip can't read ip data, do nothing
           $record = null;
		}

		if ( isset( $record->city->name ) ) {
			$location = $record->city->name;
		} elseif ( isset( $record->country->name ) ) {
			$location = $record->country->name;
		} elseif ( isset( $record->continent->name ) ) {
			$location = $record->continent->name;
		} else {
			$location = 'N/A';
		}

		if(isset( $record->city->name ) ){
			$city = $record->city->name;
		}
		else{
			$city = 'N/A';
		}

		if(isset( $record->country->name ) ){
			$country = $record->country->name;
		}
		else{
			$country = 'N/A';
		}*/

		$gender = getGender(filter_var( $_POST['user_name'], FILTER_SANITIZE_STRING ));
		$name = filter_var( $_POST['user_name'], FILTER_SANITIZE_STRING );
		$email = filter_var( $_POST['user_email'], FILTER_SANITIZE_EMAIL );
		$age = filter_var( $_POST['user_age'], FILTER_SANITIZE_NUMBER_INT );

		$location = 'N/A';
		$city = 'N/A';
		$country = 'N/A';

		$status = $wpdb->insert(
			$wpdb->prefix . 'psf',
			array(
				'Name'     => $name,
				'Email'    => $email,
				'Age'      => $age,
				'refferal_link' => filter_var( $_POST['user_refferal']),
				'Location' => $location,
				'City' => $city,
				'Country' => $country,
				'Gender' => $gender,
			)
		);

		if ( $status ) {
			/*$to  = $email;
			$subject = 'Sign Up Information Provided';
			$message = "<html><body><h3>Hello &nbsp;". $name .":</h3><br><p>Thanks For Signing Up. You Provide us the following Information.</p><br><b>Email: &nbsp;". $email ."</b><br><b>Age: &nbsp;".$age."</b></body></html>";
			$headers = array('Content-Type: text/html; charset=UTF-8'); 

			wp_mail($to,$subject,$message,$headers);*/

			die( json_encode( [
				'status'  => 200,
				'title'   => 'Success',
				'message' => __( 'Sign up successful!', 'pear' ),
				'refferal_link' => filter_var( $_POST['user_refferal']),
			] ) );
		} else {
			die( json_encode( [
				'status'  => 409,
				'title'   => 'Error',
				'message' => __( 'Email is already subscribed!', 'pear' )
			] ) );
		}
	}
}

add_action( 'wp_ajax_psf_form_submit', __NAMESPACE__ . '\\psfSubmissionHandler' );
add_action( 'wp_ajax_nopriv_psf_form_submit', __NAMESPACE__ . '\\psfSubmissionHandler' );
