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

function ilc_admin_tabs( $current = 'main' ) {
    $tabs = array( 'main' => 'Main Settings', 'customize' => 'Customize', 'language' => 'Language' );
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
   $settings = get_option( "ilc_theme_settings" );

//generic HTML and code goes here

if ( isset ( $_GET['tab'] ) ) ilc_admin_tabs($_GET['tab']); else ilc_admin_tabs('main');

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

//if ($_POST['$wpgs_wpgraphicstudio_belcher_box_url'] != '') {
$BelcherBoxurl = $belcher_box_url;
$BelcherBoxurlparts = parse_url("$BelcherBoxurl");
$BelcherBoxextracted = $BelcherBoxurlparts['path'];
$BelcherBoxextracted_trim = trim( $BelcherBoxextracted, "/" );
$BelcherBoxfrommydir_trim = ABSPATH . ''.$BelcherBoxextracted_trim.'';
$frommydir = ABSPATH . '';

$BelcherBoxpath = pathinfo(''.$BelcherBoxfrommydir_trim.'');

rename(''.$BelcherBoxfrommydir_trim.'', ''.$BelcherBoxpath['dirname'].'/belcher-boxes.png');
copy(''.$BelcherBoxpath['dirname'].'/belcher-boxes.png', ''.$tomydir.'belcher-boxes.png');
//}

//if ($Buttonsurl != '') {
$Buttonsurl = $buttons_url;
$Buttonsurlparts = parse_url("$Buttonsurl");
$Buttonsextracted = $Buttonsurlparts['path'];
$Buttonsextracted_trim = trim( $Buttonsextracted, "/" );
$Buttonsfrommydir_trim = ABSPATH . ''.$Buttonsextracted_trim.'';

$Buttonspath = pathinfo(''.$Buttonsfrommydir_trim.'');

//if ((isset($Buttonsurl)) && ($Buttonsurl != '')) {
rename(''.$Buttonsfrommydir_trim.'', ''.$Buttonspath['dirname'].'/buttons.png');
copy(''.$Buttonspath['dirname'].'/buttons.png', ''.$tomydir.'buttons.png');
//}
//}

//if ($ctaBoxesurl != '') {
$ctaBoxesurl = $cta_boxes_url;
$ctaBoxesurlparts = parse_url("$ctaBoxesurl");
$ctaBoxesextracted = $ctaBoxesurlparts['path'];
$ctaBoxesextracted_trim = trim( $ctaBoxesextracted, "/" );
$ctaBoxesfrommydir_trim = ABSPATH . ''.$ctaBoxesextracted_trim.'';

$ctaBoxespath = pathinfo(''.$ctaBoxesfrommydir_trim.'');

//if ((isset($ctaBoxesurl)) && ($ctaBoxesurl != '')) {
rename(''.$ctaBoxesfrommydir_trim.'', ''.$ctaBoxespath['dirname'].'/cta-boxes.png');
copy(''.$ctaBoxespath['dirname'].'/cta-boxes.png', ''.$tomydir.'cta-boxes.png');
//}
//}

//if ($Headlinesurl != '') {
$Headlinesurl = $headlines_url;
$Headlinesurlparts = parse_url("$Headlinesurl");
$Headlinesextracted = $Headlinesurlparts['path'];
$Headlinesextracted_trim = trim( $Headlinesextracted, "/" );
$Headlinesfrommydir_trim = ABSPATH . ''.$Headlinesextracted_trim.'';

$Headlinespath = pathinfo(''.$Headlinesfrommydir_trim.'');

//if ((isset($Headlinesurl)) && ($Headlinesurl != '')) {
rename(''.$Headlinesfrommydir_trim.'', ''.$Headlinespath['dirname'].'/headlines.png');
copy(''.$Headlinespath['dirname'].'/headlines.png', ''.$tomydir.'headlines.png');
//}
//}

//if ($WebBoxesurl != '') {
$WebBoxesurl = $web_boxes_url;
$WebBoxesurlparts = parse_url("$WebBoxesurl");
$WebBoxesextracted = $WebBoxesurlparts['path'];
$WebBoxesextracted_trim = trim( $WebBoxesextracted, "/" );
$WebBoxesfrommydir_trim = ABSPATH . ''.$WebBoxesextracted_trim.'';

$WebBoxespath = pathinfo(''.$WebBoxesfrommydir_trim.'');

//if ((isset($WebBoxesurl)) && ($WebBoxesurl != '')) {
rename(''.$WebBoxesfrommydir_trim.'', ''.$WebBoxespath['dirname'].'/web-boxes.png');
copy(''.$WebBoxespath['dirname'].'/web-boxes.png', ''.$tomydir.'web-boxes.png');
//}
//}
}
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

			<?php settings_fields('wpgs_wpgraphicstudio_settings'); ?>

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
<?php
   break;
      case 'main' :
