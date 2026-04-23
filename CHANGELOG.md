# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to Semantic Versioning.

## [1.5.0] - 2026-04-23

### Added
- **Elementor Compatibility Layer:** Full support for Elementor page builder with conditional CSS loading (`inc/elementor-compat.php`).
- **New Templates:** Created `template-elementor-full-width.php` and `template-elementor-canvas.php` for wrapper-free page building.
- **Native SEO Module:** Out-of-the-box support for Meta tags, Open Graph, Twitter Cards, and JSON-LD Schema (`inc/seo.php`).
- **SEO Metabox:** Added per-post overrides for SEO title, description, and social sharing image.
- **Customizer Typography Panel:** Dynamic Google Fonts loading based on user selection (eliminating hardcoded fonts).
- **Customizer Live Preview:** Added Selective Refresh partials and postMessage transport for instant UI feedback (`assets/js/customizer-preview.js`).
- **Back-to-top Button:** Added scroll-to-top functionality with Lenis smooth-scroll integration.

### Changed
- Refactored `inc/customizer.php` into structured panels (Hero, General, Typography, Header, Footer, SEO).
- Cleaned up `functions.php` by modularizing all theme features into the `inc/` directory.
- Updated `enqueue.php` to remove hardcoded font requests and pass theme settings to JS via `wp_localize_script`.
- Separated layout overrides into a dedicated `assets/css/elementor-compat.css` to ensure zero performance overhead for non-Elementor users.

### Security
- Added `ABSPATH` direct-access prevention checks to `helpers.php`, `metaboxes.php`, `functions.php`, and `template-elementor-full-width.php`.
- Performed Sprint 1 & 2 Security Audit verifying sanitization, escaping, and nonce usages.

## [1.1.0] - 2026-04-22

### Added
- **Project Scaffolding:** Established the initial Memory Bank directory structure (`memory-bank/`).
- **Documentation:** Created core architectural specs, standards, and security guidelines.
- **Workflow:** Integrated GitHub Actions Release workflow with `rsync` deployment logic.
- **Base Theme Features:** Minimalist dark mode "canvas" theme base, native Customizer settings loop, and glitch text effects.
