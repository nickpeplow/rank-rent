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
    error_log('claude_api_call called with prompt: ' . $prompt);
    
    $api_key = get_claude_api_key();
    if (empty($api_key)) {
        error_log('Claude API key is not set');
        return new WP_Error('no_api_key', 'Claude API key is not set');
    }

    $body = [
        'model' => 'claude-3-5-sonnet-20240620',
        'max_tokens' => 4096,
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ],
    ];

    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'headers' => [
            'x-api-key' => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ],
        'body' => json_encode($body),
        'timeout' => 60,
    ]);

    if (is_wp_error($response)) {
        error_log('API request failed: ' . $response->get_error_message());
        return new WP_Error('api_error', 'API request failed: ' . $response->get_error_message());
    }

    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body, true);

    if (isset($data['error'])) {
        $error_message = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error';
        $error_type = isset($data['error']['type']) ? $data['error']['type'] : 'Unknown type';
        error_log("Claude API error: Type: $error_type, Message: $error_message");
        return new WP_Error('claude_api_error', "Error type: $error_type. Message: $error_message");
    }

    error_log('Claude API response: ' . print_r($data, true));
    return $data['content'][0]['text'] ?? '';
}
