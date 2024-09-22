<div class="footer-location bg-dark text-light py-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="d-flex align-items-center bg-black bg-opacity-50 p-3 rounded hover-effect">
                    <i class="fas fa-map-marker-alt fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Location</h4>
                        <p class="mb-0 small"><?php echo esc_attr(get_option('site_address', '')); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center bg-black bg-opacity-50 p-3 rounded hover-effect">
                    <i class="fas fa-envelope fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Email</h4>
                        <p class="mb-0 small"><?php echo esc_attr(get_option('site_email', '')); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center bg-black bg-opacity-50 p-3 rounded hover-effect">
                    <i class="fas fa-phone fa-2x text-success me-3"></i>
                    <div>
                        <h4 class="h6 mb-1">Call</h4>
                        <p class="mb-0 small"><?php echo esc_attr(get_option('site_phone', '')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-effect {
    transition: all 0.3s ease;
}
.hover-effect:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}
</style>
