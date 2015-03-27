<?php
/**
 * Post Type Functions
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
 * Registers and sets up the WPGS custom post type
 *
 * @since 1.0
 * @return void
 */
function wpgs_setup_wpgs_post_types() {

	$archives = defined( 'WPGS_DISABLE_ARCHIVE' ) && WPGS_DISABLE_ARCHIVE ? false : true;
	$slug     = defined( 'WPGS_SLUG' ) ? WPGS_SLUG : 'graphics';
	$rewrite  = defined( 'WPGS_DISABLE_REWRITE' ) && WPGS_DISABLE_REWRITE ? false : array('slug' => $slug, 'with_front' => false);

	$graphic_labels =  apply_filters( 'wpgs_graphic_labels', array(
		'name' 				=> '%2$s',
		'singular_name' 	=> '%1$s',
		'add_new' 			=> __( 'Add New', 'wpgs' ),
		'add_new_item' 		=> __( 'Add New %1$s', 'wpgs' ),
		'edit_item' 		=> __( 'Edit %1$s', 'wpgs' ),
		'new_item' 			=> __( 'New %1$s', 'wpgs' ),
		'all_items' 		=> __( 'All %2$s', 'wpgs' ),
		'view_item' 		=> __( 'View %1$s', 'wpgs' ),
		'search_items' 		=> __( 'Search %2$s', 'wpgs' ),
		'not_found' 		=> __( 'No %2$s found', 'wpgs' ),
		'not_found_in_trash'=> __( 'No %2$s found in Trash', 'wpgs' ),
		'parent_item_colon' => '',
		'menu_name' 		=> __( '%2$s', 'wpgs' )
	) );

	foreach ( $graphic_labels as $key => $value ) {
	   $graphic_labels[ $key ] = sprintf( $value, wpgs_get_label_singular(), wpgs_get_label_plural() );
	}

	$graphic_args = array(
		'labels' 			=> $graphic_labels,
		'public' 			=> true,
		'publicly_queryable'=> false,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'query_var' 		=> false,
		'rewrite' 			=> $rewrite,
		'capability_type' 	=> '',
		'map_meta_cap'      => false,
		'has_archive' 		=> false,
		'hierarchical' 		=> true,
'supports' 			=> apply_filters( 'wpgs_graphic_supports', array( '' ) ),
	);
	register_post_type( 'graphic', apply_filters( 'wpgs_graphic_post_type_args', $graphic_args ) );

}



