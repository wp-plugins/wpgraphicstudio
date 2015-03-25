<?php
/**
 * Install Function
 *
 * @package     WPGS
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the graphic types,
 * flushing rewrite rules to initiate the new 'graphics' slug and also
 * creates the plugin and populates the settings fields for those plugin
 * pages. After successfull install, the user is redirected to the WPGS Welcome
 * screen.
 *
 * @since 3.0
 * @global $wpdb
 * @global $wpgs_options
 * @global $wp_version
 * @return void
 */

function wpgs_install() {
	global $wpdb, $wpgs_options, $wp_version;

	// Setup the Graphics Custom Post Type
	//wpgs_setup_wpgs_post_types();

	// Clear the permalinks
	flush_rewrite_rules();

	// Add Upgraded From Option
	$current_version = get_option( 'wpgs_version' );
	if ( $current_version )
		update_option( 'wpgs_version_upgraded_from', $current_version );

	// Checks if the create graphic page exists
	 function get_page_by_name($pagename)
	{
	$pages = get_pages();
	foreach ($pages as $page) if ($page->post_name == $pagename) return $page;
	return false;
}

	if ( ! wpgs_get_option( 'create_page', false ) ) {
	    // Create Graphic Page
		$create = wp_insert_post(
			array(
				'post_title'     => __( 'Create Graphic', 'wpgs' ),
				'post_content'   => '[wpgs-create]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
}
	if ( ! wpgs_get_option( 'gallery_page', false ) ) {
		// Graphic Gallery (Gallery) Page
		$gallery = wp_insert_post(
			array(
				'post_title'     => __( 'Graphic Gallery', 'wpgs' ),
				'post_content'   => '[wpgs-gallery]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
}

	if ( ! wpgs_get_option( 'belcher_boxes_page', false ) ) {
	    // Create Belcher Boxes Page
		$belcher_boxes = wp_insert_post(
			array(
				'post_title'     => __( 'Belcher Boxes', 'wpgs' ),
				'post_content'   => '[wpgs-belcher-boxes]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

}

	if ( ! wpgs_get_option( 'buttons_page', false ) ) {
	    // Create Buttons Page
		$buttons = wp_insert_post(
			array(
				'post_title'     => __( 'Buttons', 'wpgs' ),
				'post_content'   => '[wpgs-buttons]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

}

	if ( ! wpgs_get_option( 'cta_boxes_page', false ) ) {
	    // Create CTA Boxes Page
		$cta_boxes = wp_insert_post(
			array(
				'post_title'     => __( 'CTA Boxes', 'wpgs' ),
				'post_content'   => '[wpgs-cta-boxes]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

}

	if ( ! wpgs_get_option( 'web_boxes_page', false ) ) {
	    // Create Web Boxes Page
		$web_boxes = wp_insert_post(
			array(
				'post_title'     => __( 'Web Boxes', 'wpgs' ),
				'post_content'   => '[wpgs-web-boxes]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

}
	if ( ! wpgs_get_option( 'headlines_page', false ) ) {
	    // Create Headlines Page
		$headlines = wp_insert_post(
			array(
				'post_title'     => __( 'Headlines', 'wpgs' ),
				'post_content'   => '[wpgs-headlines]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

}

		// Store our page IDs
		$options = array(
			'create_page' => $create,
			'gallery_page'  => $gallery,
			'belcher_boxes_page' => $belcher_boxes,
			'buttons_page' => $buttons,
			'cta_boxes_page' => $cta_boxes,
			'web_boxes_page' => $web_boxes,
			'headlines_page' => $headlines

		);

	    update_option( 'wpgs_settings', array_merge( $wpgs_options, $options ) );
		update_option( 'wpgs_version', WPGS_VERSION );



add_action( 'admin_init', 'wpgs_change_graphic_dir', 999 );
add_action( 'admin_menu', 'wpgs_add_options_link', 10 );


add_action( 'init', 'wpgs_setup_wpgs_post_types', 1 );


	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
		return;

	// Add the transient to redirect
	set_transient( '_wpgs_activation_redirect', true, 30 );
}

register_activation_hook( WPGS_PLUGIN_FILE, 'wpgs_install' );