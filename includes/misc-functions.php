<?php
/**
 * Misc Functions
 *
 * @package     WPGS
 * @subpackage  Functions
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WPGS_WPGRAPHICSTUDIO_URL', 'http://wpgraphicstudio.com' );

define( 'WPGS_WPGRAPHICSTUDIO_PLUGIN', 'wpGraphicStudio' );

/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @since 1.0
 * @return mixed
 */
function wpgs_get_option( $key = '', $default = false ) {
	global $wpgs_options;
	$value = ! empty( $wpgs_options[ $key ] ) ? $wpgs_options[ $key ] : $default;
	$value = apply_filters( 'wpgs_get_option', $value, $key, $default );
	return apply_filters( 'wpgs_get_option_' . $key, $value, $key, $default );
}

/**
 * Is Odd
 *
 * Checks wether an integer is odd.
 *
 * @since 3.0
 * @param int $int The integer to check
 * @return bool Is the integer odd?
 */
function wpgs_is_odd( $int ) {
	return (bool) ( $int & 1 );
}

/**
 * Get Graphic Extension
 *
 * Returns the extension of a graphic.
 *
 * @since 3.0
 * @param string $string Filename
 * @return string $parts File extension
 */
function wpgs_get_file_extension( $str ) {
   $parts = explode( '.', $str );
   return end( $parts );
}

/**
 * Checks if the string (filename) provided is an image URL
 *
 * @since 3.0
 * @param string $str Filename
 * @return bool Whether or not the filename is an image
 */
function wpgs_string_is_image_url( $str ) {
	$ext = wpgs_get_file_extension( $str );

	switch( strtolower( $ext ) ) {
		case 'jpg';
			$return = true;
			break;
		case 'png';
			$return = true;
			break;
		case 'gif';
			$return = true;
			break;
		default:
			$return = false;
		break;
	}

	return (bool) apply_filters( 'wpgs_string_is_image', $return, $str );
}

/**
 * Get User IP
 *
 * Returns the IP address of the current visitor
 *
 * @since 3.0
 * @return string $ip User's IP address
*/
function wpgs_get_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpgs_get_ip', $ip );
}


/**
 * Month Num To Name
 *
 * Takes a month number and returns the name three letter name of it.
 *
 * @since 3.0
 * @return string Short month name
 */
function wpgs_month_num_to_name( $n ) {
	$timestamp = mktime( 0, 0, 0, $n, 1, 2005 );

	return date_i18n( "M", $timestamp );
}

/**
 * Get PHP Arg Separator Ouput
 *
 * @since 3.0
 * @return string Arg separator output
*/
function wpgs_get_php_arg_separator_output() {
	return ini_get('arg_separator.output');
}

/**
 * Get the current page URL
 *
 * @since 3.0
 * @global $post
 * @return string $page_url Current page URL
 */
function wpgs_get_current_page_url() {
	global $post;

	if ( is_front_page() ) :
		$page_url = home_url();
	else :
		$page_url = 'http';

		if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" )
			$page_url .= "s";

		$page_url .= "://";

		if ( $_SERVER["SERVER_PORT"] != "80" )
			$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		else
			$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	endif;

	return apply_filters( 'wpgs_get_current_page_url', esc_url( $page_url ) );
}

/**
 * Checks whether function is disabled.
 *
 * @since 3.0
 *
 * @param string $function Name of the function.
 * @return bool Whether or not function is disabled.
 */
function wpgs_is_func_disabled( $function ) {
	$disabled = explode( ',',  ini_get( 'disable_functions' ) );

	return in_array( $function, $disabled );
}

/**
 * WPGS Let To Num
 *
 * Does Size Conversions
 *
 * @since 3.0
 * @usedby wpgs_settings()
 * @author John Seroka
 * @return $ret
 */
function wpgs_let_to_num( $v ) {
	$l   = substr( $v, -1 );
	$ret = substr( $v, 0, -1 );

	switch ( strtoupper( $l ) ) {
		case 'P': // fall-through
		case 'T': // fall-through
		case 'G': // fall-through
		case 'M': // fall-through
		case 'K': // fall-through
			$ret *= 1024;
			break;
		default:
			break;
	}

	return $ret;
}

/**
 * Retrieve the URL of the symlink directory
 *
 * @since 3.0
 * @return string $url URL of the symlink directory
 */
function wpgs_get_symlink_url() {
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/wpgs/symlinks' );
	$url = $wp_upload_dir['baseurl'] . '/wpgs/symlinks';

	return apply_filters( 'wpgs_get_symlink_url', $url );
}

