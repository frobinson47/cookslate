# Cookslate — Future Ideas & Research Archive

*Extracted 2026-03-10 from ideas-and-explorations.md (148 deep dives).*
*These are non-actionable items: competitive analysis, design philosophy, research notes, and deferred concepts. For buildable items, see `roadmap.md`.*

*The full unabridged deep dives are preserved in `ideas-archive.md` if details are needed.*

---

## Competitive Landscape

### Self-Hosted Field (March 2026)

| App | Stack | Deploy | Strengths | Weaknesses |
|-----|-------|--------|-----------|------------|
| Mealie (v3.12) | Python/Vue | Docker | Most polished UI, best scraper, OpenAI structured outputs, date-range grocery | Docker required, slower than PHP |
| Tandoor | Python/Django | Docker/K8s | Most features (nutrition, cost), advanced permissions | Complex setup, heavyweight |
| KitchenOwl | Flask/Flutter | Docker | Best mobile (native apps), real-time grocery sync, expense tracking | Limited scraper |
| Norish (v0.15) | Next.js | Docker | Real-time WebSocket, household-first, video/image AI import | New, smaller community |
| Recipya | Go | Docker | Lightweight, fast | Fewer features |
| Cooklang | CLI/text | None | Best data portability (plain text), no server needed | Not a web app, CLI-oriented |
| **Crumble** | **PHP/React** | **Apache** | **Only non-Docker web app, best cook tracking, warm UI** | **No export, no collab, invisible** |

### Crumble's Position
- **Moat:** Zero-Docker deployment on any PHP hosting. LAMP stack compatibility.
- **Unique feature:** Cook tracking (CookMode + cook log + forgotten favorites + year stats). No competitor matches this.
- **Biggest gap:** No data export. First question any evaluator asks.
- **Biggest meta-gap:** Complete invisibility. No public repo, no README, not on any comparison list.

### Commercial App Trends (2026)
- AI-powered recipe capture from social media (TikTok, Instagram) is the #1 differentiator
- Household collaboration and shared grocery lists are table stakes for family apps
- One-time purchases ($5-30) are losing to subscriptions ($5-35/year)
- Privacy/data ownership driving interest in self-hosted solutions

### Strategic Decisions
- **Don't chase AI.** Video/social media import requires expensive infrastructure. Own the "I have URLs" use case.
- **Don't build multi-user grocery sync.** WebSocket + conflict resolution is disproportionate for LAMP stack. "Copy as text" bridges to collaborative apps.
- **Do add export.** JSON-LD is highest value (~1hr). Cooklang is nice-to-have.
- **Do make Crumble visible.** README + public repo is prerequisite for any adoption.

---

## Design Philosophy

### On Restraint (DD132)
Feature filter: "Would a first-time user smile or be confused?" If confused, don't build it. The simplest version is usually right. Every deep dive starts ambitious and ends with "just do this one small thing."

### Brand Voice (DD35)
Crumble sounds warm, consistent, cook-not-coder. Recipe descriptions use natural language. Error messages are friendly. The UI uses food metaphors (terracotta, cream, sage) not tech metaphors. Protect this voice when adding features.

### Emotional Design (DD28)
Three small builds that add warmth without complexity:
- CookMode completion celebration (confetti/animation when finishing a recipe)
- First-recipe onboarding moment (personalized welcome after first import)
- Micro-interactions (heart animation on favorite, subtle transitions)

### Recipe as Memory (DD131)
A recipe you've cooked 10 times isn't just instructions — it's autobiography. The cook log captures your cooking journey implicitly. The data has narrative quality (seasonality, skill progression, comfort vs exploration). Year-in-review surfaces this. Recipe annotations capture your personal version.

### The Kitchen Counter Problem (DD126)
Physical cooking UX: hands are wet/dirty, phone is propped on counter, attention is split. CookMode addresses this (large text, step-by-step, wake lock). Voice navigation isn't ready (Web Speech API unreliable in kitchen noise). Timer alerts (audio + vibration + notification) are the critical feedback channel.

---

## Architecture & Technical Notes

### Authentik SSO (DD27)
Zero-library SSO implementation. Caddy forward auth sends `X-Authentik-Username`/`X-Authentik-Email` headers. PHP checks headers, creates/links user automatically. Elegant and dependency-free.

### Frontend Performance (DD22)
85KB gzip bundle, 4 dependencies. Don't add code splitting — the app is small enough that the overhead isn't worth it. Lazy load images (already done). The bottleneck is API latency, not bundle size.

