<?php
if (!function_exists('blogarise_main_banner')) :
    /**
     *
     * @since blogarise
     *
     */
    function blogarise_main_banner()
    {
        if (is_front_page() || is_home()) {
            $blogarise_enable_main_slider = get_theme_mod('show_main_news_section',1);
        $select_vertical_slider_news_category = blogxo_get_option('select_vertical_slider_news_category');
        $all_posts_vertical = blogxo_get_posts($select_vertical_slider_news_category);
        if ($blogarise_enable_main_slider): ?>
            <div class="col-12 cc">
                <div class="homemain-slide bs swiper-container">
                    <div class="swiper-wrapper">
                        <?php blogarise_get_block('list', 'banner'); ?>         
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <div class="col-12 cc">
                <div class="bs-no-list-area">
                <?php  $i=1;
                 $select_trending_news_category = get_theme_mod('select_trending_post_category');
                 $trending_news_number = 4;
                 $blogarise_all_posts_main = blogxo_get_posts($trending_news_number, $select_trending_news_category);
                 if ($blogarise_all_posts_main->have_posts()) :
                   while ($blogarise_all_posts_main->have_posts()) : $blogarise_all_posts_main->the_post();
                   global $post;
                   $blogarise_url = blogarise_get_freatured_image_url($post->ID, 'blogarise-slider-full');
                   ?>
                   <div class="bs-no-list-items z-1">
                       <div class="d-flex bs-latest two align-items-center">
                            <div class="orderd-img"> 
                                <?php if (!empty($blogarise_url)){ ?>
                                <img src="<?php echo esc_url($blogarise_url); ?>">
                                <?php } else { ?>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/dummy-image.jpg" class="img-fluid">
                                <?php } ?>
                                <span class="count"><?php echo esc_html( $i) ?></span>
                            </div>
                            <div class="orderd-body">
                            <h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                            <?php blogarise_date_content(); ?>
                            </div>
                        </div>   
                    </div>
                    <?php 
                    $i++; endwhile;
                 endif;
               wp_reset_postdata(); ?>
               </div>
            </div>
        <?php endif; ?>
        <!-- end slider-section -->
        <?php }
    }
endif;
add_action('blogarise_action_main_banner', 'blogarise_main_banner', 40);