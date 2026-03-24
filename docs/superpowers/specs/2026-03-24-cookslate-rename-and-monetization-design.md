# Cookslate: Rename & Monetization Strategy

*Design spec for renaming Crumble to Cookslate and establishing a dual-track monetization model.*

## Implementation Phases

This spec covers the full strategy but is **implemented in two separate phases**, each with its own plan:

1. **Phase A: Rename + Open Core + License Key** — Rename the app, set up the public repo, implement the free/pro tier split with license gating, build the license key server, and create the marketing landing page. This is the plannable-now phase.
2. **Phase B: Cookslate Cloud (SaaS)** — Build the Supabase-backed Cloud tier with database abstraction, tenant provisioning, and hosted onboarding. This phase gets its own spec and plan after Phase A ships.

This spec should be used to plan **Phase A only**. Phase B sections (SaaS architecture) are included for strategic context but are explicitly deferred.

---

## 1. Brand Identity

**Name:** Cookslate
**Tagline:** "Your recipes. Your way."
**One-liner:** A recipe manager that remembers how *you* cook — not just what you cook.

### Brand Voice

- Friendly, never corporate ("Welcome back" not "Session authenticated")
- Food metaphors over tech metaphors (keep the terracotta/cream/sage palette)
- Confident but not preachy — assumes you know how to cook, just helps you organize
- Slightly playful in microcopy (empty states, error messages, onboarding)

### The "Slate" Metaphor

The word "slate" runs through the product language:

- Annotations = writing in the margins of your slate
- Cook journal = your cooking diary on the slate
- Import = adding a recipe to your slate
- The brand mark could be a minimal slate/clipboard shape with a subtle food element

### Domain Strategy

- **Primary:** cookslate.com
- **Redirect:** cookslate.app
- **SaaS:** app.cookslate.com
- **Marketing site:** cookslate.com

### Namespace Validation

As of March 2026, "Cookslate" has zero collisions in:

- App stores (iOS, Android)
- Recipe app space
- Web search results
- Domain namespace

---

## 2. Product Tiers & Licensing

### Cookslate Free (open source, self-hosted)

The hook. Gets people into the ecosystem.

- Import recipes by URL (scraper)
- Recipe storage, search, tags, favorites
- Basic cook mode (step-by-step, timers, wake lock)
- Single user
- Community support (GitHub issues)

### Cookslate Pro — $9.99 one-time (self-hosted license key)

For self-hosters who want the full experience.

Everything in Free, plus:

- Meal planning + grocery list generation
- Cook tracking (journal, stats, forgotten favorites, year-in-review)
- Recipe annotations (margin notes)
- Multi-user / household support (up to 5 users)
- Data export (JSON-LD, Cooklang)
- PWA / offline mode
- Priority support (email)

### Cookslate Cloud — $2.99/month or $24.99/year (SaaS)

For non-technical users who don't want to self-host.

Everything in Pro, included:

- Hosted on Supabase (managed infra)
- Automatic updates, backups
- Custom subdomain (yourname.cookslate.app)
- Onboarding wizard

### Pricing Rationale

- **$9.99 one-time** is an impulse buy. Lower than Paprika ($4.99/platform x 3 = $15). One price, all platforms.
- **$2.99/month** is cheaper than every competitor subscription (Mealime $5.99, Whisk $3.99, Plan to Eat $5.95). Annual price ($24.99) gives a 30% discount.
- Goal is cost recovery (LLC + domains + hosting), not venture-scale revenue.

### License Key Mechanics

**Storage:** License key is stored in the server-side config file (`config/license.key`) — a plain text file containing only the key string. This file is gitignored. The key is read by `config/license.php` on boot and cached in memory for the request lifecycle.

**Activation flow (user perspective):**
1. User purchases a Pro license at cookslate.com — receives a key via email
2. In their self-hosted Cookslate instance, they navigate to Settings > License
3. They paste the key and click "Activate"
4. The backend writes the key to `config/license.key` and calls `cookslate.com/api/license/verify` to validate it
5. On success, Pro features unlock immediately

