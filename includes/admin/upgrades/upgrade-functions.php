<?php
/**
 * Upgrade Functions
 *
 * @package     WPGS
 * @subpackage  Admin/Upgrades
 * @copyright   Copyright (c) 2014, John Seroks
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Upgrade Notices
 *
 * @since 3.0
 * @return void
*/
function wpgs_show_upgrade_notices() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'wpgs-upgrades' )
		return; // Don't show notices on the upgrades page

	$wpgs_version = get_option( 'wpgs_version' );

	if ( ! $wpgs_version ) {
		// 6.0 is the first version to use this option so we must add it
		$wpgs_version = '5.4';
	}

	$wpgs_version = preg_replace( '/[^0-9.].*/', '', $wpgs_version );

	if ( version_compare( $wpgs_version, '6.0.1', '<' ) && ! get_option( 'wpgs_remove_gallery_page' ) ) {
		printf(
			'<div class="updated"><p>' . esc_html__( 'The gallery page needs to be removed as it is no longer needed in 6.0.1 or above, click %shere%s to start the upgrade.', 'wpgs' ) . '</p></div>',
			'<a href="' . esc_url( admin_url( 'admin.php?page=wpgs-core-settings&tab=upgrades' ) ) . '">',
			'</a>'
		);
	}

	if ( version_compare( $wpgs_version, '6.4.4', '<' ) && ! get_option( 'wpgs_update_language_options' ) ) {
		printf(
			'<div class="updated"><p>' . esc_html__( 'The wpGraphicStudio Core module language file needs to be updated with new language variations, click %shere%s to start the upgrade.', 'wpgs' ) . '</p></div>',
			'<a href="' . esc_url( admin_url( 'admin.php?page=wpgs-language-upgrade' ) ) . '">',
			'</a>'
		);
	}

	if ( version_compare( $wpgs_version, '6.4.4', '<' ) && ! get_option( 'wpgs_update_help_options' ) ) {
		printf(
			'<div class="updated"><p>' . esc_html__( 'The wpGraphicStudio Core module help file needs to be updated with new language variations, click %shere%s to start the upgrade.', 'wpgs' ) . '</p></div>',
			'<a href="' . esc_url( admin_url( 'admin.php?page=wpgs-help-upgrade' ) ) . '">',
			'</a>'
		);
	}

		/*
		 *  NOTICE:
		 *
		 *  When adding new upgrade notices, please be sure to put the action into the upgrades array during install:
		 *  /includes/install.php @ Appox Line 156
		 *
		 */

		// End 'Stepped' upgrade process notices

	}

add_action( 'admin_notices', 'wpgs_show_upgrade_notices' );

/**
 * Triggers all upgrade functions
 *
 * This function is usually triggered via AJAX
 *
 * @since 3.0
 * @return void
*/
function wpgs_trigger_upgrades() {

	if( ! current_user_can( 'manage_graphic_settings' ) ) {
		wp_die( __( 'You do not have permission to do wpGraphicStudio upgrades', 'wpgs' ), __( 'Error', 'wpgs' ), array( 'response' => 403 ) );
	}

	$wpgs_version = get_option( 'wpgs_version' );

	if ( ! $wpgs_version ) {
		// 6.0 is the first version to use this option so we must add it
		$wpgs_version = '6.0';
		add_option( 'wpgs_version', $wpgs_version );
	}

	if ( version_compare( WPGS_VERSION, $wpgs_version, '>' ) ) {
		wpgs_v644_upgrades();
	}

	update_option( 'wpgs_version', WPGS_VERSION );

	if ( DOING_AJAX )
		die( 'complete' ); // Let AJAX know that the upgrade is complete
}
add_action( 'wp_ajax_wpgs_trigger_upgrades', 'wpgs_trigger_upgrades' );

/**
 * For use when doing 'stepped' upgrade routines, to see if we need to start somewhere in the middle
 * @since 3.0
 * @return mixed   When nothing to resume returns false, otherwise starts the upgrade where it left off
 */
function wpgs_maybe_resume_upgrade() {

	$doing_upgrade = get_option( 'wpgs_doing_upgrade', false );

	if ( empty( $doing_upgrade ) ) {
		return false;
	}

	return $doing_upgrade;

}

/**
 * Check if the upgrade routine has been run for a specific action
 *
 * @since  3.0
 * @param  string $upgrade_action The upgrade action to check completion for
 * @return bool                   If the action has been added to the copmleted actions array
 */
function wpgs_has_upgrade_completed( $upgrade_action = '' ) {

	if ( empty( $upgrade_action ) ) {
		return false;
	}

	$completed_upgrades = wpgs_get_completed_upgrades();

	return in_array( $upgrade_action, $completed_upgrades );

}

/**
 * Adds an upgrade action to the completed upgrades array
 *
 * @since  3.0
 * @param  string $upgrade_action The action to add to the copmleted upgrades array
 * @return bool                   If the function was successfully added
 */
function wpgs_set_upgrade_complete( $upgrade_action = '' ) {

	if ( empty( $upgrade_action ) ) {
		return false;
	}

	$completed_upgrades   = wpgs_get_completed_upgrades();
	$completed_upgrades[] = $upgrade_action;

	// Remove any blanks, and only show uniques
	$completed_upgrades = array_unique( array_values( $completed_upgrades ) );

	return update_option( 'wpgs_completed_upgrades', $completed_upgrades );
}

/**
 * Get's the array of completed upgrade actions
 *
 * @since  3.0
 * @return array The array of completed upgrades
 */
function wpgs_get_completed_upgrades() {

	$completed_upgrades = get_option( 'wpgs_completed_upgrades' );

	if ( false === $completed_upgrades ) {
		$completed_upgrades = array();
	}

	return $completed_upgrades;

}
