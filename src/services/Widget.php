<?php

namespace FluentC\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get Current URL
 * 
 * Look for Links
 * * Alter a href
 * 
 * Look for External Links
 * * Dont alter links
 * 
 * Look for file links
 * * Dont Alter links
 * 
 */

 class Widget {
    public function __construct() {
    
    }

    public function insert_fluentc_widget($default, $lang) {
        /**
         * Inserts the FluentC web widget code into the header of the site and the "<div id="fluentc-widget"></div>" in the top of the content body
         */
        $widgetapikey = get_option('fluentc_api_key');
        if ($widgetapikey) {
            if($default == false){
                $init_lang = ', { defaultLanguage: "' . $lang . '"}';	
                } else {
                    $init_lang = '';
                };
                 
                $header_code = '<script src="https://widget.fluentc.io/fluentcWidget.min.js"></script>' . "\n";
                $header_code .= '<script>' . "\n";
                $header_code .= '    document.addEventListener("DOMContentLoaded", function () {' . "\n";
                $header_code .= '        f = new fluentcWidget({widgetID: "'.$widgetapikey.'", updateWordpressUrl: true});' . "\n";
                $header_code .= '        f.setupWidget(\'fluentc-widget\' '.$init_lang.');' . "\n";
                $header_code .= '    });' . "\n";
                $header_code .= '</script>' . "\n";
        
                $body_code = '<div id="fluentc-widget"></div>' . "\n";
        
                return array($header_code, $body_code);
        } else {
            return array("FluentC API Not Set", "FluentC API Not Set");
		
    }


}

 }