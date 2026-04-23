<?php
/**
 * GNN Enqueue Functions
 *
 * Handles all script and style registration/enqueueing.
 * Google Fonts are loaded dynamically via customizer.php based on user selection.
 *
 * @package GNN-antigravity
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue front-end styles and scripts.
 *
 * @return void
 */
function gnn_antigravity_scripts()
{
    // Theme stylesheets (Google Fonts loaded by customizer.php dynamically)
    wp_enqueue_style('gnn-antigravity-style', get_stylesheet_uri(), array(), '1.5.0');
    wp_enqueue_style('gnn-antigravity-main', get_template_directory_uri() . '/assets/css/main.css', array('gnn-antigravity-style'), '1.5.0');

    // External Libraries
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
    wp_enqueue_script('lenis', 'https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js', array(), '1.0.33', true);
    wp_enqueue_script('swup', 'https://unpkg.com/swup@4', array(), '4.0.0', true);

    // Theme main script
    wp_enqueue_script('gnn-antigravity-main', get_template_directory_uri() . '/assets/js/main.js', array('gsap', 'lenis', 'swup'), '1.5.0', true);

    // Pass theme settings to JS
    wp_localize_script('gnn-antigravity-main', 'gnnSettings', array(
        'backToTop' => get_theme_mod('footer_show_back_to_top', false),
    ));
}
add_action('wp_enqueue_scripts', 'gnn_antigravity_scripts');

/**
 * Enqueue block editor assets.
 *
 * @return void
 */
function gnn_antigravity_block_editor_assets()
{
    wp_enqueue_script(
        'gnn-antigravity-blocks',
        get_template_directory_uri() . '/assets/js/blocks.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        '1.5.0'
    );
}
add_action('enqueue_block_editor_assets', 'gnn_antigravity_block_editor_assets');
