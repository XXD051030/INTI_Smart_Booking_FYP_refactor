# INTI Smart Booking FYP — Refactor

Layered rewrite of [INTI_Smart_Booking_FYP](https://github.com/XXD051030/INTI_Smart_Booking_FYP) (referred to as "V1" below).

The goal of this repository is to **preserve V1's UI and feature set pixel-for-pixel** while replacing V1's flat procedural script layout with a clean layered structure (Repositories / Services / Views).

> Status: Round 1 complete — V1 UI lifted verbatim onto a layered backend, plus a Round 1.5 visual polish pass and Round 2 OTP email verification. See [Roadmap](#roadmap).

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

- **Multi-language UI** (V1's `includes/lang/`) — Round 1 hardcodes English

OTP email verification has now landed (see Roadmap → Round 2).

## Known Technical Debt

This refactor mirrors V1 as faithfully as possible, including some weaknesses that were present in V1. The following items will be revisited after the structural refactor stabilizes:

- No CSRF token protection on state-changing forms (login, register, booking, cancel)
- No rate limiting on authentication endpoints
- Default admin credentials are documented in this README
- Mail delivery currently a no-op stub

## Roadmap

### Round 1 — Core port (complete)

V1's full HTML/CSS/JS surface lifted into V2's layered backend:

1. **Auth** — register, login, logout
2. **Bookings** — booking form, my bookings, calendar, availability API
3. **Notifications** — in-app notification feed + mark-read APIs
4. **Admin** — dashboard, bookings management, CSV export
5. **Static** — profile, settings, language, rules, support

### Round 1.5 — Visual polish (complete)

Targeted fixes after the verbatim port — V1's surface kept, but tightened where it visibly clashed with the INTI red brand:

- **Calendar** — replaced the purple/glassmorphism shell with a flat white card; toolbar, today highlight, events, and stats all moved to the red palette.
- **Admin pages** — flattened admin dashboard and admin-bookings cards/modals to match the calendar (lighter shadows, hairline borders, white headers with red icons).
- **Booking & student pages** — softened heavy hover transforms and red-glow shadows on facility cards, time slots, and stat cards.
- **Register flow** — server password rule realigned to V1's "6 chars + 1 digit" UI; server errors now render as a V1-style inline pill above the submit button instead of a floating banner.
- **Logos** — `logowhite.png` swapped in on the two surfaces that sit on a red background (register hero, admin topbar) so the mark stays visible.

### Round 2 — OTP email verification (complete)

Brings back V1's email-OTP gate on student registration, on top of V2's layered structure:

- New `user_otps` table (one row per user, upsert-on-resend) and an `is_verified` flag on `users` (existing rows grandfathered as verified so old accounts aren't locked out).
- `OtpService` generates a 6-digit code with a 15-minute TTL and a 60-second server-side resend throttle; `MailService` always writes the message body (incl. the code) to `storage/logs/mail.log` so the flow is testable without SMTP.
- Register & login now route unverified accounts to a dedicated `otp-verify.php` page that mirrors V1's UX (Send OTP → 6 inputs → Verify) but in the V2 red palette.
- Re-registering an unverified email replaces the pending row (V1 parity); re-registering a verified one is rejected.

To plug in real mail delivery, swap the SMTP hook inside `MailService::send()` for PHPMailer (or any transport) and toggle `mail.enabled` in `config/app.php`.

### Round 2 — Deferred

- Multi-language UI (V1's `includes/lang/`)
- Security hardening (CSRF, rate limiting)
- Real SMTP transport (PHPMailer wiring)

## License

Inherits the license of the upstream V1 project.
