<?php
/**
 * Core export setup class file.
 *
 * @package Everest_Backup
 */

namespace Everest_Backup\Core\Export;

use Everest_Backup\Backup_Directory;
use Everest_Backup\Logs;
use Everest_Backup\Traits\Export;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup class.
 */
class Setup {

	use Export;

	/**
	 * Run.
	 *
	 * @throws \Exception Required space not available.
	 */
	private static function run() {

		Backup_Directory::init()->create();

		if ( ! everest_backup_is_space_available( EVEREST_BACKUP_BACKUP_DIR_PATH, MB_IN_BYTES * 10, false ) ) {
			throw new \Exception( esc_html__( 'Required space not available, aborting process.', 'everest-backup' ) );
		}

		Logs::init( 'backup' );

		Logs::info( __( 'Backup started', 'everest-backup' ) );

		Logs::set_proc_stat(
			array(
				'status'   => 'in-process',
				'progress' => 7,
				'message'  => __( 'Backup started. Creating config file.', 'everest-backup' ),
			),
		);

		Logs::info( __( 'Creating config file', 'everest-backup' ) );

		global $table_prefix, $wp_version, $wpdb;

		$config = array();

		$config['Params'] = self::$params;

		$config['FileInfo'] = array(
			'uniqid'    => everest_backup_current_request_id(),
			'timestamp' => everest_backup_current_request_timestamp(),
			'filename'  => self::get_archive_name(),
		);

		// Set site URL.
		$config['SiteURL'] = site_url();

		// Set home URL.
		$config['HomeURL'] = home_url();

		$config['NavMenus'] = get_nav_menu_locations();

		$config['Widgets'] = get_option( 'sidebars_widgets', array() );

		/**
		 * Set everest backup info.
		 */
		$config['Plugin'] = array(
			'Version'         => EVEREST_BACKUP_VERSION,
			'InstalledAddons' => everest_backup_installed_addons(),
			'ActiveAddons'    => everest_backup_installed_addons( 'active' ),
		);

		// Set WordPress version and content.
		$config['WordPress'] = array(
			'Multisite'  => is_multisite(),
			'Version'    => $wp_version,
			'Content'    => WP_CONTENT_DIR,
			'Plugins'    => WP_PLUGIN_DIR,
			'Themes'     => get_theme_root(),
			'UploadsDIR' => everest_backup_get_uploads_dir(),
			'UploadsURL' => everest_backup_get_uploads_url(),
		);

		$config['Database'] = array(
			'Version' => $wpdb->db_version(),
			'Charset' => defined( 'DB_CHARSET' ) ? DB_CHARSET : '',
			'Collate' => defined( 'DB_COLLATE' ) ? DB_COLLATE : '',
			'Prefix'  => $table_prefix,
		);

		$config['Template']      = get_option( 'template' );
		$config['Stylesheet']    = get_option( 'stylesheet' );
		$config['ActivePlugins'] = get_option( 'active_plugins', array() );

		$config['PHP'] = array(
			'Version' => PHP_VERSION,
			'System'  => PHP_OS,
			'Integer' => PHP_INT_SIZE,
		);

		$config['Server'] = array(
			'.htaccess' => everest_backup_str2hex( everest_backup_get_htaccess() ),
		);

		self::writefile( EVEREST_BACKUP_CONFIG_FILENAME, wp_json_encode( $config ) );

		Logs::info( __( 'Config file created', 'everest-backup' ) );

		self::addtolist( everest_backup_current_request_storage_path( EVEREST_BACKUP_CONFIG_FILENAME ) );

		sleep( 1 );

		$debug = everest_backup_get_settings( 'debug' );

		if ( ! empty( $debug['throw_error'] ) ) {
			throw new \Exception( esc_html__( 'This error is generated manually using Everest Backup debugger.', 'everest-backup' ) );
		}

		self::set_next( 'database' );
	}
}
