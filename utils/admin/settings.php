<?php

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function css_optimise_settings_init()
{
    // Register a new setting for "css_optimise" page.
    register_setting('css_optimise', 'endpoint_url');
    register_setting('css_optimise', 'excluded_urls');
    register_setting('css_optimise', 'permitted_stylesheets');

    // Register a new section in the "css_optimise" page.
    add_settings_section(
        'id_css_optimise',
        "",
        "",
        'css_optimise'
    );

    // Register a new field in the "id_css_optimise" section, inside the "css_optimise" page.
    add_settings_field(
        'endpoint_url', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('API Endpoint URL', 'css_optimise'),
        'endpoint_url_cb',
        'css_optimise',
        'id_css_optimise',
        array(
            'label_for'         => 'endpoint_url',
        )
    );
    add_settings_field(
        'excluded_urls', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('Excluded URLs for API', 'css_optimise'),
        'excluded_urls_cb',
        'css_optimise',
        'id_css_optimise',
        array(
            'label_for'         => 'excluded_urls',
        )
    );
    add_settings_field(
        'permitted_stylesheets', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('Permitted, loadable stylesheets', 'css_optimise'),
        'permitted_loadable_urls_cb',
        'css_optimise',
        'id_css_optimise',
        array(
            'label_for'         => 'permitted_stylesheets',
        )
    );
}

/**
 * Register our css_optimise_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'css_optimise_settings_init');



function endpoint_url_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $val = get_option('endpoint_url');
?>
    <input style="width: 22rem;" type="text" name="<?php echo esc_attr($args['label_for']); ?>" placeholder="endpoint here" value="<?php echo $val; ?>" />
<?php
}
function excluded_urls_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $val = get_option('excluded_urls');
?>
    <input style="width: 22rem;" type="text" name="<?php echo esc_attr($args['label_for']); ?>" placeholder="excluded URLS" value="<?php echo $val; ?>" />
<?php
}
function permitted_loadable_urls_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $val = get_option('permitted_stylesheets');
?>
    <input style="width: 22rem;" type="text" name="<?php echo esc_attr($args['label_for']); ?>" placeholder="Permitted stylesheets" value="<?php echo $val; ?>" />
<?php
}



function css_optimise_options_page()
{
    add_submenu_page(
        'options-general.php',
        'CSS Optimisation Settings',
        'CSS Optimisation',
        'manage_options',
        'css_optimise',
        'css_optimise_options_page_html'
    );
}



add_action('admin_menu', 'css_optimise_options_page');



function css_optimise_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('css_optimise_messages', 'css_optimise_message', __('Settings Saved', 'css_optimise'), 'updated');
    }

    // show error/update messages
    settings_errors('css_optimise_messages');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post" id="settings-form">
            <?php
            // output security fields for the registered setting "css_optimise"
            settings_fields('css_optimise');
            // output setting sections and their fields
            // (sections are registered for "css_optimise", each field is registered to a specific section)
            do_settings_sections('css_optimise');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}


//add js file for settings config

function enqueue_settings_JS($page)
{
    if ($page !== 'settings_page_css_optimise') {
        return;
    }
    wp_enqueue_script('settings-js', plugin_dir_url(__DIR__) . "../js/css_settings.js", '1.0', true);
}

add_action('admin_enqueue_scripts', 'enqueue_settings_JS');
