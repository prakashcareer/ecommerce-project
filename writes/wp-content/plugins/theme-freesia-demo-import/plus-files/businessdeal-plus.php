<?php

/* Businessdeal Plus */

function businessdeal_import_files() {
  return array(
    array(
      'import_file_name'             => 'Businessdeal Free',
      'categories'                   => array( 'Businessdeal Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/businessdeal/businessdeal.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/businessdeal/businessdeal-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/businessdeal/businessdeal-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/businessdeal.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/businessdeal/',
    ),

    array(
      'import_file_name'             => 'Businessdeal Plus',
      'categories'                   => array( 'Businessdeal Plus'),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/businessdeal/businessdealplus.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/businessdeal/businessdeal-plus-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/businessdeal/businessdeal-export.dat',
      'preview_url'                  => 'https://demo.themefreesia.com/businessdeal-plus/',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/businessdeal-plus.jpg',
    ),
  );
}
add_filter( 'ocdi/import_files', 'businessdeal_import_files' );

function businessdeal_after_import_setup() {
  // Assign menus to their locations.
    $main_menu = get_term_by( 'name','Primary Menu', 'nav_menu' );
    $social_links = get_term_by( 'name','Social Links', 'nav_menu' );
    $secondary = get_term_by( 'name','Secondary Menu', 'nav_menu' );
    $footer = get_term_by( 'name','Footer Navigation', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'menu-1' => $main_menu->term_id,
        'menu-2' => $social_links->term_id,
        'menu-3' => $secondary->term_id,
        'menu-4' => $footer->term_id,
      )
    );

    $frontpage_id = get_page_by_title( 'Home' );
    $blogpage_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $frontpage_id->ID );
    update_option( 'page_for_posts', $blogpage_id->ID );

}
add_action( 'ocdi/after_import', 'businessdeal_after_import_setup' );