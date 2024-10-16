<?php
/**
 * Template part for displaying the page hero section
 *
 * @param string $title The hero title
 * @param string $subtitle The hero subtitle (optional)
 * @param string $background_image The background image URL (optional)
 */
 
$hero_subheading = get_post_meta($post->ID, 'hero_subheading', true);

$location = get_option('site_location', '');
$site_niche = rr_get_site_niche('');
$hero_heading = "Professional and Affordable $site_niche in $location";
?>

<div class="hero-section" style="background-image: url('<?php echo esc_url($background_image_url); ?>');">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-5"><?php echo esc_html($hero_heading); ?></h1>
                <p class="lead mb-4"><?php echo esc_html($hero_subheading); ?></p>
                <a href="/services" class="btn btn-primary btn-lg text-white">View All Services</a>
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
                    $about_heading = get_post_meta($post->ID, 'about_heading', true);
                    $about_text = get_post_meta($post->ID, 'about_text', true);
                    ?>
                    <h2 class="h2 mb-4"><?php echo esc_html($about_heading); ?></h2>
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
