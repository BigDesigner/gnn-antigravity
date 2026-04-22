<?php
/**
 * GNN Customizer Configurations
 */

function gnn_antigravity_customize_register($wp_customize)
{
    // Sections
    $wp_customize->add_section('gnn_hero_section', array('title' => esc_html__('Hero Section', 'gnn-antigravity'), 'priority' => 30));
    $wp_customize->add_section('gnn_settings', array('title' => esc_html__('General Settings', 'gnn-antigravity'), 'priority' => 31));

    // Settings & Controls (Condensed for Expert view)
    $settings = array(
        'hero_title' => array('default' => 'Build the new way.', 'label' => 'Hero Title', 'section' => 'gnn_hero_section', 'type' => 'text'),
        'hero_subtitle' => array('default' => 'Experimental workspace for agentic development.', 'label' => 'Hero Subtitle', 'section' => 'gnn_hero_section', 'type' => 'textarea'),
        'logo_text' => array('default' => 'GNN_ANTIGRAVITY', 'label' => 'Site Logo Text', 'section' => 'gnn_settings', 'type' => 'text'),
        'copyright_text' => array('default' => '© GNNcreative', 'label' => 'Copyright Text', 'section' => 'gnn_settings', 'type' => 'text'),
        'header_sticky' => array('default' => 'fixed', 'label' => 'Header Positioning', 'section' => 'gnn_settings', 'type' => 'select', 'choices' => array('fixed' => 'Sticky', 'absolute' => 'Normal')),
        'header_bg_type' => array('default' => 'transparent', 'label' => 'Header Background Style', 'section' => 'gnn_settings', 'type' => 'radio', 'choices' => array('transparent' => 'Transparent', 'colored' => 'Solid')),
        'footer_bg_type' => array('default' => 'transparent', 'label' => 'Footer Background Style', 'section' => 'gnn_settings', 'type' => 'radio', 'choices' => array('transparent' => 'Transparent', 'colored' => 'Solid')),
        'show_post_title' => array('default' => true, 'label' => 'Show Titles', 'section' => 'gnn_settings', 'type' => 'checkbox'),
        'show_post_date' => array('default' => true, 'label' => 'Show Dates', 'section' => 'gnn_settings', 'type' => 'checkbox'),
        'show_post_author' => array('default' => false, 'label' => 'Show Author', 'section' => 'gnn_settings', 'type' => 'checkbox'),
        'enable_mobile_menu' => array('default' => true, 'label' => 'Enable Mobile Menu', 'section' => 'gnn_settings', 'type' => 'checkbox'),
    );

    foreach ($settings as $id => $args) {
        $wp_customize->add_setting($id, array('default' => $args['default'], 'sanitize_callback' => ($args['type'] === 'checkbox' ? 'gnn_sanitize_checkbox' : 'sanitize_text_field')));
        $wp_customize->add_control($id, array('label' => esc_html__($args['label'], 'gnn-antigravity'), 'section' => $args['section'], 'type' => $args['type'], 'choices' => $args['choices'] ?? null));
    }

    // Colors
    $colors = array('header_bg_color' => 'Header Color', 'footer_bg_color' => 'Footer Color', 'accent_color' => 'Accent Color');
    foreach ($colors as $id => $label) {
        $wp_customize->add_setting($id, array('default' => '#000000', 'sanitize_callback' => 'sanitize_hex_color'));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, array('label' => esc_html__($label, 'gnn-antigravity'), 'section' => 'gnn_settings')));
    }
}
add_action('customize_register', 'gnn_antigravity_customize_register');

function gnn_sanitize_checkbox($input)
{
    return (isset($input) && true == $input ? true : false);
}
