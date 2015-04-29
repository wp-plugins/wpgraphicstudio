<?php
/**
 * Weclome Page Class
 *
 * @package     WPGS
 * @subpackage  Admin/Welcome
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPGS_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 3.0
 */
class WPGS_Welcome {
	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since 3.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function admin_menus() {
		// About Page
		add_dashboard_page(
			__( 'Welcome to wpGraphicStudio', 'wpgs' ),
			__( 'Welcome to wpGraphicStudio', 'wpgs' ),
			$this->minimum_capability,
			'wpgs-about',
			array( $this, 'about_screen' )
		);

		// Credits Page
		add_dashboard_page(
			__( 'Welcome to wpGraphicStudio', 'wpgs' ),
			__( 'Welcome to wpGraphicStudio', 'wpgs' ),
			$this->minimum_capability,
			'wpgs-credits',
			array( $this, 'credits_screen' )
		);
	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'wpgs-about' );
		remove_submenu_page( 'index.php', 'wpgs-credits' );

		?>
		<style type="text/css" media="screen">
		/*<![CDATA[*/
		.wpgs-badge {
			padding-top: 150px;
			height: 52px;
			width: 185px;
			color: #666;
			font-weight: bold;
			font-size: 14px;
			text-align: center;
			text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
			margin: 0 -5px;
		}

		.about-wrap .wpgs-badge {
			position: absolute;
			top: 0;
			right: 0;
		}

		.wpgs-welcome-screenshots {
			float: right;
			margin-left: 10px!important;
		}
		/*]]>*/
		</style>
		<?php
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function about_screen() {
		list( $display_version ) = explode( '-', WPGS_VERSION );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to wpGraphicStudio %s', 'wpgs' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for updating to the latest version! wpGraphicStudio %s is ready to make your online graphic creation faster, safer and better!', 'wpgs' ), $display_version ); ?></div>
			<div class="wpgs-badge"><?php printf( __( 'Version %s', 'wpgs' ), $display_version ); ?></div>

			<h2 class="nav-tab-wrapper">
				<div class="nav-tab nav-tab-active">
					<?php _e( "What's New", 'wpgs' ); ?>
				</div>
			</h2>

			<div class="changelog">
				<h3><?php _e( 'Introducing wpGrpahicStudio for Windows PC', 'wpgs' ); ?></h3>

				<div class="feature-section">

					<a href="http://www.wpgraphicstudio.com/downloads/wpgraphicstudio-for-windows-pc/"><img src="<?php echo WPGS_PLUGIN_URL . 'assets/images/screenshots/wpgraphicstudio-for-windows.png'; ?>" border="0" class="wpgs-welcome-screenshots"/></a>

					<h4><?php _e( 'Create and generate the same gaphics from your desktop', 'wpgs' ); ?></h4>
					<p><?php _e( 'Create and generate the same graphics as in wpGraphicStudio but from the desktop of your Windows PC or Laptop.<br>
					* No internet connection<br>
					* No usernames and passwords<br>
					* No membership fees<br>
					* Unlimited usage<br>
					* Stocked with more than 30 wpGraphicStudio modules and more on the way.', 'wpgs' ); ?></p>

				</div>

				<h3><?php _e( 'New Female Mascots Generator', 'wpgs' ); ?></h3>

				<div class="feature-section">

					<a href="http://www.wpgraphicstudio.com/downloads/female-mascot-module/"><img src="<?php echo WPGS_PLUGIN_URL . 'assets/images/screenshots/female-mascot-featured.png'; ?>" border="0" class="wpgs-welcome-screenshots"/></a>

					<h4><?php _e( 'Create Custom Female Mascots', 'wpgs' ); ?></h4>
					<p><?php _e( 'Add female mascots to your product line up of our mascot generators. Select from various options such as facial expressions, hair style/color, standing, sitting, skin tone, hand/arm gestures accessories and more..', 'wpgs' );  ?></p>

					<h4><?php _e( 'When In Demand, Be On Demand', 'wpgs' ); ?></h4>
					<p><?php _e( 'Having all the top, premium add on modules on your site will be sure your site is on demand when those graphic types and styles are in demand. With a constantly changing, evolving, updating and expanding line of graphic generators and development tools you will be sure to have what is needed, when it is needed.', 'wpgs' );  ?></p>

				</div>
			</div>

				</div>

			<div class="return-to-dashboard">
				<a href="http://wpgraphicstudio.com/modules/"><?php _e( 'Go to wpGraphicStudio Add On Modules', 'wpgs' ); ?></a>
			</div>
		<?php
	}

     /**
	 * Sends user to the Welcome page on first activation of WPGS as well as each
	 * time WPGS is upgraded to a new version
	 *
	 * @access public
	 * @since 3.0
	 * @global $wpgs_options Array of all the WPGS Options
	 * @return void
	 */
	public function welcome() {
		global $wpgs_options;

		// Bail if no activation redirect
		if ( ! get_transient( '_wpgs_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_wpgs_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		wp_safe_redirect( admin_url( 'index.php?page=wpgs-about' ) ); exit;
	}
}

new WPGS_Welcome();