# Pricing & Marketing Strategy Report
## Cookslate
Generated: 2026-04-11

---

## Executive Summary

Cookslate is a self-hosted recipe management app with an open-core model: MIT-licensed free tier with a BSL-licensed Pro tier at $9.99 one-time. It occupies a unique position as the **only self-hosted recipe app with a paid tier** — competitors (Mealie, Tandoor, KitchenOwl) are entirely free, while paid apps (Paprika, Mela, Plan to Eat) are cloud-only. The current $9.99 one-time price is **too low** — it undervalues the product, generates minimal revenue per customer, and sends a "toy" signal in a market where comparable apps charge $30-65 for lifetime access or $50/year for subscriptions. **Recommended action: raise Pro to $29.99 one-time (or $24.99/year), add a Household tier at $49.99, and position against the $50-65/year subscription apps as the "buy once, own forever" alternative.**

---

## Product Profile

| Field | Value |
|---|---|
| Product Name | Cookslate |
| One-sentence description | A self-hosted recipe manager that remembers how you cook — not just what you cook |
| Primary language / stack | PHP 8.1 (custom microframework) + React 18 + MySQL 8 |
| Product category | Web app (self-hosted) |
| Deployment model | Self-hosted (Docker or manual PHP hosting) |
| Open source? | MIT (free tier) / BSL 1.1 (Pro tier, converts to MIT 2029) |
| Maturity stage | Beta / early v1 (5 weeks, 121 commits, 13 migrations) |
| Target user (primary) | Home cooks who want to own their recipe data |
| Target user (secondary) | Privacy-conscious families, self-hosting enthusiasts |
| Core problem solved | Consolidate scattered recipes into one searchable, cookable system |
| Top 3 features | Cook Mode with timers, grocery lists with pantry tracking, recipe import from any URL |
| Integration ecosystem | USDA nutrition, OpenFoodFacts, MealDB, Mealie/Paprika import, Cooklang, Authentik SSO |
| Existing monetization | Open-core: Free self-hosted + $9.99 one-time Pro via Stripe |
| Docs quality | README + landing page + demo site |
| Codebase size | ~23K LOC (66 PHP files, 82 JS/JSX files) |
| Pro tier size | ~1,535 LOC (6.7% of total) |

---

## Brand Name Clearance

| Check | Findings | Risk Level |
|---|---|---|
| .app domain | Owned by you (cookslate.app) | LOW |
| .com domain | Registered by Gname.com (Singapore), expires 2026-10-20, likely squatter | MEDIUM |
| Other TLDs | Not checked (GoDaddy API limits) | UNKNOWN |
| Apple App Store | No "Cookslate" results | LOW |
| Google Play Store | No "Cookslate" results | LOW |
| USPTO trademarks | No results found | LOW |
| Web presence | No competing products named "Cookslate" | LOW |
| Sound-alikes | "Cooklist" (recipe app) — different enough | LOW |
| Look-alikes | None identified | LOW |

**Overall: GREEN** — Name is clear. The .com being squatted is worth monitoring (expires Oct 2026) but .app is your primary domain and sufficient for the market.

---

## Customer Segments

### Persona 1: "The Organized Home Cook"
- **Job to be done:** Consolidate 100+ recipes from bookmarks, screenshots, and cookbooks into one searchable place
- **Time saved:** 10-15 min/day searching for recipes, 20 min/week on grocery lists
- **Budget authority:** Personal card
- **Price sensitivity:** Medium — will pay for quality but compares to free alternatives
- **WTP:** $20-40 one-time, or $3-5/month
- **Perceived vs. objective value:** Higher — emotional attachment to recipe collection, "this is MY cookbook"

### Persona 2: "The Self-Hosting Enthusiast"
- **Job to be done:** Replace cloud recipe apps with something they control on their own server
- **Time saved:** Not the primary motivation — data ownership is
- **Budget authority:** Personal card
- **Price sensitivity:** Low for good software, high for anything that feels like a "tax" on open source
- **WTP:** $25-50 one-time (hostile to subscriptions)
- **Perceived vs. objective value:** Higher — values sovereignty, will pay a premium for "no cloud dependency"

### Persona 3: "The Family Kitchen Manager"
- **Job to be done:** Plan weekly meals, generate grocery lists, share recipes with spouse/kids
- **Time saved:** 1-2 hours/week on meal planning + grocery runs
- **Budget authority:** Joint household budget
- **Price sensitivity:** Medium — compares to Plan to Eat ($49/yr) and Samsung Food ($60/yr)
- **WTP:** $30-60 one-time, or $40-50/year
- **Perceived vs. objective value:** Higher — directly saves time and money on groceries

