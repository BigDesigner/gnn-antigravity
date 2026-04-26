# Architecture Decision Records (ADR)

## [ADR-001] Memory Bank System
- **Status:** Accepted
- **Context:** Need for persistent context management across AI sessions.
- **Decision:** Implement a structured `/memory-bank/` and support directories.
- **Consequences:** Higher overhead for session starts, but zero context loss.

## [ADR-002] Elementor Compatibility Strategy
- **Status:** Accepted
- **Context:** Theme needs to work with Elementor Free while being forward-compatible with Pro.
- **Decision:** Dedicated `inc/elementor-compat.php` module with conditional loading. Two custom templates: Full Width (with header/footer) and Canvas (blank). CSS overrides are split between inline critical CSS and an external `elementor-compat.css` stylesheet.
- **Consequences:** Elementor users get clean, wrapper-free rendering. Non-Elementor sites have zero performance overhead since CSS loads conditionally.

## [ADR-003] Modular inc/ Architecture
- **Status:** Accepted
- **Context:** `functions.php` was becoming monolithic. Need separation of concerns.
- **Decision:** Split into `helpers.php`, `enqueue.php`, `customizer.php`, `metaboxes.php`, and `elementor-compat.php`. Each module is a single-responsibility file.
- **Consequences:** Easier maintenance, cleaner diffs, and module-level testing capability.

## [ADR-004] Native SEO with Plugin Coexistence
- **Status:** Accepted
- **Context:** Theme needs basic SEO (meta tags, OG, Schema) but must not conflict with Yoast/Rank Math if installed.
- **Decision:** `inc/seo.php` auto-detects major SEO plugins via constants/classes. All SEO output functions check `gnn_seo_plugin_active()` and silently disable when a 3rd-party handles it.
- **Consequences:** Zero-config SEO out of the box. Seamless upgrade path when users install a dedicated SEO plugin.

## [ADR-005] Dynamic Typography via Customizer
- **Status:** Accepted
- **Context:** Hardcoded Google Fonts URL limited font choices and required code changes.
- **Decision:** Font selection via Customizer dropdown. Google Fonts URL built dynamically from `gnn_font_family` and `gnn_heading_font` settings. CSS custom properties (`--font-main`, `--font-heading`) used throughout.
- **Consequences:** Users can change fonts without code. Unused fonts are never loaded. `system-ui` option enables zero-network-request typography.

## [ADR-006] Selective Refresh + postMessage Transport
- **Status:** Accepted
- **Context:** Full page reloads in Customizer create poor UX for text/color changes.
- **Decision:** Text fields use Selective Refresh partials. CSS-based settings (colors, typography, spacing) use `postMessage` transport with `customizer-preview.js` updating CSS custom properties in real-time.
- **Consequences:** Near-instant preview for all theme settings. Slightly more JS to maintain but dramatically better Customizer UX.

## [ADR-007] Native Hero Slider vs 3rd-Party Plugins
- **Status:** Accepted
- **Context:** Need for a homepage carousel without increasing dependency debt or security risks.
- **Decision:** Built a native implementation using GSAP and Customizer API.
- **Rationale:** Maintains "Zero 3rd-party dependency" core principle. Reduces bundle size and security surface area. Using GSAP ensures performance and "juicy" animations are on par with premium plugins.
- **Consequences:** Zero plugin bloat. Full control over markup and accessibility.

## [ADR-008] Scroll-based Header Glassmorphism
- **Status:** Accepted
- **Context:** Minimalist design needs visual depth cues when scrolling over content.
- **Decision:** Implement a state-based header that gains a `.is-scrolled` class via JS.
- **Rationale:** Enhances visual depth and "premium" feel. Using `backdrop-filter` with fallback ensures modern browsers get the best experience while maintaining readability.
- **Consequences:** Subtle, modern UI improvement that signals system state to the user.

## [ADR-009] Self-hosted GitHub Auto-Updater
- **Status:** Accepted
- **Context:** Theme is distributed via GitHub, not wordpress.org. Users need a way to receive update notifications and one-click updates from WP Admin.
- **Decision:** Built a native `GNN_GitHub_Updater` class in `inc/updater.php` that hooks into `pre_set_site_transient_update_themes` to poll the GitHub Releases API. Results are cached via WP transients (12 hours). An admin page under Appearance > Theme Updates provides a manual "Check Now" button. A Customizer toggle (`enable_github_updates`) allows disabling the feature entirely.
- **Rationale:** Maintains "Zero 3rd-party dependency" principle. Avoids requiring plugins like "GitHub Updater". Public GitHub API is free with generous rate limits.
- **Consequences:** Users get wordpress.org-like update UX for a self-hosted theme. Zero cost, zero plugin overhead.

## [ADR-010] Static Hero Image with Slider Dimension Reuse
- **Status:** Accepted
- **Context:** When the Hero Slider is disabled, users wanted an option to display a full-width static background image instead of a plain text hero.
- **Decision:** Added a `hero_static_image` Customizer setting (WP_Customize_Image_Control) and `hero_static_overlay_opacity` control. The static hero wrapper reuses the slider's CSS height variables (`--slider-height-desktop` / `--slider-height-mobile`) for identical dimensions. If no image is uploaded, the existing default hero (background + text) is preserved.
- **Consequences:** Single set of height controls for both slider and static hero. Clean fallback chain: Slider ON → Slider, Slider OFF + Image → Static Hero, Slider OFF + No Image → Default Hero.
