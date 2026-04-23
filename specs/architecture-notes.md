# Architecture Notes

## System Design
GNN-ANTIGRAVITY is a lightweight, performance-oriented WordPress theme. It follows the standard WordPress hierarchy but emphasizes modularity through the `inc/` directory. Each module is a single-responsibility file.

## Data Flow
1. **Request:** Client requests a page.
2. **WordPress Core:** Loads theme files based on the Template Hierarchy.
3. **Theme Logic:** `functions.php` initializes hooks, loads modular includes from `inc/`.
4. **Templates:** `header.php`, `index.php`, `page.php`, `footer.php` assemble the final HTML.
5. **Elementor Override:** If an Elementor template is selected, `template-elementor-full-width.php` or `template-elementor-canvas.php` bypasses theme wrappers.

## Key Components
- **Hooks & Filters:** Extensive use of WP Action/Filter hooks for clean logic separation.
- **Assets Management:** Centralized in `inc/enqueue.php`. Google Fonts loaded dynamically based on Customizer selection.
- **Modular Logic:** All features reside in dedicated `inc/` modules:
    - `helpers.php` — Utility functions.
    - `enqueue.php` — Script/style registration.
    - `customizer.php` — Customizer panels, settings, dynamic CSS, Selective Refresh, Google Fonts.
    - `metaboxes.php` — Post/page visibility & hero meta boxes.
    - `elementor-compat.php` — Elementor theme support, locations, body classes, compat CSS.
    - `seo.php` — Meta descriptions, Open Graph, Twitter Cards, JSON-LD Schema, per-post SEO metabox.
- **Elementor Compatibility:**
    - Full support for `the_content()` in all templates.
    - `wp_head()` and `wp_footer()` mandatory in all wrappers.
    - Custom "Full Width" (with header/footer) and "Canvas" (blank) template support.
    - Conditional CSS loading — zero overhead when Elementor is not active.
    - Auto-hides theme hero section on Elementor-built pages.
- **Native SEO:**
    - Auto-generates meta descriptions, OG tags, Twitter Cards, JSON-LD.
    - Per-post SEO title, description, and social image override via metabox.
    - Auto-disables when Yoast SEO, Rank Math, AIOSEO, or SEOPress is detected.
- **Customizer Architecture:**
    - Organized under "GNN Theme Options" panel with sections: Hero, General, Typography, Header, Footer, SEO.
    - Dynamic CSS output via CSS custom properties (`--font-main`, `--accent-color`, etc.).
    - Selective Refresh partials for text fields (logo, copyright, hero title/subtitle).
    - postMessage transport for colors, typography, and spacing (via `customizer-preview.js`).

## External Services Integration
1. **Security (Bot Protection):**
    - **Cloudflare Turnstile:** Primary non-intrusive CAPTCHA for login and forms. *(Planned: SEC-001)*
    - **Google reCAPTCHA (v2/v3):** Alternative/Fallback protection layer. *(Planned: SEC-002)*
2. **Analytics:**
    - **Google Analytics (GA4):** Native tracking implementation via Customizer. *(Planned: ANA-001)*

## Core Principles
1. **Zero 3rd-party Dependency:** The theme must not rely on external plugins (ACF, Redux, Frameworks, etc.) for core functionality. Use native WordPress APIs (Customizer, Metadata, etc.).
2. **Native Customizer Support:** 100% integration with `wp-admin/customize.php`.
    - **Site Identity:** Logo, Title, Tagline, Favicon.
    - **Visuals:** Colors, Typography (Dynamic Google Fonts loading), Backgrounds.
    - **Layout:** Header styles, Footer settings.
    - **Special Features:** Native Slide/Carousel management via Customizer. *(Planned: CUST-002)*
3. **Plugin Coexistence:** Theme features (SEO, etc.) auto-disable when dedicated plugins are detected.
