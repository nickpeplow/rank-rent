<div class="card raised-card d-none d-md-block" style="box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 14px 0px; border-bottom: 5px solid #00664e;">
    <div class="card-body p-4">
        <?php $site_niche = ranknrent_get_site_niche_name(); ?>
        <h5 class="card-title text-primary-color"><?php echo esc_html(get_option('site_location', '')); ?> <?php echo esc_html($site_niche); ?></h5>
        <h3 class="card-subtitle mb-3">How Can We Help?</h3>
        <ul class="list-unstyled">
    <li class="d-flex align-items-start mb-4">
        <div class="primary-bg text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
            <i class="fas fa-envelope fa-lg"></i>
        </div>
        <div>
            <h3 class="h5 mb-0">Email Us</h3>
            <p class="mb-0">
                <a href="mailto:example@lawn-care" class="text-decoration-none text-muted"><?php echo esc_html(get_option('site_email', '')); ?></a>
            </p>
        </div>
    </li>
    <li class="d-flex align-items-start mb-4">
        <div class="primary-bg text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
            <i class="fas fa-map-marker-alt fa-lg"></i>
        </div>
        <div>
            <h3 class="h5 mb-1">Address</h3>
            <p class="mb-0 text-muted"><?php echo esc_html(get_option('site_address', '')); ?></p>
        </div>
    </li>
    <li class="d-flex align-items-start mb-0">
        <div class="primary-bg text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
            <i class="fas fa-phone fa-lg"></i>
        </div>
        <div>
            <h3 class="h5 mb-1">Call Us</h3>
            <p class="mb-0">
                <a href="tel:1(123)456-7890" class="text-decoration-none text-muted"><?php echo esc_html(get_option('site_phone', '')); ?></a>
            </p>
        </div>
    </li>
</ul>
    </div>
</div>
