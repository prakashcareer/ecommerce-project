<?php

/* Freeware Plus */

function freeware_import_files() {
  return array(
    array(
      'import_file_name'             => 'Freeware Free',
      'categories'                   => array( 'Freeware Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/freeware/freeware.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/freeware/freeware-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/freeware/freeware-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/freeware.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/freeware/',
    ),

    array(
      'import_file_name'             => 'Freeware Plus',
      'categories'                   => array( 'Freeware Plus'),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freeware/freewareplus.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freeware/freeware-plus-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freeware/freeware-export.dat',
      'preview_url'                  => 'https://demo.themefreesia.com/freeware-plus/',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/freeware-plus.jpg',
    ),
  );
}
add_filter( 'ocdi/import_files', 'freeware_import_files' );

function freeware_after_import_setup() {
  // Assign menus to their locations.
  $top_menu = get_term_by( 'name', esc_html__('Main Menu','freeware-pro'), 'nav_menu' );
  $main_menu = get_term_by( 'name', esc_html__('Social Links','freeware-pro'), 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'menu-1' => $top_menu->term_id,
        'menu-2' => $main_menu->term_id,
      )
    );

}
add_action( 'ocdi/after_import', 'freeware_after_import_setup' );