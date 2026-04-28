# INTI Smart Booking FYP — Refactor

Layered rewrite of [INTI_Smart_Booking_FYP](https://github.com/XXD051030/INTI_Smart_Booking_FYP) (referred to as "V1" below).

The goal of this repository is to **preserve V1's UI and feature set pixel-for-pixel** while replacing V1's flat procedural script layout with a clean layered structure (Repositories / Services / Views).

> Status: Round 1 in progress — porting core student & admin features. See [Roadmap](#roadmap).

---

## Stack

- PHP 8+
- SQLite via PDO (single-file database, auto-initialized on first run)
- Multi-page server-side rendering
- Custom CSS / JS lifted directly from V1

## Project Layout

```
.
├── bootstrap.php             # Autoloader, session, config, DB boot
├── config/app.php            # Single source of truth for runtime config
├── assets/                   # css/, js/, images/ — copied verbatim from V1
├── src/
│   ├── Support/              # Auth, Database, View, helpers, etc.
│   ├── Repositories/         # Data access (one class per V1 table)
│   ├── Services/             # Business logic (booking, auth, notifications)
│   └── Views/
│       ├── layouts/          # Shared page shells (student / admin)
│       ├── partials/         # Sidebars, topbars, alerts
│       └── pages/            # Per-page templates — V1 HTML lifted in here
├── admin/                    # Admin entry-point routers
├── actions/                  # Form-post / API endpoints
├── storage/
│   ├── database/app.sqlite   # SQLite database file (gitignored)
│   └── logs/                 # Mail log placeholder
└── *.php                     # Student-facing entry-point routers
```

Entry-point files (`booking.php`, `login.php`, `admin/dashboard.php`, ...) stay thin — they bootstrap the app, call a service, and render a view. SQL lives in repositories; HTML lives in views.

## Quick Start

```bash
# 1. Clone
git clone https://github.com/XXD051030/INTI_Smart_Booking_FYP_refactor.git
cd INTI_Smart_Booking_FYP_refactor

# 2. Run with PHP's built-in server
php -S localhost:8000

# 3. Open the app
#    Student portal:   http://localhost:8000/login.php
#    Admin portal:     http://localhost:8000/admin/index.php
```

The SQLite database file is created automatically on first request, with V1's table schema replicated and a default admin account seeded.

## Default Admin Credentials

- Username: `admin`
- Password: `admin123`

## Differences From V1

| Area | V1 | This refactor |
|---|---|---|
| Database | MySQL (manual `CREATE TABLE` scripts) | SQLite, auto-initialized |
| Code layout | Flat scripts in project root, HTML + SQL mixed | Layered: Repositories / Services / Views |
| Bootstrap | None — each script includes `db.php` directly | Single `bootstrap.php` + PSR-4 autoload |
| UI / styles | — | **Identical to V1** (HTML/CSS/JS lifted verbatim) |
| Features | — | **Identical to V1** (with two temporary deferrals, see below) |

## Deferred From V1 (Round 2)

These V1 features are **not yet ported** and will be addressed after Round 1 lands:

- **OTP email verification** during student registration
- **Multi-language UI** (V1's `includes/lang/`) — Round 1 hardcodes English

## Known Technical Debt

This refactor mirrors V1 as faithfully as possible, including some weaknesses that were present in V1. The following items will be revisited after the structural refactor stabilizes:

- No CSRF token protection on state-changing forms (login, register, booking, cancel)
- No rate limiting on authentication endpoints
- Default admin credentials are documented in this README
- Mail delivery currently a no-op stub

## Roadmap

### Round 1 — Core port (in progress)

End-to-end port of these domains, in this order:

1. **Auth** — register, login, logout
2. **Bookings** — booking form, my bookings, calendar, availability API
3. **Notifications** — in-app notifications page + APIs
4. **Admin** — dashboard, bookings management

### Round 2 — Peripheral & deferred

- Profile, settings, rules, support pages
- Admin actions module
- OTP email verification (re-enabled)
- Multi-language UI (re-enabled)
- Security hardening (CSRF, rate limiting)

## License

Inherits the license of the upstream V1 project.
