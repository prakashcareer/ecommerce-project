<?php

/* Otography Theme */

function otography_import_files() {
  return array(
    array(
      'import_file_name'             => 'Otography Free',
      'categories'                   => array( 'Otography Free' ),
      'local_import_file'            => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/otography/otography.xml',
      'local_import_widget_file'     => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/otography/otography-widgets.wie',
      'local_import_customizer_file' => trailingslashit( plugin_dir_path( __FILE__ ) ).'dummy/otography/otography-export.dat',
      'import_preview_image_url'     => 'https://themefreesia.com/wp-content/uploads/2023/08/otography.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/otography/',
    ),
    array(
      'import_file_name'             => 'Otography Plus',
      'categories'                   => array( 'Otography Plus'),
      'import_preview_image_url'    => 'https://themefreesia.com/wp-content/uploads/2023/08/otography-plus.jpg',
      'preview_url'                  => 'https://demo.themefreesia.com/otography-plus/',
      'isPlus'                      => true,
      'buy_url'                    => 'https://themefreesia.com/plugins/otography-plus/'
    ),
  );
}
add_filter( 'ocdi/import_files', 'otography_import_files' );

function otography_after_import_setup() {

	// Assign menus to their locations.
    $top_menu = get_term_by( 'name', esc_html__('Main menu','otography'), 'nav_menu' );
    $main_menu = get_term_by( 'name', esc_html__('Social Links','otography'), 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
        'menu-1' => $top_menu->term_id,
        'menu-2' => $main_menu->term_id,
      )
    );

    $frontpage_id = get_page_by_title( 'Home' );
    $blogpage_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $frontpage_id->ID );
    update_option( 'page_for_posts', $blogpage_id->ID );

}
add_action( 'ocdi/after_import', 'otography_after_import_setup' );