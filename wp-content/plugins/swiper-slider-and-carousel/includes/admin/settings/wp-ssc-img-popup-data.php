<?php
/**
 * Popup Image Data HTML
 *
 * @package Swiper Slider and Carousel
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$prefix = WP_SSC_META_PREFIX;

// Taking some values
$alt_text 			= get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
$attachment_link 	= get_post_meta( $attachment_id, $prefix.'attachment_link', true );
?>

<div class="wp-igsp-popup-title"><?php _e('Edit Image', 'swiper-slider-and-carousel'); ?></div>
	
<div class="wp-igsp-popup-body">

	<form method="post" class="wp-igsp-attachment-form">
		
		<?php if( !empty($attachment_post->guid) ) { ?>
		<div class="wp-igsp-popup-img-preview">
			<img src="<?php echo $attachment_post->guid; ?>" alt="" />
		</div>
		<?php } ?>
		<a href="<?php echo get_edit_post_link( $attachment_id ); ?>" target="_blank" class="button right"><i class="dashicons dashicons-edit"></i> <?php _e('Edit Image From Attachment Page', 'swiper-slider-and-carousel'); ?></a>

		<table class="form-table">
			<tr>
				<th><label for="wp-igsp-attachment-title"><?php _e('Title', 'swiper-slider-and-carousel'); ?>:</label></th>
				<td>
					<input type="text" name="wp_igsp_attachment_title" value="<?php echo wp_ssc_esc_attr($attachment_post->post_title); ?>" class="large-text wp-igsp-attachment-title" id="wp-igsp-attachment-title" />
					<span class="description"><?php _e('Enter image title.', 'swiper-slider-and-carousel'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wp-igsp-attachment-alt-text"><?php _e('Alternative Text', 'swiper-slider-and-carousel'); ?>:</label></th>
				<td>
					<input type="text" name="wp_igsp_attachment_alt" value="<?php echo wp_ssc_esc_attr($alt_text); ?>" class="large-text wp-igsp-attachment-alt-text" id="wp-igsp-attachment-alt-text" />
					<span class="description"><?php _e('Enter image alternative text.', 'swiper-slider-and-carousel'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wp-igsp-attachment-caption"><?php _e('Caption', 'swiper-slider-and-carousel'); ?>:</label></th>
				<td>
					<textarea name="wp_igsp_attachment_caption" class="large-text wp-igsp-attachment-caption" id="wp-igsp-attachment-caption"><?php echo wp_ssc_esc_attr($attachment_post->post_excerpt); ?></textarea>
					<span class="description"><?php _e('Enter image caption.', 'swiper-slider-and-carousel'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wp-igsp-attachment-desc"><?php _e('Description', 'swiper-slider-and-carousel'); ?>:</label></th>
				<td>
					<textarea name="wp_igsp_attachment_desc" class="large-text wp-igsp-attachment-desc" id="wp-igsp-attachment-desc"><?php echo wp_ssc_esc_attr($attachment_post->post_content); ?></textarea>
					<span class="description"><?php _e('Enter image description.', 'swiper-slider-and-carousel'); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="wp-igsp-attachment-link"><?php _e('Image Link', 'swiper-slider-and-carousel'); ?>:</label></th>
				<td>
					<input type="text" name="wp_igsp_attachment_link" value="<?php echo esc_url($attachment_link); ?>" class="large-text wp-igsp-attachment-link" id="wp-igsp-attachment-link" />
					<span class="description"><?php _e('Enter image link. e.g http://wponlinesupport.com', 'swiper-slider-and-carousel'); ?></span>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="right">
					<div class="wp-igsp-success wp-igsp-hide"></div>
					<div class="wp-igsp-error wp-igsp-hide"></div>
					<span class="spinner wp-igsp-spinner"></span>
					<button type="button" class="button button-primary wp-igsp-save-attachment-data" data-id="<?php echo $attachment_id; ?>"><i class="dashicons dashicons-yes"></i> <?php _e('Save Changes', 'swiper-slider-and-carousel'); ?></button>
					<button type="button" class="button wp-igsp-popup-close"><?php _e('Close', 'swiper-slider-and-carousel'); ?></button>
				</td>
			</tr>
		</table>
	</form><!-- end .wp-igsp-attachment-form -->

</div><!-- end .wp-igsp-popup-body -->