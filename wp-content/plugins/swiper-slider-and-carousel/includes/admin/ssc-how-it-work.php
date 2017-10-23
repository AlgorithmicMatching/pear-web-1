<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Action to add menu
add_action('admin_menu', 'wp_ssc_register_design_page');

/**
 * Register plugin design page in admin menu
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function wp_ssc_register_design_page() {
	add_submenu_page( 'edit.php?post_type='.WP_SSC_POST_TYPE, __('How it works, our plugins and offers', 'swiper-slider-and-carousel'), __('How It Works', 'swiper-slider-and-carousel'), 'manage_options', 'ssc-designs', 'wp_ssc_designs_page' );
}

/**
 * Function to display plugin design HTML
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function wp_ssc_designs_page() {

	$wpos_feed_tabs = wp_ssc_help_tabs();
	$active_tab 	= isset($_GET['tab']) ? $_GET['tab'] : 'how-it-work';
?>
		
	<div class="wrap ssc-wrap">

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($wpos_feed_tabs as $tab_key => $tab_val) {
				$tab_name	= $tab_val['name'];
				$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
				$tab_link 	= add_query_arg( array( 'post_type' => WP_SSC_POST_TYPE, 'page' => 'ssc-designs', 'tab' => $tab_key), admin_url('edit.php') );
			?>

			<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_name; ?></a>

			<?php } ?>
		</h2>
		
		<div class="ssc-tab-cnt-wrp">
		<?php
			if( isset($active_tab) && $active_tab == 'how-it-work' ) {
				wp_ssc_howitwork_page();
			}
			else if( isset($active_tab) && $active_tab == 'plugins-feed' ) {
				echo wp_ssc_get_plugin_design( 'plugins-feed' );
			} else {
				echo wp_ssc_get_plugin_design( 'offers-feed' );
			}
		?>
		</div><!-- end .ssc-tab-cnt-wrp -->

	</div><!-- end .ssc-wrap -->

<?php
}

/**
 * Gets the plugin design part feed
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function wp_ssc_get_plugin_design( $feed_type = '' ) {
	
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
	
	// If tab is not set then return
	if( empty($active_tab) ) {
		return false;
	}

	// Taking some variables
	$wpos_feed_tabs = wp_ssc_help_tabs();
	$transient_key 	= isset($wpos_feed_tabs[$active_tab]['transient_key']) 	? $wpos_feed_tabs[$active_tab]['transient_key'] 	: 'ssc_' . $active_tab;
	$url 			= isset($wpos_feed_tabs[$active_tab]['url']) 			? $wpos_feed_tabs[$active_tab]['url'] 				: '';
	$transient_time = isset($wpos_feed_tabs[$active_tab]['transient_time']) ? $wpos_feed_tabs[$active_tab]['transient_time'] 	: 172800;
	$cache 			= get_transient( $transient_key );
	
	if ( false === $cache ) {
		
		$feed 			= wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );
		$response_code 	= wp_remote_retrieve_response_code( $feed );
		
		if ( ! is_wp_error( $feed ) && $response_code == 200 ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( $transient_key, $cache, $transient_time );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'swiper-slider-and-carousel' ) . '</div>';
		}
	}
	return $cache;	
}

/**
 * Function to get plugin feed tabs
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function wp_ssc_help_tabs() {
	$wpos_feed_tabs = array(
						'how-it-work' 	=> array(
													'name' => __('How It Works', 'swiper-slider-and-carousel'),
												),
						'plugins-feed' 	=> array(
													'name' 				=> __('Our Plugins', 'swiper-slider-and-carousel'),
													'url'				=> 'http://wponlinesupport.com/plugin-data-api/plugins-data.php',
													'transient_key'		=> 'wpos_plugins_feed',
													'transient_time'	=> 172800
												),
						'offers-feed' 	=> array(
													'name'				=> __('WPOS Offers', 'swiper-slider-and-carousel'),
													'url'				=> 'http://wponlinesupport.com/plugin-data-api/wpos-offers.php',
													'transient_key'		=> 'wpos_offers_feed',
													'transient_time'	=> 86400,
												)
					);
	return $wpos_feed_tabs;
}

/**
 * Function to get 'How It Works' HTML
 *
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */
function wp_ssc_howitwork_page() { ?>
	
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.ssc-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.ssc-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
	</style>

	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
			
				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								
								<h3 class="hndle">
									<span><?php _e( 'How It Works - Display and shortcode', 'swiper-slider-and-carousel' ); ?></span>
								</h3>
								
								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label><?php _e('Getting Started with Swiper Slider', 'swiper-slider-and-carousel'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1: This plugin create a Gallery mata box under your Swiper Slider tab in WordPress menu section', 'swiper-slider-and-carousel'); ?></li>
														<li><?php _e('Step-2: You can you to any Swiper Slider POST and check a "Swiper Slider and Carousel - Settings" meta box in the end.', 'swiper-slider-and-carousel'); ?></li>
														<li><?php _e('Step-3: Under "Choose Gallery Images" click on "Gallery Images" button and select multiple images from WordPress media and click on "Add to Gallery" button. Once images added you can add the shortcode in the the same POST', 'swiper-slider-and-carousel'); ?></li>
														<li><?php _e('Step-4: If you want a sapreate section, then you can see "Swiper Slider" tab in the Wordpress menu.', 'swiper-slider-and-carousel'); ?></li>
														<li><?php _e('Step-5: Use this tab to manage your image galleries and use the shortcode from the list.', 'swiper-slider-and-carousel'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('How Shortcode Works', 'swiper-slider-and-carousel'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-2: If you are using "Swiper Slider tab", then click on "Swiper Slider--> Swiper Slider" and find out the shortcode.', 'swiper-slider-and-carousel'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('All Shortcodes', 'swiper-slider-and-carousel'); ?>:</label>
												</th>
												<td>
													<span class="ssc-shortcode-preview">[swiper_slider]</span> – <?php _e('Gallery Slider', 'swiper-slider-and-carousel'); ?> <br />
													<span class="ssc-shortcode-preview">[swiper_carousel]</span> – <?php _e('Gallery Carousel Slider', 'swiper-slider-and-carousel'); ?>
													
												</td>
											</tr>						
												
											<tr>
												<th>
													<label><?php _e('Need Support?', 'swiper-slider-and-carousel'); ?></label>
												</th>
												<td>
													<p><?php _e('Check plugin document for shortcode parameters and demo for designs.', 'swiper-slider-and-carousel'); ?></p> <br/>
													<a class="button button-primary" href="https://www.wponlinesupport.com/plugins-documentation/document-meta-slider-carousel-lightbox/?utm_source=hp&event=doc" target="_blank"><?php _e('Documentation', 'swiper-slider-and-carousel'); ?></a>									
													<a class="button button-primary" href="http://demo.wponlinesupport.com/swiper-slider-and-carousel-demo/?utm_source=hp&event=demo" target="_blank"><?php _e('Demo for Designs', 'swiper-slider-and-carousel'); ?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-body-content -->
				
				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="metabox-holder wpos-pro-box">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox" style="">
									
								<h3 class="hndle">
									<span><?php _e( 'Upgrate to Pro', 'swiper-slider-and-carousel' ); ?></span>
								</h3>
								<div class="inside">										
									<ul class="wpos-list">
										<li>15+ image gallery designs</li>
										<li>Gallery Slider with Lightbox</li>
										<li>Gallery Carousel with Lightbox</li>
										<li>Gallery slider with variable width with Lightbox</li>
										<li>Custom css</li>										
										<li>Slider RTL support</li>
										<li>Fully responsive</li>
										<li>100% Multi language</li>
									</ul>
									<a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/wp-plugin/meta-slider-carousel-lightbox/?utm_source=hp&event=go_premium" target="_blank"><?php _e('Go Premium ', 'swiper-slider-and-carousel'); ?></a>	
									<p><a class="button button-primary wpos-button-full" href="http://demo.wponlinesupport.com/prodemo/swiper-slider-and-carousel/?utm_source=hp&event=pro_demo" target="_blank"><?php _e('View PRO Demo ', 'swiper-slider-and-carousel'); ?></a>			</p>								
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->

					<!-- Help to improve this plugin! -->
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
									<h3 class="hndle">
										<span><?php _e( 'Help to improve this plugin!', 'swiper-slider-and-carousel' ); ?></span>
									</h3>									
									<div class="inside">										
										<p>Enjoyed this plugin? You can help by rate this plugin <a href="https://wordpress.org/support/plugin/swiper-slider-and-carousel/reviews/?filter=5" target="_blank">5 stars!</a></p>
									</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-container-1 -->

			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div><!-- #post-box-container -->
<?php }