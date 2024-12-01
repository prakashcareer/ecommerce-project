<?php 

if (!function_exists('blogarise_main_content')) :
    function blogarise_main_content()
    { 
        if(!is_front_page() || !is_home()) {
            do_action('blogarise_breadcrumb_content');
        }
        $blogarise_content_layout = esc_attr(get_theme_mod('blogarise_content_layout','align-content-right'));
        if($blogarise_content_layout == "align-content-left" || $blogarise_content_layout == "grid-left-sidebar") { ?>
            <!--col-lg-4-->
            <aside class="col-lg-4 sidebar-left">
                <?php get_sidebar();?>
            </aside>
            <!--/col-lg-4-->
        <?php } ?>
            <!--col-lg-8-->
        <?php if($blogarise_content_layout == "align-content-right" || $blogarise_content_layout == "align-content-left"){ ?>
            <div class="col-lg-8 content-right">
                <?php get_template_part('template-parts/content', get_post_format()); ?>
            </div>
        <?php } elseif($blogarise_content_layout == "full-width-content") { ?>
            <div class="col-lg-12 content-full">
                <?php get_template_part('template-parts/content', get_post_format()); ?>
            </div>
        <?php }  if($blogarise_content_layout == "grid-left-sidebar" || $blogarise_content_layout == "grid-right-sidebar"){ ?>
            <div class="col-lg-8 content-right">
                <?php get_template_part('content','grid'); ?>
            </div>
        <?php } elseif($blogarise_content_layout == "grid-fullwidth") { ?>
            <div class="col-lg-12 content-full">
                <?php get_template_part('content','grid'); ?>
            </div>
        <?php } ?>
            <!--/col-lg-8-->
        <?php if($blogarise_content_layout == "align-content-right" || $blogarise_content_layout == "grid-right-sidebar") { ?>
            <!--col-lg-4-->
            <aside class="col-lg-4 sidebar-right">
                <?php get_sidebar();?>
            </aside>
            <!--/col-lg-4-->
        <?php }        
    }
endif;
add_action('blogarise_action_main_content_layouts', 'blogarise_main_content', 40);