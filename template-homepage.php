<?php
/*
Template Name: Homepage
*/

get_header();
?>

<main id="main-content">
    <!-- Hero Section -->
    <style>
.hero-section {
    background-image: url('https://placehold.co/1600x400');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0;
    position: relative;
}
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5); /* Adjust opacity as needed */
    z-index: 1;
}
.hero-section > .container {
    position: relative;
    z-index: 2;
}
.raised-card {
    position: relative;
    margin-top: -100px;
    z-index: 3;
}
</style>


<?php
get_template_part('template-parts/homepage-hero', null, array(
    'title' => 'Contact Us',
    'subtitle' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar.',
    'background_image' => 'https://placehold.co/1600x400'
));
?>
    <?php get_template_part('template-parts/homepage', 'services'); ?>

    <?php get_template_part('template-parts/homepage', 'why-choose-us'); ?>

    <?php get_template_part('template-parts/homepage', 'testimonials'); ?>

    <?php //get_template_part('template-parts/homepage', 'info'); ?>

    <?php //get_template_part('template-parts/homepage', 'locations'); ?>

</main>

<?php get_footer(); ?>
