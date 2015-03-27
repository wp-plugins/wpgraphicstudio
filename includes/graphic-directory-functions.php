<?php
/**
 * Upload Functions
 *
 * @package     WPGS
 * @subpackage  WPGS Functions
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Set Graphic Directory
 *
 * Sets the graphic dir to wpgs. This function is called from
 * wpgs_change_graphic_dir()
 *
 * @since 1.0
 * @return array Graphic directory information
*/
function wpgs_set_graphic_dir( $upload ) {
	$upload['subdir'] = '/wpgs';
	$upload['path'] = $upload['basedir'] . $upload['subdir'];
	$upload['url']	= $upload['baseurl'] . $upload['subdir'];
	return $upload;
}


/**
 * Change Graphic Directory
 *
 * Hooks the wpgs_set_graphic_dir filter when appropriate. This function works by
 * hooking on to WordPress and setting the graphic files that
 * are used for WPGS to an wpgs directory under wp-content/uploads/ therefore,
 * the new directory is wp-content/uploads/wpgs/. This directory
 * provides protection to anything in it.
 *
 * @since 3.0
 * @global $pagenow
 * @return void
 */
function wpgs_change_graphic_dir() {
	global $pagenow;

			wpgs_create_protection_files( true );
			add_filter( 'graphic_dir', 'wpgs_set_graphic_dir' );


}
add_action( 'admin_init', 'wpgs_change_graphic_dir', 999 );



/**
 * Creates blank index.php and .htaccess files
 *
 * This function runs approximately once per month in order to ensure all folders
 * have their necessary protection files
 *
 * @since 3.0
 * @return void
 */
function wpgs_create_protection_files( $force = false, $method = false ) {
	if ( false === get_transient( 'wpgs_check_protection_files' ) || $force ) {
		$wp_upload_dir = wp_upload_dir();
		$upload_path = $wp_upload_dir['basedir'] . '/wpgs';

		wp_mkdir_p( $upload_path );

		// Top level blank index.php
		if ( ! file_exists( $upload_path . '/index.php' ) ) {
			@file_put_contents( $upload_path . '/index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
		}

		// Top level .htaccess file
		$rules = wpgs_get_htaccess_rules( $method );
		if ( file_exists( $upload_path . '/.htaccess' ) ) {
			$contents = @file_get_contents( $upload_path . '/.htaccess' );
			if ( $contents !== $rules || ! $contents ) {
				@file_put_contents( $upload_path . '/.htaccess', $rules );
			}
		}

		// Now place index.php files in all sub folders
		$folders = wpgs_scan_folders( $upload_path );
		foreach ( $folders as $folder ) {
			// Create index.php, if it doesn't exist
			if ( ! file_exists( $folder . 'index.php' ) ) {
				@file_put_contents( $folder . 'index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
			}
		}
		// Check for the files once per day
		set_transient( 'wpgs_check_protection_files', true, 3600 * 24 );
	}
}
add_action( 'admin_init', 'wpgs_create_protection_files' );

/**
 * Scans all folders inside of /uploads/wpgs
 *
 * @since 3.0
 * @return array $return List of files inside directory
 */
function wpgs_scan_folders( $path = '', $return = array() ) {
	$path = $path == ''? dirname( __FILE__ ) : $path;
	$lists = @scandir( $path );

	if ( ! empty( $lists ) ) {
		foreach ( $lists as $f ) {
			if ( is_dir( $path . DIRECTORY_SEPARATOR . $f ) && $f != "." && $f != ".." ) {
				if ( ! in_array( $path . DIRECTORY_SEPARATOR . $f, $return ) )
					$return[] = trailingslashit( $path . DIRECTORY_SEPARATOR . $f );

				wpgs_scan_folders( $path . DIRECTORY_SEPARATOR . $f, $return);
			}
		}
	}

	return $return;
}

/**
 * Retrieve the .htaccess rules to wp-content/uploads/wpgs/
 *
 * @since 3.0
 * @return string The htaccess rules
 */
function wpgs_get_htaccess_rules( $method = false ) {

	if( empty( $method ) )
		$method = wpgs_get_file_download_method();

	switch( $method ) :

		case 'redirect' :
			// Prevent directory browsing
			$rules = "Options -Indexes";
			break;

		case 'direct' :
		default :
			// Prevent directory browsing and direct access to all files, except images (they must be allowed for featured images / thumbnails)
			$rules = "Options -Indexes\n";
			$rules .= "deny from all\n";
			$rules .= "<FilesMatch '\.(jpg|png|gif)$'>\n";
			    $rules .= "Order Allow,Deny\n";
			    $rules .= "Allow from all\n";
			$rules .= "</FilesMatch>\n";
			break;

	endswitch;
	$rules = apply_filters( 'wpgs_protected_directory_htaccess_rules', $rules );
	return $rules;
}

/**
 * Get the file Download method
 *
 * @since 3.0
 * @return string The method to use for file downloads
 */
function wpgs_get_file_download_method() {
	global $wpgs_options;
	$method = isset( $wpgs_options['download_method'] ) ? $wpgs_options['download_method'] : 'direct';
	return apply_filters( 'wpgs_file_download_method', $method );
}
