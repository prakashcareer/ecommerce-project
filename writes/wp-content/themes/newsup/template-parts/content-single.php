<?php
/**
 * The template for displaying the Single content.
 * @package Newsup
 */

$newsup_single_page_layout = get_theme_mod('newsup_single_page_layout','single-align-content-right');
if($newsup_single_page_layout == "single-align-content-left") { ?>
        <aside class="col-lg-3 col-md-4">
        <?php get_sidebar();?>
        </aside>
<?php }
if($newsup_single_page_layout == "single-align-content-right" || $newsup_single_page_layout == "single-align-content-left"){ ?>
        <div class="col-lg-9 col-md-8">
<?php } elseif($newsup_single_page_layout == "single-full-width-content") { ?>
        <div class="col-md-12">
<?php } do_action('newsup_action_main_single_content'); ?>
      </div>
<?php if($newsup_single_page_layout == "single-align-content-right") { ?>
        <aside class="col-lg-3 col-md-4">
                <?php get_sidebar();?>
        </aside>
<?php } ?>