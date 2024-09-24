<?php
// Include custom post types
require get_template_directory() . '/inc/post-types.php';

// Include the multisite settings file
require_once get_template_directory() . '/inc/multisite.php';

// Near the top of your functions.php file
require_once get_template_directory() . '/inc/ai-content-generator.php';

function rankandrent_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'rankandrent'),
    ));
}
add_action('after_setup_theme', 'rankandrent_setup');

function rankandrent_scripts() {
    $theme_version = wp_get_theme()->get('Version');

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), $theme_version);
    
    // Enqueue Font Awesome CSS
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), $theme_version);
    
    // Enqueue theme's main stylesheet
    wp_enqueue_style('rankandrent-style', get_stylesheet_uri(), array(), $theme_version);
    
    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);
}
add_action('wp_enqueue_scripts', 'rankandrent_scripts');

// Add custom classes to menu items
function add_additional_class_on_li($classes, $item, $args) {
    if(isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

function add_menu_link_class($atts, $item, $args) {
    if (property_exists($args, 'link_class')) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);

function rankandrent_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'rankandrent'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'rankandrent'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'rankandrent_widgets_init');

// Add this to your theme's functions.php file
function theme_customize_register($wp_customize) {
    $wp_customize->add_section('favicon_section', array(
        'title' => 'Favicon',
        'priority' => 30,
    ));

    $wp_customize->add_setting('favicon');

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'favicon', array(
        'label' => 'Upload Favicon',
        'section' => 'favicon_section',
        'settings' => 'favicon',
    )));
}
add_action('customize_register', 'theme_customize_register');

add_action('after_setup_theme', 'ranknrent_add_image_sizes');
function ranknrent_add_image_sizes() {
    add_image_size('service-thumbnail', 600, 338, true); // 16:9 aspect ratio
    add_image_size('testimonial-avatar', 80, 80, array('center', 'center'));
}


function add_conditional_custom_header_code() {
    // Check if the current page is a 404 page
    if (is_404()) {
        // Send 404 status header
        status_header(404);
        echo '<meta name="robots" content="noindex, follow">';
        echo '<meta name="googlebot" content="noindex, follow">';
    } else {
        // Check if search engines are allowed to index the site
        if (get_option('blog_public') == '1') {
            echo '<meta name="robots" content="index, follow">';
            echo '<meta name="googlebot" content="index, follow">';
            echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="/wp-sitemap.xml">';
            
            // You can add more SEO-friendly meta tags or scripts here
        }
    }
}

add_action('wp_head', 'add_conditional_custom_header_code');

// redirect author page to home page
add_action( 'template_redirect', function() {
    if ( is_author() ) {
        wp_redirect( home_url(), 301);
        die;
    }
} );