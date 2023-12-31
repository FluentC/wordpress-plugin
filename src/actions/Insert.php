<?php

namespace FluentC\Actions;

use FluentC\Models\Hooks;
use FluentC\Services\Url;
use FluentC\Services\Widget;
use FluentC\Services\Connect;

class Insert implements Hooks
{
    private $fluentc_widget_c;

    private $fluentc_url;

    private $fluentc_connect;

    public function __construct()
    {
        $this->fluentc_widget_c = new Widget();
        $this->fluentc_connect = new Connect();
        $this->fluentc_url = new Url();
    }
    public function hooks()
    {

        add_action('wp_head', array($this, 'insert_fluentc_widget'));
        add_action('wp_head', array($this, 'insert_hrefLang'));
    }

    public function insert_fluentc_widget()
    {
        $language_code = get_query_var('fluentc_language');
        //if language code issset
        if($language_code){
            $fluentc_widget = $this->fluentc_widget_c->insert_fluentc_widget(false, $language_code);
        }
        else {
        $fluentc_widget = $this->fluentc_widget_c->insert_fluentc_widget(true, "en");
        }

        echo $fluentc_widget[0];

        return true;
    }
    public function insert_hrefLang()
    {
        $widgetapikey = get_option('fluentc_api_key');
        if ($widgetapikey) {
            $languages = $this->fluentc_connect->getLanguageList($widgetapikey);
            //Canocial URL
            $current_url = home_url( $_SERVER['REQUEST_URI'] );
            
            echo "<link rel=\"canonical\" href=\"".$this->fluentc_url->get_canonical_url($current_url, $widgetapikey)."\"/> \n";

            foreach ($languages as $language) {
                echo "<link rel=\"alternate\" hreflang=\"". $language ."\" href=\"".$this->fluentc_url->get_base($current_url)."/" . $language . $this->fluentc_url->get_url_query($current_url, $widgetapikey)."\" /> \n";
            }
        } else {
            echo "FluentC API Not Set";
        }
    }
}