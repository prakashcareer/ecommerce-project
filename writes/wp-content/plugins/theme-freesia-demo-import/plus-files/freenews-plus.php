<?php

/* Freenews Plus */

function freenews_import_files() {
  return array(
    array(
      'import_file_name'             => 'Freenews Free',
      'categories'                   => array( 'Freenews Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/freenews/freenews.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/freenews/freenews-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/freenews/freenews-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/freenews.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/freenews/',
    ),

    array(
      'import_file_name'             => 'Freenews Plus',
      'categories'                   => array( 'Freenews Plus'),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freenews/freenewsplus.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freenews/freenews-plus-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freenews/freenews-export.dat',
      'preview_url'                  => 'https://demo.themefreesia.com/freenews-plus/',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/freenews-plus.jpg',
    ),
  );
}
add_filter( 'ocdi/import_files', 'freenews_import_files' );

function freenews_after_import_setup() {
  // Assign menus to their locations.
    $main_menu = get_term_by( 'name', esc_html__('Primary','freenews-pro'), 'nav_menu' );
    $social_links = get_term_by( 'name', esc_html__('Social Links','freenews-pro'), 'nav_menu' );
    $secondary = get_term_by( 'name', esc_html__('Secondary','freenews-pro'), 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'menu-1' => $main_menu->term_id,
        'menu-2' => $social_links->term_id,
        'menu-3' => $secondary->term_id,
      )
    );

    $frontpage_id = get_page_by_title( 'Home' );
    $blogpage_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $frontpage_id->ID );
    update_option( 'page_for_posts', $blogpage_id->ID );

}
add_action( 'ocdi/after_import', 'freenews_after_import_setup' );