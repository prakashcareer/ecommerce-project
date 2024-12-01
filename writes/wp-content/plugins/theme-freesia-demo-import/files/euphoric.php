<?php

/* Euphoric Theme */

function euphoric_import_files() {
  return array(
    array(
      'import_file_name'             => 'Euphoric Free',
      'categories'                   => array( 'Euphoric Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/euphoric/euphoric.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/euphoric/euphoric-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/euphoric/euphoric-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/screenshot.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/euphoric/',
    ),
    array(
      'import_file_name'             => 'Euphoric Plus',
      'categories'                   => array( 'Euphoric Plus'),
      'import_preview_image_url'    => 'https://themefreesia.com/wp-content/uploads/2023/08/euphoric-plus.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/euphoric-plus/',
      'isPlus'                      => true,
      'buy_url'                    => 'https://themefreesia.com/plugins/euphoric-plus/'
    ),
  );
}
add_filter( 'ocdi/import_files', 'euphoric_import_files' );

function euphoric_after_import_setup() {

	// Assign menus to their locations.
    $top_menu = get_term_by( 'name', esc_html__('Main Menu','euphoric'), 'nav_menu' );
    $main_menu = get_term_by( 'name', esc_html__('Social Links','euphoric'), 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'menu-1' => $top_menu->term_id,
        'menu-2' => $main_menu->term_id,
      )
    );

}
add_action( 'ocdi/after_import', 'euphoric_after_import_setup' );