### Test Coverage (DD36)
54 tests, 101 assertions (security-focused: auth, CSRF, rate limiting, demo guard). 42 API endpoints catalogued. No unit tests for business logic (parsing, conversion). Consider adding tests for UnitConverter and IngredientParser.

### Project Archaeology (DD37)
12,587 lines across PHP + React + CSS. 30 commits over 5 days initial build. ~300 lines per feature average. Clean commit history with clear feature boundaries.

### Image Pipeline (DD147)
GD library, double validation (finfo + getimagesize), JPEG output at 85%/80% quality, per-recipe directories, Apache direct serving. Solid. Minor gap: `processFromUrl()` doesn't check downloaded file size (direct uploads have 10MB check, URL downloads don't).

### Meal Planning (DD148)
Better than initially assessed (★★★★ not ★★★). Automatic plan creation via `ON DUPLICATE KEY UPDATE`, servings override with ingredient scaling, direct grocery list generation, homepage "Tonight's Plan" widget. Gap: grocery generation doesn't consolidate (DD146 bug, tracked in roadmap).

### Empty States (DD144)
Every page has a well-designed empty state. Homepage welcome screen has 3-card CTA grid (Import URL / Paste or Type / Bulk Import). Consistent visual language: centered layout, warm-gray icons, positive framing, clear next actions. No onboarding wizard — contextual guidance instead. The app makes a good first impression; the problem is nobody can find the front door.

---

## Deferred Concepts

### Seasonal Cooking (DD128)
Tag recipes as seasonal, show "In Season Now" section. **Concluded: don't build.** Seasonal data is region-specific, maintenance-heavy, and the value is low. The feature you don't build is the right choice.

### Pantry-Based Recipe Search
"What Can I Make?" feature matching recipes against user's pantry inventory. No competitor has this in self-hosted. 6.5hr build. Deferred until core features are solid — pantry tracking is a significant commitment (ingredient normalization, expiration tracking, quantity management).

### Voice Navigation for CookMode
Web Speech API for hands-free step navigation ("next step", "go back"). Tested: unreliable in kitchen environments (fan noise, running water, distance from mic). Revisit when browser speech recognition improves.

### AI Recipe Parsing
Adding an AI tier to the scraper for non-URL sources (screenshots, handwritten cards, video). Requires external API key (OpenAI/Anthropic), adds cost and privacy concerns. Not aligned with Crumble's zero-dependency philosophy. Mealie and Norish do this; let them.

### Multi-User Grocery Collaboration
Real-time shared grocery lists with WebSocket sync. Engineering cost (conflict resolution, permission models, connection management) is disproportionate for a LAMP-stack app. Workaround: shared accounts or "copy as text" to collaborative apps.

### Ingredient-to-Step Matching
Tandoor highlights which ingredients are used in which step. Achievable with fuzzy string matching (~5 lines per step). Low priority but clever. Would enhance CookMode's ingredient panel.

---

## Corrections Log

These items were proposed as features but turned out to already exist or be unnecessary:

| DD | What I Proposed | What Was Already There |
|----|----------------|----------------------|
| DD129 | Timer alerts are silent | Web Audio beeps + Notifications + visual pulse already existed |
| DD134 | Build "cook again" recommendations | Forgotten Favorites + Uncooked Recipes already on HomePage |
| DD136 | Build algorithmic recommendations | Same as above — already built |
| DD137 | Redesign print stylesheet | Print CSS was already a recipe card layout with two-column, serif, step circles |
| DD148 | Meal planning is ★★★ | Actually ★★★★ — servings scaling, auto-creation, grocery pipeline |

**Lesson:** Always verify the current state before proposing changes. Run the feature, don't just read the code.

---

## Meta-Observations

### On Analysis vs. Shipping (DD138)
148 deep dives, 14,500 lines. 3 things actually shipped (search, unit conversion, mobile grid). The most valuable work was a 15-line SQL change (search fix). Analysis has diminishing returns. Future work should be code, not essays.

### Data Over Theory (DD135)
The search gap was discovered by running actual SQL: `SELECT COUNT(*) FROM recipes WHERE title LIKE '%onion%'` → 3 results. `SELECT COUNT(*) FROM ingredients WHERE name LIKE '%onion%'` → 43 results. 93% invisible. One query was more valuable than 10 pages of analysis.

### The Boring App Wins
Apps that succeed long-term are boring in the right way. They nail the core loop (find recipe → cook → log → repeat), don't chase trends, and respect the user's existing tools. Crumble's restraint is its best feature.