/**
 * Retrieve the absolute path to the symlink directory
 *
 * @since  3.0
 * @return string $path Absolute path to the symlink directory
 */
function wpgs_get_symlink_dir() {
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/wpgs/symlinks' );
	$path = $wp_upload_dir['basedir'] . '/wpgs/symlinks';

	return apply_filters( 'wpgs_get_symlink_dir', $path );
}

/**
 * Delete symbolic links afer they have been used
 *
 * @access public
 * @since  3.0
 * @return void
 */
function wpgs_cleanup_file_symlinks() {
	$path = wpgs_get_symlink_dir();
	$dir = opendir( $path );

	while ( ( $file = readdir( $dir ) ) !== false ) {
		if ( $file == '.' || $file == '..' )
			continue;

		$transient = get_transient( md5( $file ) );
		if ( $transient === false )
			@unlink( $path . '/' . $file );
	}
}
add_action( 'wpgs_cleanup_file_symlinks', 'wpgs_cleanup_file_symlinks' );

/**
 * Retrieve timezone
 *
 * @since 3.0
 * @return string $timezone The timezone ID
 */
function wpgs_get_timezone_id() {

    // if site timezone string exists, return it
    if ( $timezone = get_option( 'timezone_string' ) )
        return $timezone;

    // get UTC offset, if it isn't set return UTC
    if ( ! ( $utc_offset = 3600 * get_option( 'gmt_offset', 0 ) ) )
        return 'UTC';

    // attempt to guess the timezone string from the UTC offset
    $timezone = timezone_name_from_abbr( '', $utc_offset );

    // last try, guess timezone string manually
    if ( $timezone === false ) {

        $is_dst = date('I');

        foreach ( timezone_abbreviations_list() as $abbr ) {
            foreach ( $abbr as $city ) {
                if ( $city['dst'] == $is_dst &&  $city['offset'] == $utc_offset )
                    return $city['timezone_id'];
            }
        }
    }

    // fallback
    return 'UTC';
}

function wpgs_wpgraphicstudio_settings_menu() {
	add_submenu_page( 'NULL', __( 'wpGraphicStudio', 'wpgs' ), __( 'wpGraphicStudio', 'wpgs' ), 'administrator', 'wpgs-core-settings', 'wpgs_wpgraphicstudio_settings_page' );
}
add_action('admin_menu', 'wpgs_wpgraphicstudio_settings_menu');

function wpgs_wpgraphicstudio_settings_page() {
	$logo_url 	= get_option( 'wpgs_wpgraphicstudio_logo_url' );
	$nav_hex 	= get_option( 'wpgs_wpgraphicstudio_nav_hex' );
	$per_gallery 	= get_option( 'wpgs_wpgraphicstudio_per_gallery' );
	$per_members 	= get_option( 'wpgs_wpgraphicstudio_per_members' );

	?>
<script language="JavaScript">
jQuery(document).ready(function() {
jQuery('#upload_logo_button').click(function() {
formfield = jQuery('#wpgs_wpgraphicstudio_logo_url').attr('name');
tb_show('', 'media-upload.php?type=image&TB_iframe=true');
return false;
});

window.send_to_editor = function(html) {
imgurl = jQuery('img',html).attr('src');
jQuery('#wpgs_wpgraphicstudio_logo_url').val(imgurl);
tb_remove();
}
});
(function( $ ) {

    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('#wpgs_wpgraphicstudio_nav_hex').wpColorPicker();
    });

})( jQuery );
</script>
<div class="wrap">
		<h2><?php _e('wpGraphicStudio Core Settings/Options'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('wpgs_wpgraphicstudio_settings'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Modules Logo'); ?>
						</th>
	<td><label for="upload_logo">Upload Logo<br><input id="wpgs_wpgraphicstudio_logo_url" name="wpgs_wpgraphicstudio_logo_url" type="text" class="regular-text" value="<?php echo $logo_url ?>" />
	<input id="upload_logo_button" type="button" value="Upload Logo" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_logo_url"><?php _e('Upload or enter the url to your logo displayed at the top of each module'); ?></label><br>
							<?php
							if ($logo_url != '') { ?>
							<img src="<?php echo get_option( 'wpgs_wpgraphicstudio_logo_url' ); ?>">
							<?php } ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Navigation Menu Color'); ?>
						</th>
						<td>
							<input id="wpgs_wpgraphicstudio_nav_hex" name="wpgs_wpgraphicstudio_nav_hex" type="text" class="regular-text" value="<?php echo $nav_hex ?>" />
							<label class="description" for="wpgs_wpgraphicstudio_nav_hex"><?php _e('Select navigation menu color'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Graphic Galleries'); ?>
						</th>
						<td>
							<input id="wpgs_wpgraphicstudio_per_gallery" name="wpgs_wpgraphicstudio_per_gallery" type="text" class="regular-text" style="width: 40px;" value="<?php echo $per_gallery ?>" />
							<label class="description" for="wpgs_wpgraphicstudio_per_gallery"><?php _e('Number of graphics (per page) in graphic galleries'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Member Areas'); ?>
						</th>
						<td>
							<input id="wpgs_wpgraphicstudio_per_members" name="wpgs_wpgraphicstudio_per_members" type="text" class="regular-text" style="width: 40px;" value="<?php echo $per_members ?>" />
							<label class="description" for="wpgs_wpgraphicstudio_per_members"><?php _e('Number of modules (per page) in "Create Graphic & "Member Galleries"'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
	<?php
}

function wpgs_wpgraphicstudio_register_logo() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_logo_url' );
}
function wpgs_wpgraphicstudio_register_nav() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_nav_hex' );
}
function wpgs_wpgraphicstudio_register_gallery() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_per_gallery' );
}
function wpgs_wpgraphicstudio_register_members() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_per_members' );
}