### Persona 4: "The Privacy-Conscious Cook"
- **Job to be done:** Cook without a cloud service tracking their dietary habits
- **Time saved:** Not the driver — privacy is
- **Budget authority:** Personal card
- **Price sensitivity:** Low — will pay for privacy
- **WTP:** $30-50 one-time
- **Perceived vs. objective value:** Higher — privacy has intangible value they'll pay for

### Persona 5: "The Casual Recipe Saver"
- **Job to be done:** Save recipes they find online, maybe cook from them occasionally
- **Time saved:** Minimal — convenience, not efficiency
- **Budget authority:** Personal card
- **Price sensitivity:** Very high — why pay when bookmarks exist?
- **WTP:** $0-10
- **Perceived vs. objective value:** Lower — doesn't recognize the full value of the tool

---

## Market Landscape

### Competitor Table

| Competitor | Price Model | Entry Price | Top Tier | Platform | Differentiator |
|---|---|---|---|---|---|
| **Mealie** | Free OSS | $0 | $0 | Self-hosted (Docker) | Vue, REST API, meal planning |
| **Tandoor** | Free OSS + hosted | $0 | Hosted (Germany only) | Self-hosted (Docker) | Powerful search, Kubernetes |
| **KitchenOwl** | Free OSS | $0 | $0 | Self-hosted, iOS, Android | Flutter native apps, expense tracking |
| **Paprika 3** | One-time/platform | $4.99 (mobile) | $29.99 (desktop) | iOS, Android, Mac, Win | Cloud sync, no subscription |
| **Mela** | One-time/platform | $4.99 (iOS) | $9.99 (Mac) | iOS, macOS | Apple-native design |
| **Plan to Eat** | Subscription | $5.95/mo | $49/yr | Web, iOS, Android | Meal planning focus |
| **Samsung Food** | Freemium sub | $0 | $59.99/yr | Web, iOS, Android | Samsung ecosystem, AI |
| **Copy Me That** | Freemium | $0 | $65 lifetime | Web, iOS, Android | Browser extension |
| **Recipe Keeper** | Freemium + one-time | $0 | $29.99 (Mac) | Cross-platform | True cross-platform |
| **Forkee** | Free (for now) | $0 | $0 | Web, iOS, Android | AI import, households |
| **Cooklist** | Freemium sub | $0 | $47.99/yr | iOS, Android | Barcode scanning, pantry |
| **Preplo** | Subscription | Free tier | $49.99/yr or $129.99 lifetime | Web, iOS, Android | Video-to-recipe |

**Price floor:** $0 (Mealie, Tandoor, KitchenOwl — all self-hosted, all free)
**Price ceiling:** $130 lifetime / $60/year (Preplo, Samsung Food)
**Market standard for one-time:** $25-65 (Paprika desktop, Copy Me That lifetime, Recipe Keeper)
**Market standard for subscriptions:** $40-60/year

### Key Insight
Cookslate's $9.99 one-time price is below every paid competitor except mobile-only Paprika ($4.99) and Mela ($4.99). It's priced like a mobile app but delivers a full web platform.

---

## Demand Curve Analysis

| Price Point | Likely Buyers | Est. Monthly Customers | Est. Monthly Revenue |
|---|---|---|---|
| Free | All personas | N/A (acquisition funnel) | $0 |
| $9.99 (current) | Personas 1-5 | 30-50 | $300-500 |
| $19.99 | Personas 1-4 | 25-40 | $500-800 |
| $29.99 | Personas 1-4 | 20-35 | $600-1,050 |
| $39.99 | Personas 2-4 | 15-25 | $600-1,000 |
| $49.99 | Personas 2-3 | 8-15 | $400-750 |
| $69.99 | Persona 2 only | 3-5 | $210-350 |

**Revenue-maximizing band: $24.99-$34.99 one-time.** This captures personas 1-4 while filtering out persona 5 (casual savers who will never convert anyway).

---

## Pricing Model Recommendation

### Primary Model: Open-Core with One-Time Purchase (current model, adjusted)

**Rationale:** The self-hosting audience is subscription-hostile. One-time pricing aligns with the "own your data, own your software" ethos. The open-core model (MIT free + BSL pro) is the right structure — it's the only such offering in the self-hosted recipe space. The one-time price should be raised to capture the actual value being delivered.

**Model scores:**

