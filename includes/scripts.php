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

function wpgs_logo_manager_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('jquery');
}

function wpgs_logo_manager_admin_styles() {
wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'wpgs_logo_manager_admin_scripts');
add_action('admin_print_styles', 'wpgs_logo_manager_admin_styles');

add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
function wptuts_add_color_picker( $hook ) {

    if( is_admin() ) {

        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );

        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'wpgs-nav-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}