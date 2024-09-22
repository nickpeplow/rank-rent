<?php
/**
 * Template part for displaying the page hero section
 *
 * @param string $title The hero title
 * @param string $subtitle The hero subtitle (optional)
 * @param string $background_image The background image URL (optional)
 */
 
$hero_data = get_field('hero');
$hero_subheading = $hero_data['hero_subheading'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar.';

$background_image = $hero_data['hero_background_image'] ?? null;

// Handle image whether it's an ID, an array, or not set
if (is_array($background_image) && isset($background_image['ID'])) {
    $background_image_url = wp_get_attachment_image_url($background_image['ID'], 'full');
} elseif (is_numeric($background_image)) {
    $background_image_url = wp_get_attachment_image_url($background_image, 'full');
} else {
    $background_image_url = 'https://placehold.co/1600x400';
}

$location = get_option('site_location', '');
$site_niche = ranknrent_get_site_niche_name();
$hero_heading = $location.' '.$site_niche;

?>

<div class="hero-section" style="background-image: url('<?php echo esc_url($background_image_url); ?>');">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4"><?php echo esc_html($hero_heading); ?></h1>
                <p class="lead mb-2"><?php echo esc_html($hero_subheading); ?></p>
                <ul class="lead two-column-list">
                    <?php
                    $services = get_posts(array(
                        'post_type' => 'services',
                        'posts_per_page' => 6,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    ));
                    if ($services) {
                        foreach ($services as $service) {
                            echo '<li>' . esc_html($service->post_title) . '</li>';
                        }
                    } else {
                        echo '<li>Service 1</li><li>Service 2</li><li>Service 3</li><li>Service 4</li><li>Service 5</li><li>Service 6</li>';
                    }
                    ?>
                </ul>
                <a href="/services" class="btn btn-success btn-lg mt-2">View All Services</a>
            </div>
        </div>
    </div>
</div>

<style>
    .two-column-list {
        columns: 2;
        -webkit-columns: 2;
        -moz-columns: 2;
        list-style-type: none;
        padding-left: 0;
    }
    .two-column-list li {
        position: relative;
        padding-left: 1.5em;
    }
    .two-column-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.5em;
        width: 0.5em;
        height: 0.5em;
        background-color: #28a745;  /* Bootstrap's green color */
    }
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="contact-form-container">
              <div class="so-widget-sow-editor so-widget-sow-editor-base">
                <div class="siteorigin-widget-tinymce textwidget">
                    <?php 
                    $homepage_about = get_field('homepage_about') ?: [];
                    $about_heading = $homepage_about['about_heading'] ?? 'About Us';
                    $about_text = $homepage_about['about_text'] ?? 'Welcome to our company. We provide high-quality services to meet all your needs. Our team of experts is dedicated to ensuring customer satisfaction and delivering exceptional results.';
                    ?>
                    <h2 class="h1 mb-4"><?php echo esc_html($about_heading); ?></h2>
                    <?php echo wp_kses_post($about_text); ?>
                </div>
              </div>
            </div>
        </div>
        <div class="col-lg-4">
            <?php get_template_part('template-parts/homepage', 'form'); ?>
        </div>
    </div>
</div>
