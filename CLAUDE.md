# CLAUDE.md — Rocket Batteries Website

Standing instructions for anyone (human or AI) working in this repository.

## Project

B2B **catalog + enquiry** website for **Rocket Batteries India Pvt. Ltd.**
Stack: **Laravel (PHP 8.4) + MySQL 8.4**, **Blade + Bootstrap 5 + Alpine.js + Livewire**.
Custom-built admin panel. No cart/checkout/pricing — enquiry/lead capture only.
Full scope and phases: see [`docs/PROJECT_PLAN.md`](docs/PROJECT_PLAN.md).

## Hard rules (do not break)

1. **Never commit to / work on `main`.** Work on `develop` and feature branches off it. Open PRs into `main`.
2. **No destructive database commands.** Never run `migrate:fresh`, `migrate:refresh`, `migrate:reset`, `db:wipe`, or any `DROP`. Use **additive, reversible** migrations only (every migration has a working `down()`). If a reset is ever genuinely needed, ask the repo owner to do it — do not run it yourself.
3. **Seeders are local-only.** They may be built for local dev/demo data, must be environment-guarded (`app()->environment('local')`), and are **never** run on production.
4. Secrets stay out of git. Never commit `.env`; keep `.env.example` current.

## Conventions

- **UI:** Bootstrap 5 (not Tailwind). Livewire for dynamic components; Alpine for light interactivity.
- **Media:** local filesystem via Laravel's `Storage` facade, driver-abstracted (so S3 is a config swap later).
- **SEO is first-class:** server-rendered pages, meta/OG tags, `sitemap.xml`, schema.org Product markup, clean slugs.
- **Locale:** English only for now, but keep strings translatable.
- **Product specs are template-driven (EAV-style)** — specs differ per series, so they are admin-editable, not hardcoded columns. See the data-model section in the plan.
- Validate all input server-side; guard file uploads; rate-limit public forms.
- Prefer feature tests for catalog filtering, enquiry, warranty, and admin auth before each PR.

## Getting started (once the app is scaffolded)

```
composer install
cp .env.example .env && php artisan key:generate
# configure DB in .env (MySQL), then:
php artisan migrate           # additive only
php artisan db:seed           # LOCAL ONLY
npm install && npm run dev
php artisan serve
```
