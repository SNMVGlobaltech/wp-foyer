<?php

/**
 * Defines the admin-specific functionality of the plugin.
 *
 * @since		1.0.0
 * @since		1.3.2	Refactored class from object to static methods.
 *						Switched from using a central Foyer_Loader class to registering hooks directly
 *						on init of Foyer, Foyer_Admin and Foyer_Public.
 *
 * @package		Foyer
 * @subpackage	Foyer/admin
 * @author		Menno Luitjes <menno@mennoluitjes.nl>
 */
class Foyer_Admin {

	/**
	 * Loads dependencies and registers hooks for the admin-facing side of the plugin.
	 *
	 * @since	1.3.2
	 */
	static function init() {
		self::load_dependencies();

		/* Foyer_Admin */
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );

		/* Foyer_Admin_Display */
		add_action( 'admin_enqueue_scripts', array( 'Foyer_Admin_Display', 'localize_scripts' ) );
		add_action( 'add_meta_boxes', array( 'Foyer_Admin_Display', 'add_channel_editor_meta_box' ) );
		add_action( 'add_meta_boxes', array( 'Foyer_Admin_Display', 'add_channel_scheduler_meta_box' ) );
		add_action( 'save_post', array( 'Foyer_Admin_Display', 'save_display' ) );
		add_filter( 'manage_'.Foyer_Display::post_type_name.'_posts_columns', array( 'Foyer_Admin_Display', 'add_channel_columns' ) );
		add_action( 'manage_'.Foyer_Display::post_type_name.'_posts_custom_column', array( 'Foyer_Admin_Display', 'do_channel_columns' ), 10, 2 );
		/* Foyer_Admin_Channel */
		add_action( 'admin_enqueue_scripts', array( 'Foyer_Admin_Channel', 'localize_scripts' ) );
		add_action( 'add_meta_boxes', array( 'Foyer_Admin_Channel', 'add_slides_editor_meta_box' ), 20 );
		add_action( 'add_meta_boxes', array( 'Foyer_Admin_Channel', 'add_slides_settings_meta_box' ), 40 );
		add_action( 'save_post', array( 'Foyer_Admin_Channel', 'save_channel' ) );
		add_action( 'wp_ajax_foyer_slides_editor_add_slide', array( 'Foyer_Admin_Channel', 'add_slide_over_ajax' ) );
		add_action( 'wp_ajax_foyer_slides_editor_remove_slide', array( 'Foyer_Admin_Channel', 'remove_slide_over_ajax' ) );
		add_action( 'wp_ajax_foyer_slides_editor_reorder_slides', array( 'Foyer_Admin_Channel', 'reorder_slides_over_ajax' ) );
		add_filter( 'get_sample_permalink_html', array( 'Foyer_Admin_Channel', 'remove_sample_permalink' ) );
		add_filter( 'manage_'.Foyer_Channel::post_type_name.'_posts_columns', array( 'Foyer_Admin_Channel', 'add_slides_count_column' ) );
		add_action( 'manage_'.Foyer_Channel::post_type_name.'_posts_custom_column', array( 'Foyer_Admin_Channel', 'do_slides_count_column' ), 10, 2 );

		/* Foyer_Admin_Slide */
		add_action( 'admin_enqueue_scripts', array( 'Foyer_Admin_Slide', 'localize_scripts' ) );
		add_action( 'add_meta_boxes', array( 'Foyer_Admin_Slide', 'add_slide_editor_meta_boxes' ) );
		add_action( 'save_post', array( 'Foyer_Admin_Slide', 'save_slide' ) );
		add_filter( 'get_sample_permalink_html', array( 'Foyer_Admin_Slide', 'remove_sample_permalink' ) );
		add_filter( 'manage_'.Foyer_Slide::post_type_name.'_posts_columns', array( 'Foyer_Admin_Slide', 'add_slide_format_column' ) );
		add_action( 'manage_'.Foyer_Slide::post_type_name.'_posts_custom_column', array( 'Foyer_Admin_Slide', 'do_slide_format_column' ), 10, 2 );

		/* Foyer_Admin_Preview */
		add_action( 'wp_enqueue_scripts', array( 'Foyer_Admin_Preview', 'enqueue_scripts' ) );
		add_filter( 'show_admin_bar', array( 'Foyer_Admin_Preview', 'hide_admin_bar' ) );
		add_action( 'wp_ajax_foyer_preview_save_orientation_choice', array( 'Foyer_Admin_Preview', 'save_orientation_choice' ) );
		add_action( 'wp_ajax_nopriv_foyer_preview_save_orientation_choice', array( 'Foyer_Admin_Preview', 'save_orientation_choice' ) );

		/* Foyer_Admin_Slide_Format_PDF */
		add_filter( 'wp_image_editors', array( 'Foyer_Admin_Slide_Format_PDF', 'add_foyer_imagick_image_editor' ) );
		add_action( 'delete_attachment', array( 'Foyer_Admin_Slide_Format_PDF', 'delete_pdf_images_for_attachment' ) );
		add_action( 'admin_notices', array( 'Foyer_Admin_Slide_Format_PDF', 'display_admin_notice' ) );
	}

	/**
	 * Adds the top-level Foyer admin menu item.
	 *
	 * @since	1.0.0
	 * @since	1.3.2	Changed method to static.
	 *					Added context for translations.
	 */
	static function admin_menu() {
		add_menu_page(
			_x( 'Foyer', 'admin menu', 'foyer' ),
			_x( 'Foyer', 'admin menu', 'foyer' ),
			'edit_posts',
			'foyer',
			array(),
			'dashicons-welcome-view-site',
			31
		);
	}

	/**
	 * Enqueues the JavaScript for the admin area.
	 *
	 * @since	1.0.0
	 * @since	1.2.5	Register scripts before they are enqueued.
	 *					Makes it possible to enqueue Foyer scripts outside of the Foyer plugin.
	 *					Changed handle of script to {plugin_name}-admin.
	 * @since	1.3.2	Changed method to static.
	 */
	static function enqueue_scripts() {

		wp_register_script( Foyer::get_plugin_name() . '-admin', plugin_dir_url( __FILE__ ) . 'js/foyer-admin-min.js', array( 'jquery', 'jquery-ui-sortable' ), Foyer::get_version(), false );
		wp_enqueue_script( Foyer::get_plugin_name() . '-admin' );
	}

	/**
	 * Enqueues the stylesheets for the admin area.
	 *
	 * @since	1.0.0
	 * @since	1.3.2	Changed method to static.
	 */
	static function enqueue_styles() {

		wp_enqueue_style( Foyer::get_plugin_name(), plugin_dir_url( __FILE__ ) . 'css/foyer-admin.css', array(), Foyer::get_version(), 'all' );
	}

	/**
	 * Loads the required dependencies for the admin-facing side of the plugin.
	 *
	 * @since	1.3.2
	 * @access	private
	 */
	private static function load_dependencies() {

		/**
		 * Admin area functionality for display, channel and slide.
		 */
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-display.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-channel.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-preview.php';

		/**
		 * Admin area functionality for specific slide backgrounds.
		 */
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide-background-image.php';

		/**
		 * Admin area functionality for specific slide formats.
		 */
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide-format-default.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide-format-iframe.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide-format-pdf.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide-format-production.php';
		require_once FOYER_PLUGIN_PATH . 'admin/class-foyer-admin-slide-format-video.php';
	}
}
