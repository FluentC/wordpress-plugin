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

 class Frontpage {
    public function __construct() {
    
    }

protected function is_front_page( ) {

		return true;
	}

}