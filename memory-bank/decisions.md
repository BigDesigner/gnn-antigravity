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
