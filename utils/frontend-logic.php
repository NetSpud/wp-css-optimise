<?php
function handle_page_load($id)
{
    $allowed_styles = get_option("permitted_stylesheets", ""); //allow certain stylesheets

    //split by "," and remove any whitespace
    $permitted_stylesheets = array_map('trim', explode(",", $allowed_styles));


    $value = get_post_meta(get_the_ID(), 'css_optimise_key', true);
    $stylesheetName = get_post_meta(get_the_ID(), 'css_optimise_file', true);

    if (is_admin()) {
        return; // Return if the request is for an administrative interface page
    }
    if (is_user_logged_in()) {
        return; // Return if the user is logged in
    }

    if (!$value) return;

    $page_types = ["post", "page"];
    $post_type = get_post_type($id);

    if (!in_array($post_type, $page_types)) return; //if not in page type, return

    //if not in page type, return


    //check for the performance mode query string, if it is false, return, othrwise, continue
    if (isset($_GET['performance_mode'])) {
        if ($_GET['performance_mode'] === "false") return;
    }


    if ($value == "performance" && !empty($stylesheetName)) {

        global $wp_styles;

        //remove all other stylesheets except those in $permitted_stylesheets, and the sbp-performance stylesheet which is the minified and stripped version of the whole page
        if (is_object($wp_styles) && property_exists($wp_styles, 'queue')) {
            foreach ($wp_styles->queue as $handle) {
                if (!in_array($handle, $permitted_stylesheets)) {
                    wp_dequeue_style($handle);
                }
            }
        }

        // wp_dequeue_style('js_composer_front');
        // wp_deregister_style('js_composer_front');
        //remove wp_bakery styling because it won't play nice with dequeueing like the rest of it

        wp_enqueue_style('sbp-performance',  plugin_dir_url(__DIR__) . "optimised_css/" . $stylesheetName);
    }
}
