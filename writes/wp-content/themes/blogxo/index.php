<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * @package blogxo
 */
get_header(); ?>
<main id="content">
    <!--container-->
    <div class="container">
        <!--row-->
        <div class="row">
            <?php 
            $blogarise_content_layout = esc_attr(get_theme_mod('blogarise_content_layout','grid-right-sidebar'));
            if($blogarise_content_layout == "align-content-left" || $blogarise_content_layout == "grid-left-sidebar") { ?>
                <aside class="col-lg-4 sidebar-left">
                    <?php get_sidebar();?>
                </aside>
            <?php } if($blogarise_content_layout == "align-content-right" || $blogarise_content_layout == "align-content-left"){ ?>
                <div class="col-lg-8 content-right">
                    <?php get_template_part('template-parts/content', get_post_format()); ?>
                </div>
            <?php } elseif($blogarise_content_layout == "full-width-content") { ?>
                <div class="col-lg-12 content-full">
                    <?php get_template_part('template-parts/content', get_post_format()); ?>
                </div>
            <?php } if($blogarise_content_layout == "grid-left-sidebar" || $blogarise_content_layout == "grid-right-sidebar") { ?>
                <div class="col-lg-8 content-right">
                    <?php get_template_part('content','grid'); ?>
                </div>
            <?php } elseif($blogarise_content_layout == "grid-fullwidth") { ?>
                <div class="col-lg-12 content-full">
                    <?php get_template_part('content','grid'); ?>
                </div>
            <?php }
            if($blogarise_content_layout == "align-content-right" || $blogarise_content_layout == "grid-right-sidebar") { ?>
                <aside class="col-lg-4 sidebar-right">
                    <?php get_sidebar();?>
                </aside>
            <?php } ?>
        </div><!--/row-->
    </div><!--/container-->
</main>                
<?php
get_footer();