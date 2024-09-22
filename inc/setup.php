<?php

function ranknrent_create_page($title, $slug, $template = '', $set_as_front = false) {
    $page = get_page_by_path($slug);
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_content' => '',
        ));

        if ($page_id) {
            if ($template) {
                update_post_meta($page_id, '_wp_page_template', $template);
            }

            if ($set_as_front) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }

            return $page_id;
        }
    } else {
        if ($template) {
            update_post_meta($page->ID, '_wp_page_template', $template);
        }

        if ($set_as_front) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $page->ID);
        }

        return $page->ID;
    }

    return false;
}

function ranknrent_delete_page($slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        wp_delete_post($page->ID, true);
        return true;
    }
    return false;
}

function ranknrent_delete_post($slug) {
    $posts = get_posts(array(
        'name' => $slug,
        'post_type' => 'post',
        'numberposts' => 1
    ));
    if ($posts) {
        wp_delete_post($posts[0]->ID, true);
        return true;
    }
    return false;
}

function ranknrent_setup_site() {
    $setup_log = [];

    // Delete default page and post
    if (ranknrent_delete_page('sample-page')) {
        $setup_log[] = "Deleted default 'Sample Page'";
    }
    if (ranknrent_delete_post('hello-world')) {
        $setup_log[] = "Deleted default 'Hello World' post";
    }

    // Create pages if they don't exist
    $pages = [
        ['Homepage', 'homepage', 'template-homepage.php', true],
        ['About Us', 'about-us', 'template-about.php'],
        ['Contact', 'contact', 'template-contact.php'],
        ['Services', 'services', 'template-services.php']
    ];

    foreach ($pages as $page) {
        $page_id = ranknrent_create_page($page[0], $page[1], $page[2], isset($page[3]) ? $page[3] : false);
        if ($page_id && get_post_status($page_id) === 'publish') {
            $setup_log[] = "Created '{$page[0]}' page" . (isset($page[3]) && $page[3] ? " and set as front page" : "");
        }
    }

    // Create services based on the selected niche
    $created_services = ranknrent_create_niche_services();
    if (!empty($created_services)) {
        $setup_log[] = "Created " . count($created_services) . " new niche-specific services:";
        foreach ($created_services as $service) {
            $setup_log[] = "- " . $service;
        }
    } else {
        $setup_log[] = "No new niche-specific services were created.";
    }

    // Add a timestamp to the log
    $setup_log[] = "Setup completed at: " . current_time('mysql');

    // Log the setup actions
    update_option('ranknrent_setup_log', $setup_log);

    // Attempt to clear any caches
    wp_cache_flush();
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
    }

    // Return true if any setup actions were performed
    return !empty($setup_log);
}

function ranknrent_create_niche_services() {
    $niche_slug = ranknrent_get_site_niche();
    $niche = ranknrent_get_niche_details($niche_slug);

    if (!$niche || empty($niche['services'])) {
        return array();
    }

    $created_services = array();

    foreach ($niche['services'] as $service) {
        $existing_service = get_page_by_title($service, OBJECT, 'services');
        
        if (!$existing_service) {
            $post_id = wp_insert_post(array(
                'post_title'    => $service,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => 'services',
            ));

            if (!is_wp_error($post_id)) {
                $created_services[] = $service;
            }
        }
    }

    return $created_services;
}

// Add a filter to ensure the template is applied
add_filter('template_include', 'ranknrent_ensure_homepage_template', 99);

function ranknrent_ensure_homepage_template($template) {
    if (is_front_page()) {
        $new_template = locate_template(array('template-homepage.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    }
    return $template;
}

function ranknrent_reset_site() {
    $args = array(
        'post_type' => array('post', 'page', 'service'), // Add any custom post types here
        'posts_per_page' => -1,
    );
    $posts = get_posts($args);

    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }

    // Reset any other options or settings as needed
    // For example:
    // delete_option('site_niche');
    // delete_option('site_location');

    return true;
}

// Add an action to handle the reset request
add_action('admin_post_ranknrent_reset_site', 'ranknrent_handle_reset_site');

function ranknrent_handle_reset_site() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    check_admin_referer('ranknrent_reset_site_nonce');

    $reset_log = ranknrent_reset_site();
    
    // Redirect back to the admin page with a success message
    wp_redirect(add_query_arg('reset', 'success', admin_url('admin.php?page=ranknrent-settings')));
    exit;
}

// Add a function to display the reset button
function ranknrent_display_reset_button() {
    ?>
    <h3>Reset Site</h3>
    <p>Warning: This action will delete all posts, pages, and custom post types. This cannot be undone.</p>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('ranknrent_reset_site_nonce'); ?>
        <input type="hidden" name="action" value="ranknrent_reset_site">
        <?php submit_button('Reset Site', 'delete', 'submit', true, array('onclick' => "return confirm('Are you sure you want to reset the site? This action cannot be undone.');")); ?>
    </form>
    <?php
}

// ... existing code ...



