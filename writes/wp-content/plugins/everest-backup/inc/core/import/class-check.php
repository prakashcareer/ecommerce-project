<?php
/**
 * Check archive before starting import.
 *
 * @package EverestBackup
 */

namespace Everest_Backup\Core\Import;

use Everest_Backup\Core\Archiver;
use Everest_Backup\Core\Archiver_V2;
use Everest_Backup\Logs;
use Everest_Backup\Traits\Import;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check for file integrity.
 */
class Check {

	use Import;

	/**
	 * Prepare package.
	 */
	private static function prepare_package() {

		do_action( 'everest_backup_before_restore_init', self::$params );

		Logs::set_proc_stat(
			array(
				'log'      => 'info',
				'status'   => 'in-process',
				'progress' => 0,
				'message'  => __( 'Restore initialized. Preparing package.' ),
				'detail'   => __( 'Preparing package', 'everest-backup' ),
			)
		);

		if ( everest_backup_doing_rollback() ) {

			$file     = ! empty( self::$params['file'] ) ? self::$params['file'] : '';
			$filename = ! empty( self::$params['filename'] ) ? self::$params['filename'] : '';
			$cloud    = ! empty( self::$params['cloud'] ) ? self::$params['cloud'] : 'server';

			if ( ( 'server' !== $cloud ) && ( 'pcloud' !== $cloud ) && ( $file !== $filename ) ) {

				/**
				 * If we are doing rollback from cloud file.
				 */
				$filename = $file;
			}

			if ( everest_backup_doing_clone() && $file ) {

				/**
				 * If we are doing clone.
				 */
				$filename = $file;
			}

			$package = everest_backup_get_backup_full_path( $filename, false );
			$args    = apply_filters( 'everest_backup_filter_rollback_args', compact( 'package', 'filename', 'cloud' ) );

			self::$params['package'] = ! empty( $args['package'] ) ? $args['package'] : '';

		}
	}

