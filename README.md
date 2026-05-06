# INTI Smart Booking FYP — Refactor

Layered rewrite of [INTI_Smart_Booking_FYP](https://github.com/XXD051030/INTI_Smart_Booking_FYP) (referred to as "V1" below).

The goal of this repository is to **preserve V1's UI and feature set pixel-for-pixel** while replacing V1's flat procedural script layout with a clean layered structure (Repositories / Services / Views).

> Status: Round 1 complete — V1 UI lifted verbatim onto a layered backend. Round 1.5 visual polish, Round 2 OTP email verification, multi-language UI, and Round 2 security hardening (CSRF + rate limiting) all landed. See [Roadmap](#roadmap).

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

All V1 features have now been ported. OTP email verification and multi-language UI both landed in Round 2 (see Roadmap below).

## Known Technical Debt

This refactor mirrors V1 as faithfully as possible. Remaining gaps:

- Default admin credentials are documented in this README (intentional for dev)
- Mail delivery currently writes to `storage/logs/mail.log`; real SMTP wiring still TODO

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

To plug in real mail delivery, set the env vars listed in the [SMTP / PHPMailer section](#round-2--smtp-transport-complete) below and `MAIL_ENABLED=true`.

### Round 2 — Multi-language UI (complete)

Brings back V1's English/Bahasa Melayu/Chinese language switcher, layered onto V2's structure:

- New `Translator` class in `src/Support/` loads dictionaries from `src/Lang/{en,ms,zh}.php` (lifted verbatim from V1's `includes/lang/`). Locale resolves from `$_SESSION['language']` with config-driven default; an unknown locale falls back to English.
- `app()->translator()` and an `__('key')` helper feed the views; `<html lang>` on every layout reflects the active locale.
- `language.php` and `langsave.php` keep V1's switcher UX (POST → save → redirect) but read the available locale list from config. V1's broken Tamil option (no `ta.php` ever existed) is dropped.
- View conversion covers V1's translated surfaces — login, register, sidebar, topbar, general, booking, my bookings, rules, support, settings, profile. New V2-introduced labels (admin pages, OTP screens, calendar polish) are dictionary-keyed in `en.php` only and degrade to English under ms/zh until translations are added.

### Round 2 — Security hardening (complete)

- **CSRF**: per-session synchronizer token rotated on login/logout. Token reaches the browser through a `<meta name="csrf-token">` tag on every layout (and the two layout-less pages — register and otp-verify). HTML forms include it via `csrf_field()`; AJAX call sites pull it from the meta tag and send it as both a `_token` body field and an `X-CSRF-Token` header. `verify_csrf_or_fail()` guards every state-changing endpoint (login, register, booking create/cancel, mark-notification-read, langsave, otp-verify, admin login + actions). Bad/missing tokens return HTTP 419.
- **Rate limiting**: SQLite-backed `RateLimiter` service (no extra dependency). Login throttled at 10 attempts per IP and 5 per email per 15 minutes; register at 5 per IP per 15 minutes; OTP send at 10 per IP per hour (on top of the existing 60-second per-user throttle). Successful login clears its IP+email buckets; rate-limited responses are HTTP 429 with a `retry_after` field for AJAX consumers.

### Round 2 — SMTP transport (complete)

PHPMailer 6.9.3 is vendored under `lib/PHPMailer/` (matches V1's no-composer style; the autoloader in `bootstrap.php` knows the `PHPMailer\\PHPMailer\\` prefix). `MailService::send()` builds a configured `PHPMailer` instance when `mail.enabled` is true, and otherwise no-ops with an audit-log entry — so dev still works with no SMTP at all.

Configure via env vars (read with `getenv()`, so `php.ini`'s `variables_order` doesn't matter):

| Env var | Default | Notes |
|---|---|---|
| `MAIL_ENABLED` | `false` | Set to `true` to actually deliver mail |
| `SMTP_HOST` | — | e.g. `smtp.gmail.com` |
| `SMTP_PORT` | `587` | `587` for STARTTLS, `465` for implicit TLS |
| `SMTP_USERNAME` | — | Auth disabled if empty |
| `SMTP_PASSWORD` | — | App password recommended |
| `SMTP_ENCRYPTION` | `tls` | `tls` (STARTTLS), `ssl` (implicit), or empty (none) |
| `MAIL_FROM_ADDRESS` | `no-reply@inti.local` | |
| `MAIL_FROM_NAME` | `INTI Smart Booking` | |

Send results are always appended to `storage/logs/mail.log` with a `QUEUED` / `SENT` / `FAILED` tag — useful as an audit trail and as the dev fallback when SMTP is off. SMTP failures are caught: the user-facing flow stays alive and the error message is logged.

### Round 2 — Deferred

- ms/zh translations for V2-new keys (admin / OTP / calendar polish)

## License

Inherits the license of the upstream V1 project.
