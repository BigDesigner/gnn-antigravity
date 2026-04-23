<?php
/**
 * Elementor Compatibility Layer
 *
 * Provides full Elementor (Free) compatibility by registering
 * necessary theme supports, locations, and widget area adjustments.
 * Follows Zero-Dependency principle — uses only native WP + Elementor hooks.
 *
 * @package GNN-antigravity
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Prevent direct access.
}

/**
 * Check if Elementor plugin is active.
 *
 * @return bool True if Elementor is loaded and active.
 */
function gnn_is_elementor_active() {
    return defined( 'ELEMENTOR_VERSION' );
}

/**
 * Check if the current page/post was built with Elementor.
 *
 * Uses Elementor's own meta check to determine if content
 * was created via the page builder rather than the block editor.
 *
 * @param int|null $post_id Optional. Post ID to check. Defaults to current post.
 * @return bool True if the page uses Elementor canvas.
 */
function gnn_is_elementor_page( $post_id = null ) {
    if ( ! gnn_is_elementor_active() ) {
        return false;
    }

    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    return \Elementor\Plugin::$instance->documents->get( $post_id )
        && \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor();
}

/**
 * Register Elementor theme support flags.
 *
 * - header-footer: Allows Elementor Pro or add-ons to inject custom headers/footers.
 * - elementor-full-width: Tells Elementor it may render edge-to-edge content.
 * - Removes default content width restriction on Elementor pages.
 *
 * @since 1.2.0
 * @return void
 */
function gnn_elementor_theme_support() {
    // Allow Elementor to use full-width layout on pages
    add_theme_support( 'elementor-full-width' );

    // Allow header/footer overrides by Elementor Pro or 3rd party add-ons
    add_theme_support( 'header-footer-elementor' );
}
add_action( 'after_setup_theme', 'gnn_elementor_theme_support' );

/**
 * Register Elementor Theme Locations (for Pro header/footer builder).
 *
 * Even though this theme follows a "Free Elementor" strategy, registering
 * theme locations ensures forward-compatibility if the user upgrades to Pro
 * or uses a header/footer add-on plugin.
 *
 * @since 1.2.0
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager
 * @return void
 */
function gnn_elementor_register_locations( $manager ) {
    $manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'gnn_elementor_register_locations' );

/**
 * Remove unnecessary theme wrappers on Elementor-built pages.
 *
 * When Elementor is rendering a page, the theme should avoid injecting
 * its own wrappers (like .post-canvas, .entry-content) that may conflict
 * with Elementor's own grid/container system.
 *
 * @since 1.2.0
 * @return void
 */
function gnn_elementor_body_class( $classes ) {
    if ( gnn_is_elementor_page() ) {
        $classes[] = 'elementor-page';
        $classes[] = 'gnn-elementor-active';
    }
    return $classes;
}
add_filter( 'body_class', 'gnn_elementor_body_class' );

/**
 * Disable default hero section on Elementor-built pages.
 *
 * Elementor pages typically bring their own hero/header sections.
 * Showing the theme's built-in hero would create visual duplication.
 *
 * @since 1.2.0
 * @param string $hero_type The current hero type meta value.
 * @return string Modified hero type ('hidden' if Elementor page).
 */
function gnn_elementor_disable_hero( $hero_type ) {
    if ( gnn_is_elementor_page() ) {
        return 'hidden';
    }
    return $hero_type;
}

/**
 * Enqueue Elementor compatibility CSS overrides.
 *
 * Adds CSS rules that ensure Elementor containers can go full-width
 * without being constrained by the theme's max-width settings.
 *
 * @since 1.2.0
 * @return void
 */
function gnn_elementor_compat_styles() {
    if ( ! gnn_is_elementor_active() ) {
        return;
    }

    // Load external Elementor compatibility stylesheet
    wp_enqueue_style(
        'gnn-elementor-compat',
        get_template_directory_uri() . '/assets/css/elementor-compat.css',
        array( 'gnn-antigravity-main' ),
        '1.2.0'
    );

    $css = '
        /* GNN Elementor Compatibility Overrides */
        .elementor-page .entry-content,
        .elementor-page .post-canvas,
        .elementor-page #content-area {
            max-width: none;
            padding: 0;
            margin: 0;
        }

        .elementor-page .canvas-item {
            max-width: none;
            margin: 0;
        }

        /* Ensure Elementor sections can go edge-to-edge */
        .elementor-page .elementor-section.elementor-section-stretched {
            width: 100vw !important;
            max-width: 100vw !important;
        }

        /* Remove theme entry-header when Elementor is active on a page */
        .elementor-page .entry-header {
            display: none;
        }

        /* Preserve dark theme inside Elementor editor */
        .elementor-editor-active body {
            background: var(--bg, #000) !important;
        }
    ';

    wp_add_inline_style( 'gnn-antigravity-main', $css );
}
add_action( 'wp_enqueue_scripts', 'gnn_elementor_compat_styles', 20 );

/**
 * Adjust Elementor's default content width to match theme design.
 *
 * Sets the default container width inside Elementor editor to 1200px,
 * matching the theme's .hero-content-wrapper max-width for visual
 * consistency between editor and front-end.
 *
 * @since 1.2.0
 * @return void
 */
function gnn_elementor_default_settings() {
    if ( ! gnn_is_elementor_active() ) {
        return;
    }

    // Set default content width for Elementor containers
    update_option( 'elementor_container_width', 1200, false );

    // Set default scheme colors to match theme palette
    // This only runs on theme activation, not every page load
}
// Only set defaults once on theme switch
add_action( 'after_switch_theme', 'gnn_elementor_default_settings' );
