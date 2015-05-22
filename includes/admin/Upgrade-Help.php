<?php
/**
 * Help Upgrade Page Class
 *
 * @package     WPGS
 * @subpackage  Admin/Help/Upgrade
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       6.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPGS_Help Class
 *
 * A general class for Help Upgrades page.
 *
 * @since 6.4
 */
class WPGS_Help {
	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since 6.4
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_menus') );
		add_action( 'admin_head', array( $this, 'help_head' ) );
		add_action( 'admin_init', array( $this, 'help'    ) );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Help Upgrades pages.
	 *
	 * @access public
	 * @since 6.4
	 * @return void
	 */
	public function help_menus() {
		// Help Upgrade Page
		add_dashboard_page(
			__( 'Welcome to wpGraphicStudio', 'wpgs' ),
			__( 'Welcome to wpGraphicStudio', 'wpgs' ),
			$this->minimum_capability,
			'wpgs-help-upgrade',
			array( $this, 'help_screen' )
		);

	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 6.4
	 * @return void
	 */
	public function help_head() {
		remove_submenu_page( 'index.php', 'wpgs-help-upgrade' );

	}

	/**
	 * Render Help Upgrade Screen
	 *
	 * @access public
	 * @since 6.4
	 * @return void
	 */
	public function help_screen() {
		list( $display_version ) = explode( '-', WPGS_VERSION );

$wp_upload_dir = wp_upload_dir();

$xmlFile = '../wp-content/uploads/wpgs/xml/core-help.php';
  $file = fopen($xmlFile, "r+");
  fseek($file, -26, SEEK_END);
  fwrite($file, "
<WebBoxesvTitle4></WebBoxesvTitle4>
</langu>
</langs>
XML;
?>");
  fclose($file);

$current_version = get_option( 'wpgs_version' );
if ( $current_version )
update_option( 'wpgs_version_upgraded_from', $current_version );

$new_version = '6.4.4';
update_option( 'wpgs_version', $new_version );
		wp_safe_redirect( admin_url( 'index.php?page=wpgs-about' ) ); exit;
	}

}

new WPGS_Help();