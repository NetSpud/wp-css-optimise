<?php

function wporg_add_custom_box()
{
    $screens = ['post', 'page'];
    foreach ($screens as $screen) {
        add_meta_box(
            'css_optimise',
            'CSS Optimisation',
            'css_optimisation_box',
            $screen,
            "side",
            "high"
        );
    }
}


function css_optimisation_box($post)
{
    $optimisation_mode = get_post_meta($post->ID, 'css_optimise_key', true);
    $file = get_post_meta($post->ID, 'css_optimise_file', true);
?>
    <label for="css_optimise">OPTIMISATION MODE</label>

    <div id="css_optimise_page_slug" data-url="<? echo get_page_link() ?>" </div>
        <select name="css_optimise" id="css_optimise" class="postbox">
            <option value="">Select something...</option>
            <option value="performance" <?php selected($optimisation_mode, 'performance'); ?>>Perfomance Mode</option>
            <option value="default" <?php selected($optimisation_mode, 'default'); ?>>Default Mode</option>
        </select>
        <? if ($optimisation_mode === "performance") : ?>
            <small style="display: block; margin-bottom: 1rem;">Current optimised file: <br /> <span id="css_optimise_current_performance_file"><?php echo basename($file); ?></span></small>
            <div>

                <span id="css_optimise_spinner" class="spinner"></span>
                <button class="button-primary" id="css_optimise_generate_stylesheet">Regenerate Stylesheet</button>
            </div>
    </div>
<? endif; ?>
<?php
}


function wporg_save_postdata($post_id)
{

    global $post;

    //generate stylesheet when performance mode is enabled for the first time, otherwise switch back to previously generate stylesheet
    //only trigger save on certain page types
    if (!in_array($post->post_type, ['page', 'post'])) return;


    $stylesheet = get_post_meta($post_id, 'css_optimise_file', true);

    if ($stylesheet === "") { //first time
        $result = optimise_CSS(get_page_link($post_id), $post_id);
        //generate stylesheet
        //save the file path to the post meta
        update_post_meta(
            $post_id,
            'css_optimise_file',
            basename($result)
        );
    }

    if (array_key_exists('css_optimise', $_POST)) {
        update_post_meta(
            $post_id,
            'css_optimise_key',
            $_POST['css_optimise']
        );
    }
}


function enqueue_client_side_script($hook)
{
    global $post;
    //if not a post or page, return
    if (!in_array($post->post_type, ['page', 'post'])) return;
    if ('post.php' != $hook && 'post-new.php' != $hook) return;
    wp_enqueue_script('wporg_meta_box_script', plugin_dir_url(__FILE__) . '../../js/css_optimise.js', ['jquery']);
}
