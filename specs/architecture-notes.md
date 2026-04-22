# Architecture Notes

## System Design
GNN-ANTIGRAVITY is a lightweight, performance-oriented WordPress theme. It follows the standard WordPress hierarchy but emphasizes modularity through the `inc/` directory.

## Data Flow
1. **Request:** Client requests a page.
2. **WordPress Core:** Loads theme files based on the Template Hierarchy.
3. **Theme Logic:** `functions.php` initializes hooks, styles, and scripts.
4. **Templates:** `header.php`, `index.php`, `footer.php` assemble the final HTML.

## Key Components
- **Hooks & Filters:** Extensive use of WP Action/Filter hooks for clean logic separation.
- **Assets Management:** Centralized asset loading in `functions.php`.
- **Modular Logic:** Features like custom post types or shortcodes reside in `inc/`.
- **Elementor Compatibility:** 
    - Full support for `the_content()` in all templates.
    - `wp_head()` and `wp_footer()` mandatory in all wrappers.
    - Custom "Full Width" and "Blank Canvas" template support.
    - Theme-specific Elementor widget registration (optional).

## External Services Integration
1. **Security (Bot Protection):**
    - **Cloudflare Turnstile:** Primary non-intrusive CAPTCHA for login and forms.
    - **Google reCAPTCHA (v2/v3):** Alternative/Fallback protection layer.
2. **Analytics:**
    - **Google Analytics (GA4):** Native tracking implementation via Customizer (Header/Footer hooks).

## Core Principles
1. **Zero 3rd-party Dependency:** The theme must not rely on external plugins (ACF, Redux, Frameworks, etc.) for core functionality. Use native WordPress APIs (Customizer, Metadata, etc.).
2. **Native Customizer Support:** 100% integration with `wp-admin/customize.php`.
    - **Site Identity:** Logo, Title, Tagline, Favicon.
    - **Visuals:** Colors, Typography (Native font loading), Backgrounds.
    - **Layout:** Header styles, Footer settings, Sidebar controls.
    - **Special Features:** Native Slide/Carousel management via Customizer.
