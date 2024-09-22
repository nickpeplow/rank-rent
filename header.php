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
        <a href="<?php echo esc_url(get_theme_mod('header_cta_link', '#')); ?>" class="btn btn-success btn-lg text-white px-4 py-2 fs-5">
          <?php echo get_theme_mod('header_cta_text', 'Request a Service'); ?>
        </a>
      </div>
    </div>
  </div>
</div>

<?php get_template_part('template-parts/header', 'navbar'); ?>