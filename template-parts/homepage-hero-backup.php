<div class="bg-primary-dark overflow-hidden position-relative">
  <!-- Background Image with Tint -->
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://placehold.co/1920x1080'); background-size: cover; background-position: center;"></div>
  
  <div class="container position-relative py-5">
    <div class="row justify-content-center align-items-lg-center">
      <div class="col-md-6 mb-5 mb-md-0">
        <!-- Heading -->
        <div class="mb-4">
          <h1 class="text-white mb-3 display-5 fw-bold">Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit.
          </h1>
          <p class="text-white mb-4">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
          
          <!-- Updated button section -->
          <div>
            <a href="/services/" class="btn btn-success btn-lg">Our Services</a>
          </div>
        </div>
        <!-- End Heading -->
      </div>

      <div class="col-md-5 offset-md-1">
        <!-- Card -->
        <div class="card">
          <div class="card-body p-4">
            <!-- Form title with icon and underline -->
            <div class="text-center mb-4">
              <i class="fas fa-clipboard-list fa-2x text-success mb-3"></i>
              <h4 class="card-title fw-bold">Get a quote</h4>
              <div class="bg-success mx-auto" style="height: 2px; width: 50px;"></div>
            </div>
            <form>
              <div class="mb-3">
                <input type="text" class="form-control" name="name" placeholder="Your name" required>
              </div>
              <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Your email" required>
              </div>
              <div class="mb-3">
                <input type="tel" class="form-control" name="phone" placeholder="Your phone number" required>
              </div>
              <div class="mb-3">
                <select class="form-select" name="services" required>
                  <option value="" selected disabled>What services do you need?</option>
                  <option value="pruning">Pruning</option>
                  <option value="removal">Tree Removal</option>
                  <option value="planting">Tree Planting</option>
                  <option value="maintenance">General Maintenance</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="mb-3">
                <textarea class="form-control" name="notes" rows="3" placeholder="Briefly describe your needs..."></textarea>
              </div>
              <button type="submit" class="btn btn-success w-100">Get Your Free Quote</button>
            </form>
            <div class="text-center mt-3">
              <small class="text-muted">Need help? <a href="./page-help-center.html" class="text-success">Visit our Help Center</a></small>
            </div>
          </div>
        </div>
        <!-- End Card -->
      </div>
    </div>
  </div>
</div> <!-- Close the hero section container -->