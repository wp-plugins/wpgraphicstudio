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

function wpgs_admin_tabs( $current = 'main' ) {
    $tabs = array( 'main' => 'Main Settings', 'customize' => 'Customize', 'help' => 'Help' );
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=wpgs-core-settings&tab=$tab'>$name</a>";

    }
    echo '</h2>';
}

function wpgs_wpgraphicstudio_settings_menu() {
	add_submenu_page( 'NULL', __( 'wpGraphicStudio', 'wpgs' ), __( 'wpGraphicStudio', 'wpgs' ), 'administrator', 'wpgs-core-settings', 'wpgs_wpgraphicstudio_settings_page' );
}
add_action('admin_menu', 'wpgs_wpgraphicstudio_settings_menu');

function wpgs_wpgraphicstudio_settings_page() {
   global $pagenow;

//generic HTML and code goes here

if ( isset ( $_GET['tab'] ) ) wpgs_admin_tabs($_GET['tab']); else wpgs_admin_tabs('main');

if ( $pagenow == 'admin.php' && $_GET['page'] == 'wpgs-core-settings' ){

   if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
   else $tab = 'main';

   switch ( $tab ){
         case 'customize' :
if ($_GET['settings-updated'] == 'true') { ?>
    <div class="updated">
        <p><?php _e( 'Customize Options Updated!', 'wpgs' ); ?></p>
    </div>";

<?php
	$logo_url 	= get_option( 'wpgs_wpgraphicstudio_logo_url' );
	$nav_hex 	= get_option( 'wpgs_wpgraphicstudio_nav_hex' );
	$belcher_box_url 	= get_option( 'wpgs_wpgraphicstudio_belcher_box_url' );
	$buttons_url 	= get_option( 'wpgs_wpgraphicstudio_buttons_url' );
	$cta_boxes_url 	= get_option( 'wpgs_wpgraphicstudio_cta_boxes_url' );
	$web_boxes_url 	= get_option( 'wpgs_wpgraphicstudio_web_boxes_url' );
	$headlines_url 	= get_option( 'wpgs_wpgraphicstudio_headlines_url' );
$upload_dir = wp_upload_dir();
$upload_path = $upload_dir['basedir'];
$tomydir = "$upload_path/wpgs/images/create/";

$BelcherBoxurl = $belcher_box_url;
$BelcherBoxurlparts = parse_url("$BelcherBoxurl");
$BelcherBoxextracted = $BelcherBoxurlparts['path'];
$BelcherBoxextracted_trim = trim( $BelcherBoxextracted, "/" );
$BelcherBoxfrommydir_trim = ABSPATH . ''.$BelcherBoxextracted_trim.'';
$frommydir = ABSPATH . '';

$BelcherBoxpath = pathinfo(''.$BelcherBoxfrommydir_trim.'');

if ($BelcherBoxurl != '') {
rename(''.$BelcherBoxfrommydir_trim.'', ''.$BelcherBoxpath['dirname'].'/belcher-boxes.png');
copy(''.$BelcherBoxpath['dirname'].'/belcher-boxes.png', ''.$tomydir.'belcher-boxes.png');
}

$Buttonsurl = $buttons_url;
$Buttonsurlparts = parse_url("$Buttonsurl");
$Buttonsextracted = $Buttonsurlparts['path'];
$Buttonsextracted_trim = trim( $Buttonsextracted, "/" );
$Buttonsfrommydir_trim = ABSPATH . ''.$Buttonsextracted_trim.'';

$Buttonspath = pathinfo(''.$Buttonsfrommydir_trim.'');

if ($Buttonsurl != '') {
rename(''.$Buttonsfrommydir_trim.'', ''.$Buttonspath['dirname'].'/buttons.png');
copy(''.$Buttonspath['dirname'].'/buttons.png', ''.$tomydir.'buttons.png');
}

$ctaBoxesurl = $cta_boxes_url;
$ctaBoxesurlparts = parse_url("$ctaBoxesurl");
$ctaBoxesextracted = $ctaBoxesurlparts['path'];
$ctaBoxesextracted_trim = trim( $ctaBoxesextracted, "/" );
$ctaBoxesfrommydir_trim = ABSPATH . ''.$ctaBoxesextracted_trim.'';

$ctaBoxespath = pathinfo(''.$ctaBoxesfrommydir_trim.'');

if ($ctaBoxesurl != '') {
rename(''.$ctaBoxesfrommydir_trim.'', ''.$ctaBoxespath['dirname'].'/cta-boxes.png');
copy(''.$ctaBoxespath['dirname'].'/cta-boxes.png', ''.$tomydir.'cta-boxes.png');
}

$Headlinesurl = $headlines_url;
$Headlinesurlparts = parse_url("$Headlinesurl");
$Headlinesextracted = $Headlinesurlparts['path'];
$Headlinesextracted_trim = trim( $Headlinesextracted, "/" );
$Headlinesfrommydir_trim = ABSPATH . ''.$Headlinesextracted_trim.'';

$Headlinespath = pathinfo(''.$Headlinesfrommydir_trim.'');

if ($Headlinesurl != '') {
rename(''.$Headlinesfrommydir_trim.'', ''.$Headlinespath['dirname'].'/headlines.png');
copy(''.$Headlinespath['dirname'].'/headlines.png', ''.$tomydir.'headlines.png');
}

$WebBoxesurl = $web_boxes_url;
$WebBoxesurlparts = parse_url("$WebBoxesurl");
$WebBoxesextracted = $WebBoxesurlparts['path'];
$WebBoxesextracted_trim = trim( $WebBoxesextracted, "/" );
$WebBoxesfrommydir_trim = ABSPATH . ''.$WebBoxesextracted_trim.'';

$WebBoxespath = pathinfo(''.$WebBoxesfrommydir_trim.'');

if ($WebBoxesurl != '') {
rename(''.$WebBoxesfrommydir_trim.'', ''.$WebBoxespath['dirname'].'/web-boxes.png');
copy(''.$WebBoxespath['dirname'].'/web-boxes.png', ''.$tomydir.'web-boxes.png');
}}
//unlink(''.$path['dirname'].'/belcher-boxes.png');
 ?>
<script language="JavaScript">
(function( $ ) {

    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('#wpgs_wpgraphicstudio_nav_hex').wpColorPicker();
    });

})( jQuery );
</script>

<script type="text/javascript">
    //tb_show('', 'media-upload.php?TB_iframe=true');
    var upload_image_button=false;
    jQuery(document).ready(function() {

    jQuery('.upload_image_button').click(function() {
        upload_image_button =true;
        formfieldID=jQuery(this).prev().attr("id");
     formfield = jQuery("#"+formfieldID).attr('name');
     tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        if(upload_image_button==true){

                var oldFunc = window.send_to_editor;
                window.send_to_editor = function(html) {

                imgurl = jQuery('img', html).attr('src');
                jQuery("#"+formfieldID).val(imgurl);
                 tb_remove();
                window.send_to_editor = oldFunc;
                }
        }
        upload_image_button=false;
    });


    })

</script>
<div class="wrap">
		<h2><?php _e('wpGraphicStudio Customize Options'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('wpgs_wpgraphicstudio_settings');
	$logo_url 	= get_option( 'wpgs_wpgraphicstudio_logo_url' );
	$nav_hex 	= get_option( 'wpgs_wpgraphicstudio_nav_hex' );
	$belcher_box_url 	= get_option( 'wpgs_wpgraphicstudio_belcher_box_url' );
	$buttons_url 	= get_option( 'wpgs_wpgraphicstudio_buttons_url' );
	$cta_boxes_url 	= get_option( 'wpgs_wpgraphicstudio_cta_boxes_url' );
	$web_boxes_url 	= get_option( 'wpgs_wpgraphicstudio_web_boxes_url' );
	$headlines_url 	= get_option( 'wpgs_wpgraphicstudio_headlines_url' );
			?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Modules Logo'); ?>
						</th>
	<td><label for="upload_logo">Upload Logo<br><input id="wpgs_wpgraphicstudio_logo_url" name="wpgs_wpgraphicstudio_logo_url" type="text" class="regular-text" value="<?php echo $logo_url ?>" />
	<input class="upload_image_button" type="button" value="Upload Logo" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_logo_url"><?php _e('Upload or enter the url to your logo displayed at the top of each module'); ?></label><br>
							<?php
							if ($logo_url != '') { ?>
							<img src="<?php echo get_option( 'wpgs_wpgraphicstudio_logo_url' ); ?>">
							<?php } ?>
						</td>
					</tr>
					<tr valign="top">
					<td colspan="2"><hr></td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Belcher Box Members Area/Gallery Graphic'); ?>
						</th>
	<td><label for="upload_belcher_box">Upload Belcher Box Graphic<br><input id="wpgs_wpgraphicstudio_belcher_box_url" name="wpgs_wpgraphicstudio_belcher_box_url" type="text" class="regular-text" value="<?php echo $belcher_box_url ?>" />
    <input class="upload_image_button" type="button" value="Upload Belcher Box Graphic" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_belcher_box_url"><?php _e('Upload or enter the url to your Belcher Box Graphic displayed in the Members Area and Graphic Gallery sections'); ?></label><br>
							<img src="<?php echo content_url( '/uploads/wpgs/images/create/belcher-boxes.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
					<td colspan="2"><hr></td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Buttons Members Area/Gallery Graphic'); ?>
						</th>
	<td><label for="upload_buttons">Upload Buttons Graphic<br><input id="wpgs_wpgraphicstudio_buttons_url" name="wpgs_wpgraphicstudio_buttons_url" type="text" class="regular-text" value="<?php echo $buttons_url ?>" />
    <input class="upload_image_button" type="button" value="Upload Buttons Graphic" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_buttons_url"><?php _e('Upload or enter the url to your Buttons Graphic displayed in the Members Area and Graphic Gallery sections'); ?></label><br>
							<img src="<?php echo content_url( '/uploads/wpgs/images/create/buttons.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
					<td colspan="2"><hr></td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('CTA Boxes Members Area/Gallery Graphic'); ?>
						</th>
	<td><label for="upload_cta_boxes">Upload CTA Boxes Graphic<br><input id="wpgs_wpgraphicstudio_cta_boxes_url" name="wpgs_wpgraphicstudio_cta_boxes_url" type="text" class="regular-text" value="<?php echo $cta_boxes_url ?>" />
    <input class="upload_image_button" type="button" value="Upload CTA Boxes Graphic" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_cta_boxes_url"><?php _e('Upload or enter the url to your CTA Boxes Graphic displayed in the Members Area and Graphic Gallery sections'); ?></label><br>
							<img src="<?php echo content_url( '/uploads/wpgs/images/create/cta-boxes.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
					<td colspan="2"><hr></td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Headlines Members Area/Gallery Graphic'); ?>
						</th>
	<td><label for="upload_headlines">Upload Headlines Graphic<br><input id="wpgs_wpgraphicstudio_headlines_url" name="wpgs_wpgraphicstudio_headlines_url" type="text" class="regular-text" value="<?php echo $headlines_url ?>" />
    <input class="upload_image_button" type="button" value="Upload Headlines Graphic" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_headlines_url"><?php _e('Upload or enter the url to your Headlines Graphic displayed in the Members Area and Graphic Gallery sections'); ?></label><br>
							<img src="<?php echo content_url( '/uploads/wpgs/images/create/headlines.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
					<td colspan="2"><hr></td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Web Boxes Members Area/Gallery Graphic'); ?>
						</th>
	<td><label for="upload_web_boxes">Upload Web Boxes Graphic<br><input id="wpgs_wpgraphicstudio_web_boxes_url" name="wpgs_wpgraphicstudio_web_boxes_url" type="text" class="regular-text" value="<?php echo $web_boxes_url ?>" />
    <input class="upload_image_button" type="button" value="Upload Web Boxes Graphic" /><br>
							<label class="description" for="wpgs_wpgraphicstudio_web_boxes_url"><?php _e('Upload or enter the url to your Web Boxes Graphic displayed in the Members Area and Graphic Gallery sections'); ?></label><br>
							<img src="<?php echo content_url( '/uploads/wpgs/images/create/web-boxes.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
					<td colspan="2"><hr></td>
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
				</tbody>
			</table>
      <?php submit_button(); ?>
</form>
<?php
   break;
      case 'main' :
if ($_GET['settings-updated'] == 'true') { ?>
    <div class="updated">
        <p><?php _e( 'Main Settings/Options Updated!', 'wpgs' ); ?></p>
    </div>
<?php }
if ((get_option( 'wpgs_wpgraphicstudio_per_gallery' ) == '') || (get_option( 'wpgs_wpgraphicstudio_per_gallery' ) == '0')) {
$per_gallery = 10;
} else {
$per_gallery = get_option( 'wpgs_wpgraphicstudio_per_gallery' );
}
if ((get_option( 'wpgs_wpgraphicstudio_per_members' ) == '') || (get_option( 'wpgs_wpgraphicstudio_per_members' ) == '0')) {
$per_members = 10;
} else {
$per_members = get_option( 'wpgs_wpgraphicstudio_per_members' );
}

	$delete_files 	= get_option( 'wpgs_wpgraphicstudio_delete_files' );
	$email_graphics 	= get_option( 'wpgs_wpgraphicstudio_email_graphics' );

	?>
<div class="wrap">
		<h2><?php _e('wpGraphicStudio Main Settings/Options'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('wpgs_wpgraphicstudio_settings'); ?>

			<table class="form-table">
				<tbody>
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
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Auto Delete Files'); ?>
						</th>
						<td>
<?php
	$items = array("On", "Off");
	echo "<select id='wpgs_wpgraphicstudio_delete_files' name='wpgs_wpgraphicstudio_delete_files'>";
	foreach($items as $item) {
		$selected = ($delete_files==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
?>
							<label class="description" for="wpgs_wpgraphicstudio_delete_files"><?php _e('Automatically delete user files on account delete'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Email Graphics'); ?>
						</th>
						<td>
<?php
	$email_items = array("On", "Off");
	echo "<select id='wpgs_wpgraphicstudio_email_graphics' name='wpgs_wpgraphicstudio_email_graphics'>";
	foreach($email_items as $email_item) {
		$email_selected = ($email_graphics==$email_item) ? 'selected="selected"' : '';
		echo "<option value='$email_item' $email_selected>$email_item</option>";
	}
	echo "</select>";
?>
							<label class="description" for="wpgs_wpgraphicstudio_email_graphics"><?php _e('Allow users to email graphics to their email account'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Remove Plugin Files/Settings'); ?>
						</th>
						<td>
<?php
	$delete_settings = array("Off", "On");
	echo "<select id='wpgs_wpgraphicstudio_remove_settings' name='wpgs_wpgraphicstudio_remove_settings'>";
	foreach($delete_settings as $delete_setting) {
		$delete_selected = ($remove_settings==$delete_setting) ? 'selected="selected"' : '';
		echo "<option value='$delete_setting' $delete_selected>$delete_setting</option>";
	}
	echo "</select>";
?>
							<label class="description" for="wpgs_wpgraphicstudio_remove_settings"><?php _e('Remove all plugin settings and files (including user created graphic files) on plugin delete'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Belcher Boxes Module Page'); ?>
						</th>
						<td>
<select id="wpgs_wpgraphicstudio_belcher_boxes_page" name="wpgs_wpgraphicstudio_belcher_boxes_page">
 <option value="">Belcher Boxes</option>
 <?php
  $bbpages = get_pages();
  foreach ( $bbpages as $bbpage ) {
  	$bboption = '<option value="' . get_page_link( $bbpage->ID ) . '">';
	$bboption .= $bbpage->post_title;
	$bboption .= '</option>';
	echo $bboption;
  }
 ?>
</select>
							<label class="description" for="wpgs_wpgraphicstudio_belcher_boxes_page"><?php _e('If you have changed the Belcher Boxes Module page title or perma link, select the new page here to update wpGraphicStudio to this change.'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Buttons Module Page'); ?>
						</th>
						<td>
<select id="wpgs_wpgraphicstudio_buttons_page" name="wpgs_wpgraphicstudio_buttons_page">
 <option value="">Buttons</option>
 <?php
  $bpages = get_pages();
  foreach ( $bpages as $bpage ) {
  	$boption = '<option value="' . get_page_link( $bpage->ID ) . '">';
	$boption .= $bpage->post_title;
	$boption .= '</option>';
	echo $boption;
  }
 ?>
</select>
							<label class="description" for="wpgs_wpgraphicstudio_buttons_page"><?php _e('If you have changed the Buttons Module page title or perma link, select the new page here to update wpGraphicStudio to this change.'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('CTA Boxes Module Page'); ?>
						</th>
						<td>
<select id="wpgs_wpgraphicstudio_cta_boxes_page" name="wpgs_wpgraphicstudio_cta_boxes_page">
 <option value="">CTA Boxes</option>
 <?php
  $ctapages = get_pages();
  foreach ( $ctapages as $ctapage ) {
  	$ctaoption = '<option value="' . get_page_link( $ctapage->ID ) . '">';
	$ctaoption .= $ctapage->post_title;
	$ctaoption .= '</option>';
	echo $ctaoption;
  }
 ?>
</select>
							<label class="description" for="wpgs_wpgraphicstudio_cta_boxes_page"><?php _e('If you have changed the CTA Boxes Module page title or perma link, select the new page here to update wpGraphicStudio to this change.'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Headlines Module Page'); ?>
						</th>
						<td>
<select id="wpgs_wpgraphicstudio_headlines_page" name="wpgs_wpgraphicstudio_headlines_page">
 <option value="">Headlines</option>
 <?php
  $hpages = get_pages();
  foreach ( $hpages as $hpage ) {
  	$hoption = '<option value="' . get_page_link( $hpage->ID ) . '">';
	$hoption .= $hpage->post_title;
	$hoption .= '</option>';
	echo $hoption;
  }
 ?>
</select>
							<label class="description" for="wpgs_wpgraphicstudio_headlines_page"><?php _e('If you have changed the Headlines Module page title or perma link, select the new page here to update wpGraphicStudio to this change.'); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Web Boxes Module Page'); ?>
						</th>
						<td>
<select id="wpgs_wpgraphicstudio_web_boxes_page" name="wpgs_wpgraphicstudio_web_boxes_page">
 <option value="">Web Boxes</option>
 <?php
  $wbpages = get_pages();
  foreach ( $wbpages as $wbpage ) {
  	$wboption = '<option value="' . get_page_link( $wbpage->ID ) . '">';
	$wboption .= $wbpage->post_title;
	$wboption .= '</option>';
	echo $wboption;
  }
 ?>
</select>
							<label class="description" for="wpgs_wpgraphicstudio_web_boxes_page"><?php _e('If you have changed the Web Boxes page title or perma link, select the new page here to update wpGraphicStudio to this change.'); ?></label>
						</td>
					</tr>
				</tbody>
			</table>
      <?php submit_button(); ?>
</form>
      <?php
break;
      case 'help' :
if (isset($_POST['BelcherBoxID1Value'])) {
$sanBelcherBoxID1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxID1Value']));
$sanBelcherBoxTitle1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxTitle1Value']));
$sanBelcherBoxID2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxID2Value']));
$sanBelcherBoxTitle2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxTitle2Value']));
$sanBelcherBoxID3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxID3Value']));
$sanBelcherBoxTitle3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxTitle3Value']));
$sanBelcherBoxID4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxID4Value']));
$sanBelcherBoxTitle4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['BelcherBoxTitle4Value']));

$sanButtonsID1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsID1Value']));
$sanButtonsTitle1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsTitle1Value']));
$sanButtonsID2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsID2Value']));
$sanButtonsTitle2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsTitle2Value']));
$sanButtonsID3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsID3Value']));
$sanButtonsTitle3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsTitle3Value']));
$sanButtonsID4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsID4Value']));
$sanButtonsTitle4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ButtonsTitle4Value']));

$sanctaBoxesID1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesID1Value']));
$sanctaBoxesTitle1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesTitle1Value']));
$sanctaBoxesID2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesID2Value']));
$sanctaBoxesTitle2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesTitle2Value']));
$sanctaBoxesID3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesID3Value']));
$sanctaBoxesTitle3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesTitle3Value']));
$sanctaBoxesID4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesID4Value']));
$sanctaBoxesTitle4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ctaBoxesTitle4Value']));

$sanHeadlinesID1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesID1Value']));
$sanHeadlinesTitle1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesTitle1Value']));
$sanHeadlinesID2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesID2Value']));
$sanHeadlinesTitle2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesTitle2Value']));
$sanHeadlinesID3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesID3Value']));
$sanHeadlinesTitle3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesTitle3Value']));
$sanHeadlinesID4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesID4Value']));
$sanHeadlinesTitle4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['HeadlinesTitle4Value']));

$sanWebBoxesID1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesID1Value']));
$sanWebBoxesTitle1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesTitle1Value']));
$sanWebBoxesID2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesID2Value']));
$sanWebBoxesTitle2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesTitle2Value']));
$sanWebBoxesID3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesID3Value']));
$sanWebBoxesTitle3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesTitle3Value']));
$sanWebBoxesID4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesID4Value']));
$sanWebBoxesTitle4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['WebBoxesTitle4Value']));

$phpcontent = '<?php
$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<langs>
<langu>
<BelcherBoxvID1>'.$sanBelcherBoxID1Value.'</BelcherBoxvID1>
<BelcherBoxvTitle1>'.$sanBelcherBoxTitle1Value.'</BelcherBoxvTitle1>
<BelcherBoxvID2>'.$sanBelcherBoxID2Value.'</BelcherBoxvID2>
<BelcherBoxvTitle2>'.$sanBelcherBoxTitle2Value.'</BelcherBoxvTitle2>
<BelcherBoxvID3>'.$sanBelcherBoxID3Value.'</BelcherBoxvID3>
<BelcherBoxvTitle3>'.$sanBelcherBoxTitle3Value.'</BelcherBoxvTitle3>
<BelcherBoxvID4>'.$sanBelcherBoxID4Value.'</BelcherBoxvID4>
<BelcherBoxvTitle4>'.$sanBelcherBoxTitle4Value.'</BelcherBoxvTitle4>

<ButtonsvID1>'.$sanButtonsID1Value.'</ButtonsvID1>
<ButtonsvTitle1>'.$sanButtonsTitle1Value.'</ButtonsvTitle1>
<ButtonsvID2>'.$sanButtonsID2Value.'</ButtonsvID2>
<ButtonsvTitle2>'.$sanButtonsTitle2Value.'</ButtonsvTitle2>
<ButtonsvID3>'.$sanButtonsID3Value.'</ButtonsvID3>
<ButtonsvTitle3>'.$sanButtonsTitle3Value.'</ButtonsvTitle3>
<ButtonsvID4>'.$sanButtonsID4Value.'</ButtonsvID4>
<ButtonsvTitle4>'.$sanButtonsTitle4Value.'</ButtonsvTitle4>

<ctaBoxesvID1>'.$sanctaBoxesID1Value.'</ctaBoxesvID1>
<ctaBoxesvTitle1>'.$sanctaBoxesTitle1Value.'</ctaBoxesvTitle1>
<ctaBoxesvID2>'.$sanctaBoxesID2Value.'</ctaBoxesvID2>
<ctaBoxesvTitle2>'.$sanctaBoxesTitle2Value.'</ctaBoxesvTitle2>
<ctaBoxesvID3>'.$sanctaBoxesID3Value.'</ctaBoxesvID3>
<ctaBoxesvTitle3>'.$sanctaBoxesTitle3Value.'</ctaBoxesvTitle3>
<ctaBoxesvID4>'.$sanctaBoxesID4Value.'</ctaBoxesvID4>
<ctaBoxesvTitle4>'.$sanctaBoxesTitle4Value.'</ctaBoxesvTitle4>

<HeadlinesvID1>'.$sanHeadlinesID1Value.'</HeadlinesvID1>
<HeadlinesvTitle1>'.$sanHeadlinesTitle1Value.'</HeadlinesvTitle1>
<HeadlinesvID2>'.$sanHeadlinesID2Value.'</HeadlinesvID2>
<HeadlinesvTitle2>'.$sanHeadlinesTitle2Value.'</HeadlinesvTitle2>
<HeadlinesvID3>'.$sanHeadlinesID3Value.'</HeadlinesvID3>
<HeadlinesvTitle3>'.$sanHeadlinesTitle3Value.'</HeadlinesvTitle3>
<HeadlinesvID4>'.$sanHeadlinesID4Value.'</HeadlinesvID4>
<HeadlinesvTitle4>'.$sanHeadlinesTitle4Value.'</HeadlinesvTitle4>

<WebBoxesvID1>'.$sanWebBoxesID1Value.'</WebBoxesvID1>
<WebBoxesvTitle1>'.$sanWebBoxesTitle1Value.'</WebBoxesvTitle1>
<WebBoxesvID2>'.$sanWebBoxesID2Value.'</WebBoxesvID2>
<WebBoxesvTitle2>'.$sanWebBoxesTitle2Value.'</WebBoxesvTitle2>
<WebBoxesvID3>'.$sanWebBoxesID3Value.'</WebBoxesvID3>
<WebBoxesvTitle3>'.$sanWebBoxesTitle3Value.'</WebBoxesvTitle3>
<WebBoxesvID4>'.$sanWebBoxesID4Value.'</WebBoxesvID4>
<WebBoxesvTitle4>'.$sanWebBoxesTitle4Value.'</WebBoxesvTitle4>
</langu>
</langs>
XML;
?>';
$phpfp = fopen("../wp-content/uploads/wpgs/xml/core-help.php","wb");
fwrite($phpfp,$phpcontent);
fclose($phpfp);

$content = '<langs>
<langu>
<BelcherBoxvID1>'.$sanBelcherBoxID1Value.'</BelcherBoxvID1>
<BelcherBoxvTitle1>'.$sanBelcherBoxTitle1Value.'</BelcherBoxvTitle1>
<BelcherBoxvID2>'.$sanBelcherBoxID2Value.'</BelcherBoxvID2>
<BelcherBoxvTitle2>'.$sanBelcherBoxTitle2Value.'</BelcherBoxvTitle2>
<BelcherBoxvID3>'.$sanBelcherBoxID3Value.'</BelcherBoxvID3>
<BelcherBoxvTitle3>'.$sanBelcherBoxTitle3Value.'</BelcherBoxvTitle3>
<BelcherBoxvID4>'.$sanBelcherBoxID4Value.'</BelcherBoxvID4>
<BelcherBoxvTitle4>'.$sanBelcherBoxTitle4Value.'</BelcherBoxvTitle4>

<ButtonsvID1>'.$sanButtonsID1Value.'</ButtonsvID1>
<ButtonsvTitle1>'.$sanButtonsTitle1Value.'</ButtonsvTitle1>
<ButtonsvID2>'.$sanButtonsID2Value.'</ButtonsvID2>
<ButtonsvTitle2>'.$sanButtonsTitle2Value.'</ButtonsvTitle2>
<ButtonsvID3>'.$sanButtonsID3Value.'</ButtonsvID3>
<ButtonsvTitle3>'.$sanButtonsTitle3Value.'</ButtonsvTitle3>
<ButtonsvID4>'.$sanButtonsID4Value.'</ButtonsvID4>
<ButtonsvTitle4>'.$sanButtonsTitle4Value.'</ButtonsvTitle4>

<ctaBoxesvID1>'.$sanctaBoxesID1Value.'</ctaBoxesvID1>
<ctaBoxesvTitle1>'.$sanctaBoxesTitle1Value.'</ctaBoxesvTitle1>
<ctaBoxesvID2>'.$sanctaBoxesID2Value.'</ctaBoxesvID2>
<ctaBoxesvTitle2>'.$sanctaBoxesTitle2Value.'</ctaBoxesvTitle2>
<ctaBoxesvID3>'.$sanctaBoxesID3Value.'</ctaBoxesvID3>
<ctaBoxesvTitle3>'.$sanctaBoxesTitle3Value.'</ctaBoxesvTitle3>
<ctaBoxesvID4>'.$sanctaBoxesID4Value.'</ctaBoxesvID4>
<ctaBoxesvTitle4>'.$sanctaBoxesTitle4Value.'</ctaBoxesvTitle4>

<HeadlinesvID1>'.$sanHeadlinesID1Value.'</HeadlinesvID1>
<HeadlinesvTitle1>'.$sanHeadlinesTitle1Value.'</HeadlinesvTitle1>
<HeadlinesvID2>'.$sanHeadlinesID2Value.'</HeadlinesvID2>
<HeadlinesvTitle2>'.$sanHeadlinesTitle2Value.'</HeadlinesvTitle2>
<HeadlinesvID3>'.$sanHeadlinesID3Value.'</HeadlinesvID3>
<HeadlinesvTitle3>'.$sanHeadlinesTitle3Value.'</HeadlinesvTitle3>
<HeadlinesvID4>'.$sanHeadlinesID4Value.'</HeadlinesvID4>
<HeadlinesvTitle4>'.$sanHeadlinesTitle4Value.'</HeadlinesvTitle4>

<WebBoxesvID1>'.$sanWebBoxesID1Value.'</WebBoxesvID1>
<WebBoxesvTitle1>'.$sanWebBoxesTitle1Value.'</WebBoxesvTitle1>
<WebBoxesvID2>'.$sanWebBoxesID2Value.'</WebBoxesvID2>
<WebBoxesvTitle2>'.$sanWebBoxesTitle2Value.'</WebBoxesvTitle2>
<WebBoxesvID3>'.$sanWebBoxesID3Value.'</WebBoxesvID3>
<WebBoxesvTitle3>'.$sanWebBoxesTitle3Value.'</WebBoxesvTitle3>
<WebBoxesvID4>'.$sanWebBoxesID4Value.'</WebBoxesvID4>
<WebBoxesvTitle4>'.$sanWebBoxesTitle4Value.'</WebBoxesvTitle4>
</langu>
</langs>';
$fp = fopen("../wp-content/uploads/wpgs/xml/core-help.xml","wb");
fwrite($fp,$content);
fclose($fp);
}

include '../wp-content/uploads/wpgs/xml/core-help.php';

$langs = new SimpleXMLElement($xmlstr);
$belcher_box_id_1_value = $langs->langu[0]->BelcherBoxvID1;
$belcher_box_title_1_value = $langs->langu[0]->BelcherBoxvTitle1;
$belcher_box_id_2_value = $langs->langu[0]->BelcherBoxvID2;
$belcher_box_title_2_value = $langs->langu[0]->BelcherBoxvTitle2;
$belcher_box_id_3_value = $langs->langu[0]->BelcherBoxvID3;
$belcher_box_title_3_value = $langs->langu[0]->BelcherBoxvTitle3;
$belcher_box_id_4_value = $langs->langu[0]->BelcherBoxvID4;
$belcher_box_title_4_value = $langs->langu[0]->BelcherBoxvTitle4;

$buttons_id_1_value = $langs->langu[0]->ButtonsvID1;
$buttons_title_1_value = $langs->langu[0]->ButtonsvTitle1;
$buttons_id_2_value = $langs->langu[0]->ButtonsvID2;
$buttons_title_2_value = $langs->langu[0]->ButtonsvTitle2;
$buttons_id_3_value = $langs->langu[0]->ButtonsvID3;
$buttons_title_3_value = $langs->langu[0]->ButtonsvTitle3;
$buttons_id_4_value = $langs->langu[0]->ButtonsvID4;
$buttons_title_4_value = $langs->langu[0]->ButtonsvTitle4;

$cta_boxes_id_1_value = $langs->langu[0]->ctaBoxesvID1;
$cta_boxes_title_1_value = $langs->langu[0]->ctaBoxesvTitle1;
$cta_boxes_id_2_value = $langs->langu[0]->ctaBoxesvID2;
$cta_boxes_title_2_value = $langs->langu[0]->ctaBoxesvTitle2;
$cta_boxes_id_3_value = $langs->langu[0]->ctaBoxesvID3;
$cta_boxes_title_3_value = $langs->langu[0]->ctaBoxesvTitle3;
$cta_boxes_id_4_value = $langs->langu[0]->ctaBoxesvID4;
$cta_boxes_title_4_value = $langs->langu[0]->ctaBoxesvTitle4;

$headlines_id_1_value = $langs->langu[0]->HeadlinesvID1;
$headlines_title_1_value = $langs->langu[0]->HeadlinesvTitle1;
$headlines_id_2_value = $langs->langu[0]->HeadlinesvID2;
$headlines_title_2_value = $langs->langu[0]->HeadlinesvTitle2;
$headlines_id_3_value = $langs->langu[0]->HeadlinesvID3;
$headlines_title_3_value = $langs->langu[0]->HeadlinesvTitle3;
$cta_boxes_id_4_value = $langs->langu[0]->HeadlinesvID4;
$headlines_title_4_value = $langs->langu[0]->HeadlinesvTitle4;

$web_boxes_id_1_value = $langs->langu[0]->WebBoxesvID1;
$web_boxes_title_1_value = $langs->langu[0]->WebBoxesvTitle1;
$web_boxes_id_2_value = $langs->langu[0]->WebBoxesvID2;
$web_boxes_title_2_value = $langs->langu[0]->WebBoxesvTitle2;
$web_boxes_id_3_value = $langs->langu[0]->WebBoxesvID3;
$web_boxes_title_3_value = $langs->langu[0]->WebBoxesvTitle3;
$web_boxes_id_4_value = $langs->langu[0]->WebBoxesvID4;
$web_boxes_title_4_value = $langs->langu[0]->WebBoxesvTitle4;

if (isset($_POST['BelcherBoxID1Value'])) { ?>
    <div class="updated">
        <p><?php _e( 'Video Help Settings Updated!', 'wpgs' ); ?></p>
    </div>
<?php } ?>
<div class="wrap">
		<h2><?php _e('wpGraphicStudio Core Modules - Help Video Settings'); ?></h2>

		<form method="post" action="admin.php?page=wpgs-core-settings&tab=help">
<h4><?php _e('Users can view general overview and usage videos for this module anytime by clicking on the help icon located near the top right corner of the application labeled with a "?".<br>
Below are all the video and help options used within this modules help section.<br>
The videos currently setup for this module are the official wpGraphicStudio help videos specific to this module.'); ?></h4>
<h3><?php _e('Help Video IDs'); ?><?php _e(' - You can add up to 4 YouTube videos which are specific to this Module'); ?></h3>
<h4><?php _e('The YouTube video id is the 11 character id at the end of the YouTube url (after the "v=" variable) for the page where your help video can be viewed at.<br>
For example, in the following video url: (www.youtube.com/watch?v=<b><u>3kwom8OpnMg</u></b>) the video id is "3kwom8OpnMg".'); ?></h4>

<h3><?php _e('Belcher Box Module - Help Video Settings'); ?><?php _e(' - You can add up to 4 YouTube videos which are specific to this Module'); ?></h3>
Belcher Box Video ID 1: <input type="text" name="BelcherBoxID1Value" value="<?php echo $belcher_box_id_1_value ?>"><br>
Belcher Box Video 1 Title: <input type="text" name="BelcherBoxTitle1Value" value="<?php echo $belcher_box_title_1_value ?>" size="20"><br>
<br>
Belcher Box Video ID 2: <input type="text" name="BelcherBoxID2Value" value="<?php echo $belcher_box_id_2_value ?>"><br>
Belcher Box Video 2 Title: <input type="text" name="BelcherBoxTitle2Value" value="<?php echo $belcher_box_title_2_value ?>" size="20"><br>
<br>
Belcher Box Video ID 3: <input type="text" name="BelcherBoxID3Value" value="<?php echo $belcher_box_id_3_value ?>"><br>
Belcher Box Video 3 Title: <input type="text" name="BelcherBoxTitle3Value" value="<?php echo $belcher_box_title_3_value ?>" size="20"><br>
<br>
Belcher Box Video ID 4: <input type="text" name="BelcherBoxID4Value" value="<?php echo $belcher_box_id_4_value ?>"><br>
Belcher Box Video 4 Title: <input type="text" name="BelcherBoxTitle4Value" value="<?php echo $belcher_box_title_4_value ?>" size="20"><br>

<h3><?php _e('Buttons Module - Help Video Settings'); ?><?php _e(' - You can add up to 4 YouTube videos which are specific to this Module'); ?></h3>
Buttons Video ID 1: <input type="text" name="ButtonsID1Value" value="<?php echo $buttons_id_1_value ?>"><br>
Buttons Video 1 Title: <input type="text" name="ButtonsTitle1Value" value="<?php echo $buttons_title_1_value ?>" size="20"><br>
<br>
Buttons Video ID 2: <input type="text" name="ButtonsID2Value" value="<?php echo $buttons_id_2_value ?>"><br>
Buttons Video 2 Title: <input type="text" name="ButtonsTitle2Value" value="<?php echo $buttons_title_2_value ?>" size="20"><br>
<br>
Buttons Video ID 3: <input type="text" name="ButtonsID3Value" value="<?php echo $buttons_id_3_value ?>"><br>
Buttons Video 3 Title: <input type="text" name="ButtonsTitle3Value" value="<?php echo $buttons_title_3_value ?>" size="20"><br>
<br>
Buttons Video ID 4: <input type="text" name="ButtonsID4Value" value="<?php echo $buttons_id_4_value ?>"><br>
Buttons Video 4 Title: <input type="text" name="ButtonsTitle4Value" value="<?php echo $buttons_title_4_value ?>" size="20"><br>

<h3><?php _e('Call to Action Boxes Module - Help Video Settings'); ?><?php _e(' - You can add up to 4 YouTube videos which are specific to this Module'); ?></h3>
CTA Boxes Video ID 1: <input type="text" name="ctaBoxesID1Value" value="<?php echo $cta_boxes_id_1_value ?>"><br>
CTA Boxes Video 1 Title: <input type="text" name="ctaBoxesTitle1Value" value="<?php echo $cta_boxes_title_1_value ?>" size="20"><br>
<br>
CTA Boxes Video ID 2: <input type="text" name="ctaBoxesID2Value" value="<?php echo $cta_boxes_id_2_value ?>"><br>
CTA Boxes Video 2 Title: <input type="text" name="ctaBoxesTitle2Value" value="<?php echo $cta_boxes_title_2_value ?>" size="20"><br>
<br>
CTA Boxes Video ID 3: <input type="text" name="ctaBoxesID3Value" value="<?php echo $cta_boxes_id_3_value ?>"><br>
CTA Boxes Video 3 Title: <input type="text" name="ctaBoxesTitle3Value" value="<?php echo $cta_boxes_title_3_value ?>" size="20"><br>
<br>
CTA Boxes Video ID 4: <input type="text" name="ctaBoxesID4Value" value="<?php echo $cta_boxes_id_4_value ?>"><br>
CTA Boxes Video 4 Title: <input type="text" name="ctaBoxesTitle4Value" value="<?php echo $cta_boxes_title_4_value ?>" size="20"><br>

<h3><?php _e('Headlines Module - Help Video Settings'); ?><?php _e(' - You can add up to 4 YouTube videos which are specific to this Module'); ?></h3>
Headlines Video ID 1: <input type="text" name="HeadlinesID1Value" value="<?php echo $headlines_id_1_value ?>"><br>
Headlines Video 1 Title: <input type="text" name="HeadlinesTitle1Value" value="<?php echo $headlines_title_1_value ?>" size="20"><br>
<br>
Headlines Video ID 2: <input type="text" name="HeadlinesID2Value" value="<?php echo $headlines_id_2_value ?>"><br>
Headlines Video 2 Title: <input type="text" name="HeadlinesTitle2Value" value="<?php echo $headlines_title_2_value ?>" size="20"><br>
<br>
Headlines Video ID 3: <input type="text" name="HeadlinesID3Value" value="<?php echo $headlines_id_3_value ?>"><br>
Headlines Video 3 Title: <input type="text" name="HeadlinesTitle3Value" value="<?php echo $headlines_title_3_value ?>" size="20"><br>
<br>
Headlines Video ID 4: <input type="text" name="HeadlinesID4Value" value="<?php echo $headlines_id_4_value ?>"><br>
Headlines Video 4 Title: <input type="text" name="HeadlinesTitle4Value" value="<?php echo $headlines_title_4_value ?>" size="20"><br>

<h3><?php _e('Web Boxes Module - Help Video Settings'); ?><?php _e(' - You can add up to 4 YouTube videos which are specific to this Module'); ?></h3>
Web Boxes Video ID 1: <input type="text" name="WebBoxesID1Value" value="<?php echo $web_boxes_id_1_value ?>"><br>
Web Boxes Video 1 Title: <input type="text" name="WebBoxesTitle1Value" value="<?php echo $web_boxes_title_1_value ?>" size="20"><br>
<br>
Web Boxes Video ID 2: <input type="text" name="WebBoxesID2Value" value="<?php echo $web_boxes_id_2_value ?>"><br>
Web Boxes Video 2 Title: <input type="text" name="WebBoxesTitle2Value" value="<?php echo $web_boxes_title_2_value ?>" size="20"><br>
<br>
Web Boxes Video ID 3: <input type="text" name="WebBoxesID3Value" value="<?php echo $web_boxes_id_3_value ?>"><br>
Web Boxes Video 3 Title: <input type="text" name="WebBoxesTitle3Value" value="<?php echo $web_boxes_title_3_value ?>" size="20"><br>
<br>
Web Boxes Video ID 4: <input type="text" name="WebBoxesID4Value" value="<?php echo $web_boxes_id_4_value ?>"><br>
Web Boxes Video 4 Title: <input type="text" name="WebBoxesTitle4Value" value="<?php echo $web_boxes_title_4_value ?>" size="20"><br>

			</table>
      <?php submit_button(); ?>
</form>
<?php
break;
}}}

function wpgs_wpgraphicstudio_register_logo() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_logo_url' );
}
function wpgs_wpgraphicstudio_register_belcher_box_graphic() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_belcher_box_url' );
}
function wpgs_wpgraphicstudio_register_buttons_graphic() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_buttons_url' );
}
function wpgs_wpgraphicstudio_register_cta_boxes_graphic() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_cta_boxes_url' );
}
function wpgs_wpgraphicstudio_register_web_boxes_graphic() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_web_boxes_url' );
}
function wpgs_wpgraphicstudio_register_headlines_graphic() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_headlines_url' );
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
function wpgs_wpgraphicstudio_register_delete_files() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_delete_files' );
}
function wpgs_wpgraphicstudio_register_email_graphics() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_email_graphics' );
}
function wpgs_wpgraphicstudio_register_remove_settings() {
	register_setting('wpgs_wpgraphicstudio_settings', 'wpgs_wpgraphicstudio_remove_settings' );
}

