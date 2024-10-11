<?php

	
add_post_type_support( 'page', 'excerpt' );
// Include necessary files
require get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/multisite.php';
require_once get_template_directory() . '/inc/ai-content-generator.php';
require_once get_template_directory() . '/inc/network-settings.php';

// Theme setup
function rankandrent_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'rankandrent'),
    ));
}
add_action('after_setup_theme', 'rankandrent_setup');

// Enqueue scripts and styles
function rankandrent_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    // Enqueue styles
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), $theme_version);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), $theme_version);
    wp_enqueue_style('rankandrent-style', get_stylesheet_uri(), array(), $theme_version);
    // Enqueue scripts
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

// Add custom link classes
function add_menu_link_class($atts, $item, $args) {
    if (property_exists($args, 'link_class')) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);

// Register widget area
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

// Customize theme options
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

// Add image sizes
add_action('after_setup_theme', 'ranknrent_add_image_sizes');
function ranknrent_add_image_sizes() {
    add_image_size('service-thumbnail', 600, 338, true); // 16:9 aspect ratio
    add_image_size('testimonial-avatar', 80, 80, array('center', 'center'));
}

// Add conditional custom header code
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

// Add this new function to set ACF defaults when a post is created
function set_acf_defaults_on_post_creation($post_id, $post, $update) {
    // Only run for new posts, not updates
    if (!$update) {
        set_post_defaults_acf($post_id, $post->post_type);
    }
}
add_action('wp_insert_post', 'set_acf_defaults_on_post_creation', 10, 3);

/**
 * Sets the ACF Fields for the specified post
 * @param int $post_id
 * @param string $post_type
 */
function set_post_defaults_acf($post_id=0,$post_type='') {
    if ($post_id &&
           $post_type &&
           function_exists('update_field')
    ) {

        // Get field groups for this post type and id.
        $field_groups = acf_get_field_groups(array(
            'post_id'    => $post_id,
            'post_type'    => $post_type
        ));

        // Loop through the field groups
        foreach ($field_groups as $field_group) {

            // Get all fields associated wiht this group
            $fields = acf_get_fields($field_group);
            if ($fields) {

                // loop through each field
                foreach ($fields as $field) {

                    // Grab our needed values
                    $key_id = $field['key'] ?? false;
                    $name = $field['name'] ?? false;
                    $default = $field['default_value'] ?? false;

                    // Only apply to fields that have a name value (skips tabs etc)
                    if ($key_id && $name) {
                        // Save the field to the post with the default value
                        update_field($name,$default,$post_id);
                    }
                }
            }
        }
    }
}

// Add rewrite rule for custom endpoint
function add_set_acf_defaults_endpoint() {
    add_rewrite_rule('^set-acf-defaults/([0-9]+)/?', 'index.php?set_acf_defaults=1&post_id=$matches[1]', 'top');
}
add_action('init', 'add_set_acf_defaults_endpoint');

// Add query var
function add_set_acf_defaults_query_var($vars) {
    $vars[] = 'set_acf_defaults';
    $vars[] = 'post_id';
    return $vars;
}
add_filter('query_vars', 'add_set_acf_defaults_query_var');

// Handle the custom endpoint
function handle_set_acf_defaults() {
    if (get_query_var('set_acf_defaults') == 1) {
        $post_id = get_query_var('post_id');
        $post = get_post($post_id);

        if (!$post) {
            wp_die('Invalid post ID.');
        }

        // Run the function to set ACF defaults
        set_post_defaults_acf($post_id, $post->post_type);

        // Output a simple message
        echo "ACF defaults have been set for post ID: " . $post_id;
        exit;
    }
}
add_action('template_redirect', 'handle_set_acf_defaults');

// Include the site details API file
require_once get_template_directory() . '/inc/site-details-api.php';

// ACF Fields
require_once get_template_directory() . '/inc/acf.php';