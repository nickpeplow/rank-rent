<!-- Testimonials Section -->
<section class="testimonials py-5 pb-4 pb-md-6 primary-bg bg-opacity-10">
    <div class="container">
        <h2 class="text-center mb-5">What Our Clients Say About Us</h2>
        <div class="row justify-content-center">
            <?php
            $homepage_testimonials = rnr_replace(get_post_meta(get_option('page_on_front'), 'homepage_testimonials', true));
            
            for ($i = 1; $i <= 4; $i++) : 
                $quote = $homepage_testimonials["testimonial_{$i}_quote"] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
                $author = $homepage_testimonials["testimonial_{$i}_name"] ?? 'John Doe';
                $location = $homepage_testimonials["testimonial_{$i}_location"] ?? 'City, State';
                $rating = $homepage_testimonials["testimonial_{$i}_rating"] ?? 5;
                $image = $homepage_testimonials["testimonial_{$i}_avatar"] ?? null;
                
                // Handle image whether it's an ID, an array, or not set
                if (is_array($image) && isset($image['ID'])) {
                    $image_url = wp_get_attachment_image_url($image['ID'], 'testimonial-avatar');
                } elseif (is_numeric($image)) {
                    $image_url = wp_get_attachment_image_url($image, 'testimonial-avatar');
                } else {
                    $image_url = 'https://via.placeholder.com/80x80.png?text=Avatar';
                }
            ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="flex-shrink-0 me-3">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($author); ?>" class="rounded-circle" width="80" height="80">
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="card-title h5 mb-1"><?php echo esc_html($author); ?></h3>
                                <p class="text-muted small mb-2"><?php echo esc_html($location); ?></p>
                                <div class="mb-2">
                                    <?php for ($j = 0; $j < $rating; $j++) : ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php endfor; ?>
                                </div>
                                <blockquote class="blockquote mb-0">
                                    <p class="mb-0"><?php echo esc_html($quote); ?></p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
