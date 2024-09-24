<nav class="navbar navbar-expand-lg navbar-dark primary-bg">
  <div class="container">
    <button class="navbar-toggler mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a href="<?php echo home_url(); ?>" class="nav-link px-3 text-white">Home</a></li>
        <li class="nav-item"><a href="<?php echo home_url('/about-us'); ?>" class="nav-link px-3 text-white">About</a></li>
        <li class="nav-item dropdown">
          <a href="<?php echo home_url('/services'); ?>" class="nav-link px-3 dropdown-toggle text-white" data-bs-toggle="dropdown">Services</a>
          <ul class="dropdown-menu dropdown-menu-dark primary-bg services-submenu">
            <?php
            $services_pages = new WP_Query(array(
              'post_type' => 'services',
              'posts_per_page' => -1,
            ));
            
            if ($services_pages->have_posts()) :
              while ($services_pages->have_posts()) : $services_pages->the_post();
            ?>
              <li><a href="<?php the_permalink(); ?>" class="dropdown-item"><?php the_title(); ?></a></li>
            <?php
              endwhile;
              wp_reset_postdata();
            endif;
            ?>
            <li><hr class="dropdown-divider"></li>
            <li><a href="<?php echo home_url('/services'); ?>" class="dropdown-item">All Services</a></li>
          </ul>
        </li>
        <li class="nav-item"><a href="<?php echo home_url('/contact'); ?>" class="nav-link px-3 text-white">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<style>
  .navbar-nav .nav-link {
    font-size: 1.1rem;
  }
  .dropdown-menu {
    min-width: 30rem;
    column-count: 2;
    column-gap: 1rem;
  }
  .dropdown-item {
    break-inside: avoid;
    font-size: 0.9rem;
    color: white;
  }
  .dropdown-item:hover,
  .dropdown-item:focus {
    background-color: #157347; /* Original hover color */
    color: white;
  }
  
  /* New hover color for submenu items */
  .services-submenu .dropdown-item:hover,
  .services-submenu .dropdown-item:focus {
    background-color: #00795d; /* Different tint of bg-success */
    color: white;
  }
  
  @media (max-width: 767px) {
    .dropdown-menu {
      min-width: auto;
      column-count: 1;
      left: 50%;
      transform: none;
      position: absolute;
      width: 90%;
      max-width: 300px;
    }
  }
  
  .submenu {
    background-color: var(--primary-bg-color); /* Assuming primary-bg uses a CSS variable */
  }
</style>