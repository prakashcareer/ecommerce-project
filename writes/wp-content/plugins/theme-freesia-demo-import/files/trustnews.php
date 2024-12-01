<?php

/* TrustNews Theme */

function trustnews_import_files() {
  return array(
    array(
      'import_file_name'             => 'TrustNews Free',
      'categories'                   => array( 'TrustNews Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/trustnews/trustnews.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/trustnews/trustnews-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/trustnews/trustnews-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/trustnews.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/trustnews/',
    ),
    array(
      'import_file_name'             => 'TrustNews Plus',
      'categories'                   => array( 'TrustNews Plus'),
      'import_preview_image_url'    => 'https://themefreesia.com/wp-content/uploads/2023/08/trustnews-plus.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/trustnews-plus/',
      'isPlus'                      => true,
      'buy_url'                    => 'https://themefreesia.com/plugins/trustnews-plus/'
    ),
  );
}
add_filter( 'ocdi/import_files', 'trustnews_import_files' );

function trustnews_after_import_setup() {

	// Assign menus to their locations.
    $main_menu = get_term_by( 'name','Primary menu', 'nav_menu' );
    $social_links = get_term_by( 'name','Social Links', 'nav_menu' );
    $secondary = get_term_by( 'name','Secondary', 'nav_menu' );
    $footer = get_term_by( 'name','Footer Menu', 'nav_menu' );

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
add_action( 'ocdi/after_import', 'trustnews_after_import_setup' );