if ($_GET['settings-updated'] == 'true') { ?>
    <div class="updated">
        <p><?php _e( 'Main Settings/Options Updated!', 'wpgs' ); ?></p>
    </div>
<?php }

	$per_gallery 	= get_option( 'wpgs_wpgraphicstudio_per_gallery' );
	$per_members 	= get_option( 'wpgs_wpgraphicstudio_per_members' );
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
				</tbody>
      <?php
break;
      case 'language' :
      if (isset($_POST['navTextValue'])) {

$santxt2Headlines = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt2Headlines']));
$sanNavTextValue = preg_replace('/\\\\/', '', htmlspecialchars($_POST['navTextValue']));
$sanNavStyleValue = preg_replace('/\\\\/', '', htmlspecialchars($_POST['navStyleValue']));
$sanNavIconValue = preg_replace('/\\\\/', '', htmlspecialchars($_POST['navIconValue']));
$sanNavColorValue = preg_replace('/\\\\/', '', htmlspecialchars($_POST['navColorValue']));
$sanNavGraphicsValue = preg_replace('/\\\\/', '', htmlspecialchars($_POST['navGraphicsValue']));
$sanfont1Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font1Value']));
$sanfont2Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font2Value']));
$sanfont3Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font3Value']));
$sanfont4Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font4Value']));
$sanfont5Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font5Value']));
$sanfont6Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font6Value']));
$sanfont7Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font7Value']));
$sanfont8Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font8Value']));
$sanfont9Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font9Value']));
$sanfont10Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font10Value']));
$sanfont11Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font11Value']));
$sanfont12Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font12Value']));
$sanfont13Value = preg_replace('/\\\\/', '', htmlspecialchars($_POST['font13Value']));
$sansaveOptions = preg_replace('/\\\\/', '', htmlspecialchars($_POST['saveOptions']));
$sanColorOptions = preg_replace('/\\\\/', '', htmlspecialchars($_POST['ColorOptions']));
$santextImageDimensions = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textImageDimensions']));
$sansaveAs = preg_replace('/\\\\/', '', htmlspecialchars($_POST['saveAs']));
$santextIcon = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textIcon']));
$santextBackground = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textBackground']));
$santextBackgroundColor = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textBackgroundColor']));
$sansaveTo = preg_replace('/\\\\/', '', htmlspecialchars($_POST['saveTo']));
$santxtAlign = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtAlign']));
$santxtField = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtField']));
$santxtColor = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtColor']));
$santxtSize = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtSize']));
$santxtFont = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtFont']));
$santxtButton = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtButton']));
$santxtCTAboxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtCTAboxes']));
$santxt1BelcherBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt1BelcherBoxes']));
$santxt2BelcherBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt2BelcherBoxes']));
$santxt3BelcherBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt3BelcherBoxes']));
$santxt4BelcherBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt4BelcherBoxes']));
$santxt1WebBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt1WebBoxes']));
$santxt2WebBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt2WebBoxes']));
$santxt3WebBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt3WebBoxes']));
$santxt4WebBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt4WebBoxes']));
$santxt5WebBoxes = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt5WebBoxes']));
$santxt1Headlines = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt1Headlines']));
$santxt2Headlines = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt2Headlines']));
$santxt3Headlines = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txt3Headlines']));
$santxtAdditional = preg_replace('/\\\\/', '', htmlspecialchars($_POST['txtAdditional']));
$sanbtnReset = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnReset']));
$sanbtnDownload = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnDownload']));
$sanbtnCapture = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnCapture']));
$sanbtnUpload = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnUpload']));
$sanbtnDelete = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnDelete']));
$sanbtnBack = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnBack']));
$sanbtnFront = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnFront']));
$sanbtnAlignLeft = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnAlignLeft']));
$sanbtnAlignCenter = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnAlignCenter']));
$sanbtnAlignRight = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnAlignRight']));
$sanbtnAddText = preg_replace('/\\\\/', '', htmlspecialchars($_POST['btnAddText']));
$santextBorderStroke = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textBorderStroke']));
$santextTexture = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textTexture']));
$santextTextureBackground = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textTextureBackground']));
$santextPaymentBoxBackground = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textPaymentBoxBackground']));
$santextDoodle = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textDoodle']));
$santextPayment = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textPayment']));
$santextBorders = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textBorders']));
$santextHighlight = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textHighlight']));
$santextmWidth = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textmWidth']));
$santextXHeight = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textXHeight']));
$santextNotice = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textNotice']));
$santextButtonColor = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textButtonColor']));
$santextBottomColor = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textBottomColor']));
$santextButtonBorderColor = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textButtonBorderColor']));
$santextBorderColor = preg_replace('/\\\\/', '', htmlspecialchars($_POST['textBorderColor']));

$phpcontent = '<?php
$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<langs>
<langu>
<navText>'.$sanNavTextValue.'</navText>
<navStyle>'.$sanNavStyleValue.'</navStyle>
<navIcon>'.$sanNavIconValue.'</navIcon>
<navColor>'.$sanNavColorValue.'</navColor>
<navGraphics>'.$sanNavGraphicsValue.'</navGraphics>
<font1>'.$sanfont1Value.'</font1>
<font2>'.$sanfont2Value.'</font2>
<font3>'.$sanfont3Value.'</font3>
<font4>'.$sanfont4Value.'</font4>
<font5>'.$sanfont5Value.'</font5>
<font6>'.$sanfont6Value.'</font6>
<font7>'.$sanfont7Value.'</font7>
<font8>'.$sanfont8Value.'</font8>
<font9>'.$sanfont9Value.'</font9>
<font10>'.$sanfont10Value.'</font10>
<font11>'.$sanfont11Value.'</font11>
<font12>'.$sanfont12Value.'</font12>
<font13>'.$sanfont13Value.'</font13>
<textSaveOptions>'.$sansaveOptions.'</textSaveOptions>
<textColorOptions>'.$sanColorOptions.'</textColorOptions>
<textImageDimensions>'.$santextImageDimensions.'</textImageDimensions>
<textSaveAs>'.$sansaveAs.'</textSaveAs>
<textIcon>'.$santextIcon.'</textIcon>
<textBackground>'.$santextBackground.'</textBackground>
<textBackgroundColor>'.$santextBackgroundColor.'</textBackgroundColor>
<textSaveTo>'.$sansaveTo.'</textSaveTo>
<txtAlign>'.$santxtAlign.'</txtAlign>
<txtField>'.$santxtField.'</txtField>
<txtColor>'.$santxtColor.'</txtColor>
<txtSize>'.$santxtSize.'</txtSize>
<txtFont>'.$santxtFont.'</txtFont>
<txtButton>'.$santxtButton.'</txtButton>
<txtCTAboxes>'.$santxtCTAboxes.'</txtCTAboxes>
<txt1BelcherBoxes>'.$santxt1BelcherBoxes.'</txt1BelcherBoxes>
<txt2BelcherBoxes>'.$santxt2BelcherBoxes.'</txt2BelcherBoxes>
<txt3BelcherBoxes>'.$santxt3BelcherBoxes.'</txt3BelcherBoxes>
<txt4BelcherBoxes>'.$santxt4BelcherBoxes.'</txt4BelcherBoxes>
<txt1WebBoxes>'.$santxt1WebBoxes.'</txt1WebBoxes>
<txt2WebBoxes>'.$santxt2WebBoxes.'</txt2WebBoxes>
<txt3WebBoxes>'.$santxt3WebBoxes.'</txt3WebBoxes>
<txt4WebBoxes>'.$santxt4WebBoxes.'</txt4WebBoxes>
<txt5WebBoxes>'.$santxt5WebBoxes.'</txt5WebBoxes>
<txt1Headlines>'.$santxt1Headlines.'</txt1Headlines>
<txt2Headlines>'.$santxt2Headlines.'</txt2Headlines>
<txt3Headlines>'.$santxt3Headlines.'</txt3Headlines>
<txtAdditional>'.$santxtAdditional.'</txtAdditional>
<btnReset>'.$sanbtnReset.'</btnReset>
<btnDownload>'.$sanbtnDownload.'</btnDownload>
<btnCapture>'.$sanbtnCapture.'</btnCapture>
<btnUpload>'.$sanbtnUpload.'</btnUpload>
<btnDelete>'.$sanbtnDelete.'</btnDelete>
<btnBack>'.$sanbtnBack.'</btnBack>
<btnFront>'.$sanbtnFront.'</btnFront>
<btnAlignLeft>'.$sanbtnAlignLeft.'</btnAlignLeft>
<btnAlignCenter>'.$sanbtnAlignCenter.'</btnAlignCenter>
<btnAlignRight>'.$sanbtnAlignRight.'</btnAlignRight>
<btnAddText>'.$sanbtnAddText.'</btnAddText>
<textBorderStroke>'.$santextBorderStroke.'</textBorderStroke>
<textTexture>'.$santextTexture.'</textTexture>
<textTextureBackground>'.$santextTextureBackground.'</textTextureBackground>
<textPaymentBoxBackground>'.$santextPaymentBoxBackground.'</textPaymentBoxBackground>
<textDoodle>'.$santextDoodle.'</textDoodle>
<textHighlight>'.$santextHighlight.'</textHighlight>
<textPayment>'.$santextPayment.'</textPayment>
<textBorders>'.$santextBorders.'</textBorders>
<textmWidth>'.$santextmWidth.'</textmWidth>
<textXHeight>'.$santextXHeight.'</textXHeight>
<textNotice>'.$santextNotice.'</textNotice>
<textButtonColor>'.$santextButtonColor.'</textButtonColor>
<textBottomColor>'.$santextBottomColor.'</textBottomColor>
<textButtonBorderColor>'.$santextButtonBorderColor.'</textButtonBorderColor>
<textBorderColor>'.$santextBorderColor.'</textBorderColor>
</langu>
</langs>
XML;
?>';
$phpfp = fopen("../wp-content/plugins/wpgraphicstudio/includes/language.php","wb");
fwrite($phpfp,$phpcontent);
fclose($phpfp);

$content = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<langs>
<langu>
<navText>'.$sanNavTextValue.'</navText>
<navStyle>'.$sanNavStyleValue.'</navStyle>
<navIcon>'.$sanNavIconValue.'</navIcon>
<navColor>'.$sanNavColorValue.'</navColor>
<navGraphics>'.$sanNavGraphicsValue.'</navGraphics>
<font1>'.$sanfont1Value.'</font1>
<font2>'.$sanfont2Value.'</font2>
<font3>'.$sanfont3Value.'</font3>
<font4>'.$sanfont4Value.'</font4>
<font5>'.$sanfont5Value.'</font5>
<font6>'.$sanfont6Value.'</font6>
<font7>'.$sanfont7Value.'</font7>
<font8>'.$sanfont8Value.'</font8>
<font9>'.$sanfont9Value.'</font9>
<font10>'.$sanfont10Value.'</font10>
<font11>'.$sanfont11Value.'</font11>
<font12>'.$sanfont12Value.'</font12>
<font13>'.$sanfont13Value.'</font13>
<textSaveOptions>'.$sansaveOptions.'</textSaveOptions>
<textColorOptions>'.$sanColorOptions.'</textColorOptions>
<textImageDimensions>'.$santextImageDimensions.'</textImageDimensions>
<textSaveAs>'.$sansaveAs.'</textSaveAs>
<textIcon>'.$santextIcon.'</textIcon>
<textBackground>'.$santextBackground.'</textBackground>
<textBackgroundColor>'.$santextBackgroundColor.'</textBackgroundColor>
<textSaveTo>'.$sansaveTo.'</textSaveTo>
<txtAlign>'.$santxtAlign.'</txtAlign>
<txtField>'.$santxtField.'</txtField>
<txtColor>'.$santxtColor.'</txtColor>
<txtSize>'.$santxtSize.'</txtSize>
<txtFont>'.$santxtFont.'</txtFont>
<txtButton>'.$santxtButton.'</txtButton>
<txtCTAboxes>'.$santxtCTAboxes.'</txtCTAboxes>
<txt1BelcherBoxes>'.$santxt1BelcherBoxes.'</txt1BelcherBoxes>
<txt2BelcherBoxes>'.$santxt2BelcherBoxes.'</txt2BelcherBoxes>
<txt3BelcherBoxes>'.$santxt3BelcherBoxes.'</txt3BelcherBoxes>
<txt4BelcherBoxes>'.$santxt4BelcherBoxes.'</txt4BelcherBoxes>
<txt1WebBoxes>'.$santxt1WebBoxes.'</txt1WebBoxes>
<txt2WebBoxes>'.$santxt2WebBoxes.'</txt2WebBoxes>
<txt3WebBoxes>'.$santxt3WebBoxes.'</txt3WebBoxes>
<txt4WebBoxes>'.$santxt4WebBoxes.'</txt4WebBoxes>
<txt5WebBoxes>'.$santxt5WebBoxes.'</txt5WebBoxes>
<txt1Headlines>'.$santxt1Headlines.'</txt1Headlines>
<txt2Headlines>'.$santxt2Headlines.'</txt2Headlines>
<txt3Headlines>'.$santxt3Headlines.'</txt3Headlines>
<txtAdditional>'.$santxtAdditional.'</txtAdditional>
<btnReset>'.$sanbtnReset.'</btnReset>
<btnDownload>'.$sanbtnDownload.'</btnDownload>
<btnCapture>'.$sanbtnCapture.'</btnCapture>
<btnUpload>'.$sanbtnUpload.'</btnUpload>
<btnDelete>'.$sanbtnDelete.'</btnDelete>
<btnBack>'.$sanbtnBack.'</btnBack>
<btnFront>'.$sanbtnFront.'</btnFront>
<btnAlignLeft>'.$sanbtnAlignLeft.'</btnAlignLeft>
<btnAlignCenter>'.$sanbtnAlignCenter.'</btnAlignCenter>
<btnAlignRight>'.$sanbtnAlignRight.'</btnAlignRight>
<btnAddText>'.$sanbtnAddText.'</btnAddText>
<textBorderStroke>'.$santextBorderStroke.'</textBorderStroke>
<textTexture>'.$santextTexture.'</textTexture>
<textTextureBackground>'.$santextTextureBackground.'</textTextureBackground>
<textPaymentBoxBackground>'.$santextPaymentBoxBackground.'</textPaymentBoxBackground>
<textDoodle>'.$santextDoodle.'</textDoodle>
<textHighlight>'.$santextHighlight.'</textHighlight>
<textPayment>'.$santextPayment.'</textPayment>
<textBorders>'.$santextBorders.'</textBorders>
<textmWidth>'.$santextmWidth.'</textmWidth>
<textXHeight>'.$santextXHeight.'</textXHeight>
<textNotice>'.$santextNotice.'</textNotice>
<textButtonColor>'.$santextButtonColor.'</textButtonColor>
<textBottomColor>'.$santextBottomColor.'</textBottomColor>
<textButtonBorderColor>'.$santextButtonBorderColor.'</textButtonBorderColor>
<textBorderColor>'.$santextBorderColor.'</textBorderColor>
</langu>
</langs>';
$fp = fopen("../wp-content/plugins/wpgraphicstudio/includes/language.xml","wb");
fwrite($fp,$content);
fclose($fp);
}

