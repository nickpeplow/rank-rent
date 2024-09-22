<?php
/*
Template Name: About
*/

get_header();

get_template_part('template-parts/page-hero', 'about', array(
    'title' => get_the_title(),
    'subtitle' => get_the_excerpt() ?: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar.',
    'background_image' => 'https://placehold.co/1600x400',
    'template' => 'about'
));
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
        <?php get_template_part('template-parts/about', 'content'); ?>
        </div>
        <div class="col-lg-4">
            <?php get_template_part('template-parts/contact', 'sidebar'); ?>
        </div>
    </div>
</div>

<?php
get_footer(); // This should include any footer content and close necessary tags
?>