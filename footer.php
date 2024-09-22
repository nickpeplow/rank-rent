<?php get_template_part('template-parts/footer', 'cta'); ?>

<?php get_template_part('template-parts/footer', 'location'); ?>

<style>
    .bg-dark-secondary {
        background-color: #343a40; /* Slightly lighter than bg-dark */
    }
</style>

<footer class="bg-dark-secondary text-white py-4 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5><?php bloginfo('name'); ?></h5>
                <p><?php bloginfo('description'); ?></p>
            </div>
            <div class="col-md-2 mb-3 mb-md-0">
                <h5>Navigation</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="text-white">Home</a></li>
                    <li><a href="<?php echo esc_url(home_url('/services')); ?>" class="text-white">Services</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-white">Contact us</a></li>
                </ul>
            </div>
            <div class="col-md-6 mb-3 mb-md-0">
                <h5>Services</h5>
                <div class="row">
                    <?php
                    $services = new WP_Query(array(
                        'post_type' => 'services',
                        'posts_per_page' => -1,
                    ));
                    $total_services = $services->post_count;
                    $services_per_column = ceil($total_services / 2);
                    $count = 0;
                    ?>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                        <?php
                        while ($services->have_posts()) : $services->the_post();
                            echo '<li><a href="' . get_permalink() . '" class="text-white">' . get_the_title() . '</a></li>';
                            $count++;
                            if ($count == $services_per_column) {
                                echo '</ul></div><div class="col-md-6"><ul class="list-unstyled">';
                            }
                        endwhile;
                        wp_reset_postdata();
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr class="bg-light">
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>