| Model | Fit | Notes |
|---|---|---|
| One-time perpetual | **HIGH** | Matches audience values, no churn risk, simple |
| Subscription | LOW | Self-hosting audience hostile to recurring fees |
| Freemium (current) | **HIGH** | Free tier drives adoption, Pro converts power users |
| Usage-based | LOW | No natural consumption unit |
| Open core (current) | **HIGH** | Perfect fit — only player doing this in the space |
| Consulting/services | LOW | Consumer product, not enterprise |

### Tier Structure

| Tier | Target Persona | Key Differentiators | Price |
|---|---|---|---|
| **Free** | Everyone | Recipe import, search, Cook Mode, grocery lists, pantry tracking, shoppable quantities, ingredient database, discover, dark mode | $0 |
| **Pro** | Home Cook, Self-Hoster | Meal planning, cook stats, annotations, data export, PWA offline | **$29.99 one-time** |
| **Household** | Family Kitchen Manager | Everything in Pro + multi-user (up to 5 accounts) | **$49.99 one-time** |

**Why this structure:**
- **Free tier is generous** — intentionally. It's the demo. Every free user running Cookslate is a billboard. The grocery list + pantry features alone compete with paid apps.
- **Pro at $29.99** — matches Paprika desktop ($29.99) and sits under the Copy Me That lifetime ($65). Positions as "same price as Paprika but you own the server."
- **Household at $49.99** — captures the Family Kitchen Manager who would otherwise pay $49/year for Plan to Eat. One-time vs. annual is a massive value signal.
- **No Enterprise tier** — this is a consumer product. Don't over-engineer the pricing.

### Bundling Opportunities

- **Import Migration Pack** (free, marketing tool): "Moving from Paprika? Mealie? One-click import." — this is already built, just needs prominent positioning.
- **Recipe Starter Packs** (potential): Curated recipe collections (e.g., "30 Weeknight Dinners") pre-loaded for new installs — add perceived value without engineering cost.

---

## Switching Costs & Positioning

### Current Tool → Cookslate

| What they use today | Economic switching cost | Psychological switching cost | Mitigation |
|---|---|---|---|
| Bookmarks / screenshots | Low (just import URLs) | Low (no attachment to format) | Auto-import messaging |
| Paprika | Medium (export/import) | Medium (loyal userbase) | Paprika import already built — advertise it |
| Mealie / Tandoor | Medium (Docker migration) | Low (same self-hosting ethos) | Mealie import built; "no Docker required" messaging |
| Plan to Eat | High (annual sub, recipes locked in) | High (calendar workflows) | "Stop paying $50/year" messaging |
| Samsung Food | Low (free tier, low lock-in) | Low | "Own your data" messaging |

### Key Differentiators to Promote

1. **"No Docker Required"** — Runs on any $5/month PHP hosting. This is a killer differentiator vs. Mealie/Tandoor/KitchenOwl which all require Docker.
2. **"Buy Once, Own Forever"** — vs. Plan to Eat ($49/yr), Samsung Food ($60/yr), Cooklist ($48/yr). Cookslate pays for itself in 7 months vs. Plan to Eat.
3. **"Your Server, Your Recipes"** — Privacy and data ownership messaging for personas 2 and 4.

---

## Launch Pricing Strategy

### Day 1 (Now)
- Raise Pro from $9.99 → **$29.99**
- Add Household tier at **$49.99**
- Offer a 60-day introductory price of $19.99 Pro / $34.99 Household ("Early Adopter" pricing)
- Update landing page, README, and Stripe products

### 6 Months
- Evaluate conversion rates. If Pro converts well at $29.99, hold.
- If Household outsells Pro 2:1, consider making multi-user the default Pro tier and raising to $39.99.
- Begin collecting testimonials and case studies for social proof.

### 12 Months
- Consider a maintenance subscription add-on ($9.99/year) for priority support and early access to new Pro features — but make it optional, not required.
- Evaluate whether a managed hosting tier (SaaS) is viable based on demand signals.
- Revisit whether native mobile apps are needed based on user feedback.

### Early Adopter Handling
- Existing $9.99 buyers: **honor forever**. They got a great deal. Happy early adopters are your best marketers.
- Don't grandfather them into Household automatically — let them upgrade at a loyalty discount ($19.99 upgrade) if they want multi-user.

---

## Marketing Strategy

### Perceived Value Enhancement

1. **Comparison pages**: Create "Cookslate vs. Plan to Eat" and "Cookslate vs. Mealie" pages. Show the 3-year cost: Plan to Eat = $147, Cookslate = $29.99 once.
2. **Demo site is your best asset**: demo.cookslate.app already exists and is fully functional. Every feature is tryable. Promote it aggressively.
3. **"Runs on a $5 server" messaging**: Most self-hosters already have a VPS. Cookslate adds to their stack at zero marginal infrastructure cost.
4. **Cook Mode video**: Record a 30-second screen recording of Cook Mode in action — timers, step-by-step, wake lock. This is the "wow" feature that screenshots can't convey.

