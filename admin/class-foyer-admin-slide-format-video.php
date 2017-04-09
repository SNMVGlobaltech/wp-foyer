<?php

/**
 * Adds admin functionality for the Video slide format.
 *
 * @since		1.2.0
 *
 * @package		Foyer
 * @subpackage	Foyer/includes
 * @author		Menno Luitjes <menno@mennoluitjes.nl>
 */
class Foyer_Admin_Slide_Format_Video {

	/**
	 * Saves additional data for the Video slide format.
	 *
	 * @since	1.2.0
	 *
	 * @param	int		$post_id	The ID of the post being saved.
	 * @return	void
	 */
	static function save_slide_video( $post_id ) {
		$slide_video_video_id = sanitize_text_field( $_POST['slide_video_video_id'] );

		$slide_video_video_start = intval( $_POST['slide_video_video_start'] );
		if ( empty( $slide_video_video_start ) ) {
			$slide_video_video_start = '';
		}

		$slide_video_video_end = intval( $_POST['slide_video_video_end'] );
		if ( empty( $slide_video_video_end ) ) {
			$slide_video_video_end = '';
		}

		$slide_video_video_wait_for_end = intval( $_POST['slide_video_video_wait_for_end'] );
		if ( empty( $slide_video_video_wait_for_end ) ) {
			$slide_video_video_wait_for_end = '';
		}

		update_post_meta( $post_id, 'slide_video_video_id', $slide_video_video_id );
		update_post_meta( $post_id, 'slide_video_video_start', $slide_video_video_start );
		update_post_meta( $post_id, 'slide_video_video_end', $slide_video_video_end );
		update_post_meta( $post_id, 'slide_video_video_wait_for_end', $slide_video_video_wait_for_end );
	}

	/**
	 * Outputs the meta box for the Video slide format.
	 *
	 * @since	1.2.0
	 *
	 * @param	WP_Post	$post	The post of the current slide.
	 * @return	void
	 */
	static function slide_video_meta_box( $post ) {
		$slide_video_video_id = get_post_meta( $post->ID, 'slide_video_video_id', true );
		$slide_video_video_start = get_post_meta( $post->ID, 'slide_video_video_start', true );
		$slide_video_video_end = get_post_meta( $post->ID, 'slide_video_video_end', true );
		$slide_video_video_wait_for_end = get_post_meta( $post->ID, 'slide_video_video_wait_for_end', true );

		?><table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="slide_video_video_id"><?php _e('YouTube video ID', 'foyer'); ?></label>
					</th>
					<td>
						<input type="text" name="slide_video_video_id" id="slide_video_video_id"
							value="<?php echo $slide_video_video_id; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="slide_video_video_start"><?php _e('Start at (seconds)', 'foyer'); ?></label>
					</th>
					<td>
						<input type="text" name="slide_video_video_start" id="slide_video_video_start"
							value="<?php echo $slide_video_video_start; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="slide_video_video_end"><?php _e('Stop at (seconds)', 'foyer'); ?></label>
					</th>
					<td>
						<input type="text" name="slide_video_video_end" id="slide_video_video_end"
							value="<?php echo $slide_video_video_end; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="slide_video_video_wait_for_end"><?php _e('Wait for end?', 'foyer'); ?></label>
					</th>
					<td>
						<input type="checkbox" name="slide_video_video_wait_for_end" id="slide_video_video_wait_for_end"
							value="1" <?php checked( $slide_video_video_wait_for_end, 1 ); ?> />
					</td>
				</tr>
			</tbody>
		</table><?php
	}
}