<?php
// Include the site setup functions
require_once get_template_directory() . '/inc/setup.php';

if (!function_exists('ranknrent_add_settings_menu')) {
    function ranknrent_add_settings_menu() {
        // Add a new menu item in the sidebar
        add_menu_page(
            'Rank & Rent Settings',
            'Rank & Rent',
            'manage_options',
            'rank_rent',
            'ranknrent_render_settings_page',
            'dashicons-admin-site',
            100
        );

        // Add submenu page for Services Content
        add_submenu_page(
            'rank_rent',
            'Services Content',
            'Services Content',
            'manage_options',
            'rank_rent_services_content',
            'ranknrent_render_services_content_page'
        );
    }
    add_action('admin_menu', 'ranknrent_add_settings_menu');
}

if (!function_exists('ranknrent_render_settings_page')) {
    function ranknrent_render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Rank & Rent Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('rank_rent_options');
                do_settings_sections('rank_rent');
                submit_button();
                ?>
            </form>

            <h2>Site Setup</h2>
            <form method="post" action="">
                <?php wp_nonce_field('ranknrent_setup_action', 'ranknrent_setup_nonce'); ?>
                <p>Click the button below to perform initial site setup actions, including creating service posts for the selected niche.</p>
                <input type="submit" name="ranknrent_setup" class="button button-primary" value="Setup Site">
            </form>
        </div>
        <?php

        // Handle setup action
        if (isset($_POST['ranknrent_setup']) && check_admin_referer('ranknrent_setup_action', 'ranknrent_setup_nonce')) {
            $setup_result = ranknrent_setup_site();
            if ($setup_result) {
                $niche = ranknrent_get_niche_details(ranknrent_get_site_niche());
                $service_count = count($niche['services']);
                $site_location = get_option('site_location', ''); // Get the site location
                echo '<div class="notice notice-success"><p>Site setup completed successfully! Created ' . $service_count . ' service posts for the ' . esc_html($niche['name']) . ' niche in ' . esc_html($site_location) . '.</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>There was an error during site setup. Please make sure you have selected a niche and location, then try again.</p></div>';
            }
        }
    }
}

if (!function_exists('ranknrent_register_settings')) {
    function ranknrent_register_settings() {
        register_setting('rank_rent_options', 'site_location', 'sanitize_text_field');
        register_setting('rank_rent_options', 'site_niche', 'sanitize_text_field');

        add_settings_section(
            'rank_rent_section',
            'Site Details',
            'ranknrent_section_callback',
            'rank_rent'
        );

        add_settings_field(
            'site_location',
            'Site Location',
            'ranknrent_location_field_callback',
            'rank_rent',
            'rank_rent_section'
        );

        add_settings_field(
            'site_niche',
            'Site Niche',
            'ranknrent_niche_field_callback',
            'rank_rent',
            'rank_rent_section'
        );

        // Add new section for contact details
        add_settings_section(
            'rank_rent_contact_section',
            'Contact Details',
            'ranknrent_contact_section_callback',
            'rank_rent'
        );

        // Add new fields for contact details
        add_settings_field(
            'site_address',
            'Address',
            'ranknrent_address_field_callback',
            'rank_rent',
            'rank_rent_contact_section'
        );

        add_settings_field(
            'site_email',
            'Email',
            'ranknrent_email_field_callback',
            'rank_rent',
            'rank_rent_contact_section'
        );

        add_settings_field(
            'site_phone',
            'Phone',
            'ranknrent_phone_field_callback',
            'rank_rent',
            'rank_rent_contact_section'
        );

        // Register new settings
        register_setting('rank_rent_options', 'site_address', 'sanitize_text_field');
        register_setting('rank_rent_options', 'site_email', 'sanitize_email');
        register_setting('rank_rent_options', 'site_phone', 'sanitize_text_field');

        // Add new field for site default hero image
        add_settings_field(
            'site_default_hero',
            'Default Hero Image',
            'ranknrent_hero_field_callback',
            'rank_rent',
            'rank_rent_section'
        );

        // Register new setting
        register_setting('rank_rent_options', 'site_default_hero', 'esc_url_raw');
    }
    add_action('admin_init', 'ranknrent_register_settings');
}

if (!function_exists('ranknrent_section_callback')) {
    function ranknrent_section_callback() {
        echo '<p>Enter the details for this Rank & Rent site.</p>';
    }
}

