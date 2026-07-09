# Rocket Batteries — Website Development Plan

> Team reference. Read [`../CLAUDE.md`](../CLAUDE.md) for the hard rules (branching, no destructive DB commands, local-only seeders) before writing any code.

## Context

Rocket Batteries India Pvt. Ltd. needs a new **dynamic B2B catalog + enquiry website** on **Laravel + MySQL**. The client's sister-brand site (https://enertectbatteries.co.in/) is dated and thin — **no search, no filters, no enquiry forms, no datasheets, no live chat**. Our goal is to be clearly better in usability, features, and design.

Scope from the Digilinkers PO: dynamic site, custom theme, unlimited pages, 100–150 products, slider, enquiry form, Google Map, WhatsApp chat, testimonials, gallery, social links, click-to-call/email, mobile responsive, SSL, content writing, blog. Added on top: **advanced filtering/search**, **downloadable PDF datasheets**, and a **warranty registration + lookup portal**. Client supplied content and product data (EV, EnerRocket ES/ESC/ESH-TPPL series with detailed spec tables).

**Outcome:** a fast, SEO-strong, self-manageable marketing + catalog site with a custom admin, ready to launch and easy to extend.

## Decisions

| Area | Decision |
|---|---|
| Framework / DB | Laravel (latest, PHP 8.4) + MySQL 8.4 |
| Frontend | Blade + **Bootstrap 5** + Alpine.js + **Livewire** (server-rendered for SEO) |
| Admin | **Custom-built** admin (not Filament/Nova) |
| Commercial model | **Catalog + enquiry** (no cart/checkout/prices) |
| Warranty portal | **In core scope** — registration + serial/mobile lookup |
| Differentiators | Advanced product filtering & search; downloadable PDF datasheets |
| Media | Admin upload → local filesystem storage (driver-abstracted) |
| Design | Clean base theme built in-house; client to send logo/brand; UI polish later |
| Hosting | Undecided → **build for portability**; confirm Laravel needs (SSH, Composer, queue/cron) before locking a host (VPS strongly preferred over shared cPanel) |

## Architecture & Data Model (high level)

The catalog is the core. Battery specs differ per series (EV uses Ah + C5/C20; ES/ESC use 20/10/5-hr rates, resistance, temps, currents; ESH/TPPL uses watts), so the spec system must be **flexible and admin-editable — not hardcoded columns**.

Core tables (indicative):
- `categories` (e.g. EV Batteries, EnerRocket Stationary) and `series` (EV, ES, ESC, ESH/TPPL); series belongs to category.
- `products` — model/SKU, name, slug, series_id, nominal voltage, short/long description, hero image, status, featured flag, sort order, SEO fields.
- **Spec system (EAV, template-driven):** `attributes` (name, unit, group, data type, `is_filterable`) → attached per series as a spec template → `product_attribute_values` (product_id, attribute_id, value). Renders the datasheet table per product and powers filtering; filterable attributes (voltage, capacity, application) are indexed.
- `applications` (UPS, Solar, Telecom, EV, Medical, Fire/Safety) ↔ `products` many-to-many — primary filter facet.
- `product_media` — polymorphic media (images + uploaded datasheet PDFs), reusable for gallery/blog.
- `enquiries` — general + per-product "Request Quote"; stored in DB + emailed; admin inbox with status.
- `warranties` — registration (customer name, mobile, email, product, serial no., purchase date, dealer, invoice upload) + public lookup by serial/mobile; admin verify/approve/reject. **Assumption: no customer login** (public form + public lookup); revisit if login accounts are wanted.
- CMS: `pages`, `blog_posts` + `blog_categories`, `testimonials`, `gallery_albums`/`gallery_images`, `news_events`, `clients` (logos), `sliders`, `settings` (contact info, socials, WhatsApp, map embed, SMTP), nav/menus.
- `admin_users` with a simple, extensible role/permission layer (single super-admin to start).

