<?php
// AI Content Generator functions

// Include Claude integration
require_once get_template_directory() . '/inc/claude-integration.php';

// Add the submenu page
add_action('admin_menu', 'ai_content_generator_add_submenu');

function ai_content_generator_add_submenu() {
    add_submenu_page(
        'rank_rent',
        'AI Content Generator',
        'AI Content Generator',
        'manage_options',
        'rank_rent_ai_generator',
        'ai_content_generator_render_page'
    );
}

function ai_content_generator_render_page() {
    ai_content_generator_handle_api_key();
    ai_content_generator_test_claude();

    // Get all sites in the network
    $sites = get_sites();

    // Get the main site ID
    $main_site_id = get_main_site_id();

    // Get the current site ID
    $current_site_id = get_current_blog_id();

    // Get the reference site ID (default to main site if not set)
    $reference_site_id = isset($_POST['selected_site']) ? intval($_POST['selected_site']) : $main_site_id;

    // Fetch pages and fields for both sites
    $reference_site_data = get_site_data($reference_site_id);
    $current_site_data = get_site_data($current_site_id);

    ?>
    <div class="wrap">
        <h1>AI Content Generator</h1>
        
        <h2>Claude API Settings</h2>
        <form method="post" action="">
            <?php wp_nonce_field('ai_content_generator_api_key', 'ai_content_generator_api_key_nonce'); ?>
            <?php wp_nonce_field('ai_content_generator_test_claude', 'ai_content_generator_test_claude_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="claude_api_key">Claude API Key</label></th>
                    <td>
                        <input type="text" id="claude_api_key" name="claude_api_key" value="<?php echo esc_attr(get_claude_api_key()); ?>" class="regular-text">
                    </td>
                </tr>
            </table>
            <p class="submit">
                <?php submit_button('Save API Key', 'primary', 'submit', false); ?>
                <?php submit_button('Test Claude API', 'secondary', 'test_claude', false, ['style' => 'margin-left: 10px;']); ?>
            </p>
        </form>

        <h2>Generate Content</h2>
        <form method="post" action="">
            <?php wp_nonce_field('ai_content_generator_action', 'ai_content_generator_nonce'); ?>
            
            <label for="selected_site">Select Reference Site:</label>
            <select name="selected_site" id="selected_site">
                <?php foreach ($sites as $site): ?>
                    <option value="<?php echo esc_attr($site->blog_id); ?>" <?php selected($site->blog_id, $reference_site_id); ?>>
                        <?php echo esc_html(get_blog_details($site->blog_id)->blogname); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" name="change_site" class="button" value="Change Reference Site">

            <div class="site-comparison" style="margin-top: 20px;">
                <table class="widefat fixed" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>Reference Site (<?php echo esc_html($reference_site_data['name']); ?>)</th>
                            <th>Current Site (<?php echo esc_html($current_site_data['name']); ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php render_site_comparison($reference_site_data, $current_site_data); ?>
                    </tbody>
                </table>
            </div>

            <p><input type="submit" name="ai_generate_content" class="button button-primary" value="Generate Content"></p>
        </form>
    </div>
    <?php

    if (isset($_POST['ai_generate_content']) && check_admin_referer('ai_content_generator_action', 'ai_content_generator_nonce')) {
        if (isset($_POST['generate_fields']) && is_array($_POST['generate_fields'])) {
            $generated_content = process_content_generation($_POST['generate_fields'], $current_site_id, $reference_site_id);
            
            // Save the generated content
            switch_to_blog($current_site_id);
            foreach ($generated_content as $page_id => $fields) {
                foreach ($fields as $field_key => $content) {
                    update_field($field_key, $content, $page_id);
                }
            }
            restore_current_blog();
            
            echo '<div class="notice notice-success"><p>Content generated and saved successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Please select at least one page to generate content for.</p></div>';
        }
    }
}

function ai_content_generator_test_claude() {
    if (isset($_POST['test_claude']) && check_admin_referer('ai_content_generator_test_claude', 'ai_content_generator_test_claude_nonce')) {
        $test_prompt = "Hello, Claude! Please provide a brief introduction of yourself.";
        $result = claude_api_call($test_prompt);
        
        if (is_wp_error($result)) {
            echo '<div class="notice notice-error"><p>Claude API Test Error: ' . esc_html($result->get_error_message()) . '</p></div>';
            // Add debugging information
            $api_key = get_claude_api_key();
            echo '<div class="notice notice-info"><p>Debugging Info:<br>';
            echo 'API Key (first 5 characters): ' . esc_html(substr($api_key, 0, 5)) . '...<br>';
            echo 'API Key length: ' . esc_html(strlen($api_key)) . ' characters</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>Claude API Test Successful!</p>';
            echo '<p>Response: ' . esc_html($result) . '</p></div>';
        }
    }
}

function fetch_main_site_content($main_site_id) {
    switch_to_blog($main_site_id);
    
    $main_content = array(
        'field_1' => get_field('field_1'),
        'field_2' => get_field('field_2'),
        // ... add more fields as needed
    );
    
    restore_current_blog();
    
    return $main_content;
}

function generate_ai_content($main_content, $area) {
    $generated_content = array();
    foreach ($main_content as $key => $value) {
        $generated_content[$key] = generate_claude_content($key, $value);
    }
    return $generated_content;
}

function save_generated_content($site_id, $generated_content) {
    if (!is_array($generated_content)) {
        error_log("Error: Generated content is not an array for site ID: $site_id");
        return false;
    }

    switch_to_blog($site_id);
    
    foreach ($generated_content as $field => $content) {
        update_field($field, $content);
    }
    
    restore_current_blog();
    return true;
}

function process_site_content($site_id, $main_site_id) {
    $main_content = fetch_main_site_content($main_site_id);
    $area = get_blog_option($site_id, 'blogname');
    $generated_content = generate_ai_content($main_content, $area);
    
    if (empty($generated_content)) {
        error_log("Error: No content generated for site ID: $site_id");
        return false;
    }

    return save_generated_content($site_id, $generated_content);
}

// WP-CLI command
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('generate-site-content', function($args, $assoc_args) {
        $site_id = $args[0];
        $main_site_id = isset($assoc_args['main-site-id']) ? intval($assoc_args['main-site-id']) : 1;
        $result = process_site_content($site_id, $main_site_id);
        if ($result) {
            WP_CLI::success("Content generated for site $site_id using main site $main_site_id");
        } else {
            WP_CLI::error("Failed to generate content for site $site_id");
        }
    });
}

// Add a new function to handle API key setting
function ai_content_generator_handle_api_key() {
    if (isset($_POST['claude_api_key']) && check_admin_referer('ai_content_generator_api_key', 'ai_content_generator_api_key_nonce')) {
        $api_key = sanitize_text_field($_POST['claude_api_key']);
        set_claude_api_key($api_key);
        echo '<div class="notice notice-success"><p>Claude API key updated successfully.</p></div>';
    }
}

function process_content_generation($selected_fields, $current_site_id, $reference_site_id) {
    switch_to_blog($current_site_id);
    $content_mapping = get_dynamic_content_mapping();
    $generated_content = [];

    foreach ($selected_fields as $content_type => $value) {
        if ($value === 'all') {
            // For post types
            if (post_type_exists($content_type)) {
                $posts = get_posts(array('post_type' => $content_type, 'posts_per_page' => -1));
                foreach ($posts as $post) {
                    foreach ($content_mapping[$content_type]['fields'] as $field_key => $field_data) {
                        $original_content = get_field($field_key, $post->ID);
                        
                        $generated_content[$content_type][$post->ID][$field_key] = generate_field_content(
                            $field_data['label'],
                            $field_data['type'],
                            $original_content,
                            $content_mapping[$content_type]['title']
                        );
                    }
                }
            }
            // For specific pages (if needed)
            elseif (strpos($content_type, 'page_') === 0) {
                $template_name = str_replace('page_', '', $content_type);
                $pages = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => $template_name));
                foreach ($pages as $page) {
                    foreach ($content_mapping[$content_type]['fields'] as $field_key => $field_data) {
                        $original_content = get_field($field_key, $page->ID);
                        
                        $generated_content[$content_type][$page->ID][$field_key] = generate_field_content(
                            $field_data['label'],
                            $field_data['type'],
                            $original_content,
                            $content_mapping[$content_type]['title']
                        );
                    }
                }
            }
        }
    }

    restore_current_blog();
    return $generated_content;
}

