<?php
/**
 * Front-end Actions
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
 * Hooks WPGS actions, when present in the $_GET superglobal. Every wpgs_aciton
 * present in $_GET is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @since 3.0
 * @return void
*/
function wpgs_get_actions() {
	if ( isset( $_GET['wpgs_action'] ) ) {
		do_action( 'wpgs_' . $_GET['wpgs_action'], $_GET );
	}
}
add_action( 'init', 'wpgs_get_actions' );

/**
 * Hooks WPGS actions, when present in the $_POST superglobal. Every wpgs_aciton
 * present in $_POST is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @since 3.0
 * @return void
*/
function wpgs_post_actions() {
	if ( isset( $_POST['wpgs_action'] ) ) {
		do_action( 'wpgs_' . $_POST['wpgs_action'], $_POST );
	}
}
add_action( 'init', 'wpgs_post_actions' );