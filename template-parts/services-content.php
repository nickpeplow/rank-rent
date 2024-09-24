<?php
// Display the page content
if (have_posts()) :
    while (have_posts()) : the_post();
        echo '<div class="page-content">';
        the_content();
        echo '</div>';
    endwhile;
endif;

// Services query
$args = array(
    'post_type' => 'services',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
);

$services_query = new WP_Query($args);

if ($services_query->have_posts()) :
    echo '<ul class="services-list">';
    while ($services_query->have_posts()) : $services_query->the_post();
        $service_link = get_permalink();
        ?>
        <li>
            <a href="<?php echo $service_link; ?>"><?php the_title(); ?></a>
        </li>
        <?php
    endwhile;
    echo '</ul>';
    wp_reset_postdata();
else :
    echo '<p>No services found.</p>';
endif;
?>
