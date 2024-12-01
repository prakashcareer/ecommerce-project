<?php

/**
 * Default theme options.
 *
 * @package blogxo
 */

if (!function_exists('blogxo_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function blogxo_get_default_theme_options() {

    $defaults = array();

    
    // Header options section
    $defaults['header_layout'] = 'header-layout-1';
    $defaults['banner_advertisement_section'] = '';
    $defaults['banner_advertisement_section_url'] = '';
    $defaults['banner_advertisement_open_on_new_tab'] = 1;
    $defaults['banner_advertisement_scope'] = 'front-page-only';
    $defaults['select_flash_news_category'] = 0;
    $defaults['number_of_flash_news'] = 5;
    $defaults['breaking_news_title'] = __('Breaking','blogxo');

    // Frontpage Section.
    $defaults['show_main_news_section'] = 0;
    $defaults['select_main_banner_section_mode'] = 'default';
    $defaults['select_vertical_slider_news_category'] = 0;
    $defaults['vertical_slider_number_of_slides'] = 15;
    $defaults['select_slider_news_category'] = 0;
    $defaults['select_thumbs_news_category'] = 0;
    $defaults['number_of_slides'] = 5;
    $defaults['show_featured_news_section'] = 1;
    $defaults['featured_news_section_title'] = __('Featured Story', 'blogxo');
    $defaults['select_featured_news_category'] = 0;
    $defaults['number_of_featured_news'] = 6;
    $defaults['remove_header_image_overlay'] = 0;
    $defaults['select_editor_choice_category'] = 0;


    //Featured Ads Section
    $defaults['fatured_post_image_one'] ="";
    $defaults['featured_post_one_btn_txt'] ="";
    $defaults['featured_post_one_url'] ="";
    $defaults['featured_post_one_url_new_tab']="";

    $defaults['fatured_post_image_two']="";
    $defaults['featured_post_two_btn_txt']="";
    $defaults['featured_post_two_url']="";
    $defaults['featured_post_two_url_new_tab']="";

    $defaults['fatured_post_image_three']="";
    $defaults['featured_post_three_btn_txt']="";
    $defaults['featured_post_three_url']="";
    $defaults['featured_post_three_url_new_tab']="";

    $defaults['show_editors_pick_section'] = 1;
    $defaults['frontpage_content_alignment'] = 'align-content-left';

    //layout options
    $defaults['blogxo_content_layout'] = 'align-content-left';
    $defaults['global_post_date_author_setting'] = 'show-date-author';
    $defaults['global_hide_post_date_author_in_list'] = 1;
    $defaults['global_widget_excerpt_setting'] = 'trimmed-content';
    $defaults['global_date_display_setting'] = 'theme-date';
    
    $defaults['frontpage_latest_posts_section_title'] = __('You may have missed', 'blogxo');
    $defaults['frontpage_latest_posts_category'] = 0;
    $defaults['number_of_frontpage_latest_posts'] = 4;

    //Single
    $defaults['single_show_featured_image'] = true;

    // filter.
    $defaults = apply_filters('blogxo_filter_default_theme_options', $defaults);
    $defaults['single_show_share_icon'] = true;

	return $defaults;

}

endif;

if (!function_exists('blogxo_get_option')):
/**
 * Get theme option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function blogxo_get_option($key) {

	if (empty($key)) {
		return; 
	}

	$value = '';

	$default       = blogxo_get_default_theme_options();
	$default_value = null;

	if (is_array($default) && isset($default[$key])) {
		$default_value = $default[$key];
	}

	if (null !== $default_value) {
		$value = get_theme_mod($key, $default_value);
	} else {
		$value = get_theme_mod($key);
	}

	return $value;
}
endif;

/**
 * Returns posts.
 *
 * @since blogxo 1.0.0
 */
if (!function_exists('blogxo_get_posts')):
    function blogxo_get_posts($number_of_posts, $category = '0')
    {

        $ins_args = array(
            'post_type' => 'post',
            'posts_per_page' => absint($number_of_posts),
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'ignore_sticky_posts' => true
        );

        $category = isset($category) ? $category : '0';
        if (absint($category) > 0) {
            $ins_args['cat'] = absint($category);
        }

        $all_posts = new \WP_Query($ins_args);

        return $all_posts;
    }

endif;

/**
 * Option Panel
 *
 * @package blogxo
 */

function blogxo_customize_register($wp_customize) {


    /**
     * Customize Control for Radio Image.
     *
     * @since 1.0.0
     *
     * @see WP_Customize_Control
     */
    class Blogxo_Radio_Image_Control extends WP_Customize_Control {

       /**
         * Declare the control type.
         *
         * @access public
         * @var string
         */
        public $type = 'radio-image';
        
        /**
         * Enqueue scripts and styles for the custom control.
         * 
         * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
         * 
         * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
         * at 'customize_controls_print_styles'.
         *
         * @access public
         */
        public function enqueue() {
            wp_enqueue_script( 'jquery-ui-button' );
        }
        
        /**
         * Render the control to be displayed in the Customizer.
         */
        public function render_content() {
            if ( empty( $this->choices ) ) {
                return;
            }           
            
            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title">
                <?php echo esc_attr( $this->label ); ?>
                <?php if ( ! empty( $this->description ) ) : ?>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php endif; ?>
            </span>
            <div id="input_<?php echo $this->id; ?>" class="image">
                <?php foreach ($this->choices as $value => $label ): ?>
                    <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" id="<?php echo esc_attr($this->id . $value); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                        <label for="<?php echo esc_attr($this->id . $value); ?>">
                            <img src="<?php echo esc_html( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>">
                        </label>
                    </input>
                <?php endforeach; ?>
            </div>
            <script>jQuery(document).ready(function($) { $( '[id="input_<?php echo $this->id; ?>"]' ).buttonset(); });</script>
            <?php
        }
    }

    /*--- Site title Font size **/
    $wp_customize->add_setting('blogxo_title_font_size',
        array(
            'default'           => 60,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'priority' => 50,
        )
    );

    $wp_customize->add_control('blogxo_title_font_size',
        array(
            'label'    => esc_html__('Site Title Size', 'blogxo'),
            'section'  => 'title_tagline',
            'type'     => 'number',
        )
    );

//=================================
// Trending Posts Section.
//=================================

    $wp_customize->remove_section( 'frontpage_advertisement_settings');
    $wp_customize->remove_section( 'blogarise_featured_links_section');
    $wp_customize->remove_setting( 'background_color');
    $wp_customize->remove_setting( 'slider_tabs');
    $wp_customize->remove_control( 'blogarise_content_layout');

    $wp_customize->add_setting('blogarise_slider_title_font_size',
        array(
            'default'           => 32,
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );
    $wp_customize->add_control('blogarise_slider_title_font_size',
        array(
            'label'    => esc_html__('Slider title font Size', 'blogxo'),
            'section'  => 'frontpage_main_banner_section_settings',
            'type'     => 'number',
        )
    );

    $wp_customize->add_setting(
        'background_color', 
        array( 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'fff',
            
        ) 
    );
    $wp_customize->add_control( 'background_color', array(

            'type' => 'color',
            'label'      => __('Background Color', 'blogxo' ),
            'section' => 'colors',
            'priority' => 2,
        )
        
    );
    
    $wp_customize->add_setting(
        'slider_tabs',
        array(
            'default'           => '',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
            'priority' => 1,
        )
    ); 
    $wp_customize->add_control( new Custom_Tab_Control ( $wp_customize,'slider_tabs',
        array(
            'label'                 => '',
            'type' => 'custom-tab-control',
            'section'               => 'frontpage_main_banner_section_settings',
            'controls_general'      => json_encode  ( array( '#customize-control-select_slider_news_category',
                                                            '#customize-control-show_main_news_section', 
                                                            '#customize-control-trending_post_section_title', 
                                                            '#customize-control-select_trending_post_category', 
                                                        ) 
                                                    ),

            'controls_design'       => json_encode  (array( '#customize-control-slider_overlay_enable',
                                                            '#customize-control-blogarise_slider_overlay_color', 
                                                            '#customize-control-blogarise_slider_overlay_text_color', 
                                                            '#customize-control-blogarise_slider_title_font_size', 
                                                            '#customize-control-slider_meta_enable',
                                                        )
                                                    ),
        )
    ));
    // Setting - show_main_news_section.
    $wp_customize->add_setting('show_main_news_section',
        array(
            'default' => 1,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'blogarise_sanitize_checkbox',
        )
    );
    $wp_customize->add_control('show_main_news_section',
        array(
            'label' => esc_html__('Enable Slider Banner Section', 'blogxo'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'checkbox',
            'priority' => 10,
        )
    ); 
    //trending Post Section
    //section title
    $wp_customize->add_setting('trending_post_section_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control( new Blogarise_Section_Title($wp_customize,
        'trending_post_section_title',
        array(
            'label'             => esc_html__( 'Trending Post Section', 'blogxo' ),
            'section'           => 'frontpage_main_banner_section_settings', 
            'active_callback' => 'blogarise_main_banner_section_status',
            'priority' => 100
        )
    ));

    // Setting - drop down category for slider.
    $wp_customize->add_setting('select_trending_post_category',
        array(
            'default' => 0,
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    ); 
    $wp_customize->add_control(new Blogarise_Dropdown_Taxonomies_Control($wp_customize, 'select_trending_post_category',
        array(
            'label' => esc_html__('Category', 'blogxo'),
            'description' => esc_html__('Posts to be shown on trending post section', 'blogxo'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'dropdown-taxonomies', 
            'taxonomy' => 'category', 
            'active_callback' => 'blogarise_main_banner_section_status',
            'priority' => 100,
        )
    ));

    $wp_customize->add_setting(
        'blogarise_content_layout', array(
        'default'           => 'grid-right-sidebar',
        'sanitize_callback' => 'blogarise_sanitize_radio',
    ) );
    $wp_customize->add_control(
        new Blogxo_Radio_Image_Control( 
            // $wp_customize object
            $wp_customize,
            // $id
            'blogarise_content_layout',
            // $args
            array(
                'settings'      => 'blogarise_content_layout',
                'section'       => 'blog_layout_section',
                'priority' => 50,
                'choices'       => array(
                    'align-content-left' => get_template_directory_uri() . '/images/fullwidth-left-sidebar.png',  
                    'full-width-content'    => get_template_directory_uri() . '/images/fullwidth.png',
                    'align-content-right'    => get_template_directory_uri() . '/images/right-sidebar.png',
                    'grid-left-sidebar' => get_template_directory_uri() . '/images/grid-left-sidebar.png',
                    'grid-fullwidth' => get_template_directory_uri() . '/images/grid-fullwidth.png',
                    'grid-right-sidebar' => get_template_directory_uri() . '/images/grid-right-sidebar.png',
                )
            )
        )
    );

}
add_action('customize_register', 'blogxo_customize_register');