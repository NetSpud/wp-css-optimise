<?php


function load_scripts()
{
    $asset_file = include(plugin_dir_path(__FILE__) . '../../../../build/index.asset.php');

    // wp_enqueue_script(
    //     'example-editor-scripts',
    //     plugins_url('../../../build/index.js', __FILE__),
    //     $asset_file['dependencies'],
    //     $asset_file['version']
    // );

    wp_enqueue_script(
        'example-editor-scripts',
        plugins_url('../../../../build/index.js', __FILE__),
        array('wp-plugins', 'wp-editor', "wp-edit-post", "wp-components", 'react'),
        $asset_file['version']
    );
    // wp_register_style(
    //     'plugin-sidebar-css',
    //     plugins_url('../../../style.css', __FILE__)
    // );
    // wp_enqueue_style('plugin-sidebar-css');
}
add_action('enqueue_block_editor_assets', 'load_scripts');
