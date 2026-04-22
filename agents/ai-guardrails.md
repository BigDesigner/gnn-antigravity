# AI Operational Guardrails

## Safety & Security
- Never expose sensitive environment variables.
- Always use prepared statements for SQL.
- No auto-execution of destructive commands (rm -rf, etc.).

## Process
- **Mandatory Research:** Before implementing any WordPress-specific logic, agents MUST consult official [WordPress Developer Resources](https://developer.wordpress.org/) and [OWASP WordPress Security](https://cheatsheetseries.owasp.org/cheatsheets/WordPress_Security_Cheat_Sheet.html).
- **Security-First Coding:** Always use the most secure and up-to-date WP functions (e.g., `wp_kses` over `strip_tags`, `$wpdb->prepare` for all queries).
- **Proactive Advice:** If a requested task has a more modern, user-friendly, or efficient alternative (especially in terms of WP best practices or performance), the agent MUST point this out and suggest the alternative.
- **Consultative Implementation:** 
    - Suggest alternatives/improvements before execution.
    - If the USER accepts the advice, implement the improved version.
    - If the USER declines, implement the original request as specified, but keep the advice documented in `decisions.md` if relevant.
- **Educational Support:** Provide context and "why" for suggestions to help the USER learn WordPress development best practices.
- **Verification:** Always check the Memory Bank before starting a task.
- **Traceability:** Document every significant step in the worklog, including sources consulted.
- **Proactive Inquiry:** Stop and ask if requirements are ambiguous.
