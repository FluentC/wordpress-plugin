<?php
/**
 * FluentC WordPress Plugin
 */

class FluentCPlugin {
    /**
     * Handles the main functionality of the FluentC WordPress Plugin
     */

    private $api_key;

    public function __construct() {
        /**
         * Initializes the FluentCPlugin class and sets up any necessary variables
         */
        $this->api_key = null;
        $this->dropdown = false;
       // $this->add_menu();
    }
    public function add_menu() {
        /**
         * Adds a menu item to the WordPress admin dashboard for the plugin settings
         */
        add_menu_page('FluentC Settings', 'FluentC', 'manage_options', 'fluentc-settings', array($this, 'settings_page'));
    }
    
    public function remove_menu() {
        /**
         * Removes the menu item from the WordPress admin dashboard for the plugin settings
         */
        remove_menu_page('fluentc-settings');
    }
    public function settings_page() {
        /**
         * Displays the plugin settings page in the WordPress admin dashboard
         */
        if (isset($_POST['submit'])) {
            try {
                $api_key = isset($_POST['fluentc_api_key']) ? sanitize_text_field($_POST['fluentc_api_key']) : '';
                $dropdown = isset($_POST['fluentc_insert_language_dropdown']);
                $this->save_settings($api_key, $dropdown);
                add_settings_error('fluentc-settings', 'fluentc_api_key_saved', 'FluentC API key saved.', 'updated');
            } catch (Exception $e) {
                add_settings_error('fluentc-settings', 'fluentc_api_key_error', 'Error saving FluentC API key: ' . $e->getMessage(), 'error');
            }
        }
        include('fluentc_settings.php');
    }

    public function save_settings($api_key, $dropdown) {
        /**
         * Saves the user's FluentC API key to the WordPress database
         */
        update_option('fluentc_api_key', $api_key);
        update_option('fluentc_insert_language_dropdown', $dropdown);
        $this->api_key = $api_key;
        $this->dropdown = $dropdown;
    }

    public function get_settings() {
        /**
         * Retrieves the user's FluentC API key from the WordPress database
         */
        $api_key = get_option('fluentc_api_key');
        return $api_key;
    }

    public function get_insert_language_dropdown() {
        /**
         * Retrieves the user's FluentC API key from the WordPress database
         */
        $dropdown = get_option('fluentc_insert_language_dropdown');
        return $dropdown;
    }
    

    
    public function get_languages($fluentc) {
        /**
         * Fetch languages from FluentC
         */
        $api_key = $this->get_settings();
		$option = $fluentc->fetchWidgetOptions($api_key);
     	$environmentID = $option->data->fetchWidgetOptions->environmentID;
		$data = $fluentc->getAvailableLanguages($environmentID);
		// Extract the codes
$codes = [];
foreach ($data->data->getAvailableLanguages->body as $language) {
    $codes[] = $language->code;
}

// $codes now contains all the language codes
print_r($codes);
			$structure = get_option( 'permalink_structure' );
		echo $structure;
        return  $language;
    }
    public function get_current_language() {
        return "en";
    }
    public function get_all_content_types() {
        /**
         * Fetches all registered content types (post types) in this WordPress installation
         * 
         * @return array An array of post type names
         */
        $args = array(
           'public'   => true, // To get only the public post types
        );

        // Get all public post types
        $post_types = get_post_types($args, 'names', 'and'); 

        return $post_types;
    }
    public function content_types_page() {
        ?>
        <div class="wrap">
            <h2>Translatable Content Types</h2>
            
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th scope="col">Post Type</th>
                            <th scope="col">Post Count</th>
                            <th scope="col">Total Characters (Excluding HTML)</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $post_types = $this->get_all_content_types();
                        $grand_total_characters = 0; // Initialize total character count

                        foreach ($post_types as $type) {
                            $count_posts = wp_count_posts($type);
                            $count = $count_posts->publish;
                             // Call the function to get character counts for each post type
                        $character_counts = $this->get_post_character_counts($type);

                        // Calculate total character count for the post type
                        $total_characters = array_sum($character_counts);
                        $grand_total_characters += $total_characters; // Add to grand total

                        echo '<tr>';
                        echo '<td>' . $type . '</td>';
                        echo '<td>' . $count . '</td>';
                        echo '<td>' . $total_characters . '</td>';
                        
                            echo '</tr>';
                        }
                    ?>
                        <tr> 
                            <td colspan="2">Estimated Total Number of Characters</td>
                            <?php echo '<td><strong>' . $grand_total_characters . '</strong></td>'; ?>
                        </tr>
                        <tr> 
                            <td colspan="2">Estimated FluentC Credits needed per language (Headers, Footers and Navigation will add to the count)</td>
                            <?php $fluentc_credits = $grand_total_characters * .03; echo '<td><strong>' . $fluentc_credits . '</strong></td>'; ?>
                        </tr>
                   <!-- <tr>
                        <td colspan="3">Actions Run Translations - Edit since last translation - Notification to run translation</td>
                    </tr> -->
                    </tbody>
                </table>
            </div>
        <?php
    }

    public function get_post_character_counts($post_type) {
        $args = array(
            'posts_per_page' => -1, // Retrieve all posts
            'post_type' => $post_type, // Use the provided post type
            // Additional arguments as needed
        );

        $query = new WP_Query($args);
        $character_counts = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $content = get_the_content();
                $content_without_html = wp_strip_all_tags($content);
                $character_count = mb_strlen($content_without_html);
                $character_counts[get_the_ID()] = $character_count;
            }
        }

        wp_reset_postdata();
        return $character_counts;
    }

    
}
