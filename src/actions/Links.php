<?php

namespace FluentC\Actions;

use FluentC\Models\Hooks;
use FluentC\Services\Connect;

/* 
 *  Permalinks Actions
 */

class Links implements Hooks
{

    private $fluentc_connect;

    public function __construct()
    {

        $this->fluentc_connect = new Connect();
    }
    public function hooks()
    {

        add_filter('post_link', array($this, 'add_language_code_to_permalink'), 10, 3);
        add_filter('page_link', array($this, 'add_language_code_to_permalink'), 10, 3);
        add_filter('post_type_link', array($this, 'add_language_code_to_permalink'), 10, 3);
        add_filter('category_link', array($this, 'add_language_code_to_permalink'), 10, 3);
        add_filter('tag_link', array($this, 'add_language_code_to_permalink'), 10, 3);
        add_filter('author_link', array($this, 'add_language_code_to_permalink'), 10, 3);
        add_filter('query_vars', array($this, 'language_var'), 10, 3);
        add_action('init', array($this, 'url_rewrite_rule'), 10, 3);
        add_action('init', array($this, 'flush_rewrite'), 100);
    }

    function add_language_code_to_permalink($permalink, $post)
    {
        // Assuming you have a function that returns the current language code
        $language_code = get_query_var('fluentc_language');

        // If there's no language code, return the original permalink
        if (empty($language_code)) {
            return $permalink;
        }

        // Modify the permalink to add the language code
        $url_parts = parse_url($permalink);
        $path = '/' . $language_code . $url_parts['path'];

        // Rebuild the URL with the language code
        $new_permalink = $url_parts['scheme'] . '://' . $url_parts['host'] . $path;
        if (!empty($url_parts['query'])) {
            $new_permalink .= '?' . $url_parts['query'];
        }
        if (!empty($url_parts['fragment'])) {
            $new_permalink .= '#' . $url_parts['fragment'];
        }

        return $new_permalink;
    }
public function flush_rewrite() {
    flush_rewrite_rules();
}
    function language_var($vars)
    {
        $vars[] = 'fluentc_language'; // 'fluentc_language' is the name of path variable
        return $vars;
    }

    function get_all_woocommerce_account_endpoints() {
        // Get all WC endpoints
        $wc_endpoints = WC()->query->get_query_vars();
    
        // Filter out non-account endpoints and keep only the slugs
        $account_endpoints = array();
        foreach ($wc_endpoints as $key => $value) {
            if (strpos($key, 'endpoint') !== false && !empty($value)) {
                $account_endpoints[] = $value;
            }
        }
    
        return $account_endpoints;
    }
    

    function url_rewrite_rule()
    {

        $widgetapikey = get_option('fluentc_api_key');
        if ($widgetapikey) {
            $languages = $this->fluentc_connect->getLanguageList($widgetapikey);

            //For Pretty Permalinks
            foreach ($languages as $language) {
                //Pages & Posts
                add_rewrite_rule(
                    '^' . $language . '/(.+)/?', // 'someprefix' is a placeholder, replace with your desired URL structure
                    'index.php?pagename=$matches[1]&fluentc_language=' . $language . '', // 'value' is the value assigned to 'fluentc_language'
                    'top'
                );
                //Categories
                add_rewrite_rule(
                    '' . $language . '/category/(.+?)/?$',
                    'index.php?category_name=$matches[1]&fluentc_language=' . $language . '',
                    'top'
                );

                //Tags 
                add_rewrite_rule(
                    '' . $language . '/tag/(.+?)/?$',
                    'index.php?tag=$matches[1]&fluentc_language=' . $language . '',
                    'top'
                );

                //Author
                add_rewrite_rule(
                    '' . $language . '/author/(.+?)/?$',
                    'index.php?author_name=$matches[1]&fluentc_language=' . $language . '',
                    'top'
                );
                //WooCommerce Support



                //WooCommerce Shop Page
                if (function_exists('wc_get_page_id')) {
                    //Products

                    //wc_get_page_id() 
                    //includes/wc-page-functions.php : 42
                    //Retrieve page ids - used for myaccount, edit_address, shop, cart, checkout, pay, view_order, terms. returns -1 if no page is found.
                    
                    add_rewrite_rule(
                        '^' . $language . '/product/([^/]+)/?$',
                        'index.php?post_type=product&name=$matches[1]&fluentc_language=' . $language . '',
                        'top');
                    
                        $myaccount_page_id = wc_get_page_id('myaccount');
                        if ($myaccount_page_id > 0) {
                            $myaccount_page_url = get_permalink($myaccount_page_id);
                            $myaccount_url_path = trim(parse_url($myaccount_page_url, PHP_URL_PATH), '/');
                            
                            // Use this function to get all WooCommerce account endpoints
                            $all_endpoints = $this->get_all_woocommerce_account_endpoints();
                                                
                            foreach ($all_endpoints as $endpoint) {
                                add_rewrite_rule(
                                    '^' . $language . '/' . $myaccount_url_path . '/' . $endpoint . '/?$',
                                    'index.php?page_id=' . $myaccount_page_id . '&fluentc_language=' . $language . '&wc_endpoint_url=' . $endpoint,
                                    'top'
                                );
                            }
                        }

                    $shop_page_id = wc_get_page_id('shop');
                    if ($shop_page_id > 0) {
                       
                        $shop_page_url = get_permalink($shop_page_id);
                        $shop_url_path = trim(parse_url($shop_page_url, PHP_URL_PATH), '/');
                        add_rewrite_rule(
                            '^' . $language . '/' . $shop_url_path . '/([^/]+)/?$',
                            'index.php?post_type=page&name=$matches[1]&fluentc_language=' . $language,
                            'top');
                    }
                    // Cart Pages
                    $cart_page_id = wc_get_page_id('cart');
                    if ($cart_page_id > 0) {
                        
                        $cart_page_url = get_permalink($cart_page_id);
                        $cart_url_path = trim(parse_url($cart_page_url, PHP_URL_PATH), '/');
                        add_rewrite_rule(
                            '^' . $language . '/' . $cart_url_path . '/([^/]+)/?$',
                            'index.php?post_type=page&name=$matches[1]&fluentc_language=' . $language,
                            'top');
                    }
                    // Checkout Pages
                    $checkout_page_id = wc_get_page_id('checkout');
                    if ($checkout_page_id > 0) {
                       
                        $checkout_page_url = get_permalink($checkout_page_id);
                        $checkout_url_path = trim(parse_url($checkout_page_url, PHP_URL_PATH), '/');
                        add_rewrite_rule(
                            '^' . $language . '/' . $checkout_url_path . '/([^/]+)/?$',
                            'index.php?post_type=page&name=$matches[1]&fluentc_language=' . $language,
                            'top');
                    }
                }
            }
        } else {
            echo "FluentC API Not Set";
        }


    }

}
