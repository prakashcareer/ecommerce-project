<?php

/*
Plugin Name: Theme Freesia Demo Import
Plugin URI: https://themefreesia.com/plugins/theme-freesia-demo-import
Description: Import your content, widgets and theme settings with one click. While activating Theme Freesia Demo Import Plugin you must deactivate One Click demo import plugins. You can't activate both plugin at the same time. After Theme Freesia demo importer plugin done its job. We recommended you to deactivate the plugins but it has done its job already.
Version: 3.0
Author: Theme Freesia
Author URI: https://themefreesia.com
License: GPL3
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$arise_plus = class_exists('Arise_Plus_Features');
$freesiaempire_plus = class_exists('Freesia_Empire_Plus_Features');
$edge_plus = class_exists('Edge_Plus_Features');
$pixgraphy_plus = class_exists('Pixgraphy_Plus_Features');
$event_plus = class_exists('Event_Plus_Features');
$excellent_plus = class_exists('Excellent_Plus_Features');
$idyllic_plus = class_exists('Idyllic_Plus_Features');
$storexmas_plus = class_exists('StoreXmas_Plus_Features');
$magbook_plus = class_exists('Magbook_Plus_Features');
$photograph_plus = class_exists('Photograph_Plus_Features');
$extension_plus = class_exists('Extension_Plus_Features');
$shoppingcart_plus = class_exists('ShoppingCart_Plus_Features');
$cocktail_plus = class_exists('Cocktail_Plus_Features');
$eventsia_plus = class_exists('Eventsia_Plus_Features');
$supermarket_plus = class_exists('Supermarket_Plus_Features');
$zon_plus = is_plugin_active('zon-plus/zon-plus.php') ? '1' : ''; // Zon plus plugin is below the  theme freesia demo import plugin in plugin list inside dashboard > plugins. If the plugin name is below theme freesia demo import then use is_plugin_active
$euphoric_plus = class_exists('Euphoric_Pro');
$freeware_plus = class_exists('Freeware_Pro');
$otography_plus = class_exists('Otography_Pro');
$freenews_plus = class_exists('FreeNews_Pro');
$timesnews_plus = is_plugin_active('timesnews-pro/timesnews-pro.php') ? '1' : '';
$businessdeal_plus = class_exists('Businessdeal_Pro');
$trustnews_plus = is_plugin_active('trustnews-pro/trustnews-pro.php') ? '1' : '';


$themes = [
    'Photograph' => 'photograph',
    'Wedding Photos' => 'photograph',
    'Webart' => 'photograph',
    'Idyllic' => 'idyllic',
    'Arise' => 'arise',
    'Freesia Empire' => 'freesia-empire',
    'Freesia Business' => 'freesia-empire',
    'Freesia Corporate' => 'freesia-empire',
    'Edge' => 'edge',
    'Alternative' => 'edge',
    'Pixgraphy' => 'pixgraphy',
    'Event' => 'event',
    'Excellent' => 'excellent',
    'StoreXmas' => 'storexmas',
    'Magbook' => 'magbook',
    'Extension' => 'extension',
    'ShoppingCart' => 'shoppingcart',
    'Cocktail' => 'cocktail',
    'Cappuccino' => 'cocktail',
    'Mocktail' => 'cocktail',
    'Eventsia' => 'eventsia',
    'Supermarket' => 'supermarket',
    'Zon' => 'zon',
    'Euphoric' => 'euphoric',
    'Freeware' => 'freeware',
    'Otography' => 'otography',
    'FreeNews' => 'freenews',
    'TimesNews' => 'timesnews',
    'BusinessDeal' => 'businessdeal',
    'TrustNews' => 'trustnews'


];

$theme = wp_get_theme();
$theme_name = $theme->Name;


if ($arise_plus || $freesiaempire_plus || $edge_plus || $pixgraphy_plus || $event_plus || $excellent_plus || $idyllic_plus || $storexmas_plus || $magbook_plus || $photograph_plus || $extension_plus || $shoppingcart_plus || $cocktail_plus || $eventsia_plus || $supermarket_plus || $zon_plus || $euphoric_plus || $freeware_plus || $otography_plus || $freenews_plus || $timesnews_plus || $businessdeal_plus || $trustnews_plus ) {
	if (array_key_exists($theme_name, $themes)) {
	    $file_name = $themes[$theme_name];
	    $path = plugin_dir_path(__FILE__) . 'plus-files/'. $file_name.'-plus'. '.php';
	    require_once $path;
	}

} else {

	if (array_key_exists($theme_name, $themes)) {
	    $file_name = $themes[$theme_name];
	    $path = plugin_dir_path(__FILE__) . 'files/'. $file_name . '.php';
	    require_once $path;
	}

}

/**
 * Main plugin class with initialization tasks.
 */
class TFDI_Plugin {
	/**
	 * Constructor for this class.
	 */
	public function __construct() {
		/**
		 * Display admin error message if PHP version is older than 5.6.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.6', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		}
		else {
			// Set plugin constants.
			$this->set_plugin_constants();

			// Composer autoloader.
			require_once OCDI_PATH . 'vendor/autoload.php';

			// Instantiate the main plugin class *Singleton*.
			$one_click_demo_import = OCDI\OneClickDemoImport::get_instance();

			// Register WP CLI commands
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'ocdi list', array( 'OCDI\WPCLICommands', 'list_predefined' ) );
				WP_CLI::add_command( 'ocdi import', array( 'OCDI\WPCLICommands', 'import' ) );
			}
		}
	}


	/**
	 * Display an admin error notice when PHP is older the version 5.6.
	 * Hook it to the 'admin_notices' action.
	 */
	public function old_php_admin_error_notice() { /* translators: %1$s - the PHP version, %2$s and %3$s - strong HTML tags, %4$s - br HTMl tag. */
		$message = sprintf( esc_html__( 'The %2$sTheme Freesia Demo Import%3$s plugin requires %2$sPHP 5.6+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 7.4%4$s Your current version of PHP: %2$s%1$s%3$s', 'theme-freesia-demo-import' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}


	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'OCDI_PATH' ) ) {
			define( 'OCDI_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'OCDI_URL' ) ) {
			define( 'OCDI_URL', plugin_dir_url( __FILE__ ) );
		}

		// Used for backward compatibility.
		if ( ! defined( 'PT_OCDI_PATH' ) ) {
			define( 'PT_OCDI_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'PT_OCDI_URL' ) ) {
			define( 'PT_OCDI_URL', plugin_dir_url( __FILE__ ) );
		}

		// Action hook to set the plugin version constant.
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}


	/**
	 * Set plugin version constant -> OCDI_VERSION.
	 */
	public function set_plugin_version_constant() {
		$plugin_data = get_plugin_data( __FILE__ );

		if ( ! defined( 'OCDI_VERSION' ) ) {
			define( 'OCDI_VERSION', $plugin_data['Version'] );
		}

		// Used for backward compatibility.
		if ( ! defined( 'PT_OCDI_VERSION' ) ) {
			define( 'PT_OCDI_VERSION', $plugin_data['Version'] );
		}
	}
}

// Instantiate the plugin class.
$ocdi_plugin = new TFDI_Plugin();
