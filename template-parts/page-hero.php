<?php
/**
 * Template part for displaying the page hero section
 *
 * @param string $title The hero title
 * @param string $subtitle The hero subtitle (optional)
 * @param string $background_image The background image URL (optional)
 * @param string $template The template name (optional)
 */


$title = $args['title'] ?? get_the_title();
$subtitle = $args['subtitle'] ?? '';
$background_image = get_option('site_default_hero', '');
$template = $args['template'] ?? 'default';

// Add a class based on the template
$hero_class = $template ? "hero-section hero-{$template}" : "hero-section";
?>

<div class="<?php echo esc_attr($hero_class); ?>" style="background-image: url('<?php echo esc_url($background_image); ?>');">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if ($template === 'about' || $template === 'contact') : ?>
                    <h1 class="display-4">
                        <?php echo esc_html($title); ?>
                    </h1>
                    <?php if ($subtitle) : ?>
                        <p class="lead"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                <?php elseif ($template === 'service') : ?>
                    <h1 class="display-4">
                        <span class="h6 text-uppercase d-block mb-0"><?php echo esc_html(get_option('site_location', '')); ?></span>
                        <span class="service-title"><?php echo esc_html($title); ?></span>
                    </h1>
                    <?php if ($subtitle) : ?>
                        <p class="lead"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                <?php elseif ($template === 'services') : ?>
                    <h1 class="display-4">
                        <span class="h6 text-uppercase d-block mb-0"><?php echo esc_html(get_option('site_location', '')); ?> <?= ranknrent_get_site_niche_name(); ?></span>
                        <span class="services-title"><?php echo esc_html($title); ?></span>
                    </h1>
                    <?php if ($subtitle) : ?>
                        <p class="lead"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                <?php else : ?>
                    <h6 class="text-uppercase"><?php echo esc_html(get_option('site_location', '')); ?></h6>
                    <h1 class="display-4">
                        <span class="text-uppercase d-block" style="font-size: 1rem; font-weight: 500;"><?php echo esc_html(get_option('site_location', '')); ?></span>
                        <?php echo esc_html($title); ?>
                    </h1>
                    <?php if ($subtitle) : ?>
                        <p class="lead"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<style>
.hero-section {
    background-image: url('https://placehold.co/1600x400');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 50px 0;
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