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
            foreach ($_POST['generate_fields'] as $page_id => $value) {
                // Switch to the reference site to get the page
                $reference_site_id = isset($_POST['reference_site_id']) ? intval($_POST['reference_site_id']) : get_main_site_id();
                switch_to_blog($reference_site_id);
                
                $reference_page = get_post($page_id);
                if (!$reference_page) {
                    echo '<div class="notice notice-error"><p>Error: Could not find reference page with ID ' . esc_html($page_id) . ' on the reference site.</p></div>';
                    restore_current_blog();
                    continue;
                }

                // Switch back to the current site
                restore_current_blog();

                // Get the current page by slug
                $current_page = get_page_by_path($reference_page->post_name);

                if (!$current_page) {
                    echo '<div class="notice notice-warning"><p>Warning: Page "' . esc_html($reference_page->post_title) . '" does not exist on the current site. It will be created.</p></div>';
                }

                generate_page_content($reference_page, $current_page, $reference_site_id);
            }
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
    $generated_content = [];

    // Switch to the reference site to get page data
    switch_to_blog($reference_site_id);
    
    foreach ($selected_fields as $page_id => $value) {
        $reference_page = get_post($page_id);
        if (!$reference_page || $reference_page->post_status !== 'publish') {
            continue; // Skip if the page doesn't exist or isn't published in the reference site
        }

        $page_template = get_page_template_slug($page_id);

        // Switch to the current site to check/create the page
        restore_current_blog();
        switch_to_blog($current_site_id);

        // Check if the page exists in the current site
        $current_page = get_page_by_path($reference_page->post_name);

        if (!$current_page) {
            // Create the page if it doesn't exist
            $author_id = $reference_page->post_author;
            if (!get_user_by('id', $author_id)) {
                // If the author doesn't exist in the current site, use the current user
                $author_id = get_current_user_id();
            }

            $new_page_id = wp_insert_post([
                'post_title'    => $reference_page->post_title,
                'post_name'     => $reference_page->post_name,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => $author_id,
            ]);

            if ($new_page_id) {
                // Set the same template as the reference page
                update_post_meta($new_page_id, '_wp_page_template', $page_template);
                $current_page = get_post($new_page_id);
            }
        }

        if ($current_page) {
            $generated_content[$current_page->ID] = generate_page_content($reference_page, $current_page, $reference_site_id);
        }

        // Switch back to the reference site for the next iteration
        restore_current_blog();
        switch_to_blog($reference_site_id);
    }

    // Ensure we're back on the current site before returning
    restore_current_blog();
    switch_to_blog($current_site_id);

    return $generated_content;
}

function generate_page_content($reference_page, $current_page, $reference_site_id) {
    if (!$reference_page) {
        echo '<div class="notice notice-error"><p>Error: Invalid reference page.</p></div>';
        return [];
    }

    $generated_content = display_reference_page_content($reference_page, $reference_site_id);

    if (empty($generated_content) || !is_array($generated_content)) {
        echo '<div class="notice notice-error"><p>Error: No content was generated.</p></div>';
        return [];
    }

    // Ensure we're on the current site
    $current_site_id = get_current_blog_id();
    switch_to_blog($current_site_id);

    $updated_fields = [];

    // If $current_page is null, create a new page
    if (!$current_page) {
        $new_page_id = wp_insert_post([
            'post_title'    => $reference_page->post_title,
            'post_name'     => $reference_page->post_name,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => get_current_user_id(),
        ]);

        if ($new_page_id) {
            $current_page = get_post($new_page_id);
            echo '<div class="notice notice-success"><p>New page created: ' . esc_html($reference_page->post_title) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Failed to create new page.</p></div>';
            restore_current_blog();
            return [];
        }
    }

    foreach ($generated_content as $field_group => $fields) {
        foreach ($fields as $field_name => $new_value) {
            // Update the ACF field
            $update_result = update_field($field_name, $new_value, $current_page->ID);
            
            if ($update_result) {
                $updated_fields[] = $field_name;
            } else {
                echo '<div class="notice notice-warning"><p>Warning: Failed to update field "' . esc_html($field_name) . '".</p></div>';
            }
        }
    }

    // Switch back to the original site
    restore_current_blog();

    if (!empty($updated_fields)) {
        echo '<div class="notice notice-success"><p>Successfully updated fields: ' . esc_html(implode(', ', $updated_fields)) . '</p></div>';
    }

    return $generated_content;
}