**Validation:**
- Key checked once on activation + monthly heartbeat via `cookslate.com/api/license/verify`
- Works offline — if the monthly check fails (network issue), it retries next month
- No DRM, no kill switch — honor system with a speed bump
- Key is a signed JWT containing: email, purchase date, tier (pro). Verification checks the signature against a public key bundled with the app, so basic validation works even fully offline.

**License key server:**
- **What it is:** A standalone PHP app hosted at cookslate.com (same domain as the marketing site).
- **Where it lives:** Separate directory/deployment from the main Cookslate app (e.g., `cookslate.com/api/license/`).
- **What it does:** Three endpoints — `POST /generate` (called by payment webhook), `POST /verify` (called by self-hosted instances), `GET /status` (admin dashboard).
- **Payment processor:** LemonSqueezy — it handles checkout pages, payment processing, and has a webhook that triggers key generation. No need to build a custom storefront or integrate Stripe directly.
- **Database:** A single `licenses` table (key, email, tier, created_at, last_verified_at, active). Can live in a small MySQL database on the same hosting as the marketing site.

---

## 3. Open Core Strategy

### Licensing Structure

The project uses a **dual license** model (single repo):

- **Free-tier code** is licensed under **MIT** — fully open source, no restrictions.
- **Pro-tier code** is licensed under a **Business Source License (BSL 1.1)** — source-available, readable and auditable, but requires a paid license key for use. The BSL automatically converts to MIT after 3 years (standard BSL practice).

