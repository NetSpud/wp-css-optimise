<?php
/*
Plugin Name: CSS Optimise
Description: This plugin will optimise the CSS for user-specified pages by removing unwanted CSS and generating a leaner, optimised stylesheet based on the content on the page
Version: 1.0.0
Author: Edward Morris
*/

require_once('utils/admin/settings.php');
require_once('utils/frontend-logic.php');
require_once('utils/admin/database.php');
add_action('wp_print_styles', 'handle_page_load');





register_activation_hook(__FILE__, 'add_db_table');





require_once('utils/generate-stylesheet.php');


add_action('wp_ajax_wporg_ajax_change', 'css_optimise_generate_stylesheet_callback');
require_once('utils/admin/register-meta-box.php');
