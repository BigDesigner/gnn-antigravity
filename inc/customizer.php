<?php
/**
 * GNN Customizer Configurations
 *
 * Registers all theme Customizer sections, settings, and controls.
 * Organized into panels: General, Typography, Header, Footer, SEO.
 *
 * @package GNN-antigravity
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function gnn_antigravity_customize_register($wp_customize)
{
    // =========================================================================
    // PANEL: Theme Options
    // =========================================================================
    $wp_customize->add_panel('gnn_theme_panel', array(
        'title'    => esc_html__('GNN Theme Options', 'gnn-antigravity'),
        'priority' => 25,
    ));

    // =========================================================================
    // SECTION: Hero
    // =========================================================================
    $wp_customize->add_section('gnn_hero_section', array(
        'title'    => esc_html__('Hero Section', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 10,
    ));

    // =========================================================================
    // SECTION: General Settings
    // =========================================================================
    $wp_customize->add_section('gnn_settings', array(
        'title'    => esc_html__('General Settings', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 20,
    ));

    // =========================================================================
    // SECTION: Typography
    // =========================================================================
    $wp_customize->add_section('gnn_typography', array(
        'title'       => esc_html__('Typography', 'gnn-antigravity'),
        'panel'       => 'gnn_theme_panel',
        'priority'    => 30,
        'description' => esc_html__('Control fonts and text sizing across the theme.', 'gnn-antigravity'),
    ));

    // =========================================================================
    // SECTION: Header
    // =========================================================================
    $wp_customize->add_section('gnn_header', array(
        'title'    => esc_html__('Header', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 40,
    ));

    // =========================================================================
    // SECTION: Footer
    // =========================================================================
    $wp_customize->add_section('gnn_footer', array(
        'title'    => esc_html__('Footer', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 50,
    ));

    // =========================================================================
    // SECTION: SEO
    // =========================================================================
    $wp_customize->add_section('gnn_seo', array(
        'title'    => esc_html__('SEO Settings', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 60,
    ));

    // =========================================================================
    // SECTION: Security
    // =========================================================================
    $wp_customize->add_section('gnn_security', array(
        'title'    => esc_html__('Security Settings', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 70,
        'description' => esc_html__('Manage bot protection and security keys.', 'gnn-antigravity'),
    ));

    // ... (Security settings) ...

    // =========================================================================
    // SECTION: Analytics
    // =========================================================================
    $wp_customize->add_section('gnn_analytics', array(
        'title'    => esc_html__('Analytics Settings', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 80,
    ));

    // GA4 Measurement ID
    $wp_customize->add_setting('ga4_measurement_id', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('ga4_measurement_id', array(
        'label'       => esc_html__('GA4 Measurement ID', 'gnn-antigravity'),
        'description' => esc_html__('Enter your G-XXXXXXXXXX ID to enable Google Analytics.', 'gnn-antigravity'),
        'section'     => 'gnn_analytics',
        'type'        => 'text',
    ));


    // Turnstile Toggle
    $wp_customize->add_setting('enable_turnstile', array('default' => false, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('enable_turnstile', array(
        'label'   => esc_html__('Enable Cloudflare Turnstile', 'gnn-antigravity'),
        'section' => 'gnn_security',
        'type'    => 'checkbox',
    ));

    // Turnstile Site Key
    $wp_customize->add_setting('turnstile_site_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('turnstile_site_key', array(
        'label'   => esc_html__('Turnstile Site Key', 'gnn-antigravity'),
        'section' => 'gnn_security',
        'type'    => 'text',
    ));

    // Turnstile Secret Key
    $wp_customize->add_setting('turnstile_secret_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('turnstile_secret_key', array(
        'label'   => esc_html__('Turnstile Secret Key', 'gnn-antigravity'),
        'section' => 'gnn_security',
        'type'    => 'text',
    ));

    // reCAPTCHA v3 Site Key
    $wp_customize->add_setting('recaptcha_site_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('recaptcha_site_key', array(
        'label'   => esc_html__('reCAPTCHA v3 Site Key', 'gnn-antigravity'),
        'section' => 'gnn_security',
        'type'    => 'text',
    ));

    // reCAPTCHA v3 Secret Key
    $wp_customize->add_setting('recaptcha_secret_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('recaptcha_secret_key', array(
        'label'   => esc_html__('reCAPTCHA v3 Secret Key', 'gnn-antigravity'),
        'section' => 'gnn_security',
        'type'    => 'text',
    ));

    // =========================================================================
    // SECTION: UI Experience
    // =========================================================================
    $wp_customize->add_section('gnn_ui', array(
        'title'    => esc_html__('UI Experience', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 85,
    ));

    // Custom Cursor Toggle
    $wp_customize->add_setting('enable_custom_cursor', array('default' => true, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('enable_custom_cursor', array(
        'label'   => esc_html__('Enable Custom Magnetic Cursor', 'gnn-antigravity'),
        'section' => 'gnn_ui',
        'type'    => 'checkbox',
    ));

    // =========================================================================
    // SECTION: Slider
    // =========================================================================
    $wp_customize->add_section('gnn_slider_section', array(
        'title'    => esc_html__('Hero Slider', 'gnn-antigravity'),
        'panel'    => 'gnn_theme_panel',
        'priority' => 15,
        'description' => esc_html__('Enable and configure the hero carousel.', 'gnn-antigravity'),
    ));

    // Enable Slider
    $wp_customize->add_setting('enable_hero_slider', array('default' => false, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('enable_hero_slider', array(
        'label'   => esc_html__('Enable Hero Slider', 'gnn-antigravity'),
        'section' => 'gnn_slider_section',
        'type'    => 'checkbox',
    ));

    // Slider Height (Desktop)
    $wp_customize->add_setting('slider_height_desktop', array('default' => '100', 'sanitize_callback' => 'absint', 'transport' => 'postMessage'));
    $wp_customize->add_control('slider_height_desktop', array(
        'label'       => esc_html__('Slider Height (Desktop vh)', 'gnn-antigravity'),
        'section'     => 'gnn_slider_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 40, 'max' => 100, 'step' => 5),
    ));

    // Slider Height (Mobile)
    $wp_customize->add_setting('slider_height_mobile', array('default' => '70', 'sanitize_callback' => 'absint', 'transport' => 'postMessage'));
    $wp_customize->add_control('slider_height_mobile', array(
        'label'       => esc_html__('Slider Height (Mobile vh)', 'gnn-antigravity'),
        'section'     => 'gnn_slider_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 40, 'max' => 100, 'step' => 5),
    ));

    // Autoplay Speed
    $wp_customize->add_setting('slider_speed', array('default' => '6000', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('slider_speed', array(
        'label'   => esc_html__('Autoplay Speed (ms)', 'gnn-antigravity'),
        'section' => 'gnn_slider_section',
        'type'    => 'select',
        'choices' => array(
            '3000' => esc_html__('3 Seconds', 'gnn-antigravity'),
            '5000' => esc_html__('5 Seconds', 'gnn-antigravity'),
            '6000' => esc_html__('6 Seconds', 'gnn-antigravity'),
            '8000' => esc_html__('8 Seconds', 'gnn-antigravity'),
            '0'    => esc_html__('Manual Only', 'gnn-antigravity'),
        ),
    ));

    // UI Controls
    $wp_customize->add_setting('slider_show_nav', array('default' => true, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('slider_show_nav', array(
        'label'   => esc_html__('Show Nav Arrows', 'gnn-antigravity'),
        'section' => 'gnn_slider_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('slider_show_dots', array('default' => true, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('slider_show_dots', array(
        'label'   => esc_html__('Show Pagination Dots', 'gnn-antigravity'),
        'section' => 'gnn_slider_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('slider_pause_hover', array('default' => true, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('slider_pause_hover', array(
        'label'   => esc_html__('Pause on Hover', 'gnn-antigravity'),
        'section' => 'gnn_slider_section',
        'type'    => 'checkbox',
    ));


    // Slide loop (3 slides)
    for ($i = 1; $i <= 3; $i++) {
        // Image
        $wp_customize->add_setting("slider_image_{$i}", array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "slider_image_{$i}", array(
            'label'    => sprintf(esc_html__('Slide %d Image', 'gnn-antigravity'), $i),
            'section'  => 'gnn_slider_section',
        )));

        // Title
        $wp_customize->add_setting("slider_title_{$i}", array('default' => '', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage'));
        $wp_customize->add_control("slider_title_{$i}", array(
            'label'    => sprintf(esc_html__('Slide %d Title', 'gnn-antigravity'), $i),
            'section'  => 'gnn_slider_section',
            'type'     => 'text',
        ));

        // Subtitle
        $wp_customize->add_setting("slider_subtitle_{$i}", array('default' => '', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage'));
        $wp_customize->add_control("slider_subtitle_{$i}", array(
            'label'    => sprintf(esc_html__('Slide %d Subtitle', 'gnn-antigravity'), $i),
            'section'  => 'gnn_slider_section',
            'type'     => 'textarea',
        ));

        // Link
        $wp_customize->add_setting("slider_link_{$i}", array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
        $wp_customize->add_control("slider_link_{$i}", array(
            'label'    => sprintf(esc_html__('Slide %d Link URL', 'gnn-antigravity'), $i),
            'section'  => 'gnn_slider_section',
            'type'     => 'url',
        ));
    }

    // =====================================================================
    // SETTINGS: Hero
    // =====================================================================

    $hero_settings = array(
        'hero_title'    => array('default' => esc_html__('Build the new way.', 'gnn-antigravity'), 'label' => esc_html__('Hero Title', 'gnn-antigravity'), 'type' => 'text'),
        'hero_subtitle' => array('default' => esc_html__('Experimental workspace for agentic development.', 'gnn-antigravity'), 'label' => esc_html__('Hero Subtitle', 'gnn-antigravity'), 'type' => 'textarea'),
    );
    foreach ($hero_settings as $id => $args) {
        $wp_customize->add_setting($id, array('default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field'));
        $wp_customize->add_control($id, array('label' => $args['label'], 'section' => 'gnn_hero_section', 'type' => $args['type']));
    }

    // =====================================================================
    // SETTINGS: General
    // =====================================================================
    $general_settings = array(
        'logo_text'       => array('default' => 'GNN_ANTIGRAVITY', 'label' => esc_html__('Site Logo Text', 'gnn-antigravity'), 'type' => 'text'),
        'show_post_title' => array('default' => true, 'label' => esc_html__('Show Titles', 'gnn-antigravity'), 'type' => 'checkbox'),
        'show_post_date'  => array('default' => true, 'label' => esc_html__('Show Dates', 'gnn-antigravity'), 'type' => 'checkbox'),
        'show_post_author'=> array('default' => false, 'label' => esc_html__('Show Author', 'gnn-antigravity'), 'type' => 'checkbox'),
    );
    foreach ($general_settings as $id => $args) {
        $sanitize = ($args['type'] === 'checkbox') ? 'gnn_sanitize_checkbox' : 'sanitize_text_field';
        $wp_customize->add_setting($id, array('default' => $args['default'], 'sanitize_callback' => $sanitize));
        $wp_customize->add_control($id, array('label' => $args['label'], 'section' => 'gnn_settings', 'type' => $args['type']));
    }

    // =====================================================================
    // SETTINGS: Typography
    // =====================================================================

    // -- Font Family
    $wp_customize->add_setting('gnn_font_family', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('gnn_font_family', array(
        'label'   => esc_html__('Primary Font Family', 'gnn-antigravity'),
        'section' => 'gnn_typography',
        'type'    => 'select',
        'choices' => array(
            'Inter'          => 'Inter',
            'Roboto'         => 'Roboto',
            'Open Sans'      => 'Open Sans',
            'Montserrat'     => 'Montserrat',
            'Space Grotesk'  => 'Space Grotesk',
            'JetBrains Mono' => 'JetBrains Mono',
            'Outfit'         => 'Outfit',
            'system-ui'      => esc_html__('System Default', 'gnn-antigravity'),
        ),
    ));

    // -- Heading Font Family
    $wp_customize->add_setting('gnn_heading_font', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('gnn_heading_font', array(
        'label'       => esc_html__('Heading Font Family', 'gnn-antigravity'),
        'description' => esc_html__('Leave empty to use primary font.', 'gnn-antigravity'),
        'section'     => 'gnn_typography',
        'type'        => 'select',
        'choices'     => array(
            ''               => esc_html__('— Same as Primary —', 'gnn-antigravity'),
            'Inter'          => 'Inter',
            'Roboto'         => 'Roboto',
            'Montserrat'     => 'Montserrat',
            'Space Grotesk'  => 'Space Grotesk',
            'JetBrains Mono' => 'JetBrains Mono',
            'Outfit'         => 'Outfit',
        ),
    ));

    // -- Base Font Size
    $wp_customize->add_setting('gnn_base_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('gnn_base_font_size', array(
        'label'       => esc_html__('Base Font Size (px)', 'gnn-antigravity'),
        'section'     => 'gnn_typography',
        'type'        => 'number',
        'input_attrs' => array('min' => 12, 'max' => 24, 'step' => 1),
    ));

    // -- Letter Spacing
    $wp_customize->add_setting('gnn_letter_spacing', array(
        'default'           => '0.15',
        'sanitize_callback' => 'gnn_sanitize_float',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('gnn_letter_spacing', array(
        'label'       => esc_html__('Letter Spacing (em)', 'gnn-antigravity'),
        'section'     => 'gnn_typography',
        'type'        => 'number',
        'input_attrs' => array('min' => 0, 'max' => 0.5, 'step' => 0.01),
    ));

    // =====================================================================
    // SETTINGS: Header
    // =====================================================================
    $wp_customize->add_setting('header_sticky', array('default' => 'fixed', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('header_sticky', array(
        'label'   => esc_html__('Header Positioning', 'gnn-antigravity'),
        'section' => 'gnn_header',
        'type'    => 'select',
        'choices' => array('fixed' => esc_html__('Sticky', 'gnn-antigravity'), 'absolute' => esc_html__('Normal', 'gnn-antigravity')),
    ));

    $wp_customize->add_setting('header_bg_type', array('default' => 'transparent', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('header_bg_type', array(
        'label'   => esc_html__('Header Background Style', 'gnn-antigravity'),
        'section' => 'gnn_header',
        'type'    => 'radio',
        'choices' => array('transparent' => esc_html__('Transparent', 'gnn-antigravity'), 'colored' => esc_html__('Solid', 'gnn-antigravity')),
    ));

    $wp_customize->add_setting('enable_mobile_menu', array('default' => true, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('enable_mobile_menu', array(
        'label'   => esc_html__('Enable Mobile Menu', 'gnn-antigravity'),
        'section' => 'gnn_header',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('header_height', array('default' => '80', 'sanitize_callback' => 'absint', 'transport' => 'postMessage'));
    $wp_customize->add_control('header_height', array(
        'label'       => esc_html__('Header Height (px)', 'gnn-antigravity'),
        'section'     => 'gnn_header',
        'type'        => 'number',
        'input_attrs' => array('min' => 50, 'max' => 150, 'step' => 5),
    ));

    // =====================================================================
    // SETTINGS: Footer
    // =====================================================================
    $wp_customize->add_setting('copyright_text', array('default' => '© GNNcreative', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('copyright_text', array(
        'label'   => esc_html__('Copyright Text', 'gnn-antigravity'),
        'section' => 'gnn_footer',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('footer_bg_type', array('default' => 'transparent', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('footer_bg_type', array(
        'label'   => esc_html__('Footer Background Style', 'gnn-antigravity'),
        'section' => 'gnn_footer',
        'type'    => 'radio',
        'choices' => array('transparent' => esc_html__('Transparent', 'gnn-antigravity'), 'colored' => esc_html__('Solid', 'gnn-antigravity')),
    ));

    $wp_customize->add_setting('copyright_url', array('default' => 'https://gnn.tr', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control('copyright_url', array(
        'label'   => esc_html__('Copyright Link URL', 'gnn-antigravity'),
        'section' => 'gnn_footer',
        'type'    => 'url',
    ));

    $wp_customize->add_setting('footer_show_back_to_top', array('default' => false, 'sanitize_callback' => 'gnn_sanitize_checkbox'));
    $wp_customize->add_control('footer_show_back_to_top', array(
        'label'   => esc_html__('Show Back-to-Top Button', 'gnn-antigravity'),
        'section' => 'gnn_footer',
        'type'    => 'checkbox',
    ));

    // =====================================================================
    // SETTINGS: SEO (Customizer-level)
    // =====================================================================
    $wp_customize->add_setting('gnn_seo_home_description', array(
        'default'           => get_bloginfo('description'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('gnn_seo_home_description', array(
        'label'       => esc_html__('Home Page Meta Description', 'gnn-antigravity'),
        'description' => esc_html__('Used for search engine and social media previews. Max 160 chars.', 'gnn-antigravity'),
        'section'     => 'gnn_seo',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('gnn_seo_twitter_handle', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('gnn_seo_twitter_handle', array(
        'label'   => esc_html__('Twitter/X Handle', 'gnn-antigravity'),
        'section' => 'gnn_seo',
        'type'    => 'text',
    ));

    // =====================================================================
    // COLORS (shared across Header/Footer)
    // =====================================================================
    $colors = array(
        'header_bg_color' => array('label' => esc_html__('Header Background Color', 'gnn-antigravity'), 'section' => 'gnn_header', 'default' => '#000000'),
        'footer_bg_color' => array('label' => esc_html__('Footer Background Color', 'gnn-antigravity'), 'section' => 'gnn_footer', 'default' => '#000000'),
        'accent_color'    => array('label' => esc_html__('Accent Color', 'gnn-antigravity'), 'section' => 'gnn_settings', 'default' => '#00f2ff'),
    );
    foreach ($colors as $id => $args) {
        $wp_customize->add_setting($id, array('default' => $args['default'], 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage'));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array(
            'label'   => $args['label'],
            'section' => $args['section'],
        )));
    }
}
add_action('customize_register', 'gnn_antigravity_customize_register');

/**
 * Sanitize checkbox values for Customizer.
 *
 * @param mixed $input Raw input value.
 * @return bool Sanitized boolean.
 */