function get_acf_fields_for_post_type($post_type) {
    $field_groups = acf_get_field_groups(array('post_type' => $post_type));
    $fields = array();

    foreach ($field_groups as $field_group) {
        $group_fields = acf_get_fields($field_group);
        foreach ($group_fields as $field) {
            $fields[$field['name']] = array(
                'label' => $field['label'],
                'type' => $field['type']
            );
        }
    }

    return $fields;
}

function get_dynamic_content_mapping() {
    $post_types = get_post_types(['public' => true], 'objects');
    $mapping = [];

    foreach ($post_types as $post_type) {
        $field_groups = acf_get_field_groups(['post_type' => $post_type->name]);
        if (!empty($field_groups)) {
            $mapping[$post_type->name] = [
                'title' => $post_type->labels->singular_name,
                'fields' => []
            ];
            foreach ($field_groups as $field_group) {
                $fields = acf_get_fields($field_group);
                $mapping[$post_type->name]['fields'] += process_fields($fields);
            }
        }
    }

    // Add support for specific page templates
    $page_templates = get_page_templates();
    foreach ($page_templates as $template_name => $template_filename) {
        $field_groups = acf_get_field_groups(['page_template' => $template_filename]);
        if (!empty($field_groups)) {
            $mapping['page_' . sanitize_title($template_name)] = [
                'title' => $template_name,
                'fields' => []
            ];
            foreach ($field_groups as $field_group) {
                $fields = acf_get_fields($field_group);
                $mapping['page_' . sanitize_title($template_name)]['fields'] += process_fields($fields);
            }
        }
    }

    return $mapping;
}

