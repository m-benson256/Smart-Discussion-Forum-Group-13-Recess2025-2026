# BWAT.md

This file provides guidance to Bwat when working with code in this repository.

## Tech Stack

- **Framework**: Laravel 13 (Livewire Starter Kit) — PHP 8.3+
- **Frontend**: Livewire 3.6 + Flux 2.13 + Volt 1.7 + Alpine.js 3.4 (student dashboard), Tailwind CSS v3 + Vite
- **Auth**: Laravel built-in auth guard using the `users` table (User model) with email-domain-gated registration (`@students.ed` or `@lecturers.ed`)
- **Database**: SQLite (default) with `database` driver for both queue and cache — no Redis/file fallback
- **Testing**: Pest PHP 4 (tests in `tests/`), PHPStan level 7, Laravel Pint (preset: `laravel`)
- **Design deps**: `@tailwindcss/forms`, `@tailwindcss/vite` (Tailwind v4 vite plugin alongside v3 config via CDN on welcome page and student dashboard)

## Brand Identity

**Colors** (Material Design 3 palette, defined inline in welcome blade `#tailwind-config`):
- Primary: `#6da9ee` / `hsl(var(--primary))`
- Primary container: `#1a365d`
- Secondary: `#505f76`
- Background / surface: `#faf8ff`
- Surface container: `#eaedff`
- Surface container highest: `#dae2fd`
- On-surface (text): `#131b2e`
- Error: `#ba1a1a` / error container: `#ffdad6`
- Outline / border: `#74777f` / `#c4c6cf`
- Sidebar (student dashboard): `#1a2e4c`
- Sidebar (standalone admin page Admini.html): `#0f172a`

**Typography**:
- Display / heading: `"Source Serif 4"`, serif (welcome page); `"Figtree"`, sans-serif (default Laravel layout); `"Inter"` (Admini.html)
- Body: `"Hanken Grotesk"`, sans-serif (welcome page); Figtree (default)
- Mono: system default

**Geometry**:
- Border radius: `0.125rem` default, `0.5rem` (xl), `0.75rem` (full)
- Spacing: custom tokens — `margin-desktop: 48px`, `base: 8px`, `gutter: 24px`, `container-max-width: 1200px`

**Visual language**: Academic/institutional — clean, flat surfaces, restrained blue-slate palette, generous whitespace, serif display for headings.

## Architecture Notes

**Two parallel user systems**: Laravel's built-in `users` table (with `role` + `status` columns added via migration) is the auth guard used for login/registration. There is also a separate `members` table with child tables (`students`, `lecturers`, `administrators`) that reference it via `UserID` foreign keys — these are the feature-level user profiles for forum features (groups, topics, messages, warnings, quizzes). The `members` table is NOT connected to Laravel's auth guard. The `Member` model extends `Authenticatable` but the `config/auth.php` provider points to `User::class`.

**Email-address-based role routing**: Registration is gated to `@students.ed` (student role) and `@lecturers.ed` (lecturer role). After login, `/dashboard` checks `auth()->user()->email` — if it ends with `@lecturers.ed`, redirect to `lecturer.dashboard`; if `@students.ed`, redirect to `student.dashboard`; otherwise logs the user out with an error. The `role` column on `users` is not used for routing — the email suffix is the actual dispatcher.

**Content moderation system**: A full warning system with `WarningNumber`, `Status` (pending/resolved/expired), `Deadline`, linked to administrators and members. Quizzes have auto-submission detection (`AutoSubmitted` boolean on attempts).

**Standalone admin panel**: `Admini.html` at the project root is a standalone HTML/CSS/JS admin dashboard (not integrated with Laravel routing or Blade). It uses Chart.js for charts and has full admin CRUD mockups for users, groups, topics, warnings, reports, and settings.

## Commands

- `composer run dev` — concurrently starts `php artisan serve`, `php artisan queue:listen --tries=1`, and `npm run dev` (Vite HMR)
- `composer run setup` — full fresh-project setup: installs deps, creates `.env`, generates key, runs migrations, builds frontend
- `composer run test` — runs lint check + phpstan + pest test suite (all checks)
- `composer run lint` — runs `pint --parallel` (auto-fix style)
- `composer run lint:check` — `pint --parallel --test` (check style without fixing)
- `composer run types:check` — `phpstan analyse` (level 7)

## Coding Conventions

- **Custom primary keys**: Feature-level models use non-standard PKs — `UserID`, `GroupID`, `TopicID`, `QuizID`, `WarningID`, `CategoryID`, `StudentID`, `LecturerID`, `AdminID`, `PostID`. Always check the model's `$primaryKey` and migration before assuming a column name. Pivot table `group_members` uses Laravel default `id`.
- **Blade views**: Student dashboard is a single large Blade view at `resources/views/student/dashboard.blade.php` (~880 lines) with all JS inline — no separate JS file. Lecturer dashboard is a minimal placeholder at `resources/views/lecturer/dashboard.blade.php`.
- **Pint preset**: `laravel` (config in `pint.json`)
- **PHPStan level 7** — strict analysis across app/, bootstrap/, config/, database/, routes/

## Gotchas

- **`DB::prohibitDestructiveCommands()`** is enabled in production via `AppServiceProvider`, blocking `migrate:fresh`, `migrate:refresh`, `migrate:reset`, and `db:wipe`. Run them locally only.
- **Cache and queue both default to `database` driver** — requires the `cache` and `jobs` tables (created by the `0001_01_01_000001_create_cache_table` and `0001_01_01_000002_create_jobs_table` migrations). These must exist before the app works.
- **On Windows with WAMP**, switch from the default SQLite to MySQL: set `DB_CONNECTION=mysql` in `.env`, and set `'engine' => 'InnoDB'` in `config/database.php` for the mysql block. Without InnoDB, migrations fail with key-length errors on the users table.
- **Two user models** — `User` (auth guard) and `Member` (forum profiles). They are separate tables. The `Topic` model from the latest migration (`2026_07_02_135828`) references `users` (via `user_id`), but the original `topics` migration references `members.UserID`. Both sets of columns exist on the `topics` table after the migration. Check which FK you need before writing queries.
- **Email-domain routing** is hardcoded — `@students.ed` → student dashboard, `@lecturers.ed` → lecturer dashboard. Any other domain is rejected at both registration and login. This is enforced in `RegisteredUserController::store()` and in the `/dashboard` route closure.
