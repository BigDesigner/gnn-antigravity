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
    // Cache Busting: Use filemtime in debug mode, theme version in production
    $theme_version = wp_get_theme()->get( 'Version' );
    $css_ver = WP_DEBUG && file_exists(get_template_directory() . '/assets/css/main.css') ? filemtime( get_template_directory() . '/assets/css/main.css' ) : $theme_version;
    $js_ver  = WP_DEBUG && file_exists(get_template_directory() . '/assets/js/main.js') ? filemtime( get_template_directory() . '/assets/js/main.js' ) : $theme_version;

    // Theme stylesheets (Google Fonts loaded by customizer.php dynamically)
    wp_enqueue_style('gnn-antigravity-style', get_stylesheet_uri(), array(), $theme_version);
    wp_enqueue_style('gnn-antigravity-main', get_template_directory_uri() . '/assets/css/main.css', array('gnn-antigravity-style'), $css_ver);

    // External Libraries
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
    wp_enqueue_script('lenis', 'https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js', array(), '1.0.33', true);
    wp_enqueue_script('swup', 'https://unpkg.com/swup@4', array(), '4.0.0', true);

    // Theme main script
    wp_enqueue_script('gnn-antigravity-main', get_template_directory_uri() . '/assets/js/main.js', array('gsap', 'lenis', 'swup'), $js_ver, true);

    // Pass theme settings to JS
    wp_localize_script('gnn-antigravity-main', 'gnnSettings', array(
        'backToTop' => get_theme_mod('footer_show_back_to_top', false),
        'customCursor' => get_theme_mod('enable_custom_cursor', true),
    ));
}
add_action('wp_enqueue_scripts', 'gnn_antigravity_scripts');

/**
 * Filter to add 'defer' attribute to specific scripts for performance.
 *
 * @param string $tag    The `<script>` tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @return string
 */
function gnn_add_defer_attribute( $tag, $handle ) {
    $scripts_to_defer = array( 'gsap', 'lenis', 'swup', 'gnn-antigravity-main' );

    // If script is in array and doesn't already have defer
    if ( in_array( $handle, $scripts_to_defer, true ) && strpos( $tag, 'defer' ) === false ) {
        return str_replace( ' src', ' defer="defer" src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'gnn_add_defer_attribute', 10, 2 );


/**
 * Add resource hints for faster external connections.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function gnn_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        // Preconnect to CDNs for libraries
        $urls[] = array(
            'href'        => 'https://cdnjs.cloudflare.com',
            'crossorigin' => 'anonymous',
        );
        $urls[] = array(
            'href'        => 'https://unpkg.com',
            'crossorigin' => 'anonymous',
        );
        
        // Security & Analytics
        if ( get_theme_mod( 'enable_turnstile', false ) ) {
            $urls[] = 'https://challenges.cloudflare.com';
        }
        if ( ! empty( get_theme_mod( 'recaptcha_site_key', '' ) ) ) {
            $urls[] = 'https://www.google.com';
        }
        if ( ! empty( get_theme_mod( 'ga4_measurement_id', '' ) ) ) {
            $urls[] = 'https://www.googletagmanager.com';
        }
    }

    return $urls;
}
add_filter( 'wp_resource_hints', 'gnn_resource_hints', 10, 2 );

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
