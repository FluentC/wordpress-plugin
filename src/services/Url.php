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

 class URL {

    public function __construct() {
    
    }
    public function get_url() {
        $url = $this->get_url_for_url();
        if ( ! empty( $url ) ) {
            return $url;
        }
    }

    public function url_update( $current_url ) {
    
        if($this->check_for_file($current_url)){
            return $current_url;
        }
        
    
        return $current_url;
    }

    public function check_for_external_url( $current_url){

        return true;
    }
    public function check_for_internal_url( $current_url){

        return true;
    }

    public function check_for_file($current_url) { 
        $files = [
			'pdf',
			'rar',
			'doc',
			'docx',
			'jpg',
			'jpeg',
			'png',
			'svg',
			'ppt',
			'pptx',
			'xls',
			'zip',
			'mp4',
			'xlsx',
			'txt',
			'eps',
		];

		foreach ( $files as $file ) {
			if ( self::ends_with( strtolower( $current_url ), '.' . $file ) ) {
				return true;
			}
		}

		return false;
    }

    public function ends_with( $haystack, $needle ) {
		$temp       = strlen( $haystack );
		$len_needle = strlen( $needle );

		return '' === $needle ||
		       (
			       ( $temp - $len_needle ) >= 0 && strpos( $haystack, $needle, $temp - $len_needle ) !== false
		       );
	}
}