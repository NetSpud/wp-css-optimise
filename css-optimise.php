<?php
/*
Plugin Name: CSS Optimise
Description: This plugin will optimise the CSS for user-specified pages by removing unwanted CSS and generating a leaner, optimised stylesheet based on the content on the page
Version: 1.0.0
Author: Edward Morris
*/

require_once('utils/admin/page.php');
require_once('utils/admin/settings.php');
require_once('utils/frontend-logic.php');

add_action('add_meta_boxes', 'wporg_add_custom_box');
add_action('save_post', 'wporg_save_postdata');
add_action('wp_print_styles', 'handle_page_load');


function remove_unwanted_styling()
{

    if (!is_admin()) {
        wp_dequeue_style('js_composer_front');
        wp_deregister_style('js_composer_front');
    }
}

add_action('wp_print_styles', 'remove_unwanted_styling', 99);




require_once('utils/generate-stylesheet.php');

add_action('admin_enqueue_scripts', 'enqueue_client_side_script');

add_action('wp_ajax_wporg_ajax_change', 'css_optimise_generate_stylesheet_callback');
// add_action('admin_enqueue_scripts', 'enqueue_settings_JS');
