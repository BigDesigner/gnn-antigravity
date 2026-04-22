# Project Standards & Linting Protocols

## 1. Naming Conventions
- **Files:** lowercase-kebab-case.php
- **Classes:** PascalCase
- **Functions:** snake_case (prefixed with `gnn_`)
- **Variables:** snake_case
- **CSS Classes:** BEM methodology (`block__element--modifier`)

## 2. Code Quality
- **PHP:** PSR-12 compliance.
- **JS:** Standard JS with ES6+ features.
- **CSS:** Vanilla CSS with custom properties.

## 3. Documentation
- All functions must have PHPDoc blocks.
- Major logic blocks require inline comments explaining the "why", not just the "how".

## 5. Elementor Integration Standards
- **Compatibility:** Always support the free version of Elementor.
- **Templates:** Provide a clean `the_content()` call without theme-forced wrappers for Elementor templates.
- **Hooks:** Ensure `wp_head()` and `wp_footer()` are present and clean of intrusive scripts that might break the editor.
## 6. Security & Documentation Research
- **Official Sources:** The WordPress Codex and Developer Resources are the primary sources of truth. 
- **Security Protocols:** All input must be sanitized, and all output must be escaped using the most restrictive WP functions available.
- **Pre-Implementation Research:** For complex features, agents must perform a search to verify if a native WP function or hook exists that solves the problem more efficiently/securely than custom code.

## 7. Consultative & Mentorship Approach
- **Proactive Suggestions:** Agents must always evaluate if a better, more modern, or more user-friendly way exists to fulfill a request.
- **Decision Support:** If a better way is found, it must be presented to the USER as a suggestion before implementation.
- **Educational Context:** Suggestions should include a brief explanation of "why" it is better, serving as a learning resource for WordPress theme development.
## 8. Native WordPress Development
- **No Dependencies:** Avoid 3rd party plugins for core theme features.
- **Customizer API:** Use the `WP_Customize_Manager` class for all theme options.
- **Selective Refresh:** Implement `customize-selective-refresh-widgets` for better UX.
- **Sanitization:** Every Customizer setting MUST have a corresponding `sanitize_callback`.
- **Favicon & Logo:** Use `add_theme_support( 'custom-logo' )` and core Site Icon functionality.

## 9. Development Integrity & Verification
- **Pre-Commit Check:** No code shall be committed without a "PASS" status from the verification step.
- **Verification Methods:** PHP lint (`php -l`), CSS validation, and functional verification.
- **Atomic Commits:** Each commit must represent a single, verified, and complete change or fix.
