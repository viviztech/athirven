# Athirven (அதிர்வெண்) — Product Vision

## Problem

தலித் அரசியல் மற்றும் பண்பாட்டு விவாதங்களை (Dalit political and cultural discourse) ஆவணப்படுத்தி, மாதந்தோறும் தமிழில் வெளியிடும் ஒரு நம்பகமான தளம் தேவை. Independent Dalit political-cultural journalism in Tamil struggles with two things at once: a small in-house editorial team needs a real production workflow (draft → review → publish) across departments, and the audience it serves is reached far more effectively through WhatsApp/Telegram forwarding than through algorithmically-suppressed social feeds. Existing generic CMS/blog tools handle neither the editorial workflow nor the distribution reality well, and generic SaaS magazine platforms don't account for the privacy needs of pseudonymous contributors writing on politically sensitive topics.

## Solution

**Athirven** is a single Laravel application — a Filament v5 editorial admin panel for a multi-role team (Editor-in-Chief, Sub-Editor, Writer, Proofreader, Designer) producing a monthly issue, paired with a Blade/Livewire public site for readers, subscribers, and donors. It supports text, audio narration, YouTube video, and downloadable full-issue PDFs; four monetization streams (donations, digital subscriptions, print+digital bundles, ads/sponsorships) via both Stripe and Razorpay; and a distribution strategy built around Tamil SEO, WhatsApp/Telegram sharing, newsletter, and a privacy-conscious analytics choice (Plausible over GA4).

## Reader / Contributor Journey

- **Reader**: discovers an article via WhatsApp/Telegram forward or Tamil search → reads free content or hits the paywall on premium articles → subscribes or donates → gets the monthly issue PDF and audio narration for offline/commute consumption.
- **Writer**: drafts an article in the Filament panel → submits for review → revises per Sub-Editor/Proofreader feedback → sees it scheduled into the monthly issue → published, with byline protection if writing pseudonymously.
- **Editor-in-Chief**: assembles the monthly issue from approved articles, sets cover art, schedules publish date, oversees the review queue and revenue dashboard.

## Module Breakdown

1. **Content Core** — Issues, Articles (multi-type: editorial/interview/essay/poem/book review/cartoon/cover story), Authors (decoupled from User, pseudonym-protected), Categories/Tags (Tamil taxonomy).
2. **Editorial Workflow** — Role/permission model (`spatie/laravel-permission`), article state machine (draft → submitted → in_review → approved/needs_revision → scheduled → published → archived), issue-builder.
3. **Media** — Audio narration, YouTube embeds, uploaded issue PDFs, image/cover management via `spatie/laravel-medialibrary`.
4. **Public Frontend** — Homepage, issue archive, article reader (Tamil typography, dark/reading mode), author/category pages, search, comments (moderated).
5. **Monetization** — Subscription plans (digital/print+digital/patron), donations (one-time/recurring), ads/sponsorships, both Stripe (Cashier) and Razorpay gateways.
6. **Comment Moderation & Privacy** — Pre/post moderation, pseudonym-safe display names, salted IP hashing, encrypted contributor real-identity fields.
7. **Reach & Distribution** — Tamil SEO (sitemap, JSON-LD), WhatsApp/Telegram share + bot, email newsletter, RSS, PWA with offline issue caching.
8. **Analytics** — Plausible integration (privacy-conscious, no per-visitor tracking).
9. **Admin Operations** — Dashboard widgets (review queue, issue progress, revenue/subscriber stats, comment moderation queue).
10. **Security Hardening** — Encrypted contributor identities, moderation audit trail, soft takedowns (archive not delete).

## Pricing / Monetization Tiers (initial draft, refine in Phase 4)

- **Free** — standard articles, ad-supported.
- **Digital Subscriber** — full digital archive access, no ads, ad-free reading.
- **Print + Digital** — physical magazine delivery + full digital access.
- **Patron/Donor** — recurring or one-time support, optional public/anonymous acknowledgement.

## Project Build Phases

### Phase 0 — Setup (~2 weeks)
Laravel 13 scaffold, pinned packages, Filament panel, Vite/Tailwind 4, PostgreSQL, git. **[Done — see below]**

### Phase 1 — Foundation (Month 1-2)
Auth, roles/permissions seeder, core migrations (User/Author/Issue/Article/Category/Tag), basic Filament CRUD, Tamil slug helper decision, homepage skeleton, seeded sample Tamil issue.

### Phase 2 — Editorial Workflow (Month 3-4)
`ArticleStatus` state machine + `ArticleWorkflowService`, role-scoped Filament views, issue-builder relation manager, review-queue widget, comment moderation resource.

### Phase 3 — Public Frontend & Reading Experience (Month 5-6)
Full public site, Tamil typography + dark/reading mode, audio/video components, MVP search, public comments UI.

### Phase 4 — Monetization (Month 7-8)
`PaymentGatewayInterface` with both Stripe/Cashier and Razorpay, subscription checkout, donation flow, paywall gating, print+digital shipping capture, ads/sponsorship resources, revenue widget.

### Phase 5 — Reach & Growth (Month 9-10)
SEO/sitemap/JSON-LD, share buttons, Telegram bot + WhatsApp links, newsletter, RSS, PWA, Plausible integration.

### Phase 6 — Launch Hardening & Launch (Month 11-12)
Security pass, performance/queue/CDN tuning, live-mode payment testing (both gateways), real content migration, soft launch, monitoring, official launch.

---

*Last updated: 2026-07-06*
