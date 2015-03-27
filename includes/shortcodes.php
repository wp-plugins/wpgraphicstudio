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
/**
 * Headlines Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_headlines() {
		ob_start();

		wpgs_get_template_part( 'headlines' );
		return ob_get_clean();
}
add_shortcode( 'wpgs-headlines', 'wpgs_headlines' );

/**
 * Buttons Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_buttons() {
		ob_start();

		wpgs_get_template_part( 'buttons' );
		return ob_get_clean();
}
add_shortcode( 'wpgs-buttons', 'wpgs_buttons' );

/**
 * Web Boxes Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_web_boxes() {
		ob_start();

		wpgs_get_template_part( 'web', 'boxes' );
		return ob_get_clean();
}
add_shortcode( 'wpgs-web-boxes', 'wpgs_web_boxes' );

/**
 * CTA Boxes Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_cta_boxes() {
		ob_start();

		wpgs_get_template_part( 'cta', 'boxes' );
		return ob_get_clean();
}
add_shortcode( 'wpgs-cta-boxes', 'wpgs_cta_boxes' );

/**
 * Belcher Boxes Shortcode
 *
 * Retrieves and displays a specified graphic generator.
 *
 * @since 3.0
 */
function wpgs_belcher_boxes() {
		ob_start();

		wpgs_get_template_part( 'belcher', 'boxes' );
		return ob_get_clean();
}
add_shortcode( 'wpgs-belcher-boxes', 'wpgs_belcher_boxes' );