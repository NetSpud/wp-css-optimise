<?php

add_action('current_screen', function ($current_screen) {
    if (
        method_exists($current_screen, 'is_block_editor') &&
        $current_screen->is_block_editor()
    ) {
        // Do something specific for the Block Editor.
        error_log('Block Editor is being used.');
        require_once('ui/latest.php');
    } else {
        // Do something specific for the Classic Editor.
        require_once('ui/legacy.php');
        error_log('Classic Editor is being used.');
    }
});
