<div class="container-fluid bg-light py-3">
  <div class="container">
    <div class="row align-items-center gy-3">
      <div class="col-md-4 text-center text-md-start">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-decoration-none d-flex align-items-center">
          <div class="d-flex align-items-center">
            <img src="https://placehold.co/75x75?text=Icon" alt="Logo Icon" class="img-fluid">
            <h1 class="ms-3 mb-0 lh-1">
              <span class="d-block text-primary fs-4 fw-bold"><?= get_option('site_location', '') ?></span>
              <span class="d-block text-dark fs-2 fw-bold"><?php echo rr_get_site_niche('') ?></span>
            </h1>
          </div>
        </a>
      </div>
      <div class="col-md-4 text-center">
        <p class="mb-2 fs-6">Save On All <?php echo rr_get_site_niche('') ?> Services</p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-lg">Get a Quote Today</a>
      </div>
      <div class="col-md-4 text-center text-md-end">
        <p class="mb-0 fs-4 fw-bold lh-1">Call Us Today!</p>
        <p class="mt-1 fs-2 text-primary fw-bold mb-0 lh-1"><?php echo esc_attr(get_option('site_phone', '123-456-7890')); ?></p>
      </div>
    </div>
  </div>
</div>
