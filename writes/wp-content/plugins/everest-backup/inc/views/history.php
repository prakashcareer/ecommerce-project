<?php
/**
 * Template file for displaying the list of previous backups.
 *
 * @package everest-backup
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_array( $args ) ) {
	return;
}

$everest_backup_history_table_obj = ! empty( $args['history_table_obj'] ) ? $args['history_table_obj'] : false;

if ( ! is_object( $everest_backup_history_table_obj ) ) {
	return;
}

$everest_backup_history_table_obj->prepare_items();

?>
<div class="wrap">
	<style>
		.everest-backup-modal-custom-history {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			z-index: 9999;
			text-align: center;
		}

		.everest-backup-modal-custom-history-content {
			background-color: white;
			border-radius: 5px;
			padding: 20px;
			width: 300px;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}

		.everest-backup-modal-custom-history-close {
			cursor: pointer;
			float: right;
			font-size: 20px;
			font-weight: bold;
		}

		.float-right{
			float: right;
		}
		.float-left{
			float: left;
		}
		.w-25{ width: 25%; }
		.w-75{ width: 75%; }

		.everest-backup-loader-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent white background */
			display: none;
			justify-content: center;
			align-items: center;
			z-index: 9999; /* Ensure it's above other content */
		}

		.everest-backup-loader {
			border: 8px solid #f3f3f3; /* Light grey */
			border-top: 8px solid #3498db; /* Blue */
			border-radius: 50%;
			width: 50px;
			height: 50px;
			animation: spin 2s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>
	<hr class="wp-header-end">

	<?php
		everest_backup_render_view( 'template-parts/header' );
	?>
	<div class="everest-backup-loader-overlay">
		<div class="everest-backup-loader"></div>
	</div>

	<main class="everest-backup-wrapper">
		<form id="everest-backup-container" method="get">
			<?php
			if ( empty( $args['proc_lock'] ) ) {
				?>
				<input type="hidden" name="page" value="<?php echo esc_attr( $args['page'] ); ?>">
				<?php
				$everest_backup_history_table_obj->display();
			} else {
				everest_backup_render_view( 'template-parts/proc-lock-info', $args['proc_lock'] );
			}
			?>
		</form>

		<?php everest_backup_render_view( 'template-parts/sidebar' ); ?>

		<!-- custom modal -->
		<div id="everestBackupCustomModal" class="everest-backup-modal-custom-history">
			<div class="everest-backup-modal-custom-history-content">
				<div class="w-75 float-left">
					<h2 id="everestBackupHeaderText">Choose a cloud platform</h2>
				</div>
				<div class="w-25 float-right">
					<span class="everest-backup-modal-custom-history-close everest-backup-close-modal">&times;</span>
				</div>
				<br>
				<p id="everest-backup-active-plugins" style="width: 100%;"></p>
				<h2 id="everestBackupFooterText"></h2>
				<button class="everest-backup-close-modal float-right">Close</button>
			</div>
		</div>
		<!-- custom modal -->

	</main>
</div>
