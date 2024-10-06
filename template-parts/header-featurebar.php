<div class="container-fluid bg-secondary bg-opacity-10 py-2 d-none d-md-block">
  <div class="container">
    <div class="row justify-content-center gap-4">
      <div class="col-md-5 col-lg-4 col-xl-3">
        <div class="d-flex align-items-center">
          <img src="https://via.placeholder.com/50" alt="Star Rating" class="me-3" width="50" height="50">
          <div class="lh-sm">
            <p class="mb-0">4.9 Star Rating <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></p>
            <p class="mb-0"><small class="text-danger fst-italic">Out of 126 Reviews</small></p>
          </div>
        </div>
      </div>
      <div class="col-md-5 col-lg-4 col-xl-3">
        <div class="d-flex align-items-center">
          <img src="https://via.placeholder.com/50" alt="Location Pin" class="me-3" width="50" height="50">
          <div class="lh-sm">
            <p class="mb-0">Proudly Serving <?= get_option('site_location', '') ?></p>
            <p class="mb-0"><small class="text-danger fst-italic">25 years of experience</small></p>
          </div>
        </div>
      </div>
      <div class="col-md-5 col-lg-4 col-xl-3">
        <div class="d-flex align-items-center">
          <img src="https://via.placeholder.com/50" alt="24/7 Service" class="me-3" width="50" height="50">
          <div class="lh-sm">
            <p class="mb-0">24/7 <?php echo rr_get_site_niche('') ?> Service</p>
            <p class="mb-0"><small class="text-danger fst-italic">At your door in 30 minutes</small></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