include 'language.php';

$langs = new SimpleXMLElement($xmlstr);
$nav_text_value = $langs->langu[0]->navText;
$nav_style_value = $langs->langu[0]->navStyle;
$nav_icon_value = $langs->langu[0]->navIcon;
$nav_color_value = $langs->langu[0]->navColor;
$nav_graphics_value = $langs->langu[0]->navGraphics;

$font_1_value = $langs->langu[0]->font1;
$font_2_value = $langs->langu[0]->font2;
$font_3_value = $langs->langu[0]->font3;
$font_4_value = $langs->langu[0]->font4;
$font_5_value = $langs->langu[0]->font5;
$font_6_value = $langs->langu[0]->font6;
$font_7_value = $langs->langu[0]->font7;
$font_8_value = $langs->langu[0]->font8;
$font_9_value = $langs->langu[0]->font9;
$font_10_value = $langs->langu[0]->font10;
$font_11_value = $langs->langu[0]->font11;
$font_12_value = $langs->langu[0]->font12;
$font_13_value = $langs->langu[0]->font13;

$save_options_value = $langs->langu[0]->textSaveOptions;
$color_options_value = $langs->langu[0]->textColorOptions;
$text_image_dimensions_value = $langs->langu[0]->textImageDimensions;
$save_as_value = $langs->langu[0]->textSaveAs;
$save_to_value = $langs->langu[0]->textSaveTo;