add_action('admin_init', 'wpgs_wpgraphicstudio_register_logo');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_nav');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_gallery');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_members');

function wpgs_sanitize_register_logo( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_logo_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_logo_url' );
	}
	return $new;
}

function wpgs_sanitize_register_nav( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_nav_hex' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_nav_hex' );
	}
	return $new;
}

function wpgs_sanitize_register_gallery( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_per_gallery' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_per_gallery' );
	}
	return $new;
}

function wpgs_sanitize_register_members( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_per_members' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_per_members' );
	}
	return $new;
}

function images_wpgraphicstudio_move()
{
$from = ''.plugin_dir_path( __FILE__ ).'wpgs/';
$wp_upload_dir = wp_upload_dir();
$to = $wp_upload_dir['basedir'] . '/wpgs/';

hpt_copyr($from, $to);
}
add_action('admin_init', 'images_wpgraphicstudio_move');

function hpt_copyr($source, $dest)
{
// Check for symlinks
if (is_link($source)) {
return symlink(readlink($source), $dest);
}

// Simple copy for a file
if (is_file($source)) {
return copy($source, $dest);
}

// Make destination directory
if (!is_dir($dest)) {
mkdir($dest);
}

// Loop through the folder
$dir = dir($source);
while (false !== $entry = $dir->read()) {
// Skip pointers
if ($entry == '.' || $entry == '..') {
continue;
}

// Deep copy directories
hpt_copyr("$source/$entry", "$dest/$entry");
}

// Clean up
$dir->close();
return true;
}

 function hpt_rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
show_admin_bar(false);
}
}
add_action('after_setup_theme', 'remove_admin_bar');

function restrict_admin_with_redirect() {
	if ( ! current_user_can( 'manage_options' ) && $_SERVER['PHP_SELF'] != '/wp-admin/' ) {
		wp_redirect( site_url() ); exit;
	}
}

add_action( 'admin_init', 'restrict_admin_with_redirect' );

function app_output_buffer() {
ob_start();
} // soi_output_buffer
add_action('init', 'app_output_buffer');

function recursiveRemove($user_id) {
    $structure = glob(rtrim($user_id, "/").'/*');
    if (is_array($structure)) {
        foreach($structure as $file) {
            if (is_dir($file)) recursiveRemove($file);
            elseif (is_file($file)) unlink($file);
        }
    }
    rmdir($user_id);
}
function fileRemove($user_id) {

$upload_dir = wp_upload_dir();
$mydir = ABSPATH . ''.$upload_dir.'/wpgs/'.$user_id.'/';

if (($user_id != '') && ($mydir != '')) {
recursiveRemove("$mydir");
}
	global $wpdb;

        $user_obj = get_userdata( $user_id );
        $email = $user_obj->user_email;

	$headers = 'From: ' . get_bloginfo( "name" ) . ' <' . get_bloginfo( "admin_email" ) . '>' . "\r\n";
 	 wp_mail( $email, 'Your account has been removed', 'Your account at ' . get_bloginfo("name") . ' has been removed due to dormancy or non usage for an extended period of time.', $headers );
}

add_action( 'delete_user', 'fileRemove' );