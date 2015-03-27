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
delete_option( 'wpgs_settings_general' );
delete_option( 'wpgs_settings_emails' );
delete_option( 'wpgs_settings_misc' );