$text_font_value = $langs->langu[0]->txtFont;
$text_color_value = $langs->langu[0]->txtColor;
$text_size_value = $langs->langu[0]->txtSize;
$text_align_value = $langs->langu[0]->txtAlign;
$text_field_value = $langs->langu[0]->txtField;

$text_background_value = $langs->langu[0]->textBackground;
$text_icon_value = $langs->langu[0]->textIcon;

$text_border_stroke_value = $langs->langu[0]->textBorderStroke;
$text_texture_value = $langs->langu[0]->textTexture;
$text_texture_background_value = $langs->langu[0]->textTextureBackground;
$text_payment_box_background_value = $langs->langu[0]->textPaymentBoxBackground;
$text_doodle_value = $langs->langu[0]->textDoodle;
$text_payment_value = $langs->langu[0]->textPayment;
$text_borders_value = $langs->langu[0]->textBorders;
$text_highlight_value = $langs->langu[0]->textHighlight;
$text_mwidth_value = $langs->langu[0]->textmWidth;
$text_xheight_value = $langs->langu[0]->textXHeight;
$text_button_color_value = $langs->langu[0]->textButtonColor;
$text_button_border_color_value = $langs->langu[0]->textButtonBorderColor;
$text_bottom_color_value = $langs->langu[0]->textBottomColor;
$text_background_color_value = $langs->langu[0]->textBackgroundColor;
$text_border_color_value = $langs->langu[0]->textBorderColor;

