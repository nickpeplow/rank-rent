<?php $site_niche = ranknrent_get_site_niche_name(); ?>
<!-- Services Section -->
<section class="services py-5 pb-4 pb-md-6 primary-bg">
    <div class="container">
        <h2 class="text-center mb-1 text-white"><?php echo rr_get_site_niche('') ?> Services</h2>
        <?php
        // Update this line to correctly retrieve the custom field
        $services_subheading = get_post_meta(get_option('page_on_front'), 'homepage_services_services_subheading', true);
        ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="lead text-center text-white mb-5"><?php echo esc_html($services_subheading); ?></p>
            </div>
        </div>
        <div class="row">
            <?php
            $args = array(
                'post_type' => 'services',
                'posts_per_page' => 6,
            );
            $services_query = new WP_Query($args);

            if ($services_query->have_posts()) :
                while ($services_query->have_posts()) : $services_query->the_post();
                    $service_link = get_permalink();
                    $service_description = get_post_meta(get_the_ID(), 'service_short_description', true);
                    $service_description = $service_description ? $service_description : '';
                    ?>
                    <div class="col-md-4 mb-4">
                        <a href="<?php echo esc_url($service_link); ?>" class="card-link text-decoration-none">
                            <div class="card h-100 shadow-sm transition-hover">
                                <div class="card-img-wrapper" style="aspect-ratio: 16/9; overflow: hidden;">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php
                                        $thumbnail_id = get_post_thumbnail_id();
                                        $thumbnail_data = wp_get_attachment_image_src($thumbnail_id, 'service-thumbnail');
                                        if ($thumbnail_data) {
                                            $width = $thumbnail_data[1];
                                            $height = $thumbnail_data[2];
                                        } else {
                                            $width = 600;
                                            $height = 338;
                                        }
                                        ?>
                                        <?php the_post_thumbnail('service-thumbnail', array(
                                            'class' => 'card-img-top',
                                            'alt' => get_the_title(),
                                            'width' => $width,
                                            'height' => $height
                                        )); ?>
                                    <?php else : ?>
                                        <img src="https://via.placeholder.com/600x338" class="card-img-top" alt="<?php the_title_attribute(); ?>" width="600" height="338">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="h4 card-title"><?php the_title(); ?></h3>
                                    <p class="card-text"><?php echo esc_html($service_description); ?></p>
                                    <span class="btn btn-primary text-white">Learn More</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-white">No services found.</p>';
            endif;
            ?>
        </div> <!-- End of services row -->
        <div class="row">
            <div class="col-12 text-center">
                <a href="/services" class="btn btn-light btn-lg mt-2 mb-2">View All Services</a>
            </div>
        </div>
    </div>
</section>

<style>
    .transition-hover {
        transition: all 0.3s ease-in-out;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>