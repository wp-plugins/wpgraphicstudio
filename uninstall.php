<?php
/**
 * Uninstall wpGraphicStudio
 *
 * @package     WPGS
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2014, John Seroka - wpGraphicStudio.com
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       6.0
 */

// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Load WPGS file
include_once( 'wp-graphic-studio.php' );

global $wpdb, $wpgs_options;

/** Delete the Plugin Pages */
if ( isset( $wpgs_options['create_page'] ) )
	wp_delete_post( $wpgs_options['create_page'], true );
if ( isset( $wpgs_options['belcher_boxes_page'] ) )
	wp_delete_post( $wpgs_options['belcher_boxes_page'], true );
if ( isset( $wpgs_options['buttons'] ) )
	wp_delete_post( $wpgs_options['buttons'], true );
if ( isset( $wpgs_options['cta_boxes_page'] ) )
	wp_delete_post( $wpgs_options['cta_boxes_page'], true );
if ( isset( $wpgs_options['web_boxes_page'] ) )
	wp_delete_post( $wpgs_options['web_boxes_page'], true );
if ( isset( $wpgs_options['headlines_page'] ) )
	wp_delete_post( $wpgs_options['headlines_page'], true );

/** Delete all the Plugin Options */
delete_option( 'wpgs_wpgraphicstudio_logo_url' );
delete_option( 'wpgs_wpgraphicstudio_nav_hex' );
delete_option( 'wpgs_wpgraphicstudio_belcher_box_url' );

delete_option( 'wpgs_wpgraphicstudio_buttons_url' );
delete_option( 'wpgs_wpgraphicstudio_cta_boxes_url' );
delete_option( 'wpgs_wpgraphicstudio_web_boxes_url' );

delete_option( 'wpgs_wpgraphicstudio_headlines_url' );
delete_option( 'wpgs_wpgraphicstudio_per_gallery' );
delete_option( 'wpgs_wpgraphicstudio_per_members' );

delete_option( 'wpgs_wpgraphicstudio_delete_files' );
delete_option( 'wpgs_wpgraphicstudio_email_graphics' );
delete_option( 'wpgs_wpgraphicstudio_install' );
delete_option( 'wpgs_version_upgraded_from' );

if (get_option( 'wpgs_wpgraphicstudio_remove_settings' ) == 'On') {
$upload_dir = wp_upload_dir();
$removeDir = ABSPATH . ''.$upload_dir.'/wpgs/';
recursiveRemove("$removeDir");
}




