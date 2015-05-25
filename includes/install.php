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
if ( isset( $wpgs_options['gallery_page'] ) )
	wp_delete_post( $wpgs_options['gallery_page'], true );

	if ( ! get_option( 'create_page', false ) ) {
	    // Create Graphic Page
		$create = wp_insert_post(
			array(
				'post_title'     => __( 'Members Area', 'wpgs' ),
				'post_content'   => '[wpgs-create]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

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


		    // Store our page IDs
			$options['create_page'] = $create;
			$options['belcher_boxes_page'] = $belcher_boxes;
			$options['buttons_page'] = $buttons;
			$options['cta_boxes_page'] = $cta_boxes;
			$options['web_boxes_page'] = $web_boxes;
			$options['headlines_page'] = $headlines;

}
	    update_option( 'create_page', create_page );
		update_option( 'wpgs_version', WPGS_VERSION );

add_action( 'admin_init', 'wpgs_change_graphic_dir', 999 );
add_action( 'admin_menu', 'wpgs_add_options_link', 10 );
update_option( 'wpgs_wpgraphicstudio_install', 1 );

add_action( 'init', 'wpgs_setup_wpgs_post_types', 1 );


	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
		return;

	if ( ! $current_version ) {
		require_once WPGS_PLUGIN_DIR . 'includes/admin/upgrades/upgrade-functions.php';

		// When new upgrade routines are added, mark them as complete on fresh install
		$upgrade_routines = array(
			'upgrade_remove_gallery_page'
		);

		foreach ( $upgrade_routines as $upgrade ) {
			wpgs_set_upgrade_complete( $upgrade );
		}
	}

	// Add the transient to redirect
	set_transient( '_wpgs_activation_redirect', true, 30 );
}