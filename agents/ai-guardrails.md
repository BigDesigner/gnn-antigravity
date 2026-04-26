# AI Agent Zero-Tolerance Coding Standard

> **MANDATORY READING.** Every AI agent working on this project MUST read and internalize
> this document BEFORE writing a single line of code. Violations are UNACCEPTABLE.
> The human owner should NEVER have to catch bugs that the AI introduced.

---

## ABSOLUTE RULES — NO EXCEPTIONS

### 1. CSS Variable Fallbacks — ALWAYS
Every `var()` reference MUST include a hardcoded fallback value.

```css
/* ❌ FORBIDDEN — will silently fail if variable is undefined */
height: var(--slider-height-desktop);

/* ✅ REQUIRED — always works, even before inline styles load */
height: var(--slider-height-desktop, 100vh);
```

**Why:** CSS variables injected via `wp_add_inline_style()` are NOT available during initial
render or inside the WordPress Customizer iframe. Without fallbacks, layouts collapse silently.

### 2. Critical Inline CSS — MANDATORY for ALL Visual Components
If a component is rendered in `header.php` or above the fold, its COMPLETE layout styles
MUST be duplicated in the `<style>` block inside `<head>`.

**Required properties in critical CSS:**
- `position`, `display`, `width`, `height`/`min-height`
- `flex` properties if using flexbox
- `z-index` for layered elements
- `background` for visual elements
- `overflow` behavior

**Why:** External stylesheets load asynchronously. The Customizer iframe has timing differences.
Critical CSS prevents Flash of Unstyled Content (FOUC) in ALL contexts.

### 3. Customizer postMessage Handlers — COMPLETE COVERAGE
If a Customizer setting has `'transport' => 'postMessage'`, there MUST be a corresponding
handler in `customizer-preview.js`. No exceptions.

**Checklist for every new Customizer setting:**
- [ ] Setting registered in `customizer.php`
- [ ] If `transport: postMessage` → handler exists in `customizer-preview.js`
- [ ] If the setting changes CSS variables → JS updates `:root` property
- [ ] If the setting changes DOM content → Selective Refresh partial registered
- [ ] If the setting creates/removes HTML blocks → `fallback_refresh: true` on partial

### 4. Use ONLY Defined CSS Variables and Classes
Never reference CSS variables or class names that don't exist in the theme's design system.

```css
/* ❌ FORBIDDEN — these variables don't exist in this theme */
background: var(--primary-color);
color: var(--bg-color);

/* ✅ CORRECT — use the theme's actual variables */
background: var(--accent-color, #00f2ff);
color: var(--bg);
```

Before using any CSS variable or class name, VERIFY it exists by searching the codebase.

### 5. Escaping — Defense in Depth
Even if a WordPress function claims to return safe output, apply explicit escaping:
- `esc_html()` for all text output
- `esc_attr()` for all HTML attributes
- `esc_url()` for all URLs
- `wp_kses_post()` for rich content

### 6. Front Page Detection — Use Both Checks
WordPress has TWO different homepage configurations. ALWAYS use both:

```php
/* ❌ INCOMPLETE — fails when "Your latest posts" is set as homepage */
if (is_front_page()) { ... }

/* ✅ CORRECT — works in ALL WordPress configurations */
if (is_front_page() || is_home()) { ... }
```

---

## MANDATORY PRE-COMMIT VERIFICATION

Before ANY commit, the AI agent MUST verify:

1. **CSS Audit:** Search for ALL `var(--` in `main.css` and verify each has a fallback value
2. **Critical CSS Audit:** Every component in `header.php` rendering has matching critical CSS
3. **postMessage Audit:** Every `'transport' => 'postMessage'` setting has a JS handler
4. **Selective Refresh Audit:** Every setting that changes HTML structure has a partial
5. **Variable Existence:** Every CSS variable/class referenced actually exists in the codebase
6. **Escape Audit:** Every `echo` or output statement uses proper escaping
7. **Front Page:** Every `is_front_page()` check also includes `is_home()`

---

## PAST VIOLATIONS — LEARN FROM THESE

These bugs were introduced by AI agents and caught by the human. This is UNACCEPTABLE.

| Date | Bug | Root Cause | Lesson |
|------|-----|-----------|--------|
| 2026-04-26 | Static hero text stuck in top-left corner in Customizer | Missing critical CSS in `<head>` `<style>` block | ALWAYS add critical inline CSS for above-fold components |
| 2026-04-26 | Static hero image not visible in Customizer preview | `var(--slider-height-desktop)` had no fallback, resolved to `auto` | ALWAYS include fallback values in `var()` |
| 2026-04-26 | Hero height not updating in Customizer | `hero_height_desktop` had `transport: postMessage` but no JS handler | ALWAYS add JS handler for postMessage settings |
| 2026-04-26 | Static hero not showing on blog homepage | Used only `is_front_page()` without `is_home()` | ALWAYS use both front page checks |
| 2026-04-26 | 404 button using undefined CSS variables | Referenced `--primary-color` and `--bg-color` which don't exist | ALWAYS verify variables exist before using |
| 2026-04-26 | Slider wrapper missing height fallback | Same `var()` without fallback pattern | SYSTEMATIC checks, not ad-hoc fixes |

---

## THE STANDARD

> **If a human has to find your bug, you have failed.**
> Write code that works in ALL contexts: normal page load, Customizer preview, 
> Customizer iframe, mobile viewport, and slow network conditions.
> Test your mental model against EVERY rendering path before committing.
> Zero tolerance. Zero excuses.
