<?php
/**
 * Handle file extraction during import.
 *
 * @package EverestBackup
 */

namespace Everest_Backup\Core\Import;

use Everest_Backup\Core\Archiver;
use Everest_Backup\Core\Archiver_V2;
use Everest_Backup\Filesystem;
use Everest_Backup\Logs;
use Everest_Backup\Traits\Import;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extraction class.
 */
class Extraction {

	use Import;

	/**
	 * For normalizing file contents.
	 *
	 * @param string $filepath File Path.
	 * @param array  $find_and_replace File Path.
	 */
	private static function normalize_file_contents( $filepath, $find_and_replace ) {

		if ( false !== strpos( $filepath, LANGDIR ) ) {
			// Bail if we are inside language directory.
			return;
		}

		if ( ! is_file( $filepath ) ) {
			return;
		}

		if ( ! in_array( pathinfo( $filepath, PATHINFO_EXTENSION ), array( 'css', 'js', 'json', 'txt' ) ) ) { //phpcs:ignore
			return;
		}

		return @file_put_contents( $filepath, strtr( @file_get_contents( $filepath ), $find_and_replace ) ); //phpcs:ignore
	}

	/**
	 * Run function.
	 *
	 * @param array $params Parameters.
	 * @throws \Exception Exception.
	 */
	private static function run( $params ) {

		$metadata = self::get_metadata();

		if ( empty( $metadata['FilePath'] ) ) {
			throw new \Exception( esc_html__( 'Archive file path missing from metadata. Aborting restore.', 'everest-backup' ) );
		}

		Logs::info( __( 'Restoring files', 'everest-backup' ) );

		$timer_start = time();

		$stats = $metadata['stats'];

		if ( everest_backup_is_gzip( $metadata['FilePath'] ) ) {
			$archiver = new Archiver( $metadata['FilePath'] );
		} else {
			$archiver = new Archiver_V2( $metadata['FilePath'] );
		}

		if ( $archiver->open( 'rb' ) ) {

			$find_and_replace = self::get_find_replace();

			$type   = ''; // Current folder type, "others" means wp-content folder root.
			$path   = '';
			$count  = 1;
			$handle = false;

			$current_position = 0;
			if ( ! empty( $params['current_position'] ) ) {
				$current_position = $params['current_position'];
			}
			if ( ! empty( $params['count'] ) ) {
				$count = $params['count'];
			}

			$time       = time();
			$start_time = 0;

			fseek( $archiver->get_ziphandle(), $current_position ); // Start file read from previous position.

			if ( ! empty( $params['current_file_name'] ) && ! empty( $params['current_file_seek'] ) ) {
				$path = $params['current_file_name'];
				if ( file_exists( $path ) ) {
					$handle = fopen( $path, 'ab' ); //phpcs:ignore
					if ( ! $handle ) {
						throw new \Exception( 'Could not open file for append: ' . esc_html( $path ) );
					}
					$start_time = microtime( true );
				} else {
					throw new \Exception( 'Extraction error. Created file not found.' );
				}
				if ( false !== strpos( $path, 'ebwp-files' ) ) {
					$type = 'ebwp-files';
				} else {
					$_type = strstr( $path, '/', true );
					$type  = $_type ? $_type : 'others';
				}
			}

			while ( ! feof( $archiver->get_ziphandle() ) ) {
				$line = fgets( $archiver->get_ziphandle() );

				/**
				 * First step for extraction.
				 * ===============================================
				 * Find file start.
				 * If found, then set handle and move to next line.
				 * ===============================================
				 */
				if ( 0 === strpos( $line, 'EBWPFILE_START:' ) ) {
					$path = trim( str_replace( 'EBWPFILE_START:', '', $line ) );
					$path = str_replace( chr( 0 ), '', $path ); // Fix for null byte issue.

					if ( false !== strpos( $path, 'ebwp-files' ) ) {
						$type = 'ebwp-files';
						$path = everest_backup_current_request_storage_path( $path );
					} else {
						$_type = strstr( $path, '/', true );

						$type = $_type ? $_type : 'others';
						$path = wp_normalize_path( WP_CONTENT_DIR . '/' . $path );
					}

					$dir = dirname( $path );

					if ( ! is_dir( $dir ) ) {
						Filesystem::init()->mkdir_p( $dir );
					}

					if ( file_exists( $path ) ) {
						@unlink( $path ); //phpcs:ignore
					}

					if ( 'sql' === pathinfo( $path, PATHINFO_EXTENSION ) ) {
						$path .= '-temp';
					}

					$handle = fopen( $path, 'wb' ); //phpcs:ignore
					if ( ! $handle ) {
						throw new \Exception( 'Unable to create file: ' . esc_html( $path ) );
					}
					$start_time = microtime( true );
					continue;
				}

				/**
				 * Third step for extraction.
				 * ========================================
				 * If we are end of current extracting file,
				 * then close the handle and release memory.
				 * If the archive still has other lines,
				 * then move to new line.
				 * ========================================
				 */
				if ( 0 === strpos( $line, 'EBWPFILE_END:' ) ) {
					if ( empty( $params['current_file_name'] ) || $path !== $params['current_file_name'] ) {
						if ( is_resource( $handle ) ) {

							/**
							 * Lets truncate the extra line that is being added at the end of the restored file.
							 * This is also the fix for issue: #217
							 */
							$curr_pos = ftell( $handle );

							if ( ! ! $curr_pos ) {
								// Remove additional last line which gets added during archive process.
								ftruncate( $handle, $curr_pos - 1 );
							}
						}
					}

					/**
					 * Fix few paths, or static values in files of wp-contents or uploads folders.
					 */
					switch ( $type ) {
						case 'uploads':
						case 'others':
							self::normalize_file_contents( $path, $find_and_replace );
							break;

						default:
							break;
					}

					++$count;

					$calc     = ( $count / $stats['total'] ) * 100;
					$progress = $calc > 100 ? 100 : $calc;

					$time_taken = microtime( true ) - $start_time;

					Logs::set_proc_stat(
						array(
							'status'     => 'in-process',
							'progress'   => round( $progress * 0.3 + 30, 2 ), // At the end, it is always going to be 60%.
							'message'    => sprintf(
								/* translators: */
								__( 'Restoring files [ %1$s ] : %2$d%% completed', 'everest-backup' ),
								esc_html( ucwords( str_replace( '-', ' ', $type ) ) ),
								esc_html( $progress )
							),
							/* translators: */
							'detail'     => sprintf( __( 'Restored: %1$s out of %2$s', 'everest-backup' ), esc_html( $count ), esc_html( $stats['total'] ) ),
							'params'     => $params,
							'time_taken' => $time_taken,
						)
					);

					$type = '';
					$line = '';
					$path = '';

					@fclose( $handle ); //phpcs:ignore
					$handle = false;

					continue;
				}

				/**
				 * Second step for extraction.
				 * ===============================
				 * As long as our handle is set,
				 * keep writing data of that file.
				 * ===============================
				 */
				if ( $handle ) {
					fwrite( $handle, $line ); //phpcs:ignore

					if ( ( time() - $time ) > 10 ) {

						$calc     = ( $count / $stats['total'] ) * 100;
						$progress = $calc > 100 ? 100 : $calc;

						$time_taken = microtime( true ) - $start_time;

						$current_position = ftell( $archiver->get_ziphandle() );

						Logs::set_proc_stat(
							array(
								'status'            => 'in-process',
								'progress'          => round( $progress * 0.3 + 30, 2 ), // At the end, it is always going to be 60%.
								'message'           => sprintf(
									/* translators: */
									__( 'Restoring files [ %1$s ] : %2$d%% completed', 'everest-backup' ),
									esc_html( ucwords( str_replace( '-', ' ', $type ) ) ),
									esc_html( $progress )
								),
								/* translators: */
								'detail'            => sprintf( __( 'Restored: %1$s out of %2$s', 'everest-backup' ), esc_html( $count ), esc_html( $stats['total'] ) ),
								'current_position'  => $current_position,
								'current_file_name' => $path,
								'current_file_seek' => ftell( $handle ),
								'next'              => 'extraction', // Set next to same.
								'count'             => $count, // current count.
								'time_taken'        => $time_taken,
							)
						);
						return;
					}
				}
			}

			$archiver->close();

		}

		$general_settings     = everest_backup_get_settings( 'general' );
		$delete_after_restore = ! empty( $general_settings['delete_after_restore'] ) ? $general_settings['delete_after_restore'] : 'yes';

		Logs::set_proc_stat(
			array(
				'log'      => 'info',
				'status'   => 'in-process',
				'progress' => 60,
				'message'  => sprintf(
					/* translators: total files and time taken */
					__( 'Restored %1$d files. Time taken: %2$s', 'everest-backup' ),
					esc_html( $stats['total'] ),
					esc_html( human_time_diff( $timer_start ) )
				),
				'detail'   => ( 'yes' === $delete_after_restore ) ? __( 'Removing uploaded archive file', 'everest-backup' ) : __( 'Keeping uploaded archive file.', 'everest-backup' ),
				'next'     => 'wrapup', // Set next.
			)
		);

		if ( 'yes' === $delete_after_restore ) {
			unlink( $metadata['FilePath'] ); //phpcs:ignore
		}
	}
}
