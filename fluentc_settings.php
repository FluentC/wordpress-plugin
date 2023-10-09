<div class="wrap">
    <h1>FluentC Settings</h1>
    <p>To use the FluentC widget, you need FluentC Credits. If you haven't signed up yet, please visit <a href="https://dashboard.fluentc.io/signup" target="_blank">https://dashboard.fluentc.io/signup</a> to create an account.</p>
    <p>After signing up, create a widget in the FluentC dashboard and copy your widget ID into the box below. Find detailed Widget setup directions at <a href="https://docs.fluentc.io/docs/dashboard/web-widget" target="_blank">https://docs.fluentc.io/docs/dashboard/web-widget</a> </p>
    <form method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="fluentc_api_key">FluentC API Key</label></th>
                    <td><input name="fluentc_api_key" type="text" id="fluentc_api_key" value="<?php echo esc_attr($this->get_settings()); ?>" class="regular-text"></td>
                </tr>
                <tr>
            <th scope="row"><label for="fluentc_insert_language_dropdown">Automatically Insert Language Dropdown</label></th>
            <td>
                <input name="fluentc_insert_language_dropdown" type="checkbox" id="fluentc_insert_language_dropdown" value="1" <?php checked($this->get_insert_language_dropdown()); ?>>
                <p class="description">Check this box to automatically insert the language dropdown in the site's content block. Leave it unchecked if you prefer to manually add the <code>&lt;div id="fluentc-widget"&gt;&lt;/div&gt;</code> to your pages and templates.</p>
            </td>
        </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>