Public interactivity via **Livewire**: product listing with live filter/search, enquiry forms, warranty registration + lookup.

## Phased Plan

### Phase 0 — Setup & guardrails
`develop` branch; fresh Laravel (PHP 8.4 / MySQL 8.4); `.env.example`; Bootstrap 5 + Alpine + Livewire via Vite; `CLAUDE.md`; base layout, mail/storage/settings config.
**Deliverable:** running skeleton, home renders base theme.

### Phase 1 — Data model & migrations
Additive migrations for all tables; models + relationships + factories; **local-only** seeders loading supplied product data (EV/ES/ESC/ESH) + spec templates + demo CMS content.
**Deliverable:** schema + seeded local catalog. (Owner runs migrations against any existing DB; never reset.)

### Phase 2 — Custom admin panel
Auth-protected `/admin`; CRUD for categories/series, attributes & spec templates, products (specs, images, datasheet upload), applications, blog, pages, testimonials, gallery, news/events, clients, sliders, settings; enquiry inbox; warranty management. Server-side validation, image handling, WYSIWYG.
**Deliverable:** client can manage 100–150 products and all content end-to-end.

### Phase 3 — Public site: catalog + core pages
Home (slider, featured products, about teaser, applications, clients, testimonials, CTA); product listing with **Livewire filter/search** (application, series, voltage, capacity) + product detail (spec table, images, **PDF datasheet**, "Request Quote"); About/Vision/Mission; series/category landings; blog list + post; gallery; news/events; clientele; contact (map, form, click-to-call/email, WhatsApp, socials). Responsive nav/footer, floating WhatsApp, breadcrumbs.
**Deliverable:** complete browsable catalog + marketing site.

### Phase 4 — Enquiry, warranty & integrations
Enquiry forms (general + per-product) → DB + email + admin inbox; spam protection (honeypot/rate-limit; captcha optional). Warranty: public registration (invoice upload) + public lookup + admin verification. WhatsApp click-to-chat, Google Maps embed, social links, SMTP wiring, SSL notes.
**Deliverable:** all lead-capture and warranty flows working.

### Phase 5 — SEO, performance, hardening
Meta/OG per page, `sitemap.xml`, robots, schema.org Product/Organization, slugs, 301 strategy; caching, image optimization, lazy-load, asset build; accessibility pass; security review (validation, authz, upload safety, rate limits).
**Deliverable:** launch-ready, audited build.

### Phase 6 — Content, QA & launch
Real content/copywriting, real logo/brand theming, real images/datasheets; cross-device/browser QA; form/email testing; redirect map; **host decision & deploy** (document shared-hosting limits if applicable), DB migrate (owner-run), env, SSL, backups, 5 email IDs.
**Deliverable:** live site.

### Phase 7 (post-launch, optional)
Deeper UI/design pass; deferred candidates: spec comparison tool, dealer/distributor locator, multilingual, newsletter.

## Verification (drive it, don't just build it)

- **Run:** `php artisan serve` + `npm run dev`; render on desktop + mobile widths.
- **Catalog:** seed local data; exercise filter/search combinations; open products; download a datasheet PDF.
- **Admin:** create/edit a product with specs + image + PDF; publish a blog post; edit settings — confirm on public site.
- **Enquiry:** submit general + per-product; confirm DB row + admin inbox + email (Mailpit/log locally).
- **Warranty:** register with invoice upload; look up by serial/mobile; verify/approve in admin; confirm status change.
- **SEO:** check meta/OG tags, `/sitemap.xml`, one Product schema block.
- `php artisan test` (feature tests for filtering, enquiry, warranty, admin auth) before each PR into `main`.

## Open items to confirm
- Hosting environment (VPS vs GoDaddy shared) — affects queue/cron/deploy.
- Warranty: public form + lookup (assumed) vs customer login accounts.
- Final product count and whether all four series' spec sheets are final.
