<?php

namespace FluentC\Services;

if (!defined('ABSPATH')) {
    exit;
}


class Connect
{
    private $fluentc_cache;

    public function __construct()
    {
        $this->fluentc_cache = new Cache();
    }
    function getLanguageList($widgetID)
    {
        //Add Caching Key
        $languages = [];
        if ($this->fluentc_cache->get('language_list')) {
            $languages = $this->fluentc_cache->get('language_list');
        } else {

            //If Widget ID is Set
            $widget = $this->fetchWidgetOptions($widgetID);

            // Not adding the default language
            //$languages[] = $widget->data->fetchWidgetOptions->sourceLanguage;

            $getAllLanguages = $this->getAvailableLanguages($widget->data->fetchWidgetOptions->environmentID);
            // fetchWidget WidgetID
            foreach ($getAllLanguages->data->getAvailableLanguages->body as $key => $value) {
                $languages[] = $value->code;
            }
            // fetch available languages Environment ID
            $this->fluentc_cache->set('language_list', $languages);

        }
        return $languages;
    }
    function fetchWidgetOptions($widgetID)
    {
        // Setup the endpoint URL
        $url = 'https://api.fluentc.io/graphql';

        // Prepare the GraphQL query with the dynamic widgetID
        $data = [
            "query" => "{\n  fetchWidgetOptions(\n    widgetID: \"$widgetID\"\n    host: \"https://www.fluentc.io/\"\n  ) {\n    environmentID\n    name\n    display\n    tags\n    disabled\n    sourceLanguage\n  }\n}",
            "type" => "fetchWidgetOptions"
        ];
        $json_data = json_encode($data);

        $curl = curl_init();

        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'content-type: application/json',
            'x-api-key: da2-wtkl5bpofjbu5ex5iugu4o2mbm'
        ));
        curl_setopt($curl, CURLOPT_REFERER, 'https://www.fluentc.io/');
        // Make sure to set this option
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Execute the cURL request and fetch response
        $response = curl_exec($curl);
        //echo "Raw response: " . $response;  // Debugging line
        // Check for errors
        if (curl_errno($curl)) {
            echo 'Error in cURL: ' . curl_error($curl);
        }

        // Close the cURL session
        curl_close($curl);
        if (!json_decode($response)) {
            echo 'Invalid JSON response';
            return null;
        }
        // Return the response
        return json_decode($response);
    }

    function getAvailableLanguages($environmentID)
    {
        // Setup the endpoint URL
        $url = 'https://api.fluentc.io/graphql';

        // Prepare the GraphQL query with the dynamic widgetID
        $data = [
            "query" => "{\n  getAvailableLanguages(\n    environmentID: \"$environmentID\"\n ) {\n  body\n {\n  code\n }}\n}"
        ];
        $json_data = json_encode($data);

        $curl = curl_init();

        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'content-type: application/json',
            'x-api-key: da2-wtkl5bpofjbu5ex5iugu4o2mbm'

        ));
        curl_setopt($curl, CURLOPT_REFERER, 'https://www.fluentc.io/');
        // Make sure to set this option
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Execute the cURL request and fetch response 
        $response = curl_exec($curl);
        //echo "Raw response: " . $response;  // Debugging line

        // Check for errors
        if (curl_errno($curl)) {
            echo 'Error in cURL: ' . curl_error($curl);
        }

        // Close the cURL session
        curl_close($curl);

        // Return the response
        return json_decode($response);
    }
}