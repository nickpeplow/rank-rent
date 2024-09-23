<section class="primary-bg text-white py-4">
    <div class="container">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-6">
                <h2><?php echo esc_html(get_bloginfo('name')); ?></h2>
                <p class="lead mb-0"><?php echo esc_html(get_bloginfo('description')); ?></p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <a href="tel:<?php echo esc_attr(get_option('site_phone', '')); ?>" class="btn btn-light btn-lg me-2 mb-2 mb-md-0">
                    <i class="fas fa-phone me-2"></i><?php echo esc_html(get_option('site_phone', '')); ?>
                </a>
                <a href="mailto:<?php echo esc_attr(get_option('site_email', '')); ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Email Us
                </a>
            </div>
        </div>
    </div>
</section>
