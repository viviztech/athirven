# Athirven — Architecture

## Layered Architecture

```
┌─────────────────────────────────────────────────────┐
│ LAYER 5: REACH & DISTRIBUTION                        │
│ SEO/sitemap, Social share, WhatsApp/Telegram bot,    │
│ Newsletter, RSS, PWA, Plausible analytics            │
├─────────────────────────────────────────────────────┤
│ LAYER 4: PUBLIC FRONTEND (Blade + Livewire)          │
│ Home, Archive, Reader, Author/Category pages, Search │
├─────────────────────────────────────────────────────┤
│ LAYER 3: MONETIZATION                                │
│ Subscriptions (Cashier/Razorpay), Donations, Ads     │
├─────────────────────────────────────────────────────┤
│ LAYER 2: EDITORIAL ADMIN (Filament v5)               │
│ Roles/Workflow, Issue Builder, Moderation, Media     │
├─────────────────────────────────────────────────────┤
│ FOUNDATION: CONTENT DATA CORE                         │
│ PostgreSQL + spatie/medialibrary (S3) + spatie/permission │
└─────────────────────────────────────────────────────┘
```

## Data Flow: Publish Event

```
Writer submits → ArticleWorkflowService transitions status
  → Sub-Editor/EIC review, Proofreader annotates
  → EIC approves + schedules into an Issue
  → cron publishes due articles (status → published)
  → ArticlePublished event fires
      → NewsletterSubscriber digest queued
      → Telegram bot posts title+summary+link
      → WhatsApp share link surfaced on article page
      → Plausible records a pageview (no per-visitor tracking)
```

## Tech Stack (confirmed, matches sibling project `merza` where applicable)

- PHP 8.3.31, Laravel Framework 13.18.1
- Filament 5.6.8 (+ spatie-laravel-media-library-plugin 5.6.8)
- spatie/laravel-permission 6.25.0
- spatie/laravel-medialibrary 11.23.1 + intervention/image 3.11.8
- laravel/cashier 15.8.0 (Stripe) — paired with a custom Razorpay gateway (Phase 4)
- barryvdh/laravel-dompdf 3.x (auxiliary generated PDFs only — issue PDFs are uploaded, not generated)
- league/flysystem-aws-s3-v3 3.35.1
- Livewire 4.3.3
- Vite 8 + Tailwind CSS 4 (`@tailwindcss/vite`) — Laravel 13's default, one major version ahead of merza's Vite 7 pin; not a compatibility concern.

**Database — deviation from the original plan, resolved during setup**: the plan initially specified MySQL to match `merza`'s `.env.example`. During Phase 0 setup, we found `merza`'s *actual* `.env` (not its example file) runs on **PostgreSQL 17**, and this machine only has PostgreSQL running as a service (no MySQL server process, despite the MySQL client being installed). Athirven uses **PostgreSQL** (`DB_CONNECTION=pgsql`, database `athirven`, UTF8 encoding for Tamil content) to match the actual environment and the real convention in use.

## Roles & Permissions (spatie/laravel-permission)

Roles: `Editor-in-Chief`, `Sub-Editor`, `Writer`, `Proofreader`, `Designer`, `Admin`, `Subscriber` (no panel access).

Key permissions: `articles.create`, `articles.edit.own`, `articles.edit.any`, `articles.submit`, `articles.review`, `articles.approve`, `articles.reject`, `articles.schedule`, `articles.publish`, `articles.archive`, `issues.manage`, `media.upload`, `comments.moderate`, `subscriptions.manage`, `ads.manage`, `authors.view-real-identity`.

## Article State Machine

```
draft → submitted (Writer)
submitted → in_review (Sub-Editor picks up)
in_review → approved | needs_revision (Sub-Editor / Editor-in-Chief)
needs_revision → submitted (Writer revises)
approved → scheduled (Editor-in-Chief assigns issue + date)
scheduled → published (cron publishes due articles)
published → needs_revision | archived (correction/takedown)
```

Enforced via `ArticleWorkflowService::transition()` + `ArticlePolicy` — never a raw Filament form field write.

## Payment Gateway Abstraction (Phase 4)

```
PaymentGatewayInterface
├── CashierStripeGateway   (diaspora subscribers/donors — Malaysia/SG/US/UK/etc)
└── RazorpayGateway        (Indian subscribers — UPI Autopay for recurring)
```
Routed per `SubscriptionPlan.gateway` / `Donation.gateway` column — both are first-class from the data model outward, not a stub.

---

*Last updated: 2026-07-06*