function display_reference_page_content($reference_page, $reference_site_id) {
    $generated_content = [];

    echo "<div style='background-color: #f0f0f0; padding: 20px; margin: 20px 0; border: 1px solid #ccc;'>";
    echo "<h2>Content from Reference Page: " . esc_html($reference_page->post_title) . "</h2>";
    echo "<p>Reference Page ID: " . esc_html($reference_page->ID) . "</p>";
    echo "<p>Reference Page Slug: " . esc_html($reference_page->post_name) . "</p>";

    echo "<p>Current Site ID: " . esc_html($reference_site_id) . "</p>";
    switch_to_blog($reference_site_id);

    $field_groups = acf_get_field_groups(array('post_id' => $reference_page->ID));

    if (!$field_groups) {
        echo "<p>No ACF field groups found for this page.</p>";
    } else {
        foreach ($field_groups as $field_group) {
            $group_content = process_field_group($field_group, $reference_page->ID);
            if (!empty($group_content)) {
                $generated_content[$field_group['title']] = $group_content;
            }
        }
    }

    restore_current_blog();

    echo "</div>";

    return $generated_content;
}

function process_field_group($field_group, $post_id) {
    $fields = acf_get_fields($field_group['key']);
    $group_content = array();
    $group_instructions = array();

    foreach ($fields as $field) {
        if (stripos($field['name'], 'image') !== false || stripos($field['name'], 'avatar') !== false) {
            continue;
        }

        $field_value = get_field($field['name'], $post_id);
        
        if (is_array($field_value)) {
            $filtered_value = array();
            $filtered_instructions = array();
            foreach ($field_value as $subfield_name => $subfield_value) {
                if (stripos($subfield_name, 'image') === false && stripos($subfield_name, 'avatar') === false) {
                    $filtered_value[$subfield_name] = $subfield_value;
                    // Get instructions for subfields
                    $subfield = acf_get_field($subfield_name);
                    $filtered_instructions[$subfield_name] = $subfield['instructions'] ?? '';
                }
            }
            if (!empty($filtered_value)) {
                $group_content[$field['name']] = $filtered_value;
                $group_instructions[$field['name']] = $filtered_instructions;
            }
        } else {
            $group_content[$field['name']] = $field_value;
            $group_instructions[$field['name']] = $field['instructions'] ?? '';
        }
    }

    if (!empty($group_content)) {
        echo "<h2>Field Group: " . esc_html($field_group['title']) . "</h2>";
        echo "<h3>Filtered Content (Debug):</h3>";
        echo "<pre>" . esc_html(json_encode($group_content, JSON_PRETTY_PRINT)) . "</pre>";
        echo "<h3>Field Instructions (Debug):</h3>";
        echo "<pre>" . esc_html(json_encode($group_instructions, JSON_PRETTY_PRINT)) . "</pre>";
        preview_claude_api_call($field_group['title'], $group_content, $group_instructions);
        $generated_content = generate_claude_content($field_group['title'], $group_content, $group_instructions);
        echo "<h3>Generated Content:</h3>";
        echo "<pre>" . esc_html(json_encode($generated_content, JSON_PRETTY_PRINT)) . "</pre>";
        return $generated_content;
    } else {
        echo "<p>No non-image fields found in this group.</p>";
    }
    echo "<hr>";
    return [];
}

function preview_claude_api_call($group_name, $original_content, $instructions) {
    $prompt = "You are an AI assistant for a local business website. Please rewrite the following content for the field group '{$group_name}', maintaining the same meaning but making it unique and suitable for a local business website.";
    
    $prompt .= "\n\nField Instructions:";
    foreach ($instructions as $field_name => $instruction) {
        if (is_array($instruction)) {
            $prompt .= "\n{$field_name}:";
            foreach ($instruction as $subfield_name => $subfield_instruction) {
                if (!empty($subfield_instruction)) {
                    $prompt .= "\n  {$subfield_name}: " . preg_replace('/\[.*?\]/', '', $subfield_instruction);
                }
            }
        } elseif (!empty($instruction)) {
            $prompt .= "\n{$field_name}: " . preg_replace('/\[.*?\]/', '', $instruction);
        }
    }
    
    $prompt .= "\n\nHere's the original content:\n\n" . json_encode(array_map(function($value) {
        return is_string($value) ? preg_replace('/\[.*?\]/', '', $value) : $value;
    }, $original_content), JSON_PRETTY_PRINT);
    $prompt .= "\n\nPlease provide the rewritten content for each field. Format your response as a JSON object, where each key is the field name and each value is the rewritten content. Do not include any additional commentary or explanations outside of this JSON structure.";
    
    echo "<h3>Preview of Claude API Call for group: " . esc_html($group_name) . "</h3>";
    echo "<pre>";
    echo "Endpoint: https://api.anthropic.com/v1/messages\n";
    echo "Headers: \n";
    echo json_encode([
        'x-api-key' => substr(get_claude_api_key(), 0, 5) . '...', // Only show first 5 characters of API key
        'anthropic-version' => '2023-06-01',
        'content-type' => 'application/json',
    ], JSON_PRETTY_PRINT);
    echo "\n\nBody:\n";
    echo json_encode([
        'model' => 'claude-3-5-sonnet-20240620',
        'max_tokens' => 4096,
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ],
    ], JSON_PRETTY_PRINT);
    echo "</pre>";
}

