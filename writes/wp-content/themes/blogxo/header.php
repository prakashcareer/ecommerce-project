<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package blogxo
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class();?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#content">
<?php _e( 'Skip to content', 'blogxo' ); ?></a>
<?php $blogxo_background_image = get_theme_support( 'custom-header', 'default-image' );
  if ( has_header_image() ) { $blogxo_background_image = get_header_image(); } ?>
    <div class="wrapper" id="custom-background-css">
    <?php if ( has_header_image() ) { ?> <img src="<?php echo esc_url( $blogxo_background_image ); ?>"> <?php } ?>
      <!--header--> 
    <header class="bs-headtwo">
        <!--top-bar-->
        <div class="bs-head-detail d-none d-lg-block">
        <div class="container">
          <div class="row align-items-center">
            <!-- mg-latest-news -->
            <div class="col-md-8 col-xs-12">
              <?php $blogxo_brk_news_enable = get_theme_mod('brk_news_enable',true); 
              if($blogxo_brk_news_enable == true) {
              ?>
              <div class="mg-latest-news">
                <?php
                $blogxo_category = blogxo_get_option('select_flash_news_category');
                $blogxo_number_of_posts = blogxo_get_option('number_of_flash_news');
                $blogxo_breaking_news_title = blogxo_get_option('breaking_news_title');
                $blogxo_all_posts = blogarise_get_posts($blogxo_number_of_posts, $blogxo_category);
                $blogxo_count = 1;
                ?>
                <!-- mg-latest-news -->
                 <?php if (!empty($blogxo_breaking_news_title)): ?>
                  <div class="bn_title">
                    <h2 class="title"><?php echo esc_html($blogxo_breaking_news_title); ?></h2>
                  </div>
                <?php endif; ?>
                <!-- mg-latest-news_slider -->
                <?php if(is_rtl()){ ?> 
                <div class="mg-latest-news-slider marquee" data-direction='right' dir="ltr">
                  <?php } else { ?> 
                <div class="mg-latest-news-slider marquee">
                    <?php }
                      if ($blogxo_all_posts->have_posts()) :
                        while ($blogxo_all_posts->have_posts()) : $blogxo_all_posts->the_post();
                          if(is_rtl()) { ?> 
                            <a href="<?php the_permalink(); ?>">
                              <span><?php the_title(); ?></span>
                              <i class="fa-solid fa-circle-arrow-left"></i>
                            </a>
                          <?php } else { ?>
                            <a href="<?php the_permalink(); ?>">
                            <i class="fa-solid fa-circle-arrow-right"></i>
                              <span><?php the_title(); ?></span>
                            </a>
                            <?php }
                          $blogxo_count++;
                        endwhile;
                      endif;
                    wp_reset_postdata();
                  ?>
                </div>
                <!-- // mg-latest-news_slider -->
              </div>
            <?php } ?>
            </div>
            <!--/col-md-6-->
            <div class="col-md-4 col-xs-12">
              <?php do_action('blogarise_action_header_social_section'); ?>
            </div>
            <!--/col-md-6-->
          </div>
        </div>
      </div>
      <!--/top-bar-->
      <div class="clearfix"></div>
      <!-- Main Menu Area-->
      <div class="bs-menu-full">
        <nav class="navbar navbar-expand-lg navbar-wp">
          <div class="container">
            <!-- Right nav -->
            <div class="navbar-header d-none d-lg-block">
                  <?php the_custom_logo(); 
                  if (display_header_text()) { ?>
                    <div class="site-branding-text">
                    <?php } else { ?>
                    <div class="site-branding-text d-none">
                    <?php } if (is_front_page() || is_home()) { ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></h1>
                    <?php } else { ?>
                    <p class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></p>
                    <?php } ?>
                    <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
                    </div>
            </div>
            <!-- Mobile Header -->
            <div class="m-header align-items-center">
              <!-- navbar-toggle -->
              <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-wp" aria-controls="navbar-wp" aria-expanded="false"
                aria-label="Toggle navigation"> 
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
                  <div class="navbar-header">
                   <?php the_custom_logo(); 
                  if (display_header_text()) { ?>
                    <div class="site-branding-text">
                    <?php } else { ?>
                    <div class="site-branding-text d-none">
                    <?php } if (is_front_page() || is_home()) { ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></h1>
                    <?php } else { ?>
                    <p class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></p>
                    <?php } ?>
                    <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
                    </div>
                  </div>
                  <div class="right-nav"> 
                  <!-- /navbar-toggle -->
                  <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search','true'); 
                  if($blogarise_menu_search == true) {
                  ?>
                    <a class="msearch ml-auto bs_model" data-bs-target="#exampleModal" href="#" data-bs-toggle="modal"> <i class="fa fa-search"></i> </a>
               
                  <?php } ?>
                   </div>
                </div>
            <!-- /Mobile Header -->
            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="navbar-wp">
                  <?php 
                  $blogarise_menu_align_setting = get_theme_mod('blogarise_menu_align_setting','mx-auto');
                    wp_nav_menu( array(
                      'theme_location' => 'primary',
                      'container'  => 'nav-collapse collapse',
                      'menu_class' => $blogarise_menu_align_setting . ' nav navbar-nav'. (is_rtl() ? ' sm-rtl' : ''),
                      'fallback_cb' => 'blogarise_fallback_page_menu',
                      'walker' => new blogarise_nav_walker()
                    ) );
                  ?>
              </div>
            <!-- Right nav -->
            <div class="desk-header right-nav pl-3 ml-auto my-2 my-lg-0 position-relative align-items-center">
              <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search','true'); 
                    $blogarise_subsc_link = get_theme_mod('blogarise_subsc_link', '#'); 
                    $blogarise_menu_subscriber  = get_theme_mod('blogarise_menu_subscriber','true');
                    $subsc_icon = get_theme_mod('subsc_icon_layout','bell');
                    $blogarise_subsc_open_in_new  = get_theme_mod('blogarise_subsc_open_in_new', true);
                  if($blogarise_menu_search == true) {
                  ?>
                <a class="msearch ml-auto bs_model" data-bs-target="#exampleModal" href="#" data-bs-toggle="modal">
                    <i class="fa fa-search"></i>
                  </a> 
               <?php } if($blogarise_menu_subscriber == true) { ?>
              <a class="subscribe-btn" href="<?php echo esc_url($blogarise_subsc_link); ?>" <?php if($blogarise_subsc_open_in_new) { ?> target="_blank" <?php } ?> ><i class="fas fa-<?php echo $subsc_icon ; ?>"></i></a>
              <?php } $blogarise_lite_dark_switcher = get_theme_mod('blogarise_lite_dark_switcher','true');
                if($blogarise_lite_dark_switcher == true){ ?>
               <label class="switch" for="switch">
                <input type="checkbox" name="theme" id="switch">
                <span class="slider"></span>
              </label>
              <?php } ?>         
            </div>
          </div>
        </nav>
      </div>
      <!--/main Menu Area-->
    </header>
<!--mainfeatured start-->
  <div class="mainfeatured">
    <!--container-->
    <div class="container">
      <!--row-->
      <div class="row">              
        <?php do_action('blogarise_action_main_banner'); ?>  
      </div><!--/row-->
    </div><!--/container-->
  </div>
<!--mainfeatured end-->
<?php