This is the same model used by MariaDB, Sentry, and CockroachDB. It builds trust (people can audit what they're paying for) while maintaining the business model.

### What's MIT (Free Tier)

The full free-tier codebase:

- Recipe CRUD, import/scrape, search, tags, favorites
- Basic cook mode
- Single-user auth
- Database schema and migrations
- Frontend (React + Vite + Tailwind)
- Backend (PHP API)

### What's BSL (Pro Tier — License Key Required)

Pro features live in the same repo, in clearly separated directories:

**Backend:** Pro PHP files live in `api/pro/` (controllers, models, services). The main router conditionally includes pro routes only when `License::isActive()` returns true. Free-tier code in `api/controllers/`, `api/models/`, etc. never references pro code directly.

**Frontend:** Pro React components live in `frontend/src/pro/` (pages, components, hooks). The main router uses lazy-loaded routes that only render when the `/api/license/status` endpoint returns `{active: true}`. Free-tier components import nothing from `src/pro/`.

**Pro modules:**
- `api/pro/controllers/MealPlanController.php` — Meal planning
- `api/pro/controllers/StatsController.php` — Cook tracking / stats / year-in-review
- `api/pro/models/RecipeAnnotation.php` — Recipe annotations
- `api/pro/` auth middleware — Multi-user support
- `api/pro/controllers/ExportController.php` — Data export
- `frontend/src/pro/` — Corresponding UI for all above
- PWA service worker registration (in `vite.config.js`, conditionally included)

---

## 4. Go-to-Market Strategy

### Phase 1: Foundation (prerequisites before any marketing)

1. **README.md** — screenshots, one-line pitch, 7-step install guide, tech stack badges
2. **Public repo** on GitHub (Forgejo push-mirrors to GitHub automatically)
3. **Landing page** at cookslate.com — hero screenshot, feature list, "Self-host free" + "Try Cloud" CTAs
4. **License key server** — simple PHP endpoint for key generation/validation
5. **.env.example** — clean onboarding for self-hosters

### Phase 2: Organic Discovery

Target channels where self-hosted recipe app users actually browse:

1. **r/selfhosted** — "Show off your project" post with screenshots + differentiators (zero-Docker, cook tracking, annotations)
2. **awesome-selfhosted** — submit PR to the GitHub awesome-selfhosted list (source for every comparison article)
3. **Cooklang blog** — get listed on their open source recipe managers page
4. **Hacker News** — "Show HN: Cookslate — a recipe manager that tracks how you cook, not just what you cook"

### Phase 3: Content & SEO

Low-effort, high-compound-interest plays:

1. **Comparison page** — "Cookslate vs Mealie vs Tandoor vs KitchenOwl" (honest comparison; win on deployment simplicity)
2. **"Why self-host your recipes?"** blog post — privacy angle, data ownership, no subscriptions
3. **Import/migration guides** — "Migrate from Paprika to Cookslate", "Migrate from Mealime to Cookslate" (captures active switchers)

### Phase 4: Cloud Launch

After self-hosted version has GitHub traction:

1. Soft launch Cloud tier to existing self-hosted users ("Want us to host it for you?")
2. Add subtle "Powered by Cookslate" footer link on free-tier instances
3. ProductHunt launch once testimonials exist

---

## 5. Revenue Model & Break-Even Analysis

### Monthly Expenses

| Expense | Monthly Cost |
|---------|-------------|
| LLC maintenance | ~$10 |
| Domains (cookslate.com + .app) | ~$3 |
| Supabase (Pro plan) | ~$25 |
| Misc (email, DNS) | ~$5 |
| **Total** | **~$43/month** |

### Break-Even Scenarios

| Scenario | Revenue |
|----------|---------|
| 15 Cloud subscribers @ $2.99/mo | $45/month |
| 52 Pro licenses/year @ $9.99 | $43/month avg |
| 8 Cloud + 25 Pro/year | ~$45/month |

### What We Explicitly Don't Do

- No ads, ever
- No AI features (let Mealie and Ladle chase that)
- No social features or recipe sharing marketplace
- No mobile native apps (PWA covers this)
- No freemium nagging — free tier is genuinely useful, not crippled

---

## 6. Competitive Positioning

### Cookslate's Moat

| Differentiator | Cookslate | Mealie | Tandoor | KitchenOwl |
|---------------|-----------|--------|---------|------------|
| Deploy without Docker | Yes | No | No | No |
| Runs on shared PHP hosting | Yes | No | No | No |
| Cook tracking + journal | Full | None | Basic | None |
| Recipe annotations | Yes (planned) | No | No | No |
| Year-in-review stats | Yes (planned) | No | No | No |
| One-time purchase option | $9.99 | Free | Free | Free |
| Hosted SaaS option | $2.99/mo | No | No | No |

### Positioning Statement

Cookslate is for home cooks who want a recipe manager that's simple to deploy, respects their data, and remembers their cooking journey — not just their recipes. It's the only self-hosted recipe app that runs on standard PHP hosting without Docker, and the only one that treats cooking as a practice worth tracking over time.

---

## 7. Technical Implementation Notes

### Rename Scope

The rename from Crumble to Cookslate touches:

- Package names (frontend package.json, API references)
- URL routing (api prefix, .htaccess)
- Frontend branding (title, meta tags, PWA manifest, favicon)
- Database name (keep as-is internally — no migration needed, only the app-facing name changes)
- Documentation (README, CLAUDE.md, docs/)
- Git repo name

### SaaS Architecture (Cookslate Cloud) — DEFERRED TO PHASE B

*This section is included for strategic context. It will get its own dedicated spec and plan after Phase A ships.*

Cookslate Cloud will be a **separate deployment** of the same codebase, not a fork. The approach:

- **Shared monorepo** with a database abstraction layer. Models use a `DatabaseAdapter` interface with two implementations: `MysqlAdapter` (self-hosted) and `SupabaseAdapter` (Cloud).
- **Auth:** Supabase Auth for Cloud tier; PHP sessions remain for self-hosted.
- **Database:** Supabase Postgres for Cloud; MySQL remains for self-hosted.
- **Storage:** Supabase Storage for Cloud; local filesystem remains for self-hosted.
- **Deployment:** Cloud instances are provisioned per-tenant on Supabase.
- **Build-time configuration:** An environment variable (`COOKSLATE_PLATFORM=selfhosted|cloud`) selects the adapter set at boot.

### License Gating Implementation

- PHP middleware checks for valid license key in `config/license.php`
- Pro features check `License::isActive()` before rendering
- Frontend checks license status via `/api/license/status` endpoint
- Graceful degradation — pro features simply don't appear in free tier, no error messages or nag screens

---

## 8. Success Criteria

1. **Visibility:** Listed on awesome-selfhosted within 3 months of public launch
2. **Adoption:** 50+ GitHub stars within 6 months
3. **Revenue:** Break-even ($43/month) within 12 months of Cloud launch
4. **Retention:** Cloud churn < 5% monthly
5. **Quality:** Maintain the "boring app wins" philosophy — nail the core loop before adding features
