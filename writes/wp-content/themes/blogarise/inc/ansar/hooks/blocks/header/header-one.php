<?php function blogarise_header_default_section()
{ 
  ?>
    <!--header-->
    <header class="bs-headthree">
      <!--top-bar-->
      <div class="bs-head-detail d-none d-lg-block">
        <div class="container">
          <div class="row align-items-center">
            <!-- mg-latest-news -->
            <div class="col-md-8 col-xs-12">
              <?php $brk_news_enable = get_theme_mod('brk_news_enable',true); 
                if($brk_news_enable == true) { ?>
                <div class="mg-latest-news">
                  <?php
                  $category = blogarise_get_option('select_flash_news_category');
                  $number_of_posts = blogarise_get_option('number_of_flash_news');
                  $breaking_news_title = blogarise_get_option('breaking_news_title');
                  $all_posts = blogarise_get_posts($number_of_posts, $category);
                  $count = 1;
                  ?>
                  <!-- mg-latest-news -->
                  <?php if (!empty($breaking_news_title)): ?>
                    <div class="bn_title">
                      <h2 class="title"><?php echo esc_html($breaking_news_title); ?></h2>
                    </div>
                  <?php endif; ?>
                  <!-- mg-latest-news_slider -->
                  
                  <div class="mg-latest-news-slider marquee" <?php if(is_rtl()){ ?> data-direction='right' dir="ltr" <?php } ?> >
                    <?php if ($all_posts->have_posts()) :
                          while ($all_posts->have_posts()) : $all_posts->the_post();
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
                            $count++;
                          endwhile;
                        endif;
                      wp_reset_postdata(); ?>
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
      <div class="bs-header-main" style="background-image: url('');">
        <?php $banner_advertisement_section = blogarise_get_option('banner_advertisement_section');?>
        <div class="inner<?php if(empty($banner_advertisement_section)){ echo ' responsive'; } ?>">
          <div class="container">
            <div class="row align-items-center">
              <?php $center_logo_title = get_theme_mod('blogarise_center_logo_title',false); ?>
              <div class="navbar-header col d-none <?php echo esc_attr($center_logo_title == false ? ' text-md-start  d-lg-block col-md-4' : 'd-lg-block col-md-12 text-center mx-auto'); ?>">
                <!-- Display the Custom Logo -->
                <div class="site-logo">
                    <?php if(get_theme_mod('custom_logo') !== ""){ the_custom_logo(); } ?>
                </div>
                <div class="site-branding-text <?php echo esc_attr( display_header_text() ? ' ' : 'd-none'); ?>">
                  <?php if (is_front_page() || is_home()) { ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></h1>
                  <?php } else { ?>
                    <p class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></p>
                  <?php } ?>
                    <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
                </div> 
              </div>
                <?php /* Banner Ad */ if (!empty(blogarise_get_option('banner_advertisement_section'))) { do_action('blogarise_action_banner_advertisement'); } ?>
            </div>
          </div>
        </div>
      </div>
      <!-- /Main Menu Area-->
      <div class="bs-menu-full">
        <nav class="navbar navbar-expand-lg navbar-wp">
          <div class="container">
            <!-- m-header -->
            <div class="m-header align-items-center justify-content-justify"> 
              <!-- navbar-toggle -->
              <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbar-wp" aria-controls="navbar-wp" aria-expanded="false"
                    aria-label="<?php esc_attr_e('Toggle navigation','blogarise'); ?>">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
              <div class="navbar-header">
                <!-- Display the Custom Logo -->
                <div class="site-logo">
                    <?php if(get_theme_mod('custom_logo') !== ""){ the_custom_logo(); } ?>
                </div>
                <div class="site-branding-text <?php echo esc_attr( display_header_text() ? ' ' : 'd-none'); ?>">
                  <div class="site-title">
                     <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a>
                  </div>
                  <p class="site-description"><?php echo esc_html(get_bloginfo( 'description' )); ?></p>
                </div>
              </div>
              <div class="right-nav">
              <?php $blogarise_menu_search  = get_theme_mod('blogarise_menu_search','true'); 
              if($blogarise_menu_search == true) {
                  ?>
                <a class="msearch ml-auto"  data-bs-target="#exampleModal"  href="#" data-bs-toggle="modal">
                    <i class="fa fa-search"></i>
                  </a> 
               <?php } ?>
              </div>
            </div>
            <!-- /m-header -->
            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="navbar-wp">
              <?php 
                  if(is_rtl()) { wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'  => 'nav-collapse collapse navbar-inverse-collapse',
                        'menu_class' => 'nav navbar-nav sm-rtl',
                        'fallback_cb' => 'blogarise_fallback_page_menu',
                        'walker' => new blogarise_nav_walker()
                      ) ); 
                      } else
                      {
                         wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'  => 'nav-collapse collapse',
                        'menu_class' => 'nav navbar-nav',
                        'fallback_cb' => 'blogarise_fallback_page_menu',
                        'walker' => new blogarise_nav_walker()
                      ) );
                         

                      }
                    ?>
            </div>
            <!-- Right nav -->

            <div class="desk-header right-nav pl-3 ml-auto my-2 my-lg-0 position-relative align-items-center">
                <?php $blogarise_subsc_link = get_theme_mod('blogarise_subsc_link', '#'); 
                    $blogarise_menu_subscriber  = get_theme_mod('blogarise_menu_subscriber','true');
                    $blogarise_subsc_open_in_new  = get_theme_mod('blogarise_subsc_open_in_new', true);

                if($blogarise_menu_search == true) { ?>
                <a class="msearch ml-auto"  data-bs-target="#exampleModal"  href="#" data-bs-toggle="modal">
                    <i class="fa fa-search"></i>
                  </a> 
                <?php } 
                if($blogarise_menu_subscriber == true) { ?>
                  <a class="subscribe-btn" href="<?php echo esc_url($blogarise_subsc_link); ?>" <?php if($blogarise_subsc_open_in_new) { ?> target="_blank" <?php } ?>  ><i class="fas fa-bell"></i></a>
                <?php } 
                $blogarise_lite_dark_switcher = get_theme_mod('blogarise_lite_dark_switcher','true');
                if($blogarise_lite_dark_switcher == true){ ?>
                  <label class="switch" for="switch">
                    <input type="checkbox" name="theme" id="switch">
                    <span class="slider"></span>
                  </label>
                <?php } ?>              
            </div>
            <!-- /Right nav -->
          </div>
        </nav>
      </div>
      <!--/main Menu Area-->
    </header>
  
    <!--/header-->
<?php } ?>