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
    echo '<style>
        .services-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }
        .service-card {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .service-image {
            flex: 0 0 200px;
        }
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .service-content {
            flex: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
        }
        .service-title {
            margin-top: 0;
        }
        .service-description {
            flex-grow: 1;
        }
        .read-more-link {
            align-self: flex-start;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
    </style>';
    echo '<div class="services-list">';
    while ($services_query->have_posts()) : $services_query->the_post();
        $service_link = get_permalink();
        $service_description = get_field('service_short_description');
        $service_description = $service_description ? rnr_replace($service_description) : 'Brief description of the service. Click to learn more.';
        ?>
        <article class="service-card">
            <div class="service-image">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else {
                    echo '<img src="https://via.placeholder.com/200x200.png?text=Service+Image" alt="Placeholder service image">';
                }
                ?>
            </div>
            <div class="service-content">
                <h3 class="service-title"><?php the_title(); ?></h3>
                <div class="service-description"><?php echo $service_description; ?></div>
                <a href="<?php echo $service_link; ?>" class="read-more-link">Read more</a>
            </div>
        </article>
        <?php
    endwhile;
    echo '</div>';
    wp_reset_postdata();
else :
    echo '<p>No services found.</p>';
endif;
?>