if (!function_exists('ranknrent_location_field_callback')) {
    function ranknrent_location_field_callback() {
        $location = get_option('site_location', '');
        echo '<input type="text" name="site_location" value="' . esc_attr($location) . '" class="regular-text">';
    }
}

if (!function_exists('ranknrent_niche_field_callback')) {
    function ranknrent_niche_field_callback() {
        $niche_slug = get_option('site_niche', '');
        $niches = ranknrent_get_niches();
        echo '<select name="site_niche">';
        echo '<option value="">Select a niche</option>';
        foreach ($niches as $niche) {
            echo '<option value="' . esc_attr($niche['slug']) . '" ' . selected($niche_slug, $niche['slug'], false) . '>' . esc_html($niche['name']) . '</option>';
        }
        echo '</select>';
    }
}

if (!function_exists('ranknrent_contact_section_callback')) {
    function ranknrent_contact_section_callback() {
        echo '<p>Enter the contact details for this Rank & Rent site.</p>';
    }
}

if (!function_exists('ranknrent_address_field_callback')) {
    function ranknrent_address_field_callback() {
        $address = get_option('site_address', '');
        echo '<input type="text" name="site_address" value="' . esc_attr($address) . '" class="regular-text">';
    }
}

if (!function_exists('ranknrent_email_field_callback')) {
    function ranknrent_email_field_callback() {
        $email = get_option('site_email', '');
        echo '<input type="email" name="site_email" value="' . esc_attr($email) . '" class="regular-text">';
    }
}

if (!function_exists('ranknrent_phone_field_callback')) {
    function ranknrent_phone_field_callback() {
        $phone = get_option('site_phone', '');
        echo '<input type="tel" name="site_phone" value="' . esc_attr($phone) . '" class="regular-text">';
    }
}

if (!function_exists('ranknrent_hero_field_callback')) {
    function ranknrent_hero_field_callback() {
        $hero_image = get_option('site_default_hero', '');
        ?>
        <input type="text" name="site_default_hero" id="site_default_hero" value="<?php echo esc_attr($hero_image); ?>" class="regular-text">
        <input type="button" class="button-secondary" value="Upload Image" id="upload_hero_image_button">
        <br>
        <img id="hero_image_preview" src="<?php echo esc_url($hero_image); ?>" style="max-width: 300px; <?php echo empty($hero_image) ? 'display:none;' : ''; ?>">
        <?php
    }
}

if (!function_exists('ranknrent_get_niches')) {
    function ranknrent_get_niches() {
        $json_file = get_template_directory() . '/inc/niches.json';
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            return json_decode($json_content, true);
        }
        return array();
    }
}

if (!function_exists('ranknrent_get_site_location')) {
    function ranknrent_get_site_location() {
        return get_option('site_location', '');
    }
}

if (!function_exists('ranknrent_get_site_niche')) {
    function ranknrent_get_site_niche() {
        return get_option('site_niche', '');
    }
}

if (!function_exists('ranknrent_get_niche_details')) {
    function ranknrent_get_niche_details($slug) {
        $json_file = get_template_directory() . '/inc/niches.json';
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $niches = json_decode($json_content, true);
           
            if (is_array($niches)) {
                foreach ($niches as $niche) {
                    if ($niche['slug'] === $slug) {
                        return $niche;
                    }
                }
            } else {
            }
        } else {
        }
        return null;
    }
}

if (!function_exists('ranknrent_get_niche_services')) {
    function ranknrent_get_niche_services() {
        $niche_slug = ranknrent_get_site_niche();
        $niche = ranknrent_get_niche_details($niche_slug);
        return $niche ? $niche['services'] : array();
    }
}

if (!function_exists('ranknrent_get_site_address')) {
    function ranknrent_get_site_address() {
        return get_option('site_address', '');
    }
}

if (!function_exists('ranknrent_get_site_email')) {
    function ranknrent_get_site_email() {
        return get_option('site_email', '');
    }
}

if (!function_exists('ranknrent_get_site_phone')) {
    function ranknrent_get_site_phone() {
        return get_option('site_phone', '');
    }
}

if (!function_exists('ranknrent_get_site_niche_name')) {
    function ranknrent_get_site_niche_name() {
        $niche_slug = get_option('site_niche', '');
        $niche = ranknrent_get_niche_details($niche_slug);
        return $niche ? $niche['name'] : '';
    }
}

