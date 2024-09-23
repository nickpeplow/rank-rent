<?php
/*
Template Name: Services
*/

$template = 'services';

get_header();

$hero_data = rnr_replace(get_field('hero'));
$hero_subheading = $hero_data['hero_subheading'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar.';

// Handle background image whether it's an ID, an array, or not set
$background_image = $hero_data['hero_background_image'] ?? null;

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