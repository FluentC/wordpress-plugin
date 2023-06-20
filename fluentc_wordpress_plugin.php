<?php
/**
 * Plugin Name: FluentC WordPress Plugin
 * Plugin URI: https://github.com/fluentc/wordpress-plugin
 * Description: A plugin that enables website owners to easily install the FluentC web widget on their WordPress site.
 * Version: 1.0.0
 * Author: FluentC
 * Author URI: https://fluentc.io
 * License: GPL2
 */

require_once('fluentc_plugin.php');

$fluentc_plugin = new FluentCPlugin();

register_activation_hook(__FILE__, array($fluentc_plugin, 'activate'));
register_deactivation_hook(__FILE__, array($fluentc_plugin, 'deactivate'));

add_action('the_content', function ($content) use ($fluentc_plugin) {
    $fluentc_widget = $fluentc_plugin->insert_fluentc_widget();
    if($fluentc_plugin->get_insert_language_dropdown()){
        $content = $fluentc_widget[0] . $fluentc_widget[1] . $content; 
    }else{
        $content = $fluentc_widget[0] . $content; // Append the widget to the content
    }
    return $content;
});

add_action('admin_menu', function() use ($fluentc_plugin) {
    add_menu_page(
        'FluentC Web Widget Settings', // Page title
        'FluentC Web Widget', // Menu title
        'manage_options', // Capability required to access the menu
        'fluentc-settings', // Menu slug
        array($fluentc_plugin, 'settings_page'), // Callback function
        'dashicons-admin-generic', // Menu icon
        99 // Menu position
    );
});

add_filter('template_include', function($template) {
    if (is_page('fluentc-settings')) {
        return plugin_dir_path(__FILE__) . 'fluentc_settings.php';
    }
    return $template;
});
