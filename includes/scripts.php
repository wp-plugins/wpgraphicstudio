<?php
/**
 * Scripts
 *
 * @package     WPGS
 * @subpackage  Functions
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load Scripts
 *
 * Enqueues the required scripts.
 *
 * @since 3.0
 * @global $wpgs_options
 * @global $post
 * @return void
 */
function wpgs_load_scripts() {
	global $wpgs_options, $post;

	$js_dir = WPGS_PLUGIN_URL . 'assets/js/';

	wp_enqueue_script( 'jquery' );

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';


}
add_action( 'wp_enqueue_scripts', 'wpgs_load_scripts' );

/**
 * Load Admin Scripts
 *
 * Enqueues the required admin scripts.
 *
 * @since 1.0
 * @global $post
 * @global $pagenow
 * @param string $hook Page hook
 * @return void
 */
function wpgs_load_admin_scripts( $hook ) {
	global $post,
	$pagenow,
	$wp_version;

	$js_dir = WPGS_PLUGIN_URL . 'assets/js/';
	$css_dir = WPGS_PLUGIN_URL . 'assets/css/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	$wpgs_pages = array( $wpgs_settings_page, $wpgs_system_info_page, $wpgs_add_ons_page, $wpgs_upgrades_screen, 'index.php', );
	$wpgs_cpt   = apply_filters( 'wpgs_load_scripts_for_these_types', array( 'graphics', 'wpgs_payment', ) );

	if ( ! in_array( $hook, $wpgs_pages ) && ! is_object( $post ) )
		return;

	if ( is_object( $post ) && ! in_array( $post->post_type, $wpgs_cpt ) )
		return;

	if ( $hook == $wpgs_settings_page ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'colorbox', $css_dir . 'colorbox' . $suffix . '.css', array(), '1.3.20' );
		wp_enqueue_script( 'colorbox', $js_dir . 'jquery.colorbox-min.js', array( 'jquery' ), '1.3.20' );
		if( function_exists( 'wp_enqueue_media' ) && version_compare( $wp_version, '3.5', '>=' ) ) {
        	 //call for new media manager
         	wp_enqueue_media();
      }
	}
	wp_enqueue_style( 'jquery-chosen', $css_dir . 'chosen' . $suffix . '.css', array(), WPGS_VERSION );
	wp_enqueue_script( 'jquery-chosen', $js_dir . 'chosen.jquery.min.js', array( 'jquery' ), WPGS_VERSION );
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'wpgs-admin-scripts', $js_dir . 'admin-scripts' . $suffix . '.js', array( 'jquery' ), WPGS_VERSION, false );
	wp_localize_script( 'wpgs-admin-scripts', 'wpgs_vars', array(
		'post_id'            => isset( $post->ID ) ? $post->ID : null,
		'wpgs_version'        => WPGS_VERSION,
	));

	wp_enqueue_style( 'wpgs-admin', $css_dir . 'wpgs-admin' . $suffix . '.css', WPGS_VERSION );
}
add_action( 'admin_enqueue_scripts', 'wpgs_load_admin_scripts', 100 );

/**
 * Adds WPGS Version to the <head> tag
 *
 * @since 1.4.2
 * @return void
*/
function wpgs_version_in_header(){
	// Newline on both sides to avoid being in a blob
	echo '<meta name="generator" content="wpGraphicStudio v' . WPGS_VERSION . '" />' . "\n";
}
add_action( 'wp_head', 'wpgs_version_in_header' );