$reset_value = $langs->langu[0]->btnReset;
$delete_value = $langs->langu[0]->btnDelete;
$upload_value = $langs->langu[0]->btnUpload;
$move_forward_value = $langs->langu[0]->btnFront;
$move_backward_value = $langs->langu[0]->btnBack;
$save_gallery_value = $langs->langu[0]->btnCapture;
$save_computer_value = $langs->langu[0]->btnDownload;
$align_left_value = $langs->langu[0]->btnAlignLeft;
$align_center_value = $langs->langu[0]->btnAlignCenter;
$align_right_value = $langs->langu[0]->btnAlignRight;
$add_text_field_value = $langs->langu[0]->btnAddText;

$button_text_field_value = $langs->langu[0]->txtButton;
$cta_boxes_text_field_value = $langs->langu[0]->txtCTAboxes;
$text_notice_value = $langs->langu[0]->textNotice;
$belcherbox_text1_field_value = $langs->langu[0]->txt1BelcherBoxes;
$belcherbox_text2_field_value = $langs->langu[0]->txt2BelcherBoxes;
$belcherbox_text3_field_value = $langs->langu[0]->txt3BelcherBoxes;
$belcherbox_text4_field_value = $langs->langu[0]->txt4BelcherBoxes;
$webboxes_text1_field_value = $langs->langu[0]->txt1WebBoxes;
$webboxes_text2_field_value = $langs->langu[0]->txt2WebBoxes;
$webboxes_text3_field_value = $langs->langu[0]->txt3WebBoxes;
$webboxes_text4_field_value = $langs->langu[0]->txt4WebBoxes;
$webboxes_text5_field_value = $langs->langu[0]->txt5WebBoxes;
$headlines_text1_field_value = $langs->langu[0]->txt1Headlines;
$headlines_text2_field_value = $langs->langu[0]->txt2Headlines;
$headlines_text3_field_value = $langs->langu[0]->txt3Headlines;
$additional_text_field_value = $langs->langu[0]->txtAdditional;

