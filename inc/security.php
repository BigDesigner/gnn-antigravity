<?php
/**
 * GNN Security & Bot Protection (Cloudflare Turnstile)
 *
 * Implements Cloudflare Turnstile for login and potentially other forms.
 * Follows "Zero 3rd-party dependency" for core logic.
 *
 * @package GNN-antigravity
 * @since   1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Initialize Security logic if enabled.
 */
function gnn_security_init() {
    $turnstile_active = get_theme_mod( 'enable_turnstile', false ) && ! empty( get_theme_mod( 'turnstile_site_key' ) );
    $recaptcha_active = ! empty( get_theme_mod( 'recaptcha_site_key' ) );

    if ( ! $turnstile_active && ! $recaptcha_active ) {
        return;
    }

    // Turnstile priority
    if ( $turnstile_active ) {
        add_action( 'login_form', 'gnn_render_turnstile_widget' );
        add_action( 'register_form', 'gnn_render_turnstile_widget' );
        add_action( 'lostpassword_form', 'gnn_render_turnstile_widget' );
        add_action( 'login_enqueue_scripts', 'gnn_enqueue_turnstile_script' );
        add_action( 'wp_enqueue_scripts', 'gnn_enqueue_turnstile_script' );
        add_filter( 'wp_authenticate_user', 'gnn_verify_turnstile_login', 10, 2 );
    } 
    // reCAPTCHA fallback
    elseif ( $recaptcha_active ) {
        add_action( 'login_form', 'gnn_render_recaptcha_field' );
        add_action( 'register_form', 'gnn_render_recaptcha_field' );
        add_action( 'lostpassword_form', 'gnn_render_recaptcha_field' );
        add_action( 'login_enqueue_scripts', 'gnn_enqueue_recaptcha_script' );
        add_action( 'wp_enqueue_scripts', 'gnn_enqueue_recaptcha_script' );
        add_filter( 'wp_authenticate_user', 'gnn_verify_recaptcha_login', 10, 2 );
    }
}
add_action( 'init', 'gnn_security_init' );

/**
 * TURNSTILE LOGIC
 */
function gnn_enqueue_turnstile_script() {
    wp_enqueue_script( 'cloudflare-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, true );
}

function gnn_render_turnstile_widget() {
    $site_key = get_theme_mod( 'turnstile_site_key', '' );
    echo '<div class="cf-turnstile" data-sitekey="' . esc_attr( $site_key ) . '" data-theme="dark" style="margin-bottom: 20px;"></div>';
}

function gnn_verify_turnstile_login( $user, $username ) {
    if ( is_wp_error( $user ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) return $user;
    
    $secret_key = get_theme_mod( 'turnstile_secret_key', '' );
    $response   = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( $_POST['cf-turnstile-response'] ) : '';

    if ( empty( $response ) ) return new WP_Error( 'security_error', __( '<strong>Error</strong>: Please complete the security check.', 'gnn-antigravity' ) );

    $verify = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'body' => array( 'secret' => $secret_key, 'response' => $response, 'remoteip' => $_SERVER['REMOTE_ADDR'] ),
    ) );

    if ( is_wp_error( $verify ) ) return $user;
    $result = json_decode( wp_remote_retrieve_body( $verify ), true );
    return ( ! empty( $result['success'] ) ) ? $user : new WP_Error( 'security_fail', __( '<strong>Error</strong>: Security check failed.', 'gnn-antigravity' ) );
}

/**
 * reCAPTCHA v3 LOGIC
 */
function gnn_enqueue_recaptcha_script() {
    $site_key = get_theme_mod( 'recaptcha_site_key', '' );
    wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $site_key ), array(), null, true );
    
    // Add inline script to execute reCAPTCHA
    wp_add_inline_script( 'google-recaptcha', "
        grecaptcha.ready(function() {
            grecaptcha.execute('" . esc_js( $site_key ) . "', {action: 'login'}).then(function(token) {
                var inputs = document.querySelectorAll('form#loginform, form#registerform, form#lostpasswordform');
                inputs.forEach(function(form) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    input.value = token;
                    form.appendChild(input);
                });
            });
        });
    " );
}

function gnn_render_recaptcha_field() {
    // reCAPTCHA v3 is invisible, we just need the field for the script to target
    echo '<p style="font-size: 10px; opacity: 0.6; margin-bottom: 20px;">' . esc_html__( 'This site is protected by reCAPTCHA v3.', 'gnn-antigravity' ) . '</p>';
}

function gnn_verify_recaptcha_login( $user, $username ) {
    if ( is_wp_error( $user ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) return $user;

    $secret_key = get_theme_mod( 'recaptcha_secret_key', '' );
    $response   = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( $_POST['g-recaptcha-response'] ) : '';

    if ( empty( $response ) ) return new WP_Error( 'security_error', __( '<strong>Error</strong>: reCAPTCHA verification failed.', 'gnn-antigravity' ) );

    $verify = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
        'body' => array( 'secret' => $secret_key, 'response' => $response, 'remoteip' => $_SERVER['REMOTE_ADDR'] ),
    ) );

    if ( is_wp_error( $verify ) ) return $user;
    $result = json_decode( wp_remote_retrieve_body( $verify ), true );

    // For reCAPTCHA v3, we check success AND score (0.5 is usually a good threshold)
    if ( ! empty( $result['success'] ) && isset( $result['score'] ) && $result['score'] >= 0.5 ) {
        return $user;
    }

    return new WP_Error( 'security_fail', __( '<strong>Error</strong>: Security verification failed. Please try again.', 'gnn-antigravity' ) );
}

