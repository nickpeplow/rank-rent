<?php $site_niche = ranknrent_get_site_niche_name(); ?>
<!-- Services Section -->
<section class="services py-5 pb-4 pb-md-6 secondary-bg">
    <div class="container">
        <h2 class="text-center mb-1 text-white"><?php echo esc_html($site_niche); ?> Services</h2>
        <?php
        // Update this line to correctly retrieve the ACF field
        $services_data = rnr_replace(get_field('homepage_services'));
        $services_subheading = $services_data['services_subheading'] ?? 'Discover our range of professional services tailored to meet your needs.';
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
                    $service_description = rnr_replace(get_field('service_short_description'));
                    $service_description = $service_description ? $service_description : 'Brief description of the service. Click to learn more.';
                    ?>
                    <div class="col-md-4 mb-4">
                        <a href="<?php echo esc_url($service_link); ?>" class="card-link text-decoration-none">
                            <div class="card h-100 shadow-sm transition-hover">
                                <div class="card-img-wrapper d-flex align-items-center justify-content-center secondary-bg" style="overflow: hidden;">
                                    <i class="fas fa-cogs fa-4x text-white"></i>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="h4 card-title"><?php the_title(); ?></h3>
                                    <p class="card-text"><?php echo esc_html($service_description); ?></p>
                                    <span class="btn cta-bg text-white">Learn More</span>
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
                <a href="/services" class="btn text-white cta-bg btn-lg mt-2 mb-2">View All Services</a>
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