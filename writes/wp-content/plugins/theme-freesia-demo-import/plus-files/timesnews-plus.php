<?php

/* TimesNews Plus */

function timesnews_import_files() {
  return array(
    array(
      'import_file_name'             => 'TimesNews Free',
      'categories'                   => array( 'TimesNews Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/timesnews/timesnews.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/timesnews/timesnews-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'../files/dummy/timesnews/timesnews-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/timesnews.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/timesnews/',
    ),

    array(
      'import_file_name'             => 'Breaking News',
      'categories'                   => array( 'Breaking News' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/breakingnews/breakingnews.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/breakingnews/breakingnews-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/breakingnews/breakingnews-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/timesnews-bn.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/breakingnews/',
    ),

    array(
      'import_file_name'             => 'TimesNews Plus',
      'categories'                   => array( 'TimesNews Plus'),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/timesnews/timesnewsplus.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/timesnews/timesnews-plus-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/timesnews/timesnews-export.dat',
      'preview_url'                  => 'https://demo.themefreesia.com/timesnews-plus/',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/timesnews-plus.jpg',
    ),
  );
}
add_filter( 'ocdi/import_files', 'timesnews_import_files' );

function timesnews_after_import_setup() {
  // Assign menus to their locations.
    $main_menu = get_term_by( 'name','Primary', 'nav_menu' );
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
add_action( 'ocdi/after_import', 'timesnews_after_import_setup' );