function process_fields($fields, $prefix = '') {
    $processed_fields = [];

    foreach ($fields as $field) {
        $field_key = $prefix . $field['name'];
        
        if ($field['type'] === 'group') {
            $processed_fields[$field_key] = [
                'label' => $field['label'],
                'type' => 'group',
                'sub_fields' => process_fields($field['sub_fields'], $field_key . '_')
            ];
        } else {
            $processed_fields[$field_key] = [
                'label' => $field['label'],
                'type' => $field['type']
            ];
        }
    }

    return $processed_fields;
}

function render_field_list($fields, $parent_key, $indent = 0) {
    foreach ($fields as $field_key => $field_data) {
        $full_key = $parent_key . '[' . $field_key . ']';
        echo str_repeat('&nbsp;', $indent * 4);
        if ($field_data['type'] === 'group') {
            echo '<strong>' . esc_html($field_data['label']) . ' (group):</strong><br>';
            render_field_list($field_data['sub_fields'], $full_key, $indent + 1);
        } else {
            echo esc_html($field_data['label']) . ' (' . esc_html($field_data['type']) . ')<br>';
        }
    }
}

function get_site_data($site_id) {
    switch_to_blog($site_id);
    $site_data = array(
        'id' => $site_id,
        'name' => get_bloginfo('name'),
        'pages' => get_pages(['sort_column' => 'menu_order', 'sort_order' => 'asc']),
    );
    restore_current_blog();
    return $site_data;
}

function render_site_comparison($reference_site_data, $current_site_data) {
    $all_pages = array_merge($reference_site_data['pages'], $current_site_data['pages']);
    $unique_pages = array_unique($all_pages, SORT_REGULAR);

    foreach ($unique_pages as $page) {
        echo '<tr>';
        echo '<td>';
        echo '<input type="checkbox" name="generate_fields[' . esc_attr($page->ID) . ']" value="all">';
        echo ' <strong>' . esc_html($page->post_title) . '</strong>';
        echo '</td>';
        echo '<td>' . render_page_fields($page->ID, $reference_site_data) . '</td>';
        echo '<td>' . render_page_fields($page->ID, $current_site_data) . '</td>';
        echo '</tr>';
    }
}

function render_page_fields($page_id, $site_data) {
    switch_to_blog($site_data['id']);
    
    $output = '';
    $page_exists = false;

    foreach ($site_data['pages'] as $site_page) {
        if ($site_page->ID == $page_id) {
            $page_exists = true;
            $fields = get_fields($page_id);
            if ($fields) {
                $output .= '<ul>';
                foreach ($fields as $field_name => $field_value) {
                    $field_object = get_field_object($field_name, $page_id);
                    $output .= '<li>';
                    $output .= esc_html($field_name) . ' (' . esc_html($field_object['type']) . '): ';
                    $output .= !empty($field_value) ? '✅' : '❌';
                    $output .= '</li>';
                }
                $output .= '</ul>';
            } else {
                $output .= '<p>No custom fields found for this page.</p>';
            }
            break;
        }
    }

    if (!$page_exists) {
        $output = '<p>Page does not exist in this site.</p>';
    }

    restore_current_blog();
    return $output;
}