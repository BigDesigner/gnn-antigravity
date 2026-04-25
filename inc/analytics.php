<?php
/**
 * GNN Analytics Integration
 *
 * Handles Google Analytics (GA4) tracking logic.
 *
 * @package GNN-antigravity
 * @since   1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Output GA4 Tracking Code in Head.
 */
function gnn_output_ga4_code() {
    $ga4_id = get_theme_mod( 'ga4_measurement_id', '' );
    
    if ( empty( $ga4_id ) ) {
        return;
    }
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga4_id ); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_attr( $ga4_id ); ?>', {
            'anonymize_ip': true,
            'cookie_flags': 'SameSite=None;Secure'
        });
    </script>
    <?php
}
add_action( 'wp_head', 'gnn_output_ga4_code', 5 );

/**
 * Custom JS for Tracking Slider and UI Events.
 * Injects a small script to handle custom tracking.
 */
function gnn_analytics_custom_tracking() {
    $ga4_id = get_theme_mod( 'ga4_measurement_id', '' );
    if ( empty( $ga4_id ) ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Track Slider Button Clicks
        document.querySelectorAll('.gnn-slide .gnn-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const slideTitle = this.closest('.gnn-slide').querySelector('.hero-title').textContent.trim();
                if (typeof gtag === 'function') {
                    gtag('event', 'slider_click', {
                        'slide_title': slideTitle,
                        'button_url': this.href
                    });
                }
            });
        });

        // Track Back to Top usage
        const btt = document.getElementById('gnn-back-to-top');
        if (btt) {
            btt.addEventListener('click', function() {
                if (typeof gtag === 'function') {
                    gtag('event', 'ui_interaction', {
                        'element': 'back_to_top'
                    });
                }
            });
        }
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'gnn_analytics_custom_tracking', 100 );
