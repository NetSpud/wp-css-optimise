<?php
function handle_page_load()
{
    $allowed_styles = get_option("permitted_stylesheets", ""); //allow certain stylesheets that are garunteed to be needed on all pages, such as the admin bar and the ubermenu font stylesheet;

    //split by "," and remove any whitespace
    $permitted_stylesheets = array_map('trim', explode(",", $allowed_styles));


    $value = get_post_meta(get_the_ID(), 'css_optimise_key', true);
    $stylesheetName = get_post_meta(get_the_ID(), 'css_optimise_file', true);

    if (is_admin()) {
        return; // Return if the request is for an administrative interface page
    }

    if (!$value) return;

    //check for the performance mode query string, if it is false, return, othrwise, continue
    if (isset($_GET['performance_mode'])) {
        if ($_GET['performance_mode'] === "false") return;
    }


    if ($value == "performance" && !empty($stylesheetName)) {

        global $wp_styles;

        //remove all other stylesheets except those in $permitted_stylesheets, and the sbp-performance stylesheet which is the minified and stripped version of the
        if (is_object($wp_styles) && property_exists($wp_styles, 'queue')) {
            foreach ($wp_styles->queue as $handle) {
                if (!in_array($handle, $permitted_stylesheets)) {
                    wp_dequeue_style($handle);
                }
            }
        }


        wp_enqueue_style('sbp-performance',  plugin_dir_url(__DIR__) . "optimised_css/" . $stylesheetName);
    }
}
