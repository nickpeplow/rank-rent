<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <title><?php
    if (is_front_page()) {
        echo esc_html(get_bloginfo('name')) . ' - ' . esc_html(get_bloginfo('description'));
    } elseif (is_singular('post')) {
        echo esc_html(get_the_title()) . ' - ' . esc_html(get_bloginfo('name'));
    } elseif (is_page()) {
        echo esc_html(get_the_title()) . ' - ' . esc_html(get_bloginfo('name'));
    } elseif (is_singular('services')) {
        echo esc_html(get_option('site_location', '')) . ' ' . esc_html(get_the_title()) . ' - ' . esc_html(get_bloginfo('name'));
    } else {
        wp_title('-', true, 'right');
        echo esc_html(get_bloginfo('name'));
    }
    ?></title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    
    <?php
    $favicon = get_theme_mod('favicon');
    if ($favicon) {
        echo '<link rel="icon" href="' . esc_url($favicon) . '" type="image/x-icon">';
        echo '<link rel="shortcut icon" href="' . esc_url($favicon) . '" type="image/x-icon">';
    } else {
        echo '<link rel="icon" href="' . esc_url(get_template_directory_uri() . '/assets/images/default-favicon.ico') . '" type="image/x-icon">';
        echo '<link rel="shortcut icon" href="' . esc_url(get_template_directory_uri() . '/assets/images/default-favicon.ico') . '" type="image/x-icon">';
    }

    // Ensure the multisite.php file is included
    require_once get_template_directory() . '/inc/multisite.php';

    // // Get the selected niche
    // $niche_slug = get_option('site_niche', '');

    $primary_color = rr_get_primary_color('#007bff'); // Use the function with a default color
    $primary_hover_color = rr_get_primary_hover_color(); // Get the hover color
    $cta_color = '#ffc107'; // Default CTA color
    $secondary_background = '#D5D8DA'; // Default secondary background color

    // Output the colors as CSS variables
    echo '<style>:root { 
        --primary-color: ' . esc_attr($primary_color) . '; 
        --primary-hover-color: ' . esc_attr($primary_hover_color) . '; 
        --cta-color: ' . esc_attr($cta_color) . '; 
        --secondary-background: ' . esc_attr($secondary_background) . '; 
        --primary-color-rgb: ' . implode(',', sscanf($primary_color, "#%02x%02x%02x")) . '; 
    }</style>';

    // Get the excerpt for the description
    $description = get_the_excerpt();
    if (empty($description)) {
        $description = get_bloginfo('description');
    }
    
    // Remove any HTML tags and limit the length
    $description = wp_strip_all_tags($description);
    $description = substr($description, 0, 160); // Limit to 160 characters
    
    // Add meta description
    echo '<meta name="description" content="' . esc_attr($description) . '">';

    // Add Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">';
    echo '<meta property="og:description" content="' . esc_attr($description) . '">';
    echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '">';
    echo '<meta property="og:image" content="' . esc_url(get_template_directory_uri() . '/assets/images/og-image.jpg') . '">';
    echo '<meta property="og:type" content="website">';
    ?>
    
    <!-- LocalBusiness Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "<?php echo esc_attr(get_bloginfo('name')); ?>",
      "telephone": "<?php echo esc_attr(get_option('site_phone', '123-456-7890')); ?>",
      "url": "<?php echo esc_url(home_url('/')); ?>"
    }
    </script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/header', 'featurebar'); ?>
<?php get_template_part('template-parts/header', 'logobar'); ?>
<?php get_template_part('template-parts/header', 'navbar'); ?>
