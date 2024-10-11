<?php
/**
 * Site Details API
 *
 * This file contains functions to register and handle REST API endpoints
 * for retrieving and updating site details.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register REST API routes for site details
 */
function ranknrent_register_site_details_api() {
    // Register GET endpoint for retrieving site details
    register_rest_route('ranknrent/v1', '/site-details', array(
        'methods' => 'GET',
        'callback' => 'ranknrent_get_site_details_api',
        'permission_callback' => '__return_true'
    ));

    // Register POST endpoint for updating site details
    register_rest_route('ranknrent/v1', '/site-details', array(
        'methods' => 'POST',
        'callback' => 'ranknrent_update_site_details_api',
        'permission_callback' => function() {
            return current_user_can('manage_options');
        }
    ));
}
add_action('rest_api_init', 'ranknrent_register_site_details_api');

/**
 * Callback function to retrieve site details
 *
 * @return array Site details including location, phone, email, and address
 */
function ranknrent_get_site_details_api() {
    return array(
        'site_location' => get_option('site_location', ''),
        'site_phone' => get_option('site_phone', ''),
        'site_email' => get_option('site_email', ''),
        'site_address' => get_option('site_address', ''),
    );
}

/**
 * Callback function to update site details
 *
 * @param WP_REST_Request $request The request object
 * @return WP_REST_Response|WP_Error The response object or WP_Error on failure
 */
function ranknrent_update_site_details_api($request) {
    $params = $request->get_params();
    $updated = false;
    $messages = array();

    $fields = array('site_location', 'site_phone', 'site_address');

    foreach ($fields as $field) {
        if (isset($params[$field])) {
            $old_value = get_option($field);
            $new_value = sanitize_text_field($params[$field]);
            if ($old_value !== $new_value) {
                $result = update_option($field, $new_value);
                if ($result) {
                    $updated = true;
                    $messages[] = "Updated $field from '$old_value' to '$new_value'";
                } else {
                    $messages[] = "Failed to update $field";
                }
            } else {
                $messages[] = "No change for $field: value remains '$old_value'";
            }
        } else {
            $messages[] = "Field $field not provided in request";
        }
    }

    if ($updated) {
        return new WP_REST_Response(array(
            'message' => 'Site details updated successfully',
            'details' => $messages
        ), 200);
    } else {
        return new WP_REST_Response(array(
            'message' => 'No changes were made to site details',
            'details' => $messages
        ), 200);  // Changed from 400 to 200
    }
}

add_action('rest_api_init', function () {
    register_rest_route('ranknrent/v1', '/site-details', array(
        'methods' => 'POST',
        'callback' => 'ranknrent_update_site_details_api',
        'permission_callback' => function() {
            return current_user_can('manage_options');
        }
    ));
});
