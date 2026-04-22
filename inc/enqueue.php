<?php
/**
 * GNN Enqueue Functions
 */

function gnn_antigravity_scripts()
{
    wp_enqueue_style('gnn-antigravity-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap', array(), null);
    wp_enqueue_style('gnn-antigravity-style', get_stylesheet_uri(), array(), '1.4.2');
    wp_enqueue_style('gnn-antigravity-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.4.2');

    // ... (accent color code) ...

    // External Libraries
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
    wp_enqueue_script('lenis', 'https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js', array(), '1.0.33', true);
    wp_enqueue_script('swup', 'https://unpkg.com/swup@4', array(), '4.0.0', true);

    wp_enqueue_script('gnn-antigravity-main', get_template_directory_uri() . '/assets/js/main.js', array('gsap', 'lenis', 'swup'), '1.4.2', true);
}
add_action('wp_enqueue_scripts', 'gnn_antigravity_scripts');

function gnn_antigravity_block_editor_assets()
{
    wp_enqueue_script(
        'gnn-antigravity-blocks',
        get_template_directory_uri() . '/assets/js/blocks.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        '1.4.0'
    );
}
add_action('enqueue_block_editor_assets', 'gnn_antigravity_block_editor_assets');
