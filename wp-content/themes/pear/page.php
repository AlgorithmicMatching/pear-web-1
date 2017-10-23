<!-- <div class="terms-privacy pl16 pr16">
	<div class="row flex--justify-center"> -->
		<?php while ( have_posts() ) : the_post(); ?>

			<!--<div class="col-xs-12 col-sm-8 text-center mt84 pt96 pb72">
				<?php get_template_part( 'templates/page', 'header' ); ?>
			</div>-->

			<?php the_content(); ?>

			<!--<div class="email col-xs-12 col-sm-5 pb96 text-center">
				<img src="<?php echo get_template_directory_uri() ?>/dist/images/mail-icon.svg" style="height: 80px; width: 80px;"/>
				<p class="text-size-xss margin0 text-thin">If you have other questions, or other technical issues, please
					contact us at <a class="text-link" href="mailto:support@pear.me">support@pear.me</a></p>
			</div>-->

		<?php endwhile; ?>
<!-- 	</div>
</div> -->
