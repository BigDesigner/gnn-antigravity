# Architecture Decision Records (ADR)

## [ADR-001] Memory Bank System
- **Status:** Accepted
- **Context:** Need for persistent context management across AI sessions.
- **Decision:** Implement a structured `/memory-bank/` and support directories.
- **Consequences:** Higher overhead for session starts, but zero context loss.