function create_flush_rules() {
$rules = get_option( 'rewrite_rules' );
if ( ! isset( $rules['(create-graphic)/(.+)$'] ) ) {
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
}
add_action( 'wp_loaded','create_flush_rules' );

function create_insert_rewrite_rules( $rules ) {
$newrules = array();
$newrules['(create-graphic)/(.+)$'] = 'index.php?pagename=$matches[1]&create=$matches[2]';
return $newrules + $rules;
}
add_filter( 'rewrite_rules_array','create_insert_rewrite_rules' );

function create_insert_query_vars( $vars ) {
array_push($vars, 'create');
return $vars;
}
add_filter( 'query_vars','create_insert_query_vars' );

function headlines_flush_rules() {
$rules = get_option( 'rewrite_rules' );
if ( ! isset( $rules['(headlines)/(.+)$'] ) ) {
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
}
add_action( 'wp_loaded','headlines_flush_rules' );

function headlines_insert_rewrite_rules( $rules ) {
$newrules = array();
$newrules['(headlines)/(.+)$'] = 'index.php?pagename=$matches[1]&headlines=$matches[2]';
return $newrules + $rules;
}
add_filter( 'rewrite_rules_array','headlines_insert_rewrite_rules' );

function headlines_insert_query_vars( $vars ) {
array_push($vars, 'headlines');
return $vars;
}
add_filter( 'query_vars','headlines_insert_query_vars' );

function buttons_flush_rules() {
$rules = get_option( 'rewrite_rules' );
if ( ! isset( $rules['(buttons)/(.+)$'] ) ) {
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
}
add_action( 'wp_loaded','buttons_flush_rules' );

function buttons_insert_rewrite_rules( $rules ) {
$newrules = array();
$newrules['(buttons)/(.+)$'] = 'index.php?pagename=$matches[1]&buttons=$matches[2]';
return $newrules + $rules;
}
add_filter( 'rewrite_rules_array','buttons_insert_rewrite_rules' );

function buttons_insert_query_vars( $vars ) {
array_push($vars, 'buttons');
return $vars;
}
add_filter( 'query_vars','buttons_insert_query_vars' );

function web_boxes_flush_rules() {
$rules = get_option( 'rewrite_rules' );
if ( ! isset( $rules['(web-boxes)/(.+)$'] ) ) {
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
}
add_action( 'wp_loaded','web_boxes_flush_rules' );

function web_boxes_insert_rewrite_rules( $rules ) {
$newrules = array();
$newrules['(web-boxes)/(.+)$'] = 'index.php?pagename=$matches[1]&web-boxes=$matches[2]';
return $newrules + $rules;
}
add_filter( 'rewrite_rules_array','web_boxes_insert_rewrite_rules' );

function web_boxes_insert_query_vars( $vars ) {
array_push($vars, 'web-boxes');
return $vars;
}
add_filter( 'query_vars','web_boxes_insert_query_vars' );

function belcher_boxes_flush_rules() {
$rules = get_option( 'rewrite_rules' );
if ( ! isset( $rules['(belcher-boxes)/(.+)$'] ) ) {
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
}
add_action( 'wp_loaded','belcher_boxes_flush_rules' );

function belcher_boxes_insert_rewrite_rules( $rules ) {
$newrules = array();
$newrules['(belcher-boxes)/(.+)$'] = 'index.php?pagename=$matches[1]&belcher-boxes=$matches[2]';
return $newrules + $rules;
}
add_filter( 'rewrite_rules_array','belcher_boxes_insert_rewrite_rules' );

function belcher_boxes_insert_query_vars( $vars ) {
array_push($vars, 'belcher-boxes');
return $vars;
}
add_filter( 'query_vars','belcher_boxes_insert_query_vars' );

function cta_boxes_flush_rules() {
$rules = get_option( 'rewrite_rules' );
if ( ! isset( $rules['(cta-boxes)/(.+)$'] ) ) {
global $wp_rewrite;
$wp_rewrite->flush_rules();
}
}
add_action( 'wp_loaded','cta_boxes_flush_rules' );

function cta_boxes_insert_rewrite_rules( $rules ) {
$newrules = array();
$newrules['(cta-boxes)/(.+)$'] = 'index.php?pagename=$matches[1]&cta-boxes=$matches[2]';
return $newrules + $rules;
}
add_filter( 'rewrite_rules_array','cta_boxes_insert_rewrite_rules' );

function cta_boxes_insert_query_vars( $vars ) {
array_push($vars, 'cta-boxes');
return $vars;
}
add_filter( 'query_vars','cta_boxes_insert_query_vars' );

add_action( 'init', 'wpgs_setup_wpgs_post_types', 1 );

/**
 * Get Default Labels
 *
 * @since 1.0.8.3
 * @return array $defaults Default labels
 */
function wpgs_get_default_labels() {
	$defaults = array(
	   'singular' => __( 'Graphic', 'wpgs' ),
	   'plural' => __( 'Graphic', 'wpgs')
	);
	return apply_filters( 'wpgs_default_graphics_name', $defaults );
}

/**
 * Get Singular Label
 *
 * @since 1.0.8.3
 * @return string $defaults['singular'] Singular label
 */
function wpgs_get_label_singular( $lowercase = false ) {
	$defaults = wpgs_get_default_labels();
	return ($lowercase) ? strtolower( $defaults['singular'] ) : $defaults['singular'];
}

/**
 * Get Plural Label
 *
 * @since 1.0.8.3
 * @return string $defaults['plural'] Plural label
 */
function wpgs_get_label_plural( $lowercase = false ) {
	$defaults = wpgs_get_default_labels();
	return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
}