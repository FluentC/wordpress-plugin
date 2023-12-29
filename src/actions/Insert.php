<?php

namespace FluentC\Actions;

use FluentC\Models\Hooks;
use FluentC\Services\Widget;
use FluentC\Services\Connect;

class Insert implements Hooks
{
    private $fluentc_widget_c;

    private $fluentc_connect;

    public function __construct()
    {
        $this->fluentc_widget_c = new Widget();
        $this->fluentc_connect = new Connect();
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
            foreach ($languages as $language) {
                echo "<link rel=\"alternate\" hreflang=" . $language . " href=\"url_of_page\" /> \n";
            }
        } else {
            echo "FluentC API Not Set";
        }
    }
}