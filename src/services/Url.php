<?php

namespace FluentC\Services;

use FluentC\Services\Connect;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL Editting Functions
 */

 class Url {

	protected $page_url;

	private $fluentc_connect;

    public function __construct() {

		$this->fluentc_connect = new Connect();
    }
 	public function get_canonical_url( $url , $widgetapikey ) {
		
		$prefixes = $this->fluentc_connect->getLanguageList($widgetapikey);
		$newUrl = $this->removeMatchingPrefixFromUrl($url, $prefixes);
		
		return $newUrl;
		//Remove Language Code
        
    }

	public function get_base($url) {
		$url_parts = parse_url($url);
        

        // Rebuild the URL with the language code
        $base__url = $url_parts['scheme'] . '://' . $url_parts['host'];
		return $base__url;
	}

	public function get_url_query($url, $widgetapikey) {
		
		$prefixes = $this->fluentc_connect->getLanguageList($widgetapikey);
		$newPath = $this->removeMatchingPrefixFromUrlPath($url, $prefixes);
        
		return $newPath;
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
	function removeMatchingPrefixFromUrl($url, $prefixes) {
		// Parse the URL and get the path part
		$parsedUrl = parse_url($url);
		$path = $parsedUrl['path'];
		
		foreach ($prefixes as $prefix) {
			
			// Check if the path starts with the current prefix
			if (substr($path, 0, strlen("/".$prefix)) === "/".$prefix) {
				// Remove the prefix from the path
				$path = substr($path, strlen("/".$prefix));
				break;
			}
		}
	
		// Reconstruct and return the modified URL
		return (isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '')
			. (isset($parsedUrl['host']) ? $parsedUrl['host'] : '')
			. $path
			. (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '')
			. (isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '');
	}

	function removeMatchingPrefixFromUrlPath($url, $prefixes) {
		// Parse the URL and get the path part
		$parsedUrl = parse_url($url);
		$path = $parsedUrl['path'];
		
		foreach ($prefixes as $prefix) {
			
			// Check if the path starts with the current prefix
			if (substr($path, 0, strlen("/".$prefix)) === "/".$prefix) {
				// Remove the prefix from the path
				$path = substr($path, strlen("/".$prefix));
				break;
			}
		}
	
		// Reconstruct and return the modified URL
		return $path
			. (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '')
			. (isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '');
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