function gnn_sanitize_checkbox($input)
{
    return (isset($input) && true == $input ? true : false);
}

/**
 * Sanitize float values for Customizer.
 *
 * @param mixed $input Raw input value.
 * @return float Sanitized float.
 */
function gnn_sanitize_float($input)
{
    return floatval($input);
}

/**
 * Output dynamic CSS from Customizer settings.
 *
 * Injects typography and layout custom properties into the
 * document head so all theme components can reference them.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_customizer_dynamic_css()
{
    $font_family    = get_theme_mod('gnn_font_family', 'Inter');
    $heading_font   = get_theme_mod('gnn_heading_font', '');
    $base_size      = absint(get_theme_mod('gnn_base_font_size', 16));
    $letter_spacing = floatval(get_theme_mod('gnn_letter_spacing', 0.15));
    $accent_color   = get_theme_mod('accent_color', '#00f2ff');
    $header_height    = absint(get_theme_mod('header_height', 80));
    $slider_h_desktop = absint(get_theme_mod('slider_height_desktop', 100));
    $slider_h_mobile  = absint(get_theme_mod('slider_height_mobile', 70));

    $heading_stack = ! empty($heading_font) ? esc_attr($heading_font) : esc_attr($font_family);

    $css = sprintf(
        ':root {
            --font-main: "%s", system-ui, -apple-system, sans-serif;
            --font-heading: "%s", system-ui, -apple-system, sans-serif;
            --base-size: %dpx;
            --tracking: %sem;
            --accent-color: %s;
            --header-height: %dpx;
            --slider-height-desktop: %dvh;
            --slider-height-mobile: %dvh;
        }
        body { font-size: var(--base-size); }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-heading); }',
        esc_attr($font_family),
        $heading_stack,
        $base_size,
        esc_attr($letter_spacing),
        esc_attr($accent_color),
        $header_height,
        $slider_h_desktop,
        $slider_h_mobile
    );

    wp_add_inline_style('gnn-antigravity-main', $css);
}
add_action('wp_enqueue_scripts', 'gnn_customizer_dynamic_css', 25);

/**
 * Dynamically load the correct Google Font based on Customizer selection.
 *
 * Builds a Google Fonts URL from the selected primary and heading fonts,
 * replacing the hardcoded Inter-only enqueue.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_enqueue_google_fonts()
{
    $primary = get_theme_mod('gnn_font_family', 'Inter');
    $heading = get_theme_mod('gnn_heading_font', '');

    // system-ui doesn't need Google Fonts
    $families = array();
    if ($primary !== 'system-ui') {
        $families[] = str_replace(' ', '+', $primary) . ':wght@300;400;600;700';
    }
    if (! empty($heading) && $heading !== $primary && $heading !== 'system-ui') {
        $families[] = str_replace(' ', '+', $heading) . ':wght@400;600;700';
    }

    if (! empty($families)) {
        $url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $families) . '&display=swap';
        wp_enqueue_style('gnn-google-fonts', $url, array(), null);
    }
}
add_action('wp_enqueue_scripts', 'gnn_enqueue_google_fonts', 5);

/**
 * Register Selective Refresh partials for real-time Customizer updates.
 *
 * Partials allow specific sections of the page to update instantly
 * when their associated Customizer setting changes, without a full
 * page reload. Only text-based settings that render in templates
 * benefit from partials.
 *
 * @since 1.3.0
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function gnn_customizer_selective_refresh($wp_customize)
{
    // Ensure selective refresh is available
    if (! isset($wp_customize->selective_refresh)) {
        return;
    }

    // Logo Text
    $wp_customize->selective_refresh->add_partial('logo_text', array(
        'selector'        => '.site-branding .site-title',
        'render_callback' => function () {
            return esc_html(get_theme_mod('logo_text', get_bloginfo('name')));
        },
    ));

    // Copyright Text
    $wp_customize->selective_refresh->add_partial('copyright_text', array(
        'selector'        => '.corner-nav.bottom-right a',
        'render_callback' => function () {
            return esc_html(get_theme_mod('copyright_text', '© GNNcreative'));
        },
    ));

    // Hero Title
    $wp_customize->selective_refresh->add_partial('hero_title', array(
        'selector'        => '.hero-title',
        'render_callback' => function () {
            return esc_html(get_theme_mod('hero_title', 'Build the new way.'));
        },
    ));

    // Hero Subtitle
    $wp_customize->selective_refresh->add_partial('hero_subtitle', array(
        'selector'        => '.hero-subtitle',
        'render_callback' => function () {
            return esc_html(get_theme_mod('hero_subtitle', 'Experimental workspace for agentic development.'));
        },
    ));
}
add_action('customize_register', 'gnn_customizer_selective_refresh');

/**
 * Enqueue Customizer preview script for postMessage transport.
 *
 * This script handles live-updating CSS custom properties
 * (typography, colors, layout) in the Customizer preview iframe.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_customizer_preview_scripts()
{
    wp_enqueue_script(
        'gnn-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        array('customize-preview', 'jquery'),
        '1.3.0',
        true
    );
}
add_action('customize_preview_init', 'gnn_customizer_preview_scripts');

