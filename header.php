<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
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

    // Get the selected niche
    $niche_slug = get_option('site_niche', '');

    $primary_color = '#007bff'; // Default color
    $cta_color = '#ffc107'; // Default CTA color
    $secondary_background = '#D5D8DA'; // Default secondary background color

    // Get the niche details
    $niche = ranknrent_get_niche_details($niche_slug);

    if ($niche && isset($niche['colors'])) {
        $primary_color = $niche['colors']['primary-color'] ?? $primary_color;
        $cta_color = $niche['colors']['cta-color'] ?? $cta_color;
        $secondary_background = $niche['colors']['secondary-background'] ?? $secondary_background;
    }

    // Output the colors as CSS variables
    echo '<style>:root { 
        --primary-color: ' . esc_attr($primary_color) . '; 
        --cta-color: ' . esc_attr($cta_color) . '; 
        --secondary-background: ' . esc_attr($secondary_background) . '; 
        --primary-color-rgb: ' . implode(',', sscanf($primary_color, "#%02x%02x%02x")) . '; 
    }</style>';

    // Add Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">';
    echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">';
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


<?php get_template_part('template-parts/header', 'logobar'); ?>
<?php get_template_part('template-parts/header', 'navbar'); ?>