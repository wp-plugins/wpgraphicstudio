<?php
/**
 * Shortcodes
 *
 * @package     WPGS
 * @subpackage  Shortcodes
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create Graphic Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_create() {
		ob_start();

		wpgs_get_template_part( 'create', 'graphic' );
		return ob_get_clean();
}

add_shortcode( 'wpgs-create', 'wpgs_create' );

/**
 * Graphic Gallery Shortcode
 *
 * Retrieves and displays a specified user graphic gallery.
 *
 * @since 3.0
 */
function wpgs_gallery() {
		ob_start();

		wpgs_get_template_part( 'graphic', 'gallery' );
		return ob_get_clean();
}

add_shortcode( 'wpgs-gallery', 'wpgs_gallery' );

/**
 * Generator Shortcode
 *
 * Retrieves and displays selection options for sub generators.
 *
 * @since 3.0
 */
function wpgs_select() {
		ob_start();

		wpgs_get_template_part( 'select', 'graphic' );
		return ob_get_clean();
}

add_shortcode( 'wpgs-select', 'wpgs_select' );

/**
 * Create Graphic Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_process() {
		//ob_start();

		wpgs_get_template_part( 'process', 'graphic' );
		return;
		//ob_get_clean();
}

add_shortcode( 'wpgs-process', 'wpgs_process' );

/**
 * Upload Graphic Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */

function wpgs_upload() {
		ob_start();

		wpgs_get_template_part( 'upload', 'graphic' );
		return ob_get_clean();
}

add_shortcode( 'wpgs-upload', 'wpgs_upload' );

/**
 * Process Upload Graphic Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */

function wpgs_process_upload() {
		ob_start();

		wpgs_get_template_part( 'process', 'upload' );
		return ob_get_clean();
}

add_shortcode( 'wpgs-process-upload', 'wpgs_process_upload' );
