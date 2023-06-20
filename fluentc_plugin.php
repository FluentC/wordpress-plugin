<?php
/**
 * FluentC WordPress Plugin
 */

class FluentCPlugin {
    /**
     * Handles the main functionality of the FluentC WordPress Plugin
     */

    private $api_key;

    public function __construct() {
        /**
         * Initializes the FluentCPlugin class and sets up any necessary variables
         */
        $this->api_key = null;
        $this->dropdown = false;
    }

    public function activate() {
        /**
         * Called when the plugin is activated and sets up any necessary database tables or options
         */
        $this->add_menu();
    }

    public function deactivate() {
        /**
         * Called when the plugin is deactivated and cleans up any database tables or options
         */
        $this->remove_menu();
    }

    public function add_menu() {
        /**
         * Adds a menu item to the WordPress admin dashboard for the plugin settings
         */
        add_menu_page('FluentC Settings', 'FluentC', 'manage_options', 'fluentc-settings', array($this, 'settings_page'));
    }

    public function remove_menu() {
        /**
         * Removes the menu item from the WordPress admin dashboard for the plugin settings
         */
        remove_menu_page('fluentc-settings');
    }

    public function settings_page() {
        /**
         * Displays the plugin settings page in the WordPress admin dashboard
         */
        if (isset($_POST['submit'])) {
            try {
                $api_key = isset($_POST['fluentc_api_key']) ? sanitize_text_field($_POST['fluentc_api_key']) : '';
                $dropdown = isset($_POST['fluentc_insert_language_dropdown']);
                $this->save_settings($api_key, $dropdown);
                add_settings_error('fluentc-settings', 'fluentc_api_key_saved', 'FluentC API key saved.', 'updated');
            } catch (Exception $e) {
                add_settings_error('fluentc-settings', 'fluentc_api_key_error', 'Error saving FluentC API key: ' . $e->getMessage(), 'error');
            }
        }
        include('fluentc_settings.php');
    }

    public function save_settings($api_key, $dropdown) {
        /**
         * Saves the user's FluentC API key to the WordPress database
         */
        update_option('fluentc_api_key', $api_key);
        update_option('fluentc_insert_language_dropdown', $dropdown);
        $this->api_key = $api_key;
        $this->dropdown = $dropdown;
    }

    public function get_settings() {
        /**
         * Retrieves the user's FluentC API key from the WordPress database
         */
        $api_key = get_option('fluentc_api_key');
        return $api_key;
    }

    public function get_insert_language_dropdown() {
        /**
         * Retrieves the user's FluentC API key from the WordPress database
         */
        $dropdown = get_option('fluentc_insert_language_dropdown');
        return $dropdown;
    }
    

    public function insert_fluentc_widget() {
        /**
         * Inserts the FluentC web widget code into the header of the site and the "<div id="fluentc-widget"></div>" in the top of the content body
         */
        $header_code = '<script src="https://widget.fluentc.io/fluentcWidget.min.js"></script>' . "\n";
        $header_code .= '<script>' . "\n";
        $header_code .= '    document.addEventListener("DOMContentLoaded", function () {' . "\n";
        $header_code .= '        f = new fluentcWidget({widgetID: "' . $this->get_settings() . '"});' . "\n";
        $header_code .= '        f.setupWidget(\'fluentc-widget\');' . "\n";
        $header_code .= '    });' . "\n";
        $header_code .= '</script>' . "\n";

        $body_code = '<div id="fluentc-widget"></div>' . "\n";

        return array($header_code, $body_code);
    }
}
