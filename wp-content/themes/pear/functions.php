<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */

require_once( $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php' );

$sage_includes = [
	'lib/acf.php',       // ACF files
	'lib/assets.php',    // Scripts and stylesheets
	'lib/extras.php',    // Custom functions
	'lib/setup.php',     // Theme setup
	'lib/titles.php',    // Page titles
	'lib/wrapper.php',   // Theme wrapper class
	'lib/customizer.php' // Theme customizer
];

foreach ( $sage_includes as $file ) {
	if ( ! $filepath = locate_template( $file ) ) {
		trigger_error( sprintf( __( 'Error locating %s for inclusion', 'pear' ), $file ), E_USER_ERROR );
	}

	require_once $filepath;
}
unset( $file, $filepath );


function my_recent_post()
 {
  global $post;
  $count = 0;
  $html = "";

  $my_query = new WP_Query( array(
       'post_type' => 'post',
       'posts_per_page' => -1,'orderby' => 'post_date', 'order' => 'ASC'
  ));
  $html .= " <div class='content-inner'>
				    <div class='container col-md-9'>
				      <div class='content-inner-title'>
				        <div class='row'>
				          <h2>Latest Posts</h2>
				        </div>
				      </div><div class='article'>";
  if( $my_query->have_posts() ) : while( $my_query->have_posts() ) : $my_query->the_post();
  	   $count ++;
  	   if($count % 2 != 0){
	    	$html .= "<div class='row'>";
	    }  
  	   $img = get_the_post_thumbnail_url( $my_query->ID);	

		$html .= "<div class='col-xs-12 col-sm-6 post'>
              <div class='post-inner'>
            <a href=\"" . get_permalink() . "\">
              <div class=''>
               <img src=\"" . $img . "\" />
              </div>
              <h5>" . get_the_title() . " </h5>
            </a>
          </div>
          </div>";		      
    if($count % 2 == 0){
    	$html .= "</div>";
    }   
  endwhile; 
  wp_reset_postdata();
  $html .= "</div>";
       $html .= "</div>";
  $html .= "</div>";
       $html .= "</div>";
       
  endif;

  return $html;
 }
 add_shortcode( 'recent', 'my_recent_post' );
