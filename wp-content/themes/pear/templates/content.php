<div <?php is_single() ? post_class( 'col-xs-12 col-md-6 col-sm-6 post' ) : post_class( 'text-center post' ); ?>>
	<a href="<?php the_permalink(); ?>"><div class="post-inner"><?php the_post_thumbnail( is_single() ? 'thumbnail' : 'medium' ); ?>
	<h5"><?php the_title(); ?></h5>
	</div>
	</a>
</div>