### Reference Point Management

**Encourage comparisons to:**
- Plan to Eat ($49/year) — "one payment vs. forever payments"
- Samsung Food ($60/year) — "no corporation tracking what you eat"
- Paprika ($29.99) — "same price, but you control the server"

**Avoid comparisons to:**
- Mealie/Tandoor ($0) — they're free and open source; don't compete on price with free
- Instead, position against them on convenience: "No Docker, no YAML, no CLI. Just upload to your hosting."

### Channel Strategy

1. **GitHub** (primary): Already set up. Star count = social proof.
2. **r/selfhosted** (Reddit): The #1 acquisition channel for self-hosted apps. Post when you hit meaningful milestones.
3. **Awesome Self-Hosted list**: Get listed on awesome-selfhosted.net (already categorized).
4. **Hacker News**: "Show HN" post when you have a compelling story (e.g., "I built a recipe app that runs without Docker").
5. **YouTube cooking communities**: Partner with cooking YouTubers who also self-host.

### Key Messaging Pillars

1. **"Your recipes. Your server. One price."** — Ownership + value in six words.
2. **"Runs anywhere PHP runs. No Docker required."** — Technical differentiator that matters to the audience.
3. **"Cook Mode turns your phone into a sous chef."** — Emotional feature highlight that creates desire.

---

## Risk Flags

| Risk | Severity | Mitigation |
|---|---|---|
| **Free tier too generous** | MEDIUM | The free tier already has grocery lists, pantry, shoppable quantities, Cook Mode, ingredient database, and discover. Monitor Pro conversion rate — if < 3%, consider moving one feature (e.g., pantry tracking or shoppable quantities) to Pro. |
| **KitchenOwl has native mobile apps** | MEDIUM | KitchenOwl's Flutter apps give it a mobile advantage. PWA is acceptable for now but native apps may be needed within 12-18 months. |
| **Pro tier is thin (6.7% of LOC)** | HIGH | Only meal planning, stats, annotations, export, and multi-user. If users see the free tier as "enough," Pro won't convert. Consider adding 1-2 more Pro features (AI recipe suggestions, advanced nutrition tracking, recipe scaling for parties). |
| **BSL converts to MIT in 2029** | LOW | 3 years of exclusivity is sufficient. By 2029, Cookslate should have a strong brand moat regardless. |
| **No native mobile apps** | MEDIUM | Acceptable now, but every competitor launched in 2025-2026 has native apps. Monitor user requests. |
| **One developer** | HIGH | Bus factor of 1. If development stops, Pro buyers may feel burned. Mitigate by keeping the free tier excellent and being transparent about development cadence. |

---

## Pricing Checklist

| Question | Answer |
|---|---|
| Is the price anchored to value delivered, not cost incurred? | YES — $29.99 for a tool that replaces $50/yr subscriptions |
| Does the price signal quality? | YES — $29.99 says "real product" vs. $9.99 which says "side project" |
| Is the price under the relevant purchasing threshold? | YES — well under $50 personal card limit |
| Does the tier structure make comparison easy? | YES — Free / Pro / Household is simple |
| Is the free tier good enough to be useful? | YES — possibly too good (risk flag above) |
| Is the Pro tier compelling enough to convert? | NEEDS WORK — consider adding 1-2 more Pro features |
| Can buyers try before buying? | YES — demo.cookslate.app + free self-hosted tier |
| Is switching cost addressed? | YES — Paprika and Mealie importers built |
| Does the price compare favorably to reference points? | YES — cheaper than Plan to Eat, Paprika desktop, Copy Me That lifetime |

---

## Recommended Next Steps

1. **Raise Pro to $29.99 and add Household at $49.99** — Create new Stripe products, update landing page and README. Offer a 60-day early adopter price of $19.99/$34.99.

2. **Thicken the Pro tier** — Add 1-2 more Pro-exclusive features to justify the $29.99 price. Candidates: recipe scaling for party sizes, advanced nutrition reports, AI-powered recipe suggestions, or recipe collections/cookbooks.

3. **Create comparison landing pages** — "Cookslate vs. Plan to Eat" and "Cookslate vs. Mealie" pages that highlight the cost and convenience advantages.

4. **Post to r/selfhosted** — This is the single highest-ROI marketing action. Write a genuine "I built this" post with screenshots and a link to the demo.

5. **Monitor cookslate.com** — The domain expires Oct 2026. Set a reminder to attempt acquisition if it drops.
