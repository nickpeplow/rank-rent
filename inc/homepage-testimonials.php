<!-- Testimonials Section -->
<section class="testimonials py-5 pb-4 pb-md-6 primary-bg bg-opacity-10">
    <div class="container">
        <h2 class="text-center mb-5">What Our Clients Say</h2>
        <div class="row justify-content-center">
            <?php
            $homepage_testimonials = get_field('homepage_testimonials');
            
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
                            <div class="flex-shrink-0 me-3 text-center" style="width: 100px;">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($author); ?>" class="rounded-circle mb-2" width="80" height="80">
                                <h5 class="card-title mb-0 fs-6"><?php echo esc_html($author); ?></h5>
                                <small class="text-muted"><?php echo esc_html(get_option('site_location', '')); ?></small>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <?php for ($j = 0; $j < $rating; $j++) : ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-success">
                                        <i class="fas fa-check-circle"></i> Verified Review
                                    </div>
                                </div>
                                <blockquote class="blockquote mb-0">
                                    <p class="mb-0"><i class="fas fa-quote-left text-success me-2"></i><?php echo esc_html($quote); ?></p>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