add_action('admin_init', 'wpgs_wpgraphicstudio_register_logo');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_cta_boxes_graphic');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_web_boxes_graphic');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_headlines_graphic');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_buttons_graphic');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_belcher_box_graphic');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_nav');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_gallery');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_members');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_delete_files');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_email_graphics');
add_action('admin_init', 'wpgs_wpgraphicstudio_register_remove_settings');

function wpgs_sanitize_register_logo( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_logo_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_logo_url' );
	}
	return $new;
}

function wpgs_sanitize_register_belcher_box_graphic( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_belcher_box_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_belcher_box_url' );
	}
	return $new;
}

function wpgs_sanitize_register_cta_boxes_graphic( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_cta_boxes_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_cta_boxes_url' );
	}
	return $new;
}

function wpgs_sanitize_register_buttons_graphic( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_buttons_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_buttons_url' );
	}
	return $new;
}

function wpgs_sanitize_register_headlines_graphic( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_headlines_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_headlines_url' );
	}
	return $new;
}

function wpgs_sanitize_register_web_boxes_graphic( $new ) {
	$old = get_option( 'wpgs_wpgraphicstudio_web_boxes_url' );
	if( $old && $old != $new ) {
		update_option( 'wpgs_wpgraphicstudio_web_boxes_url' );
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

function deactivate_wpgraphicstudio() {
$wp_upload_dir = wp_upload_dir();

$headlines_delete = get_page_by_title( 'Headlines' );
wp_delete_post($headlines_delete->ID, true);
$myHeadlines = $wp_upload_dir['basedir'] . '/wpgs/images/create/headlines.png';
unlink($myHeadlines);

$web_boxes_delete = get_page_by_title( 'Web Boxes' );
wp_delete_post($web_boxes_delete->ID, true);
$myWebBoxes = $wp_upload_dir['basedir'] . '/wpgs/images/create/web-boxes.png';
unlink($myWebBoxes);

$cta_boxes_delete = get_page_by_title( 'CTA Boxes' );
wp_delete_post($cta_boxes_delete->ID, true);
$myctaBoxes = $wp_upload_dir['basedir'] . '/wpgs/images/create/cta-boxes.png';
unlink($myctaBoxes);

$buttons_delete = get_page_by_title( 'Buttons' );
wp_delete_post($buttons_delete->ID, true);
$myButtons = $wp_upload_dir['basedir'] . '/wpgs/images/create/buttons.png';
unlink($myButtons);

$belcher_boxes_delete = get_page_by_title( 'Belcher Boxes' );
wp_delete_post($belcher_boxes_delete->ID, true);
$myBelcherBoxes = $wp_upload_dir['basedir'] . '/wpgs/images/create/belcher-boxes.png';
unlink($myBelcherBoxes);

$members_area_delete = get_page_by_title( 'Members Area' );
wp_delete_post($members_area_delete->ID, true);

update_option( 'wpgs_wpgraphicstudio_install', 0 );
delete_option( 'create_page', create_page );

}

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

function wpgs_remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
show_admin_bar(false);
}
}
add_action('after_setup_theme', 'wpgs_remove_admin_bar');

	$upgraded_from = get_option( 'wpgs_version_upgraded_from' );

