<?php
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
        echo '<li>';
        echo '<h3>' . get_the_title() . '</h3>';
        echo '<div class="service-excerpt">' . get_the_excerpt() . '</div>';
        echo '<a href="' . get_permalink() . '">Read more</a>';
        echo '</li>';
    endwhile;
    echo '</ul>';
    wp_reset_postdata();
else :
    echo '<p>No services found.</p>';
endif;
?>
