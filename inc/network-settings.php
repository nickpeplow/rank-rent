<?php

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

class RR_Network_Settings {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('network_admin_menu', array($this, 'add_network_settings_page'));
        add_action('admin_init', array($this, 'register_network_settings'));
        add_action('network_admin_edit_rankandrent_update_network_settings', array($this, 'update_network_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function add_network_settings_page() {
        add_menu_page(
            'RankAndRent Network Settings',
            'RankAndRent',
            'manage_network_options',
            'rankandrent-network-settings',
            array($this, 'network_settings_page_callback'),
            'dashicons-admin-multisite', // You can change this icon
            30 // Menu position
        );
    }

    public function network_settings_page_callback() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="<?php echo esc_url(network_admin_url('edit.php?action=rankandrent_update_network_settings')); ?>" method="post">
                <?php
                settings_fields('rr_network_settings');
                do_settings_sections('rankandrent-network-settings');
                submit_button('Save Network Settings');
                ?>
            </form>
        </div>
        <?php
    }

    public function register_network_settings() {
        add_settings_section(
            'rr_network_settings',
            'General Settings',
            array($this, 'network_settings_callback'),
            'rankandrent-network-settings' // Changed from 'rr-network-settings'
        );

        // Add site niche setting
        add_settings_field(
            'rr_site_niche',
            'Site Niche',
            array($this, 'site_niche_callback'),
            'rankandrent-network-settings', // Changed from 'rr-network-settings'
            'rr_network_settings'
        );

        register_setting('rr_network_settings', 'rr_site_niche');
    }

    public function network_settings_callback() {
        echo '<p>General settings for the RankAndRent theme across the network.</p>';
    }

    public function site_niche_callback() {
        $value = get_network_option(get_main_network_id(), 'rr_site_niche', '');
        ?>
        <input type="text" id="rr_site_niche" name="rr_site_niche" value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description">Enter the primary niche for your network sites (e.g., "Real Estate", "Dentistry", "Law").</p>
        <?php
    }

    public function update_network_settings() {
        check_admin_referer('rr_network_settings-options');

        if (isset($_POST['rr_site_niche'])) {
            $site_niche = sanitize_text_field($_POST['rr_site_niche']);
            update_network_option(get_main_network_id(), 'rr_site_niche', $site_niche);
        }

        wp_redirect(add_query_arg(array('page' => 'rankandrent-network-settings', 'updated' => 'true'), network_admin_url('admin.php')));
        exit;
    }

    public function enqueue_admin_styles($hook) {
        if ($hook !== 'toplevel_page_rankandrent-network-settings') {
            return;
        }

        wp_add_inline_style('admin-menu', $this->get_admin_css());
    }

    private function get_admin_css() {
        return "
        .form-table th {
            padding-top: 20px;
        }
        .form-table td {
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .form-table td input[type='text'] {
            margin-top: 0;
        }
        .form-table td .description {
            margin-top: 5px;
        }
        ";
    }

    public static function get_site_niche($default = '') {
        return get_network_option_with_fallback('rr_site_niche', $default);
    }
}

// Initialize the network settings
RR_Network_Settings::get_instance();

// Function to get a network option with a single site fallback
function rr_get_network_option($option_name, $default = false) {
    if (is_multisite()) {
        return get_network_option(get_main_network_id(), $option_name, $default);
    } else {
        return get_option($option_name, $default);
    }
}

// Add this function at the bottom of the file
function rr_get_site_niche($default = '') {
    if (is_multisite()) {
        return get_network_option(get_main_network_id(), 'rr_site_niche', $default);
    } else {
        return get_option('rr_site_niche', $default);
    }
}
