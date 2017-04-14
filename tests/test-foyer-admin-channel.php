<?php


class Test_Foyer_Admin_Channel extends Foyer_UnitTestCase {

	function get_meta_boxes_for_channel( $channel_id ) {
		$this->assume_role( 'author' );
		set_current_screen( Foyer_Channel::post_type_name );

		do_action( 'add_meta_boxes', Foyer_Channel::post_type_name );
		ob_start();
		do_meta_boxes( Foyer_Channel::post_type_name, 'normal', get_post( $channel_id ) );
		$meta_boxes = ob_get_clean();

		return $meta_boxes;
	}

	function test_does_channel_have_slides() {

		$channel = new Foyer_Channel( $this->channel1 );
		$slides = $channel->get_slides();

		$this->assertCount( 2, $slides );
	}

	function test_slides_editor_is_displayed_on_channel_admin_page() {

		$meta_boxes = $this->get_meta_boxes_for_channel( $this->channel1 );

		$this->assertContains( '<div class="foyer_meta_box foyer_slides_editor"', $meta_boxes );
	}

	function test_add_slide_html_is_displayed_on_channel_admin_page() {

		$foyer_admin_channel = new Foyer_Admin_Channel( 1, 1 ); //@todo
		$add_slide_html = $foyer_admin_channel->get_add_slide_html();

		$meta_boxes = $this->get_meta_boxes_for_channel( $this->channel1 );

		$this->assertContains( $add_slide_html, $meta_boxes );
	}

	function test_slides_list_html_is_displayed_on_channel_admin_page() {

		$foyer_admin_channel = new Foyer_Admin_Channel( 1, 1 ); //@todo
		$slides_list_html = $foyer_admin_channel->get_slides_list_html( get_post( $this->channel1 ) );

		$meta_boxes = $this->get_meta_boxes_for_channel( $this->channel1 );

		$this->assertContains( $slides_list_html, $meta_boxes );
	}

	function test_slide_is_added_on_channel_admin_page() {
	}
}

/**
 * Test case for the Ajax callbacks.
 *
 * @group ajax
 */
class Test_Foyer_Admin_Channel_Ajax extends WP_Ajax_UnitTestCase {

	function test_event_is_removed_with_ajax_on_production_page() {
	}

}