<?php






function sidebar_plugin_script_enqueue()
{
    $asset_file = include(plugin_dir_path(__FILE__) . '../../../../build/index.asset.php');
    wp_register_script(
        'example-editor-scripts',
        plugins_url('../../../../build/index.js', __FILE__),
        array('wp-plugins', 'wp-editor', "wp-edit-post", "wp-components", 'react'),
        $asset_file['version']
    );
    wp_enqueue_script('example-editor-scripts');
}
add_action('enqueue_block_editor_assets', 'sidebar_plugin_script_enqueue');

// function load_scripts()
// {
//     $asset_file = include(plugin_dir_path(__FILE__) . '../../../../build/index.asset.php');
// }
// add_action('enqueue_block_editor_assets', 'load_scripts');
