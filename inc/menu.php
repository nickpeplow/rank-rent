<div class="bg-light py-2 py-md-3">
  <div class="container">
    <div class="row align-items-center gy-2 gy-md-0">
      <div class="col-md-4 text-center text-md-start order-md-1 order-2">
        <i class="fas fa-phone-alt me-2 fs-4"></i>
        <span class="fs-4">(123) 456-7890</span>
      </div>
      <div class="col-md-4 text-center order-md-2 order-1">
        <img src="https://via.placeholder.com/200x50?text=Your+Logo" alt="<?= $site->title() ?> Logo" height="80">
      </div>
      <div class="col-md-4 text-center text-md-end order-md-3 order-3">
        <a href="#" class="btn btn-primary btn-lg text-white px-4 py-2 fs-5">Request a Service</a>
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark primary-bg">
  <div class="container">
    <button class="navbar-toggler mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white px-3 fs-5" href="<?= $site->url() ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white px-3 fs-5" href="<?= $site->url() ?>/about-us">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white px-3 fs-5" href="<?= $site->url() ?>/services">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white px-3 fs-5" href="<?= $site->url() ?>/contact">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>