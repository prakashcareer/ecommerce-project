<?php
/**
 * Theme functions and definitions
 *
 * @package blogxo
 */
if ( ! function_exists( 'blogxo_enqueue_scripts' ) ) :
	/**
	 * @since 0.1
	 */
	function blogxo_enqueue_scripts() {
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_enqueue_style( 'blogarise-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'blogxo-style', get_stylesheet_directory_uri() . '/style.css', array( 'blogarise-style-parent' ), '1.0' );
		wp_dequeue_style( 'blogarise-default',get_template_directory_uri() .'/css/colors/default.css');
		wp_enqueue_style( 'blogxo-default-css', get_stylesheet_directory_uri()."/css/colors/default.css" );
        wp_enqueue_style( 'blogxo-dark', get_stylesheet_directory_uri()."/css/colors/dark.css" );

		if(is_rtl()){
		    wp_enqueue_style( 'blogarise_style_rtl', trailingslashit( get_template_directory_uri() ) . 'style-rtl.css' );
	    }

	}

endif;
add_action( 'wp_enqueue_scripts', 'blogxo_enqueue_scripts', 9999 );

function blogxo_theme_setup() {

    //Load text domain for translation-ready
    load_theme_textdomain('blogxo', get_stylesheet_directory() . '/languages');

    require( get_stylesheet_directory() . '/font.php');
    
    require( get_stylesheet_directory() . '/frontpage-options.php');

    require( get_stylesheet_directory() . '/featured-slider-hook.php');
}

add_action( 'after_setup_theme', 'blogxo_theme_setup' );

// custom header Support
$args = array(
    'width'			=> '1600',
    'height'		=> '300',
    'flex-height'		=> false,
    'flex-width'		=> false,
    'header-text'		=> true,
    'default-text-color'	=> '000',
    'wp-head-callback'       => 'blogxo_header_color',
);
add_theme_support( 'custom-header', $args );

add_theme_support( "title-tag" );
add_theme_support( 'automatic-feed-links' );

add_action( 'customize_register', 'blogxo_customizer_rid_values', 1000 );
function blogxo_customizer_rid_values($wp_customize) {
  $wp_customize->remove_control('blogarise_title_font_size');      

}

if ( ! function_exists( 'blogxo_admin_scripts' ) ) :
function blogxo_admin_scripts() {

    wp_enqueue_style('blogxo-admin-style-css', get_stylesheet_directory_uri() . '/css/customizer-controls.css');
}
endif;
add_action( 'admin_enqueue_scripts', 'blogxo_admin_scripts' );

/**
* banner additions.
*/

if (!function_exists('blogxo_get_block')) :
    /**
     *
     * @param null
     *
     * @return null
     *
     * @since blogxo 1.0.0
     *
     */
    function blogxo_get_block($block = 'grid', $section = 'post')
    {

        get_template_part('hooks/blocks/block-' . $section, $block);

    }
endif;



function blogxo_theme_option( $wp_customize )
{

}
add_action('customize_register','blogxo_theme_option');

if ( ! function_exists( 'blogxo_header_color' ) ) :
function blogxo_header_color() {
    $blogarise_logo_text_color = get_header_textcolor();
    $blogxo_title_font_size = get_theme_mod('blogxo_title_font_size','');

    ?>
    <style type="text/css">
    <?php
        if ( ! display_header_text() ) :
    ?>
        .site-title,
        .site-description {
            position: absolute;
            clip: rect(1px, 1px, 1px, 1px);
        }
    <?php
        else :
    ?>
        .site-title a,
        .site-description {
            color: #<?php echo esc_attr( $blogarise_logo_text_color ); ?>;
        }

        .site-branding-text .site-title a {
                font-size: <?php echo esc_attr( $blogxo_title_font_size); ?>px;
            }

            @media only screen and (max-width: 640px) {
                .site-branding-text .site-title a {
                    font-size: 26px;

                }
            }

            @media only screen and (max-width: 375px) {
                .site-branding-text .site-title a {
                    font-size: 26px;

                }
            }

    <?php endif; 

    $color = get_theme_mod( 'background_color','fff' ); ?>
    :root {
        --wrap-color: #<?php echo esc_attr($color); ?>
    }
    </style>
    <?php
}
endif;


function blogxo_limit_content_chr( $content, $limit=100 ) {
    return mb_strimwidth( strip_tags($content), 0, $limit, '...' );
}

function blogxo_footer_js(){ ?>
    <script>
        /* =================================
        ===        home -slider        ====
        =================================== */
        function homemain() {
            var homemain = new Swiper('.homemain-slide', {
                direction: 'horizontal',
                loop: true,
                autoplay: true,
                slidesPerView: 1,
                spaceBetween: 30,
                // Navigation arrows
                navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
                },
                breakpoints: {
                    767: {
                        slidesPerView: 2,
                        spaceBetweenSlides: 30
                    },
                    991: {
                        slidesPerView: 3,
                        spaceBetweenSlides: 30
                    }
                }
            });              
        }
        homemain(); 
    </script>
<?php } add_action('wp_footer','blogxo_footer_js'); 
function blogxo_footer_css(){ ?>
    <style>
        @media (min-width:991px) {
            .homemain-slide .bs-slide .inner .title{
                font-size: <?php echo esc_attr(get_theme_mod('blogarise_slider_title_font_size','32')); ?>px;
            } 
        }
    </style>
<?php } add_action('wp_footer','blogxo_footer_css');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogxo_widgets_init() {

	$blogarise_footer_column_layout = esc_attr(get_theme_mod('blogarise_footer_column_layout',3));
	
	$blogarise_footer_column_layout = 12 / $blogarise_footer_column_layout;
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Widget Area', 'blogxo' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );


	
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'blogxo' ),
		'id'            => 'footer_widget_area',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="col-md-'.$blogarise_footer_column_layout.' rotateInDownLeft animated bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );

}
add_action( 'widgets_init', 'blogxo_widgets_init' );