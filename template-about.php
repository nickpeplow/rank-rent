<?php
/*
Template Name: About
*/

$template = 'about';

get_header();

$hero_subheading = rnr_replace(get_post_meta(get_the_ID(), 'hero_subheading', true));

// Handle background image whether it's an ID, an array, or not set
$background_image = get_post_meta(get_the_ID(), 'hero_background_image', true);

if (is_array($background_image) && isset($background_image['ID'])) {
    $background_image_url = wp_get_attachment_image_url($background_image['ID'], 'full');
} elseif (is_numeric($background_image)) {
    $background_image_url = wp_get_attachment_image_url($background_image, 'full');
} else {
    $background_image_url = 'https://placehold.co/1600x400';
}

get_template_part('template-parts/page-hero', $template, array(
    'title' => get_the_title(),
    'subtitle' => $hero_subheading,
    'background_image' => $background_image_url,
    'template' => $template
));
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
        <?php get_template_part('template-parts/' . $template, 'content'); ?>
        </div>
        <div class="col-lg-4">
            <?php get_template_part('template-parts/contact', 'sidebar'); ?>
        </div>
    </div>
</div>

<?php
get_footer(); // This should include any footer content and close necessary tags
?>