	/**
	 * Run.
	 *
	 * @throws \Exception Exception.
	 */
	private static function run() {

		everest_backup_set_our_active_plugin_list();

		if ( get_transient( 'everest_backup_migrate_clone_download' ) ) {
			if ( ! defined( 'EVEREST_BACKUP_DOING_ROLLBACK' ) ) {
				define( 'EVEREST_BACKUP_DOING_ROLLBACK', true );
			}
		}

		if ( empty( self::$params['package'] ) ) {
			self::prepare_package();
		}

		Logs::set_proc_stat(
			array(
				'log'      => 'info',
				'status'   => 'in-process',
				'progress' => 0,
				'message'  => __( 'Restore initialized. Checking server vitals for restore.' ),
				/* translators: package name */
				'detail'   => sprintf( __( 'Restoring: %s', 'everest-backup' ), basename( self::$params['package'] ) ),
			)
		);

		sleep( 1 );

		if ( ! everest_backup_is_gzip( self::$params['package'] ) ) {
			$archiver = new Archiver_V2( self::$params['package'] );
		} else {
			$archiver = new Archiver( self::$params['package'] );
		}

		/**
		 * --------------------------------------
		 * Check total files and available spaces.
		 * ---------------------------------------
		 */

		$stats = $archiver->get_metadata( 'stats' );

		if ( empty( $stats ) ) {
			throw new \Exception( esc_html__( 'Uploaded backup is either corrupted or not supported. Metadata missing, aborting restore.', 'everest-backup' ) );
		}

		Logs::set_proc_stat(
			array(
				'status'   => 'in-process',
				'progress' => 2,
				'message'  => __( 'Restore initialized. Checking server vitals for restore.' ),
				/* translators: total files */
				'detail'   => sprintf( __( 'Total files to restore: %s. Checking available space...', 'everest-backup' ), esc_html( $stats['total'] ) ),
			)
		);

		if ( ! everest_backup_is_space_available( WP_CONTENT_DIR, absint( $stats['size'] ) ) ) {
			throw new \Exception( esc_html__( 'Required space not available, aborting restore.', 'everest-backup' ) );
		}

		Logs::set_proc_stat(
			array(
				'status'   => 'in-process',
				'progress' => 4,
				'message'  => __( 'Restore initialized. Checking server vitals for restore.' ),
				'detail'   => __( 'Space available', 'everest-backup' ),
			)
		);

		sleep( 1 );

		/**
		 * ------------------
		 * List excluded tags.
		 * ------------------
		 */

		$tags = (array) $archiver->get_metadata( 'tags' );

		if ( ! empty( $tags ) ) {
			Logs::set_proc_stat(
				array(
					'status'   => 'in-process',
					'progress' => 6,
					'message'  => __( 'Restore initialized. Checking server vitals for restore.' ),
					/* translators: tags */
					'detail'   => sprintf( __( 'Modules excluded: %s', 'everest-backup' ), esc_html( implode( ', ', $tags ) ) ),
				)
			);

			sleep( 1 );
		}

		/**
		 * -----------------------
		 * Check backup file age.
		 * -----------------------
		 */

		$config = $archiver->get_metadata( 'config' );

		Logs::set_proc_stat(
			array(
				'status'   => 'in-process',
				'progress' => 8,
				'message'  => __( 'Restore initialized. Checking server vitals for restore.' ),
				/* translators: date time */
				'detail'   => sprintf( __( 'Backup file created: %s ago', 'everest-backup' ), esc_html( human_time_diff( $config['FileInfo']['timestamp'] ) ) ),
			)
		);

		sleep( 1 );

		/**
		 * -------------------
		 * Check PHP version
		 * -------------------
		 */

		Logs::set_proc_stat(
			array(
				'status'   => 'in-process',
				'progress' => 10,
				'message'  => __( 'Restore initialized. Checking server vitals for restore.' ),
				'detail'   => __( 'Checking PHP version', 'everest-backup' ),
			)
		);

		sleep( 1 );

		$current_php_version = PHP_VERSION;
		$zip_php_version     = ! empty( $config['PHP']['Version'] ) ? $config['PHP']['Version'] : '';
		$is_comparable       = ( ( $current_php_version !== $zip_php_version ) && ( version_compare( $current_php_version, $zip_php_version, 'gt' ) ) );
		$is_minor_update     = $zip_php_version && $is_comparable ? everest_backup_version_compare( $current_php_version, $zip_php_version, 'gt', true ) : true;

		if ( ! $is_minor_update ) {

			/* translators: backup php version and current server php version */
			$detail = sprintf( __( 'Attention: You are restoring from PHP %1$s to %2$s', 'everest-backup' ), esc_html( $zip_php_version ), esc_html( $current_php_version ) );

			Logs::set_proc_stat(
				array(
					'log'      => 'warn',
					'status'   => 'in-process',
					'progress' => 10,
					'message'  => __( 'Restoration might fail... Major difference found in PHP version' ),
					'detail'   => $detail,
				)
			);

			Logs::warn( $detail );

			sleep( 3 );

		}

		$metadata = $archiver->get_metadatas();

		$metadata['Params']   = self::$params;
		$metadata['FilePath'] = self::$params['package'];

		self::writefile( 'ebwp-metadata.json', wp_json_encode( $metadata ) );

		$debug = everest_backup_get_settings( 'debug' );

		if ( ! empty( $debug['throw_error'] ) ) {
			throw new \Exception( esc_html__( 'This error is generated manually using Everest Backup debugger.', 'everest-backup' ) );
		}

		Logs::set_proc_stat(
			array(
				'log'      => 'info',
				'status'   => 'in-process',
				'progress' => 12,
				'message'  => __( 'All checks passed. Starting files extraction...' ),
				'next'     => 'extraction',
			)
		);

		deactivate_plugins( everest_backup_get_other_plugins(), true );
	}
}
