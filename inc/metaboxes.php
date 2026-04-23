<?php
/**
 * GNN Meta Box Controls
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function gnn_add_metadata_metabox()
{
    $screens = array('post', 'page');
    foreach ($screens as $screen) {
        add_meta_box('gnn_metadata_box', esc_html__('Post/Page Visibility Settings', 'gnn-antigravity'), 'gnn_render_metadata_metabox', $screen, 'side', 'high');
    }
}
add_action('add_meta_boxes', 'gnn_add_metadata_metabox');

function gnn_render_metadata_metabox($post)
{
    wp_nonce_field('gnn_save_metadata_nonce', 'gnn_metadata_nonce');
    $meta = array(
        'hide_title' => get_post_meta($post->ID, '_gnn_hide_title', true),
        'hide_date' => get_post_meta($post->ID, '_gnn_hide_date', true),
        'hide_author' => get_post_meta($post->ID, '_gnn_hide_author', true),
        'hero_type' => get_post_meta($post->ID, '_gnn_hero_type', true) ?: 'default',
        'hero_media' => get_post_meta($post->ID, '_gnn_hero_media', true),
        'hero_text' => get_post_meta($post->ID, '_gnn_hero_custom_text', true),
    );

    echo '<div class="gnn-metabox-content">';
    echo '<h4>' . esc_html__('Visibility Settings', 'gnn-antigravity') . '</h4>';
    foreach (array('title', 'date', 'author') as $f) {
        printf('<p><label><input type="checkbox" name="gnn_hide_%s" value="1" %s> %s</label></p>', $f, checked($meta["hide_$f"], '1', false), esc_html__("Hide " . ucfirst($f), 'gnn-antigravity'));
    }

    echo '<hr><h4>' . esc_html__('Hero Media Settings', 'gnn-antigravity') . '</h4>';
    echo '<select name="gnn_hero_type" style="width:100%">';
    $opts = array('default' => 'Default', 'custom_text' => 'Custom Text', 'image' => 'Image', 'video' => 'Video', 'hidden' => 'Hidden');
    foreach ($opts as $v => $l) {
        printf('<option value="%s" %s>%s</option>', esc_attr($v), selected($meta['hero_type'], $v, false), esc_html__($l, 'gnn-antigravity'));
    }
    echo '</select>';

    echo '<p><input type="text" name="gnn_hero_media" value="' . esc_attr($meta['hero_media']) . '" style="width:100%" placeholder="Media URL"></p>';
    echo '<p><textarea name="gnn_hero_custom_text" style="width:100%" placeholder="Custom Hero Text">' . esc_textarea($meta['hero_text']) . '</textarea></p>';
    echo '</div>';
}

function gnn_save_metadata_metabox($post_id)
{
    // Sanity checks
    if (!isset($_POST['gnn_metadata_nonce']) || !wp_verify_nonce($_POST['gnn_metadata_nonce'], 'gnn_save_metadata_nonce'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (wp_is_post_revision($post_id))
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    $fields = array(
        'gnn_hide_title' => 'sanitize_text_field',
        'gnn_hide_date' => 'sanitize_text_field',
        'gnn_hide_author' => 'sanitize_text_field',
        'gnn_hero_type' => 'sanitize_text_field',
        'gnn_hero_media' => 'esc_url_raw',
        'gnn_hero_custom_text' => 'sanitize_textarea_field'
    );

    foreach ($fields as $f => $sanitize_func) {
        if (isset($_POST[$f])) {
            update_post_meta($post_id, '_' . $f, call_user_func($sanitize_func, $_POST[$f]));
        } else {
            delete_post_meta($post_id, '_' . $f);
        }
    }
}
add_action('save_post', 'gnn_save_metadata_metabox');