if (!function_exists('ranknrent_site_location_shortcode')) {
    function ranknrent_site_location_shortcode() {
        return esc_html(get_option('site_location', ''));
    }
    add_shortcode('site_location', 'ranknrent_site_location_shortcode');
}

if (!function_exists('ranknrent_replace_site_location')) {
    function ranknrent_replace_site_location($content) {
        $site_location = esc_html(get_option('site_location', ''));
        return str_replace('[site_location]', $site_location, $content);
    }

    // Add filters to apply the replacement
    add_filter('the_content', 'ranknrent_replace_site_location');
    //add_filter('the_title', 'ranknrent_replace_site_location');
    add_filter('widget_text_content', 'ranknrent_replace_site_location');
    //add_filter('the_excerpt', 'ranknrent_replace_site_location');

    // Apply the filter to all text widgets
    add_filter('widget_text', 'ranknrent_replace_site_location');

    // Apply the filter to navigation menu items
    //add_filter('nav_menu_item_title', 'ranknrent_replace_site_location');

    // Optional: Apply the filter to custom fields
 //add_filter('acf/load_value', 'ranknrent_replace_site_location');

    // Optional: If you want to allow shortcodes in titles
    //add_filter('the_title', 'do_shortcode');
}

if (!function_exists('rnr_replace')) {
    function rnr_replace($content) {
        $site_location = esc_html(get_option('site_location', ''));

        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = rnr_replace($value);
            }
        } else {
            $content = str_replace('[site_location]', $site_location, $content);
        }

        return $content;
    }
}

if (!function_exists('get_niche_colors')) {
    function get_niche_colors($slug) {
        $json = file_get_contents(get_template_directory() . '/inc/niches.json');
        $niches = json_decode($json, true);
    
        foreach ($niches as $niche) {
            if ($niche['slug'] === $slug) {
                return $niche['colors'];
            }
        }
    
        return null;
    }
}

if (!function_exists('ranknrent_enqueue_media_uploader')) {
    function ranknrent_enqueue_media_uploader() {
        if (isset($_GET['page']) && $_GET['page'] === 'rank_rent') {
            wp_enqueue_media();
            wp_enqueue_script('ranknrent-admin-script', get_template_directory_uri() . '/js/ranknrent-admin.js', array('jquery'), null, true);
        }
    }
    add_action('admin_enqueue_scripts', 'ranknrent_enqueue_media_uploader');
}

if (!function_exists('ranknrent_register_site_location_api')) {
    function ranknrent_register_site_location_api() {
        register_rest_route('ranknrent/v1', '/site-location', array(
            'methods' => 'GET',
            'callback' => 'ranknrent_get_site_location_api',
            'permission_callback' => '__return_true'
        ));
    }
    add_action('rest_api_init', 'ranknrent_register_site_location_api');
}

if (!function_exists('ranknrent_get_site_location_api')) {
    function ranknrent_get_site_location_api() {
        $site_location = get_option('site_location', '');
        return array('site_location' => $site_location);
    }
}

if (!function_exists('ranknrent_register_site_location_setting')) {
    function ranknrent_register_site_location_setting() {
        register_setting(
            'general',
            'site_location',
            array(
                'type' => 'string',
                'description' => 'The location of the site',
                'show_in_rest' => true,
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '',
            )
        );
    }
    add_action('init', 'ranknrent_register_site_location_setting');

    // Add a custom REST API endpoint to update site location
    add_action('rest_api_init', function () {
        register_rest_route('ranknrent/v1', '/update-site-location', array(
            'methods' => 'POST',
            'callback' => 'ranknrent_update_site_location_callback',
            'permission_callback' => function() {
                return current_user_can('manage_options');
            }
        ));
    });
}

if (!function_exists('ranknrent_update_site_location_callback')) {
    function ranknrent_update_site_location_callback($request) {
        $location = $request->get_param('site_location');
        if (empty($location)) {
            return new WP_Error('empty_location', 'Site location cannot be empty', array('status' => 400));
        }
        
        $updated = update_option('site_location', $location);
        
        if ($updated) {
            return new WP_REST_Response(array('message' => 'Site location updated successfully'), 200);
        } else {
            return new WP_Error('update_failed', 'Failed to update site location', array('status' => 500));
        }
    }
}

// Include the services content file
require_once get_template_directory() . '/inc/services-content.php';