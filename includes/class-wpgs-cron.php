<?php
/**
 * Cron
 *
 * @package     WPGS
 * @subpackage  Classes/Cron
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

/**
 * WPGS_Cron Class
 *
 * This class handles scheduled events
 *
 * @since 3.0
 */
class WPGS_Cron {
	/**
	 * Get things going
	 *
	 * @access public
	 * @since 3.0
	 * @see WPGS_Cron::weekly_events()
	 * @return void
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_schedules'   ) );
		add_action( 'wp',             array( $this, 'schedule_Events' ) );
	}

	/**
	 * Registers new cron schedules
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function add_schedules( $schedules = array() ) {
		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __( 'Once Weekly', 'wpgs' )
		);

		return $schedules;
	}

	/**
	 * Schedules our events
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function schedule_Events() {
		$this->weekly_events();
		$this->daily_events();
	}

	/**
	 * Schedule weekly events
	 *
	 * @access private
	 * @since 3.0
	 * @return void
	 */
	private function weekly_events() {
		if ( ! wp_next_scheduled( 'wpgs_weekly_cron' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'weekly', 'wpgs_weekly_cron' );
			do_action( 'wpgs_weekly_scheduled_events' );
		}
	}

	/**
	 * Schedule daily events
	 *
	 * @access private
	 * @since 3.0
	 * @return void
	 */
	private function daily_events() {
		if ( ! wp_next_scheduled( 'wpgs_daily_cron' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'daily', 'wpgs_daily_cron' );
			do_action( 'wpgs_daily_scheduled_events' );
		}
	}
}
$wpgs_cron = new WPGS_Cron;
