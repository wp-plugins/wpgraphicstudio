<?php
/**
 * Upgrade Screen
 *
 * @package     WPGS
 * @subpackage  Admin/Upgrades
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Render Upgrades Screen
 *
 * @since 3.0
 * @return void
*/
function wpgs_upgrades_screen() {
	$action = isset( $_GET['wpgs-upgrade'] ) ? sanitize_text_field( $_GET['wpgs-upgrade'] ) : '';
	$step   = isset( $_GET['step'] )        ? absint( $_GET['step'] )                     : 1;
	$total  = isset( $_GET['total'] )       ? absint( $_GET['total'] )                    : false;
	$custom = isset( $_GET['custom'] )      ? absint( $_GET['custom'] )                   : 0;
	$number = isset( $_GET['number'] )      ? absint( $_GET['number'] )                   : 100;
	$steps  = round( ( $total / $number ), 0 );

	$doing_upgrade_args = array(
		'page'        => 'wpgs-upgrades',
		'wpgs-upgrade' => $action,
		'step'        => $step,
		'total'       => $total,
		'custom'      => $custom,
		'steps'       => $steps
	);
	update_option( 'wpgs_doing_upgrade', $doing_upgrade_args );
	if ( $step > $steps ) {
		// Prevent a weird case where the estimate was off. Usually only a couple.
		$steps = $step;
	}
	?>
	<div class="wrap">
		<h2><?php _e( 'wpGraphicStudio - Upgrades', 'wpgs' ); ?></h2>

		<?php if( ! empty( $action ) ) : ?>

			<div id="wpgs-upgrade-status">
				<p><?php _e( 'The upgrade process has started, please be patient. This could take several minutes. You will be automatically redirected when the upgrade is finished.', 'wpgs' ); ?></p>

				<?php if( ! empty( $total ) ) : ?>
					<p><strong><?php printf( __( 'Step %d of approximately %d running', 'edd' ), $step, $steps ); ?></strong></p>
				<?php endif; ?>
			</div>
			<script type="text/javascript">
				setTimeout(function() { document.location.href = "index.php?wpgs_action=<?php echo $action; ?>&step=<?php echo $step; ?>&total=<?php echo $total; ?>&custom=<?php echo $custom; ?>"; }, 250);
			</script>

		<?php else : ?>

			<div id="wpgs-upgrade-status">
				<p>
					<?php _e( 'The upgrade process has started, please be patient. This could take several minutes. You will be automatically redirected when the upgrade is finished.', 'wpgs' ); ?>
					<img src="<?php echo WPGS_PLUGIN_URL . '/assets/images/loading.gif'; ?>" id="wpgs-upgrade-loader"/>
				</p>
			</div>
			<script type="text/javascript">
				jQuery( document ).ready( function() {
					// Trigger upgrades on page load
					var data = { action: 'wpgs_trigger_upgrades' };
					jQuery.post( ajaxurl, data, function (response) {
						if( response == 'complete' ) {
							jQuery('#wpgs-upgrade-loader').hide();
							document.location.href = 'index.php?page=wpgs-about'; // Redirect to the welcome page
						}
					});
				});
			</script>

		<?php endif; ?>

	</div>
	<?php
}
