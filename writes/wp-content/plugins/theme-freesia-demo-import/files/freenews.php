<?php

/* Freenews Theme */

function freenews_import_files() {
  return array(
    array(
      'import_file_name'             => 'Freenews Free',
      'categories'                   => array( 'Freenews Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freenews/freenews.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freenews/freenews-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/freenews/freenews-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/freenews.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/freenews/',
    ),
    array(
      'import_file_name'             => 'Freenews Plus',
      'categories'                   => array( 'Freenews Plus'),
      'import_preview_image_url'    => 'https://themefreesia.com/wp-content/uploads/2023/08/freenews-plus.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/freenews-plus/',
      'isPlus'                      => true,
      'buy_url'                    => 'https://themefreesia.com/plugins/freenews-plus/'
    ),
  );
}
add_filter( 'ocdi/import_files', 'freenews_import_files' );

function freenews_after_import_setup() {

	// Assign menus to their locations.
    $main_menu = get_term_by( 'name', esc_html__('Primary','freenews'), 'nav_menu' );
    $social_links = get_term_by( 'name', esc_html__('Social Links','freenews'), 'nav_menu' );
    $secondary = get_term_by( 'name', esc_html__('Secondary','freenews'), 'nav_menu' );

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