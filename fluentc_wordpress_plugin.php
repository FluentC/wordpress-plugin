<?php

/**
 * Plugin Name: FluentC Translation
 * Plugin URI: https://github.com/fluentc/wordpress-plugin
 * Description: A plugin that enables website owners to easily install the FluentC web widget on their WordPress site.
 * Version: 1.2.0
 * Author: FluentC
 * Author URI: https://www.fluentc.io
 * License: GPL2
 */
define( 'FLUENTC_DIR', __DIR__ );
define( 'FLUENTC_SLUG', 'fluentc_translation' );


require_once('fluentc_plugin.php');

$fluentc_plugin = new FluentCPlugin();

function activate() {
    /**
     * Called when the plugin is activated and sets up any necessary database tables or options
     */
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/bootstrap.php';
    
   // $fluentc_plugin->add_menu();
   Context_FluentC::fluentc_get_context()->activate_plugin();
   flush_rewrite_rules();
}

function deactivate() {
    /**
     * Called when the plugin is deactivated and cleans up any database tables or options
     */
    flush_rewrite_rules();
  //  $fluentc_plugin->remove_menu();
}



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

register_activation_hook(__FILE__, 'activate');
register_deactivation_hook(__FILE__,  'deactivate');


function fluentc_plugin_loaded() {
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/bootstrap.php';
		fluentc_init();
	
}

add_action( 'plugins_loaded', 'fluentc_plugin_loaded' , 10);

/*




*/