# Security Audit

## Threat Models
- **SQL Injection:** Mitigated by using `$wpdb->prepare()`.
- **XSS:** Mitigated by using `esc_html()`, `esc_attr()`, and `wp_kses()`.
- **CSRF:** Mitigated by using nonces for all form submissions and AJAX requests.

## Constraints
- No direct database queries without abstraction.
- No use of `eval()`.
- Strict output sanitization.
