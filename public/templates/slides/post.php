<?php
/**
 * Post slide format template.
 *
 * @since	1.5.0
 */

$slide = new Foyer_Slide( get_the_id() );

$slide_post_id = get_post_meta( $slide->ID, 'slide_post_post_id', true );
$slide_post = get_post( $slide_post_id );
$slide_post_img = get_the_post_thumbnail( $slide_post->ID, 'foyer_fhd_square' );

$slide_post_display_thumbnail = get_post_meta( $slide->ID, 'slide_post_display_thumbnail', true );
$slide_post_use_excerpt = get_post_meta( $slide->ID, 'slide_post_use_excerpt', true );

if ( ! empty( $slide_post_use_excerpt ) ) {
	$content = apply_filters( 'the_content', $slide_post->post_excerpt );
}
else {
	$content = apply_filters( 'the_content', $slide_post->post_content );
}

?><div<?php $slide->classes(); ?><?php $slide->data_attr(); ?>>
	<div class="inner">
		<?php if ( ! empty( $slide_post_id ) ) { ?>
			<?php if ( ! empty( $slide_post_display_thumbnail ) && ! empty( $slide_post_img ) ) { ?>
				<figure>
					<?php echo $slide_post_img; ?>
				</figure>
			<?php } ?>
			<div class="foyer-slide-fields">
				<div class="foyer-slide-field foyer-slide-field-title"><span><?php echo get_the_title( $slide_post->ID ); ?></span></div>
				<div class="foyer-slide-field foyer-slide-field-date"><span><?php echo get_the_date( false, $slide_post->ID ); ?></span></div>
				<?php if ( ! empty( $content ) ) { ?>
					<div class="foyer-slide-field foyer-slide-field-content"><?php echo $content; ?></div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	<?php $slide->background(); ?>
</div>