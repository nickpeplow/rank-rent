<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the Claude integration file
require_once get_template_directory() . '/inc/claude-integration.php';

// Function to render the Services Content page
function ranknrent_render_services_content_page() {
    ?>
    <div class="wrap">
        <h1>Services Content</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="update_ranknrent_services_content">
            <?php
            wp_nonce_field('rank_rent_services_options-options');
            do_settings_sections('rank_rent_services');
            submit_button('Save Changes');
            ?>
        </form>
    </div>
    <?php
}

// Add meta box for service content
function ranknrent_add_service_content_meta_box() {
    add_meta_box(
        'service_content_meta_box',
        'Service Content',
        'ranknrent_render_service_content_meta_box',
        'services',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'ranknrent_add_service_content_meta_box');

// Render meta box content
function ranknrent_render_service_content_meta_box($post) {
    wp_nonce_field('ranknrent_save_service_content', 'ranknrent_service_content_nonce');
    $content = get_post_meta($post->ID, '_service_content', true);
    ?>
    <textarea name="service_content" id="service_content" style="width: 100%; height: 400px;"><?php echo esc_textarea($content); ?></textarea>
    <button type="button" class="button generate-content" data-service-id="<?php echo esc_attr($post->ID); ?>">Generate Content</button>
    <span class="spinner" style="float:none;"></span>
    <?php
}

// Save meta box content
function ranknrent_save_service_content() {
    if (!isset($_POST['ranknrent_services_content']) || !isset($_POST['_wpnonce'])) {
        wp_redirect(admin_url('admin.php?page=rank_rent_services_content&error=1'));
        exit;
    }

    if (!wp_verify_nonce($_POST['_wpnonce'], 'rank_rent_services_options-options')) {
        wp_die('Security check failed');
    }

    $services_content = $_POST['ranknrent_services_content'];
    foreach ($services_content as $service_id => $content) {
        $post_data = array(
            'ID' => $service_id,
            'post_content' => wp_kses_post($content)
        );
        wp_update_post($post_data);
    }

    // Redirect back to the settings page with a success message
    wp_redirect(admin_url('admin.php?page=rank_rent_services_content&updated=1'));
    exit;
}
add_action('admin_post_update_ranknrent_services_content', 'ranknrent_save_service_content');

// AJAX handler for content generation
function ranknrent_generate_service_content() {
    error_log('ranknrent_generate_service_content called');
    
    if (!check_ajax_referer('generate_content_nonce', 'nonce', false)) {
        wp_send_json_error('Invalid nonce');
        return;
    }

    if (!isset($_POST['service_id'])) {
        wp_send_json_error('Service ID not provided');
        return;
    }

    $service_id = intval($_POST['service_id']);
    $service = get_post($service_id);

    if (!$service || $service->post_type !== 'services') {
        wp_send_json_error('Invalid service');
        return;
    }

    $prompt = ranknrent_get_service_prompt($service->post_title);
    
    if (!$prompt) {
        wp_send_json_error('Prompt file not found');
        return;
    }

    error_log('Calling Claude API with prompt: ' . $prompt);
    $generated_content = claude_api_call($prompt);

    if (is_wp_error($generated_content)) {
        wp_send_json_error($generated_content->get_error_message());
        return;
    }

    // Update the post meta
    update_post_meta($service_id, '_service_content', wp_kses_post($generated_content));

    wp_send_json_success($generated_content);
}
add_action('wp_ajax_generate_service_content', 'ranknrent_generate_service_content');

// Function to get service prompt
function ranknrent_get_service_prompt($service_title) {
    $prompt_file = get_template_directory() . '/prompts/service_prompt.txt';
    if (!file_exists($prompt_file)) {
        return false;
    }
    $prompt = file_get_contents($prompt_file);
    return str_replace('{service_title}', $service_title, $prompt);
}

// Enqueue JavaScript for AJAX functionality
function ranknrent_enqueue_admin_scripts($hook) {
    if ('rank-rent_page_rank_rent_services_content' !== $hook) {
        return;
    }

    wp_enqueue_script('ranknrent-admin-js', get_template_directory_uri() . '/js/admin-services.js', array('jquery'), '1.0', true);
    wp_localize_script('ranknrent-admin-js', 'ranknrentAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('generate_content_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'ranknrent_enqueue_admin_scripts');

// Register settings and fields
function ranknrent_register_services_settings() {
    register_setting('rank_rent_services_options', 'ranknrent_services_content');

    add_settings_section(
        'ranknrent_services_section',
        'Edit Services Content',
        'ranknrent_services_section_callback',
        'rank_rent_services'
    );
}
add_action('admin_init', 'ranknrent_register_services_settings');

function ranknrent_services_section_callback() {
    $services = get_posts(array('post_type' => 'services', 'posts_per_page' => -1));
    
    echo '<table class="form-table">';
    foreach ($services as $service) {
        $editor_id = 'service_content_' . $service->ID;
        $permalink = get_permalink($service->ID);
        
        echo '<tr>';
        echo '<td>';
        echo '<h3>' . esc_html($service->post_title) . ' <a href="' . esc_url($permalink) . '" target="_blank">(View Service)</a></h3>';
        
        $settings = array(
            'textarea_name' => "ranknrent_services_content[{$service->ID}]",
            'textarea_rows' => 10,
            'media_buttons' => true,
            'teeny' => false,
            'quicktags' => true,
            'tinymce' => array(
                'toolbar1' => 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                'toolbar2' => 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
            ),
        );

        wp_editor($service->post_content, $editor_id, $settings);
        
        echo '<button type="button" class="button generate-content" data-service-id="' . esc_attr($service->ID) . '">Generate Content</button>';
        echo '<span class="spinner" style="float:none;"></span>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function ranknrent_service_field_callback($args) {
    $service = $args['service'];
    $content = get_post_meta($service->ID, '_service_content', true);
    $editor_id = 'service_content_' . $service->ID;
    $permalink = get_permalink($service->ID);
    
    echo '<th scope="row">';
    echo esc_html($service->post_title);
    echo '<br><a href="' . esc_url($permalink) . '" target="_blank">View Service</a>';
    echo '</th>';
    echo '<td>';
    
    $settings = array(
        'textarea_name' => "ranknrent_services_content[{$service->ID}]",
        'textarea_rows' => 10,
        'media_buttons' => true,
        'teeny' => false,
        'quicktags' => true,
        'tinymce' => array(
            'toolbar1' => 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
            'toolbar2' => 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
        ),
    );

    wp_editor($content, $editor_id, $settings);
    
    echo '<button type="button" class="button generate-content" data-service-id="' . esc_attr($service->ID) . '">Generate Content</button>';
    echo '<span class="spinner" style="float:none;"></span>';
    echo '</td>';
}

function ranknrent_admin_styles() {
    ?>
    <style>
        .form-table td h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .form-table td h3 a {
            font-size: 14px;
            font-weight: normal;
        }
        .form-table td {
            padding-top: 30px;
            padding-bottom: 30px;
        }
    </style>
    <?php
}
add_action('admin_head', 'ranknrent_admin_styles');
