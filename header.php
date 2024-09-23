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
    ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="bg-light py-2 py-md-3">
  <div class="container">
    <div class="row align-items-center gy-2 gy-md-0">
      <div class="col-md-3 text-center text-md-start order-md-1 order-2">
        <i class="fas fa-phone-alt me-2 fs-4"></i>
        <span class="fs-4"><?php echo esc_attr(get_option('site_phone', '123-456-7890')); ?></span>
      </div>
      <div class="col-md-6 text-center order-md-2 order-1">
        <?php
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            echo '<h1><a href="' . esc_url(home_url('/')) . '" style="color: black; text-decoration: none;">' . get_bloginfo('name') . '</a></h1>';
        }
        ?>
      </div>
      <div class="col-md-3 text-center text-md-end order-md-3 order-3">
        <a href="/contact" class="btn cta-bg btn-lg text-white px-4 py-2 fs-5">
          <?php echo get_theme_mod('header_cta_text', 'Request a Service'); ?>
        </a>
      </div>
    </div>
  </div>
</div>

<?php get_template_part('template-parts/header', 'navbar'); ?>