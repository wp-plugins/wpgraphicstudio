<?php
/**
 * WPGS Session
 *
 * This is a wrapper class for WP_Session / PHP $_SESSION and handles the storage of cart items, purchase sessions, etc
 *
 * @package     WPGS
 * @subpackage  Classes/Session
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPGS_Session Class
 *
 * @since 3.0
 */
class WPGS_Session {

	/**
	 * Holds our session data
	 *
	 * @var array
	 * @access private
	 * @since 3.0
	 */
	private $session = array();


	/**
	 * Whether to use PHP $_SESSION or WP_Session
	 *
	 * PHP $_SESSION is opt-in only by defining the WPGS_USE_PHP_SESSIONS constant
	 *
	 * @var bool
	 * @access private
	 * @since 3.0
	 */
	private $use_php_sessions = false;


	/**
	 * Get things started
	 *
	 * Defines our WP_Session constants, includes the necessary libraries and
	 * retrieves the WP Session instance
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function __construct() {

		$this->use_php_sessions = defined( 'WPGS_USE_PHP_SESSIONS' ) && WPGS_USE_PHP_SESSIONS;

		if( $this->use_php_sessions ) {

			// Use PHP SESSION (must be enabled via the WPGS_USE_PHP_SESSIONS constant)

			if( ! session_id() )
				add_action( 'init', 'session_start', -1 );

		} else {

			// Use WP_Session (default)

			if ( ! defined( 'WP_SESSION_COOKIE' ) )
				define( 'WP_SESSION_COOKIE', 'wordpress_wp_session' );

			if ( ! class_exists( 'Recursive_ArrayAccess' ) )
				require_once WPGS_PLUGIN_DIR . 'includes/libraries/class-recursive-arrayaccess.php';

			if ( ! class_exists( 'WP_Session' ) ) {
				require_once WPGS_PLUGIN_DIR . 'includes/libraries/class-wp-session.php';
				require_once WPGS_PLUGIN_DIR . 'includes/libraries/wp-session.php';
			}

		}

		if ( empty( $this->session ) && ! $this->use_php_sessions )
			add_action( 'plugins_loaded', array( $this, 'init' ) );
		else
			add_action( 'init', array( $this, 'init' ) );
	}


	/**
	 * Setup the WP_Session instance
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function init() {

		if( $this->use_php_sessions )
			$this->session = isset( $_SESSION['wpgs'] ) && is_array( $_SESSION['wpgs'] ) ? $_SESSION['wpgs'] : array();
		else
			$this->session = WP_Session::get_instance();

		return $this->session;
	}


	/**
	 * Retrieve session ID
	 *
	 * @access public
	 * @since 3.0
	 * @return string Session ID
	 */
	public function get_id() {
		return $this->session->session_id;
	}


	/**
	 * Retrieve a session variable
	 *
	 * @access public
	 * @since 3.05
	 * @param string $key Session key
	 * @return string Session variable
	 */
	public function get( $key ) {
		$key = sanitize_key( $key );
		return isset( $this->session[ $key ] ) ? maybe_unserialize( $this->session[ $key ] ) : false;
	}


	/**
	 * Set a session variable
	 *
	 * @access public
	 * @since 3.0
	 * @param string $key Session key
	 * @param string $variable Session variable
	 * @return array Session variable
	 */
	public function set( $key, $value ) {
		$key = sanitize_key( $key );

		if ( is_array( $value ) )
			$this->session[ $key ] = serialize( $value );
		else
			$this->session[ $key ] = $value;

		if( $this->use_php_sessions )
			$_SESSION['wpgs'] = $this->session;

		return $this->session[ $key ];
	}
}