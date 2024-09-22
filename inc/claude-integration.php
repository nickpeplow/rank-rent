<?php
// Claude AI Integration

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to get the Claude API key
function get_claude_api_key() {
    return get_option('claude_api_key', '');
}

// Function to set the Claude API key
function set_claude_api_key($api_key) {
    update_option('claude_api_key', $api_key);
}

// Function to make API calls to Claude
function claude_api_call($prompt) {
    $api_key = get_claude_api_key();
    if (empty($api_key)) {
        return new WP_Error('no_api_key', 'Claude API key is not set');
    }

    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'headers' => [
            'x-api-key' => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'claude-3-5-sonnet-20240620',
            'max_tokens' => 1024,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]),
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('api_error', 'API request failed: ' . $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['error'])) {
        $error_message = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error';
        $error_type = isset($data['error']['type']) ? $data['error']['type'] : 'Unknown type';
        return new WP_Error('claude_api_error', "Error type: $error_type. Message: $error_message");
    }

    return $data['content'][0]['text'] ?? '';
}

// Function to generate content using Claude
function generate_claude_content($field_name, $original_content) {
    $prompt = "You are an AI assistant for a local business website. Please rewrite the following content, maintaining the same meaning but making it unique and suitable for a local business website. The content is for the field '{$field_name}'. Here's the original content:\n\n{$original_content}\n\nPlease provide the rewritten content:";
    
    $generated_content = claude_api_call($prompt);
    
    if (is_wp_error($generated_content)) {
        error_log('Claude API Error: ' . $generated_content->get_error_message());
        return $original_content; // Return original content if there's an error
    }
    
    return $generated_content;
}
