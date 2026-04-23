<?php
/**
 * GNN-antigravity functions and definitions
 *
 * @package GNN-antigravity
 */

if (!function_exists('gnn_antigravity_setup')):
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function gnn_antigravity_setup()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in four locations.
        register_nav_menus(array(
            'header-menu' => esc_html__('Header Menu', 'gnn-antigravity'),
            'footer-menu' => esc_html__('Footer Menu', 'gnn-antigravity'),
        ));

        // Switch default core markup for search form, comment form, and comments to output valid HTML5.
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Enable Site Editor styling (Appearance Tools)
        add_theme_support('appearance-tools');

        // Enable Block Templates support for the Site Editor
        add_theme_support('block-templates');

        // Remove Core Block Patterns
        remove_theme_support('core-block-patterns');

        add_theme_support('custom-logo', array(
            'height' => 100,
            'width' => 400,
            'flex-height' => true,
            'flex-width' => true,
        ));

    }
endif;
add_action('after_setup_theme', 'gnn_antigravity_setup');

/**
 * Remove any lingering block patterns
 */
function gnn_remove_all_patterns()
{
    $patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();
    foreach ($patterns as $pattern) {
        unregister_block_pattern($pattern['name']);
    }
}
add_action('init', 'gnn_remove_all_patterns', 100);

/**
 * Expert Refactor: Load modular functions
 */
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/metaboxes.php';
require get_template_directory() . '/inc/elementor-compat.php';
require get_template_directory() . '/inc/seo.php';

/**
 * Custom Block registration logic can stay here or move to inc/blocks.php if it grows.
 * Currently keeping it here for immediate Expert visibility.
 */
// (Any additional hooks or helper functions go here)
