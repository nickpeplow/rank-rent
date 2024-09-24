<div class="card homepage-form-card" style="box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 14px 0px; border-bottom: 5px solid #00664e;">
    <div class="card-body p-4">
        <div class="text-center mb-3">
            <i class="fas fa-clipboard-list text-primary-color mb-2" style="font-size: 2.5rem;"></i>
            <h3 class="card-title mb-1">Get a Quote</h3>
        </div>
        <form>
            <div class="mb-2">
                <label for="name" class="form-label small mb-1">Name</label>
                <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-2">
                <label for="email" class="form-label small mb-1">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-2">
                <label for="phone" class="form-label small mb-1">Phone</label>
                <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" required>
            </div>
            <div class="mb-2">
                <label for="service" class="form-label small mb-1">What service do you need?</label>
                <select class="form-select" id="service" required>
                    <option value="" selected disabled>Choose a service</option>
                    <?php
                    $services = get_posts(array(
                        'post_type' => 'services',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    ));
                    foreach ($services as $service) {
                        echo '<option value="' . esc_attr($service->ID) . '">' . esc_html($service->post_title) . '</option>';
                    }
                    ?>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label small mb-1">Message</label>
                <textarea class="form-control" id="message" rows="3" placeholder="Enter your message"></textarea>
            </div>
            <button type="submit" class="btn cta-bg btn-lg w-100 text-white">Submit</button>
        </form>
    </div>
</div>

<style>
    .homepage-form-card {
        position: sticky;
        top: 20px;
        z-index: 3;
        margin-top: -300px;
        max-width: 400px; /* Add this line to limit the maximum width */
        margin-left: auto; /* Add this line to push the form to the right */
    }

    @media (max-width: 991px) { /* Change from 768px to 991px to match Bootstrap's lg breakpoint */
        .homepage-form-card {
            position: static;
            margin-top: 0;
            max-width: none; /* Remove max-width on smaller screens */
            margin-left: 0; /* Remove margin-left on smaller screens */
        }
    }
</style>