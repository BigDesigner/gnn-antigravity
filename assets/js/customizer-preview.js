/**
 * GNN Customizer Live Preview (postMessage transport)
 *
 * Handles real-time preview updates in the WordPress Customizer
 * without requiring full page reloads. Uses the wp.customize JS API.
 *
 * @package GNN-antigravity
 * @since   1.3.0
 */

(function ($) {
    'use strict';

    // --- Typography ---
    wp.customize('gnn_font_family', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--font-main', '"' + newval + '", system-ui, -apple-system, sans-serif');
        });
    });

    wp.customize('gnn_heading_font', function (value) {
        value.bind(function (newval) {
            var font = newval || wp.customize('gnn_font_family').get();
            document.documentElement.style.setProperty('--font-heading', '"' + font + '", system-ui, -apple-system, sans-serif');
        });
    });

    wp.customize('gnn_base_font_size', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--base-size', newval + 'px');
        });
    });

    wp.customize('gnn_letter_spacing', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--tracking', newval + 'em');
        });
    });

    // --- Colors ---
    wp.customize('accent_color', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--accent-color', newval);
        });
    });

    wp.customize('header_bg_color', function (value) {
        value.bind(function (newval) {
            var headerEl = document.querySelector('.site-top-bar');
            if (headerEl && wp.customize('header_bg_type').get() === 'colored') {
                headerEl.style.background = newval;
            }
        });
    });

    wp.customize('footer_bg_color', function (value) {
        value.bind(function (newval) {
            var footerEl = document.querySelector('.site-bottom-bar');
            if (footerEl && wp.customize('footer_bg_type').get() === 'colored') {
                footerEl.style.background = newval;
            }
        });
    });

    // --- Header Height ---
    wp.customize('header_height', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--header-height', newval + 'px');
        });
    });

    // --- Hero / Slider Height ---
    wp.customize('hero_height_desktop', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--slider-height-desktop', newval + 'vh');
        });
    });

    wp.customize('hero_height_mobile', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--slider-height-mobile', newval + 'vh');
        });
    });

    // Legacy slider height controls (sync to same CSS variables)
    wp.customize('slider_height_desktop', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--slider-height-desktop', newval + 'vh');
        });
    });

    wp.customize('slider_height_mobile', function (value) {
        value.bind(function (newval) {
            document.documentElement.style.setProperty('--slider-height-mobile', newval + 'vh');
        });
    });

    // --- Static Hero Overlay Opacity ---
    wp.customize('hero_static_overlay_opacity', function (value) {
        value.bind(function (newval) {
            var overlay = document.querySelector('.gnn-hero-static-wrapper .hero-media-overlay');
            if (overlay) {
                overlay.style.opacity = newval;
            }
        });
    });

    // --- Static Hero Image (live swap without full refresh) ---
    wp.customize('hero_static_image', function (value) {
        value.bind(function (newval) {
            var bg = document.querySelector('.gnn-hero-static-bg');
            if (bg) {
                bg.style.backgroundImage = newval ? "url('" + newval + "')" : 'none';
            }
        });
    });

})(jQuery);
