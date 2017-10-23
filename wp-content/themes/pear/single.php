
<!-- <div class="article-cover">
</div> -->
<div class="article">
 <div class="container col-md-9">
    <?php get_template_part( 'templates/page-header' ); ?>
    <div class="col-xs-12 col-sm-10 pimg">
        <img class="text-center" style="width:100%" src="<?php echo the_post_thumbnail_url( 'medium' ); ?>"/>
    </div>
  <div>
    <?php get_template_part( 'templates/content-single', get_post_type() ); ?>
  </div>


  <?php
  $args         = array(
    'posts_per_page' => 2,'orderby' => 'post_date', 'order' => 'ASC',
    'post_status' => 'publish',
  );
  $recent_posts = new WP_Query( $args );
  ?>
  <?php if ( $recent_posts->have_posts() ) : ?>
    <div class="row">
        <div class="col-xs-12">
          <div class="content-inner-title">
            <h2>Latest Posts</h2></div>
        </div>

        <?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
          <?php get_template_part( 'templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format() ); ?>
        <?php endwhile;
        wp_reset_postdata(); ?>

    </div>
  <?php endif; ?>


</div>
</div>
