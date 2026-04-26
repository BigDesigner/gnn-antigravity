# Project Snapshot

## System Status
- **Theme Version:** 1.6.1
- **Current Branch:** main
- **Remote:** https://github.com/BigDesigner/gnn-antigravity.git
- **Core Principles:** Zero 3rd-party Dependency, 100% Native WP API focus

## Active Features
- WordPress Theme core (header/footer/hero/page/index templates)
- Memory Bank System
- GitHub Actions Release workflow
- Elementor Compatibility Layer (Full Width + Canvas templates)
- Native SEO module (Meta, OG, Twitter Cards, Schema, per-post metabox)
- Native Customizer (Typography, Header, Footer, Colors, SEO panel, Hero Slider, Analytics, Security)
- Selective Refresh + postMessage live preview
- `theme.json` Design System (Gutenberg sync)
- Native Hero Slider (GSAP powered, touch-enabled)
- Advanced Navigation (Scroll-based glassmorphism, GSAP dropdowns)
- Native GA4 Tracking (with auto-event tracking for slider)
- Multi-provider Bot Protection (Cloudflare Turnstile & Google reCAPTCHA v3)
- Back-to-top button (optional via Customizer)
- Standardized `CHANGELOG.md` tracking
- Advanced Performance Optimization (PERF-001: Defer, Preconnect, Cache-busting)
- Custom Magnetic Cursor (UI-002)

## Module Map (`inc/`)
| Module | Responsibility |
|--------|---------------|
| `helpers.php` | Utility functions (YouTube ID parser) |
| `enqueue.php` | Script/style registration |
| `customizer.php` | Customizer panels, settings, dynamic CSS, Selective Refresh, Slider |
| `metaboxes.php` | Post/page visibility & hero media meta boxes |
| `elementor-compat.php` | Elementor theme support, locations, body classes, compat CSS |
| `seo.php` | Meta descriptions, OG, Twitter, Schema, SEO metabox |
| `security.php` | Cloudflare Turnstile integration |
| `analytics.php` | Google Analytics (GA4) with custom UI event tracking |


## Planned Integrations
- Accessibility (A11Y) & Internationalization (I18N) Audit (Completed in v1.6.1)
- Elementor Template Optimization (Completed in v1.6.1)
- Live Production Testing


## Environment
- **Platform:** WordPress
- **PHP Version:** >= 7.4
- **External Libraries:** GSAP 3.12, Lenis 1.0, Swup 4 (CDN)
