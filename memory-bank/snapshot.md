# Project Snapshot

## System Status
- **Theme Version:** 1.5.0
- **Current Branch:** main
- **Remote:** https://github.com/BigDesigner/gnn-antigravity.git
- **Core Principles:** Zero 3rd-party Dependency, 100% Native WP API focus

## Active Features
- WordPress Theme core (header/footer/hero/page/index templates)
- Memory Bank System
- GitHub Actions Release workflow
- Elementor Compatibility Layer (Full Width + Canvas templates)
- Native SEO module (Meta, OG, Twitter Cards, Schema, per-post metabox)
- Native Customizer (Typography, Header, Footer, Colors, SEO panel)
- Selective Refresh + postMessage live preview
- Back-to-top button (optional via Customizer)
- Standardized `CHANGELOG.md` tracking

## Module Map (`inc/`)
| Module | Responsibility |
|--------|---------------|
| `helpers.php` | Utility functions (YouTube ID parser) |
| `enqueue.php` | Script/style registration |
| `customizer.php` | Customizer panels, settings, dynamic CSS, Selective Refresh |
| `metaboxes.php` | Post/page visibility & hero media meta boxes |
| `elementor-compat.php` | Elementor theme support, locations, body classes, compat CSS |
| `seo.php` | Meta descriptions, OG, Twitter, Schema, SEO metabox |

## Planned Integrations
- `theme.json` Design System (CUST-004)
- Cloudflare Turnstile (SEC-001)
- Google reCAPTCHA v3 (SEC-002)
- Google Analytics GA4 (ANA-001)
- Native Slider/Carousel (CUST-002)

## Environment
- **Platform:** WordPress
- **PHP Version:** >= 7.4
- **External Libraries:** GSAP 3.12, Lenis 1.0, Swup 4 (CDN)
