<?php
/**
 * Plugin Name: wpGraphicStudio
 * Plugin URI: http://wpgraphicstudio.com
 * Description: The internets only premier, premium graphics development and distribution system for wordpress...
 * Author: wpGraphicStudio
 * Author URI: http://wpgraphicstudio.com
 * Version: 6.4.7
 * Text Domain: wpgs
 * Domain Path: languages
 *
 *
 *
 * @package WPGS
 * @category Core
 * @author wpGraphicStudio
 * @version 6.4.6
 */
 // Exit if accessed directly
 if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'wp_Graphic_Studio' ) ) :
/**
 * Main wp_Graphic_Studio Class
 *
 * @since 3.0
 */

final class wp_Graphic_Studio {
	/** Singleton *************************************************************/

	/**
	 * @var wp_Graphic_Studio The one true wp_Graphic_Studio
	 * @since 3.0
	 */
	private static $instance;

	/**
	 * WPGS API Object
	 *
	 * @var object
	 * @since 3.0
	 */
	public $api;


	/**
	 * WPGS HTML Session Object
	 *
	 * This holds graphic items, graphic sessions, and anything else stored in the session
	 *
	 *
	 * @var object
	 * @since 3.0
	 */
	public $session;

	/**
	 * WPGS HTML Element Helper Object
	 *
	 * @var object
	 * @since 3.0
	 */
	public $html;

	/**
	 * Main wp_Graphic_Studio Instance
	 *
	 * Insures that only one instance of wpGraphicStudio exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 3.0
	 * @static
	 * @staticvar array $instance
	 * @uses wp_Graphic_Studio::setup_globals() Setup the globals needed
	 * @uses wp_Graphic_Studio::includes() Include the required files
	 * @uses wp_Graphic_Studio::setup_actions() Setup the hooks and actions
	 * @see WPGS()
	 * @return The one true wp_Graphic_Studio
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof wp_Graphic_Studio ) ) {
			self::$instance = new wp_Graphic_Studio;
			self::$instance->setup_constants();
			self::$instance->includes();
			//self::$instance->load_textdomain();
			//self::$instance->roles = new WPGS_Roles();
			//self::$instance->api = new WPGS_API();
			self::$instance->session = new WPGS_Session();
			//self::$instance->html = new WPGS_HTML_Elements();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 3.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgs' ), '3.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 3.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgs' ), '3.0' );
	}

	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @since 3.0
	 * @return void
	 */
	private function setup_constants() {
		// Plugin version
		if ( ! defined( 'WPGS_VERSION' ) )
			define( 'WPGS_VERSION', '6.4.7' );

		// Plugin Folder Path
		if ( ! defined( 'WPGS_PLUGIN_DIR' ) )
			define( 'WPGS_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );

		// Plugin Folder URL
		if ( ! defined( 'WPGS_PLUGIN_URL' ) )
			define( 'WPGS_PLUGIN_URL', plugin_dir_url( WPGS_PLUGIN_DIR ) . basename( dirname( __FILE__ ) ) . '/' );

		// Plugin Root File
		if ( ! defined( 'WPGS_PLUGIN_FILE' ) )
			define( 'WPGS_PLUGIN_FILE', __FILE__ );
	}

	/**
	 * Include required files
	 *
	 * @access private
	 * @since 3.0
	 * @return void
	 */
	private function includes() {
			global $wpgs_options;

		require_once WPGS_PLUGIN_DIR . 'includes/actions.php';
		require_once WPGS_PLUGIN_DIR . 'includes/install.php';
		require_once WPGS_PLUGIN_DIR . 'includes/template-functions.php';
		require_once WPGS_PLUGIN_DIR . 'includes/class-wpgs-cron.php';
		require_once WPGS_PLUGIN_DIR . 'includes/class-wpgs-session.php';
		require_once WPGS_PLUGIN_DIR . 'includes/misc-functions.php';
		require_once WPGS_PLUGIN_DIR . 'includes/class-wpgs-roles.php';
		require_once WPGS_PLUGIN_DIR . 'includes/scripts.php';
		require_once WPGS_PLUGIN_DIR . 'includes/graphic-directory-functions.php';
		require_once WPGS_PLUGIN_DIR . 'includes/post-types.php';
		//require_once WPGS_PLUGIN_DIR . 'includes/admin/upgrades/upgrade-functions.php';
		//require_once WPGS_PLUGIN_DIR . 'includes/admin/upgrades/upgrades.php';

		if( is_admin() ) {
			require_once WPGS_PLUGIN_DIR . 'includes/admin/welcome.php';
			//require_once WPGS_PLUGIN_DIR . 'includes/admin/Upgrade-Language.php';
		} else {
			require_once WPGS_PLUGIN_DIR . 'includes/shortcodes.php';
		}
	}

}

endif; // End if class_exists check

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpgs_settings_action_links' );

function wpgs_settings_action_links( $wpgslinks ) {
   $wpgslinks[] = '<a href="'. get_admin_url(null, 'admin.php?page=wpgs-core-settings') .'">Settings</a>';
   $wpgslinks[] = '<a href="http://wpgraphicstudio.com" target="_blank">Add-Ons</a>';
   return $wpgslinks;
}
/**
 * The main function responsible for returning the one true wp_Graphic_Studio
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wpgs = WPGS(); ?>
 *
 * @since 3.0
 * @return object The one true wp_Graphic_Studio Instance
 */
function WPGS() {
	return wp_Graphic_Studio::instance();
}
// Get WPGS Running
WPGS();