if (isset($_POST['navTextValue'])) { ?>
    <div class="updated">
        <p><?php _e( 'Text Customize Options Updated!', 'wpgs' ); ?></p>
    </div>
<?php } ?>
<div class="wrap">
		<h2><?php _e('wpGraphicStudio Text Customize Options'); ?></h2>

<form method="post" action="">
<h4><?php _e('Below are all the text variations used within the wpGraphicStudio Core graphic modules
 (Buttons, Belcher Boxes, CTA Boxes, Headlines, WordPress Headers) to change the language simply enter
  in the translated text for each text variation and save your changes at the bottom.<br><br>You can use <a href="https://translate.google.com/" target="_blank">
  <b><u>Google Translate</b></u></a> to translate these word combinations to just about any language and paste the translated versions below.<br><br>The text
   variations below are used on all modules and/or the five included modules in this plugin.<br>All add on modules also have a language section where additional
   text variations that are specific to those modules are located and can be translated as well.'); ?></h4>
<h2><?php _e('Navigation Menu'); ?><?php _e(' - Text displayed for each navigation menu button'); ?></h2>
Text menu button: <input type="text" name="navTextValue" value="<?php echo $nav_text_value ?>"><br>
Style menu button: <input type="text" name="navStyleValue" value="<?php echo $nav_style_value ?>"><br>
Icon menu button: <input type="text" name="navIconValue" value="<?php echo $nav_icon_value ?>"><br>
Color menu button: <input type="text" name="navColorValue" value="<?php echo $nav_color_value ?>"><br>
Graphics menu button: <input type="text" name="navGraphicsValue" value="<?php echo $nav_graphics_value ?>">

<h2><?php _e('Font Menu'); ?><?php _e(' - Text displayed for each font name'); ?></h2>
Font 1 name: <input type="text" name="font1Value" value="<?php echo $font_1_value ?>"><br>
Font 2 name: <input type="text" name="font2Value" value="<?php echo $font_2_value ?>"><br>
Font 3 name: <input type="text" name="font3Value" value="<?php echo $font_3_value ?>"><br>
Font 4 name: <input type="text" name="font4Value" value="<?php echo $font_4_value ?>"><br>
Font 5 name: <input type="text" name="font5Value" value="<?php echo $font_5_value ?>"><br>
Font 6 name: <input type="text" name="font6Value" value="<?php echo $font_6_value ?>"><br>
Font 7 name: <input type="text" name="font7Value" value="<?php echo $font_7_value ?>"><br>
Font 8 name: <input type="text" name="font8Value" value="<?php echo $font_8_value ?>"><br>
Font 9 name: <input type="text" name="font9Value" value="<?php echo $font_9_value ?>"><br>
Font 10 name: <input type="text" name="font10Value" value="<?php echo $font_10_value ?>"><br>
Font 11 name: <input type="text" name="font11Value" value="<?php echo $font_11_value ?>"><br>
Font 12 name: <input type="text" name="font12Value" value="<?php echo $font_12_value ?>"><br>
Font 13 name: <input type="text" name="font13Value" value="<?php echo $font_13_value ?>">

<h2><?php _e('Save Options'); ?><?php _e(' - Text displayed in the save options section'); ?></h2>
Save Options: <input type="text" name="saveOptions" value="<?php echo $save_options_value ?>"><br>
Save As: <input type="text" name="saveAs" value="<?php echo $save_as_value ?>"><br>
Save To: <input type="text" name="saveTo" value="<?php echo $save_to_value ?>"><br>
Image Dimensions: <input type="text" name="textImageDimensions" value="<?php echo $text_image_dimensions_value ?>">

<h2><?php _e('Text Menu'); ?><?php _e(' - Text displayed in the text menu'); ?></h2>
Color: <input type="text" name="txtColor" value="<?php echo $text_color_value ?>"><br>
Font: <input type="text" name="txtFont" value="<?php echo $text_font_value ?>"><br>
Size: <input type="text" name="txtSize" value="<?php echo $text_size_value ?>"><br>
Align: <input type="text" name="txtAlign" value="<?php echo $text_align_value ?>"><br>
Text Field: <input type="text" name="txtField" value="<?php echo $text_field_value ?>">

<h2><?php _e('Colors Menu'); ?><?php _e(' - Text displayed in the colors menu'); ?></h2>
Color Options: <input type="text" name="ColorOptions" value="<?php echo $color_options_value ?>"><br>
Background: <input type="text" name="textBackground" value="<?php echo $text_background_value ?>"><br>
Icon: <input type="text" name="textIcon" value="<?php echo $text_icon_value ?>"><br>
Border Stroke: <input type="text" name="textBorderStroke" value="<?php echo $text_border_stroke_value ?>"><br>
Texture: <input type="text" name="textTexture" value="<?php echo $text_texture_value ?>"><br>
Texture Background: <input type="text" name="textTextureBackground" value="<?php echo $text_texture_background_value ?>"><br>
Payment Box Background: <input type="text" name="textPaymentBoxBackground" value="<?php echo $text_payment_box_background_value ?>"><br>
Doodle: <input type="text" name="textDoodle" value="<?php echo $text_doodle_value ?>"><br>
Highlight: <input type="text" name="textHighlight" value="<?php echo $text_highlight_value ?>"><br>
Width: <input type="text" name="textmWidth" value="<?php echo $text_mwidth_value ?>"><br>
X-Height: <input type="text" name="textXHeight" value="<?php echo $text_xheight_value ?>"><br>
Payment: <input type="text" name="textPayment" value="<?php echo $text_payment_value ?>"><br>
Borders: <input type="text" name="textBorders" value="<?php echo $text_borders_value ?>"><br>
Button: <input type="text" name="textButtonColor" value="<?php echo $text_button_color_value ?>"><br>
Button Border Color: <input type="text" name="textButtonBorderColor" value="<?php echo $text_button_border_color_value ?>"><br>
Border Color: <input type="text" name="textBorderColor" value="<?php echo $text_border_color_value ?>"><br>
Background Color: <input type="text" name="textBackgroundColor" value="<?php echo $text_background_color_value ?>"><br>
Bottom Color: <input type="text" name="textBottomColor" value="<?php echo $text_bottom_color_value ?>">

<h2><?php _e('Feature Tooltips'); ?><?php _e(' - Tooltip text displayed when hovering over action icons'); ?></h2>
Reset: <input type="text" name="btnReset" value="<?php echo $reset_value ?>"><br>
Delete: <input type="text" name="btnDelete" value="<?php echo $delete_value ?>"><br>
Upload: <input type="text" name="btnUpload" value="<?php echo $upload_value ?>"><br>
Move Forward: <input type="text" name="btnFront" value="<?php echo $move_forward_value ?>"><br>
Move Backward: <input type="text" name="btnBack" value="<?php echo $move_backward_value ?>"><br>
Save To Gallery: <input type="text" name="btnCapture" value="<?php echo $save_gallery_value ?>"><br>
Save To Computer: <input type="text" name="btnDownload" value="<?php echo $save_computer_value ?>"><br>
Align Left: <input type="text" name="btnAlignLeft" value="<?php echo $align_left_value ?>"><br>
Align Center: <input type="text" name="btnAlignCenter" value="<?php echo $align_center_value ?>"><br>
Align Right: <input type="text" name="btnAlignRight" value="<?php echo $align_right_value ?>"><br>
Text Field: <input type="text" name="btnAddText" value="<?php echo $add_text_field_value ?>">

<h2><?php _e('Height/Width Notice - Headlines'); ?><?php _e(' - Tooltip text displayed when hovering over action icons'); ?></h2>
Height/Width Notice: <input type="text" name="textNotice" value="<?php echo $text_notice_value ?>">

<h2><?php _e('Module Design Areas'); ?><?php _e(' - Text displayed in the design areas of the graphic modules'); ?></h2>
Buttons Text Field: <input type="text" name="txtButton" value="<?php echo $button_text_field_value ?>"><br>
Call toAction Boxes Text Field: <input type="text" name="txtCTAboxes" value="<?php echo $cta_boxes_text_field_value ?>"><br>
Belcher Boxes Text Field 1: <input type="text" name="txt1BelcherBoxes" value="<?php echo $belcherbox_text1_field_value ?>"><br>
Belcher Boxes Text Field 2: <input type="text" name="txt2BelcherBoxes" value="<?php echo $belcherbox_text2_field_value ?>"><br>
Belcher Boxes Text Field 3: <input type="text" name="txt3BelcherBoxes" value="<?php echo $belcherbox_text3_field_value ?>"><br>
Belcher Boxes Text Field 4: <input type="text" name="txt4BelcherBoxes" size="50" value="<?php echo $belcherbox_text4_field_value ?>"><br>
Web Boxes Text Field 1: <input type="text" name="txt1WebBoxes" value="<?php echo $webboxes_text1_field_value ?>"><br>
Web Boxes Text Field 2: <input type="text" name="txt2WebBoxes" value="<?php echo $webboxes_text2_field_value ?>"><br>
Web Boxes Text Field 3: <input type="text" name="txt3WebBoxes" value="<?php echo $webboxes_text3_field_value ?>"><br>
Web Boxes Text Field 4: <input type="text" name="txt4WebBoxes" size="50" value="<?php echo $webboxes_text4_field_value ?>"><br>
Web Boxes Text Field 5: <input type="text" name="txt5WebBoxes" size="50" value="<?php echo $webboxes_text5_field_value ?>"><br>
Headlines Text Field 1: <input type="text" name="txt1Headlines" value="<?php echo $headlines_text1_field_value ?>"><br>
Headlines Text Field 2: <input type="text" name="txt2Headlines" value="<?php echo htmlspecialchars($headlines_text2_field_value) ?>"><br>
Headlines Text Field 3: <input type="text" name="txt3Headlines" value="<?php echo $headlines_text3_field_value ?>"><br>
Additional Text Field: <input type="text" name="txtAdditional" value="<?php echo $additional_text_field_value ?>">

<?php break; } ?>
			</table>
      <?php submit_button(); ?>

		</form>
	<?php
}}

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
function wpgs_get_pages( $force = false ) {

	$pages_options = array( '' => '' ); // Blank option

	if( ( ! isset( $_GET['page'] ) || 'edd-settings' != $_GET['page'] ) && ! $force ) {
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
if ((get_option( 'wpgs_wpgraphicstudio_delete_files' ) == '') || (get_option( 'wpgs_wpgraphicstudio_delete_files' ) == 'On')) {
add_action( 'delete_user', 'fileRemove' );
}