function wpgs_restrict_admin_with_redirect() {
	if ( ! current_user_can( 'manage_options' ) && $_SERVER['PHP_SELF'] != '/wp-admin/' ) {
		wp_redirect( site_url() ); exit;
	}
}

add_action( 'admin_init', 'wpgs_restrict_admin_with_redirect' );

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
function wpgs_get_pages( $force = false ) {

	$pages_options = array( '' => '' ); // Blank option

	if( ( ! isset( $_GET['page'] ) || 'wpgs-settings' != $_GET['page'] ) && ! $force ) {
		return $pages_options;
	}

	$pages = get_pages();
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}

	return $pages_options;
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
if (get_option( 'wpgs_wpgraphicstudio_delete_files' ) == 'On') {
add_action( 'delete_user', 'fileRemove' );
}

register_deactivation_hook( WPGS_PLUGIN_FILE, 'deactivate_wpgraphicstudio' );
if (get_option( 'wpgs_wpgraphicstudio_install' ) == '') {
register_activation_hook( WPGS_PLUGIN_FILE, 'wpgs_install' );
register_activation_hook( WPGS_PLUGIN_FILE, 'images_wpgraphicstudio_move' );
} elseif (get_option( 'wpgs_wpgraphicstudio_install' ) == '0') {
register_activation_hook( WPGS_PLUGIN_FILE, 'wpgs_install' );
}
