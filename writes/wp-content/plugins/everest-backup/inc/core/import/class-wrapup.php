<?php
/**
 * Wrap up archive import.
 *
 * @package EverestBackup
 */

namespace Everest_Backup\Core\Import;

use Everest_Backup\Filesystem;
use Everest_Backup\Logs;
use Everest_Backup\Modules\Import_Database;
use Everest_Backup\Traits\Import;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wrap up import.
 */
class Wrapup {

	use Import;

	/**
	 * Import databases.
	 *
	 * @param array $db_configs Database configs.
	 */
	private static function import_databases( $db_configs ) {

		if ( empty( $db_configs['Tables'] ) ) {
			return;
		}

		$database_files = Filesystem::init()->list_files( everest_backup_current_request_storage_path( 'ebwp-files/ebwp-database' ) );

		if ( empty( $database_files ) ) {
			return;
		}

		if ( is_array( $database_files ) && ! empty( $database_files ) ) {

			Logs::info( 'Importing databases', 'everest-backup' );

			$total_tables = count( $database_files );
			$find_replace = self::get_find_replace();

			foreach ( $database_files as $current_key => $database_file ) {

				$progress = ( ( $current_key + 1 ) / $total_tables ) * 100;

				$proc_stat_args = array(
					'status'   => 'in-process',
					'progress' => round( $progress * 0.25 + 65, 2 ), // At the end, it is always going to be 90%.
					'message'  => sprintf(
						/* translators: progress, current table number and total tables. */
						__( 'Importing database: %1$d%% completed [ %2$s out of %3$s ]', 'everest-backup' ),
						esc_html( $progress ),
						esc_html( $current_key + 1 ),
						esc_html( $total_tables )
					),
				);

				$import_database = new Import_Database( $database_file, $db_configs['Tables'], $find_replace );
				$import_database->import_table(
					function ( $query_count ) use ( $proc_stat_args ) {
						/* translators: query count. */
						$proc_stat_args['detail'] = sprintf( __( 'Queries count: %s', 'everest-backup' ), $query_count );
						return Logs::set_proc_stat( $proc_stat_args );
					}
				);

				/**
				 * Remove the imported database files.
				 */
				unlink( $database_file ); //phpcs:ignore
			}
		}

		update_option( 'template', '' );
		update_option( 'stylesheet', '' );
		update_option( 'active_plugins', array() );
	}

	/**
	 * Run.
	 */
	private static function run() {

		$general_settings     = everest_backup_get_settings( 'general' );
		$delete_after_restore = ! empty( $general_settings['delete_after_restore'] ) ? $general_settings['delete_after_restore'] : 'yes';

		Logs::set_proc_stat(
			array(
				'log'      => 'info',
				'status'   => 'in-process',
				'progress' => 65,
				'message'  => __( 'Restoration almost complete...', 'everest-backup' ),
				'detail'   => 'yes' === $delete_after_restore ? __( 'Uploaded archive file removed', 'everest-backup' ) : __( 'Uploaded archive file kept', 'everest-backup' ),
			)
		);

		$metadata = self::get_metadata();

		if ( empty( $metadata['config'] ) ) {
			return;
		}

		if ( ! empty( $metadata['config']['Database'] ) ) {
			self::import_databases( $metadata['config']['Database'] );
		}

		/**
		 * Activate themes.
		 */
		Logs::info( 'Activating theme', 'everest-backup' );
		wp_clean_themes_cache();
		switch_theme( $metadata['config']['Stylesheet'] );

		/**
		 * Activate plugins.
		 */
		Logs::info( 'Activating plugins', 'everest-backup' );
		wp_clean_plugins_cache();
		$active_plugins = ! empty( $metadata['config']['ActivePlugins'] ) ? everest_backup_filter_plugin_list( $metadata['config']['ActivePlugins'] ) : array();
		activate_plugins( $active_plugins, '', false, true );

		if ( isset( $metadata['config']['NavMenus'] ) ) {
			Logs::info( 'Setting up navigation menus', 'everest-backup' );
			set_theme_mod( 'nav_menu_locations', $metadata['config']['NavMenus'] );
		}

		if ( isset( $metadata['config']['Widgets'] ) ) {
			Logs::info( 'Setting up widgets', 'everest-backup' );
			update_option( 'sidebars_widgets', $metadata['config']['Widgets'] );
		}

		if ( ! empty( $metadata['config']['Plugin']['ActiveAddons'] ) ) {
			activate_plugins( $metadata['config']['Plugin']['ActiveAddons'], '', false, true );
		}

		everest_backup_activate_our_plugins();

		Logs::info( 'Flushing cache and clearing temporary files', 'everest-backup' );

		if ( class_exists( '\LiteSpeed\Purge' ) ) {
			\LiteSpeed\Purge::purge_all();
		}

		flush_rewrite_rules();
		everest_backup_elementor_cache_flush();

		if ( empty( $metadata['config']['Database'] ) ) {
			wp_clear_auth_cookie();
		}

		Logs::done( __( 'Restore completed.', 'everest-backup' ) );

		do_action( 'everest_backup_after_restore_done', $metadata );

		everest_backup_send_success();
	}
}
