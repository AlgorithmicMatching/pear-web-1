<div class="article-cover" style="background-image: url('<?php the_post_thumbnail_url( 'large' ); ?>');">
</div>

<div style="margin-top: 20px;"><?php get_template_part( 'templates/page-header' ); ?></div>

<div class="article container pl16 pr16">

  <div>
    <?php get_template_part( 'templates/content-single', get_post_type() ); ?>
  </div>


  <?php
  $args         = array(
    'numberposts' => 4,
    'post_status' => 'publish',
  );
  $recent_posts = new WP_Query( $args );
  ?>
  <?php if ( $recent_posts->have_posts() ) : ?>
    <div>
      <div class="row mb64">
        <div class="col-xs-12">
          <h4 class="text-uppercase mt40 mb40">Lates posts</h4>
        </div>

        <?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
          <?php get_template_part( 'templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format() ); ?>
        <?php endwhile;
        wp_reset_postdata(); ?>

      </div>
    </div>
  <?php endif; ?>


</div>