function generate_claude_content($group_name, $original_content, $instructions) {
    $prompt = "You are an AI assistant for a local business website. Please rewrite the following content for the field group '{$group_name}', maintaining the same meaning but making it unique and suitable for a local business website. Ignore any text within square brackets [] as these are placeholders.";
    
    $prompt .= "\n\nField Instructions:";
    foreach ($instructions as $field_name => $instruction) {
        if (is_array($instruction)) {
            $prompt .= "\n{$field_name}:";
            foreach ($instruction as $subfield_name => $subfield_instruction) {
                if (!empty($subfield_instruction)) {
                    $prompt .= "\n  {$subfield_name}: " . preg_replace('/\[.*?\]/', '', $subfield_instruction);
                }
            }
        } elseif (!empty($instruction)) {
            $prompt .= "\n{$field_name}: " . preg_replace('/\[.*?\]/', '', $instruction);
        }
    }
    
    $prompt .= "\n\nHere's the original content:\n\n" . json_encode(array_map(function($value) {
        return is_string($value) ? preg_replace('/\[.*?\]/', '', $value) : $value;
    }, $original_content), JSON_PRETTY_PRINT);
    
    $prompt .= "\n\nPlease provide the rewritten content for each field. Format your response as a JSON object, where each key is the field name and each value is the rewritten content. Do not include any additional commentary or explanations outside of this JSON structure.";

    $result = claude_api_call($prompt);

    if (is_wp_error($result)) {
        return "Error: " . $result->get_error_message() . "\n\nIf this error persists, please check your network connection or contact support.";
    }

    // Attempt to decode the JSON response
    $decoded_result = json_decode($result, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // The response is valid JSON
        return $decoded_result;
    } else {
        // The response is not valid JSON
        return "Error: The AI generated an invalid response. Please try again.";
    }
}

function get_site_data($site_id) {
    switch_to_blog($site_id);
    $query = new WP_Query([
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    $site_data = array(
        'id' => $site_id,
        'name' => get_bloginfo('name'),
        'pages' => $query->posts,
    );
    restore_current_blog();
    return $site_data;
}

function render_site_comparison($reference_site_data, $current_site_data) {
    foreach ($reference_site_data['pages'] as $reference_page) {
        echo '<tr>';
        echo '<td>';
        echo '<input type="checkbox" name="generate_fields[' . esc_attr($reference_page->ID) . ']" value="all">';
        echo ' <strong>' . esc_html($reference_page->post_title) . '</strong>';
        echo '</td>';
        echo '<td>' . render_page_fields($reference_page, $reference_site_data) . '</td>';
        echo '<td>' . render_page_fields($reference_page, $current_site_data) . '</td>';
        echo '</tr>';
    }
}

function render_page_fields($reference_page, $site_data) {
    switch_to_blog($site_data['id']);
    
    $output = '';
    $page_exists = false;

    // Find the matching page in the current site
    $current_page = get_page_by_path($reference_page->post_name);

    if ($current_page) {
        $page_exists = true;
        $field_groups = acf_get_field_groups(array('post_id' => $current_page->ID));
        
        if ($field_groups) {
            $output .= '<ul>';
            foreach ($field_groups as $field_group) {
                $output .= '<li>' . esc_html($field_group['title']) . ':';
                $fields = acf_get_fields($field_group['key']);
                if ($fields) {
                    $output .= render_fields_recursive($fields, $current_page->ID);
                }
                $output .= '</li>';
            }
            $output .= '</ul>';
        } else {
            $output .= '<p>No custom fields found for this page.</p>';
        }
    } else {
        $output = '<p>Page does not exist in this site.</p>';
    }

    restore_current_blog();
    return $output;
}

function render_fields_recursive($fields, $post_id, $parent_field = '') {
    $output = '<ul>';
    foreach ($fields as $field) {
        $field_name = $parent_field ? $parent_field . '_' . $field['name'] : $field['name'];
        $field_value = get_field($field_name, $post_id);
        
        $output .= '<li>';
        $output .= esc_html($field['label']) . ' (' . esc_html($field['type']) . '): ';
        
        if ($field['type'] === 'group') {
            $output .= render_fields_recursive($field['sub_fields'], $post_id, $field_name);
        } else {
            $output .= !empty($field_value) ? '✅' : '❌';
        }
        
        $output .= '</li>';
    }
    $output .= '</ul>';
    return $output;
}