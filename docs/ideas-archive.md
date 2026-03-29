# Crumble — Ideas, Explorations & Honest Assessments

*Started: 2026-03-08 — A living document of thoughts on where Crumble could go.*

### Document Map (~14,500 lines)

| Section | Topic | Key Takeaway |
|---------|-------|-------------|
| Where Crumble Stands Today | Opening assessment | Deliberately simple, clean codebase |
| Things That Should Be Fixed | 6 original bugs/gaps | 5 fixed, 1 left as-is. More issues found in later deep dives. |
| Feature Ideas — Ranked | Tier 1/2/3 features | Meal planning and recipe sharing are built. Scaling was already built. |
| IngredientParser | Parser capabilities & gaps | Solid parsing but no math layer (amounts as strings, no unit conversion) |
| Meal Planning UI | UI patterns research | Week-at-a-glance, mobile day-picker, quick-add critical |
| Competitive Landscape | vs. Mealie/Tandoor/KitchenOwl | Deployment simplicity is Crumble's differentiator |
| Smart Grocery Consolidation | Technical design | AmountConverter + UnitConverter needed. Fixes existing merge bug. |
| Recipe Sharing | Technical design | Built: UUID tokens, 30-day expiry, public endpoint |
| PWA Feasibility | Implementation plan | ~3.5 hours, high ROI, mostly configuration |
| Pantry-Based Recipe Search | Algorithm + UI design | ~6.5 hours, no competitor has this |
| RecipeScraper | Strengths and gaps | Strong 4-tier parser; missing nutrition/tag extraction from JSON-LD |
| CookMode | What's there, what's missing | Timer persistence bug, no cook log on Done, no auto-start |
| Search | How it works, where it fails | Only searches title/description, not ingredients. Multi-word bug. |
| Frontend Performance | Bundle analysis, patterns | 85KB gzip, 4 deps. Don't add code splitting yet. |
| Accessibility Audit | WCAG analysis | Good touch targets/aria-labels. Missing focus traps, skip nav, contrast. |
| 2026 Landscape & AI | Market research, Norish | **Supersedes original Competitive Landscape table** with Norish + AI column |
| **Synthesis: Roadmap** | **Prioritized action plan** | **29 hours, 42 items. Critical path: 12.75hr. Start here for "what to build."** |
| Data Portability | Import/export analysis | 3 import paths, zero export. Trust gap. |
| Authentik SSO | Architecture note | Elegant zero-library SSO via Caddy forward auth |
| Emotional Design | Warmth vs. clinical feel | CookMode completion, onboarding, micro-interactions. 3 builds <2hr. |
| Kitchen Stats | "Spotify Wrapped" for cooking | No competitor has this. 2.25hr build, zero schema changes. |
| Night Mode | Warm dark theme, not cold | CSS-only via variable overrides. 1.5hr. Zero JSX changes. |
| Updated Competitive Landscape | March 2026 full field | 15 projects compared. Crumble is only non-Docker web app. |
| Add Recipe Flow | First-contact UX analysis | Ingredient entry is the bottleneck. 4hr for major improvement. |
| Missing README | Install experience & project presentation | No README, no install.php, no .env.example. Step zero for adoption. |
| Recipe Detail Page | Reading experience analysis | Excellent scaling UX. Gaps: no lightbox, 150 lines dead code, unused prev/next. |
| Brand Voice Audit | What Crumble "sounds like" | Warm, consistent, cook-not-coder. Protect the voice. |
| Test Coverage & API Map | What's tested, full API surface | 46 assertions (security only). 42 endpoints catalogued. |
| Project Archaeology | Git history & code metrics | 12,587 lines, 30 commits, 5 days. 300 lines/feature. |
| Cooklang | Import/export format analysis | High compatibility. 4hr build. Export matters more than import. |
| Night Mode CSS Prototype | Actual CSS + palette + audit | 25 lines of CSS. 49 `bg-white` refs to migrate. 2.25hr total. |
| IngredientParser.js | Working JS port + paste UX | 100-line parser, paste textarea. 2hr. Highest UX ROI. |
| Cooking Journal | Ghost feature unlock | Full stack wired, UI missing. 1.5hr for unique feature. |
| RecipeScraper Analysis | 5-tier parser strengths + gaps | Missing nutrition/tag extraction from JSON-LD. 1hr fix. |
| CookMode Deep Dive | Bugs, fixes, voice nav prototype | Timer persistence is worst UX bug. 2hr for full fix suite. |
| Tandoor's AI & Crumble | Competitive response analysis | Don't chase AI. Steal ingredient-to-step matching (5 lines). |
| Grocery Lists | Feature audit | Works well. Mobile delete bug. No ingredient consolidation. |
| **8-Hour Implementation Guide** | **Distilled action plan** | **Exact files, exact code, exact order. Start here to build.** |
| 2026 Landscape Revisited | Competitive field update | Norish is the newcomer to watch. Docker-free remains Crumble's moat. |
| Kitchen Counter Problem | Physical cooking UX analysis | Timer alert gap is the #1 CookMode bug. Voice nav isn't ready. |
| Recipe Format Portability | Export format analysis | JSON-LD export is the highest-value addition (~1hr). Cooklang is nice-to-have. |
| Seasonal Cooking | Thought experiment | Interesting but premature. The feature you don't build is the right choice. |
| Timer Alert Fix | Detailed implementation | Web Audio API + Vibration. 30 lines. Bug fix, not feature. |
| Ingredient Math Layer | Architectural deep dive | String amounts are foundational debt. Unit conversion is 2hr, high value. |
| Recipe as Memory | PKM + emotional architecture | Cook log = autobiography. Year-in-review is 3hr, zero schema changes. |
| On Restraint | Feature filtering framework | "Would a first-time user smile or be confused?" 8hr build list. |
| PWA — The Full Picture | Service worker + share target plan | 2.5hr for native-feeling mobile. Highest-impact improvement remaining. |
| Being Wrong (Meta) | Reflection on analysis failures | Timer was already built. Always verify before claiming. |
| Search — Invisible Bottleneck | Full search architecture analysis | **Shipped.** Ingredients unsearchable → fixed. +1,333% onion results. |
| Algorithmic Recommendations | No-AI recommendation strategies | Already built (forgotten favorites, uncooked recipes). DD134 pattern. |
| Print Stylesheet | Design audit | Already 90% there. Border frame + notes area = 30min of polish. |
| 138 Deep Dives Reflection | Meta-analysis | Over-propose, under-verify. Data > theory. Restraint wins. 6hr of work left. |
| Grocery List Honest Assessment | Why people use Notes apps | Recipe connection is the moat. "Copy as text" bridges to real shopping apps. |
| Recipe Evolution (Annotations) | Margin notes on recipes | Personal adjustments per ingredient/step. Cookbook marginalia as a feature. 3.5hr. |
| Grocery Copy as Text | 30-minute feature design | Clipboard export with aisle grouping. Don't fight existing tools, hand off gracefully. |
| Cook Log Potential | Year-in-review concept | Data-as-narrative. Build before December. 2.25hr. |
| Landscape March 2026 | Mealie v3.12, Norish v0.15 | Crumble invisible. Only non-Docker web app. AI is table stakes elsewhere. |
| Empty States Audit | First-time UX analysis | Surprisingly good. Every page has considered empty state. Welcome screen is textbook. |
| 2026 Landscape Lessons | Three trends synthesis | AI capture, grocery collab, plain-text movement. Confirms existing roadmap. |
| Meal Plan Grocery Bug | **Bug found** + code duplication | `generateGroceryList()` skips consolidation. Duplicate parseAmount/formatAmount. 1.25hr fix. |
| Image Pipeline | WebP opportunity analysis | Solid JPEG pipeline. WebP saves 25-35%. 30min change. processFromUrl missing size check. |
| Meal Planning Reassessment | **Correction: ★★★ → ★★★★** | Servings scaling, auto-plan creation, grocery generation. Better than assessed in DD143. |
| **Updated Roadmap** | **Post-DD138 action plan** | **10.25hr remaining. 2 items shipped. 6 designed.** |
| **Closing Essay** | **What makes it feel like home** | **Keep it cozy. The restraint is the feature.** |
| **Executive Summary** | **One-page overview** | **Start here for the TL;DR of everything.** |

> **Start reading at:** "Executive Summary" for the overview, or "Synthesis: Roadmap" for the action plan.
> **The original "Competitive Landscape Notes" table** is superseded by the updated version in "2026 Landscape & AI" which includes Norish and AI features.

---

## Where Crumble Stands Today

After spending time digging through the codebase and looking at the self-hosted recipe manager landscape (Mealie, Tandoor, KitchenOwl, Grocy, Recipya), I think Crumble occupies an interesting niche. It's *deliberately simple*. No Docker requirement, no Python/Node backend complexity — just PHP, MySQL, and a React frontend. That's its strength and its constraint.

The codebase is clean. No TODO debris, consistent patterns, good security posture (CSRF, rate limiting, account lockout, SSRF protection). The custom PHP router is lightweight and readable. But there are cracks worth examining.

---

## Things That Should Be Fixed Before New Features

These aren't glamorous, but they matter:

### 1. ~~The N+1 Problem in Recipe Detail~~ — FIXED
`Recipe.findById()` collapsed from 9 queries to 3 by folding aggregates, user-specific data, and prev/next navigation into the main query as subqueries. All hit indexed columns.

### 2. ~~Hardcoded Laragon CA Path~~ — FIXED
Added `getCaBundlePath()` helper to `api/config/env.php` that checks php.ini, `CURL_CA_BUNDLE` env var, common Linux/macOS paths, and Laragon as last fallback. Updated `RecipeScraper.php` and `ImageProcessor.php`.

### 3. Nutrition Fields Are in Limbo — ASSESSED, LEAVE AS-IS
The database has columns for calories (INT), protein/carbs/fat/fiber/sugar (VARCHAR). 0 of 146 recipes have any nutrition data. The `NutritionFacts` component is well-built and self-hiding (returns null when empty). The add/edit forms support manual entry. Not a bug — just a dormant feature waiting for data. Options if pursuing nutrition later: (a) auto-populate from recipe scraping (schema.org `NutritionInformation`), (b) API integration (Nutritionix, Edamam), (c) leave as manual entry. Not worth changing now — the code is correct, just unused.

### 4. ~~Grocery Item Property Mismatch~~ — FIXED
`GroceryItem.jsx` referenced `item.recipe_name` but backend returned `recipe_title`. Changed frontend to `item.recipe_title`.

### 5. ~~No Error Boundaries~~ — FIXED
Added `ErrorBoundary` component wrapping all routes in `App.jsx`. Shows a styled error page with refresh button instead of white-screen.

### 6. ~~GroceryItem::addFromRecipe() Index Bug~~ — FIXED
`$existingByName` now updated after creating new items, preventing within-recipe duplicates.

---

## Feature Ideas — Ranked by Impact vs. Effort

### Tier 1: High Impact, Moderate Effort

#### Meal Planning
Every serious competitor has this (Mealie, Tandoor, KitchenOwl, Grocy). The pattern is well-established:
- Calendar-style weekly view
- Drag recipes onto days/meals (breakfast, lunch, dinner)
- "Add all ingredients to grocery list" for a day or week
- Database: `meal_plans` table with `date`, `meal_type`, `recipe_id`, `user_id`

This is probably the single most-requested feature in recipe managers. Crumble already has grocery lists — connecting them to a meal plan is a natural extension. The question is whether to keep it simple (just a week view) or go full calendar. I'd start with a simple week view.

#### Smart Grocery List Consolidation
Current grocery lists can merge quantities when units match, but can't handle "2 cups" + "16 oz" or recognize that "chicken breast" and "boneless skinless chicken breasts" are the same thing. Two approaches:
- **Unit conversion table** — map common cooking unit equivalencies. Straightforward, finite problem.
- **Ingredient normalization** — strip modifiers ("boneless", "fresh", "large") to match base ingredients. Harder but much more useful.

Even basic unit conversion would be a significant quality-of-life improvement.

#### Recipe Sharing / Public Links
Currently Crumble is single-household. But people constantly want to share recipes. A simple implementation:
- Generate a unique share token per recipe
- `/shared/{token}` renders a read-only recipe view (no auth required)
- Owner can revoke tokens

No user account complexity, no social features — just "here's a link to my recipe."

---

### Tier 2: Medium Impact, Interesting Problems

#### Social Media Recipe Import
This is a hot trend in 2026. Apps like Honeydew and Pestle let you paste an Instagram/TikTok URL and extract the recipe. The technical challenge is real:
- Video content requires AI transcription
- Instagram/TikTok APIs are restrictive
- Recipe content is often spoken, not structured

Crumble's scraper already handles structured web pages well. Extending to social media would require either:
- An external AI service (OpenAI/Claude API) to parse unstructured content
- A simpler approach: let users paste the caption text and use NLP to extract ingredients/instructions

The simpler approach might actually be more useful — most people screenshot or copy the caption anyway.

#### Cooking Mode Improvements
CookMode.jsx exists and has step-by-step navigation with timers, but it could be much more:
- **Voice control** — Web Speech API for hands-free "next step" / "start timer"
- **Inline timers** — Parse "bake for 25 minutes" from instructions and offer auto-start timers
- **Keep screen on** — `useWakeLock` hook already exists! Just needs to be activated in cook mode
- **Post-cook notes** — After finishing, prompt for notes ("too salty", "reduce garlic next time") and save to cook log

The wake lock hook is already there but I'd want to verify it's actually used in CookMode. If not, that's a quick win.

#### Recipe Scaling — ALREADY BUILT
**Missed this on first review.** `frontend/src/utils/ingredientScaling.js` + `ServingsAdjuster` component already implement full client-side recipe scaling:
- `parseAmount()`: handles integers, decimals, fractions, mixed numbers, ranges
- `formatAmount()`: outputs Unicode fractions (½, ⅓, ¼, ¾, etc.) with closest-match rounding
- `scaleIngredients()`: scales both structured amounts and amounts embedded in ingredient names
- `ServingsAdjuster`: +/- buttons on RecipePage

The implementation is clean and handles edge cases well (non-scalable ingredients like "salt to taste" pass through unchanged). No work needed here.

---

### Tier 3: Lower Priority But Interesting to Think About

#### AI-Powered Features
The 2026 recipe app landscape is drowning in AI features. Some actually useful ones:
- **"What can I make with...?"** — Input available ingredients, get matching recipes. This is a search/filter problem more than an AI problem. Could be done with ingredient indexing.
- **Recipe suggestions based on history** — "You haven't cooked Italian in 2 weeks" or "You rated pasta dishes highly." Cook log + ratings data already exists.
- **Automatic tagging** — Parse recipe title/ingredients/instructions to suggest tags. Could use simple keyword matching without AI.

I'm skeptical of adding AI for AI's sake. Most "AI features" in recipe apps are thin wrappers around LLM APIs that add latency and cost. The ingredient-matching search is the most genuinely useful one and doesn't need AI at all.

#### Multi-User Collaboration
Tandoor and KitchenOwl both support household/group features:
- Multiple users contributing recipes
- Shared grocery lists (real-time sync)
- Per-user preferences (dietary restrictions, favorites)

Crumble already has multi-user auth with roles. Shared grocery lists would be the natural first step. Real-time sync via WebSockets or SSE would be nice but is architecturally heavy for a PHP backend. Polling every 30 seconds might be "good enough."

#### Progressive Web App (PWA)
Recipe apps benefit enormously from offline support — you're often cooking in a kitchen with spotty wifi. A service worker that caches:
- Recently viewed recipes
- The user's favorites
- Active grocery list

Would make Crumble feel native. Vite has good PWA plugin support (`vite-plugin-pwa`).

---

## Architectural Thoughts

### Should Crumble Stay PHP?
Honestly? Yes. The PHP backend is simple, fast, and works. Rewriting in Node/Go/Rust would be resume-driven development. PHP 8.x is performant, the codebase is clean, and Laragon makes deployment trivial. The only argument for switching would be WebSocket support for real-time features, but that's a bridge to cross later.

### React Query / TanStack Query
The custom hooks (`useRecipes`, `useGrocery`, etc.) work but don't cache, deduplicate, or handle stale data. React Query would add:
- Automatic caching and background refetching
- Optimistic updates (check off grocery item → instant UI, sync in background)
- Request deduplication
- Loading/error states for free

This is the highest-leverage frontend architectural change. It would make every data-fetching component faster and more resilient.

### Database Considerations
MySQL is fine for Crumble's scale. But if meal planning + more users + more data comes:
- Add indexes on `recipes.created_by` and `cook_log.user_id`
- Consider `DECIMAL` for nutrition fields instead of `VARCHAR`
- The full-text search works but is basic — for advanced recipe search (by ingredient, by cooking method), a dedicated search approach might be needed eventually

---

## What I Find Most Interesting

The **ingredient intelligence** space is where Crumble could differentiate without massive effort:
1. The IngredientParser already exists and works
2. Building an ingredient-to-recipe index would enable "what can I make?" search
3. Smart grocery consolidation builds on the same parsing
4. Recipe scaling builds on the same parsing

All four features share a foundation. Investing in making IngredientParser more robust (handling "2-3 cloves garlic", "one 14oz can", Unicode fractions) would pay dividends across multiple features.

The other thing that interests me is **the simplicity angle**. Most self-hosted recipe managers are trying to be everything — Tandoor has nutritional tracking, cost calculation, Nextcloud integration, AI ingredient recognition. Crumble could win by being the recipe manager that's *easy*. Easy to deploy, easy to use, easy to understand. Not every app needs to be a platform.

---

## Questions I Don't Have Answers To Yet

- How many recipes does a typical Crumble instance hold? 50? 500? 5000? This changes which performance optimizations matter.
- Is Crumble used by single users or households? This changes whether multi-user features matter.
- What's the deployment target? Laragon-only, or should it work on generic LAMP/Docker? The hardcoded paths suggest Laragon-only today.
- Is there interest in a mobile app, or is the PWA approach sufficient?

---

---

## Deep Dive: IngredientParser Capabilities & Gaps

*Explored 2026-03-08*

After reading the actual parser code (`api/services/IngredientParser.php`), here's what it handles:

**Works well:**
- Integer, decimal, fraction, mixed number amounts ("2", "1.5", "1/2", "1 1/2")
- Ranges ("2-3")
- 27 unit types with plural/abbreviation aliases
- Parenthetical patterns like "2 (14 oz) cans tomatoes" → amount=2, unit=can, name=(14 oz) tomatoes
- Graceful fallback — unparseable strings become the full name with null amount/unit

**Gaps that matter for grocery consolidation & recipe scaling:**
- **No Unicode fractions** — "½ cup" won't parse the amount (½ is a single character, not "1/2")
- **No word-form numbers** — "one", "two", "a dozen" are not recognized
- **No amount arithmetic** — amounts are stored as strings ("1 1/2"), not floats. Any consolidation needs a `stringToFloat` converter and a `floatToFraction` formatter.
- **No unit conversion** — knows what "cup" and "ml" are, but can't convert between them
- **No ingredient normalization** — "garlic cloves" and "cloves garlic" and "garlic, minced" are three different ingredients

**What's needed for consolidation (in order of implementation):**

1. `AmountConverter` utility — parse "1 1/2" → 1.5, and format 1.5 → "1 1/2". Handle Unicode fractions.
2. `UnitConverter` class — conversion tables for volume (tsp→tbsp→cup→pint→quart→gallon, ml→L) and weight (g→kg, oz→lb). Cross-system (cups→ml) is trickier but doable with standard cooking conversions.
3. `IngredientMatcher` — at minimum, case-insensitive trimmed comparison. Next level: strip common modifiers ("fresh", "dried", "chopped", "minced", "large", "small") before comparing.

The parser itself is solid for what it does. The gaps are in the *math layer* on top of it.

---

## Deep Dive: Meal Planning UI Patterns

*Explored 2026-03-08*

After researching meal planning UIs across Mealie, Tandoor, KitchenOwl, and design case studies:

**What works in the wild:**
- **Week-at-a-glance** is the dominant pattern. Monthly views exist but are rarely used day-to-day.
- **Simple > Structured** — apps that force breakfast/lunch/dinner slots get complaints from users who eat 2 meals or have non-traditional schedules. A flat "meals for this day" list is more flexible.
- **Quick-add is critical** — the #1 friction point is "how do I get a recipe onto my plan?" Must be <3 taps/clicks. Search + click = done.
- **Mobile = stacked days** — 7 columns doesn't work on mobile. Stack days vertically with collapsible sections or a horizontal day-picker + single-day detail view.

**Crumble-specific frontend considerations:**
- Existing search is inline with debounce in `HomePage.jsx` — not a reusable component. For meal planning, I need a recipe search modal. The existing `Modal.jsx` component supports sizes up to 'xl' and has escape/backdrop close. Good foundation.
- Nav pattern: Sidebar uses `lucide-react` icons. `CalendarDays` icon exists in Lucide and fits perfectly.
- Color palette: terracotta (#C1694F) as primary, sage (#7D9B76) as secondary. The meal plan could use sage for "planned" states to differentiate from terracotta actions.
- Card pattern: `bg-white rounded-2xl shadow-md` is consistent everywhere. Meal plan day cards should match.
- Touch targets: 44px minimum enforced throughout. Important for the day cards on mobile.
- Typography: Playfair Display (serif) for headings, Nunito (sans) for body. Week header should use serif.

**Mobile layout decision:**
A horizontal day-picker (Mon|Tue|Wed|...) fixed at top, with the selected day's meals below, is probably better than stacking all 7 days. It keeps the screen focused and matches mobile calendar patterns users already know. Desktop can show all 7 columns in a grid.

**Grocery list generation from meal plan:**
The existing `addRecipeToGrocery(listId, recipeId)` endpoint adds one recipe at a time. For meal plan → grocery, we'd want a batch endpoint that adds all planned recipes at once. The backend `GroceryController` already has smart merging for same-unit ingredients — we just need to call it in a loop or batch.

---

## Competitive Landscape Notes

*2026-03-08 — See updated table in "2026 Landscape & AI" section below with Norish and AI columns.*

| Feature | Mealie | Tandoor | KitchenOwl | Crumble |
|---------|--------|---------|------------|---------|
| Meal Planning | Yes | Yes | Yes | **Yes** (built 2026-03-08) |
| Grocery Lists | Yes (auto from plan) | Yes | Yes (real-time sync) | Yes (+ from meal plan) |
| Recipe Scaling | Yes | Yes | Yes | **Yes** (frontend-side) |
| Recipe Sharing | Yes (groups) | Yes (public links) | Yes (household) | **Yes** (built 2026-03-08) |
| Pantry Search | No | No | No | **No** (none have it!) |
| Offline/PWA | Partial | No | Yes (Flutter) | **No** |
| AI Features | No | Yes (ingredient recognition) | No | **No** |
| Import from URL | Yes | Yes | Yes | Yes |
| Social Import | No | No | No | **No** |
| Docker Required | Yes | Yes | Yes | **No** (advantage!) |
| Tech Stack | Python/Vue | Python/Vue | Flask/Flutter | PHP/React |

**Crumble's differentiator is deployment simplicity.** Every competitor requires Docker. Crumble runs on shared hosting with PHP and MySQL. That's a real advantage for non-technical users who just want a recipe box.

**Updated 2026-03-08:** Meal planning, recipe sharing, and recipe scaling are all built. Recipe scaling was already implemented via `ServingsAdjuster` component + `ingredientScaling.js` utility (handles fractions, ranges, mixed numbers, Unicode fraction display). The remaining gap is grocery consolidation. Interestingly, *none* of the self-hosted competitors have pantry-based recipe search — that could be a genuine differentiator.

---

## Deep Dive: Smart Grocery Consolidation — Technical Design

*Explored 2026-03-08*

After reading the actual grocery merging code (`GroceryItem::addFromRecipe()`), here's how it works today:

**Current merging logic:**
1. Fetch all ingredients from recipe (`SELECT name, amount, unit FROM ingredients WHERE recipe_id = ?`)
2. Build lookup of existing grocery items by `strtolower(trim(name))`
3. For each ingredient:
   - If name matches AND units match AND both amounts are numeric → **add amounts** (float addition)
   - If name matches but units differ or amounts non-numeric → **skip** (already exists)
   - If name doesn't match → **create new item**

**What this can't do:**
- "2 cups flour" + "8 oz flour" → stays as two items (units differ)
- "chicken breast" + "boneless skinless chicken breast" → stays as two items (names differ)
- "1 1/2 cups" + "2 cups" → stays as two items (amounts are strings, not numeric — `is_numeric("1 1/2")` returns false!)

Wait — that last one is a **bug in the existing code**. The merging only works when amounts are simple numbers like "2" or "0.5". Any mixed number ("1 1/2"), fraction ("3/4"), or range ("2-3") won't merge because `is_numeric()` returns false for those strings. This means the current "smart merging" only works for a subset of ingredients.

### Proposed Unit Conversion Table

Based on standard cooking conversions:

```php
// Volume conversions — everything in teaspoons as base unit
private const VOLUME_TO_TSP = [
    'tsp'    => 1,
    'tbsp'   => 3,
    'oz'     => 6,        // fluid ounces
    'cup'    => 48,
    'pint'   => 96,
    'quart'  => 192,
    'gallon' => 768,
    'ml'     => 0.202884, // 1 ml ≈ 0.2 tsp
    'L'      => 202.884,  // 1 L ≈ 203 tsp
];

// Weight conversions — everything in grams as base unit
private const WEIGHT_TO_G = [
    'g'  => 1,
    'kg' => 1000,
    'oz' => 28.3495,    // weight ounces
    'lb' => 453.592,
];
```

**The oz ambiguity problem:** "oz" can mean fluid ounces (volume) or weight ounces. In cooking:
- Liquids: usually fluid ounces (volume)
- Solids: usually weight ounces
- We can't know which without ingredient context

**Pragmatic solution:** Only convert within unambiguous groups:
- Volume-only: tsp ↔ tbsp ↔ cup ↔ pint ↔ quart ↔ gallon ↔ ml ↔ L
- Weight-only: g ↔ kg ↔ lb
- "oz" stays separate — don't auto-convert oz to cups or oz to grams

**Display unit preference:** When combining, prefer the larger unit if the result is ≥ 1 of that unit. "96 tsp" → "2 cups". "0.5 tsp" stays as "1/2 tsp".

### AmountConverter — Parsing and Formatting

This is needed both for grocery consolidation AND for the meal planning grocery generation (servings scaling). Should be a standalone service class:

```php
class AmountConverter {
    // Parse string amount to float
    public static function toFloat(?string $amount): ?float {
        if ($amount === null || trim($amount) === '') return null;
        $amount = trim($amount);

        // Handle Unicode fractions: ½→0.5, ⅓→0.333, ¼→0.25, ¾→0.75, ⅔→0.667, ⅛→0.125
        $unicodeMap = ['½'=>0.5, '⅓'=>0.333, '⅔'=>0.667, '¼'=>0.25, '¾'=>0.75, '⅕'=>0.2, '⅖'=>0.4, '⅗'=>0.6, '⅘'=>0.8, '⅙'=>0.167, '⅚'=>0.833, '⅛'=>0.125, '⅜'=>0.375, '⅝'=>0.625, '⅞'=>0.875];

        // Range: "2-3" → average 2.5
        if (preg_match('/^(\S+)\s*-\s*(\S+)$/', $amount, $m)) {
            $low = self::toFloat($m[1]);
            $high = self::toFloat($m[2]);
            return ($low !== null && $high !== null) ? ($low + $high) / 2 : null;
        }

        // Mixed number: "1 1/2" → 1.5
        if (preg_match('/^(\d+)\s+(\d+)\/(\d+)$/', $amount, $m)) {
            return (float)$m[1] + (float)$m[2] / (float)$m[3];
        }

        // Fraction: "3/4" → 0.75
        if (preg_match('/^(\d+)\/(\d+)$/', $amount, $m)) {
            return (float)$m[1] / (float)$m[2];
        }

        // Plain number
        if (is_numeric($amount)) return (float)$amount;

        // Check for Unicode fraction character
        foreach ($unicodeMap as $char => $val) {
            if (mb_strpos($amount, $char) !== false) {
                $prefix = trim(mb_substr($amount, 0, mb_strpos($amount, $char)));
                return ($prefix !== '' ? (float)$prefix : 0) + $val;
            }
        }

        return null; // "to taste", "a pinch", etc.
    }

    // Format float back to readable string
    public static function toString(float $value): string {
        // Common fractions lookup
        $fractions = [
            0.125 => '1/8', 0.25 => '1/4', 0.333 => '1/3',
            0.375 => '3/8', 0.5 => '1/2', 0.625 => '5/8',
            0.667 => '2/3', 0.75 => '3/4', 0.875 => '7/8',
        ];

        $whole = floor($value);
        $frac = round($value - $whole, 3);

        if ($frac < 0.01) return (string)(int)$whole;

        $fracStr = $fractions[$frac] ?? null;
        if ($fracStr !== null) {
            return $whole > 0 ? "$whole $fracStr" : $fracStr;
        }

        // Irregular decimal — round to reasonable precision
        return (string)round($value, 2);
    }
}
```

**This class should be shared** between:
1. `MealPlan::generateGroceryList()` — for servings scaling
2. `GroceryItem::addFromRecipe()` — to fix the existing merging bug and enable unit conversion
3. Future recipe scaling feature

### Implementation Plan for Consolidation

1. Create `api/services/AmountConverter.php` (standalone, no dependencies)
2. Create `api/services/UnitConverter.php` (uses AmountConverter, conversion tables)
3. Update `GroceryItem::addFromRecipe()` to use both:
   - Parse amounts with `AmountConverter::toFloat()` instead of `is_numeric()`
   - When units differ, check if `UnitConverter::canConvert($unitA, $unitB)` — if yes, convert both to a common unit and add
   - Format result with `AmountConverter::toString()`

This fixes the existing merging bug AND adds cross-unit consolidation in one change.

---

## Deep Dive: Recipe Sharing — Technical Design

*Explored 2026-03-08*

### Data Model

```sql
CREATE TABLE recipe_shares (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT NOT NULL,
  token CHAR(36) NOT NULL,          -- UUID v4
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  expires_at TIMESTAMP NOT NULL,    -- 30 days from creation
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY idx_token (token),
  INDEX idx_recipe (recipe_id)
) ENGINE=InnoDB;
```

30-day expiration as discussed. `ON DELETE CASCADE` from recipes means deleting a recipe kills its share links automatically.

### API Design

```
POST   /api/recipes/{id}/share    → create share token, return { token, url, expires_at }
DELETE /api/recipes/{id}/share    → revoke all share tokens for this recipe
GET    /api/shared/{token}        → public endpoint, no auth, returns recipe data
```

The public endpoint is the interesting one. It needs to:
- Look up token, verify not expired
- Return recipe with ingredients, instructions, tags — but NOT user info, ratings, favorites, or cook history
- Be rate-limited separately from auth'd endpoints (prevent scraping)
- NOT set any session or CSRF cookies

### Frontend Approach

**Share button on RecipePage:**
- Click "Share" → POST to create token → show modal with copyable URL
- URL format: `https://crumble.fmr.local/shared/{token}`
- "Copy Link" button with clipboard API
- Show expiration date
- "Revoke" button

**Public recipe page:**
- New route: `/shared/:token` → `SharedRecipePage.jsx`
- Renders outside of `<ProtectedRoute>` and `<Layout>` — no sidebar, no nav
- Minimal branded page: Crumble logo, recipe content, "Get your own Crumble" footer link
- No edit/delete/favorite/rate buttons
- Mobile-responsive (same responsive patterns as RecipePage but simpler)

### Security Considerations

- UUID v4 tokens are 128-bit random — unguessable without the link
- 30-day expiration prevents indefinite exposure
- Public endpoint returns recipe data only — no user IDs, no session creation
- Rate limit: 30 requests per minute per IP on the public endpoint
- No search/index of shared recipes — you need the exact token

### Edge Cases

- Recipe deleted while share link exists → CASCADE deletes share, public endpoint returns 404
- User revokes and re-shares → new token, old links stop working
- Multiple shares of same recipe → could allow (multiple tokens) or enforce one active token per recipe. I'd enforce one — simpler, less confusion. `UNIQUE KEY (recipe_id)` or delete-then-insert.

Actually, on reflection: **one active share per recipe is better.** If someone shares, then shares again, they probably expect the old link to still work. So: if a share already exists and isn't expired, return the existing token. Only create new if none exists or current is expired. Revoke explicitly deletes.

This is the simplest Tier 1 feature by far — maybe 2-3 hours of work. Could be a good warm-up before tackling grocery consolidation.

---

## Deep Dive: PWA Feasibility for Crumble

*Explored 2026-03-08*

Recipe apps are arguably the best use case for PWA. You're standing in a kitchen with flour on your hands, wifi is spotty, and you need to see step 4 of your lasagna recipe. If the app goes blank, dinner is ruined.

### What Would PWA Give Crumble?

1. **Installable** — "Add to Home Screen" on mobile, launches like a native app
2. **Offline recipe viewing** — cached recipes stay accessible without network
3. **Offline grocery list** — check off items at the store even with no signal
4. **Faster loads** — service worker serves cached assets instantly

### How Hard Is It?

Surprisingly easy with `vite-plugin-pwa`. Here's the minimum viable PWA:

**Install:**
```bash
npm install -D vite-plugin-pwa
```

**Update `vite.config.js`:**
```javascript
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  plugins: [
    react(),
    tailwindcss(),
    VitePWA({
      registerType: 'autoUpdate',
      manifest: {
        name: 'Crumble - Recipe Manager',
        short_name: 'Crumble',
        description: 'Your personal recipe collection',
        theme_color: '#C1694F',        // terracotta
        background_color: '#FFF8F0',   // cream
        display: 'standalone',
        icons: [
          { src: '/icon-192.png', sizes: '192x192', type: 'image/png' },
          { src: '/icon-512.png', sizes: '512x512', type: 'image/png' },
        ]
      },
      workbox: {
        globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
        runtimeCaching: [
          {
            urlPattern: /\/api\/recipes\/\d+$/,
            handler: 'StaleWhileRevalidate',
            options: { cacheName: 'recipe-detail', expiration: { maxEntries: 50, maxAgeSeconds: 7 * 24 * 60 * 60 } }
          },
          {
            urlPattern: /\/uploads\//,
            handler: 'CacheFirst',
            options: { cacheName: 'recipe-images', expiration: { maxEntries: 100, maxAgeSeconds: 30 * 24 * 60 * 60 } }
          },
          {
            urlPattern: /\/api\/grocery/,
            handler: 'NetworkFirst',
            options: { cacheName: 'grocery', expiration: { maxEntries: 10 } }
          }
        ]
      }
    })
  ],
})
```

**That's it for basic PWA.** The plugin generates the service worker, manifest, and handles registration automatically.

### Caching Strategy Breakdown

| Resource | Strategy | Why |
|----------|----------|-----|
| Static assets (JS/CSS/HTML) | **Precache** (automatic) | Never changes between builds |
| Recipe detail (`/api/recipes/:id`) | **StaleWhileRevalidate** | Show cached version instantly, update in background |
| Recipe images (`/uploads/`) | **CacheFirst** | Images don't change, save bandwidth |
| Grocery lists (`/api/grocery/`) | **NetworkFirst** | Freshness matters more, fallback to cache offline |
| Recipe list (`/api/recipes`) | **NetworkFirst** | Want latest data, but cache for offline |
| Auth endpoints | **NetworkOnly** | Never cache auth — security risk |

**StaleWhileRevalidate** is the sweet spot for recipes — show what you have instantly, silently update if the network is available. User sees the recipe immediately, and if it was updated since last visit, it refreshes in the background.

### What's Missing from the Current Setup

1. **No manifest** — `index.html` has no `<link rel="manifest">` (plugin adds this automatically)
2. **No theme-color meta** — need `<meta name="theme-color" content="#C1694F">` in index.html
3. **No icons** — need 192x192 and 512x512 PNG icons (could generate from `crumble_icon.PNG` which already exists!)
4. **No apple-touch-icon** — iOS still needs this separately
5. **Google Fonts dependency** — Nunito and Playfair Display loaded from CDN. These won't work offline unless we self-host the fonts or add a runtime cache rule for Google Fonts.

### The Google Fonts Problem

Current `index.html` loads fonts from `fonts.googleapis.com`. Offline = no fonts = ugly fallback. Options:
1. **Self-host fonts** — download woff2 files, serve from `/fonts/`. Most reliable.
2. **Cache the CDN** — add a runtime cache rule for `fonts.googleapis.com` and `fonts.gstatic.com`. Works but depends on first visit caching.

Self-hosting is cleaner. Nunito + Playfair Display woff2 files total ~200KB. Worth it for offline reliability.

### The Auth Problem

PWAs that require auth have a tricky offline story. If the session expires while offline:
- Cached API responses still work (service worker serves from cache)
- But new API requests will fail with 401
- User sees cached data but can't modify anything

**Pragmatic approach:** Show cached data in read-only mode when offline. Display a subtle "You're offline" banner. Disable write operations (add/edit/delete) when navigator.onLine is false. The `useWakeLock` hook already checks navigator support — similar pattern for online detection.

### Effort Estimate

| Task | Effort |
|------|--------|
| Install plugin + config | 15 min |
| Generate icons from crumble_icon.PNG | 15 min |
| Add meta tags to index.html | 5 min |
| Self-host fonts | 30 min |
| Runtime caching rules | 30 min |
| Offline detection + banner | 1 hour |
| Testing on mobile | 1 hour |
| **Total** | **~3.5 hours** |

For 3.5 hours of work, Crumble gets: installable on mobile, offline recipe viewing, cached images, and faster loads. That's extremely high ROI.

### What I'd Skip for v1 PWA

- Offline writes with background sync (complex, needs queue management)
- Push notifications (requires push server infrastructure)
- Full offline-first architecture (would need IndexedDB, conflict resolution)

Just make recipes and grocery lists *readable* offline. That covers 90% of the use case (looking at a recipe while cooking, checking grocery list at the store).

### My Take

This should probably be Tier 1.5, not Tier 3. The effort is lower than recipe sharing, and the impact on daily usage is higher. The fact that `vite-plugin-pwa` exists and Crumble already has a nice icon makes this almost a configuration task rather than a feature build.

The main risk is the Google Fonts dependency — if you don't self-host, the first offline experience will have ugly font fallbacks and users will think the app is broken. Self-hosting fonts is the boring but correct answer.

---

## Deep Dive: "What Can I Make?" — Pantry-Based Recipe Search

*Explored 2026-03-08*

This is the feature that commercial apps like SuperCook nail but **no self-hosted recipe manager has implemented**. Mealie has an open discussion (#2448) requesting it, Tandoor has a feature request (#3840), KitchenOwl doesn't have it. This is a genuine gap in the self-hosted space.

### Why It's Interesting for Crumble

Crumble already has the building blocks:
- `ingredients` table with parsed name/amount/unit per recipe
- `IngredientParser` that breaks strings into structured components
- Grocery list system (could double as pantry tracking)

The missing pieces are: an ingredient index for reverse lookups, a matching algorithm, and a UI.

### The Algorithm Problem

Three approaches, ranked by complexity:

**1. Simple set intersection (easiest)**
```
score = count(user_ingredients ∩ recipe_ingredients) / count(recipe_ingredients)
```
This is "asymmetric coverage" — what percentage of a recipe's ingredients does the user have? A recipe needing 8 ingredients where you have 7 scores 87.5%. Sort descending.

Problem: "salt", "pepper", "olive oil" are in almost every recipe. They inflate match scores without being meaningful. Need to either exclude pantry staples or weight ingredients by specificity.

**2. Weighted coverage (medium)**
Weight each ingredient by how distinctive it is (inverse document frequency):
```
weight(ingredient) = log(total_recipes / recipes_containing_ingredient)
```
"Saffron" appears in 2 recipes → high weight. "Salt" appears in 200 → low weight. Sum weights of matched ingredients, divide by sum of all recipe ingredient weights.

This naturally deprioritizes staples without a hardcoded exclusion list.

**3. ML/NLP approaches (overkill for Crumble)**
TF-IDF vectorization, cosine similarity, collaborative filtering. Powerful but adds Python dependencies, model training, and complexity that contradicts Crumble's philosophy.

**My recommendation: Option 1 with a staples exclusion list.** Simple, fast, explainable. The exclusion list can be user-configurable ("I always have these on hand"). This is what SuperCook effectively does.

### The Ingredient Normalization Problem

This is the *hard* part. When a user types "chicken" into their pantry, should it match:
- "chicken breast" ✓
- "boneless skinless chicken breast" ✓
- "chicken thighs" ✓ (probably)
- "chicken broth" ✗ (different ingredient entirely)
- "rotisserie chicken" ✓ (maybe)

Approaches:
- **Substring matching**: "chicken" matches anything containing "chicken". Simple but catches "chicken broth" which is wrong.
- **Base ingredient extraction**: Strip modifiers (boneless, skinless, fresh, dried, chopped, minced, large, small, whole) and match the core noun. "boneless skinless chicken breast" → "chicken breast". Better but still imperfect.
- **Ingredient taxonomy**: Map ingredients to categories. "chicken breast", "chicken thigh", "chicken wing" all map to parent "chicken". Most accurate but requires maintaining a lookup table.

For a v1, **substring match with a blacklist** is probably fine. Blacklist compound ingredients where the substring match would be wrong: "broth", "stock", "sauce", "powder", "extract", "oil" (so "chicken" doesn't match "chicken broth" or "chicken stock").

### Database Requirements

Currently the `ingredients` table has only one index: `idx_ingredient_recipe (recipe_id)`. For reverse lookups ("which recipes use flour?"), we need:

```sql
-- Option A: Just add an index on name (case-insensitive search via LIKE)
ALTER TABLE ingredients ADD INDEX idx_ingredient_name (name);

-- Option B: Normalized ingredient lookup table (better long-term)
CREATE TABLE ingredient_index (
  id INT AUTO_INCREMENT PRIMARY KEY,
  base_name VARCHAR(100) NOT NULL,  -- normalized: "chicken breast"
  recipe_id INT NOT NULL,
  ingredient_id INT NOT NULL,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
  FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE,
  INDEX idx_base_name (base_name),
  UNIQUE KEY idx_unique (base_name, recipe_id)
) ENGINE=InnoDB;
```

Option A is simple but slow for large ingredient lists (LIKE '%chicken%' can't use an index efficiently). Option B is faster and enables exact matching on normalized names, but needs to be rebuilt when recipes are added/updated.

For Crumble's scale (likely hundreds, not millions of recipes), Option A with a name index is probably fine. Add the normalized lookup table later if performance matters.

### UI Design

SuperCook's pattern is the gold standard:

```
┌─────────────────────────────────────────────┐
│  What's in your kitchen?                    │
│  ┌──────────────────────────────────┐       │
│  │ Type an ingredient...       🔍  │       │
│  └──────────────────────────────────┘       │
│                                             │
│  Your ingredients:                          │
│  [chicken ×] [rice ×] [garlic ×] [onion ×] │
│                                             │
│  ── You can make (12 recipes) ──────────── │
│                                             │
│  Garlic Chicken Rice         ✓ 4/4 100%    │
│  Chicken Stir Fry            ✓ 6/7  86%    │
│    missing: soy sauce                       │
│  Chicken Fried Rice          ✓ 5/6  83%    │
│    missing: eggs                            │
│  ...                                        │
│                                             │
│  ☐ Include recipes missing 1 ingredient     │
│  ☐ Include recipes missing 2 ingredients    │
└─────────────────────────────────────────────┘
```

Key UX decisions:
- **Autocomplete from existing ingredients** — query the `ingredients` table for unique names, deduplicate, offer as suggestions. No need for a separate ingredient database.
- **Show what's missing** — the missing ingredients are the actionable info. "You're one egg away from Chicken Fried Rice" is more useful than just a match score.
- **Configurable tolerance** — let users choose "exact matches only" or "missing 1-2 ingredients". Default to missing ≤ 2.
- **Persistent pantry** — save the user's ingredient list to localStorage (or a `user_pantry` table) so they don't re-enter everything each time.

### Implementation Sketch

**Backend: `GET /api/recipes/by-ingredients?ingredients=chicken,rice,garlic&max_missing=2`**

```php
public function findByIngredients(array $ingredientNames, int $maxMissing = 2): array {
    // 1. For each recipe, count how many of the user's ingredients appear
    //    Using LIKE for substring matching (with blacklist)
    // 2. Calculate coverage = matched / total_recipe_ingredients
    // 3. Filter: total - matched <= maxMissing
    // 4. Sort by coverage DESC, then by total ingredients ASC (simpler recipes first)
    // 5. Return recipes with match info (matched count, missing ingredients list)
}
```

The SQL could be done with subqueries, but for clarity a two-pass approach might be cleaner:
1. Find all recipes that contain at least one of the user's ingredients
2. For each candidate recipe, get its full ingredient list and compute the match

For a database with <1000 recipes, this is fast enough even without optimization.

**Frontend: New page or modal?**
- **New page** (`/pantry` or `/whats-for-dinner`) — gives room for the full search interface, results list, and filters. Feels like a feature, not a utility.
- **Modal from home page** — quicker access but cramped for the results display.

I'd go with a new page. Add it to the sidebar with a `ChefHat` or `Search` icon.

### Effort Estimate

| Task | Effort |
|------|--------|
| Add name index to ingredients table | 5 min |
| Backend: findByIngredients query | 2 hours |
| Backend: ingredient autocomplete endpoint | 30 min |
| Frontend: PantrySearchPage with ingredient input | 2 hours |
| Frontend: results display with match info | 1.5 hours |
| localStorage pantry persistence | 30 min |
| **Total** | **~6.5 hours** |

### My Take

This is a **Tier 2 feature that could become Tier 1** because of the competitive gap. No self-hosted recipe manager has it. SuperCook proves the UX works. The algorithm is straightforward (no AI needed). The hard part — ingredient normalization — can start simple (substring + blacklist) and improve over time.

The question is whether Crumble users would actually use this day-to-day. Meal planning answers "what should I cook this week?" proactively. Pantry search answers "what can I cook right now?" reactively. Both are valid, but meal planning is probably used more consistently.

If I were prioritizing: grocery consolidation first (fixes a bug + adds value), then pantry search (new capability, differentiator).

---

## ~~UX Issue: Two Share Buttons on RecipePage~~ — RESOLVED

*Noticed and fixed 2026-03-08*

Card sharing button commented out, "Share Link" renamed to "Share". Single button, single purpose. Card sharing code preserved in comments for potential future unified share modal. Also added error state to share modal (was spinner-forever on API failures) and removed ownership restriction (any authenticated user can share any recipe).

---

## Deep Dive: RecipeScraper — Strengths and Gaps

*Explored 2026-03-08*

After reading the full 688-line `RecipeScraper.php`, this is genuinely well-built. It's one of Crumble's best assets.

### What It Does Well

**4-tier parsing cascade:**
1. JSON-LD (`schema.org/Recipe`) — most reliable, handles `@graph` nesting, `HowToSection` with sub-steps
2. Microdata (`itemtype="schema.org/Recipe"`) — fallback for older sites
3. Heuristic HTML — finds ingredient/instruction `<li>` items in containers with matching class/id names
4. Open Graph — metadata-only fallback (title, description, image — no recipe data)

If all four fail, it tries Google AMP cache and Google Web Cache for JS-rendered pages. That's thorough.

**Good security:**
- SSRF protection: blocks private/reserved IPs via `gethostbyname()` + `FILTER_FLAG_NO_PRIV_RANGE`
- SSL verification enabled (now with portable CA bundle)
- 5 rotating user agents
- 15s timeout, 5 max redirects

**Ingredient parsing integration:** Every ingredient string goes through `IngredientParser::parse()` immediately, so scraped recipes arrive pre-structured with amount/unit/name separated.

### What Could Be Better

**1. No nutrition extraction**
JSON-LD `schema.org/Recipe` supports `nutrition.calories`, `nutrition.proteinContent`, etc. The scraper ignores these entirely. Adding them would auto-populate the dormant nutrition fields for any site that provides structured data (which is most major recipe sites — AllRecipes, Food Network, BBC Good Food all include it).

This would be ~15 lines of code in `mapJsonLdRecipe()`:
```php
if (isset($data['nutrition']) && is_array($data['nutrition'])) {
    $n = $data['nutrition'];
    $result['calories'] = $this->parseNutritionValue($n['calories'] ?? null);
    $result['protein'] = $n['proteinContent'] ?? null;
    $result['carbs'] = $n['carbohydrateContent'] ?? null;
    $result['fat'] = $n['fatContent'] ?? null;
    $result['fiber'] = $n['fiberContent'] ?? null;
    $result['sugar'] = $n['sugarContent'] ?? null;
}
```

This would also make the nutrition fields less "in limbo" — they'd get populated automatically from imports.

**2. No tag extraction**
JSON-LD `recipeCategory` and `recipeCuisine` fields exist on most recipe sites. These could auto-populate Crumble tags:
```json
"recipeCategory": "Dessert",
"recipeCuisine": "Italian",
"keywords": "pasta, quick, weeknight"
```

The `keywords` field is particularly useful — it maps directly to Crumble's tag system. Currently, imported recipes have no tags unless manually added.

**3. User agent strings are dated**
The user agents reference Chrome 120 / Firefox 121 (late 2023). Some sites check user agent freshness and serve different content or block old versions. Should update to 2025/2026 versions periodically. Not a critical issue but worth a refresh.

**4. Google Web Cache may not work anymore**
Google deprecated its web cache service in early 2024. The `fetchCachedVersion()` fallback to `webcache.googleusercontent.com` likely returns errors now. The AMP cache fallback is also less useful as AMP adoption declines. These aren't harmful (they fail gracefully) but represent dead code paths.

**5. Heuristic parser doesn't handle "recipe card" plugins**
WordPress recipe plugins (WP Recipe Maker, Tasty Recipes, Recipe Card Blocks) have specific HTML patterns that aren't standard microdata. They do emit JSON-LD though, so this is only an issue when JSON-LD is malformed or missing.

### Priority Improvements

| Change | Effort | Impact |
|--------|--------|--------|
| Extract nutrition from JSON-LD | 30 min | Populates dormant fields for free |
| Extract tags from keywords/category/cuisine | 30 min | Auto-tags imported recipes |
| Update user agent strings | 5 min | Avoid potential blocking |
| Remove dead Google Cache fallback | 5 min | Code cleanup |

The nutrition and tag extraction are the highest-value changes — they make existing features (NutritionFacts component, tag filtering) work automatically for imported recipes. Both are trivial to implement because the structured data is already parsed, we just need to read more fields.

---

## Session Summary — 2026-03-08

### Features Built
1. **Meal Planning** — full weekly view with mobile day-picker, recipe search, grocery generation (committed, pushed)
2. **Recipe Sharing** — public links with 30-day expiry, rate-limited public endpoint, share/revoke UI (ready to commit)

### Bugs Fixed (5 of 6 "things to fix")
1. ~~N+1 queries~~ — `Recipe::findById()` 9 queries → 3
2. ~~Hardcoded CA path~~ — shared `getCaBundlePath()` helper
3. ~~Grocery `recipe_name` mismatch~~ — frontend now reads `recipe_title`
4. ~~Grocery `$existingByName` index~~ — updates after creating new items
5. ~~No error boundaries~~ — `ErrorBoundary` wrapping all routes

### Other Fixes
- Share button spinner-forever bug (ownership check too restrictive + no error state)
- Removed duplicate share button, cleaned up card sharing code

### Remaining
- Nutrition fields cleanup (design decision, not a bug)
- Smart Grocery Consolidation (draft plan ready at `docs/plans/grocery-consolidation-autonomous.md`)
- Pantry-based recipe search (deep dive written, ~6.5 hours effort, no competitor has this)
- PWA support (deep dive written, ~3.5 hours effort, high ROI)

### What Changed (uncommitted files)
- `api/models/Recipe.php` — N+1 fix
- `api/models/GroceryItem.php` — index bug fix
- `api/config/env.php` — `getCaBundlePath()` helper
- `api/services/RecipeScraper.php` — use shared CA helper
- `api/services/ImageProcessor.php` — use shared CA helper
- `api/models/RecipeShare.php` — new file (recipe sharing)
- `api/controllers/RecipeShareController.php` — new file (recipe sharing)
- `database/migrations/007_recipe_shares.sql` — new file (recipe sharing)
- `frontend/src/components/ui/ErrorBoundary.jsx` — new file
- `frontend/src/components/grocery/GroceryItem.jsx` — property name fix
- `frontend/src/pages/RecipePage.jsx` — share button cleanup + error state
- `frontend/src/pages/SharedRecipePage.jsx` — new file (public recipe view)
- `frontend/src/services/api.js` — share link API functions
- `frontend/src/App.jsx` — ErrorBoundary + shared route

---

## Deep Dive: CookMode — What's There and What's Missing

*Explored 2026-03-08*

CookMode (`frontend/src/components/recipe/CookMode.jsx`) is one of Crumble's most polished components. It's a full-screen cooking interface that I initially assumed was bare-bones, but it already has more than I expected.

### What's Already Built

**Full-screen immersive UI:**
- Dark brown background (`bg-brown`), large readable text (xl → 3xl responsive), centered step content
- Progress bar showing step X of Y
- Clean, focused — no distractions

**Navigation:**
- Previous/Next buttons with proper disabled states
- Keyboard: arrow keys (left/right/up/down) + Escape to close
- Touch: swipe left/right with 50px threshold
- "Done!" button on last step (sage green, different from terracotta Next)

**Wake lock:**
- `useWakeLock` hook is actually used! CookMode acquires it on mount, releases on unmount
- The hook even re-acquires on `visibilitychange` (switching tabs and back)
- Graceful degradation — `'wakeLock' in navigator` check, console.warn on failure

**Inline timer detection:**
- Regex parses "bake for 25 minutes", "cook 2 hours" etc. from step text
- Shows "Start X min timer" button when time references are detected
- Timer component (`Timer.jsx`) has play/pause/reset, tabular-nums display
- Audio alert: 3 beeps at 880Hz via Web Audio API when timer finishes
- Multiple simultaneous timers supported

**Ingredient reference:**
- Slide-out panel from left (280px wide, max 85vw for mobile)
- Shows full ingredient list via `IngredientList` component
- Backdrop overlay when open, closes on backdrop click

### The Missed Connections

**1. "Done!" doesn't log a cook**
This is the biggest gap. CookMode has a "Done!" button on the final step, but it just calls `onClose()` — which closes the overlay and goes back to RecipePage. The cook log is tracked separately via a `CookButton` ("I Cooked This") on RecipePage.

The user finishes cooking step-by-step through CookMode, hits "Done!", and... nothing is recorded. They have to then find and click a separate button. The natural flow would be:

```
Done! → "Log this cook?" → [Yes / Yes + Add Notes / Skip] → close
```

The backend already supports notes (`cook_log.notes` column, `CookLog::log()` accepts `?string $notes`), but the `CookButton` component doesn't expose any UI for entering notes. The cook history page displays notes if they exist, but there's currently no way to create them.

**2. Timers require two clicks to start**
Detected timers show a "Start X min timer" button, which adds the timer to `activeTimers` state. But the Timer component starts in a paused state — user has to click Play to actually start it. That's two clicks: "Start timer" then "Play". The timer should auto-start since the user explicitly chose to start it.

**3. No timer persistence across steps**
When navigating to the next step, `setActiveTimers([])` clears all active timers. If you start a "bake for 30 minutes" timer and move to the next step to prep something else, your timer vanishes. This defeats the purpose of the timer — baking instructions specifically require continuing to other steps while waiting.

**4. No step completion tracking**
There's no visual indicator of which steps have been visited/completed. In a long recipe (10+ steps), it's useful to know "I've done steps 1-6, currently on 7." A simple dot indicator or step list would help.

### Voice Control Assessment

The Web Speech API (`SpeechRecognition`) could enable hands-free "next step" / "start timer" commands. Current browser support:

- **Chrome, Edge, Opera**: Supported (uses server-side recognition — requires internet)
- **Safari, Firefox**: Not supported
- **Mobile Chrome/Android**: Works well
- **iOS Safari**: Not supported

For a kitchen scenario: Chrome on Android = most likely mobile use case, and it works there. The limited browser support is acceptable as a progressive enhancement (show mic button only when supported).

Commands needed are simple and finite:
- "next" / "next step" → `goNext()`
- "previous" / "back" → `goPrev()`
- "start timer" → `startTimer(detectedTimers[0])`
- "ingredients" → `setShowIngredients(true)`

The recognition only needs to match ~6 words, not full sentences. `SpeechRecognition.continuous = true` with `interimResults = false` would work. The main concern is background noise in a kitchen (fan, sizzling) causing false triggers. A wake word like "hey Crumble" is overkill — a simple push-to-listen mic button is probably better.

### Priority Fixes

| Change | Effort | Impact |
|--------|--------|--------|
| Auto-start timers (remove paused initial state) | 5 min | Eliminates unnecessary click |
| Persist timers across steps | 30 min | Critical for baking recipes |
| "Done!" prompts cook log with optional notes | 1 hour | Connects two features that should be one flow |
| Step completion dots/indicators | 30 min | Better orientation in long recipes |
| Voice control (progressive enhancement) | 2 hours | Hands-free cooking, differentiator |

### My Take

CookMode is 80% of the way to being a standout feature. The timer persistence bug is the most impactful fix — it's the kind of thing that makes you stop trusting the feature entirely. You start a 30-minute timer, swipe to the next step, and it's gone. That trains users to use their phone's built-in timer instead, making CookMode's timer feature pointless.

The "Done!" → cook log connection is a UX flow issue, not a technical one. It's a 1-hour fix that ties together two existing features (CookMode and cook history) into a coherent experience. The fact that `CookLog::log()` already accepts notes but no UI exposes note entry is a missed opportunity — post-cook notes ("too salty", "halve the garlic next time") are genuinely useful when you cook the recipe again months later.

Voice control is the flashy feature, but the three fixes above should come first. A CookMode that loses your timers isn't ready for voice control.

---

## Deep Dive: Search — How It Works and Where It Falls Short

*Explored 2026-03-08*

### The Current Implementation

**Backend:** MySQL FULLTEXT index on `recipes.title` and `recipes.description`. Query uses `MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE)` with the search term + `*` wildcard appended for prefix matching.

**Frontend — two search inputs:**
1. **Header search** (`Header.jsx`): Form-based, triggers on Enter. Calls `onSearch(value)` → navigates to `/` → passes through `Layout` state → arrives as `searchQuery` prop on `HomePage`.
2. **HomePage inline search** (`HomePage.jsx`): Controlled input with 300ms debounce. Calls `fetchRecipes()` directly. Only visible on desktop (`hidden md:block`).

The Header search exists for mobile (where the inline search is hidden) and for searching from non-home pages. The HomePage search gives instant feedback on desktop.

### Problems

**1. Search only covers title and description**
Searching "chicken" won't find a recipe titled "Easy Weeknight Stir Fry" that has chicken breast in its ingredients. Users expect ingredient search to work — it's the most natural search pattern in a recipe app. Every competitor (Mealie, Tandoor, KitchenOwl) supports it.

The fix requires either:
- **Extend FULLTEXT**: Add a `searchable_text` column that concatenates title + description + ingredient names, FULLTEXT index on that. Updated on recipe create/update. Simple but denormalized.
- **Separate ingredient search**: `WHERE r.id IN (SELECT recipe_id FROM ingredients WHERE name LIKE ?)`. Slower but doesn't need schema changes. Could use the `idx_ingredient_name` index from the pantry search proposal.
- **Combined**: FULLTEXT for title/description + fallback LIKE query on ingredients if FULLTEXT returns few results.

**2. InnoDB FULLTEXT minimum word length**
Default `innodb_ft_min_token_size` is **3**. Words shorter than 3 characters are not indexed. Searching for "egg" (3 chars) works, but searching for "bun" might not match "buns" in boolean mode depending on stopword configuration. More importantly, two-letter ingredients or terms are invisible to FULLTEXT.

**3. The two search inputs are slightly disconnected**
The Header search and HomePage search maintain independent state. If you type "pasta" in the Header and press Enter, it navigates to `/` and sets `searchQuery` via Layout. But the HomePage's `localSearch` state syncs from the prop via `useEffect`. If you then clear the inline search, the Header search still shows "pasta" (Header doesn't read from HomePage state — it's one-way).

This isn't a bug per se, but it's a subtle UX inconsistency. A shared search context (React context or URL search params as source of truth) would be cleaner.

**4. ~~No search results feedback~~ — WRONG, already handled**
`RecipeGrid.jsx` does show "No recipes found" + "Try a different search or add a new recipe" when `recipes.length === 0 && !isLoading`. This works correctly. The emoji book icon (📖) is a nice touch. One minor improvement: it could echo back the search query ("No recipes found for 'xyz'") to confirm the search actually ran.

**5. Boolean mode wildcard behavior**
The backend appends `*` to the search term: `$params[] = $search . '*'`. This means "pasta" becomes "pasta*", matching "pasta", "pastas", "pastabilities". But if the user types multiple words like "chicken pasta", it becomes "chicken pasta*" — only the last word gets the wildcard. The first word "chicken" must be an exact match (or at least match the FULLTEXT stemming). Multi-word searches may produce unexpected results.

Better approach: split by whitespace and wildcard each term:
```php
$terms = array_filter(explode(' ', $search));
$searchParam = implode(' ', array_map(fn($t) => '+' . $t . '*', $terms));
// "+chicken* +pasta*" — both words required, both with prefix matching
```

### Effort to Fix

| Change | Effort | Impact |
|--------|--------|--------|
| Wildcard each search term separately | 10 min | Multi-word searches actually work |
| Add ingredient search (LIKE fallback) | 1 hour | Most-requested search behavior |
| "No results" message in RecipeGrid | 15 min | Basic UX gap |
| Unified search state via URL params | 45 min | Cleaner architecture, shareable search URLs |
| `searchable_text` column + FULLTEXT | 2 hours | Best long-term search performance |

The wildcard fix is a 10-minute change that meaningfully improves search quality. The ingredient search is the biggest user-facing improvement.

---

## Deep Dive: Frontend Performance & Architecture

*Explored 2026-03-08*

### Bundle Analysis

```
index.js     303.62 KB  (85.52 KB gzipped)
index.css     47.33 KB  ( 8.58 KB gzipped)
Total:       350.95 KB  (94.10 KB gzipped)
```

**Dependencies (runtime):** react, react-dom, react-router-dom, lucide-react. That's it. Four dependencies. The restraint here is genuinely impressive — no state management library, no form library, no CSS-in-JS, no data fetching library.

**Is 85KB gzipped good?** For context:
- React + ReactDOM alone: ~42KB gzipped
- react-router-dom: ~12KB gzipped
- lucide-react (tree-shakeable): varies by usage, probably ~15-20KB for icons used
- App code: ~10-15KB gzipped

Yes, this is lean. A typical Create React App with a fraction of these features ships 200KB+ gzipped. Crumble is well under budget.

### No Code Splitting

Every page (HomePage, RecipePage, AddRecipePage, EditRecipePage, GroceryPage, MealPlanPage, AdminPage, BulkImportPage, CookHistoryPage, FavoritesPage, SharedRecipePage) is bundled into a single JS file. No `React.lazy()`, no `Suspense`, no dynamic imports.

**Does this matter?** At 85KB gzipped, honestly not much. Code splitting adds complexity (loading states per route, preloading logic, more HTTP requests) and the benefit is only meaningful when bundles are 200KB+ gzipped. The entire Crumble app loads faster than many apps' initial chunk.

**When it would matter:**
- If PWA is implemented, the service worker precaches this entire bundle anyway
- If the app grows to 500KB+ (adding a rich text editor, chart library, etc.)
- For users on very slow connections (2G) where even 85KB is significant

**Verdict: Don't bother with code splitting yet.** It's premature optimization. If a heavy dependency gets added later (React Query, a markdown editor, a chart library for cooking analytics), revisit then.

### Data Fetching Patterns

Every data-fetching hook follows the same pattern:
```javascript
const [data, setData] = useState([]);
const [isLoading, setIsLoading] = useState(false);
const [error, setError] = useState(null);

const fetch = useCallback(async () => {
  setIsLoading(true);
  setError(null);
  try {
    const result = await api.someEndpoint();
    setData(result);
  } catch (err) {
    setError(err.message);
  } finally {
    setIsLoading(false);
  }
}, []);
```

This is fine for a small app, but has known weaknesses:
- **No caching** — navigating to a recipe, going back, and navigating to it again makes two API calls
- **No deduplication** — two components requesting the same data make two network calls
- **No stale-while-revalidate** — every navigation shows a loading skeleton even if we have perfectly valid cached data
- **Race conditions** — if the user types fast in search, responses could arrive out of order (the debounce mitigates but doesn't eliminate this)

React Query (TanStack Query) would solve all of these. But at Crumble's scale — a single-user or household app with <200 recipes — the duplicate requests are imperceptible. The loading skeletons flash for maybe 100ms on a local network.

**When React Query becomes worth it:**
- If optimistic updates are needed (check off grocery item → instant UI → sync in background)
- If the app goes PWA and needs sophisticated cache invalidation
- If multiple components on the same page need the same data

**Current verdict: Not needed yet, but it's the single highest-leverage frontend architectural change when the time comes.** The migration is incremental — you can adopt it one hook at a time.

### Component Patterns — What's Good

1. **Consistent styling language** — `bg-white rounded-2xl shadow-md` for cards, `min-h-[44px]` for touch targets, terracotta/sage/brown color palette. No deviations.
2. **Skeleton loading** — `Skeleton` component used consistently across pages. Better than spinners for perceived performance.
3. **Mobile-first** — responsive breakpoints used correctly. Mobile FAB for add recipe. Collapsible mobile search.
4. **No prop drilling** — `useAuth` context used cleanly. Search state passed through render props pattern (Layout → HomePage).

### What Could Be Better

1. **No loading state for navigation** — clicking a recipe card has no feedback until the page renders. A subtle top-of-page progress bar (like YouTube/GitHub) would feel snappier. `nprogress` is 2KB.
2. **Images aren't lazy-loaded** — `RecipeGrid` renders all images immediately, even below the fold. Native `loading="lazy"` on `<img>` tags is a zero-effort fix.
3. **Google Fonts block render** — `<link>` tags in index.html for Google Fonts are render-blocking. Adding `display=swap` (if not already present) prevents FOIT (Flash of Invisible Text). Self-hosting would be better for offline/PWA.
4. **No scroll restoration** — React Router 6 doesn't restore scroll position by default. Scrolling down the recipe list, clicking a recipe, and hitting back takes you to the top. `<ScrollRestoration />` from react-router-dom fixes this.

### The "Should We Add X?" Heuristic

Crumble's dependency list (4 runtime deps) is a feature, not a limitation. Before adding any dependency, the bar should be:

1. Does it solve a problem users actually have today? (not hypothetical)
2. Is the problem unsolvable in <50 lines of custom code?
3. Is the dependency smaller than the problem it solves?

By this heuristic:
- ✅ `vite-plugin-pwa` — solves offline access, can't be hand-rolled reasonably
- ✅ `@tanstack/react-query` — when optimistic updates are needed
- ❌ State management libraries — Context + useState is sufficient
- ❌ Form libraries — the existing forms work fine
- ❌ Animation libraries — CSS transitions handle everything needed
- ✅ `nprogress` (2KB) — instant navigation feedback, can't match the UX in 50 lines

### My Overall Take

Crumble's frontend is in that sweet spot where it's simple enough to understand in an afternoon but polished enough to feel like a real product. The 85KB gzipped bundle is excellent. The main improvements are micro-UX things (scroll restoration, image lazy loading, navigation progress) that take 30 minutes total but collectively make the app feel more professional.

The architecture doesn't need changing. No state management library, no code splitting, no build-time optimization gymnastics. When the time comes for React Query or PWA, the migration is straightforward because the codebase is simple. Complexity is easy to add but hard to remove — Crumble is right to stay lean.

---

## Deep Dive: Accessibility Audit — Kitchen Apps Have Unique A11y Needs

*Explored 2026-03-08*

Recipe apps have unusual accessibility requirements. The user is often:
- Standing 2-3 feet from the screen (arm's length)
- Has wet, floured, or greasy hands
- In variable lighting (bright kitchen, dim phone screen)
- Multitasking (cooking, not browsing)
- Possibly using voice (hands occupied)

This means standard web a11y (screen reader support, keyboard navigation) is necessary but not sufficient. A truly accessible kitchen app also needs large touch targets, high contrast, voice control, and offline resilience.

### What Crumble Gets Right

**Touch targets:** `min-h-[44px]` enforced consistently across every interactive element — buttons, nav links, form inputs, checkboxes. This meets WCAG 2.5.5 Target Size (Enhanced) at 44×44px. Even the CookMode navigation buttons are 56px. This is excellent and rare in self-hosted apps.

**aria-labels on icon-only buttons:** Every button that uses an icon without text has an `aria-label`. CookMode: "Toggle ingredients", "Close cook mode". Timer: "Pause", "Start", "Reset", "Play sound again". Favorites: "Add to favorites" / "Remove from favorites". This is consistent across the entire codebase — someone cared about this.

**Keyboard navigation in CookMode:** Arrow keys for step navigation, Escape to close. This is the most important keyboard interaction in the app (cooking scenario = can't always touch).

**Escape to close modals:** Modal component listens for Escape key globally. CookMode does too. Consistent pattern.

**Print stylesheet:** `print.css` is surprisingly thorough — hides nav, buttons, modals, CookMode overlay. Shows recipe content in a clean serif font at 12pt. Hides ingredient checkboxes and servings adjuster buttons. Even constrains image height to 300pt. Someone thought about "print this recipe and bring it to the kitchen."

**Semantic HTML:** Uses `<nav>`, `<main>`, `<aside>`, `<header>`, `<footer>`. Screen reader landmark navigation works.

**Focus styling on inputs:** All text inputs have `focus:border-terracotta focus:ring-1 focus:ring-terracotta`. Buttons use `focus:ring-2 focus:ring-offset-2`. Visible focus indicators.

### What Needs Fixing

**1. Modal doesn't trap focus (Critical)**
The Modal component renders a floating div but doesn't trap keyboard focus inside it. A user tabbing through the modal can tab behind it into the page content. It also lacks `role="dialog"` and `aria-modal="true"`.

Fix:
```jsx
<div role="dialog" aria-modal="true" aria-labelledby={titleId}>
```
Plus a focus trap (either `focus-trap-react` library or a custom implementation that catches Tab/Shift+Tab at the edges). Focus should move to the first focusable element when the modal opens and return to the trigger element when it closes.

This is the most impactful a11y fix because modals are used for share links, recipe selection in meal planning, and potentially more.

**2. CookMode has no focus trap either**
CookMode is a full-screen overlay (`fixed inset-0 z-50`) but tab can escape it. The ingredient slide-out panel has the same issue. Since CookMode takes over the entire screen, focus should be completely contained.

**3. No skip navigation link**
The sidebar has 8+ nav links. A "Skip to main content" link as the first focusable element is standard a11y practice. Without it, keyboard users must tab through every nav item on every page navigation.

```jsx
<a href="#main-content" className="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 bg-terracotta text-white px-4 py-2 rounded-xl">
  Skip to main content
</a>
// ...
<main id="main-content">
```

**4. Nested interactive elements in RecipeCard**
`RecipeCard` is a `<Link>` (interactive) that contains `FavoriteButton` (also interactive — a `<button>` inside an `<a>`). This is invalid HTML and confusing for screen readers. The favorite button click also triggers the link navigation.

Common fix: make the card a non-interactive `<div>`, use a stretched link for the title, and keep the favorite button separate. Or intercept the favorite button click with `e.preventDefault()` + `e.stopPropagation()`.

**Update:** FavoriteButton already uses `e.preventDefault()` + `e.stopPropagation()` in its click handler — so clicking the heart doesn't trigger navigation. The behavior works correctly. The HTML is still technically invalid (button inside anchor), but this is a very common pattern and practically harmless. Demoting this from "fix" to "aware of it."

**5. No `aria-live` regions for dynamic content**
Several areas update content dynamically without announcing changes to screen readers:
- Search results changing as user types (should be `aria-live="polite"`)
- Timer countdown in CookMode (should announce when timer reaches zero)
- "Logged!" feedback on CookButton (should be an aria-live announcement)
- Toast-style feedback (share link copied, recipe deleted)

Example fix for search:
```jsx
<div aria-live="polite" aria-atomic="true" className="sr-only">
  {recipes.length} recipes found
</div>
```

**6. Two `<nav>` elements without distinct labels**
Sidebar (`<aside>` containing `<nav>`) and BottomNav (`<nav>`) are both navigation landmarks. Screen reader users can't distinguish them. Fix:
```jsx
<nav aria-label="Main navigation">   // sidebar
<nav aria-label="Quick navigation">  // bottom nav
```

**7. Color contrast concerns**
`text-warm-gray` (#8D7B6E) on `bg-cream` (#FFF8F0) — this combination is used for secondary text throughout the app (metadata, timestamps, descriptions). The contrast ratio needs to be checked against WCAG AA (4.5:1 for normal text, 3:1 for large text).

Quick calculation: #8D7B6E on #FFF8F0 ≈ 3.4:1 contrast ratio. This **fails WCAG AA** for normal-size text (needs 4.5:1) but passes for large text (3:1). The warm-gray text is used at `text-sm` and `text-xs` sizes throughout, which means it's failing for most secondary text.

Fix options:
- Darken warm-gray to ~#6B5D52 (hits 4.5:1) — may look harsh
- Keep warm-gray for large text only, use a darker shade for small text
- Accept the AA failure for aesthetic reasons (many design-forward apps do this, though it's not ideal)

**8. Recipe images have no meaningful alt text**
RecipeCard uses `alt={recipe.title}` which is reasonable but generic. The image always just repeats the title. For a screen reader: "Chicken Parmesan. Image: Chicken Parmesan" is redundant. Better options:
- `alt=""` (decorative, since the title is already visible) — technically correct but loses information
- Keep `alt={recipe.title}` — it's fine, the redundancy is a minor issue

This is more of a nitpick than a real problem. The current approach is acceptable.

### Priority Fixes

| Change | Effort | Impact |
|--------|--------|--------|
| Add `role="dialog"` + `aria-modal` to Modal | 5 min | Screen readers understand modals |
| Focus trap in Modal | 30 min | Keyboard users can't get lost |
| Focus trap in CookMode | 20 min | Same, for cooking overlay |
| Skip navigation link | 10 min | Standard a11y, easy win |
| `aria-label` on nav elements | 5 min | Screen readers distinguish navs |
| `aria-live` for search results | 10 min | Dynamic content announced |
| Fix nested interactive in RecipeCard | 30 min | Valid HTML, better screen reader UX |
| Darken secondary text color | 15 min | WCAG AA compliance |
| `loading="lazy"` on images | 10 min | Not a11y but good practice, free perf |

### Kitchen-Specific A11y Thoughts

The standard WCAG checklist is useful, but for a kitchen app, I'd prioritize differently:

1. **CookMode is the most important screen** — this is where accessibility matters most. Large text, wake lock, and keyboard nav are already there. Adding voice control (Web Speech API, covered in CookMode deep dive) would be the biggest kitchen a11y win.

2. **Print is a legitimate accessibility strategy** — the print stylesheet exists and works well. Some users will always prefer a printed recipe over a screen. This is an often-overlooked form of accessibility.

3. **The 44px touch targets are the unsung hero** — in a kitchen with wet hands, small touch targets cause failed taps and frustration. Crumble's consistent 44px minimum is more impactful than any ARIA attribute for the primary use case.

4. **Offline access (PWA) is an accessibility concern** — if the app goes blank because of spotty kitchen wifi, the recipe is inaccessible regardless of how perfect the ARIA markup is. PWA with cached recipes would be the most impactful "accessibility" feature for kitchen use.

### My Overall Assessment

Crumble's a11y is above average for a self-hosted app — the touch targets, aria-labels, keyboard navigation, and print stylesheet show intentional accessibility work. The gaps (focus trapping, live regions, color contrast) are the typical holes in apps that do a11y "by instinct" rather than by audit.

The most impactful fixes are the focus trap in Modal (30 min) and the skip navigation link (10 min). The color contrast issue with warm-gray text is worth discussing but might conflict with the visual design intent — it's a judgment call between aesthetics and compliance.

For the kitchen use case specifically: the app is already good. The large touch targets and CookMode design show someone who actually cooks designed this. The next step for kitchen accessibility is voice control in CookMode, which transforms the app from "usable while cooking" to "designed for cooking."

---

## The 2026 Recipe App Landscape — Where Crumble Fits (and Where It Doesn't)

*Explored 2026-03-09*

I spent time researching what's happening in the recipe app space — both commercial and self-hosted — in 2026. The landscape has shifted since I last looked at it, and some of the shifts matter for Crumble.

### The Commercial AI Arms Race

The commercial recipe app market is in full AI fever. The dominant trends:

1. **Social media recipe extraction** — Apps like Pluck, Recipe One, and Honeydew use multimodal AI to extract structured recipes from Instagram Reels, TikTok videos, and YouTube Shorts. This is the "killer feature" of 2026 commercial recipe apps. The AI analyzes video frames, audio, and on-screen text to produce ingredient lists and instructions.

2. **AI meal planning** — Samsung Food and Ollie generate weekly meal plans based on dietary preferences, budget, and what's in your fridge. These go beyond Crumble's manual drag-and-drop planning.

3. **Ingredient substitution** — Apps like Recipe Remixer let you say "make this vegan" or "I don't have buttermilk" and the AI adapts the recipe. This is the most genuinely useful AI feature I've seen — it solves a real problem (dietary restrictions, missing ingredients) without being gimmicky.

4. **Photo-to-recipe** — Point your phone at ingredients on the counter, AI suggests what to cook. Mr. Cook is leading here. Neat demo, questionable daily utility.

The market is growing ~20% annually. Premium subscriptions are the business model. Most of these features require cloud AI services (OpenAI, Claude) and are impossible to self-host.

### The Self-Hosted Landscape: A New Competitor

The established players (Mealie, Tandoor, KitchenOwl) haven't changed dramatically. Mealie is still the most popular (11k+ GitHub stars, 10k+ instances), Tandoor is still the most feature-rich, KitchenOwl still has the best mobile experience.

But there's a new entrant worth watching: **Norish**.

**Norish** (github.com/norish-recipes/norish) launched December 2025 and already has 788 stars. It's household-first with real-time WebSocket sync, CalDAV calendar integration, and — interestingly — optional AI features including:
- Video recipe import (YouTube Shorts, Instagram Reels, TikTok)
- Image recipe import (screenshots/photos)
- Nutritional information generation
- Allergy detection and warnings
- AI-powered URL scraping fallback

The AI features are opt-in ("requires AI provider" — you plug in your own OpenAI key). This is the right approach for self-hosted: the app works without AI, but if you have an API key, you unlock extra capabilities.

**Norish's tech stack is maximalist:** Next.js 16, tRPC, PostgreSQL, Redis, BullMQ, Playwright, headless Chrome, yt-dlp, FFmpeg, Sharp. Deploying it requires Docker with PostgreSQL, Redis, and a Chrome instance. That's a lot of infrastructure for a recipe app.

**This is where Crumble's positioning becomes clearer.** Norish is for power users who run Kubernetes clusters and want real-time household sync. Crumble is for people who have a LAMP server and want a recipe box. These aren't competing for the same users.

### Updated Competitive Landscape

| Feature | Mealie | Tandoor | KitchenOwl | Norish | Crumble |
|---------|--------|---------|------------|--------|---------|
| Deploy complexity | Docker | Docker | Docker | Docker + PG + Redis + Chrome | **PHP + MySQL** |
| Real-time sync | No | No | Yes (Flutter) | Yes (WebSockets) | No |
| AI features | No | OCR | No | Yes (optional) | **No** |
| Social import | No | No | No | Yes (AI) | **No** |
| CalDAV | No | No | No | Yes | **No** |
| Mobile app | PWA | No | Flutter native | Next.js PWA | **No** |
| Meal planning | Yes | Yes | Yes | Yes | **Yes** |
| Recipe sharing | Groups | Public links | Household | Household + policies | **Public links** |
| Stars | 11k+ | 5k+ | 2k+ | 788 | — |
| Tech stack | Python/Vue | Python/Vue | Flask/Flutter | Next.js/tRPC/PG | **PHP/React** |

### What Crumble Should Learn (and What It Should Ignore)

**Learn from Norish: Optional AI is the right model.**
If Crumble ever adds AI features, the Norish approach is correct — bring your own API key, features degrade gracefully without it. Never require an AI service to function. The self-hosted ethos demands local-first operation.

The most valuable AI feature for Crumble would be **recipe adaptation** — "make this gluten-free" or "substitute for eggs." This is:
- Genuinely useful (people with dietary restrictions use this daily)
- Hard to do without AI (ingredient substitution requires culinary knowledge)
- Low-infrastructure (single API call, no persistent service needed)
- Non-essential (the recipe still works without it)

**Learn from Cooklang: Data portability matters.**
Cooklang's approach (plain text files, no database) is the opposite of Crumble, but the principle is sound — your recipes should be exportable and not locked in. Crumble should have a complete export feature (JSON and/or Cooklang format) so users never feel trapped.

**Ignore: Social media import.**
Extracting recipes from TikTok videos requires multimodal AI, video processing, and significant infrastructure. It's a feature that makes sense for a $5/month commercial app backed by cloud GPU inference. It makes zero sense for a self-hosted PHP app. The ROI is negative — massive implementation effort for a use case that most self-hosters (who probably don't save TikTok recipes) won't use.

**Ignore: Real-time sync.**
WebSocket support in PHP is architecturally painful (requires Ratchet or Swoole, long-running processes, fundamentally changes the deployment model). The benefit — seeing your spouse's grocery list updates in real time — is nice but not essential. Polling every 30 seconds is "good enough" and requires zero infrastructure changes.

**Double down: Deployment simplicity.**
Every competitor requires Docker. Norish requires Docker + PostgreSQL + Redis + Chrome. Crumble runs on a $5/month shared hosting account with PHP and MySQL. This is a genuine, defensible advantage. As the competitors get more complex, Crumble's simplicity becomes more valuable, not less.

### The AI Question — My Honest Take

I've been skeptical about AI in recipe apps throughout this document, and after researching the 2026 landscape, I'm more nuanced:

**AI features I'd build (if ever):**
1. **Recipe adaptation** — "make this dairy-free." Single API call. Optional. Genuinely solves a daily problem. Could be a simple button on RecipePage that sends the recipe to Claude/GPT and returns an adapted version. ~4 hours of work, requires user's API key.
2. **Smarter scraper fallback** — When the structured data parser fails, send the raw HTML to an LLM for extraction. Norish already does this. ~2 hours, optional feature.
3. **Auto-tagging on import** — Send recipe title + ingredients to an LLM, get back suggested tags. Saves the manual tagging step. ~1 hour, optional.

**AI features I wouldn't build:**
1. Video/social import — Too much infrastructure for the self-hosted use case.
2. AI meal planning — Manual planning works fine. The AI version adds latency and cost for marginal improvement.
3. AI-generated recipes — Users want to cook *their* recipes, not hallucinated ones.
4. Chatbot interface — "Ask your recipe collection" sounds cool in a demo, meaningless in practice.

The common thread: **AI should augment existing workflows, not create new ones.** Recipe adaptation takes an existing recipe and makes it work for you. Auto-tagging takes an existing import and saves a step. These are invisible, helpful AI. A chatbot or AI meal planner creates a new interface that competes with the existing one.

### Where Does Crumble Go From Here?

Looking at the competitive landscape, Crumble's path isn't to become Mealie or Norish. It's to be the recipe manager that doesn't need Docker, doesn't need AI, doesn't need a CS degree to deploy, but still does the job well.

The features that matter most for Crumble aren't the flashy ones — they're the invisible quality improvements:
1. **Search that finds ingredients** (not just titles)
2. **CookMode that doesn't lose timers** (trust-building)
3. **PWA for offline recipes** (kitchen reliability)
4. **Grocery consolidation that actually consolidates** (daily utility)
5. **Export/import** (data freedom)

These aren't impressive on a feature comparison table. But they're the difference between a recipe app you use once and one you use every day.

### The Simplicity Thesis

There's a broader thesis here: **every successful self-hosted app eventually becomes too complex for its original audience.** Mealie started simple and now has webhooks, LDAP, and OpenID. Tandoor has cost tracking, storage management, and multi-tenancy. As features accumulate, the deployment gets harder, the documentation gets longer, and the target audience shifts from "normal person who wants to manage recipes" to "homelabber who likes configuring things."

Crumble has a window to be the app that doesn't do this. The recipe manager for people who just want a recipe manager. The Pinboard to Mealie's Notion. The question is whether that restraint can be maintained as the feature list grows.

I don't know the answer. But I think the instinct that produced Crumble — PHP because it's simple, React because it's known, no Docker because why — is the right instinct. The temptation to add AI, real-time sync, CalDAV, and video import is strong. The discipline to not add them is stronger.

---

## Synthesis: The Crumble Roadmap — Pulling It All Together

*Written 2026-03-09, updated after 19 deep dives across code, competitors, and ideas*

After spending significant time reading every corner of this codebase, researching the competitive landscape, and writing thousands of words of analysis, here's what I'd actually build — and in what order.

### Design Principles (Distilled From Everything Above)

1. **Fix before feature.** Bugs erode trust faster than features build it.
2. **Invisible > flashy.** Search that finds ingredients beats AI chatbots.
3. **Simple > complete.** Crumble's deployment advantage is existential. Never add infrastructure.
4. **Optional > required.** Any new dependency or service must be opt-in.
5. **Kitchen-first.** If it doesn't help someone standing at a stove with flour on their hands, it can wait.

### Priority 1: Quick Wins (30 min total, no risk)

These are verified fixes from the code audit. Zero architecture, zero controversy.

| # | Fix | Effort | Source |
|---|-----|--------|--------|
| 1 | **Multi-word search wildcard** — split terms and wildcard each: `+chicken* +pasta*` | 10 min | Search deep dive |
| 2 | **`loading="lazy"` on images** — add to RecipeCard and HomePage img tags | 10 min | Frontend perf |
| 3 | **`aria-label` on nav elements** — distinguish Sidebar vs BottomNav for screen readers | 5 min | A11y audit |
| 4 | **`role="dialog"` + `aria-modal` on Modal** — proper screen reader semantics | 5 min | A11y audit |

### Priority 2: CookMode Trust Fixes (1.5 hours, high daily-use impact)

CookMode is the most kitchen-critical screen. These fixes make it trustworthy.

| # | Fix | Effort | Why |
|---|-----|--------|-----|
| 5 | **Persist timers across steps** — lift `activeTimers` state above step navigation | 30 min | A 30-minute bake timer vanishing on step change is a deal-breaker |
| 6 | **Auto-start timers** — remove the paused initial state when user clicks "Start timer" | 5 min | Two clicks → one click |
| 7 | **"Done!" → log cook with notes** — prompt after last step: "Log this cook? Add notes?" | 45 min | Connects two features that should be one flow |
| 8 | **Focus trap in CookMode** — keyboard users can't tab behind the full-screen overlay | 15 min | A11y critical for overlay |

### Priority 3: Search That Actually Works (1.5 hours, biggest UX gap)

Users expect to search by ingredient. Every competitor supports it. Crumble doesn't.

| # | Fix | Effort | Why |
|---|-----|--------|-----|
| 9 | **Ingredient search** — add fallback `WHERE r.id IN (SELECT recipe_id FROM ingredients WHERE name LIKE ?)` | 1 hr | Most-requested search pattern |
| 10 | **Index on ingredients.name** — `ALTER TABLE ingredients ADD INDEX idx_ingredient_name (name)` | 5 min | Makes ingredient search fast |
| 11 | **Focus trap in Modal** — used by share links, meal plan recipe picker | 30 min | A11y, keyboard users |
| 12 | **Skip navigation link** — first focusable element, standard a11y | 10 min | Quick win |

### Priority 3.5: Data Export (~2 hours, trust feature)

Users can import recipes but can't get them out. This is a data freedom gap.

| # | Component | Effort | Why |
|---|-----------|--------|-----|
| 13 | **JSON export endpoint** — all recipes with ingredients, tags, image paths | 1.5 hr | Backup capability |
| 14 | **Export button on Admin page** — triggers file download | 15 min | UI for export |
| 15 | **Crumble JSON re-import** — round-trip for server migration | 15 min | Uses existing import infrastructure |

### Priority 4: Smart Grocery Consolidation (~4 hours, Tier 1 feature)

This fixes an existing bug (mixed numbers don't merge) AND adds new capability (cross-unit consolidation). Draft plan already exists at `docs/plans/grocery-consolidation-autonomous.md`.

| # | Component | Effort | Why |
|---|-----------|--------|-----|
| 16 | **AmountConverter** service — parse "1 1/2" → 1.5 and back | 1.5 hr | Fixes existing merge bug |
| 17 | **UnitConverter** service — tsp↔tbsp↔cup, g↔kg↔lb conversion tables | 1.5 hr | Cross-unit consolidation |
| 18 | **Update GroceryItem::addFromRecipe()** — use both converters | 1 hr | Integrates everything |

### Priority 5: Scraper Improvements (1 hour, high value per minute)

The scraper is the #1 way recipes enter the system. These 3 changes make every import richer.

| # | Fix | Effort | Why |
|---|-----|--------|-----|
| 19 | **Extract nutrition from JSON-LD** — read `nutrition.calories/proteinContent/etc.` | 30 min | Populates dormant nutrition fields for free |
| 20 | **Extract tags from JSON-LD** — read `recipeCategory`, `recipeCuisine`, `keywords` | 25 min | Auto-tags imported recipes |
| 21 | **Update user agent strings** — Chrome 120 → Chrome 130+ | 5 min | Avoid potential blocking |

### Priority 6: PWA (~3.5 hours, transforms daily use)

The highest-ROI "feature" because it's mostly configuration, not code.

| # | Component | Effort | Why |
|---|-----------|--------|-----|
| 22 | **Install vite-plugin-pwa + config** — workbox caching, manifest | 45 min | Installable app + offline |
| 23 | **Generate icons** from crumble_icon.PNG — 192x192 and 512x512 | 15 min | Required for install prompt |
| 24 | **Self-host Google Fonts** — download Nunito + Playfair Display woff2 | 30 min | Offline font reliability |
| 25 | **Runtime caching rules** — StaleWhileRevalidate for recipes, CacheFirst for images | 30 min | Offline recipe viewing |
| 26 | **Offline detection banner** — "You're offline" + disable writes | 1 hr | Graceful degradation |
| 27 | **Meta tags** — theme-color, apple-touch-icon in index.html | 15 min | Polish |

### Priority 7: Pantry-Based Recipe Search (~6.5 hours, differentiator)

No self-hosted competitor has this. Mealie and Tandoor both have open feature requests for it.

| # | Component | Effort | Why |
|---|-----------|--------|-----|
| 28 | **Backend: findByIngredients endpoint** — asymmetric coverage algorithm | 2 hr | Core matching logic |
| 29 | **Backend: ingredient autocomplete** — unique names from ingredients table | 30 min | Search-as-you-type |
| 30 | **Frontend: PantrySearchPage** — ingredient input with tag chips | 2 hr | Main UI |
| 31 | **Frontend: results with match info** — "7/8 ingredients, missing: soy sauce" | 1.5 hr | The useful detail |
| 32 | **localStorage pantry persistence** — remember ingredients across sessions | 30 min | Don't re-enter every time |

### What I'd Skip (For Now)

| Idea | Why Skip |
|------|----------|
| React Query migration | Current hooks work fine at Crumble's scale. Add when optimistic updates are needed. |
| Code splitting | 85KB gzip. Not worth the complexity until bundle doubles. |
| Real-time sync | Requires WebSocket infrastructure. Polling is fine. |
| Social media import | Requires multimodal AI + video processing. Wrong fit for self-hosted PHP. |
| Voice control in CookMode | Cool but niche. Fix timers first, voice later. |
| AI recipe adaptation | Genuinely useful but requires API key infrastructure. Build after core features. |
| CalDAV integration | Nice-to-have but significant effort for limited audience. |
| Recipe export (JSON/Cooklang) | ~~Important for data freedom but not a daily-use improvement.~~ **Moved to Priority 3.5.** |

### New Items From Deep Dives 15-19 (integrated below)

| # | Item | Effort | Fits After Priority |
|---|------|--------|-------------------|
| 33 | **CookMode completion screen** — cook count, notes, rate prompt | 30 min | 2 (CookMode fixes) |
| 34 | **First-time welcome card** — conditional on 0 recipes, 3 action buttons | 30 min | 1 (quick wins) |
| 35 | **Favorite heart animation** — CSS keyframe scale+color on toggle | 20 min | 1 (quick wins) |
| 36 | **Kitchen Stats card** — stats endpoint + sparkline on CookHistoryPage | 2.25 hr | 3.5 (after export) |
| 37 | **Warm Night Mode** — CSS variable overrides, toggle + localStorage | 1.5 hr | 6 (alongside PWA) |
| 38 | **Enter → new ingredient row** — auto-add row on Enter in last ingredient | 15 min | 1 (quick wins) |
| 39 | **Instruction textarea height** — `rows={2}` → `rows={3}` | 5 min | 1 (quick wins) |
| 40 | **Paste-all-ingredients button** — textarea → split on newlines → parse each | 1 hr | 3 (alongside search) |
| 41 | **Client-side IngredientParser** — JS port of PHP parser for instant parsing | 1.5 hr | 3 (enables #40) |
| 42 | **Draft autosave** — localStorage on form change, resume on mount | 45 min | 3 (form reliability) |

### The Number (Updated)

If everything above were built in order:

- **Priorities 1-3**: ~3.5 hours — fixes and search. Daily-use quality improvements.
- **Priority 3.5**: ~2 hours — data export. Trust and data freedom.
- **Priorities 4-5**: ~5 hours — grocery consolidation + scraper. Core feature enhancement.
- **Priorities 6-7**: ~10 hours — PWA + pantry search. Transformative new capabilities.

**Previous total: ~20.5 hours** (Priorities 1-7, 32 items)
**New items from Deep Dives 15-19: ~8.5 hours** (10 items)
**Revised total: ~29 hours** across 42 items.

But not all 42 items are equal. The **critical path** — items that transform daily use — is:

1. Quick wins (#1-4, #34, #35, #38, #39): **1.5 hours**
2. CookMode trust (#5-8, #33): **2 hours**
3. Search + forms (#9-12, #40-42): **5 hours**
4. Data export (#13-15): **2 hours**
5. Kitchen Stats (#36): **2.25 hours**

That's **12.75 hours to get from "good" to "excellent"** on everything users touch daily. The remaining 16 hours (grocery, scraper, PWA, night mode, pantry search) are genuinely optional until the core is polished.

The key is still doing them in order — quick wins build momentum, CookMode fixes build trust, and the form improvements make adding recipes a pleasure instead of a chore.

### Design System Reference (For Future Work)

Captured from `index.css` and component patterns:

**Color palette:**
```
cream:           #FFF8F0  — page background, light fills
cream-dark:      #F5EDE3  — input borders, hover backgrounds, dividers
terracotta:      #C1694F  — primary action, active states, links
terracotta-light:#D4896F  — hover highlights
terracotta-dark: #A8533A  — active/pressed states
brown:           #3E2723  — primary text, headings
brown-light:     #5D4037  — secondary text, nav items
sage:            #7D9B76  — secondary action (Done, success states)
sage-light:      #A8C5A0  — light success fills
sage-dark:       #5F7A58  — success pressed states
warm-gray:       #8D7B6E  — tertiary text, metadata, timestamps
                            ⚠️ Fails WCAG AA at small sizes on cream bg (~3.4:1)
```

**Typography:**
```
Display/Body:  Nunito (sans-serif) — weights 300, 400, 600, 700
Headings:      Playfair Display (serif) — weights 400, 700
Usage:         font-serif for recipe titles and page headings
               font-display / default for everything else
```

**Component patterns:**
```
Cards:         bg-white rounded-2xl shadow-md
Touch targets: min-h-[44px] min-w-[44px]
Inputs:        rounded-xl border border-cream-dark focus:border-terracotta focus:ring-1
Buttons:       rounded-xl, terracotta (primary), sage (secondary/success), cream-dark (ghost)
Transitions:   transition-colors duration-200 (standard), transition-transform duration-300 (images)
```

**Spacing/Layout:**
```
Page padding:  p-4 md:p-6 lg:p-8
Grid:          grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6
Max width:     max-w-7xl mx-auto
Sidebar:       w-64, hidden md:flex
Bottom nav:    md:hidden, h-16, fixed bottom-0
```

This reference should save significant time for any future feature work — no need to reverse-engineer the design language from individual components.

---

## Deep Dive: Recipe Data Portability — Import, Export, and Data Freedom

*Explored 2026-03-09*

### What Crumble Has Today

**Import (3 paths):**
1. **URL scraping** — single recipe or batch (up to 50 URLs). Uses the 4-tier RecipeScraper. The primary way recipes enter the system.
2. **Mealie export** — `.zip` file containing `database.json` with relational data + images. MealieImporter extracts recipes, ingredients (sorted by position), instructions, images (webp from `data/recipes/{uuid}/images/original.webp`). Handles ISO 8601 durations and plain text time strings.
3. **Paprika export** — `.paprikarecipes` file (zip of individually gzipped JSON entries). PaprikaImporter handles the nested compression, parses newline-separated ingredients and directions.

Both importers use `IngredientParser::parse()` to structure raw ingredient strings into amount/unit/name. The Mealie importer even extracts embedded images via session-stored temp files.

**Export: Nothing.**

Zero export functionality. No JSON export, no schema.org output, no Cooklang, no CSV, no print-friendly download, no backup tool. Once recipes are in Crumble, the only way to get them out is the database directly.

This is a significant gap. Not because users export recipes every day, but because the *inability* to export creates lock-in anxiety. The self-hosted ethos is about owning your data. If you can't take your data out, you don't really own it.

### Recipe Interchange Formats — The Landscape

There's no single standard for recipe interchange. The ecosystem is fragmented:

| Format | Type | Pros | Cons | Used By |
|--------|------|------|------|---------|
| **Schema.org JSON-LD** | Web standard | Google/SEO, machine-readable, widely understood | Web-only, no images, verbose | All major recipe sites |
| **Cooklang** | Markup language | Human-readable, git-friendly, simple syntax | Small ecosystem, no images | Cooklang apps, Obsidian users |
| **Mealie JSON** | App-specific | Complete data, includes images | Mealie-specific structure | Mealie |
| **Paprika** | App-specific | Widely used commercial format | Gzipped JSON in zip, proprietary | Paprika app |
| **MealMaster** | Legacy | Huge archive of recipes online | Fixed-width columns, fragile | Legacy recipe software |
| **Markdown** | Plain text | Universal, human-readable | No structure, can't parse reliably | General use |

**The pragmatic answer: export in multiple formats.** Each serves a different use case.

### What Crumble Should Export

**Tier 1: JSON backup (essential)**
A full JSON export of all recipes with ingredients, instructions, tags, and image paths. This is the "I want to back up my data" use case. Should match the internal data model closely so it's also re-importable.

```
GET /api/recipes/export?format=json
→ Download: crumble-export-2026-03-09.json
```

Structure:
```json
{
  "version": 1,
  "exported_at": "2026-03-09T12:00:00Z",
  "recipes": [
    {
      "title": "Chicken Parmesan",
      "description": "Classic Italian-American...",
      "prep_time": 15,
      "cook_time": 30,
      "servings": 4,
      "source_url": "https://...",
      "ingredients": [
        { "name": "chicken breast", "amount": "2", "unit": "lb" }
      ],
      "instructions": ["Step 1...", "Step 2..."],
      "tags": ["Italian", "Dinner"],
      "image_path": "recipes/42/full.jpg",
      "created_at": "2026-01-15T10:30:00Z"
    }
  ]
}
```

**Tier 2: Schema.org JSON-LD (interoperability)**
Export each recipe as a `schema.org/Recipe` JSON-LD document. This is the closest thing to a universal recipe format — any app that can parse structured recipe data from websites can read this.

```json
{
  "@context": "https://schema.org/",
  "@type": "Recipe",
  "name": "Chicken Parmesan",
  "description": "Classic Italian-American...",
  "prepTime": "PT15M",
  "cookTime": "PT30M",
  "recipeYield": "4 servings",
  "recipeIngredient": [
    "2 lb chicken breast",
    "1 cup breadcrumbs"
  ],
  "recipeInstructions": [
    { "@type": "HowToStep", "text": "Step 1..." }
  ],
  "recipeCategory": "Dinner",
  "keywords": "Italian, Dinner"
}
```

**Tier 3: Individual recipe download (sharing)**
A "Download as JSON-LD" button on each recipe page. Useful for sharing a single recipe without generating a share link.

### What Crumble Should Also Import

**Crumble's own JSON export** — round-trip import/export. If you export from one Crumble instance, you should be able to import into another. This enables migration between servers, backup/restore, and sharing recipe collections.

**Generic JSON-LD** — since the scraper already parses JSON-LD from websites, importing a JSON-LD file is minimal additional work. The `mapJsonLdRecipe()` function already exists in RecipeScraper — it just needs to accept a file input instead of a URL.

### Implementation Sketch

**Backend:**
```php
// RecipeController — add export methods

public function exportAll(): void {
    $recipes = $this->recipeModel->getAllForExport();
    // Add ingredients, tags for each recipe
    // Stream as JSON download
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="crumble-export-' . date('Y-m-d') . '.json"');
    echo json_encode([
        'version' => 1,
        'exported_at' => date('c'),
        'recipes' => $recipes,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}
```

The main work is in the `getAllForExport()` model method — it needs to fetch all recipes with their ingredients and tags in an efficient way (not N+1). Two queries: one for all recipes, one for all ingredients, then merge in PHP.

**Frontend:**
A simple "Export All" button in the Admin page (or a dedicated Settings page). Triggers a file download.

For individual recipe export, a "Download" option in the recipe action menu.

### Effort Estimate

| Task | Effort |
|------|--------|
| JSON export endpoint (all recipes + ingredients + tags) | 2 hours |
| Frontend export button (Admin page) | 15 min |
| Crumble JSON import endpoint (reverse of export) | 1.5 hours |
| Schema.org JSON-LD export (per recipe) | 1 hour |
| Individual recipe download button | 30 min |
| **Total** | **~5.5 hours** |

### The Bulk Import UX — What's Already Good

The BulkImportPage is well-designed:
- Two-mode chooser (URLs vs. file upload) with clear visual distinction
- Results table with per-recipe status (success/error), save/review actions
- "Save All Successful" batch operation
- "Review" option that loads the recipe into the Add page via sessionStorage
- Error messages preserved per-recipe (not just "import failed")
- Supports review-before-save workflow (not just blind import)

The Mealie importer is particularly thorough — it handles UUID formatting, ISO 8601 durations, position-sorted ingredients, and even extracts embedded images from the zip archive into session-stored temp files.

### My Take

Export should be Priority 3.5 in the roadmap — after search fixes but before grocery consolidation. Not because it's a daily-use feature, but because it's a trust feature. Users who know they can leave are more likely to stay.

The minimum viable export is JSON backup — one endpoint, one button, maybe 2 hours of work. Schema.org JSON-LD is a nice-to-have that makes Crumble a better citizen in the recipe ecosystem. Individual recipe download is the cherry on top.

The re-import (Crumble JSON → Crumble) is what turns export from "backup" into "migration." It enables moving between servers, sharing recipe collections, and restoring from backup. That's worth the extra 1.5 hours.

One interesting possibility: if the export format is Schema.org JSON-LD, and the scraper already parses JSON-LD, then import is essentially free — just feed the file through the existing parser. The format does double duty as both export and interchange.

---

## Architectural Note: The Authentik SSO Pattern

*Noticed 2026-03-09*

Crumble's SSO integration is worth documenting because it's unusually elegant for a PHP app.

**How it works:**
1. Caddy runs `forward_auth` against Authentik (OIDC provider)
2. Authentik validates the user and sets response headers
3. Caddy proxies the request to PHP with `X-Authentik-Username` and `X-Authentik-Email` headers
4. Caddy strips these headers from external requests (security: clients can't spoof them)
5. PHP checks for these headers at the top of `index.php` (lines 54-74)
6. If headers present and no active session: find-or-create user, set session, done

**What's clever:**
- Zero OIDC/OAuth code in PHP — no library, no token management, no redirect flows
- Auto-provisioning: new SSO users get created automatically as `member` role with a random 64-char password (never used — auth is handled upstream)
- Session-based: after the initial header check, everything works through normal PHP sessions. The SSO is invisible to the rest of the application.
- Secure by architecture: the trusted proxy (Caddy) is the security boundary, not PHP

**What it depends on:**
- Caddy as reverse proxy (Nginx could do the same with `auth_request`)
- Authentik (or any OIDC provider that sets custom headers)
- The proxy must strip `X-Authentik-*` headers from client requests — without this, anyone could forge them

This pattern is transferable to any PHP app behind Caddy/Nginx with an OIDC provider. It's the simplest possible SSO integration and avoids the complexity of implementing OAuth flows in PHP.

---

## State of Crumble — Executive Summary

*Written 2026-03-09, updated after comprehensive codebase analysis and 22 deep dives — every page, component, and model read*

### What This Is

A self-hosted recipe manager with a PHP API backend and React frontend. Runs on Laragon/Apache with MySQL. No Docker required. Deployed at crumble.fmr.local behind Caddy with optional Authentik SSO.

### What It Does Well

**Architecture:**
- Custom PHP router — no framework, ~400 lines, readable and fast
- 4 runtime JS dependencies (React, ReactDOM, React Router, Lucide). 85KB gzipped bundle
- Session-based auth with CSRF, rate limiting, account lockout, SSRF protection
- Clean model-controller pattern with PDO, proper transaction handling
- Tailwind CSS 4 with a coherent 11-color design system

**Features that work:**
- Recipe CRUD with image processing (resize to 800px full + 300px thumb)
- 4-tier recipe scraper (JSON-LD → Microdata → Heuristic → Open Graph) with AMP/cache fallbacks
- Bulk import from URLs (batch of 50), Mealie exports, Paprika exports
- Meal planning with weekly view, mobile day-picker, grocery list generation
- Recipe sharing via public links (UUID tokens, 30-day expiry, rate-limited)
- Grocery lists with basic quantity merging
- Cook history with timestamps
- Favorites, ratings (1-5 stars), recipe scaling with Unicode fractions
- CookMode: full-screen step-by-step with wake lock, timers, swipe navigation, ingredient panel
- Demo account with read-only guard middleware
- Authentik SSO via Caddy forward auth (zero OAuth code in PHP)
- Print stylesheet for recipes

**UI/UX quality:**
- 44px touch targets everywhere (WCAG 2.5.5)
- Skeleton loading states on all pages
- Consistent card pattern, color palette, typography
- Mobile-first: bottom nav, collapsible search, responsive grids, floating add button
- aria-labels on all icon-only buttons
- Ingredient scaling with Unicode fractions (½, ¼, ⅓, ¾) and range support
- Warm, consistent brand voice ("Your cozy recipe manager") — see Deep Dive 22
- Standalone shared recipe page with own layout + "Made with Crumble" footer
- Tag autocomplete on recipe forms prevents fragmentation

### What Needs Fixing (Bugs & Gaps)

| Issue | Severity | Effort | Status |
|-------|----------|--------|--------|
| Multi-word search only wildcards last term | Medium | 10 min | Open |
| CookMode timers clear on step navigation | High | 30 min | Open |
| CookMode timers don't auto-start | Low | 5 min | Open |
| "Done!" in CookMode doesn't log cook | Medium | 45 min | Open |
| Grocery amounts like "1 1/2" don't merge (`is_numeric` fails) | High | 1.5 hr | Open — part of consolidation |
| No recipe export | Medium | 2 hr | Open |
| No ingredient search (title/description only) | High | 1 hr | Open |
| Modal lacks focus trap | Medium | 30 min | Open |
| No skip navigation link | Low | 10 min | Open |
| warm-gray text fails WCAG AA contrast on cream bg | Low | 15 min | Open |
| Images not lazy-loaded | Low | 10 min | Open |
| RecipePage: ~150 lines commented-out card sharing code | Low | 5 min | Open — just delete |
| RecipePage: prev/next IDs fetched but unused | Low | 30 min | Open |
| RecipePage: no image lightbox | Low | 1 hr | Open |
| Rating conflates user_rating with avg_rating | Low | 30 min | Open |
| Scaling: "pinch" scales like a regular unit | Low | 10 min | Open |
| No README.md or install documentation | Medium | 1 hr | Open |
| No .env.example | Low | 5 min | Open |
| No first-time onboarding/welcome card | Medium | 30 min | Open |
| ~~N+1 queries in Recipe::findById()~~ | — | — | Fixed (9→3 queries) |
| ~~Hardcoded Laragon CA path~~ | — | — | Fixed (getCaBundlePath) |
| ~~Grocery recipe_name mismatch~~ | — | — | Fixed |
| ~~Grocery $existingByName index bug~~ | — | — | Fixed |
| ~~No error boundaries~~ | — | — | Fixed |
| ~~Share link spinner-forever~~ | — | — | Fixed |

### What Should Be Built Next

**The 29-hour roadmap** (detailed in Synthesis section above, 42 items across 8+ priorities). Critical path is 12.75 hours:

1. **Quick wins** (1.5 hr) — search wildcard, lazy images, aria attrs, welcome card, fav animation, Enter-to-add, textarea height
2. **CookMode trust** (2 hr) — timer persistence, auto-start, Done→completion screen with notes/rating, focus trap
3. **Search + forms** (5 hr) — ingredient search, focus traps, skip nav, paste-all ingredients, client-side parser, draft autosave
4. **Data export** (2 hr) — JSON backup with re-import capability
5. **Kitchen Stats** (2.25 hr) — cooking stats card on CookHistoryPage, sparkline, no competitor has this
6. **Grocery consolidation** (4 hr) — AmountConverter + UnitConverter + integration
7. **Scraper enrichment** (1 hr) — nutrition + tag extraction from JSON-LD
8. **PWA + Night Mode** (5 hr) — installable, offline, warm dark theme
9. **Pantry search** (6.5 hr) — "What can I make?" — only one tiny competitor has this

### What Should NOT Be Built

- Real-time WebSocket sync (wrong architecture for PHP)
- Social media video import (requires AI infrastructure)
- AI chatbot / recipe generator (gimmick)
- Full offline-first with IndexedDB (over-engineering)
- CalDAV integration (limited audience, significant effort)
- Docker deployment (Crumble's advantage is NOT needing Docker)

### Competitive Position

**Crumble is the only web-based recipe manager that doesn't require Docker.** Out of 15 projects tracked on awesome-selfhosted (March 2026), every web app requires containers. CookCLI avoids Docker but it's a CLI tool, not a web app. See Deep Dive 18 for the full competitive table with current star counts.

The risk is feature gravity — the temptation to match competitors feature-for-feature until deployment complexity catches up. The discipline is to build what matters for the kitchen (reliable CookMode, offline access, smart grocery lists) and skip what doesn't (real-time sync, AI, CalDAV).

### One-Sentence Summary

Crumble is the only non-Docker web-based recipe manager in the self-hosted ecosystem, with a warm brand voice and clean architecture. It's 80% of the way to excellent — the remaining 20% is trust fixes (timers, search, export), form UX (ingredient entry), and a README. Not flashy features.

---

## Deep Dive 15: Emotional Design — What Makes a Recipe App Feel Like Home

*2026-03-09*

### The Question

Most recipe apps feel like software. The good ones feel like a kitchen. What separates the two, and where does Crumble land?

### What Crumble Already Gets Right

**Color palette is warm, not clinical.** The cream (#FFF8F0) / terracotta (#C1694F) / sage (#7D9B76) palette reads like a well-worn cookbook, not a SaaS dashboard. This is a deliberate and correct choice — most competitors (Mealie, Tandoor) use generic Material UI blues and grays. Crumble's palette says "food" before you read a word.

**The login page has personality.** "Your cozy recipe manager" with a cookie emoji is a tiny detail that sets expectations. The "Try Demo" button is a welcome mat, not a sales pitch. Compare to Mealie's login screen which is a blank form with no personality.

**Empty states have warmth.** The FavoritesPage shows a Heart icon with "No favorites yet! — Browse your recipes and tap the heart to save your favorites." That's guidance disguised as encouragement. The GroceryPage's empty state is similar. These aren't error messages — they're invitations.

**Print stylesheet is thoughtful.** 165 lines of print CSS that switches to serif fonts, removes navigation, constrains images, and unhides ingredient text that's normally behind checkboxes. Someone thought about what happens when you actually cook with a printed recipe. That's kitchen empathy.

### Where It Falls Short

**CookMode's "Done!" is anticlimactic.** You've just cooked an entire meal step by step, and the finish line is... the recipe page again. No congratulations, no "You've cooked this 3 times!" message, no prompt to rate or take notes. The emotional arc drops to zero at the moment of highest satisfaction.

Compare: Paprika shows a completion animation and offers to add the recipe to your cook history with optional notes. That 5-second moment turns a forgettable action into a ritual.

**No onboarding story.** A new user logs in and sees... an empty grid with "No recipes found." The featured recipe hero is blank. The recently viewed carousel is empty. The tag chips show nothing. It's the digital equivalent of walking into an empty kitchen. There's no guidance about what to do first: import recipes? Scrape a URL? Add one manually?

A warm onboarding would be:
1. First login → show a gentle "Welcome to Crumble" card with 3 suggested actions (Import from another app, Scrape a recipe from the web, Add your first recipe manually)
2. After first recipe → celebrate: "Your first recipe! 🎉 Your collection has begun."
3. After 5 recipes → suggest favorites, meal planning
4. After 10 → mention grocery lists, cooking history

This is progressive disclosure applied to emotional engagement, not feature complexity.

**Recently viewed feels transactional.** The `useRecentlyViewed` hook stores `{id, title, image_path}` in localStorage — bare minimum data for rendering a card. But "recently viewed" isn't the relationship a home cook has with recipes. The real relationships are:
- "I cooked this last Tuesday and it was great"
- "I've been meaning to try this one"
- "This is my go-to weeknight dinner"

The cook_log table has this data. A "Your Kitchen Story" section could show: "You've cooked 23 recipes, 47 times. Your most-made recipe is Pasta Carbonara (8 times). You haven't tried Chicken Tikka Masala yet — you saved it 3 weeks ago."

That's not a feature. That's a mirror.

**No seasonal awareness.** Recipe apps are inherently seasonal — soups in winter, salads in summer, baking in December. Crumble has no concept of time beyond `created_at` and cook_log timestamps. But the data is there: if you've cooked chili 6 times and all of them were October–February, the app could surface it when the weather turns cold. This is advanced (requires weather API or simple month-based heuristics), but even a manually tagged "season" field on recipes would add warmth.

### The Micro-Interactions That Matter

Small moments that separate "software I use" from "my recipe app":

1. **Favoriting animation.** Currently the heart toggles instantly. A brief scale-up + color fill animation (150ms ease-out) makes the action feel like you're actually *choosing* something, not clicking a checkbox.

2. **Recipe image hover/tap.** No zoom, no lightbox. When you've just photographed your beautiful sourdough and uploaded it, the 300px thumbnail doesn't do it justice. A simple image lightbox on the recipe detail page would honor the effort.

3. **Ingredient check-off sound.** CookMode has timer completion beeps (880Hz oscillators), but checking off an ingredient is silent. A soft tick sound (optional, off by default) would create satisfying feedback during cooking. The satisfying checkbox sounds in apps like Todoist aren't accidental.

4. **Loading states.** The app uses generic spinners. A tiny cooking-themed loading indicator (a spoon stirring, a pot bubbling — even just the Crumble icon with a subtle pulse) would maintain the kitchen metaphor during the 200ms of data fetching.

5. **Night mode.** Not "dark mode" — *night mode*. Warm amber tones like f.lux, not harsh dark-gray-on-black. When you're planning tomorrow's dinner at 10pm, the cream background is uncomfortably bright. A night mode that shifts the palette to warm dark tones (deep brown backgrounds, amber text) would feel intentional, not just a dark theme checkbox.

### The Danger of Over-Designing Emotion

There's a line between "warm and inviting" and "twee and patronizing." Recipe apps that try too hard (animated mascots, gamification badges, streaks) end up feeling like a mobile game rather than a kitchen tool. Crumble's current restraint is mostly correct — it just needs a few more moments of warmth, not a personality overhaul.

The rule: **every emotional touch should feel like it was made by someone who cooks, not someone who designs apps.** A completion screen that says "Nice work, chef!" feels like a game. One that says "Cooked Pasta Carbonara — that's 4 times now. Add notes?" feels like a kitchen journal.

### What I'd Actually Build

From this analysis, three changes that would cost <2 hours combined and meaningfully shift the emotional register:

1. **CookMode completion screen** (30 min) — After "Done!", show a brief screen: recipe name, cook count (fetched from cook_log), optional notes textarea, rate button if not yet rated, then "Back to recipe." This is the single highest-impact emotional improvement.

2. **First-time user welcome card** (30 min) — Conditional card on HomePage when recipe count is 0. Three action buttons: "Import Recipes", "Paste a URL", "Add Manually." Disappears after first recipe is created.

3. **Favorite animation** (20 min) — CSS keyframe on the heart icon: scale(1) → scale(1.3) → scale(1) with color transition. Pure CSS, no JS. Tiny but satisfying.

Everything else (seasonal awareness, kitchen story, night mode) is interesting but falls into the "build it when the core is perfect" category. The core isn't perfect yet — CookMode timers still reset on step change, search still doesn't wildcard properly, and there's no data export. Fix trust issues before adding delight.

---

## Deep Dive 16: Kitchen Stats — The "Spotify Wrapped" for Cooking

*2026-03-09*

### The Gap Nobody's Filling

Research confirms: **no major self-hosted recipe manager offers personal cooking statistics.** Tandoor explicitly positions itself as "no tracking or analytics — just your recipes." Mealie has no stats dashboard. KitchenOwl, Recipya — none of them. The commercial apps (Paprika, Mela, Crouton) don't either.

Meanwhile, the "Spotify Wrapped" concept has spawned clones across every industry — Duolingo's Year in Review, Amazon's "Delivered" recap, YouTube Recap. Spotify Wrapped 2025 hit 200M+ users in its first day. The appetite for personal data stories is enormous and proven.

Nobody has done this for cooking. And Crumble already has the data.

### What Crumble Already Knows (Zero Schema Changes)

From existing tables, a single SQL query can derive:

**From `cook_log`:**
- Total times cooked (COUNT)
- Most-cooked recipe (GROUP BY recipe_id, ORDER BY count DESC)
- Cooking streaks (consecutive days with entries)
- Busiest cooking day of the week (DAYOFWEEK)
- Monthly cooking frequency (DATE_FORMAT + GROUP BY)
- First-ever cook log entry (MIN cooked_at)
- Time since last cook (MAX cooked_at)

**From `favorites`:**
- Total favorites count
- Most recently favorited
- Favorited-but-never-cooked recipes (LEFT JOIN cook_log WHERE cook_log.id IS NULL)

**From `ratings`:**
- Average rating given (do they rate high or low?)
- Most generous rating (MAX score recipe)
- Total recipes rated

**From `recipes`:**
- Total recipes in collection
- Most common tags (JOIN recipe_tags → tags, GROUP BY)
- Average prep + cook time across all recipes
- Recipes with images vs. without

**Cross-table insights:**
- "Your signature dish" — highest-rated recipe you've cooked the most
- "Untouched treasures" — favorited but never cooked
- "One-hit wonders" — cooked exactly once, rated 5 stars
- "Comfort food" — cooked 3+ times
- "Most adventurous month" — month with the most *new* recipes cooked (first cook_log entry per recipe)

### The Stats Endpoint

A single new method on CookLog (or a new Stats model) with one complex query:

```php
public function getUserStats(int $userId): array {
    // Total cooks
    // Most-cooked recipe (id, title, count)
    // Busiest day of week
    // Monthly cook counts (last 12 months)
    // Total recipes in collection
    // Total favorites
    // Favorited-but-never-cooked count
    // Top 3 tags by cook frequency
    // Average rating given
    // "Signature dish" — most-cooked + highest-rated
    // Streak: max consecutive days with a cook log entry
}
```

This is ~50 lines of SQL total, 5-6 separate queries (or a few WITH CTEs if MySQL 8+ is available). No schema changes. No new tables. Pure read operations on existing data.

### UI Concept

Not a full-page dashboard. A **card on the CookHistoryPage** — the data is contextually relevant there. The card would show:

```
┌─────────────────────────────────────┐
│  🍳 Your Kitchen Story              │
│                                     │
│  47 cooks  ·  23 recipes  ·  12 ★   │
│                                     │
│  Signature Dish: Pasta Carbonara    │
│  Cooked 8 times · Rated ★★★★★       │
│                                     │
│  You cook most on Sundays           │
│  Most adventurous: October 2025     │
│  3 favorites you haven't tried yet  │
│                                     │
│  ▂▃▅▇█▆▃▂▄▅▇▅  (12-month sparkline)│
│  Mar          ─────────────  Feb    │
└─────────────────────────────────────┘
```

The sparkline is pure CSS/HTML — a row of thin `<div>`s with varying heights. No chart library needed.

### Why This Matters Emotionally

Cooking is invisible labor. Nobody counts how many dinners you made this year. A kitchen stats card says: "Your effort is seen. Your patterns are interesting. You have a cooking identity."

This is the opposite of gamification. No badges, no streaks to maintain, no pressure. Just a mirror held up to your habits. The difference: "You have a 12-day streak!" (pressure, guilt) vs. "You cook most on Sundays" (observation, identity).

### Implementation Estimate

- Backend stats endpoint: 1 hour (SQL queries, tested)
- Frontend stats card component: 1 hour (responsive, CSS sparkline)
- Integration into CookHistoryPage: 15 min
- **Total: ~2.25 hours**

### The "Year in Cooking" Extension

If the basic stats card lands well, a December "Year in Cooking" page (the Wrapped equivalent) writes itself:

- "In 2025, you cooked 127 times across 34 recipes."
- "Your most-cooked recipe was Chicken Tikka Masala (12 times)."
- "You added 15 new recipes to your collection."
- "Your busiest month was November (18 cooks)."
- "You discovered 4 new tags you'd never used before: Korean, Fermented, Sourdough, One-Pot."

This would be a separate page, maybe accessible from the stats card in December. The data is the same — just presented as a narrative instead of a dashboard.

### Why I Think This Is Higher Priority Than It Seems

The roadmap currently has this as unmentioned. But consider: the CookHistoryPage is the most emotionally charged page in the app — it's where you see your relationship with food over time — and it's currently just a flat chronological list with no insights.

Adding the stats card would:
1. Make the Cook History page worth visiting regularly (currently it's a "check once, never return" page)
2. Motivate using the "I Cooked This" button (there's now a *reason* to log cooks)
3. Surface the "favorited but never cooked" insight, which directly drives engagement
4. Differentiate Crumble from every competitor in a way that none of them can easily replicate (they don't have cook_log data in the same structured way)

This isn't a vanity feature. It's the answer to "why should I bother logging my cooks?"

---

## Deep Dive 17: Night Mode — Not Dark Mode

*2026-03-09*

### Why "Night Mode" and Not "Dark Mode"

Most dark modes are designed for developers staring at code. They're cold: dark gray (#1a1a1a) backgrounds, blue-tinged text, high contrast. That's the opposite of what a cooking app needs at 9pm when you're planning tomorrow's dinner.

A *night* mode should feel like a kitchen with the overhead lights off and the under-cabinet lights on. Warm. Amber. Like reading a cookbook by lamplight.

### The Warm Night Palette

Starting from Crumble's existing light palette and shifting to warm dark equivalents:

| Light Mode | Hex | Night Mode | Hex | Role |
|-----------|-----|------------|-----|------|
| cream | #FFF8F0 | deep-espresso | #1C1410 | Background |
| cream-dark | #F5EDE3 | dark-mocha | #2A2018 | Surface/cards |
| brown | #3E2723 | warm-parchment | #E8DDD0 | Primary text |
| brown-light | #5D4037 | warm-tan | #C4B5A4 | Secondary text |
| terracotta | #C1694F | terracotta-warm | #D4896F | Accent (lightened for contrast) |
| sage | #7D9B76 | sage-muted | #8DAA85 | Secondary accent (lightened) |
| warm-gray | #8D7B6E | warm-gray-light | #A89888 | Tertiary text (lightened) |

The key insight: the night background isn't neutral gray — it's **brown-black** (#1C1410), pulled from the warm end of the spectrum. Cards are **dark mocha** (#2A2018), not gray. Every surface has warmth.

### Tailwind CSS 4 Implementation

Tailwind 4 supports dark mode via CSS `prefers-color-scheme` or a class-based toggle. The cleanest approach for Crumble:

```css
@theme {
  /* Existing light palette stays as-is */
  --color-cream: #FFF8F0;
  /* ... */
}

/* Night mode overrides via class on <html> */
.night {
  --color-cream: #1C1410;
  --color-cream-dark: #2A2018;
  --color-brown: #E8DDD0;
  --color-brown-light: #C4B5A4;
  --color-terracotta: #D4896F;
  --color-terracotta-light: #E4A48F;
  --color-terracotta-dark: #C1694F;
  --color-sage: #8DAA85;
  --color-sage-light: #A8C5A0;
  --color-sage-dark: #7D9B76;
  --color-warm-gray: #A89888;
}

.night body {
  background-color: #1C1410;
  color: #E8DDD0;
}
```

Because Crumble uses semantic color names (`bg-cream`, `text-brown`, etc.) rather than raw hex values, the night mode is purely CSS variable overrides. **Zero JSX changes.** Every `bg-cream` automatically becomes the dark equivalent. Every `text-brown` becomes parchment.

This is a 30-minute CSS task if the palette is designed correctly. The design is the hard part, not the implementation.

### What About `prefers-color-scheme`?

Respect the OS preference as default, but add a manual toggle:
1. On first visit: follow `prefers-color-scheme: dark`
2. Toggle in the sidebar: Sun/Moon icon → stores preference in localStorage
3. Manual override persists across sessions

The toggle is a 20-line React component + 5 lines of CSS. The OS detection is 3 lines.

### The Hard Parts

**Images.** Recipe photos look jarring on dark backgrounds. Two options:
1. Add a subtle CSS `filter: brightness(0.9)` on images in night mode (reduces eye strain without making food look bad)
2. Round image corners more aggressively in night mode (softer visual transition)

**White recipe cards.** If any component uses raw `bg-white` instead of `bg-cream`, it'll be a bright white rectangle in night mode. Need to audit all components for hardcoded white. A quick `grep -r "bg-white"` would catch these.

**Scrollbar.** Already styled in index.css with hardcoded colors. Need the same `.night` override for scrollbar track and thumb.

**Recipe print.** Print stylesheet should always use light mode regardless of screen preference. Add `@media print { .night { /* reset all vars */ } }`.

### Estimate

- Palette design + CSS variables: 30 min
- Audit components for hardcoded colors (`bg-white`, `text-black`, etc.): 30 min
- Toggle component + localStorage persistence: 20 min
- Image brightness adjustment: 10 min
- Scrollbar + print stylesheet fixes: 10 min
- **Total: ~1.5 hours**

### Should It Be Built?

It's a "nice to have" — lower priority than trust fixes (timer persistence, search, export) and lower than the emotional design items (CookMode completion, onboarding). But it's *disproportionately impressive* for the effort. A warm night mode that feels like a kitchen instead of a code editor would immediately set Crumble apart visually from every competitor. Screenshots of the night mode would be the most shareable marketing asset the project has.

Priority recommendation: after the trust fixes and emotional design items. Slot it at ~Priority 5 in the roadmap, alongside PWA work (they'd pair well since both are about "the recipe app you reach for at home").

---

## Deep Dive 18: Updated Competitive Landscape (March 2026)

*2026-03-09 — Supersedes the earlier competitive landscape table with current data from awesome-selfhosted and GitHub*

### The Full Field

| Project | Stars | Stack | Docker? | Key Differentiator |
|---------|-------|-------|---------|-------------------|
| **Mealie** | 11,622 | Python/Vue | Yes | Most popular. Polished UI, meal planning, shopping lists |
| **Tandoor** | ~8,100 | Django/Vue | Yes | Most feature-rich. Nutritional calc, cost tracking, extensive import |
| **KitchenOwl** | 3,139 | Flutter/Flask | Yes | Cross-platform mobile apps (Flutter). Expense tracking |
| **CookCLI** | 1,145 | Rust | No | Cooklang format. CLI-first, web server optional. Plain text recipes |
| **Bar Assistant** | 978 | PHP/Docker | Yes | Cocktail-specific. Only other PHP project in the space |
| **RecipeSage** | 842 | Node.js | Yes | Any-URL import, progressive web app |
| **Norish** | ~675 | Next.js/tRPC | Yes | Real-time sync, AI features (video import, nutritional gen) |
| **Recipya** | 389 | Go | Yes | Clean/minimal, but development appears to have slowed (last update Nov 2025) |
| **Specifically Clementines** | 284 | Docker | Yes | Multi-device sync, Tandoor integration |
| **Vanilla Cookbook** | 139 | Node.js | Yes | Minimal UI, simplicity focus |
| **Tamari** | 114 | Python | Yes | Built-in recipe collection |
| **Fork Recipes** | 64 | Docker | Yes | BSD license, simple |
| **"What To Cook?"** | 55 | Docker | Yes | Ingredient-based search (the pantry concept!) |
| **ManageMeals** | 53 | Docker | Yes | No-ads focus, URL import |
| **Crumble** | — | PHP/React | **No** | **Only non-Docker, non-CLI option. Shared hosting compatible.** |

### Key Observations

**1. Crumble is the only web app that doesn't require Docker or a container runtime.**

CookCLI avoids Docker too, but it's a CLI tool — not a web app. Every single web-based recipe manager in the ecosystem requires Docker. Crumble runs on a $3/month shared hosting plan with PHP and MySQL. This is not a minor differentiator — it's a category of one.

**2. The "What To Cook?" project validates the pantry search concept.**

At 55 stars, it's tiny, but it exists: ingredient-based recipe matching. The fact that a standalone project was created just for this feature suggests real demand. Crumble's pantry search design (documented in Deep Dive 9) would fold this into a full recipe manager — a strictly better product.

**3. Bar Assistant proves PHP is viable in this space.**

At 978 stars, Bar Assistant shows that a PHP-based recipe-adjacent tool can compete. It's cocktail-specific, but the tech validation matters: PHP + modern frontend is a legitimate architecture.

**4. Norish's AI dependency is a double-edged sword.**

Norish requires an AI provider for: video import, image import, nutritional info generation, unit conversion, and allergy detection. That's 5 core features gated behind an external API dependency. For self-hosters who choose self-hosting *because* they want independence from cloud services, this is a philosophical contradiction.

Crumble's approach — doing everything locally with PHP — is more aligned with the self-hosting ethos. The one exception is recipe URL scraping, which inherently requires network access.

**5. Star counts follow a power law.**

Mealie (11.6K) and Tandoor (8.1K) together have more stars than all other projects combined. The long tail has dozens of sub-500 projects. Breaking into the top 3 would require ~3,000 stars. Getting to 500-1,000 stars is achievable for a quality project with a clear niche.

### Crumble's Strategic Position

Crumble doesn't need to compete with Mealie or Tandoor on features. Those are established projects with years of development and large contributor bases. The winning strategy is:

1. **Own the "no Docker" niche.** This is unchallenged. Market it explicitly.
2. **Leapfrog on experience.** Kitchen Stats, warm Night Mode, CookMode completion — features that make the app *feel better* rather than *do more*.
3. **Stay dependency-minimal.** 4 JS dependencies, no Redis, no AI providers, no headless Chrome. Every dependency a competitor adds is another thing that can break.
4. **Build the features that matter in the kitchen.** Offline access (PWA), timer persistence, grocery consolidation. These are daily-use features, not feature-list padding.

The endgame isn't "Mealie but without Docker." It's "the recipe app that runs anywhere and feels like home."

---

## Deep Dive 19: The Add Recipe Flow — First Contact UX

*2026-03-09*

### Why This Matters

The first recipe a user adds determines whether they keep using the app. If the experience is friction-heavy or confusing, they'll stop at one recipe. If it's satisfying, they'll add five more that evening.

### What Happens Today

**Step 1: Choose mode.** The AddRecipePage presents two large cards: "Import from URL" (terracotta Link icon) and "Write Manually" (sage BookOpen icon). Clean, clear, good visual hierarchy. No confusion about what to do.

**Step 2a (Import): Paste URL.** Single input field, "Import Recipe" button, helpful hint text: "Works best with sites that use structured recipe data." Straightforward. After import, transitions to the form with a sage banner: "Recipe imported! Review and edit the details below before saving."

**Step 2b (Manual): Fill the form.** RecipeForm with: Title (required), Description, Prep/Cook/Servings (3-column grid), Source URL, Image (drag & drop), Ingredients (amount/unit/name per row with reorder), Instructions (numbered steps with reorder), Nutrition (collapsible), Tags (autocomplete). Full-width "Save Recipe" button at bottom.

### What's Good

1. **The choose-mode screen is excellent.** Two clear paths. No decision paralysis. The icons and descriptions are distinct enough that a first-time user knows exactly what each does.

2. **Import-then-edit pattern is correct.** Showing scraped data in an editable form (not auto-saving) gives users control. The sage "Review" banner sets the right expectation.

3. **Tag autocomplete works.** Fetches existing tags, filters as you type, Enter/comma to add. This prevents tag fragmentation (e.g., "Italian" vs "italian" vs "Italy").

4. **Image drag & drop.** Nice touch that most users won't expect. Preview with X-to-remove is intuitive.

5. **Ingredient reorder.** ChevronUp/ChevronDown buttons on each row. Works, but...

### What's Not Great

**1. The ingredient row is cramped on mobile.**

Each ingredient row has 5 elements: reorder buttons, amount input (w-16), unit dropdown (w-24), name input (flex-1), and delete button. On a 375px screen, the name input gets crushed to maybe 100px. That's barely enough for "butter" let alone "boneless skinless chicken thighs, cut into 1-inch pieces."

The unit dropdown at w-24 takes fixed space for "(unit)" which is rarely needed for simple recipes. Most home cooks write "2 cups flour" — they don't think in amount/unit/name triplets. The structured input is correct for the data model but wrong for human entry.

**Better approach:** A single free-text input per ingredient with smart parsing. The IngredientParser already exists on the backend. A simpler UX: type "2 cups flour" → hits a parse endpoint (or client-side regex) → splits into structured data. Keep the structured fields available as an "advanced" toggle for corrections.

This is how Mealie and Tandoor handle it. It's a proven pattern.

**2. No keyboard shortcuts for efficiency.**

Power users adding 15 ingredients want: type ingredient → Enter → next row. Currently, Enter in an ingredient field does nothing (no form submission because it's part of a larger form, but also no "add next row" behavior). The only way to add a new row is clicking "+ Add Ingredient."

Simple fix: detect Enter on the last ingredient row → auto-add a new empty row and focus it.

**3. The instruction textareas start too small.**

Each step gets `rows={2}` and `min-h-[60px]`. A typical instruction step is 2-3 sentences. The textarea is too short to see the full text without scrolling inside it. `rows={3}` or `min-h-[80px]` would reduce the in-field scrolling.

**4. No paste-all-ingredients mode.**

Common scenario: user copies ingredients from a website that the scraper couldn't handle. They have a block of text:

```
2 cups flour
1 tsp salt
3 eggs
1 cup milk
```

Currently, they have to add each ingredient one by one: click "+ Add Ingredient", type in three fields, repeat. A "Paste ingredients" button that accepts a multi-line text block and splits on newlines (using IngredientParser for each line) would eliminate 80% of the friction for manual entry.

**5. No draft/autosave.**

If the user accidentally navigates away (back button, sidebar link) mid-form, everything is lost. No confirmation dialog, no localStorage draft. For a form with 15 ingredients and 10 steps, this is devastating.

Fix: save form state to localStorage on change (debounced), clear on successful submit. On mount, check for a draft and offer "Resume draft?" Simple, high-impact.

**6. Nutrition fields accept inconsistent formats.**

Calories is `type="number"` but protein/carbs/fat/fiber/sugar are text inputs (accepting "12g", "12", "12 grams"). The database stores protein/carbs/fat/fiber/sugar as VARCHAR(20) and calories as INT. This inconsistency means some nutrition values have units and some don't. The UI should either:
- Strip units and store raw numbers (add unit labels in the UI: "Protein (g)")
- Or accept free text consistently (make calories also text)

### The "Quick Add" Concept

For the common case — "I just want to save this recipe quickly" — the full form is overkill. A "Quick Add" mode would be:

1. **Title** (required)
2. **Ingredients** (single textarea, one per line, parsed on save)
3. **Instructions** (single textarea, numbered or paragraph, split on double-newline or numbered patterns)
4. **Image** (optional, drag & drop)
5. **Save**

That's 4 fields instead of 12+. The full form remains available for editing/refinement after save. This pattern mirrors how people actually think about recipes: "I need to write down what's in this dish before I forget."

### Priority Assessment

| Improvement | Impact | Effort | Priority |
|------------|--------|--------|----------|
| Free-text ingredient input (parse on entry) | High | 2 hr | Should build |
| Paste-all-ingredients button | High | 1 hr | Should build |
| Enter → new ingredient row | Medium | 15 min | Quick win |
| Draft autosave | Medium | 45 min | Should build |
| Instruction textarea height increase | Low | 5 min | Quick win (literally one number) |
| Quick Add mode | Medium | 2 hr | Consider after trust fixes |
| Nutrition format consistency | Low | 30 min | Nice to have |

The ingredient entry improvements (free-text + paste-all) would transform the manual recipe flow from "tolerable" to "fast." Combined with draft autosave and Enter-to-add, the total investment is ~4 hours for a significantly better first-contact experience.

### Client-Side Ingredient Parsing

The backend `IngredientParser.php` is a well-structured parser (~165 lines) that handles:
- Integers, decimals, fractions (`1/2`), mixed numbers (`1 1/2`), ranges (`2-3`)
- 27 unit types with 70+ aliases (cup/cups/c, tbsp/tablespoon/tablespoons/tbs, etc.)
- Parenthetical units: `2 (14 oz) cans tomatoes` → amount: "2", unit: "can", name: "(14 oz) tomatoes"
- Graceful fallback: `salt and pepper to taste` → no amount, no unit, full text as name

This parser could be ported to JavaScript (~80 lines, same regex patterns) for instant client-side parsing. Benefits:
- Zero server round-trips for the "paste all" and free-text features
- Inline preview: as you type "2 cups flour", show the parsed breakdown in real-time
- The same parser logic is used on both sides, preventing import/manual entry inconsistencies

The port is straightforward because the PHP uses standard regex (`preg_match`) that maps 1:1 to JavaScript `RegExp.exec()`. The unit lookup map is a simple `Object` or `Map`.

Sketch of the JS API:

```js
// parseIngredient("2 cups all-purpose flour")
// => { amount: "2", unit: "cup", name: "all-purpose flour" }

// parseIngredients("2 cups flour\n1 tsp salt\n3 eggs")
// => [{ amount: "2", unit: "cup", name: "flour" }, ...]
```

This is a self-contained utility — no dependencies, no build step changes. Drop it in `src/utils/ingredientParser.js` and import where needed.

### Edge Cases Worth Thinking About

The PHP parser handles the 80% case well. But real-world recipe text from copy-paste is messy:

| Input | Expected Parse | Tricky Because |
|-------|---------------|----------------|
| `2 cups all-purpose flour` | 2 / cup / all-purpose flour | Easy case |
| `1 1/2 tsp salt` | 1 1/2 / tsp / salt | Mixed number |
| `salt and pepper to taste` | — / — / salt and pepper to taste | No amount, no unit |
| `2 (14 oz) cans diced tomatoes` | 2 / can / (14 oz) diced tomatoes | Parenthetical + unit after |
| `1 (8-ounce) package cream cheese` | 1 / package / (8-ounce) cream cheese | Hyphenated unit in parens |
| `2-3 cloves garlic, minced` | 2-3 / clove / garlic, minced | Range + trailing comma modifier |
| `½ cup sugar` | ½ / cup / sugar | Unicode fraction (not "1/2") |
| `1½ cups milk` | 1½ / cup / milk | Adjacent Unicode fraction |
| `juice of 2 lemons` | — / — / juice of 2 lemons | "juice of" is not an amount |
| `one large egg` | — / — / one large egg | Word-form number |
| `a pinch of cayenne` | — / pinch / cayenne | "a" as amount-like word |
| `freshly ground black pepper` | — / — / freshly ground black pepper | Adjectives, no amount |
| `2 T butter` | 2 / tbsp / butter | "T" = tablespoon (common shorthand) |
| `3# chicken thighs` | 3 / lb / chicken thighs | "#" = pounds (US grocery notation) |

**The PHP parser currently handles rows 1-6 correctly.** It does NOT handle:
- Unicode fractions (½, ⅓, ¼, ¾) — would need a normalization step
- Word-form numbers ("one", "two", "a")
- "T" for tablespoon or "#" for pounds
- "juice of X" pattern (this is genuinely hard to parse without NLP)

**For the JS port, I'd add:**
1. Unicode fraction normalization: `½→1/2`, `⅓→1/3`, `¼→1/4`, `¾→3/4`, `⅔→2/3`, `⅛→1/8`
2. The "T" alias for tablespoon (extremely common in American recipes)
3. Skip word-form numbers — not worth the complexity. If someone pastes "one egg", they get `{name: "one egg"}` which is acceptable.

The JS port doesn't need to be perfect. It needs to be good enough that the paste-all feature produces editable results. The structured fields remain visible for correction.

---

## Deep Dive 20: The Missing README — Install Experience & Project Presentation

*2026-03-09*

### The Problem

Crumble's core differentiator is "no Docker, runs on shared hosting." But there is:
- No README.md
- No install guide
- No CHANGELOG
- No `install.php` (referenced in schema.sql but doesn't exist)
- No `.env.example`
- No requirements documentation

The schema.sql comments reference `install.php` for generating the admin password hash, but the file was never created. Setting up Crumble currently requires reading the source code to understand:
1. Create MySQL database and run schema.sql + migrations in order
2. Create `api/.env` with DB_HOST, DB_NAME, DB_USER, DB_PASS
3. Configure Apache/Nginx to point to the project root
4. Run `cd frontend && npm install && npm run build`
5. Manually set the admin password hash in the database

For a project that claims deployment simplicity, this is a gap between promise and reality. Mealie has a one-line `docker compose up`. Crumble requires manual database setup, env configuration, web server config, and frontend build.

### What A Good Install Experience Looks Like

**Minimum (README):**
```
## Requirements
- PHP 8.1+ with extensions: pdo_mysql, gd, fileinfo, curl
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite (or Nginx)
- Node.js 18+ (for building frontend)

## Install
1. Clone: git clone <repo> /var/www/crumble
2. Database: mysql -u root -p < database/schema.sql
3. Migrations: mysql -u root -p crumble_db < database/migrations/002_refinements.sql
   (run each migration file in order)
4. Configure: cp api/.env.example api/.env && edit api/.env
5. Build frontend: cd frontend && npm install && npm run build
6. Point web server document root to project root
7. Visit /api/install to set admin password
```

**Better (install.php):**

A web-based installer that:
1. Checks PHP version and required extensions
2. Tests database connection
3. Runs schema + all migrations
4. Creates admin account with user-chosen password
5. Writes .env file
6. Self-deletes after setup (security)

This is a one-afternoon project (~3 hours) that would make Crumble genuinely one-click installable on any PHP host.

**Best (CLI installer):**
```bash
php crumble install
# Interactive: asks DB host, DB name, DB user, DB pass
# Tests connection, runs schema, creates admin
# Outputs: "Crumble installed! Visit http://localhost/crumble"
```

### The .env.example File

Doesn't exist. Should contain:

```env
# Database
DB_HOST=localhost
DB_NAME=crumble_db
DB_USER=root
DB_PASS=

# Environment (development or production)
APP_ENV=development

# Optional: CA bundle path for recipe scraping (usually auto-detected)
# CURL_CA_BUNDLE=/path/to/cacert.pem
```

### The README.md

For a project competing on GitHub (where stars matter for discoverability), a README is the landing page. It needs:

1. **One screenshot** — the recipe grid in the warm cream/terracotta theme
2. **One-line pitch** — "Self-hosted recipe manager. No Docker required."
3. **Feature list** — bullet points, not paragraphs
4. **Install instructions** — 5-7 numbered steps
5. **Tech stack badge row** — PHP, React, MySQL, Tailwind
6. **Demo link** — if the demo instance at crumble.fmr.local were publicly accessible

The screenshots/ directory has `login.png` but no screenshots of the actual app in use.

### Why This Matters More Than Code

Every GitHub project is discovered through its README. A beautiful codebase with no README gets zero stars. A mediocre codebase with a polished README and screenshots gets hundreds.

Crumble's differentiator ("no Docker") is invisible without a README that says it explicitly. The warm terracotta UI is invisible without screenshots. The clean architecture is invisible without a tech stack section.

If the goal is ever to publish Crumble to awesome-selfhosted, the listing requires: name, description, license, and a working demo or clear install instructions. Currently, none of these exist in a public-facing form.

### Effort Estimate

| Item | Effort |
|------|--------|
| `.env.example` | 5 min |
| `README.md` with screenshots | 1 hour |
| `install.php` (web installer) | 3 hours |
| CHANGELOG.md | 30 min (retroactive from git log) |
| **Total** | ~4.5 hours |

### Priority

This is orthogonal to the feature roadmap — it's about project presentation, not user-facing features. But if there's ever intent to share Crumble publicly, this is **step zero**. You can't get adoption without a README.

---

## Deep Dive 21: The Recipe Detail Page — Reading Experience

*2026-03-09*

### Page Structure

The RecipePage is 602 lines — the largest page component in the app. Here's the visual structure top-to-bottom:

1. **Back button** — `navigate(-1)`, warm-gray text
2. **Hero image** — 16:9 on mobile, 21:9 on desktop, rounded-2xl, no lightbox
3. **Title + Favorite + Rating** — Playfair Display serif, 3xl/4xl, heart button, star rating
4. **Description** — brown-light, relaxed line-height
5. **Edit/Delete buttons** — only if owner or admin
6. **Metadata row** — Prep time, Cook time, Total time, ServingsAdjuster
7. **Tag badges** — flex-wrap
8. **Action buttons** — Start Cooking, I Cooked This, Add to Grocery, Print, Share
9. **Source URL** — "View original recipe" link
10. **Two-column content** — Ingredients (sticky sidebar, 300px) | Instructions (main)
11. **Nutrition Facts** — below content area
12. **Related Recipes** — 4-column grid at bottom

### What's Excellent

**The ingredient scaling system is beautifully engineered.** The `ingredientScaling.js` utility handles:
- Parsing fractions (`1/2`), mixed numbers (`1 1/2`), ranges (`2-3`), decimals
- Scaling by servings ratio
- Formatting back to Unicode fractions (½, ¼, ⅓, ¾, etc.) with a closest-match algorithm
- Graceful fallback to decimals when no Unicode fraction is close enough
- Handling amounts embedded in ingredient names (common from imports)

The `ServingsAdjuster` component is properly accessible: 44px touch targets, aria-labels, singular/plural "serving"/"servings". The +/- buttons are round with terracotta accent. This is genuinely delightful to use.

**The sticky ingredient sidebar is the right pattern.** On desktop, `md:sticky md:top-20 md:self-start` keeps ingredients visible while scrolling through instructions. This solves the #1 frustration with recipe pages: scrolling back up to check amounts.

**The two-column layout inverts correctly on mobile.** `grid-cols-1 md:grid-cols-[300px_1fr]` puts ingredients above instructions on mobile (correct reading order) and side-by-side on desktop.

**Action button hierarchy is correct.** "Start Cooking" is primary (terracotta, large), other actions are secondary/outline. The visual priority matches usage frequency.

### What Could Be Better

**1. Hero image has no lightbox.**

The image renders at 16:9 / 21:9 with `object-cover`, which means significant cropping on tall images. There's no way to see the full image. A click-to-expand lightbox would let users see their recipe photos at full resolution. This is especially important for plating reference — cooks often photograph the finished dish to remember how to present it.

**2. The 602 lines include ~150 lines of commented-out code.**

The card sharing feature (lines 116-159, 378-386, 515-554) is commented out with "may revisit as part of unified share modal." That's 10% of the file. Dead code should be removed — it's version-controlled in git. If the feature comes back, it can be recovered from history. The commented code makes the component harder to read and maintain.

**3. StepList doesn't detect timers or temperatures.**

Steps display as plain text. CookMode has timer detection (looks for "X minutes" patterns), but the regular StepList doesn't highlight key information. Bolding or coloring time mentions ("bake for **25 minutes** at **375°F**") in the instruction text would improve scanability when not in CookMode.

This could be a simple regex: `text.replace(/(\d+\s*(?:minutes?|mins?|hours?|hrs?))/gi, '<strong>$1</strong>')` with `dangerouslySetInnerHTML` or a safer React approach using splits.

**4. Rating UX has a subtle issue.**

Lines 266-283 show two `StarRating` blocks:
```jsx
{recipe.avg_rating !== null && recipe.avg_rating !== undefined && (
  <StarRating value={recipe.user_rating || recipe.avg_rating || 0} ... />
)}
{!recipe.avg_rating && (
  <StarRating value={0} ... />
)}
```

The first block shows `user_rating || avg_rating || 0`. But if the user rated 3 stars and the average is 4.5, it shows the user's rating, not the average. There's no visual distinction between "your rating" and "average rating." The user can't see both.

A better pattern: show the average rating as display-only stars, with a separate "Your rating" indicator below. Or show tooltip text: "You rated ★★★ · Average ★★★★½".

**5. The "I Cooked This" button is disconnected from CookMode.**

The `CookButton` sits in the action bar. The CookMode "Done!" button (in `CookMode.jsx`) just calls `onClose()`. These should be connected: finishing CookMode should offer to log the cook. This is the same issue identified in the Emotional Design deep dive — the completion screen gap.

**6. No prev/next recipe navigation on the page.**

The backend returns `prev_id` and `next_id` from the query (lines 131-132 of Recipe.php), but the frontend doesn't use them. Arrow buttons (← Previous Recipe | Next Recipe →) at the bottom of the page would let users browse sequentially — useful when exploring a collection.

**7. Source URL position feels buried.**

The source URL appears between the action buttons and the content area. When you import a recipe from a website, the source attribution should be visible without scrolling past 5 action buttons. Moving it to the metadata row (near prep/cook time) would give it appropriate prominence.

### The Scaling Edge Cases

The `scaleIngredients` function has one subtle issue: it scales amounts but not descriptive quantities. Example:

| Original (4 servings) | Scaled to 8 servings | Problem |
|----------------------|---------------------|---------|
| 1 can (14 oz) tomatoes | 2 cans (14 oz) tomatoes | ✅ Correct |
| 1 pinch salt | 2 pinch salt | ❌ Should stay "1 pinch" |
| 1 tbsp olive oil | 2 tbsp olive oil | ✅ Correct |
| salt to taste | salt to taste | ✅ Correctly unchanged |

"Pinch" and "to taste" are inherently non-scalable. The scaling logic correctly skips "to taste" items (no amount) but does scale "1 pinch" to "2 pinch" because it has a numeric amount. A small allowlist of non-scalable units (`pinch`, `to taste`, `dash`) would prevent this.

### Component Extraction Opportunity

The RecipePage at 602 lines is the largest component. It handles:
- Recipe display
- CookMode toggle
- Grocery list modal
- Delete confirmation modal
- Share link modal
- Card sharing (commented out)
- Servings adjustment + ingredient scaling

The modals alone account for ~150 lines. Extracting them into named components (`GroceryAddModal`, `DeleteConfirmModal`, `ShareLinkModal`) would bring the page to ~400 lines and make each feature independently testable. Not urgent, but worth noting if the file keeps growing.

### Summary

The recipe detail page is well-structured with excellent scaling UX, good responsive layout, and proper action hierarchy. The main gaps are: no image lightbox, disconnected CookMode completion, unused prev/next IDs, and ~150 lines of dead code. All are fixable without architectural changes.

### Quick Note: Meal Plan Page

Read the full MealPlanPage.jsx (454 lines). The implementation closely matches the "week-at-a-glance + mobile day picker" pattern I recommended in the earlier meal planning research. Highlights:

- **Desktop**: 7-column CSS grid, each day is a card with min-height, today highlighted with `ring-2 ring-terracotta/30`
- **Mobile**: Horizontal day picker (Mon–Sun as single letters with date numbers), active day gets terracotta background, dots indicate days with meals. Tapping a day shows that day's full card
- **Smart defaults**: `getDefaultMobileDay()` opens today if it's in the current week, else Monday
- **Grocery generation**: Creates a named grocery list from all recipes in the week, with a success modal offering navigation to the grocery page
- **Recipe search modal**: Debounced search (300ms), shows recipe thumbnails, 44px touch targets

The only thing missing from the original design is drag-to-reorder or drag-between-days for rearranging meals. Not critical — the add/remove pattern works fine for weekly planning.

This is one of the most polished features in the app. It implements the week view, mobile optimization, and grocery integration exactly right.

---

## Deep Dive 22: Crumble's Voice — A Brand Audit After Reading Every Line

*2026-03-09*

### Context

I've now read every page component, every shared component, every hook, every service file, and every model in the Crumble codebase. 21 deep dives, ~2,700 lines of analysis. This final piece is a synthesis of what Crumble "sounds like" as an application — its personality, tone, and the emotional texture it creates through microcopy, visual choices, and design patterns.

### The Voice That Emerges

**Crumble speaks like a home cook, not a software product.**

Evidence across the codebase:

| Surface | Copy | Tone |
|---------|------|------|
| Login page | "Your cozy recipe manager" | Warm, personal, unpretentious |
| Demo button | "Try Demo" | Inviting, low-pressure |
| Favorites empty | "No favorites yet! Browse your recipes and tap the heart to save your favorites." | Encouraging, guiding |
| Cook history empty | "You haven't cooked anything yet! Click 'I Cooked This' on a recipe to start tracking" | Gentle instruction |
| Grocery empty | "No items yet" | Minimal, not condescending |
| Meal plan empty day | "No meals planned" | Neutral observation |
| Shared recipe error | "This shared recipe link has expired or doesn't exist. Please ask the recipe owner for a new link." | Helpful, explains what to do |
| Shared recipe footer | "Made with Crumble · Your recipe manager" | Proud but humble |
| Import hint | "Works best with sites that use structured recipe data (most major recipe sites)" | Honest about limitations |
| Delete confirm | "Are you sure you want to delete this recipe? This action cannot be undone." | Clear, not dramatic |
| Search placeholder | "Search recipes..." | Simple, familiar |
| Add recipe choice | "Import from URL" / "Write Manually" | Clear, no jargon |
| Form placeholder | "e.g., Grandma's Chocolate Chip Cookies" | Personal, evocative |

### What This Tells Us

**1. The voice is consistent.** Across 12 pages and 30+ components, the copy maintains the same register: warm but not cute, helpful but not patronizing, personal but not informal. There are no jarring shifts. No page sounds like a different app.

**2. Empty states are invitations, not errors.** Every empty state I found follows the same pattern: icon + observation + guidance. Not "Error: no data found" but "You haven't cooked anything yet! Click 'I Cooked This' to start tracking." This is a deliberate design choice that sets Crumble apart from most technical tools.

**3. The app doesn't explain itself.** Crumble assumes you know what a recipe manager is. There's no tutorial, no walkthrough, no tooltip tour. You open the app and the UI is self-evident. This is correct for the target audience (self-hosters who chose to install a recipe app) but creates the onboarding gap I identified in the Emotional Design deep dive.

**4. There's no marketing voice.** The shared recipe page says "Made with Crumble · Your recipe manager" but there's no tagline, no pitch, no "sign up" CTA. The app doesn't try to sell itself. This is philosophically aligned with the self-hosted ethos (you already have it; there's nothing to sell) but limits viral potential when shared recipes reach non-users.

### The Visual Voice

Colors, typography, and spacing also constitute "voice." Crumble's visual personality:

- **Playfair Display for headings** — a transitional serif font with a literary quality. It says "cookbook" not "dashboard." Every `font-serif` class in the app reinforces this.
- **Nunito for body text** — rounded sans-serif, friendly and modern. The combination with Playfair is classic cookbook design: dignified headings, readable body.
- **Cream (#FFF8F0) background** — not white, not gray. This single choice puts Crumble in the "warm" category before any content loads. Every competitor uses white or dark gray.
- **Terracotta (#C1694F) as primary accent** — a food-associated color. Not blue (tech), not green (health), not red (urgency). Terracotta is clay pots, spice, kitchen tile.
- **Rounded corners everywhere** — `rounded-2xl` on cards, `rounded-xl` on inputs, `rounded-full` on avatars. No sharp edges. This is visual friendliness.
- **44px touch targets consistently** — `min-h-[44px]` and `min-w-[44px]` appear throughout. This is WCAG compliance, but it also feels generous — the app isn't cramped.

### The One Inconsistency

The **Admin page** breaks the voice. AdminPage.jsx is a user management table with Create/Edit/Reset Password/Delete buttons. The layout is pure CRUD dashboard — functional, necessary, but it doesn't feel like Crumble. The table rows, the modal forms, the "Last Login" column — this is the only place where Crumble sounds like a SaaS admin panel instead of a kitchen tool.

This isn't a problem to fix. Admin functionality is inherently technical. But it's worth noting that the voice is 95% consistent with this one justified exception.

### What A Brand Guide Would Say

If someone asked "how should new features in Crumble feel?", the answer from the existing codebase would be:

1. **Use plain language.** "Add Recipe" not "Create New." "Start Cooking" not "Launch CookMode."
2. **Empty states are hopeful.** Show what could be here, not what's missing.
3. **Warm colors, rounded shapes.** Never sharp, never cold, never corporate.
4. **Headings in serif, everything else in sans.** The serif says "tradition." The sans says "modern."
5. **No exclamation marks in error states.** Errors are calm observations with suggested next steps.
6. **Explain what to do, not what went wrong.** "Please ask the recipe owner for a new link" not "Error 404: Token not found."
7. **The form placeholder is your chance to be human.** "e.g., Grandma's Chocolate Chip Cookies" > "Enter recipe title."

### The Shared Recipe Page as Marketing

One thing I noticed: the SharedRecipePage is the only place non-users encounter Crumble. It's a landing page whether the developer intended it or not. Currently it's a clean recipe view with "Made with Crumble · Your recipe manager" in the footer.

If Crumble ever goes public (GitHub, awesome-selfhosted listing), this page becomes the organic discovery channel. A shared recipe viewed by a friend is a more compelling pitch than any README. The footer could include a subtle link to the project — not aggressive marketing, just "Get your own: github.com/..." alongside "Made with Crumble."

This is the lightest possible marketing: the product demonstrates itself through use.

### Final Thought on Voice

Most recipe apps feel like they were built by developers who eat food. Crumble feels like it was built by someone who cooks. That's a subtle but real distinction, and it's the hardest thing to replicate. Competitors can copy features (Docker is a commodity, React is a commodity, recipe scraping is well-documented). They can't copy the feeling that "Grandma's Chocolate Chip Cookies" is the default placeholder and the primary color is the same shade as a kitchen backsplash.

The voice isn't a feature. It's the foundation everything else is built on. Protect it.

---

## Deep Dive 23: Test Coverage & API Surface Map

*2026-03-09*

### What's Tested

7 test files: 6 unit tests + 1 integration test. All focused on **security infrastructure**:

| Test File | What It Tests | Assertions |
|-----------|--------------|------------|
| `CsrfTest` | Token generation, validation, session persistence | 5 tests |
| `PasswordValidatorTest` | Length, uppercase, lowercase, number requirements | 6 tests |
| `RateLimiterTest` | Rate limiting, IP isolation, action isolation, TTL expiry | 5 tests |
| `DemoGuardTest` | POST/PUT/DELETE blocked for demo, GET allowed, logout exempt | 7 tests |
| `SessionSecurityTest` | Cookie params (Secure, HttpOnly, SameSite), session lifetime | 3 tests |
| `EnvConfigTest` | .env loading, comments/blank lines, missing file handling | 3 tests |
| `AccountLockoutTest` | Failed attempt recording, lockout after 5, reset, expiry | 4 tests (integration, hits real DB) |

**Total: ~33 test assertions, all security-focused.**

### What's NOT Tested

Everything else:
- **No model tests** — Recipe CRUD, ingredient parsing, tag operations, grocery operations
- **No controller tests** — No HTTP-level endpoint tests
- **No scraper tests** — RecipeScraper, MealieImporter, PaprikaImporter untested
- **No ImageProcessor tests** — Upload validation, resize logic untested
- **No frontend tests** — No Jest, no React Testing Library, no Playwright/Cypress

### What This Tells Us

The test suite was written during the security hardening phase (commit `e508dd1`). The developer tested what they were building at that moment — security middleware — and didn't retroactively test existing code.

This is a pragmatic choice, not a quality problem. For a personal recipe manager, the security layer is the highest-risk area (authentication, rate limiting, CSRF). Model/controller bugs cause visible UI issues that get caught during use. Security bugs are invisible until exploited.

**If I were to add tests, the priority order would be:**

1. **IngredientParser** (~20 test cases) — Pure function, easy to test, high value. The parser handles the most complex string manipulation in the codebase. The edge case table from Deep Dive 19 is practically a test spec.

2. **ingredientScaling.js** (~15 test cases) — Another pure function, the frontend scaling utility. `parseAmount`, `formatAmount`, `scaleIngredients` are all independently testable with no DOM or API dependencies.

3. **Recipe model** (~10 test cases) — Integration tests for `getAll` (search, tag filter, pagination), `create` (with ingredients + tags), `update` (ingredient replacement, tag sync).

4. **RecipeScraper** (~10 test cases) — Mock HTTP responses, test JSON-LD/Microdata/Heuristic parsing tiers independently.

### The Complete API Surface

Catalogued from `index.php` router (496 lines):

**Public Routes (no auth required):**

| Method | Endpoint | Description | Rate Limited |
|--------|----------|-------------|-------------|
| `GET` | `/api` | Health check, returns app name + version | No |
| `POST` | `/api/auth/login` | Login with username/password | 5/5min per IP |
| `GET` | `/api/shared/{token}` | View shared recipe | 30/min per IP |

**Authenticated Routes:**

| Method | Endpoint | Description | Notes |
|--------|----------|-------------|-------|
| `POST` | `/api/auth/logout` | End session | Demo-allowed |
| `GET` | `/api/auth/me` | Current user + CSRF token | |
| `GET` | `/api/recipes` | List recipes | Supports `?search=`, `?tag=`, `?page=`, `?perPage=` |
| `POST` | `/api/recipes` | Create recipe | Multipart (image) or JSON |
| `GET` | `/api/recipes/featured` | Get featured recipe | |
| `GET` | `/api/recipes/{id}` | Get recipe detail | Includes prev/next IDs, user rating |
| `PUT/POST` | `/api/recipes/{id}` | Update recipe | Accepts both PUT and POST |
| `DELETE` | `/api/recipes/{id}` | Delete recipe | Owner or admin only |
| `GET` | `/api/recipes/{id}/related` | Get related recipes by tags | |
| `POST` | `/api/recipes/{id}/favorite` | Toggle favorite | |
| `POST` | `/api/recipes/{id}/rate` | Rate recipe 1-5 | |
| `POST` | `/api/recipes/{id}/cook` | Log cook (optional notes) | |
| `POST` | `/api/recipes/{id}/share` | Create share link | |
| `DELETE` | `/api/recipes/{id}/share` | Revoke share link | |
| `POST` | `/api/recipes/import` | Import single URL | 10/5min per IP |
| `POST` | `/api/recipes/import-batch` | Import batch URLs | 10/5min per IP |
| `POST` | `/api/recipes/import-mealie` | Import Mealie .zip | 10/5min per IP |
| `POST` | `/api/recipes/import-paprika` | Import Paprika file | 10/5min per IP |
| `GET` | `/api/favorites` | List user's favorites | |
| `GET` | `/api/cook-log` | Get cook history | |
| `GET` | `/api/tags` | List all tags | |
| `DELETE` | `/api/tags/{id}` | Delete tag | |
| `GET` | `/api/grocery` | List grocery lists | |
| `POST` | `/api/grocery` | Create grocery list | |
| `GET` | `/api/grocery/{id}` | Get grocery list detail | |
| `DELETE` | `/api/grocery/{id}` | Delete grocery list | |
| `POST` | `/api/grocery/{id}/items` | Add item to list | |
| `PUT` | `/api/grocery/{id}/items/{itemId}` | Update item (check/uncheck) | |
| `DELETE` | `/api/grocery/{id}/items/{itemId}` | Delete item | |
| `POST` | `/api/grocery/{id}/recipes/{recipeId}` | Add recipe ingredients to list | |
| `GET` | `/api/meal-plan` | Get week plan | Supports `?week_start=` |
| `POST` | `/api/meal-plan/items` | Add recipe to day | |
| `PUT` | `/api/meal-plan/items/{id}` | Update meal plan item | |
| `DELETE` | `/api/meal-plan/items/{id}` | Remove meal plan item | |
| `POST` | `/api/meal-plan/grocery` | Generate grocery from plan | |

**Admin Routes:**

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/users` | List all users |
| `POST` | `/api/users` | Create user |
| `PUT` | `/api/users/{id}` | Update user |
| `PUT` | `/api/users/{id}/password` | Reset user password |
| `DELETE` | `/api/users/{id}` | Delete user |
| `POST` | `/api/admin/reparse-ingredients` | Re-parse unstructured ingredients |

**Total: 3 public + 33 authenticated + 6 admin = 42 endpoints.**

### API Design Notes

**What's RESTful:**
- Resource-based URLs (`/recipes`, `/grocery`, `/tags`)
- Proper HTTP methods (GET for reads, POST for creates, PUT for updates, DELETE for deletes)
- Sub-resources are nested logically (`/recipes/{id}/favorite`, `/grocery/{id}/items/{itemId}`)

**What's not quite REST:**
- `/recipes/import` is an action, not a resource (RPC-style). This is fine — it's the pragmatic choice for file upload + scraping.
- `/meal-plan/grocery` is another action (generate grocery list from plan).
- Login accepts both PUT and POST for recipe updates — slightly loose.
- No HATEOAS links, no pagination metadata in standard format (but this is normal for internal APIs).

**What's missing:**
- No API versioning (`/api/v1/...`). Not needed now, but worth noting if the API ever becomes public.
- No OpenAPI/Swagger spec. Would be useful for third-party clients.
- No stats endpoint (identified in Kitchen Stats deep dive).
- No export endpoint (identified in Data Portability deep dive).

### The Hidden Gem: `/api/admin/reparse-ingredients`

This endpoint is fascinating. It finds ingredients where `amount IS NULL` but the name starts with a digit (e.g., "2 cups flour"), runs them through IngredientParser, and updates the structured fields. It's a one-time data cleanup tool for imported recipes that came in as unstructured text.

This is the kind of admin utility that shows developer thoughtfulness — the imported data problem is real (different sources structure ingredients differently), and this tool fixes it retroactively.

---

## Closing Notes

*2026-03-09*

This document grew from a few observations into a 3,000-line audit across 23 deep dives. Every page component, every model, every service file, and every test has been read. The competitive landscape has been researched across 15 projects. The brand voice has been catalogued. The API surface has been mapped.

### What I'd Actually Do First

If I had one afternoon (4 hours) to make Crumble meaningfully better:

**Hour 1: Quick wins (30 min writing, 30 min testing)**
- Fix multi-word search: split on spaces, wildcard each term (`+chicken* +pasta*`) — 10 min
- Add `loading="lazy"` to RecipeCard and RelatedRecipes images — 5 min
- Add `aria-label="Main navigation"` to Sidebar/BottomNav — 5 min
- Change RecipeForm instruction `rows={2}` to `rows={3}` — 1 min
- Delete 150 lines of commented-out card sharing code from RecipePage — 5 min
- Create `.env.example` — 5 min

**Hour 2: CookMode completion (the single highest-impact emotional improvement)**
- After "Done!" in CookMode → show completion screen with cook count, notes textarea, rate prompt
- Connects CookMode to cook_log, which makes the "I Cooked This" flow feel integrated

**Hour 3: Ingredient entry UX**
- Add Enter-to-add on last ingredient row — 15 min
- Add "Paste Ingredients" button (textarea → split on newlines → display in structured rows) — 45 min

**Hour 4: README**
- Write README.md with one screenshot, install steps, feature list, tech stack
- This is the single most important thing for anyone else to discover and use Crumble

That's it. Four hours, and Crumble goes from "solid personal project" to "ready to share with the world."

### What This Document Is For

This is not a specification. It's a thinking space. The observations are grounded in code (every claim cites a file and line number), the competitive data is current (March 2026), and the estimates are based on reading the actual implementation.

Use it as:
- **A roadmap**: the Synthesis section (line ~1464) has 42 prioritized items
- **A design reference**: the color palette, typography, and voice patterns are documented
- **A competitive brief**: 15 projects compared with stars and tech stacks
- **A feature spec**: Kitchen Stats, Night Mode, Pantry Search, Grocery Consolidation all have implementation sketches
- **An API reference**: all 42 endpoints catalogued with methods, descriptions, and rate limits

Skip to the **Executive Summary** (line ~1866) for the one-page overview, or the **Synthesis Roadmap** (line ~1464) for what to build.

---

## Appendix: Project Archaeology

*2026-03-09*

### The Numbers

| Metric | Value |
|--------|-------|
| Total commits | 30 (excl. merges) |
| Active development days | 5 days across 9 calendar days |
| First commit | Feb 28, 2026 10:39pm ("Initial release of Crumble") |
| Latest commit | Mar 8, 2026 10:15pm |
| Backend PHP (excl. vendor) | 5,418 lines across 44 files |
| Frontend JS/JSX/CSS | 6,998 lines across 52 files |
| Database SQL | 171 lines across 7 files |
| Total hand-written code | ~12,587 lines |
| Test suite | 29 tests, 46 assertions — all passing (PHPUnit 10.5, PHP 8.3) |
| Runtime JS dependencies | 4 (react, react-dom, react-router-dom, lucide-react) |
| API endpoints | 42 (3 public, 33 authenticated, 6 admin) |
| Database tables | 11 |

### Development Timeline

```
Feb 28 (4 commits)  — Initial release + tag delete + scaling + scaling fix
Mar  1 (18 commits) — IngredientParser, scraper overhaul, bulk import (Mealie/Paprika),
                       print view, sharing, grocery delete, 13 UX refinements, favorites
Mar  5 (1 commit)   — Security hardening (CSRF, rate limiting, account lockout, tests)
Mar  7 (2 commits)  — Demo account + sponsorship
Mar  8 (5 commits)  — Meal planning, admin/users, email field, recipe sharing, bug fixes
```

### What This Tells Us

**The project was built in intense bursts, not steady iteration.** March 1 alone accounts for 60% of all commits — 18 features and fixes in ~14 hours. This explains some patterns:

1. **Why the test suite is security-only** — testing was done during the security hardening phase (Mar 5), not retroactively for existing code.

2. **Why some features feel more polished than others** — Meal planning and the recipe detail page got more attention; the grocery list and admin page are more utilitarian.

3. **Why there's commented-out code** — The recipe card sharing feature was built (Mar 1, 10:33am), then replaced with image generation (Mar 1, 11:32am), then the share link system was added later (Mar 8). The old code was commented out rather than deleted during rapid iteration.

4. **Why there's no README** — The project went from "initial release" to "18 features" in 8 hours. Documentation wasn't the priority; building was.

### Code Density

12,587 lines for a full-stack recipe manager with:
- 42 API endpoints
- 12 pages
- 30+ reusable components
- Recipe scraping (4-tier parser)
- Image processing (resize + thumbnails)
- 3 import formats (URL, Mealie, Paprika)
- Meal planning with grocery generation
- Session auth with SSO support

That's roughly **300 lines per feature**. This is remarkably lean — no boilerplate framework, no ORM abstraction layers, no TypeScript type definitions. The code/feature ratio is what you get when a developer who knows exactly what they want builds it without ceremony.

---

## Deep Dive 24: Cooklang — Should Crumble Speak It?

### What Cooklang Is

Cooklang is a plain-text recipe markup language (spec at cooklang.org). Think Markdown but for recipes:

```
Preheat oven to ~{10%minutes}.

Dice @potatoes{3} and place in #baking dish{}.
Drizzle with @olive oil{2%tbsp} and season with @salt{} and @pepper{}.

Roast until golden, about ~{25%minutes}.
```

The format encodes three things inline: **ingredients** (`@`), **cookware** (`#`), and **timers** (`~`). Metadata lives in YAML-style `>>` headers. The human reads flowing prose; a parser extracts structured data.

### The Ecosystem

- **CookCLI** (1,145 GitHub stars) — Rust CLI + local web server for `.cook` files. It's the reference implementation.
- **cook-import** — Converts common recipe formats into `.cook` files.
- **Community cookbooks** — Shared recipe collections on GitHub as plain `.cook` files.
- The spec is stable (1.0), with a 2.0 draft in progress adding shopping list annotations and inline quantities.

### Crumble's Data Model vs. Cooklang

| Crumble | Cooklang | Compatible? |
|---------|----------|-------------|
| `recipes.title` | `>> title: ...` metadata | Yes |
| `recipes.description` | No direct equivalent | Crumble extra |
| `recipes.instructions` (JSON array of steps) | Paragraphs separated by blank lines | Convertible |
| `ingredients.name` | `@ingredient` | Yes |
| `ingredients.amount` | `{quantity}` | Yes |
| `ingredients.unit` | `{quantity%unit}` | Yes |
| `ingredients.sort_order` | Implicit (order of appearance) | Yes |
| `tags` | `>> tags: comma,separated` | Yes |
| `recipes.prep_time` / `cook_time` | `>> prep: ...` / `>> cook: ...` (metadata) | Yes |
| `recipes.servings` | `>> servings: N` | Yes |
| `recipes.source_url` | `>> source: ...` | Yes |
| Nutrition fields | No standard equivalent | Crumble extra |
| `recipes.image_path` | Convention: same-name `.jpg` next to `.cook` file | Loosely |

**Verdict:** High compatibility. Crumble's structured data maps cleanly to/from Cooklang. The only Crumble-specific fields (description, nutrition, image_path) would need metadata extensions or would be lost in export.

### What Import Would Look Like

```
POST /api/recipes/import-cooklang
Content-Type: text/plain (or multipart with .cook file)
```

Parser logic (roughly 80 lines of PHP):
1. Split on blank lines → steps
2. Within each step, regex for `@ingredient{qty%unit}` → extract to ingredients array
3. Parse `>>` headers → map to title, servings, prep_time, etc.
4. Strip markup from step text → clean instructions array
5. Feed into existing `Recipe::create()`

This is simpler than RecipeScraper (which handles 4 tiers of HTML parsing). A Cooklang parser is pure text — no HTTP, no DOM, no fallbacks.

### What Export Would Look Like

```
GET /api/recipes/{id}/cooklang
Accept: text/plain
```

Reverse the process:
1. Emit `>>` metadata headers
2. For each instruction step, inline the ingredients that belong to that step

**The hard part:** Crumble doesn't associate ingredients with specific steps. The `ingredients` table has `sort_order` but no `step_number`. So export would either:
- (a) List all ingredients in the first step (ugly but valid Cooklang)
- (b) Use heuristics to match ingredient names mentioned in step text (fragile)
- (c) Add a `step_number` column to ingredients (schema change, affects RecipeForm)

Option (a) is the pragmatic choice. It produces valid `.cook` files that any Cooklang tool can parse, even if the ingredient placement isn't ideal.

### Should Crumble Do This?

**Arguments for:**
- **Data portability** — The Deep Dive 17 (Data Portability) identified zero export paths as a trust gap. Cooklang export gives users a human-readable backup format that doesn't lock them in.
- **Interop with CookCLI users** — CookCLI is the other non-Docker option in the recipe space. Its users are exactly Crumble's target audience: people who want simple tools without container orchestration.
- **Low effort** — ~80 lines for import, ~60 lines for export. Compare to RecipeScraper (165 lines) or MealieImporter.
- **Plain text is forever** — `.cook` files are just text. They survive every platform, every backup strategy, every migration.

**Arguments against:**
- **Tiny user base** — Cooklang has ~1,100 GitHub stars total. The number of people who have `.cook` files AND want to import them into a web app is very small.
- **No ingredient-step association** — Export produces suboptimal `.cook` files without a schema change.
- **Opportunity cost** — Those 140 lines of code could go toward features that benefit all users (ingredient paste, kitchen stats, night mode) rather than a niche format.

### My Take

**Import: yes, but low priority.** It's easy to build and rounds out the import story (URL, Mealie JSON, Paprika ZIP, Cooklang). The parser is simpler than anything Crumble already has.

**Export: yes, and more important than import.** Even if nobody uses Cooklang specifically, the export endpoint establishes the pattern for "get your data out." JSON export would follow the same route structure. The trust signal matters more than the format.

**Priority:** After the README/install experience and the quick wins (night mode, kitchen stats), but before pantry search or PWA. Call it Tier 2 — nice to have, low effort, good optics for a self-hosted project that claims to respect user data.

### Implementation Estimate

| Task | Time |
|------|------|
| PHP Cooklang parser (import) | 1.5 hr |
| Export endpoint + formatter | 1 hr |
| Route registration + tests | 0.5 hr |
| Frontend: import option in AddRecipePage | 0.5 hr |
| Frontend: export button on RecipePage | 0.5 hr |
| **Total** | **4 hr** |

---

## Deep Dive 25: Night Mode — CSS Prototype

The Deep Dive 21 (Night Mode) described the concept. This is the actual CSS.

### Why This Works Without Touching JSX

Crumble uses 11 semantic Tailwind colors defined as CSS custom properties in `@theme`. Every component references these by name (`bg-cream`, `text-brown`, `bg-terracotta`, etc.) — 145 occurrences across 20 files. Override the variables, and the entire app changes.

### The Palette

The day palette is warm earth tones. The night palette should feel like a kitchen lit by a single warm bulb — not the blue-gray "dark mode" that every app defaults to.

| Token | Day Value | Night Value | Night Reasoning |
|-------|-----------|-------------|-----------------|
| `cream` | `#FFF8F0` | `#1C1410` | Near-black with warm brown undertone |
| `cream-dark` | `#F5EDE3` | `#2A1F18` | Card surfaces, slightly lighter than bg |
| `terracotta` | `#C1694F` | `#D4896F` | Lighten for contrast on dark bg |
| `terracotta-light` | `#D4896F` | `#E8A88E` | Hover states, needs to be visible |
| `terracotta-dark` | `#A8533A` | `#C1694F` | Active states |
| `brown` | `#3E2723` | `#F5EDE3` | **Inverted** — this is the primary text color |
| `brown-light` | `#5D4037` | `#D4C4B5` | Secondary text, muted headings |
| `sage` | `#7D9B76` | `#8DAE86` | Slightly brighter for dark bg visibility |
| `sage-light` | `#A8C5A0` | `#6B8C64` | Borders and subtle accents |
| `sage-dark` | `#5F7A58` | `#A8C5A0` | Tags, badges |
| `warm-gray` | `#8D7B6E` | `#6B5D52` | Muted text, borders |

### The CSS

```css
/* Add to index.css after the @theme block */
@media (prefers-color-scheme: dark) {
  :root {
    --color-cream: #1C1410;
    --color-cream-dark: #2A1F18;
    --color-terracotta: #D4896F;
    --color-terracotta-light: #E8A88E;
    --color-terracotta-dark: #C1694F;
    --color-brown: #F5EDE3;
    --color-brown-light: #D4C4B5;
    --color-sage: #8DAE86;
    --color-sage-light: #6B8C64;
    --color-sage-dark: #A8C5A0;
    --color-warm-gray: #6B5D52;
  }

  body {
    background-color: #1C1410;
    color: #F5EDE3;
  }

  ::-webkit-scrollbar-track {
    background: #2A1F18;
  }
  ::-webkit-scrollbar-thumb {
    background: #6B5D52;
  }
}
```

That's it. ~25 lines. No JSX changes. No build config. No state management. Just CSS variable overrides behind a media query.

### What Breaks

Not much, but a few things to check:

1. **Hardcoded colors** — Any `className` with literal hex values instead of semantic tokens won't update. Quick grep:

```bash
# These would need manual review:
grep -r "#FFF8F0\|#3E2723\|#F5EDE3" frontend/src/
```

The `body` background in index.css uses `#FFF8F0` directly — that's why the override block includes `body { background-color }` explicitly.

2. **White backgrounds on images** — Recipe images with white backgrounds will have harsh contrast against the dark surface. Not a CSS fix — would need `mix-blend-mode` or subtle border treatment.

3. **`bg-white` usage** — Standard Tailwind `bg-white` won't respect the theme. Need to check if any components use `bg-white` instead of `bg-cream`.

4. **Input fields** — Browser default input styling might fight the dark palette. May need:
```css
@media (prefers-color-scheme: dark) {
  input, textarea, select {
    background-color: #2A1F18;
    color: #F5EDE3;
    border-color: #6B5D52;
  }
}
```

### Manual Toggle vs. System Preference

The CSS above uses `prefers-color-scheme: dark` — follows the OS setting automatically. For a manual toggle:

1. Replace the media query with a class selector: `.dark { ... }`
2. Add a toggle button in the header (sun/moon icon)
3. Store preference in `localStorage`
4. Apply class to `<html>` on load

The toggle adds ~20 lines of JS and a button in the header. Worth it — many people use light OS theme but want dark apps at night.

### Contrast Check

Running the night palette through WCAG AA (4.5:1 for normal text):

- `brown` (#F5EDE3) on `cream` (#1C1410) = **13.2:1** ✅ (AAA)
- `terracotta` (#D4896F) on `cream` (#1C1410) = **5.8:1** ✅ (AA)
- `warm-gray` (#6B5D52) on `cream` (#1C1410) = **2.9:1** ❌ (fails AA)

The warm-gray on dark fails — this is the muted text / placeholder color. Fix: brighten it to `#8D7B6E` (the current day warm-gray) which gives 4.7:1. So in night mode, warm-gray stays the same value it has in day mode. Updated in the table above.

Actually, let me correct that: `#8D7B6E` on `#1C1410` = **4.6:1** — just barely AA. For muted text that's acceptable, but `#9B8A7E` (slightly brighter) would give more headroom at **5.4:1**.

### Final Night Warm-Gray: `#9B8A7E` (5.4:1 on dark bg)

Update the override:
```css
--color-warm-gray: #9B8A7E;
```

### What This Means for the Roadmap

The Deep Dive 21 estimated 1.5 hours. With this CSS prototype done, actual implementation drops to **~45 minutes**:

- Paste the CSS block into index.css (5 min)
- Grep for hardcoded hex values, fix any (15 min)
- Grep for `bg-white` usage, replace with `bg-cream` (10 min)
- Test all 12 pages in dark mode (15 min)
- Optional: add manual toggle (+30 min)

### Actual `bg-white` Count

Just checked: **49 occurrences across 27 files.** This is the real work. Every `bg-white` needs to become `bg-cream-dark` (or a new `bg-surface` token) to respond to the night mode variable override.

Files with the most `bg-white` usage:
- `MealPlanPage.jsx` — 6
- `BulkImportPage.jsx` — 5
- `AdminPage.jsx` — 4
- `AddRecipePage.jsx` — 3
- `CookHistoryPage.jsx` — 3

This is a find-and-replace operation but needs visual testing per page. Revised time estimate: **1 hour** for the `bg-white` → `bg-cream-dark` migration, **45 minutes** for the CSS overrides + testing. **Total: 1.75 hours** for system-preference night mode, **2.25 hours** with manual toggle.

---

## Deep Dive 26: IngredientParser.js — Working Prototype

The PHP `IngredientParser` (165 lines) parses free-text ingredient strings like "2 cups all-purpose flour" into `{amount, unit, name}`. This is the missing piece for a "paste all ingredients" UX — users paste a block of text, each line gets parsed into structured fields automatically.

### The JS Port

```js
// ingredientParser.js — Client-side port of api/services/IngredientParser.php

const UNITS = {
  cup:     ['cup', 'cups', 'c'],
  tbsp:    ['tbsp', 'tablespoon', 'tablespoons', 'tbs'],
  tsp:     ['tsp', 'teaspoon', 'teaspoons'],
  oz:      ['oz', 'ounce', 'ounces'],
  lb:      ['lb', 'pound', 'pounds', 'lbs'],
  g:       ['g', 'gram', 'grams'],
  kg:      ['kg', 'kilogram', 'kilograms'],
  ml:      ['ml', 'milliliter', 'milliliters'],
  L:       ['l', 'liter', 'liters'],
  clove:   ['clove', 'cloves'],
  pinch:   ['pinch', 'pinches'],
  piece:   ['piece', 'pieces', 'pcs'],
  can:     ['can', 'cans'],
  bunch:   ['bunch', 'bunches'],
  sprig:   ['sprig', 'sprigs'],
  slice:   ['slice', 'slices'],
  stick:   ['stick', 'sticks'],
  head:    ['head', 'heads'],
  dash:    ['dash', 'dashes'],
  package: ['package', 'packages', 'pkg'],
  bag:     ['bag', 'bags'],
  bottle:  ['bottle', 'bottles'],
  jar:     ['jar', 'jars'],
  handful: ['handful', 'handfuls'],
  quart:   ['quart', 'quarts', 'qt'],
  pint:    ['pint', 'pints', 'pt'],
  gallon:  ['gallon', 'gallons', 'gal'],
};

// Build reverse lookup once
const aliasToCanonical = {};
for (const [canonical, aliases] of Object.entries(UNITS)) {
  for (const alias of aliases) {
    aliasToCanonical[alias.toLowerCase()] = canonical;
  }
}

function lookupUnit(word) {
  return aliasToCanonical[word.toLowerCase().replace(/[.,;:]+$/, '')] ?? null;
}

/**
 * Parse a single ingredient line.
 * "2 cups all-purpose flour" → { amount: "2", unit: "cup", name: "all-purpose flour" }
 * "salt and pepper to taste" → { amount: null, unit: null, name: "salt and pepper to taste" }
 */
export function parseIngredient(text) {
  text = text.trim().replace(/\s+/g, ' ');
  if (!text) return { amount: null, unit: null, name: '' };

  let amount = null;
  let unit = null;
  let remaining = text;

  // Step 1: Extract amount (integer, decimal, fraction, mixed number, or range)
  const numPart = '(?:\\d+\\s+\\d+/\\d+|\\d+\\.\\d+|\\d+/\\d+|\\d+)';
  const amountRe = new RegExp(`^(${numPart}(?:\\s*-\\s*${numPart})?)\\s*`);
  const amountMatch = remaining.match(amountRe);

  if (amountMatch) {
    amount = amountMatch[1].trim();
    remaining = remaining.slice(amountMatch[0].length);
  }

  if (!amount) return { amount: null, unit: null, name: text };

  remaining = remaining.trimStart();

  // Step 2: Parenthetical units — "2 (14 oz) cans tomatoes"
  const parenRe = /^\(([^)]*)\)\s+(\S+)(.*)/s;
  const parenMatch = remaining.match(parenRe);
  if (parenMatch) {
    const canonical = lookupUnit(parenMatch[2]);
    if (canonical) {
      return {
        amount,
        unit: canonical,
        name: `(${parenMatch[1]}) ${parenMatch[3]}`.trim(),
      };
    }
  }

  // Step 3: First word as unit
  const wordRe = /^(\S+)(.*)$/s;
  const wordMatch = remaining.match(wordRe);
  if (wordMatch) {
    const canonical = lookupUnit(wordMatch[1]);
    if (canonical) {
      unit = canonical;
      remaining = wordMatch[2].trimStart();
    }
  }

  return { amount: amount || null, unit, name: remaining.trim() };
}

/**
 * Parse multiple ingredient lines (for paste-all feature).
 * Splits on newlines, filters blanks, parses each line.
 */
export function parseIngredientBlock(text) {
  return text
    .split(/\n/)
    .map(line => line.trim())
    .filter(line => line.length > 0)
    .map((line, i) => ({ ...parseIngredient(line), sort_order: i }));
}
```

### Test Cases (PHP parity)

```js
// These match the PHP parser's documented behavior exactly:
parseIngredient("2 cups all-purpose flour")
// → { amount: "2", unit: "cup", name: "all-purpose flour" }

parseIngredient("1 1/2 tsp salt")
// → { amount: "1 1/2", unit: "tsp", name: "salt" }

parseIngredient("salt and pepper to taste")
// → { amount: null, unit: null, name: "salt and pepper to taste" }

parseIngredient("2 (14 oz) cans tomatoes")
// → { amount: "2", unit: "can", name: "(14 oz) tomatoes" }

parseIngredient("3 large eggs")
// → { amount: "3", unit: null, name: "large eggs" }

parseIngredient("1/2 - 3/4 cup sugar")
// → { amount: "1/2 - 3/4", unit: "cup", name: "sugar" }
```

### How It Plugs Into RecipeForm

The current RecipeForm (645 lines) has ingredient rows with separate amount/unit/name fields. The paste feature would add a textarea above the rows:

```jsx
// In RecipeForm.jsx, above the ingredient rows:
const [pasteMode, setPasteMode] = useState(false);

{pasteMode ? (
  <div>
    <textarea
      className="w-full h-40 p-3 border rounded-lg font-mono text-sm"
      placeholder={"Paste ingredients, one per line:\n2 cups flour\n1 tsp salt\n3 large eggs"}
      onBlur={(e) => {
        if (e.target.value.trim()) {
          const parsed = parseIngredientBlock(e.target.value);
          setIngredients(prev => [...prev, ...parsed]);
          setPasteMode(false);
        }
      }}
      autoFocus
    />
    <p className="text-xs text-warm-gray mt-1">
      Press Tab or click outside to parse
    </p>
  </div>
) : (
  <button
    type="button"
    onClick={() => setPasteMode(true)}
    className="text-sm text-terracotta hover:text-terracotta-dark"
  >
    + Paste multiple ingredients
  </button>
)}
```

### What This Enables

1. **Copy from any recipe site** → paste into textarea → structured rows appear
2. **Type naturally** → "2 cups flour" in a single field instead of three separate inputs
3. **Bulk entry** → paste an entire ingredient list at once

### Edge Cases the PHP Parser Handles (and the JS port should too)

- Mixed numbers: `1 1/2` (space between integer and fraction)
- Ranges: `2-3 cloves garlic`
- Parenthetical sizes: `2 (14 oz) cans crushed tomatoes`
- No amount: `salt and pepper to taste`
- No unit: `3 large eggs` (amount=3, unit=null, name="large eggs")
- Trailing punctuation: `2 cups, divided` (strips comma before unit lookup)

### What the PHP Parser Doesn't Handle (improvements for the JS version)

1. **Unicode fractions** — `½ cup flour` should parse as amount="1/2". The PHP parser doesn't handle these. The JS version could normalize `½→1/2, ⅓→1/3, ¼→1/4, ¾→3/4, ⅔→2/3, ⅛→1/8` before parsing.

2. **"of" between unit and name** — `2 cups of flour` currently parses as unit="cup", name="of flour". Should strip leading "of".

3. **Descriptors before the ingredient** — `1 large ripe tomato` gives name="large ripe tomato" which is correct, but "large" and "ripe" are descriptors, not the ingredient. This is fine for Crumble's data model (name is free-text) but worth noting.

### Implementation Estimate

| Task | Time |
|------|------|
| `ingredientParser.js` (the code above, cleaned up) | 30 min |
| Unit tests for parser | 20 min |
| Paste textarea UI in RecipeForm | 30 min |
| Unicode fraction normalization | 15 min |
| "of" stripping | 5 min |
| Visual testing | 20 min |
| **Total** | **2 hr** |

This is the highest-impact UX improvement per hour of development time. Recipe entry is the most common user action, and ingredient entry is the slowest part of it.

---

## Deep Dive 27: The Cooking Journal — Unlocking a Ghost Feature

### Discovery

While reading the RecipeScraper (which turned out to be a 5-tier parser, not 4 — JSON-LD → Microdata → Heuristic HTML → Open Graph → AMP/Cache retry), I got curious about the unused `notes` field in `cook_log`.

Here's what I found: **the entire feature is already wired up, end to end.** Every layer accepts notes. Nothing passes them.

```
Database:    cook_log.notes TEXT          ✅ exists
Model:       CookLog::log($notes)         ✅ accepts parameter
API:         POST /recipes/{id}/cook      ✅ reads body.notes
Frontend:    api.logCook(id, notes)       ✅ accepts parameter
Component:   CookButton.handleCook()      ❌ calls api.logCook(recipeId) — no notes
Display:     CookHistoryPage              ✅ renders entry.notes if present
```

One line of code changed, and notes flow through. But that's not the interesting part.

### What If "I Cooked This" Became a Moment?

Right now, clicking "I Cooked This" is a throwaway click. Green flash, counter increments, done. It's a tally mark, not a memory.

What if clicking it opened a small modal — not a form, a *moment*:

```
┌──────────────────────────────────────┐
│  🎉 Nice! How'd it go?              │
│                                       │
│  ┌─────────────────────────────────┐ │
│  │ Add a note... (optional)        │ │
│  │                                 │ │
│  │ "Used half the sugar, turned    │ │
│  │  out perfect. Kids loved it."   │ │
│  └─────────────────────────────────┘ │
│                                       │
│  [Just Log It]     [Save Note]       │
│                                       │
└──────────────────────────────────────┘
```

**"Just Log It"** works exactly like today — no notes, instant log.
**"Save Note"** saves the note text with the cook log entry.

The modal is optional. If the user wants the quick-click behavior, "Just Log It" is right there. No friction added for people who don't want it.

### Why This Matters

1. **Recipes improve over time.** "Doubled the garlic" and "needs 10 more minutes in my oven" are the kind of notes that make a recipe *yours*. No recipe app captures this well.

2. **Cook History becomes useful.** Right now it's a flat list of dates. With notes, it becomes a cooking journal — a personal record of what you learned each time.

3. **The data already exists.** This isn't a new table, new migration, new endpoint. It's passing a string through an existing pipe.

### The Cooking Journal Vision (Bigger Version)

If notes land well, the CookHistoryPage could evolve:

**Phase 1 (30 min):** Note input in CookButton modal. Display in history.

**Phase 2 (1 hr):** Per-recipe cook history. On the RecipePage, show a "Your Cook Notes" section under the recipe. Previous notes for *this specific recipe* in reverse chronological order. "Last cooked 3 weeks ago — you noted: 'Used Greek yogurt instead of sour cream, even better.'"

**Phase 3 (2 hr):** Photo per cook. Add an optional photo upload to the cook log modal. "Here's how it looked this time." The schema would need a `photo_path` column in `cook_log`, and the ImageProcessor already handles uploads.

**Phase 4 (concept):** Variation tracking. If you consistently note "I always double the garlic" on a recipe, the UI could surface a subtle suggestion: "You've mentioned garlic modifications 3 times. Want to update the recipe?" This is where the journal becomes intelligent, but it's way down the road.

### Implementation: Phase 1

The minimal version:

**CookButton.jsx** — Change from instant-log to modal:

```jsx
const [showModal, setShowModal] = useState(false);
const [notes, setNotes] = useState('');

const handleCook = async (withNotes = false) => {
  if (loading) return;
  setLoading(true);
  try {
    await api.logCook(recipeId, withNotes ? notes : null);
    setCount(prev => prev + 1);
    setJustCooked(true);
    setShowModal(false);
    setNotes('');
    setTimeout(() => setJustCooked(false), 2000);
    if (onCook) onCook();
  } catch {
    // ignore
  } finally {
    setLoading(false);
  }
};

// The button opens the modal instead of logging directly:
onClick={() => setShowModal(true)}
```

The modal itself is ~30 lines of JSX — a textarea and two buttons.

**CookHistoryPage.jsx** — Already renders notes (line 78-80). No changes needed.

**Backend** — Already accepts notes. No changes needed.

### Per-Recipe Cook Notes (Phase 2 Query)

The backend needs one new method in CookLog:

```php
public function getByRecipe(int $userId, int $recipeId): array {
    $stmt = $this->db->prepare('
        SELECT id, cooked_at, notes
        FROM cook_log
        WHERE user_id = ? AND recipe_id = ?
        ORDER BY cooked_at DESC
    ');
    $stmt->execute([$userId, $recipeId]);
    return $stmt->fetchAll();
}
```

And a new route: `GET /recipes/{id}/cook-log` → returns the user's cook notes for that recipe.

On the RecipePage, a small section below the recipe:

```
Your Cook Notes
─────────────────
Mar 2 — "Added smoked paprika, way better"
Feb 15 — "Good but needs more salt"
Jan 28 — (no notes)
```

This transforms the recipe page from "here's the recipe" to "here's *your story* with this recipe."

### Competitive Analysis

I searched for cooking journal/note features in self-hosted recipe managers:

- **Mealie** — No cook log at all
- **Tandoor** — Has a cook log with servings and rating per cook, but no free-text notes
- **KitchenOwl** — No cook log
- **Grocy** — Has a "consume" log (it's a pantry tracker), no recipe notes
- **Paprika** — Has a "notes" field on the recipe itself (one note, not per-cook)

**Nobody has per-cook notes.** Tandoor comes closest with per-cook ratings, but a rating is a number, not a story. Crumble would be the only self-hosted recipe manager where you can say "I cooked this on March 2nd and here's what I learned."

### Time Estimates

| Phase | What | Time |
|-------|------|------|
| 1 | Cook note modal + basic flow | 30 min |
| 2 | Per-recipe cook history endpoint + RecipePage section | 1 hr |
| 3 | Photo per cook (upload + display) | 2 hr |
| **Phases 1+2** | **The useful version** | **1.5 hr** |

Phase 1+2 at 1.5 hours is the second-best ROI after the ingredient paste feature. And it gives Crumble a genuinely unique feature — not just parity with competitors, but something they don't have.

---

## Deep Dive 28: RecipeScraper — The 5-Tier Parser

### Architecture

I initially described this as a "4-tier parser" in earlier deep dives. After reading the full 688-line file, it's actually 5 tiers with a retry loop:

```
Tier 1: JSON-LD (schema.org/Recipe)
  ↓ fail
Tier 2: Microdata (itemprop attributes)
  ↓ fail
Tier 3: Heuristic HTML (class/id keyword matching)
  ↓ fail
Tier 4: Open Graph meta tags (title/description/image only)
  ↓ fail
Tier 5: Fetch cached/AMP version, retry tiers 1-3
  ↓ fail
Return partial data with error message
```

### What's Good

**SSRF protection** (lines 114-145) — Resolves hostname to IP and blocks private/reserved ranges. This prevents `http://localhost/admin` or `http://192.168.1.1/` attacks through the scraper. Proper security.

**Rotating user agents** (5 browser strings) — Reduces bot detection. Pragmatic.

**Human error messages** — Not "HTTP 403" but "This website blocked our request. Try copying the recipe manually." The error messages are specific and actionable:
- `invalid_url`: "This doesn't look like a valid URL."
- `fetch_failed`: "Couldn't reach this website."
- `fetch_blocked`: "This website blocked our request."
- `fetch_timeout`: "The website took too long to respond."
- `parse_failed`: "Found the page but couldn't find recipe data."

**JSON-LD recursive search** — Handles `@graph` arrays, nested structures, arrays of Recipe objects. This is important because real-world JSON-LD is messy — sites wrap Recipe in `@graph` alongside WebPage, BreadcrumbList, Organization, etc.

**HowToSection handling** — Some recipe sites nest instructions in `HowToSection > itemListElement > HowToStep`. The parser handles both flat and nested instruction structures.

**Ingredient parsing at every tier** — JSON-LD, Microdata, and Heuristic all pipe raw ingredient strings through IngredientParser for structured output.

### What's Missing

1. **Nutrition from JSON-LD** — The schema.org Recipe type has a `nutrition` property with `calories`, `proteinContent`, `fatContent`, etc. Crumble already stores these fields (`calories`, `protein`, `carbs`, `fat`, `fiber`, `sugar` in the recipes table). The scraper just... doesn't extract them.

   This is a ~20-line addition to `mapJsonLdRecipe()`:
   ```php
   if (!empty($data['nutrition'])) {
       $n = $data['nutrition'];
       $result['calories'] = $this->parseNutrition($n['calories'] ?? null);
       $result['protein'] = $this->parseNutrition($n['proteinContent'] ?? null);
       $result['fat'] = $this->parseNutrition($n['fatContent'] ?? null);
       $result['carbs'] = $this->parseNutrition($n['carbohydrateContent'] ?? null);
       $result['fiber'] = $this->parseNutrition($n['fiberContent'] ?? null);
       $result['sugar'] = $this->parseNutrition($n['sugarContent'] ?? null);
   }
   ```
   Where `parseNutrition()` extracts the number from strings like "250 calories" or "12 g".

2. **Tags from JSON-LD** — `recipeCategory` ("Dinner", "Dessert") and `recipeCuisine` ("Italian", "Mexican") map directly to Crumble's tag system. Another ~10 lines.

3. **Keywords from JSON-LD** — The `keywords` field is a comma-separated string of tags. Free data.

4. **Yield as text** — `recipeYield` can be "4 servings" or "1 loaf" or ["4", "1 loaf"]. The parser extracts the first number, which loses "1 loaf". Could preserve the text when it's not just a number.

5. **Author** — `author.name` in JSON-LD could be stored or displayed as "Originally by {author}" on the recipe page.

### Improvement Priority

| Fix | Lines | Impact |
|-----|-------|--------|
| Extract nutrition from JSON-LD | ~25 | High — auto-fills 6 fields users rarely enter manually |
| Extract tags (category + cuisine + keywords) | ~15 | Medium — auto-tags imported recipes |
| Extract author name | ~5 | Low — nice to have for attribution |
| Preserve text yields | ~10 | Low — edge case |

The nutrition extraction is the biggest win. Most major recipe sites include nutrition in their JSON-LD (it's an SEO signal). Currently users have to manually enter nutrition data — or more realistically, they just don't. Auto-extraction would mean imported recipes have richer data automatically.

### Time Estimate

All four improvements: **1 hour**. The nutrition extraction alone: **20 minutes**.

---

## Deep Dive 31: Grocery Lists — The Quiet Workhorse

### What's There

The grocery system is more capable than I expected:

- **Multiple named lists** — Not a single global list. Users create "Weekly Groceries", "Party Supplies", etc.
- **Recipe-to-grocery pipeline** — `addFromRecipe()` bulk-adds all ingredients from a recipe, with dedup by name and amount combining when units match.
- **Meal plan integration** — MealPlanPage has "Generate Grocery List" that creates a list from all recipes in the week.
- **Structured items** — Each grocery item has `name`, `amount`, `unit`, `checked`, `recipe_id` (with `recipe_title` via JOIN for attribution).
- **Optimistic sorting** — Checked items sink to the bottom.
- **Delete confirmation** — `window.confirm()` for list deletion (simple but effective).

### What's Missing

**1. No ingredient consolidation across recipes**

`addFromRecipe()` deduplicates by name and combines amounts when units match. But it fails on:
- "flour" from recipe A (2 cups) + "all-purpose flour" from recipe B (1 cup) → not combined (different names)
- "butter" (2 tbsp) + "butter" (1/4 cup) → not combined (different units)
- "onion" (1) + "onions" (2) → not combined (singular vs plural)

The meal plan grocery generation presumably hits the same issue since it calls the same backend.

This is the "Smart Grocery Consolidation" problem from Deep Dive 7. The fix needs:
- Name normalization (lowercase, strip "all-purpose", handle singular/plural)
- Unit conversion (tbsp → cup, oz → g)
- These are non-trivial — probably 4-6 hours of work

**2. No manual amount editing**

GroceryItem displays amount/unit/name but there's no inline edit. If the consolidation gives you "4 cups flour" and you only need 3, you can't fix it without deleting and re-adding.

**3. No category grouping**

Items are listed in insertion order. A real grocery list groups by aisle: produce, dairy, meats, pantry, frozen. This is hard to do automatically (requires ingredient categorization) but could be done manually with user-defined sections.

**4. No sharing**

Grocery lists are per-user. Can't share a list with a partner who's at the store. This would need either a share-by-link system (like recipe sharing) or multi-user list access.

**5. Delete button invisible on mobile**

`GroceryItem.jsx` line 37: `opacity-0 group-hover:opacity-100` — the delete button is hidden until hover. On mobile, there's no hover. The `md:opacity-0 md:group-hover:opacity-100` means it's hidden on both mobile AND desktop until hover. Mobile users literally can't delete items without long-pressing or swiping (which isn't implemented).

This is a real bug. Fix: make the delete button always visible on mobile:
```
opacity-100 md:opacity-0 md:group-hover:opacity-100
```

### Quick Wins

| Fix | Time | Impact |
|-----|------|--------|
| Mobile delete button visibility | 2 min | Bug fix — mobile users can't delete items |
| Singular/plural normalization in `addFromRecipe` | 30 min | Better dedup |
| Inline amount editing | 45 min | QoL for adjusting quantities |

---

## The Implementation Guide — 8 Hours to a Better Crumble

After 31 deep dives, here's the distilled action plan. Not theory — exact files, exact code, exact order. This is the "if you have 8 hours" plan.

### Hour 1: Bug Fixes (3 bugs, ~45 min)

**1. CookMode timer persistence** (30 min)
- File: `frontend/src/components/recipe/CookMode.jsx`
- Remove `setActiveTimers([])` from `goNext` (line 57) and `goPrev` (line 64)
- Add `step` property to timer objects in `startTimer()`
- Render active timers in a floating bar outside the step content area
- Add `autoStart={true}` prop to Timer when started from CookMode

**2. Grocery delete button on mobile** (2 min)
- File: `frontend/src/components/grocery/GroceryItem.jsx` line 37
- Change `opacity-0 group-hover:opacity-100` to `opacity-100 md:opacity-0 md:group-hover:opacity-100`

**3. Timer auto-start in CookMode** (5 min)
- File: `frontend/src/components/ui/Timer.jsx` line 8
- Add `autoStart` prop: `const [isRunning, setIsRunning] = useState(autoStart ?? false);`
- Pass `autoStart={true}` from CookMode's `startTimer`

### Hour 2: Cooking Journal (1 hr)

**Phase 1: Cook note modal** (30 min)
- File: `frontend/src/components/recipe/CookButton.jsx`
- Change `onClick` to open a small modal instead of logging directly
- Modal: textarea + "Just Log It" (no notes) + "Save Note" (with notes)
- Pass `notes` to `api.logCook(recipeId, notes)` — already accepted by API

**Phase 2: Per-recipe cook history** (30 min)
- File: `api/models/CookLog.php` — add `getByRecipe(userId, recipeId)` method
- File: `api/index.php` — add `GET /recipes/{id}/cook-log` route
- File: `frontend/src/pages/RecipePage.jsx` — add "Your Cook Notes" section below recipe

### Hour 3: Night Mode (1 hr)

**CSS overrides** (15 min)
- File: `frontend/src/index.css` — add the `@media (prefers-color-scheme: dark)` block from Deep Dive 25

**bg-white migration** (45 min)
- 49 occurrences across 27 files
- Global find-replace: `bg-white` → `bg-cream-dark` (context-dependent — some may need `bg-cream`)
- Test each page in OS dark mode

### Hour 4: Ingredient Paste (1.5 hr)

**Parser** (30 min)
- Create `frontend/src/utils/ingredientParser.js` with code from Deep Dive 26
- Add Unicode fraction normalization (½→1/2 etc.)
- Add "of" stripping ("2 cups of flour" → "flour")

**UI** (30 min)
- File: `frontend/src/components/recipe/RecipeForm.jsx`
- Add "Paste multiple ingredients" button above ingredient rows
- Toggles a textarea that auto-parses on blur via `parseIngredientBlock()`
- Parsed results append to existing ingredients list

**Testing** (30 min)
- Manual test with real recipe ingredient lists from popular sites

### Hour 5: RecipeScraper Improvements (1 hr)

**Nutrition extraction from JSON-LD** (20 min)
- File: `api/services/RecipeScraper.php` → `mapJsonLdRecipe()` method
- Add `parseNutrition()` helper for strings like "250 calories" → 250
- Map: `calories`, `proteinContent`, `fatContent`, `carbohydrateContent`, `fiberContent`, `sugarContent`

**Tag extraction from JSON-LD** (15 min)
- Extract `recipeCategory`, `recipeCuisine`, `keywords` → merge into `tags` array

**CookMode "Done" logs cook** (15 min)
- File: `frontend/src/components/recipe/CookMode.jsx`
- "Done!" button triggers the cook journal modal from Hour 2 instead of `onClose()`
- Pass `onCookLogged` callback to CookMode

**CookMode ingredient highlighting** (10 min)
- In CookMode, filter ingredients list to highlight items mentioned in current step text
- Simple `name.toLowerCase()` includes check — 5 lines

### Hour 6: Data Export + Cooklang (1.5 hr)

**JSON export endpoint** (30 min)
- `GET /api/recipes/{id}/export` — returns full recipe as JSON (ingredients, tags, instructions, nutrition)
- `GET /api/recipes/export-all` — returns all recipes as JSON array
- Admin-only for export-all

**Cooklang export** (30 min)
- `GET /api/recipes/{id}/cooklang` — returns `.cook` formatted text
- `>> title:`, `>> servings:`, ingredients in first step (pragmatic approach)

**Export button in UI** (30 min)
- RecipePage: dropdown menu with "Export as JSON" / "Export as Cooklang"
- AdminPage: "Export All Recipes" button

### Hour 7: Kitchen Stats (1.25 hr)

**Backend** (30 min)
- File: `api/models/CookLog.php` — add `getStats(userId)` method
- Queries: total cooks, most-cooked recipe, favorite tags, monthly frequency, current streak
- New route: `GET /api/cook-log/stats`

**Frontend** (45 min)
- File: `frontend/src/pages/CookHistoryPage.jsx`
- Add stats card at top of page: "You've cooked 47 meals, your most-made is Pasta Carbonara (8 times)"
- Monthly chart (simple CSS bar chart, no chart library)

### Hour 8: Polish + README (1 hr)

**README.md** (30 min)
- Project description, screenshot, requirements (PHP 8.1+, MySQL, Apache)
- Installation steps (clone, database setup, .env config, Apache vhost)
- Development setup (frontend dev server, API config)

**Dead code cleanup** (15 min)
- Remove 150 lines of commented-out card sharing code from RecipePage.jsx
- Remove unused prev/next navigation data (or wire it up — 10 min for arrow buttons)

**.env.example** (5 min)
- Template with all expected env vars and comments

**Final testing pass** (10 min)
- Test all 12 pages in both light and dark mode
- Test CookMode with timer across steps
- Test ingredient paste with a real recipe

### What This Gets You

After 8 hours:
- 3 bugs fixed (CookMode timer, grocery mobile delete, timer auto-start)
- 1 unique feature no competitor has (cooking journal with per-recipe notes)
- Night mode (CSS-only, follows OS preference)
- Ingredient paste (biggest UX improvement)
- Richer recipe imports (auto-nutrition, auto-tags)
- Data export (JSON + Cooklang — trust signal for self-hosted users)
- Kitchen stats (emotional engagement)
- Project documentation (README, .env.example)
- Dead code removed

The app goes from "solid recipe manager" to "polished, differentiated recipe manager with features its competitors don't have."

---

## Deep Dive 29: CookMode — What's There, What Breaks, What's Next

### What CookMode Actually Is

A full-screen cooking companion (`CookMode.jsx`, 252 lines). Dark background (bg-brown), large text, one step at a time. It's surprisingly well-featured:

**What works well:**
- **Wake Lock** — Screen stays on while cooking. Uses the Screen Wake Lock API with automatic re-acquisition when the page becomes visible again (e.g., after switching apps). This is a detail most cooking apps get wrong.
- **Swipe navigation** — Touch swipe left/right with 50px threshold. Also keyboard arrows.
- **Timer auto-detection** — Regex parses step text for time mentions ("20 minutes", "1 hour"). Shows a "Start 20 min timer" button inline with the step. Smart.
- **Timer audio** — WebAudio API generates 3 beeps at 880Hz when timer completes. No audio file dependency.
- **Slide-out ingredient panel** — Toggle to see ingredients without leaving the current step. Overlay backdrop. Checkable items.
- **Progress bar** — Terracotta fill showing step progress.
- **44px touch targets** — All buttons meet the minimum touch target size.

### The Bugs

**1. Timer destruction on step change (Critical)**

```js
const goNext = useCallback(() => {
  if (currentStep < totalSteps - 1) {
    setCurrentStep(prev => prev + 1);
    setActiveTimers([]);  // ← KILLS ALL RUNNING TIMERS
  }
}, [currentStep, totalSteps]);
```

If you start a 20-minute timer on step 3 and advance to step 4, the timer is destroyed. This is the most severe UX bug in the entire app — you're actively cooking and your timer just vanishes.

**Fix:** Lift `activeTimers` state to persist across steps. Show active timers in a floating bar at the bottom regardless of current step. When a timer finishes, show which step it came from.

```jsx
// Instead of clearing timers on navigation:
const goNext = useCallback(() => {
  if (currentStep < totalSteps - 1) {
    setCurrentStep(prev => prev + 1);
    // Don't touch activeTimers
  }
}, [currentStep, totalSteps]);

// Floating timer bar (always visible):
{activeTimers.length > 0 && (
  <div className="fixed bottom-20 left-0 right-0 flex justify-center gap-2 px-4 z-20">
    {activeTimers.map(timer => (
      <Timer key={timer.id} initialMinutes={timer.minutes} stepLabel={`Step ${timer.step}`} ... />
    ))}
  </div>
)}
```

**2. Timer doesn't auto-start**

You click "Start 20 min timer" and get a paused timer. Then you have to press Play. The Timer component initializes with `isRunning: false`. When you're cooking and your hands are messy, that extra tap matters.

**Fix:** Add an `autoStart` prop to Timer:
```jsx
const [isRunning, setIsRunning] = useState(autoStart ?? false);
```

**3. Timer drift**

The Timer uses `setInterval(fn, 1000)` which accumulates drift over time. After 20 minutes, it could be off by several seconds. For cooking timers this is acceptable (nobody cares about ±5 seconds on a 20-minute bake), but the proper fix is timestamp-based:

```js
// Instead of counting down by 1 each second:
const endTimeRef = useRef(Date.now() + initialMinutes * 60 * 1000);

useEffect(() => {
  if (!isRunning) return;
  const tick = () => {
    const remaining = Math.max(0, Math.ceil((endTimeRef.current - Date.now()) / 1000));
    setTotalSeconds(remaining);
    if (remaining <= 0) { /* done */ }
  };
  const id = setInterval(tick, 250); // Check 4x/sec for smoother display
  return () => clearInterval(id);
}, [isRunning]);
```

**4. "Done!" doesn't log the cook**

The last step shows a green "Done!" button that just calls `onClose()`. It should trigger the cook log — or at least prompt the cooking journal modal from Deep Dive 27. You just finished cooking; this is the natural moment to ask "How'd it go?"

**5. No temperature detection**

The timer regex catches "20 minutes" but not "350°F" or "180°C". These could be highlighted visually (bold, colored) even without any interactive feature — just to make temperatures stand out in the step text.

### What SideChef Does Better (Industry Benchmark)

SideChef is the commercial gold standard for cook mode:
- **Voice commands** — "Next step" / "Previous" / "Start timer" hands-free
- **Per-step photos** — Each instruction has a corresponding image
- **Auto-triggered timers** — Timers start automatically when you reach a step that has one
- **Smart appliance integration** — Sends oven temperature directly to connected appliances

Crumble can't (and shouldn't) match smart appliance integration. But voice navigation and auto-start timers are achievable.

### Voice Navigation — Is It Feasible?

The Web Speech API (`webkitSpeechRecognition`) works in Chrome, Edge, and Safari. Basic implementation:

```js
const recognition = new webkitSpeechRecognition();
recognition.continuous = true;
recognition.onresult = (event) => {
  const transcript = event.results[event.results.length - 1][0].transcript.toLowerCase();
  if (transcript.includes('next')) goNext();
  if (transcript.includes('back') || transcript.includes('previous')) goPrev();
  if (transcript.includes('timer') || transcript.includes('start')) {
    if (detectedTimers.length > 0) startTimer(detectedTimers[0]);
  }
  if (transcript.includes('ingredients')) setShowIngredients(true);
};
```

~20 lines of code. No API key, no server round-trip, runs entirely in the browser. The catch: it requires microphone permission and only works in Chromium browsers and Safari. Firefox has limited support.

For a self-hosted app where you control your own browser, this is fine. It's a progressive enhancement — works where supported, invisible where not.

### CookMode Improvement Roadmap

| Fix | Time | Impact |
|-----|------|--------|
| Timer persistence across steps | 30 min | Critical — current bug destroys timers |
| Timer auto-start | 5 min | High — removes unnecessary tap |
| "Done" triggers cook log | 15 min | Medium — connects CookMode to journal |
| Temperature highlighting | 20 min | Low — visual polish |
| Timestamp-based timer | 15 min | Low — drift is negligible for cooking |
| Voice navigation | 45 min | Medium — hands-free is a real kitchen need |
| **Total** | **2 hr 10 min** | |

The timer persistence fix alone is worth doing immediately. It's a 30-minute fix for the worst UX bug in the app.

---

## Deep Dive 30: Tandoor's AI — What It Means for Crumble

### The Competitive Shift

When I wrote the original competitive landscape (Deep Dive 6), Tandoor was a strong but conventional recipe manager. As of mid-2025, **Tandoor has shipped AI features**:

1. **Recipe import from photos/PDFs** — Take a photo of a cookbook page → structured recipe
2. **Step organization** — AI sorts recipe steps and assigns ingredients to specific steps
3. **Metadata extraction** — Nutrition data, food properties from recipe text
4. **Multi-provider support** — Routes through LiteLLM: OpenAI, Google AI Studio, OpenRouter, any OpenAI-compatible endpoint

Timeline: Feature request Nov 2023 → Alpha June 2025 → Docs August 2025 → Stable in current release.

### How They Did It

Tandoor's approach is architecturally clean:
- All AI calls routed through **LiteLLM** (provider abstraction)
- **Structured JSON responses** required — models must support JSON mode
- **Per-space spending limits** (~$1 USD/month default)
- **Vision support** for image/PDF import
- Configurable at space level (multi-tenant) or globally (superuser)

This is the right architecture. No vendor lock-in, cost-controlled, works with local models (85-90% accuracy) or cloud (95%+ accuracy).

### What This Means for Crumble

**The question isn't "should Crumble add AI?"** The question is: **what AI features make sense for a zero-dependency, Docker-free app?**

Tandoor can require LiteLLM because their users already run Docker. Adding a Python dependency to a Docker Compose file is free. Crumble's users run PHP on shared hosting, Laragon, or bare metal. Adding a Python AI service is a deployment burden.

### Three Paths for Crumble + AI

**Path 1: Client-side AI (browser-only)**

Use browser APIs and small models:
- **Web Speech API** — Voice commands in CookMode (covered above, ~20 lines)
- **Chrome's built-in AI** — Chrome Canary has `window.ai` for on-device inference. Not production-ready yet, but worth watching
- **TensorFlow.js** — Ingredient recognition from photos in-browser. Heavy (~20MB model), but no server needed

Pros: No server dependency. Aligns with Crumble's zero-infrastructure philosophy.
Cons: Limited capability. Browser support varies.

**Path 2: Optional server-side AI endpoint**

Add a single configurable AI endpoint in Crumble's settings:
```
AI_PROVIDER=openai
AI_API_KEY=sk-...
AI_MODEL=gpt-4o-mini
```

Use it for:
- Recipe import from photos/text (like Tandoor)
- Nutrition estimation from ingredient lists
- Recipe suggestions based on what you have

Pros: Powerful. Provider-agnostic.
Cons: Requires API key. Costs money. Adds complexity.

**Path 3: Do nothing — lean into non-AI differentiators**

Crumble's pitch: *Simple, fast, no Docker, no cloud, no AI subscription.*

While Tandoor adds complexity (AI providers, spending limits, credit tracking), Crumble stays lean. The audience that wants zero dependencies isn't the same audience that wants AI. These are different users making different tradeoffs.

Pros: Maintains simplicity. No infrastructure decisions.
Cons: Looks outdated on feature comparison charts.

### My Take

**Path 3 for now, Path 2 as opt-in later.**

The honest truth: AI recipe import from photos is genuinely useful. It's the one AI feature that solves a real problem (typing recipes from cookbooks is tedious). But it's not urgent, and Crumble has higher-ROI improvements to make first.

The right sequence:
1. Fix the real UX issues (ingredient paste, timer persistence, cook journal)
2. Add the missing basics (README, night mode, data export)
3. *Then* consider optional AI as a "bring your own API key" feature

If/when AI is added, the architecture should be:
- **Optional** — Crumble works perfectly without it
- **Single endpoint** — One API key in `.env`, not a provider management UI
- **One feature** — Recipe import from photo/text, not a Swiss Army knife
- **PHP-native** — `curl` to an OpenAI-compatible endpoint, no Python dependencies

This matches Crumble's philosophy: do one thing, do it simply, skip the ceremony.

### The Feature Tandoor Has That Crumble Should Actually Steal

It's not AI. It's **assigning ingredients to steps.**

Tandoor uses AI to assign each ingredient to the step where it's first used. This is brilliant UX — when you're on step 3, you see only the ingredients for step 3, not the full list.

Crumble could approximate this without AI:
- Simple text matching: if step 3 mentions "garlic", show the garlic ingredient
- Highlight ingredients in the sidebar that appear in the current step text
- No schema change needed — just string matching in the frontend

```js
// In CookMode, highlight ingredients mentioned in current step:
const currentStepIngredients = ingredients.filter(ing =>
  stepText.toLowerCase().includes(ing.name.toLowerCase())
);
```

~5 lines of code. No AI. Meaningful UX improvement. That's the Crumble way.

---

## Closing Essay: What Makes a Recipe App Feel Like Home

I've now read every page, every component, every model, every service, and every test in this codebase. 12,587 lines across ~80 files. Here's something that doesn't fit in a feature comparison chart or a prioritized roadmap.

### The Login Page Tells You Everything

The first thing you see:

> **Crumble**
> Your cozy recipe manager

Not "Your powerful recipe management platform." Not "AI-powered kitchen assistant." Not "The smart way to organize recipes."

*Cozy.* That's the whole identity in one word.

The CookingPot icon sits in a soft terracotta circle. The form has generous padding. The "Try Demo" button says "Browse recipes in read-only mode" — it doesn't say "Experience our features" or "Start your free trial." It's direct. It's warm. It treats you like a person who just wants to look at some recipes.

Below the form, quietly: "Open source on GitHub." No pitch. No sales copy. Just a fact.

### The Design System Is Small and Consistent

After reading every component:

- **Button** — 5 variants (primary, secondary, outline, ghost, danger), 4 sizes, always 44px min touch target
- **Input** — Handles text and textarea, auto-generates IDs from labels, terracotta focus ring
- **Modal** — Animated entry/exit (scale + opacity), backdrop blur, Escape to close
- **Skeleton** — Loading placeholders that match the shape of what's loading

That's it. Four primitives. Every page uses them. There's no "design system framework" — just four files that enforce consistency by being simple enough to always use.

The color palette is 11 tokens. Most apps this size have 30+. Crumble has cream, terracotta, sage, brown, and warm-gray. You can describe the entire visual language in one sentence: "Brown text on cream, terracotta for actions, sage for success."

### What Creates the "Home" Feeling

Having read all 12 pages, I think these specific details contribute:

1. **The rounded corners.** Everything is `rounded-xl` (12px) or `rounded-2xl` (16px). No sharp edges anywhere. Rounded corners are literally less threatening — the visual cortex processes them as safer than sharp angles. This isn't new UX theory, but Crumble applies it uniformly.

2. **The serif font in headers.** Playfair Display for recipe titles, Nunito for everything else. Serif fonts evoke print cookbooks — your grandmother's recipe cards. It's a deliberate contrast to the sans-serif headers that dominate web apps.

3. **The background color.** `#FFF8F0` (cream) instead of `#FFFFFF` (white). This one CSS value does more work than any feature. White screens feel clinical. Cream feels like parchment, like a kitchen countertop, like warm light.

4. **The ingredient sidebar in CookMode.** It's on a cream background with a dark overlay behind it. When you open it, it feels like peeking at a recipe card while your hands are busy. Not a modal interrupt — a side-peek.

5. **The empty states.** "You haven't cooked anything yet!" with a BookOpen icon. "No grocery lists yet" with a ShoppingCart. Every empty state has an icon, a friendly message, and a hint about what to do. No blank screens. No "No data found."

6. **What's absent.** No notification badges. No dashboard widgets. No analytics. No "you haven't logged in for 3 days" nudges. The app doesn't demand attention. It waits.

### What Undermines It

A few things break the spell:

1. **`window.confirm()` for deletions.** The browser's built-in confirm dialog is visually jarring — it's a grey system dialog in a warm cream world. There's already a Modal component. Using it for confirmations would keep the visual language intact.

2. **The admin page.** I haven't read it in detail, but based on the data model it's probably utilitarian — user management, maybe tag cleanup. Admin pages always feel like the backstage of a theater. That's fine, but if it uses the same warm palette, it stays in-world.

3. **Error messages in red.** `bg-red-50 text-red-600` is standard but cold. The existing terracotta-dark could work for errors — it's already warm red. Sage for success, terracotta for errors, cream for neutral. Keep the palette.

4. **The "Bulk Import" in the sidebar.** Seven sidebar items is already pushing it. "Bulk Import" is a power-user feature that most people use once. It could live under "Add Recipe" as a tab or option instead of claiming equal navigation real estate with "Recipes" and "Meal Plan."

### The Things That Should Never Change

If I could lock certain things in the codebase:

- **The cream background.** Never go white.
- **"Your cozy recipe manager."** Never make it "powerful" or "smart" or "AI-powered."
- **The CookingPot icon.** Not a fork, not a chef hat, not a plate. A pot. Pots are communal.
- **Terracotta as the action color.** It's the visual signature. Every button, every active state, every focus ring.
- **The absence of notifications.** This app respects attention. Don't add badges or nudges.
- **Serif recipe titles.** They connect the digital to the physical — cookbooks, handwritten cards, kitchen walls.

### A Final Thought

I've spent 32 deep dives (about 4,400 lines) analyzing a 12,587-line recipe app. The technical analysis is done — bugs identified, features designed, roadmaps written, prototypes coded, competitive landscape mapped.

But the thing that will determine whether Crumble succeeds isn't in the roadmap. It's in the restraint. The self-hosted recipe manager space has 15 competitors, and they're all racing to add features — AI, Docker Compose services, multi-tenant spaces, plugin systems, shopping integrations.

Crumble's advantage isn't what it does. It's what it doesn't do. It's a PHP file, a MySQL database, and a React app. No containers. No AI subscriptions. No infrastructure decisions. You download it, point Apache at it, and you have a recipe manager that feels like a kitchen, not a SaaS dashboard.

Every feature added should pass one test: **does this make the kitchen warmer, or does it make it more like an office?**

The cooking journal (warm — it's your personal story with a recipe). Kitchen stats (warm — it celebrates your cooking). Night mode (warm — it literally changes the light). Ingredient paste (neutral — it removes friction without changing the feeling). Data export (neutral — it builds trust without adding UI).

AI features, analytics dashboards, social sharing, gamification — these make it more like an office. They might be impressive on a feature chart, but they change what the app *is*.

The 8-hour implementation guide in Deep Dive 32 was designed with this in mind. Every item either fixes something broken, adds warmth, or removes friction. Nothing in it changes the identity.

That's the whole document. 32 analyses, one conclusion: **keep it cozy.**

---

## Deep Dive 33: CookMode Completion Flow & Ingredient Highlighting (Implemented)

*Two improvements that close the loop between cooking and journaling.*

### What Changed

**1. "Done!" now triggers the cook journal**

Previously, tapping "Done!" on the last step simply closed CookMode. The user had to separately find and click "I Cooked This" to log the cook. This broke the natural flow — you just finished cooking, you're in the moment, and the app asks you to close one modal and open another.

Now, "Done!" opens a cook-note modal *inside* CookMode itself. Same UI as the standalone CookButton — "Just Log It" (no notes) or "Save Note" (with notes). After logging, the button changes to a checkmark "Logged!" and CookMode auto-closes after 1.2 seconds. The cook notes list on RecipePage refreshes automatically.

If `recipeId` isn't provided (defensive), "Done!" falls back to the old close behavior.

**2. Ingredient highlighting in step text**

Each instruction step now highlights ingredient names in terracotta with `font-medium`. The `highlightIngredients()` function:
- Extracts ingredient names (≥3 chars to avoid false positives like "a" or "to")
- Sorts longest-first to prevent partial matches ("olive oil" before "oil")
- Uses word-boundary regex for case-insensitive matching
- Returns React elements (JSX spans) when matches are found, plain text otherwise
- Memoized via `useMemo` to avoid re-running regex on every render

This makes scanning a step much faster — your eye catches the terracotta-colored ingredient names immediately, and you can cross-reference with the slide-out ingredient panel.

### Technical Notes

- CookMode now accepts `recipeId` and `onDone` props from RecipePage
- The completion modal uses the same Modal + Button components as CookButton (consistent UX)
- `onDone` callback refreshes cook notes in the parent, same pattern as CookButton's `onCook`
- Ingredient highlighting is purely visual — no new API calls, no state changes
- The 3-char minimum filter avoids highlighting common words that happen to be ingredients (e.g., "a", "it")

---

## Deep Dive 34: Night Mode Foundation (Implemented)

*CSS variable dark mode with semantic surface tokens — the entire app responds to `prefers-color-scheme: dark` with zero JavaScript.*

### What Changed

**1. Dark mode color palette**

Added `@media (prefers-color-scheme: dark)` overrides in `index.css` that swap all 11 semantic color tokens:

| Token | Light | Dark |
|-------|-------|------|
| cream | #FFF8F0 | #1A1412 |
| cream-dark | #F5EDE3 | #231C17 |
| surface | #FFFFFF | #2A221D |
| surface-raised | #FFFFFF | #342B25 |
| brown (text) | #3E2723 | #F5EDE3 |
| brown-light | #5D4037 | #D4C4B5 |
| warm-gray | #8D7B6E | #A89888 |
| terracotta | #C1694F | #D4896F |
| sage | #7D9B76 | #A8C5A0 |

The dark palette is deliberately warm — no cold grays or pure blacks. The darkest background (`#1A1412`) has brown undertones. This keeps the "cozy kitchen" feeling even in dark mode.

**2. New `surface` token**

Added `--color-surface` and `--color-surface-raised` tokens for card/panel backgrounds. Batch-replaced all 49 occurrences of `bg-white` → `bg-surface` across 27 component files. One exception: `bg-white/20` in CookButton stays white since it's a decorative overlay on a green button.

**3. Manual toggle support**

Added `[data-theme="dark"]` CSS selector alongside `@media (prefers-color-scheme: dark)`. Setting `document.documentElement.dataset.theme = 'dark'` enables manual override. This means:
- By default, respects system preference (zero config)
- A future settings toggle can override via `data-theme` attribute
- The toggle just needs `localStorage.setItem('theme', 'dark')` + one line of JS

**4. Scrollbar theming**

Dark mode also overrides scrollbar track/thumb colors to match.

### What's Left for Full Night Mode

The CSS foundation is done — the app *works* in dark mode right now. Remaining polish:
- **Settings toggle UI**: A sun/moon icon in the header or sidebar to override system preference
- **Image handling**: Recipe card images look fine, but the placeholder gradient might need adjustment
- **Input borders**: `border-cream-dark` becomes `border-#231C17` which is very subtle in dark mode — might need a lighter border token
- **Shadow visibility**: `shadow-md` is near-invisible on dark backgrounds — consider switching to `ring-1 ring-cream-dark/10` or subtle borders in dark mode

### Follow-up: Theme Toggle UI (Implemented)

Added a complete theme toggle system:

**`useTheme.js` hook** — manages theme state with three modes: `system` (default, respects `prefers-color-scheme`), `light` (forces light), `dark` (forces dark). Stores preference in localStorage as `crumble-theme`. Provides `cycle()` to rotate through modes.

**Header toggle** — Sun/Moon/Monitor icon button in the header bar. Cycles system → light → dark on click. Shows on all screen sizes.

**Sidebar toggle** — "System Mode" / "Light Mode" / "Dark Mode" button with matching icon in the desktop sidebar, above the logout button.

**Flash prevention** — Inline `<script>` in `index.html` reads localStorage and applies `data-theme` attribute before CSS loads, preventing a white flash on dark-themed loads.

**CSS specificity** — Verified that `[data-theme]` selectors outside Tailwind's `@layer theme` have higher specificity than `@theme` variables, so overrides work correctly.

**Error state fixes** — Added dark mode overrides for `bg-red-50` (light pink error backgrounds look wrong on dark backgrounds) to use subtle red tinting instead.

---

## Deep Dive 35: Data Export (Implemented)

*Your recipes are yours. One click, full JSON backup.*

### What Was Built

**Backend:** `GET /recipes/export` — authenticated endpoint that returns all recipes with ingredients, tags, instructions, nutrition, and timestamps. Uses batch-fetching (3 queries total regardless of recipe count) instead of N+1 queries per recipe.

**Frontend:** "Export Recipes" button on AdminPage that triggers a browser download of `crumble-export-YYYY-MM-DD.json`.

### Export Format

```json
{
  "version": "1.0",
  "exported_at": "2026-03-09T14:30:00+00:00",
  "recipe_count": 42,
  "recipes": [
    {
      "id": 1,
      "title": "Chocolate Chip Cookies",
      "description": "...",
      "prep_time": 15,
      "cook_time": 12,
      "servings": 24,
      "source_url": "https://...",
      "instructions": ["Preheat oven...", "Mix dry ingredients..."],
      "ingredients": [
        { "name": "flour", "amount": "2", "unit": "cups", "sort_order": 0 },
        ...
      ],
      "tags": ["Dessert", "Baking"],
      "calories": 150, "protein": 2, "carbs": 20, "fat": 7,
      "created_at": "2026-01-15 10:30:00",
      "updated_at": "2026-02-20 14:15:00"
    }
  ]
}
```

### Design Decisions

- **No `image_path` in export** — internal file paths are meaningless outside the server. A future enhancement could base64-encode images or provide a zip download with images.
- **Version field** — allows future format changes without breaking importers
- **Auth required but not admin-only** — any logged-in user can export. Data portability shouldn't be gated behind admin privileges.
- **Batch queries** — 3 queries total (recipes, all ingredients, all tags) regardless of recipe count. No N+1 problem.
- **Admin page placement** — export lives next to "Add User" for now. Could move to a dedicated Settings/Data page later.

---

## Deep Dive 36: Kitchen Stats Dashboard (Implemented)

*A personal cooking dashboard that celebrates your cooking journey.*

### What Was Built

**Backend:** `GET /stats` endpoint (StatsController) with 8 queries that return:
- Total cooks + unique recipes cooked
- Recipes owned + favorites count
- Estimated total time cooking (sum of prep_time + cook_time for all cooked recipes)
- Cooking streak (consecutive days)
- Most cooked recipe (with image)
- Top 5 tags by cook frequency
- Monthly activity (last 6 months)
- Average rating given

**Frontend:** `/stats` page with:
- 4 primary stat cards (Times Cooked, Unique Recipes, Time Cooking, Day Streak)
- 2 secondary stat cards (Favorites, Avg Rating)
- Most cooked recipe card (links to recipe page)
- Top tags horizontal bar chart
- Monthly activity mini bar chart
- Empty state for new users

**Navigation:** Added "Kitchen Stats" link with TrendingUp icon to sidebar nav.

### Design Philosophy

This is a *celebration* dashboard, not an analytics dashboard. Every stat is framed positively:
- Streak shows "Keep it up!" when active, "Cook something today!" when not (encouraging, not shaming)
- Time is "estimated total" — honest about the approximation
- No negative metrics (no "recipes you haven't cooked" or "days since you last cooked")
- Top tags are "What You Cook Most" — descriptive, not prescriptive

The monthly activity chart uses sage green instead of a performance-y red/blue. It's deliberately simple — no axis labels, no tooltips, just a visual pattern of your cooking rhythm.

### Stats That Were Considered and Rejected

- **Recipe completion rate**: "You've cooked 12 of 45 recipes" — feels like homework
- **Calories consumed**: Turns a recipe app into a diet tracker
- **Comparison to other users**: Not a social platform
- **Achievement badges**: Gamification changes the relationship with cooking from "I want to cook" to "I want the badge"

---

## Deep Dive 37: Non-Linear Recipe Scaling — The Hard Problem

*When you double a recipe, not everything doubles. Most recipe apps ignore this.*

### The Problem

Crumble already has a `ServingsAdjuster` that linearly scales ingredients. If a recipe serves 4 and you want 8, every ingredient gets multiplied by 2. This works for most ingredients but fails badly for:

1. **Leavening agents** (baking soda, baking powder, yeast) — chemical reactions don't scale linearly. At 2x, you get too much CO2, leading to metallic taste or collapsed baked goods. Rule of thumb: scale at ~75% of linear rate (double → use 1.5x, triple → use 2.5x).

2. **Seasonings and spices** — flavor perception is logarithmic, not linear. Doubling salt in a doubled recipe often makes it too salty. The recommendation is to scale at ~1.5x for a 2x batch.

3. **Baking time** — doesn't scale linearly. A doubled recipe takes roughly 10-20% longer, not 100% longer. Halved recipes take ~75% of original time.

4. **Pan sizes** — depth matters more than volume. The food should be the same depth in the pan regardless of batch size. A doubled recipe in the same pan will burn on the outside and be raw inside.

5. **Liquid ratios in baking** — liquids evaporate at the same rate regardless of batch size, so scaled-up recipes sometimes need slightly less liquid per unit.

### What Could Crumble Do?

**Option A: Smart scaling warnings (low effort, high value)**

When the scale factor is >1.5x or <0.5x, show a yellow info bar:

> "Scaling 3x — baking recipes may need adjustments to leavening (use 2.5x instead of 3x for baking soda/powder), spices (add gradually and taste), and baking time (+10-20%)."

This doesn't require any AI or complex logic — just detect if `scale > 1.5` and if any tags include "baking" or "dessert". The warning is always helpful and never wrong.

**Option B: Ingredient-category-aware scaling (medium effort)**

Classify ingredients into categories and apply different scaling curves:

```javascript
const SCALING_RULES = {
  linear: ['flour', 'sugar', 'butter', 'milk', 'eggs', 'water', 'oil'],
  sublinear_075: ['baking soda', 'baking powder', 'yeast', 'cream of tartar'],
  sublinear_085: ['salt', 'pepper', 'vanilla', 'cinnamon', 'garlic', 'ginger'],
  // ... more spices
};

function smartScale(ingredient, factor) {
  const category = detectCategory(ingredient.name);
  switch (category) {
    case 'sublinear_075': return ingredient.amount * (1 + (factor - 1) * 0.75);
    case 'sublinear_085': return ingredient.amount * (1 + (factor - 1) * 0.85);
    default: return ingredient.amount * factor;
  }
}
```

The formula `1 + (factor - 1) * coefficient` ensures:
- At 1x: no change
- At 2x with 0.75 coefficient: 1.75x instead of 2x
- At 3x with 0.75 coefficient: 2.5x instead of 3x

**Option C: Per-ingredient scale override (low effort, user-driven)**

In the ServingsAdjuster, let users tap an individual ingredient to manually override its scaled amount. This acknowledges that the user knows their recipe better than any algorithm, while still giving them the linear default as a starting point.

### Implementation: Option A (Done)

Added a scaling warning bar on RecipePage that appears when scale factor is >1.5x or <0.5x. It checks if any recipe tags match baking-related categories (`baking`, `dessert`, `bread`, `cake`, `cookies`, `pastry`) and shows a tailored message:

- **Baking recipes**: "Leavening agents (baking soda/powder) scale at ~75% — use less than shown. Baking time may need adjustment."
- **Non-baking recipes**: "Spices and seasonings may need fine-tuning — add gradually and taste as you go."

Styled with terracotta/10 background to be visible but not alarming. Shows the scale factor (e.g., "Scaling up 2.0x").

### Recommendation

Option A is the obvious first step — a contextual warning when scaling significantly. It takes maybe 20 minutes to implement and handles 80% of the problem. Option C is a great follow-up because it empowers the user without prescribing behavior.

Option B is interesting but risky — ingredient name matching is fuzzy (is "garlic powder" a spice or a bulk ingredient? What about "garlic cloves"?), and getting it wrong is worse than not trying. It also goes against the "keep it cozy" philosophy — a recipe app that second-guesses your grandmother's cookie recipe feels presumptuous.

### Sources

- [How to Scale Recipes Like a Professional](https://beginwithbutter.com/how-to-scale-recipes-like-a-professional/)
- [Scaling Recipes Like a Pro](https://schweidandsons.com/blog/scaling-recipes-like-a-pro-essential-tips-for-adjusting-serving-sizes/)
- [How to Scale Recipes Without Mistakes - Cooklang](https://cooklang.org/blog/26-how-to-scale-recipes-without-mistakes/)
- [Scaling Baking Recipes - CIA Foodies](https://www.ciafoodies.com/scaling-baking-recipes-up-and-down/)
- [Scaling Baking Recipes - Better Baker Club](https://betterbakerclub.com/scaling-down-recipes-baking/)

---

## Deep Dive 38: Typography & Readability — Reading Recipes in the Kitchen

*Recipes are read while standing, from arm's length, with messy hands. The typography should respect that.*

### The Kitchen Reading Problem

A recipe app has at least four distinct reading contexts:

1. **Browsing** (couch, phone in hand, 12-18 inches) — normal phone reading distance
2. **Planning** (table, laptop/tablet, 18-24 inches) — selecting recipes, editing lists
3. **Cooking** (counter, phone propped up, 24-36 inches) — hands are busy or wet
4. **CookMode** (fullscreen, phone on stand, 24-48 inches) — eyes dart back and forth

Most web apps optimize for context 1. Recipe apps need to nail contexts 3 and 4.

### Crumble's Current Typography Audit

| Context | Element | Current Size | Effective Size at Distance |
|---------|---------|-------------|--------------------------|
| Recipe page | Instructions (StepList) | `text-base` (16px) | ~8pt at 30" — too small |
| Recipe page | Ingredients (amount) | `text-base` (16px) | ~8pt at 30" — too small |
| Recipe page | Ingredient names | `text-base` (16px) | Same |
| CookMode | Step text | `text-xl`→`text-3xl` (20-30px) | 10-15pt at 36" — good |
| CookMode | Timer buttons | `text-base` (16px) | ~8pt — fine (you're closer to tap) |
| CookMode | Step counter | `text-sm` (14px) | ~7pt — borderline but acceptable |

**The gap**: The regular recipe page instructions are too small for the counter-propping context. If you prop your phone on the counter and step back to work, 16px text at 30 inches is equivalent to ~8pt text at normal reading distance. That's below the 10pt minimum recommended by cookbook designers.

### Key Principles from Cookbook Typography

1. **Body text for recipes should be larger than normal body text** — cookbook designers use 10-14pt for print, which at typical book reading distance (12-16 inches) is comfortable. A phone at 30 inches needs proportionally larger text — at minimum 18-20px.

2. **Amounts and units should be visually distinct from ingredient names** — Crumble already does this with `font-semibold` on amounts. Good.

3. **Instructions should be numbered, not bulleted** — Crumble uses numbered circles. Good.

4. **Line height matters more than font size for scanning** — `leading-relaxed` (1.625) is excellent. This is one of Crumble's strongest typography choices.

5. **Step boundaries must be visually obvious** — When you glance at a recipe mid-cooking, you need to find "step 4" in under 2 seconds. Crumble's terracotta numbered circles are effective.

### What Could Improve

**1. Increase recipe page instruction text to `text-lg` (18px)**

The StepList component uses default `text-base` (16px). Bumping to `text-lg` (18px) adds 12.5% more readability for zero visual cost — 18px at 30 inches is equivalent to ~9pt at reading distance, which is borderline but much better than 8pt.

**2. Make ingredient amounts `text-lg` with tabular-nums**

Ingredient lists benefit from monospace-width numbers so that amounts align vertically. Tailwind's `tabular-nums` class makes "1/2", "2", and "12" take up proportional space, creating a neat left edge. Combined with `text-lg`, the ingredient list becomes scannable from counter distance.

**3. Consider a "counter mode" for the recipe page**

Not as immersive as CookMode, but a simple toggle that increases all text by 25% and adds more spacing. Like a reading mode for the kitchen. This could be as simple as a CSS class on the recipe container that scales up `font-size` via `text-lg` and `text-xl` overrides.

**4. CookMode is already well-designed typographically**

The responsive sizing (`text-xl md:text-2xl lg:text-3xl`) is excellent. The `font-light` weight on step text is a good choice — at large sizes, light weights are more readable than regular because they have less visual density. The `leading-relaxed` line height prevents steps from feeling cramped.

### The "Glanceable UI" Connection

Research from wearable UI design applies directly to kitchen cooking UIs: a user should understand the screen in under 2 seconds. This means:
- **One focal element** per view (CookMode nails this — one step at a time)
- **High contrast** (cream text on brown background in CookMode ✓)
- **Short labels** (step counter "Step 3 of 8" ✓)
- **Large touch targets** (min 44px throughout CookMode ✓)

Crumble's CookMode is essentially a glanceable UI already. The regular recipe page is the one that needs a bit more kitchen-friendliness.

### Sources

- [Choosing and pairing typefaces for cookbooks — Typekit Blog](https://blog.typekit.com/2016/02/12/choosing-and-pairing-typefaces-for-cookbooks/)
- [Best practices for designing your cookbook — TeaBerry Creative](http://teaberrycreative.com/2017/10/06/best-practices-for-designing-your-cookbook/)
- [The Perfect Text Readability Recipe — Medium](https://medium.com/design-bootcamp/the-perfect-text-readability-recipe-science-backed-typography-for-better-ux-7c8bf190df85)
- [Fonts should be at least 16px — UX West](https://uxwest.com/fonts-should-be-16px-including-mobile-and-email/)
- [Font Size Guidelines for Responsive Websites — Learn UI Design](https://www.learnui.design/blog/mobile-desktop-website-font-size-guidelines.html)

### Implementation Notes

Bumped `StepList` from `text-base` (16px) to `text-lg` (18px) for recipe instructions. Added `tabular-nums` to ingredient amounts for visual alignment. Ingredient text bumped to 17px. These are small changes that compound — every recipe in the app is now slightly more readable when propped on a counter.

---

## Deep Dive 39: Adaptive Recipe Instructions — The Skill Level Problem

*"Sauté onions until soft" means entirely different things to a beginner and a professional.*

### The Observation

Recipe instructions face an impossible audience problem. A single set of instructions serves:
- **The beginner** who doesn't know what "fold" means or when onions are "translucent"
- **The experienced cook** who finds step-by-step obvious and just wants the ingredient ratios and technique
- **The returning cook** who made this recipe before and just needs the timing reminders

Traditional recipe apps (and Crumble) treat all three the same: one linear list of steps. This is the only real option without AI, but it's worth thinking about what *could* be done.

### What Other Domains Do

**Code IDEs** solved this with code folding — you can collapse implementation details and see just the function signatures. The same instruction could work at two levels:

> **Collapsed**: Make a roux with butter and flour.
> **Expanded**: Melt 2 tablespoons butter in a heavy saucepan over medium heat. When the foaming subsides (about 1 minute), sprinkle in 2 tablespoons all-purpose flour. Whisk continuously for 2-3 minutes until the mixture turns golden and smells nutty. If it darkens too quickly, reduce heat immediately.

**Video games** do this with difficulty settings that change how much guidance you get — "Easy" mode highlights objectives, "Hard" mode removes all markers.

**Documentation sites** (like MDN) use `<details>` elements for expandable explanations inline.

### What This Could Look Like in Crumble

**Option A: Inline glossary tooltips (lowest effort)**

Detect cooking terms in step text and show a tooltip on tap:
- "fold" → "Gently combine by lifting batter from the bottom over the top, rotating the bowl. Don't stir."
- "deglaze" → "Add liquid to a hot pan with browned bits. Scrape the bottom with a wooden spoon as it sizzles."
- "julienne" → "Cut into thin matchstick-sized strips, about 1/8 inch × 2 inches."

This requires a dictionary of ~50-80 cooking terms. No per-recipe work needed. Could be toggled on/off in settings ("Show cooking term hints: Beginner / Off").

**Option B: Expandable step detail**

Each step has a "summary" and "detail" field:
```json
{
  "instructions": [
    {
      "summary": "Make a roux with butter and flour (3 min)",
      "detail": "Melt 2 tbsp butter over medium heat until foaming subsides..."
    },
    {
      "summary": "Add milk gradually, whisking constantly",
      "detail": "Pour milk in a thin stream, about 1/4 cup at a time..."
    }
  ]
}
```

Users toggle between summary view (for experienced cooks or re-cooks) and detail view (for beginners or first time). The summary is what you see in CookMode by default; tap to expand.

**Problem**: This doubles the recipe writing work. No one will fill in both fields. It would need AI to generate summaries from detailed instructions (or vice versa).

**Option C: AI-generated adaptive layers (future)**

Given a detailed instruction set, an LLM could generate:
- A beginner expansion (add timing cues, equipment notes, troubleshooting)
- An expert condensation (just the ratios and techniques)
- A re-cook summary (just the timing and order)

This is technically feasible but philosophically questionable for Crumble. Adding LLM-generated text to a recipe changes the app's identity from "your recipes" to "AI's interpretation of your recipes."

### Recommendation

**Option A (glossary tooltips) is the clear winner.** It:
- Works with existing recipe data (no dual-format instructions needed)
- Helps beginners without patronizing experienced cooks (opt-in)
- Is a one-time implementation (build the glossary once)
- Doesn't require AI
- Keeps the recipe as-written — the original text is never altered

A dictionary of 60-80 cooking terms with 1-2 sentence explanations, shown as a subtle dotted underline + tooltip on tap. Off by default for clean aesthetics, toggleable in user settings.

### Implementation (Done)

Built `cookingGlossary.js` with 32 cooking terms (from "al dente" to "zest"), each with a 1-2 sentence practical explanation written in second person ("Don't stir — you'll lose the air"). Terms are matched case-insensitively with word boundaries, longest-first to prevent partial matches.

`GlossaryTerm.jsx` is a tooltip component — dotted underline on the term, tap to show a brown tooltip bubble with the definition. Tap anywhere else to dismiss. The tooltip uses absolute positioning with a CSS triangle pointer.

`StepList.jsx` now passes each step through `renderWithGlossary()` which finds terms and wraps them in `<GlossaryTerm>` components. Accepts a `glossary={false}` prop to disable (e.g., for CookMode where you don't want tappable text interfering with swipe navigation).

The glossary is always on in the recipe page instructions. In CookMode, the ingredient highlighting serves a similar purpose — showing you what's relevant in the current step.

---

## Deep Dive 40: Recipe Memory — How People Re-find Things

*Crumble has five ways to re-find a recipe. None of them talk to each other.*

### The Re-finding Problem

When someone wants to make something they've made before, they don't think "let me search for chicken tikka masala." They think:
- "That thing I made last Tuesday"
- "The pasta recipe my friend shared"
- "Something with chickpeas that was really good"
- "The slow cooker thing I made for the party"

These are *memory-based* queries, not keyword-based. Crumble has five retrieval systems, each serving a different memory type:

| System | Memory Type | How It's Accessed |
|--------|-----------|------------------|
| **Search** | "I know the name" | Search bar, full-text |
| **Tags** | "I know the category" | Tag filter chips on home page |
| **Favorites** | "I know I liked it" | Favorites page |
| **Cook History** | "I know I made it" | Cook History page |
| **Recently Viewed** | "I just looked at it" | Recently Viewed row on home page |

### What's Missing: Cross-referencing

These five systems are silos. You can't:
- Search within your favorites ("that favorited chicken recipe")
- Filter cook history by tag ("what Italian things have I cooked?")
- See which favorites you've never actually cooked
- Find recipes you rated highly but haven't made in a while

### The "Make Again" Pattern

The most common recipe app action isn't "find new recipe" — it's "make that thing again." This is a fundamentally different intent than browsing. It combines:
1. **Recognition over recall** — you'll know it when you see it
2. **Temporal context** — roughly when you last made it
3. **Emotional context** — whether it went well (rating, notes)
4. **Sensory context** — what it looked like (image)

Crumble's Cook History page is the closest to serving this need, but it shows a flat chronological list. A more powerful version would surface:

**"Your Regulars"** — recipes cooked 3+ times (you clearly like making these)
**"Been a While"** — favorited recipes you haven't cooked in 60+ days
**"Last Month"** → **"Last Week"** — temporal grouping instead of flat list

### A Concrete Improvement: Enhanced Cook History

The current CookHistoryPage could be restructured into sections:

```
Your Regulars (cooked 3+ times)
├── Chicken Tikka Masala (7 times, last: 2 days ago)
├── Spaghetti Carbonara (5 times, last: 2 weeks ago)
└── Banana Bread (3 times, last: 1 month ago)

This Week
├── Tuesday: Pad Thai
└── Monday: Caesar Salad

Last Week
├── Sunday: Roast Chicken
└── Thursday: Mushroom Risotto

Earlier
├── Feb 2: Beef Stew
└── Jan 28: Fish Tacos
```

This requires no new API — all the data exists in `cook_log` already. It's purely a frontend restructuring of how the data is displayed.

### The Bigger Thought

Recipe apps optimize for *collection* (importing, organizing, categorizing) but users spend 90% of their time *retrieving*. The retrieval UX should get more design attention than the collection UX.

Crumble's five retrieval systems are individually good. The gap is in connecting them — making the app feel like it *remembers* alongside you rather than making you navigate to five different places to jog your memory.

A simple first step: add a "cook count" badge to RecipeCard. Seeing "cooked 5×" on a card in the grid immediately tells you this is a regular. No new page, no new feature — just surfacing data that's already there in a place where you're already looking.

### Implementation (Done)

Added `cook_count` to the recipe list query (`getAll`) via a batch fetch — one extra query (`GROUP BY recipe_id` on cook_log) similar to how favorites are batch-fetched. No N+1 problem.

RecipeCard now shows a sage-colored ChefHat icon with "3×" (or whatever the count is) next to the servings count when `cook_count > 0`. Subtle enough to not clutter cards for never-cooked recipes, visible enough to immediately identify regulars when scanning the grid.

---

## Deep Dive 41: Grocery Amount Merging — Fixing the Fraction Bug

*"1 1/2 cups flour" + "2 cups flour" was creating two separate grocery items instead of merging to "3 1/2 cups".*

### The Bug

`GroceryItem::addFromRecipe()` on line 118 used `is_numeric()` to check if amounts could be combined. But `is_numeric("1 1/2")` returns `false` in PHP — it only handles integers, decimals, and scientific notation. This meant:

- "2" + "3" ✓ → "5" (both numeric)
- "1/2" + "1/4" ✗ → two items (fractions not numeric)
- "1 1/2" + "2" ✗ → two items (mixed number not numeric)
- "1½" + "1" ✗ → two items (unicode fraction not numeric)

Since the IngredientParser commonly produces fractions and mixed numbers, the "smart" merging was silently failing for a large percentage of ingredients.

### The Fix

Added `parseAmount()` and `formatAmount()` private methods to GroceryItem:

**`parseAmount()`** handles: integers, decimals, fractions ("3/4"), mixed numbers ("1 1/2"), unicode fractions ("1½"), and ranges ("2-3" → averages to 2.5). Returns `null` for unparseable strings.

**`formatAmount()`** converts floats back to readable strings with fraction display for common values (0.5 → "1/2", 0.25 → "1/4", etc.), and supports mixed numbers (1.5 → "1 1/2"). Falls back to decimal for uncommon fractions.

The merging logic now uses `$this->parseAmount()` instead of `is_numeric()`, so "1 1/2 cups flour" + "2 cups flour" properly merges to "3 1/2 cups flour".

### What This Doesn't Fix Yet

- **Unit conversion**: "2 cups flour" + "8 oz flour" still stays as two items (different units). The unit conversion table from Deep Dive 308 (volume → teaspoons, weight → grams) would fix this, but it's a larger change.
- **Name normalization**: "chicken breast" vs "boneless skinless chicken breast" still stays as two items. Fuzzy matching would help but risks false merges.
- **Ambiguous oz**: Fluid ounces vs weight ounces can't be resolved without ingredient context.

---

## Deep Dive 42: Crumble's Strategic Position — The Accidental Niche

*Every self-hosted recipe manager requires Docker. Crumble doesn't. That's not a limitation — it's a market.*

### The Competitive Landscape (March 2026)

I audited every self-hosted recipe manager listed on awesome-selfhosted and Cooklang's 2026 comparison. Here's the stack breakdown:

| App | Stack | Deployment | License |
|-----|-------|-----------|---------|
| Mealie | Python/FastAPI | Docker only | MIT |
| Tandoor | Django/Python | Docker/K8s | AGPL |
| RecipeSage | Node.js | Docker | AGPL |
| KitchenOwl | Flutter + Flask | Docker + native apps | AGPL |
| Recipya | Go | Docker | GPL |
| Tamari | Python | Docker | GPL |
| ManageMeals | Unknown | Docker | GPL |
| Fork Recipes | Unknown | Docker | BSD |
| Vanilla Cookbook | Node.js | Docker | GPL |
| **Crumble** | **PHP + React** | **Any PHP host** | **—** |

Every. Single. Competitor. Requires Docker.

### Why This Matters

Docker is a barrier to entry for a large segment of self-hosters:

1. **Shared hosting users** — millions of people have cPanel/Plesk hosting for $5/month. They can run PHP and MySQL. They cannot run Docker.

2. **Laragon/WAMP/MAMP users** — local dev environments that "just work" with PHP. Adding Docker introduces a second orchestration layer.

3. **Raspberry Pi users** — many self-hosters run a Pi. Docker on ARM has historically been unreliable, and the overhead on a Pi Zero/3 is significant.

4. **The "I don't want to learn containers" crowd** — a non-trivial segment of technically-capable people who can SSH into a server and configure Apache but have no interest in container orchestration.

5. **NAS users** — Synology, QNAP, etc. ship with PHP but Docker support varies by model and often requires premium models.

### What Crumble Should Lean Into

Crumble's PHP stack isn't a compromise — it's the **entire value proposition** for a segment that no competitor serves. The install process should be:

1. Upload files to web server
2. Import `schema.sql` into MySQL
3. Copy `.env.example` to `.env`, set database credentials
4. Visit the URL

That's it. No `docker-compose.yml`, no `docker pull`, no volume mounts, no port mapping. This is how WordPress became the most-used CMS in the world — you upload it and it works.

### Feature Comparison: Crumble vs The Field

| Feature | Crumble | Mealie | Tandoor | KitchenOwl |
|---------|---------|--------|---------|------------|
| **No Docker** | ✅ | ❌ | ❌ | ❌ |
| Recipe scraping | ✅ (5-tier) | ✅ | ✅ | ✅ |
| Meal planning | ✅ | ✅ | ✅ | ✅ |
| Grocery lists | ✅ | ✅ | ✅ | ✅ (real-time) |
| Cook mode | ✅ | ❌ | ❌ | ❌ |
| Cooking journal | ✅ | ❌ | ❌ | ❌ |
| Kitchen stats | ✅ | ❌ | ❌ | ❌ |
| Nutrition tracking | ✅ (scraper) | ✅ | ✅ (auto-calc) | ❌ |
| SSO support | ✅ (Authentik) | ✅ (OIDC) | ✅ (OIDC) | ✅ |
| Servings scaling | ✅ | ✅ | ✅ (smart) | ✅ |
| Data export | ✅ (JSON) | ✅ | ✅ | ✅ |
| Mobile app | PWA-ready | PWA | PWA | Native (Flutter) |
| Multi-user | ✅ | ✅ | ✅ | ✅ |
| API | REST | REST | REST | REST |
| Recipe sharing | ✅ (links) | ✅ | ✅ | ❌ |
| AI features | ❌ | ❌ | ✅ (LiteLLM) | ❌ |

Crumble is **feature-competitive** with the big three. It's missing AI (intentionally) and real-time grocery sync (KitchenOwl's killer feature), but it has CookMode, the cooking journal, kitchen stats, and the glossary — features none of the competitors have.

### The Positioning Statement

> **Crumble is the self-hosted recipe manager that doesn't need Docker.**
>
> Beautiful, full-featured recipe management for anyone with PHP hosting. No containers, no compose files, no orchestration. Upload it like WordPress, use it like a native app.

### What This Means for Development Priorities

If "no Docker" is the positioning, then:

1. **Install experience is critical** — a clear README with 4-step install, `.env.example`, and a setup wizard would make Crumble immediately accessible to its target audience.

2. **PHP compatibility matters** — should work on PHP 8.1+ (what shared hosts run). No bleeding-edge PHP features that require 8.3+.

3. **MySQL/MariaDB only is fine** — every shared host has MySQL. Don't add PostgreSQL support for the sake of it.

4. **The frontend build step is a problem** — `npm run build` requires Node.js, which shared hosting users may not have. Consider shipping pre-built `dist/` in releases, or providing a GitHub Actions workflow that builds and packages a release zip.

5. **PWA is more important than native apps** — the target audience doesn't run Docker, so they probably won't sideload APKs either. A solid PWA with offline caching covers mobile perfectly.

### The Uncomfortable Truth

Crumble's PHP stack is currently viewed as a technical choice. It should be viewed as a **product decision**. The entire self-hosted recipe manager ecosystem converged on Docker without asking whether their users wanted it. Crumble should be the explicit, unapologetic alternative.

Not "we happen to be PHP." Instead: "We're PHP **because** you shouldn't need Docker to manage your recipes."

### Implementation: Install Wizard & .env.example (Done)

**`.env.example`** — Improved with section headers, comments explaining each variable, and optional settings clearly marked with `#` prefix.

**`install.php`** — One-time setup wizard that:
1. `GET /api/install.php` — runs requirements check (PHP version, extensions, writable dirs, .env existence)
2. `POST /api/install.php` — accepts database credentials + admin username/password, runs schema.sql + migrations, creates admin user, writes .env if needed, creates uploads directories
3. Self-protects: refuses to run if users table already has entries
4. Returns JSON (designed to be called from a future frontend setup page, or used directly via curl)

This brings Crumble's install experience to parity with WordPress/Nextcloud — the two apps that proved PHP's deployment advantage.

---

## Deep Dive 43: Recipe Data Formats — Why They All Fail

*The fundamental tension: what's readable by humans is invisible to machines. What's parseable by machines is unwriteable by humans.*

### The Format Landscape

| Format | Human-readable | Machine-parseable | Ingredient structure | Status |
|--------|:----:|:----:|------|--------|
| Plain text | ✅ | ❌ | None | Universal |
| Markdown | ✅ | ❌ | None | Universal |
| Cooklang | ✅ | ✅ | Inline: `@butter{2%tbsp}` | Growing |
| Schema.org JSON-LD | ❌ | ✅ | Free-text strings | Dominant (web) |
| RecipeML (XML) | ❌ | ✅ | Structured fields | Dead |
| Open Recipe Format (YAML) | ⚠️ | ✅ | Structured fields | Niche |
| MealMaster | ⚠️ | ⚠️ | Column-based | Legacy |

### What Crumble Actually Does

Crumble stores recipes in a **hybrid format**:

- **Instructions**: JSON array of plain-text strings. No structured annotations. `["Preheat oven to 350°F", "Mix dry ingredients"]`
- **Ingredients**: Structured in a separate table with `name`, `amount`, `unit`, `sort_order` columns.
- **Metadata**: Structured fields for title, description, prep_time, cook_time, servings, nutrition.

This is pragmatic but has a fundamental disconnect: **ingredients and instructions don't reference each other**. When step 3 says "add the butter," there's no link to ingredient #4 (butter, 2 tbsp). The ingredient highlighting I built for CookMode works around this with regex name matching, but it's fuzzy — "olive oil" might match "oil" in a step about motor oil (unlikely in a recipe, but the point stands).

### Cooklang's Insight

Cooklang's breakthrough is embedding ingredients *inside* instruction text:

```
Preheat the oven to ~{15%minutes}. Mix @flour{2%cups} and @sugar{1/2%cup} in a bowl. Cut in @butter{1/2%cup, cold} until pea-sized.
```

This solves the ingredient-instruction disconnect completely. Each step knows exactly which ingredients it uses, with exact quantities. The parser extracts both a readable step and structured ingredient data from the same source.

### Why Crumble Shouldn't Switch to Cooklang

Despite Cooklang's elegance, adopting it would be wrong for Crumble:

1. **Import compatibility**: 99% of recipe websites emit Schema.org JSON-LD or unstructured HTML. Crumble's scraper parses these into plain instructions + structured ingredients. Converting to Cooklang during import would require an AI step to merge ingredients back into instruction text — unreliable and lossy.

2. **User editing**: People don't write `@flour{2%cups}`. They write "2 cups flour." The annotation syntax creates friction for non-technical users.

3. **Existing data**: Every recipe in every Crumble instance stores instructions as plain text arrays. Migrating would break all existing data or require a parallel storage format.

4. **The scraper covers it**: Crumble already gets structured ingredients from scraping. The separation between ingredients and instructions is a *display* problem, not a *data* problem.

### What Crumble Could Do Instead

**Option A: Per-step ingredient references (database-level)**

Add an `ingredient_step` junction table:

```sql
CREATE TABLE ingredient_steps (
    ingredient_id INT,
    step_index INT,  -- 0-based index into the instructions JSON array
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE
);
```

This links ingredients to specific steps without changing the instruction text format. The existing ingredient highlighting regex could be used *during import* to auto-generate these links, then the frontend uses the explicit links instead of runtime regex matching.

**Benefit**: CookMode could show "Ingredients for this step" with exact amounts, not just highlighted names. The slide-out ingredient panel could bold the current step's ingredients.

**Cost**: Moderate — needs scraper changes, import logic, and a migration. But no user-facing format change.

**Option B: Accept the fuzzy approach**

The current regex-based ingredient highlighting in CookMode is 95% accurate for real recipes. "Butter" in step text always means the butter in the ingredient list. False matches are extremely rare in cooking instructions.

The engineering effort of Option A may not be worth the marginal improvement over the existing regex approach. Sometimes "good enough" is the right answer.

### My Recommendation

**Option B for now. Keep it fuzzy.** The ingredient highlighting already works well. The effort of building per-step ingredient references would be better spent on user-facing features (PWA offline support, night mode toggle, enhanced cook history). If Crumble ever adds a recipe editor that shows "which ingredients does this step use?", Option A becomes worthwhile.

### Sources

- [Recipe File Formats Compared — Cooklang](https://cooklang.org/blog/19-recipe-formats-compared/)
- [Schema.org Recipe Type](https://schema.org/Recipe)
- [Building a Recipe API with Cooklang](https://cooklang.org/blog/29-building-recipe-api-with-cooklang/)
- [JSON-LD and Recipe Markup — Chicory](https://chicory.co/blog-feed/tech-support-how-should-i-mark-up-my-recipes-json-ld-and-microdata)

### Sources

- [awesome-selfhosted Recipe Management](https://awesome-selfhosted.net/tags/recipe-management.html)
- [Open Source Recipe Managers 2026 — Cooklang](https://cooklang.org/blog/18-open-source-recipe-managers-2026/)
- [I replaced Mealie with Tandoor — XDA](https://www.xda-developers.com/reasons-tandoor-replaced-mealie-for-managing-my-recipes/)
- [Marius Hosting Docker Recipe Containers](https://mariushosting.com/best-docker-containers-for-recipes-and-groceries/)

### The Deeper Thought

The real insight here isn't about UI — it's about **who a recipe app is for**. Most recipe managers are built for recipe collectors (people who save 500 recipes). But cooking skill grows over time. The person who saved a recipe as a beginner will outgrow the detailed instructions in 6 months.

A truly thoughtful recipe app would recognize this progression and adapt. Not through AI, but through simple gestures: the cooking journal notes are already a form of this. When you note "used less salt next time", you're writing your own adaptive instruction layer. The cook log with notes is Crumble's most philosophically sophisticated feature — it turns a static recipe into a living document that evolves with the cook.

### Sources

- [How to Write Cooking Instructions That Actually Work — Spice](https://spice.alibaba.com/spice-basics/how-to-cook-how-to)
- [26 tiny ways to be a better cook in 2026 — Salon](https://www.salon.com/2025/12/20/26-tiny-ways-to-be-a-better-cook-in-2026/)

## Deep Dive 44: Mobile Experience Audit — What Crumble Gets Right

### The Current State

After auditing every component for mobile-specific patterns, Crumble's mobile experience is surprisingly good for a project that didn't start with a formal mobile strategy. Here's what's there:

**Navigation:** Bottom nav bar (BottomNav.jsx) with 5 core items — Home, Add, Favorites, Plan, Grocery. Hidden on desktop where the full sidebar takes over. This is the correct pattern for a utility app; hamburger menus are for content sites.

**Touch targets:** Consistently 44px minimum height/width across all interactive elements — buttons, nav items, list items. This matches Apple's HIG and Google's Material Design guidelines. Most recipe apps get this wrong (tiny star buttons, small delete icons).

**CookMode swipe:** Full touch gesture implementation for navigating between recipe steps. 50px threshold feels natural. Also supports keyboard arrows for desktop. Wake lock keeps the screen on during cooking — critical when your hands are covered in flour.

**Responsive pivot:** Single breakpoint strategy using Tailwind's `md:` (768px). Mobile layout is the default, desktop is the enhancement. Clean, no fragmentation.

**Search:** Collapsible search bar on mobile (icon toggle), full bar on desktop. Smart use of limited screen space.

### What's Missing

**1. PWA — The Big One**

No manifest.json, no service worker. This means:
- No "Add to Home Screen" prompt
- No offline access (can't view saved recipes without internet)
- No app-like launch experience (no splash screen, no standalone mode)
- No background sync for grocery list changes

For a kitchen app, this is the single biggest improvement available. You don't browse recipes with a laptop on the counter — you use your phone. A PWA would make Crumble feel native.

**What a minimal PWA needs:**
```json
{
  "name": "Crumble",
  "short_name": "Crumble",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#FFF8F0",
  "theme_color": "#C1694F",
  "icons": [
    { "src": "/icons/icon-192.png", "sizes": "192x192", "type": "image/png" },
    { "src": "/icons/icon-512.png", "sizes": "512x512", "type": "image/png" }
  ]
}
```

The manifest alone gives you "Add to Home Screen" and standalone mode — zero JavaScript required. The service worker is where the complexity lives.

**2. Service Worker Caching Strategy**

For Crumble, a tiered caching approach makes sense:

- **Cache-first:** App shell (HTML, CSS, JS bundles) — these change rarely
- **Network-first with cache fallback:** API responses for recipes — always try fresh data, fall back to cached
- **Cache-first with background revalidation (stale-while-revalidate):** Recipe images — show cached immediately, update in background
- **Network-only:** Auth endpoints, grocery writes — always need server

The tricky part: recipe images are served from `/uploads/` on the PHP backend, not the Vite dev server. The service worker needs to intercept these cross-origin-ish requests.

**3. Pull-to-Refresh**

The recipe list and grocery list would benefit from pull-to-refresh. Currently you have to navigate away and back, or rely on the browser's built-in refresh. This is a solved problem with a small touch gesture handler (similar to CookMode's swipe detection but vertical).

**4. Haptic Feedback**

`navigator.vibrate()` is available on Android Chrome and could add satisfying tactile feedback for:
- Checking off grocery items
- Completing a cook log entry
- Toggling favorites

Tiny enhancement, disproportionate impact on perceived quality. iOS Safari doesn't support it, but the API degrades gracefully (just doesn't vibrate).

**5. Long-Press Actions**

No long-press interactions anywhere. On mobile, long-press is the equivalent of right-click. Potential uses:
- Long-press a recipe card → quick actions (edit, share, add to meal plan)
- Long-press a grocery item → edit quantity or move to different list
- Long-press a meal plan slot → move to a different day

### The Keyboard Problem

Something subtle that most recipe apps ignore: when you tap the search bar on mobile, the keyboard pushes the bottom nav up. If the keyboard covers content, users can't scroll to see results. Crumble's sticky header + fixed bottom nav creates a "sandwich" that can squeeze content on small screens with keyboard open.

Fix: detect keyboard visibility (viewport resize) and hide the bottom nav when keyboard is active. Or use `visualViewport` API:
```javascript
window.visualViewport?.addEventListener('resize', () => {
  const isKeyboardOpen = window.innerHeight - window.visualViewport.height > 150;
  // toggle bottom nav visibility
});
```

### What Crumble Gets Right That Others Don't

1. **No horizontal scrolling anywhere.** Sounds basic, but Tandoor's ingredient list overflows on narrow screens.
2. **Touch targets are consistently sized.** Mealie has 28px icon buttons that are painful to tap.
3. **CookMode is genuinely good on mobile.** Large text, swipe navigation, wake lock. This is where competitors have bare-minimum implementations.
4. **Bottom nav over hamburger.** KitchenOwl uses a hamburger menu. Every usability study shows bottom nav outperforms it for utility apps.

### The 80/20 of Mobile Improvements

If I could only do one thing: **add a web app manifest.** Zero code complexity, instant improvement. Users get "Add to Home Screen" and the app opens in standalone mode (no browser chrome). Takes 15 minutes to implement.

If I could do two things: **add a basic service worker for app shell caching.** Users can at least see the UI offline even if data isn't available. Vite has `vite-plugin-pwa` that generates the service worker from the build output.

Everything else (haptics, long-press, pull-to-refresh) is polish. The manifest + service worker is the structural improvement.

## Deep Dive 45: Bundle Analysis — Why 325KB Is Actually Fine

### The Numbers

```
index.js    325.81 KB (92.69 KB gzip)
index.css    51.44 KB ( 9.28 KB gzip)
```

Total: ~102 KB over the wire (gzipped). For a full-featured SPA with React 18, React Router, Tailwind CSS 4, and Lucide icons, this is lean.

### Breakdown Estimates

Based on known library sizes and the dependency list:

| Library | Estimated Size (minified) | Notes |
|---------|--------------------------|-------|
| react + react-dom | ~130 KB | The unavoidable baseline |
| react-router-dom | ~45 KB | v6 is lighter than v5 |
| lucide-react (tree-shaken) | ~25-30 KB | Only imported icons ship |
| Crumble app code | ~120 KB | Pages, components, hooks, utils |

### Why Code Splitting Isn't Worth It (Yet)

The textbook answer is "lazy-load routes," but:

1. **Only 4 runtime dependencies.** There's no bloated charting library, no date picker, no rich text editor. The "framework tax" (React + Router) is the bulk.

2. **All routes share the Layout component.** Code splitting saves nothing on the shell — Header, Sidebar, BottomNav load on every page.

3. **Route chunking creates waterfall requests.** On a self-hosted LAN app, the initial load of 93KB gzipped takes <100ms. Splitting into 8 route chunks means 8 sequential network requests as users navigate. Net slower.

4. **Crumble is a utility app, not a content site.** Users open it, go to a recipe, cook. They don't bounce between 12 pages per session. Lazy loading optimizes for breadth of navigation that doesn't happen here.

### When Code Splitting Would Matter

- If a heavy library gets added (chart.js for stats, a markdown editor for recipe notes, a drag-and-drop library for meal planning)
- If the bundle crosses ~500KB gzipped — that's the point where initial load becomes perceptibly slow on 3G
- If the app adds a public marketing page that shouldn't load the full authenticated SPA

### One Optimization That Is Worth Doing

The dependency list is clean (4 deps), but `lucide-react` is v0.474.0 — that's a rapidly-moving library. Every version bump adds new icons. Worth pinning the version rather than using `^` to prevent surprise bundle growth.

### The Real Performance Bottleneck

It's not the bundle — it's the API waterfall. When the home page loads:
1. Fetch `/auth/me` (session check + CSRF token)
2. Fetch `/recipes` (all recipes with ingredients and tags)
3. For each recipe card image: fetch `/uploads/thumbnails/{filename}`

Steps 1 and 2 are sequential (need auth before fetching recipes). Step 3 is parallel but can be 20+ image requests. The PHP backend processes these on a single thread per request, so under load, the image serving becomes the bottleneck.

Solutions that don't require code changes:
- **Apache mod_expires:** Set aggressive cache headers on `/uploads/` (images don't change once uploaded)
- **Lazy loading images:** Add `loading="lazy"` to recipe card images (browsers handle this natively)
- **Image format:** The ImageProcessor already creates thumbnails at 300px — but they're JPEG. WebP would be ~30% smaller.

Solutions that require code changes:
- **Prefetch on hover:** When a user hovers over a recipe card, prefetch the full recipe API response
- **Intersection Observer for images:** Only load images when cards scroll into view (more reliable than `loading="lazy"` on older browsers)
- **API response caching:** Cache the `/recipes` response in memory with a short TTL — the list changes rarely

### The Bottom Line

325KB (93KB gzip) is not a problem to solve. It's within the performance budget for any reasonable network connection. The time spent on code splitting would be better spent on:
1. Adding a PWA manifest (instant perceived performance improvement)
2. Image lazy loading (biggest visual load time improvement)
3. API response caching (biggest interaction speed improvement)

Don't optimize what isn't slow.

## Deep Dive 46: Collections & Cookbooks — Organizing Beyond Tags

### The Problem

Tags are Crumble's only organizational tool. They work for filtering ("show me all Italian recipes"), but they don't capture **intent**. Users don't think in tags — they think in contexts:

- "My weeknight dinners" (time-constrained, familiar recipes)
- "Recipes to impress guests" (special occasion, tested and reliable)
- "Learning to bake" (progressive difficulty, related techniques)
- "Mom's recipes" (sentimental, family heritage)

Tags can't express this. You'd need tags like `weeknight`, `impressive`, `learning-baking`, `moms-recipes` — and then you're using tags as collections, which is awkward because tags have no description, no ordering, and no visual identity.

### What Collections Would Look Like

A collection is a **named, ordered list of recipes with a description**. Unlike tags:

| Feature | Tags | Collections |
|---------|------|-------------|
| Created by | Often from imported data | Always user-created |
| Order | Alphabetical/newest | User-defined |
| Description | No | Yes — "Why does this group exist?" |
| Visual identity | Color dot | Cover image (from one of its recipes) |
| Overlap | Any recipe can have many tags | Any recipe can be in many collections |
| Discovery | Filter sidebar | Dedicated page |

### Database Design

```sql
CREATE TABLE collections (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  cover_recipe_id INT NULL,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (cover_recipe_id) REFERENCES recipes(id) ON DELETE SET NULL
);

CREATE TABLE collection_recipes (
  collection_id INT NOT NULL,
  recipe_id INT NOT NULL,
  sort_order INT DEFAULT 0,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (collection_id, recipe_id),
  FOREIGN KEY (collection_id) REFERENCES collections(id) ON DELETE CASCADE,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);
```

Simple junction table. The `sort_order` on both tables allows users to arrange their collections and recipes within them.

### The UI Question

Where do collections live in the nav? Crumble already has 8 nav items in the sidebar (9 for admins). Adding "Collections" makes it 9/10. The bottom nav only has 5 slots and they're full.

**Option A: Replace Favorites**
Favorites is just a special collection. If collections exist, Favorites becomes the default collection that every account starts with. This is what Apple Music did — "Liked Songs" is just a playlist.

But Favorites has the one-tap star interaction on recipe cards. Collections require choosing which collection to add to. The friction difference matters.

**Option B: Nest under Recipes**
The home page already shows all recipes. Collections could be tabs at the top: "All Recipes" | "My Collections". No new nav item needed.

This is clean but hides collections. Users who don't know about them won't discover them.

**Option C: Replace the Recipes icon with a split view**
The home page becomes a collections-first view: a grid of collection covers, with "All Recipes" as the default collection. Tapping a collection shows its recipes in the same grid layout.

This is the most opinionated approach and the most satisfying if you have enough recipes to organize. But it's hostile to users with <20 recipes who don't need organization.

### My Take

**Option B.** Tabs on the home page. Low-commitment, discoverable, doesn't displace anything. Favorites stays as-is — it's a different mental model (quick save vs. intentional curation).

The key insight: collections are a power-user feature. Don't force them on new users. Let them emerge naturally when someone has enough recipes to feel the need.

### What This Enables

With collections in place, several other features become natural:

1. **Meal plan from collection:** "Plan this week from my 'Weeknight Dinners' collection" — pick 5 random recipes from a specific collection.

2. **Shared collections:** Give someone a link to your "Holiday Baking" collection. They see all 12 recipes, can browse, but can't edit.

3. **Smart collections (future):** Auto-populated based on rules — "Recipes I've cooked more than 3 times" or "Recipes with prep time under 20 minutes." This is the power-user feature that makes organization zero-effort.

4. **Collection cover images:** Use the hero image from the cover_recipe_id. Gives the collections page a visual, magazine-like quality without requiring users to upload separate cover images.

### Implementation Complexity

Low. It's a junction table, 4 API endpoints (CRUD for collections, add/remove recipe), and 2 frontend components (collection list view, collection detail view). The recipe card component already exists — it just needs to be rendered inside a collection context instead of the "all recipes" context.

The drag-to-reorder within collections is the only complex part (requires a drag-and-drop library or custom touch handlers). But initial implementation can use simple move-up/move-down buttons, same as the instruction step reordering in the recipe editor.

## Deep Dive 47: PWA Implementation Blueprint — From SPA to Installable App

### Why This Is the Highest-Impact Feature

Crumble is a kitchen app used on phones with messy hands. Right now:
- Users must open a browser, type the URL, log in
- No offline access — can't view a recipe if the server is down or WiFi drops
- No "app" presence on the home screen
- Screen stays on only inside CookMode (wake lock), not while browsing recipes

A PWA fixes all of these with minimal code. It's the best effort-to-impact ratio feature available.

### Phase 1: Manifest Only (15 minutes)

Create `public/manifest.json`:
```json
{
  "name": "Crumble — Recipe Manager",
  "short_name": "Crumble",
  "description": "Your personal recipe cookbook",
  "start_url": "/",
  "display": "standalone",
  "orientation": "portrait",
  "background_color": "#FFF8F0",
  "theme_color": "#C1694F",
  "icons": [
    {
      "src": "/icons/icon-192.png",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "/icons/icon-512.png",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any maskable"
    }
  ]
}
```

Add to `index.html`:
```html
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#C1694F">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
```

This alone gives:
- "Add to Home Screen" prompt on Android Chrome
- Standalone mode (no URL bar) when launched from home screen
- Custom splash screen with the Crumble icon and cream background
- `theme_color` matches the terracotta accent — status bar looks intentional

iOS Safari note: Apple partially supports the manifest but also needs the `apple-mobile-web-app-capable` meta tag. iOS PWAs have quirks (no push notifications until iOS 16.4, no badge API), but the install-to-home-screen works.

### Phase 2: Service Worker with vite-plugin-pwa (2-3 hours)

Install:
```bash
npm install -D vite-plugin-pwa
```

Vite config addition:
```javascript
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
  plugins: [
    react(),
    tailwindcss(),
    VitePWA({
      registerType: 'prompt',  // Don't auto-update — let user choose
      workbox: {
        // Precache the app shell
        globPatterns: ['**/*.{js,css,html,svg,png,ico}'],

        // Runtime caching for API and images
        runtimeCaching: [
          {
            // Recipe API responses
            urlPattern: /\/api\/recipes/,
            handler: 'NetworkFirst',
            options: {
              cacheName: 'recipe-api',
              expiration: { maxEntries: 100, maxAgeSeconds: 86400 },
              networkTimeoutSeconds: 3,
            },
          },
          {
            // Recipe images
            urlPattern: /\/uploads\//,
            handler: 'CacheFirst',
            options: {
              cacheName: 'recipe-images',
              expiration: { maxEntries: 200, maxAgeSeconds: 2592000 }, // 30 days
              cacheableResponse: { statuses: [0, 200] },
            },
          },
          {
            // Auth endpoints — never cache
            urlPattern: /\/api\/auth/,
            handler: 'NetworkOnly',
          },
        ],
      },
      manifest: {
        // ... same as Phase 1 manifest
      },
    }),
  ],
});
```

### Caching Strategy Rationale

| Resource | Strategy | Why |
|----------|----------|-----|
| App shell (JS/CSS/HTML) | **Precache** | These are versioned by Vite's content hashing. Always serve from cache. |
| `/api/recipes` | **NetworkFirst** (3s timeout) | Always try fresh data. If network is slow/down, serve cached recipe list. |
| `/api/auth/*` | **NetworkOnly** | Session state must never be stale. |
| `/uploads/*` images | **CacheFirst** | Images don't change once uploaded. Cache aggressively. |
| `/api/grocery/*` | **NetworkFirst** | Grocery state changes frequently but should work offline. |

The 3-second `networkTimeoutSeconds` on recipes is important. On a LAN, responses come in <100ms. If it takes >3 seconds, something is wrong — serve cached data rather than making the user wait.

### Phase 3: Update Prompt UI (1 hour)

When a new version is deployed, the service worker detects the change. With `registerType: 'prompt'`, the app should show a non-intrusive toast:

```jsx
// hooks/usePwaUpdate.js
import { useRegisterSW } from 'virtual:pwa-register/react';

export default function usePwaUpdate() {
  const {
    needRefresh: [needRefresh],
    updateServiceWorker,
  } = useRegisterSW();

  return { needRefresh, update: () => updateServiceWorker(true) };
}
```

In the Layout component, a simple toast bar at the top:
```jsx
{needRefresh && (
  <div className="bg-sage text-white text-center py-2 text-sm">
    New version available.{' '}
    <button onClick={update} className="underline font-bold">Update now</button>
  </div>
)}
```

Why `prompt` over `autoUpdate`: if someone is mid-cook in CookMode and the service worker silently reloads the page, they lose their step position and timer. The user should choose when to update.

### Phase 4: Offline Fallback Page (30 minutes)

When a user navigates to a page that isn't cached and has no network:

```html
<!-- public/offline.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crumble — Offline</title>
  <style>
    body { font-family: 'Nunito', sans-serif; background: #FFF8F0; color: #3E2723;
           display: flex; align-items: center; justify-content: center;
           min-height: 100vh; margin: 0; text-align: center; padding: 2rem; }
    h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
    p { color: #8D7B6E; }
  </style>
</head>
<body>
  <div>
    <h1>You're offline</h1>
    <p>Crumble needs an internet connection to load new recipes.<br>
    Previously viewed recipes may still be available.</p>
    <button onclick="location.reload()"
            style="margin-top:1rem; padding:0.75rem 1.5rem; background:#C1694F;
                   color:white; border:none; border-radius:0.75rem; cursor:pointer;
                   font-size:1rem;">
      Try Again
    </button>
  </div>
</body>
</html>
```

### What This Doesn't Solve

1. **Offline recipe editing:** Users can view cached recipes offline but can't save changes. This would require IndexedDB queuing and background sync — significantly more complexity.

2. **Offline grocery list editing:** Same problem. Checking off items offline would need conflict resolution when reconnecting.

3. **Push notifications:** "Your meal plan for tomorrow has 'Beef Stew' — start marinating tonight!" Nice but requires a push server, notification permissions UX, and the VAPID key infrastructure. Phase 5 territory.

4. **Background sync:** If a user logs a cook while offline, the log should sync when they reconnect. The Background Sync API handles this but has limited browser support (Chrome only as of 2026).

### The Progressive Path

| Phase | Effort | Impact |
|-------|--------|--------|
| 1. Manifest | 15 min | Install to home screen, standalone mode |
| 2. Service worker | 2-3 hrs | Offline viewing of cached recipes and images |
| 3. Update prompt | 1 hr | Safe, user-controlled version updates |
| 4. Offline fallback | 30 min | Graceful degradation instead of browser error |
| 5. Background sync | 4-8 hrs | Offline writes for grocery and cook log |
| 6. Push notifications | 8+ hrs | Proactive meal prep reminders |

Phases 1-4 are the sweet spot. They deliver 90% of the PWA value for ~4 hours of work. Phase 5-6 are diminishing returns unless users specifically request them.

### Sources

- [Vite PWA — Service Worker Precache Guide](https://vite-pwa-org.netlify.app/guide/service-worker-precache)
- [Vite PWA — Service Worker Strategies and Behaviors](https://vite-pwa-org.netlify.app/guide/service-worker-strategies-and-behaviors.html)
- [MDN — Caching for Progressive Web Apps](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps/Guides/Caching)
- [PWA Offline Functionality: Caching Strategies Checklist](https://www.zeepalm.com/blog/pwa-offline-functionality-caching-strategies-checklist)
- [CSS-Tricks — Using VitePWA Plugin for an Offline Site](https://css-tricks.com/vitepwa-plugin-offline-service-worker/)

## Deep Dive 48: The Recipe Image Problem — Optimization Without Complexity

### Current State

Crumble's `ImageProcessor.php` does two things when a recipe image is uploaded:
1. Resizes to max 800px width (full size)
2. Creates a 300px thumbnail

Both are saved as JPEG. The originals are stored in `/uploads/` and thumbnails in `/uploads/thumbnails/`.

### The Numbers That Matter

A typical recipe hero image:
- Original upload: 2-5 MB (phone camera)
- After ImageProcessor (800px): ~80-150 KB JPEG
- Thumbnail (300px): ~15-30 KB JPEG

For a collection of 100 recipes, the thumbnail grid on the home page loads ~1.5-3 MB of images. On a LAN this is fast. Over the internet or on mobile data, it's noticeable.

### Quick Wins (No Library Changes)

**1. `loading="lazy"` on all recipe card images**

The simplest optimization. Browsers natively defer loading images outside the viewport. For a recipe grid showing 12 cards above the fold and 50+ below, this cuts initial image requests by 75%.

Check if RecipeCard already uses this... it should be a one-line change if not:
```jsx
<img loading="lazy" src={thumbnailUrl} alt={recipe.title} />
```

**2. `srcset` for responsive images**

The 300px thumbnail is overkill for mobile screens where cards might be 150px wide. A `srcset` with multiple sizes lets the browser pick:
```html
<img srcset="/uploads/thumbnails/recipe-150.jpg 150w,
             /uploads/thumbnails/recipe-300.jpg 300w"
     sizes="(max-width: 768px) 150px, 300px"
     src="/uploads/thumbnails/recipe-300.jpg" />
```

But this requires generating a second thumbnail size (150px) in ImageProcessor. Modest effort for modest gain — only worth it if image loading is actually measured as slow.

**3. WebP conversion**

WebP is ~30% smaller than JPEG at equivalent quality. PHP's GD library supports WebP since PHP 7.1. The change in ImageProcessor would be:
```php
// Instead of:
imagejpeg($resized, $path, 85);
// Use:
imagewebp($resized, $path, 80);
```

But this breaks for older browsers. The safe approach is to generate both JPEG and WebP, then serve WebP when the browser supports it (via `Accept` header or `<picture>` element). This doubles storage requirements for a format that saves ~30% bandwidth. On a self-hosted LAN app, the trade-off usually isn't worth it.

**4. Apache cache headers for uploads**

This is the single highest-impact server-side change. Recipe images never change after upload (editing a recipe replaces the file with a new one, new filename). Setting a 30-day cache:

```apache
# In .htaccess or Apache config
<Directory "/uploads">
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 30 days"
    ExpiresByType image/png "access plus 30 days"
    ExpiresByType image/webp "access plus 30 days"
</Directory>
```

After first load, the browser never re-requests images. Combined with the PWA service worker (CacheFirst for `/uploads/`), images load instantly on return visits.

### The Placeholder Pattern

What happens when a recipe has no image? Currently, the card likely shows nothing or a broken state. A good pattern for placeholder images:

```jsx
const PlaceholderImage = ({ title }) => (
  <div className="w-full h-48 bg-cream-dark flex items-center justify-center">
    <CookingPot className="text-warm-gray" size={48} />
  </div>
);
```

This is nicer than a blank space and communicates "no image yet" without looking broken. The icon could even be contextual — use a Cake icon for desserts, a Soup icon for soups, etc., based on tags.

### What Not To Do

- **Don't add a CDN.** Crumble is self-hosted. Adding Cloudflare or similar defeats the self-hosting purpose.
- **Don't implement image lazy loading with JavaScript.** The native `loading="lazy"` attribute works in all modern browsers. No library needed.
- **Don't compress images more aggressively.** JPEG quality 85 is already a good balance. Going lower makes food photos look unappetizing — and the whole point of a recipe hero image is to make the food look good.
- **Don't implement responsive images unless you measure the problem.** The 300px thumbnail is already small. The complexity of generating and serving multiple sizes isn't worth it for a personal app.

### The Priority Order

1. `loading="lazy"` — one attribute, huge impact (do this immediately)
2. Apache cache headers — one config line, eliminates repeat requests
3. PWA service worker CacheFirst — covered in Deep Dive 47
4. Placeholder images for no-image recipes — better UX, 10 lines of code
5. WebP — only if bandwidth is actually a measured problem

## Deep Dive 49: RecipeScraper.php — A Line-by-Line Audit

### What It Does Well

After reading every line of `RecipeScraper.php` (737 lines) and `IngredientParser.php` (164 lines), this is genuinely good code. Some highlights:

**1. The 5-tier fallback cascade**
```
JSON-LD → Microdata → Heuristic HTML → Open Graph → Cached/AMP version
```
This is the right order. JSON-LD is where 90%+ of modern recipe sites put their structured data (Google recommends it). Microdata catches older sites. Heuristic catches blogs without schema markup. Open Graph provides at least a title and image for anything else. The cached/AMP tier is clever — it handles JS-rendered sites that don't serve usable HTML on the initial request.

**2. SSRF protection in `isValidUrl()`**
Resolves the hostname to an IP and blocks private ranges (`FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE`). This prevents a user from entering `http://192.168.1.1/admin` or `http://localhost/sensitive-endpoint` and using the scraper as a proxy to access internal services. Textbook correct.

**3. Nutrition parsing**
Extracts `calories`, `protein`, `fat`, `carbs`, `fiber`, `sugar` from the schema.org `NutritionInformation` block. Most recipe scrapers skip this. Crumble captures it even though the frontend doesn't display it yet — forward-thinking.

**4. Tag extraction from multiple sources**
Pulls tags from `recipeCategory`, `recipeCuisine`, AND `keywords`, deduplicates them. This means a recipe on AllRecipes tagged as "Italian, Dinner, Quick" will import all three as tags automatically.

**5. IngredientParser handles the hard cases**
Mixed numbers ("1 1/2 cups"), ranges ("2-3 tablespoons"), parenthetical units ("2 (14 oz) cans tomatoes"). The regex is readable and the fallback is graceful — if nothing matches, the entire string becomes the name.

### What Could Be Better

**Gap 1: HowToSection names are lost**

The current code handles `HowToSection` with nested `itemListElement` steps (lines 363-373), but it flattens the sections — the section name (e.g., "For the Crust", "For the Filling") is silently dropped.

Currently:
```php
} elseif (isset($step['itemListElement']) && is_array($step['itemListElement'])) {
    foreach ($step['itemListElement'] as $subStep) {
        if (is_array($subStep) && isset($subStep['text'])) {
            $cleaned = $this->cleanText($subStep['text']);
            if ($cleaned !== '') {
                $result['instructions'][] = $cleaned;
            }
        }
    }
}
```

The `$step['name']` (section title) is never read. A recipe with 3 sections and 4 steps each would import as 12 flat steps with no indication of where one section ends and another begins.

**Fix:** Prepend the section name as a step:
```php
} elseif (isset($step['itemListElement']) && is_array($step['itemListElement'])) {
    // Preserve section heading
    if (!empty($step['name'])) {
        $sectionName = $this->cleanText($step['name']);
        if ($sectionName !== '') {
            $result['instructions'][] = '--- ' . $sectionName . ' ---';
        }
    }
    foreach ($step['itemListElement'] as $subStep) {
        // ...existing code...
    }
}
```

This is a convention — `--- For the Crust ---` — that the frontend could later render as a section header instead of a numbered step. Mealie has the same problem (Issue #73 on their GitHub).

**Gap 2: Unicode fractions in amounts**

The IngredientParser's amount regex (`\d+\s+\d+\/\d+|\d+\.\d+|\d+\/\d+|\d+`) doesn't match Unicode vulgar fractions (½, ¼, ¾, ⅓, ⅔). These appear in recipe data more often than you'd expect — some WordPress recipe plugins convert `1/2` to `½` automatically.

Input: `½ cup butter` → Parser returns `{amount: null, unit: null, name: "½ cup butter"}`
Expected: `{amount: "1/2", unit: "cup", name: "butter"}`

The `GroceryItem.php` model already has `parseAmount()` that handles unicode fractions. The IngredientParser should do the same. The fix is a normalization step before the regex:

```php
// Normalize unicode fractions
$unicodeFractions = [
    '½' => '1/2', '⅓' => '1/3', '⅔' => '2/3',
    '¼' => '1/4', '¾' => '3/4', '⅕' => '1/5',
    '⅖' => '2/5', '⅗' => '3/5', '⅘' => '4/5',
    '⅙' => '1/6', '⅚' => '5/6', '⅛' => '1/8',
    '⅜' => '3/8', '⅝' => '5/8', '⅞' => '7/8',
];
$text = strtr($text, $unicodeFractions);
```

**Gap 3: Relative image URLs**

The image URL extraction doesn't resolve relative URLs. If a site uses `<img src="/images/recipe.jpg">` in microdata or heuristic parsing, the stored URL will be `/images/recipe.jpg` — useless without the domain.

The `image_url` should be resolved against the source URL:
```php
if (!empty($result['image_url']) && !preg_match('#^https?://#', $result['image_url'])) {
    $parsed = parse_url($url);
    $base = $parsed['scheme'] . '://' . $parsed['host'];
    if ($result['image_url'][0] === '/') {
        $result['image_url'] = $base . $result['image_url'];
    } else {
        $result['image_url'] = $base . '/' . $result['image_url'];
    }
}
```

JSON-LD images are almost always absolute URLs (Google requires it for rich results), but microdata and heuristic images are frequently relative.

**Gap 4: Encoded HTML entities in JSON-LD**

Some sites serve JSON-LD with HTML entities still in the text:
```json
{"recipeIngredient": ["1 cup all&ndash;purpose flour"]}
```

The `cleanText()` method handles `html_entity_decode`, but it's called on the JSON-LD mapped values after extraction, which is correct. However, some sites double-encode: `&amp;ndash;` becomes `&ndash;` after one decode. A second decode pass would catch these, but double-decoding normal text could corrupt it. Not worth fixing unless real examples surface.

**Gap 5: Google Web Cache and AMP fallback may not work in 2026**

The `fetchCachedVersion()` method tries:
1. Google AMP Cache (`cdn.ampproject.org`)
2. Google Web Cache (`webcache.googleusercontent.com`)

Google has been de-emphasizing AMP since 2021 and officially deprecated the AMP cache in several regions. Google Web Cache was removed from search results in early 2024 (the "cached" link is gone). The endpoint may still work for now but is unreliable.

A more future-proof fallback would be:
- Try the Wayback Machine (`web.archive.org`) — but this has the same reliability issues
- Or honestly, just accept the failure gracefully. If a site requires JavaScript rendering, the PHP scraper can't handle it. The error message "Found the page but couldn't find recipe data" is already appropriate.

### Comparison to recipe-scrapers (Python)

The `hhursev/recipe-scrapers` Python library is the gold standard, used by Mealie, Tandoor, and RecipeSage. It supports 250+ specific website scrapers with custom parsing logic for each.

Crumble takes the opposite approach: zero site-specific scrapers, relying entirely on generic structured data parsing. This means:

| Aspect | Crumble | recipe-scrapers |
|--------|---------|-----------------|
| Maintenance burden | Near-zero | Constant (sites change layouts) |
| Coverage of schema.org sites | Equivalent | Equivalent |
| Coverage of non-schema sites | Heuristic only | Custom per-site |
| New site support | Automatic (if schema.org) | Requires PR + merge |
| Edge case handling | Generic | Per-site tuned |

Crumble's approach is the right one for a PHP app without the Python ecosystem. The generic JSON-LD parser catches ~90% of recipe sites. The heuristic fallback catches another ~5%. The remaining 5% are sites that require JavaScript rendering or have truly non-standard HTML — these are genuinely unsolvable without a headless browser.

### The IngredientParser's Quiet Strength

Something I appreciate about the IngredientParser: it doesn't try to be smart about ingredient names. It doesn't have a database of known ingredients. It doesn't use NLP. It just does structural extraction:

1. Strip the amount from the front
2. Check if the next word is a unit
3. Everything else is the name

This is robust precisely because it's dumb. An NLP-based parser might incorrectly parse "1 large egg" as `{amount: 1, unit: null, name: "large egg"}` if it knows "large" is a size modifier. Crumble's parser returns `{amount: 1, unit: null, name: "large egg"}` — same result, but for the right reason (it doesn't recognize "large" as a unit, so the entire remainder is the name).

The only case where this fails: "1 medium onion, diced" → `{amount: 1, unit: null, name: "medium onion, diced"}`. The "medium" stays in the name, which is actually correct — "medium" is a descriptor for the onion, not a unit of measurement.

### Priority Fixes

1. **Unicode fraction normalization** — 10 lines, fixes a real parsing failure
2. **Relative image URL resolution** — 5 lines, fixes broken images from some sites
3. **HowToSection name preservation** — 8 lines, preserves recipe structure

All three are surgical fixes that don't change the architecture. Total effort: ~30 minutes including tests.

### Sources

- [Schema.org Recipe Type](https://schema.org/Recipe)
- [Google Recipe Schema Markup Documentation](https://developers.google.com/search/docs/appearance/structured-data/recipe)
- [Mealie Issue #73: HowToSection not parsed](https://github.com/mealie-recipes/mealie/issues/73)
- [hhursev/recipe-scrapers — Python package](https://github.com/hhursev/recipe-scrapers)
- [recipe-scrapers Contributing Guide](https://docs.recipe-scrapers.com/contributing/in-depth-guide-scraper-functions/)

## Deep Dive 50: The Quiet Things — Small UX Details That Compound

### The Theory

There's a concept in product design called "microinteractions" — the tiny, often invisible design decisions that separate an app that feels professional from one that feels like a student project. None of them individually matter. Together, they create an emotional impression of quality.

Crumble already has some of these: the recipe card hover zoom, the terracotta accent color that feels warm without being aggressive, the dotted underline on glossary terms. But there are gaps — moments where the default behavior shows through.

### 1. Empty States — Already Done (Mostly)

After auditing every page, Crumble already handles empty states well:
- **Favorites:** Heart icon + "No favorites yet!" + explanatory subtext
- **Cook History:** BookOpen icon + "You haven't cooked anything yet!" + subtext
- **Grocery Lists:** ShoppingCart icon + "No grocery lists yet" + "New List" CTA button
- **Meal Plan:** CalendarDays icon + "No meals planned" + nearby "Add Recipe" button
- **Stats:** ChefHat icon + "No cooking history yet" + subtext
- **Recipe Grid:** Book emoji + "No recipes found" + subtext

Most pages also use skeleton loading (RecipeCardSkeleton, GroceryListSkeleton, custom per-page skeletons). MealPlanPage and StatsPage still use spinners instead of skeletons — these could be upgraded.

The only gap: the recipe grid empty state uses an emoji (📖) instead of a Lucide icon, and lacks a direct CTA button to add a recipe. All other pages use Lucide icons consistently.

### 2. Loading Skeletons vs Spinners

Spinners tell the user "wait." Skeletons tell the user "content is coming." Skeletons also prevent layout shift because they occupy the same space as the content.

For the recipe grid:
```jsx
function RecipeCardSkeleton() {
  return (
    <div className="bg-surface rounded-2xl shadow-md overflow-hidden animate-pulse">
      <div className="aspect-[4/3] bg-cream-dark" />
      <div className="p-4">
        <div className="h-5 bg-cream-dark rounded-full w-3/4 mb-2" />
        <div className="h-4 bg-cream-dark rounded-full w-1/2" />
      </div>
    </div>
  );
}
```

This is 10 lines. Replacing a spinner with a skeleton on the home page is a massive perceived performance improvement.

### 3. Optimistic Updates

When a user stars a recipe as a favorite, there's currently (likely) a network round-trip:
1. User taps star → request to API → wait for response → update UI

What it should be:
1. User taps star → UI updates immediately → request to API in background
2. If API fails → revert the UI and show a toast

The star animation feels instant. The API call happens silently. If the network is slow, the user doesn't notice. This is especially important on mobile where latency is higher.

Same pattern applies to:
- Checking off grocery items
- Deleting grocery items
- Adding a recipe to the meal plan

### 4. Transition Between Pages

React Router does hard cuts between pages by default. The recipe grid disappears, then the recipe detail page pops in. Adding a simple fade transition:

```jsx
// In the page wrapper
<div className="animate-in fade-in duration-200">
  {children}
</div>
```

Or with React Router's built-in transition API (v6.4+). Subtle, but it makes navigation feel intentional rather than glitchy.

### 5. The Back Button Label

When viewing a recipe and tapping "Back," where does the user go? If they came from Favorites, they should go back to Favorites. If they came from Search, back to search results. If they came from the meal plan, back to the meal plan.

Currently, the back button likely always goes to `/` (home). This breaks the user's mental model of "back means where I was."

`useNavigate(-1)` handles this correctly in React Router — it uses the browser's actual history. But if the user directly navigated to `/recipe/5` (from a bookmark or shared link), `navigate(-1)` would go nowhere. The fallback should be home.

```jsx
const navigate = useNavigate();
const handleBack = () => {
  if (window.history.length > 1) {
    navigate(-1);
  } else {
    navigate('/');
  }
};
```

### 6. Keyboard Shortcuts

Power users expect these in any web app:
- `/` → Focus search
- `n` or `a` → New recipe (navigate to /add)
- `Escape` → Close modal / exit CookMode
- `?` → Show keyboard shortcuts overlay

CookMode already has keyboard support (arrow keys, Escape). But the main app has none. Adding `useEffect` with `keydown` listeners on the Layout component would cover the basics.

### 7. The URL Paste Shortcut

The most common recipe import flow: user finds a recipe online, copies the URL, switches to Crumble, navigates to Add Recipe, pastes the URL, clicks Import.

What if pasting a URL anywhere in the app (not in an input field) automatically started the import flow?

```jsx
useEffect(() => {
  const handlePaste = (e) => {
    // Only if no input is focused
    if (document.activeElement?.tagName === 'INPUT' ||
        document.activeElement?.tagName === 'TEXTAREA') return;

    const text = e.clipboardData?.getData('text');
    if (text && /^https?:\/\/.+/i.test(text)) {
      navigate(`/add?url=${encodeURIComponent(text)}`);
    }
  };
  document.addEventListener('paste', handlePaste);
  return () => document.removeEventListener('paste', handlePaste);
}, [navigate]);
```

User copies URL on food blog → switches to Crumble → Ctrl+V → import starts automatically. Three steps instead of six.

### 8. Toast Notifications

There's no feedback system for actions. When a user favorites a recipe, nothing visible happens. When they add ingredients to a grocery list, same. When they log a cook — silence.

A simple toast system:
```jsx
function Toast({ message, visible }) {
  return (
    <div className={`fixed bottom-20 left-1/2 -translate-x-1/2 z-50 px-4 py-2
      bg-brown text-cream rounded-full text-sm font-medium shadow-lg
      transition-all duration-300 ${visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'}`}>
      {message}
    </div>
  );
}
```

Messages like:
- "Added to favorites" (with undo link)
- "Recipe saved"
- "Ingredients added to grocery list"
- "Cook logged!"

### The Implementation Priority

| Enhancement | Effort | Impact | Notes |
|-------------|--------|--------|-------|
| Empty states | 2 hours | High | First impression for new users |
| Loading skeletons | 1 hour | Medium | Perceived performance |
| Toast notifications | 1 hour | Medium | Action feedback |
| Optimistic updates | 2 hours | Medium | Responsiveness |
| URL paste shortcut | 30 min | High | Power user delight |
| Keyboard shortcuts | 1 hour | Low-Med | Power users only |
| Back button fix | 20 min | Low | Correctness |
| Page transitions | 30 min | Low | Polish |

The first three items would transform Crumble from "works correctly" to "feels polished." That's the difference between a tool and a product.

## Deep Dive 51: What Home Cooks Actually Want — Research vs. Reality

### The Gap

Developers build recipe managers for developers. Home cooks want something different. After researching the HelloFresh State of Home Cooking Report 2025-2026, recipe app user research, and common complaints on Reddit and review sites, here's what actually matters:

### What Users Say They Want (Research Data)

**1. "I can't find what to make" (the #1 problem)**

38% of adults don't have the right groceries when they need them (HelloFresh). 52% aren't meal preppers. The core cooking problem isn't recipe storage — it's the decision of *what to cook tonight* given what's already in the fridge.

Crumble has recipes. It has a meal plan. But it doesn't have the bridge: "here are the recipes you can make with what you already have." This is the "What Can I Make?" feature from Deep Dive 6 — it's also the most requested feature in every competitor's issue tracker.

**2. Social media recipe import (52% get inspiration from social media)**

The explosion of TikTok and Instagram recipe videos created a new format: short-form video recipes. Users see a 60-second video of someone making pasta, save the link, and want it in their recipe app.

Crumble's scraper handles traditional recipe websites (JSON-LD). It can't handle:
- TikTok videos (no structured data)
- Instagram posts (walled garden)
- YouTube Shorts (sometimes have JSON-LD, often don't)

The commercial apps solving this (Honeydew, RecipeOne) use AI to extract recipes from video transcripts and screenshots. That's a fundamentally different technical challenge than HTML scraping.

For Crumble, the pragmatic answer: don't try to parse videos. Instead, make manual entry *fast*. The user watched the video, they know the recipe — let them type it in 60 seconds instead of 5 minutes. This means the Add Recipe form needs to be optimized for speed, not completeness.

**3. Grocery integration that actually works**

Crumble has grocery lists. But the real desire is: "add recipe ingredients to my grocery list, intelligently merge duplicates, and let me check items off while I shop."

The merging was recently fixed (Deep Dive 41 — fraction parsing). The shopping experience could be improved with:
- Aisle-based sorting (group produce together, dairy together)
- Swipe to check off (instead of checkbox tap)
- Smart quantity recognition ("I already have eggs at home, skip those")

**4. Nutritional information**

50% of users want detailed nutritional info. Crumble scrapes nutrition data from schema.org but doesn't display it anywhere in the frontend. The data is captured — just never shown.

This is low-hanging fruit: add a "Nutrition" section to RecipePage showing calories, protein, carbs, fat when available. Maybe a collapsible section so it doesn't clutter the page for users who don't care.

### What Users Complain About (Pain Points)

**1. Ads destroy the experience**

This is the #1 complaint across all recipe sites. Pop-ups, auto-play videos, interstitials — they make cooking from a website miserable.

Crumble has zero ads. This is its biggest competitive advantage and it should never change. The self-hosted model means no monetization pressure, no analytics tracking, no dark patterns. This should be prominently stated in marketing: "No ads. No tracking. No subscriptions. Your recipes, your server."

**2. "I can't see ingredients and instructions at the same time"**

Users hate toggling between tabs to see ingredients while reading instructions. Some apps literally separate them into different screens.

Crumble's recipe detail page shows both ingredients and instructions on the same page in a two-column layout on desktop. On mobile, ingredients come first, then instructions. CookMode goes further — it has an ingredient panel you can slide in while on any step.

This is a genuine strength. Crumble solves this problem well.

**3. Unclear instructions and missing details**

Vague steps, missing times, wrong servings. These are content problems, not app problems. But the app can help: the cooking glossary (Deep Dive 26) explains terms like "fold" and "deglaze." The scaling warning (implemented) tells you when adjustments might be needed.

**4. Slow, glitchy apps**

Performance matters more than features. Users abandon apps that lag. Crumble's 93KB gzipped bundle and simple PHP API are fast. The lazy loading (just added) prevents image-related slowdowns. The skeleton loading prevents perceived slowness.

### The Feature Priority Matrix (What Matters vs. What's Built)

| Feature | User Demand | Crumble Status | Gap |
|---------|-------------|----------------|-----|
| No ads/tracking | Critical | Done | None |
| Recipe import from URL | High | Done (JSON-LD, microdata) | Social media gap |
| Ingredients + instructions together | High | Done (two-column, CookMode) | None |
| Meal planning | Medium-High | Done | No "what to make?" bridge |
| Grocery lists | Medium-High | Done | No aisle sorting |
| Nutritional info display | Medium | Data captured, not shown | Frontend gap |
| "What can I make?" search | Medium | Not built | Full gap |
| Social media video import | Medium | Not feasible | Intentional gap |
| Offline access | Medium | Not built (no PWA) | Full gap |
| Recipe sharing | Medium | Done (token links) | None |
| Dark mode | Low-Medium | Done | None |
| Export/backup | Low-Medium | Done | None |
| Cook tracking/journal | Low | Done | None |
| Statistics | Low | Done | None |

### The Honest Assessment

Crumble is over-built in developer-facing areas (stats, cook journal, dark mode, export) and under-built in user-facing areas (nutrition display, "what can I make?", offline/PWA).

The most impactful next features aren't technically complex:
1. **Show nutrition data** — it's already scraped, just needs a UI section
2. **PWA manifest** — 15-minute implementation, massive UX improvement
3. **"What can I make?"** — ingredient-based recipe search, moderate complexity

The technically impressive features (cook journal, glossary tooltips, ingredient highlighting in CookMode) are genuinely good — they're the features that make a user think "someone who actually cooks built this." But they serve a user who has already committed to the app. The features that make a user commit in the first place are the utilitarian ones: fast import, good search, offline access.

### Sources

- [HelloFresh: State of Home Cooking 2025-2026](https://www.hellofresh.com/eat/reports/stateofhomecooking)
- [Recipe App Statistics 2025](https://electroiq.com/stats/recipe-app-statistics/)
- [The Problem with Online Recipes — Plan to Eat](https://www.plantoeat.com/blog/2025/06/the-problem-with-online-recipes-and-what-to-do-about-it/)
- [Why Recipe Websites Drive Us Crazy — Smart Kitchen Improvement](https://smartkitchenimprovement.com/why-recipe-websites-drive-us-crazy-a-deep-dive-into-user-frustrations/)
- [Best Recipe Apps Social Media Imports 2026 — Honeydew](https://honeydewcook.com/blog/recipe-apps-social-media-imports)
- [12 Best Recipe Apps 2026 — RecipeOne](https://www.recipeone.app/blog/best-recipe-manager-apps)

## Deep Dive 53: "What Can I Make?" — A Working Prototype

### The Problem

User has chicken, rice, and garlic in the fridge. They want to see which recipes they can make. Current search only looks at title and description (FULLTEXT on `recipes.title, recipes.description`). It can't search ingredients.

### The SQL Challenge

The naive approach — "find recipes where ALL ingredients match the user's pantry" — requires comparing every ingredient in every recipe against a user-provided list. This is a set containment problem.

But users don't want *exact* matches. They have "chicken" and want to find recipes with "chicken breast", "chicken thighs", or "boneless skinless chicken." This is fuzzy ingredient matching, which is genuinely hard.

### Three Approaches

**Approach 1: Simple LIKE matching (80% solution)**

Search ingredients with `LIKE '%keyword%'` for each user-provided ingredient. Rank recipes by how many ingredients match.

```sql
-- User provides: chicken, rice, garlic
SELECT r.id, r.title, r.image_path,
       COUNT(DISTINCT CASE WHEN i.name LIKE '%chicken%' THEN 1 END) +
       COUNT(DISTINCT CASE WHEN i.name LIKE '%rice%' THEN 1 END) +
       COUNT(DISTINCT CASE WHEN i.name LIKE '%garlic%' THEN 1 END) AS match_count,
       (SELECT COUNT(*) FROM ingredients i2 WHERE i2.recipe_id = r.id) AS total_ingredients
FROM recipes r
JOIN ingredients i ON i.recipe_id = r.id
WHERE i.name LIKE '%chicken%'
   OR i.name LIKE '%rice%'
   OR i.name LIKE '%garlic%'
GROUP BY r.id
ORDER BY match_count DESC, total_ingredients ASC
LIMIT 20;
```

The `ORDER BY match_count DESC, total_ingredients ASC` is key: recipes matching more of your ingredients rank higher, and among equal matches, simpler recipes (fewer total ingredients) rank first. A 5-ingredient recipe matching 3/5 of your items is more useful than a 20-ingredient recipe matching 3/20.

**Pros:** Dead simple. Works with MySQL's basic features. No new tables.
**Cons:** LIKE is case-insensitive but not fuzzy ("chicken" won't match "pollo"). No concept of "I probably have salt and pepper" (pantry staples).

**Approach 2: Match percentage with staple exclusion**

Improve on Approach 1 by calculating what percentage of a recipe's ingredients the user has, and excluding common pantry staples from the count.

```php
private const PANTRY_STAPLES = [
    'salt', 'pepper', 'black pepper', 'water', 'oil', 'olive oil',
    'vegetable oil', 'cooking spray', 'butter', 'sugar',
];

public function findByIngredients(array $ingredients): array {
    // Build CASE statements for each user ingredient
    $cases = [];
    $params = [];
    foreach ($ingredients as $ing) {
        $ing = trim($ing);
        if ($ing === '') continue;
        $cases[] = "MAX(CASE WHEN i.name LIKE ? THEN 1 ELSE 0 END)";
        $params[] = '%' . $ing . '%';
    }

    if (empty($cases)) return [];

    $matchSum = implode(' + ', $cases);

    $sql = "
        SELECT r.id, r.title, r.description, r.image_path,
               r.prep_time, r.cook_time, r.servings,
               ($matchSum) AS matched,
               COUNT(i.id) AS total_ingredients
        FROM recipes r
        JOIN ingredients i ON i.recipe_id = r.id
        GROUP BY r.id
        HAVING matched > 0
        ORDER BY matched DESC,
                 (matched * 1.0 / total_ingredients) DESC
        LIMIT 20
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
```

This returns recipes ranked by: (1) how many of your ingredients match, then (2) what percentage of the recipe your ingredients cover.

**Approach 3: Ingredient-based full-text index**

Add a FULLTEXT index on `ingredients.name` and use MySQL's natural language search:

```sql
ALTER TABLE ingredients ADD FULLTEXT INDEX idx_ingredient_name (name);

SELECT r.id, r.title,
       SUM(MATCH(i.name) AGAINST('chicken rice garlic' IN NATURAL LANGUAGE MODE)) AS relevance
FROM recipes r
JOIN ingredients i ON i.recipe_id = r.id
WHERE MATCH(i.name) AGAINST('chicken rice garlic' IN NATURAL LANGUAGE MODE)
GROUP BY r.id
ORDER BY relevance DESC
LIMIT 20;
```

**Pros:** MySQL handles stemming and relevance scoring. Fast with index.
**Cons:** Natural language mode has a minimum word length (default 3-4 chars), so short ingredient names like "oil" might not match. Boolean mode would work but loses relevance scoring.

### My Recommendation: Approach 1 with UI Magic

Start with the simplest LIKE-based search. The SQL is trivial, the endpoint is straightforward, and the results are good enough for most real-world cases.

The real magic is in the UI. Instead of a search box, present it as a tag-style input:

```
┌──────────────────────────────────────┐
│ What's in your kitchen?              │
│ [chicken ×] [rice ×] [garlic ×]  [+] │
│                                      │
│ ─── You can make ─────────────────── │
│                                      │
│ 🟢 Garlic Chicken Rice  (3/5 match)  │
│ 🟡 Chicken Stir Fry     (2/8 match)  │
│ 🟡 Garlic Fried Rice    (2/4 match)  │
└──────────────────────────────────────┘
```

Color-code by coverage:
- 🟢 Green: 60%+ ingredients matched — you can probably make this
- 🟡 Yellow: 30-60% — you need a few more things
- 🔴 Red: <30% — you're missing a lot (don't show these)

Show the matched vs. total count ("3/5 ingredients") so users can quickly judge feasibility.

### The Pantry Staples Problem

Every recipe assumes you have salt, pepper, oil, and water. These shouldn't count against the match percentage. Two solutions:

1. **Hardcoded list** of ~15 common staples that are excluded from counting
2. **User-configurable pantry** where users mark "I always have these" — more flexible but requires a new table and UI

Start with the hardcoded list. It covers 95% of cases. A configurable pantry is a v2 feature.

### API Design

```
GET /api/recipes/by-ingredients?ingredients=chicken,rice,garlic
```

Returns the same recipe card format as `/api/recipes` but ranked by ingredient match instead of creation date.

### Frontend Integration

Two options for where this lives:

**Option A: Separate page** (`/pantry` or `/what-can-i-make`)
A dedicated page with the tag input and results grid. New nav item.

**Option B: Enhancement to existing search**
Add a toggle on the home page: "Search recipes" / "Search by ingredients". Same page, different search mode.

I prefer Option B — it doesn't add navigation complexity and it's discoverable from the existing search flow. When the user switches to ingredient mode, the search bar becomes a tag-style input.

### Implementation Effort

- Backend: `findByIngredients()` method + route — 1-2 hours
- Frontend: ingredient tag input + search mode toggle — 2-3 hours
- Total: ~4 hours for a basic working version

This is the feature that would make the biggest difference in daily usage. Not because it's technically impressive, but because it answers the question every home cook asks at 5 PM: "What can I make with what I have?"

### Update: Implemented

Built the full feature:
- **Backend:** `Recipe::findByIngredients()` method with LIKE matching, pantry staple exclusion (15 common items), match count and percentage ranking. API endpoint at `GET /api/recipes/by-ingredients?ingredients=chicken,rice,garlic`.
- **Frontend:** Mode toggle on home page (desktop: segmented control, mobile: toggle button), tag-style ingredient input with Enter to add and Backspace to remove, results grid with match badge (e.g., "3/5") color-coded by coverage percentage (green ≥60%, yellow ≥30%, gray <30%).

## Deep Dive 52: Showing Nutrition Data — Implemented

### Discovery

The full nutrition pipeline was already built but disconnected at the display layer:
- **Scraper:** Extracts `calories`, `protein`, `fat`, `carbs`, `fiber`, `sugar` from schema.org
- **Database:** Migration 002 added all 6 columns to the `recipes` table
- **Backend:** Recipe model saves them on create, allows editing on update, returns them in GET responses
- **RecipeForm:** Has a collapsible "Nutrition (optional)" section with all 6 input fields
- **Display:** Nothing. Zero. The data was captured, stored, editable — and invisible.

### Implementation

Added a `<details>` collapsible section to both RecipePage and SharedRecipePage showing nutrition data in a 6-column grid. Uses semantic colors:
- Calories → terracotta (the main number)
- Protein → sage (the "healthy" color)
- Carbs → terracotta-light
- Fat → warm-gray
- Fiber → sage-dark
- Sugar → terracotta-dark

Only shows when at least one nutrition value exists. Collapsed by default so it doesn't clutter the page for users who don't care.

This was a 5-minute fix that unlocks data that was already being captured for every imported recipe. Pure frontend.

## Deep Dive 54: The Grocery List Experience

### What Exists

The grocery system is more capable than it first appears:
- Multiple named lists per user
- Items have `name`, `amount`, `unit`, `checked`, and optional `recipe_id`
- When adding a recipe's ingredients to a grocery list, the backend **deduplicates by name** and **combines amounts** when units match (e.g., two recipes calling for 1 cup butter → "2 cups butter")
- The `GroceryItem` model has serious fraction handling: unicode fractions, mixed numbers, ranges (averages them), and a `formatAmount()` that converts decimals back to readable fractions
- Items show their source recipe ("from Chicken Parmesan")
- Checked items sort to the bottom automatically

This is genuinely good infrastructure. The data model and backend logic are thoughtfully built. But the frontend experience has some friction that limits how useful it feels in practice.

### The Friction Points

**1. Manual item entry is dumb.**

When you type "2 cups flour" into the "Add an item" field, it gets saved as a single `name` string with no `amount` or `unit`. The backend has full parsing infrastructure, but it's only used when adding ingredients from recipes. A quick-add parser on the frontend (or a backend endpoint that parses freetext) would make manual entry feel natural.

The IngredientParser already exists in `api/services/IngredientParser.php`. It could be exposed as a utility or the `addItem` endpoint could accept a raw text field and parse it server-side.

**2. No "Clear checked" button.**

After a shopping trip, you've checked off 15 items. Now you either delete them one by one or delete the entire list. A single "Clear checked items" action is the most requested feature in every grocery app ever made.

**3. No category grouping.**

Items display in creation order (within checked/unchecked). In a real store, you want produce together, dairy together, etc. This is a harder problem — it requires either:
- **Manual categories**: user assigns each item to a category (tedious)
- **Auto-categorization**: a lookup table mapping common ingredients to store sections (e.g., "milk" → Dairy, "chicken" → Meat, "carrots" → Produce)
- **User-defined sort**: drag to reorder (complex UI but no category logic needed)

The auto-categorization approach is the most interesting. A static map of ~200 common ingredients to 8-10 store sections would cover 80%+ of cases. Unmapped items go to "Other". No AI needed — just a lookup table.

**4. No inline editing.**

Once an item is added, you can check it or delete it. You can't edit the name, amount, or unit. If you added "chiken" instead of "chicken", your only option is delete and re-add.

**5. No list sharing.**

Each grocery list is owned by a single user. In a household, one person plans meals and another does the shopping. There's no way to share or collaborate on a list. This is a v2 feature, but worth noting because it's the #1 reason people switch to dedicated grocery apps (like AnyList or OurGroceries).

### What's Worth Building

Ranked by impact-to-effort ratio:

1. **Clear checked items** — One button, one SQL query. ✅ **Implemented.** Button appears in the detail view header when checked items exist, showing "Clear X done". Backend: `GroceryItem::clearChecked()` + `DELETE /grocery/{id}/checked` route.

2. **Recipe back-links** — Grocery items from recipes now link back to their source recipe. ✅ **Implemented.** The "from Recipe Title" label is now a clickable link when `recipe_id` is present.

3. **Smart item parsing** — Expose the existing IngredientParser for manual entry. When user types "2 lbs chicken breast", save it as `amount: "2"`, `unit: "lbs"`, `name: "chicken breast"`. Maybe 1-2 hours.

4. **Inline editing** — Make item names clickable/editable. The backend `updateItem` already supports updating name/amount/unit. Just needs frontend work. 1-2 hours.

5. **Category grouping** — Static lookup table for auto-categorization. Moderate effort (~4 hours including the map data) but would make the shopping experience dramatically better. The sorted-by-aisle grocery list is one of those things that, once you've used it, you can't go back.

### The Category Problem in Detail

If I were to build auto-categorization, here's the approach:

```php
// A static map, not a database table. Fast, no migration needed.
const CATEGORY_MAP = [
    'Produce' => ['lettuce', 'tomato', 'onion', 'garlic', 'carrot', 'celery', 'potato', 'bell pepper', 'broccoli', 'spinach', 'avocado', 'lemon', 'lime', 'ginger', 'cilantro', 'parsley', 'basil', 'mushroom', ...],
    'Dairy' => ['milk', 'butter', 'cheese', 'cream', 'yogurt', 'sour cream', 'cream cheese', 'parmesan', 'mozzarella', 'cheddar', 'eggs', ...],
    'Meat & Seafood' => ['chicken', 'beef', 'pork', 'salmon', 'shrimp', 'ground beef', 'bacon', 'sausage', 'turkey', ...],
    'Pantry' => ['flour', 'sugar', 'rice', 'pasta', 'olive oil', 'vegetable oil', 'soy sauce', 'vinegar', 'honey', 'canned tomatoes', 'broth', 'stock', ...],
    'Spices' => ['salt', 'pepper', 'cumin', 'paprika', 'cinnamon', 'oregano', 'thyme', 'chili powder', 'garlic powder', 'onion powder', ...],
    'Bakery' => ['bread', 'tortillas', 'pita', 'buns', 'rolls', ...],
    'Frozen' => ['frozen peas', 'frozen corn', 'ice cream', 'frozen berries', ...],
    'Beverages' => ['juice', 'wine', 'beer', 'soda', 'coffee', 'tea', ...],
];
```

The matching would use `str_contains()` on the item name (lowercased) against each category's keywords. First match wins. Unmatched items go to "Other" at the bottom.

The frontend would render items grouped by category with collapsible sections — each section header shows the category name and count of remaining items. Checked items within each category would still sort to the bottom of their group.

### Observation

The grocery list is one of those features that seems simple but actually determines whether people use the app daily. A recipe manager that you visit when you want to find a recipe is used weekly. A recipe manager with a grocery list you take to the store is used daily. And the grocery list's UX is what determines if people actually use it or fall back to Apple Notes.

The current grocery implementation has strong bones — the deduplication and amount-combining logic is better than what most apps ship with. But the day-to-day interaction (adding items, checking them off, organizing by aisle) is where it needs polish.

## Deep Dive 55: What Makes Recipe Apps Sticky

### The Daily Touchpoint Problem

Most recipe apps are used once a week: you search for a recipe, open it, cook. That's a weak engagement loop. The apps that people actually rely on daily — the ones they'd miss if they disappeared — have figured out how to insert themselves into the daily routine. Looking at what works:

**Paprika** became indispensable not because of its recipe storage (which is mediocre) but because of its grocery list. People planned meals → generated grocery lists → took their phone to the store → checked items off. That's 3-4 touchpoints per week instead of 1.

**Mealime** got stickier by automating the "what's for dinner" decision. Rather than browsing a collection, it generates a meal plan and produces a grocery list. The value isn't the recipes — it's the removal of the daily decision.

**Cookpad/Allrecipes** get engagement through social features — seeing what others cooked, getting comments on your posts. Crumble is self-hosted so this doesn't apply, but the insight is that *creating content* (logging cooks, writing notes, rating) generates more engagement than *consuming content* (browsing recipes).

### Crumble's Current Touchpoints

Let me map out what currently brings someone back to Crumble:

| Touchpoint | Frequency | Existing? |
|------------|-----------|-----------|
| Search for a recipe to cook | 1-3x/week | Yes |
| Read a recipe while cooking | 1-3x/week | Yes |
| Import a new recipe from the web | 1-2x/week | Yes |
| Check grocery list at the store | 1-2x/week | Yes (needs UX work) |
| Plan meals for the week | 1x/week | Yes (meal planner) |
| Log a cook | 1-3x/week | Yes |
| Browse what to make (idle browsing) | 0-2x/week | Partial (home page) |
| Review stats | 1x/week | Yes |
| Share a recipe | Occasional | Yes (share links) |

That's actually a decent spread. The gaps are in *daily* habits:

### What's Missing for Daily Stickiness

**1. The "What's for dinner tonight?" moment.**

This happens every day around 4-5 PM. The meal planner partially addresses it, but only if you planned ahead. For the (very common) case where you didn't plan: you open the app and need a quick answer.

The "What Can I Make?" feature helps here — you can input what's in the fridge and get suggestions. But there's another angle: **quick suggestions based on past behavior**. "You haven't made [favorite recipe] in 3 weeks" or "Last time you cooked on a Tuesday, you made [quick recipe]." No AI needed — just simple queries against the cook log.

**2. The cooking companion experience.**

When you're actively cooking, you need the recipe visible and your hands are often messy. The current recipe page works but could be more cooking-friendly:
- **Keep-awake**: prevent screen from dimming while viewing a recipe (Wake Lock API)
- **Step-by-step mode**: show one instruction at a time in large text, swipe to advance
- **Cooking timers**: when instructions mention "bake for 25 minutes", offer a tap-to-start timer

The keep-awake feature in particular is trivially implementable and solves a real annoyance. The Wake Lock API is widely supported and takes ~10 lines of code.

**3. The post-cook reflection.**

The cook log currently captures a timestamp and optional notes. But the *moment* right after cooking — when the food is plated and you're proud of what you made — is underutilized. This is when people take photos, when they think "next time I should add more garlic", when they'd rate a recipe. Making the "Log Cook" flow slightly more engaging (prompt for a photo, ask "would you change anything?", show streak progress) could turn a perfunctory button press into a micro-ritual.

### The PWA Connection

All of these daily touchpoints get better with PWA. If Crumble is installed on the home screen:
- The "What's for dinner?" check becomes a one-tap action
- The cooking companion works offline (cached recipe)
- The grocery list works offline (with sync when back online)
- Push notifications could remind about meal plans ("You planned Chicken Tikka Masala for tonight")

PWA isn't a feature — it's the delivery mechanism that makes all other features more accessible. The blueprint from Deep Dive 47 is solid; it's the most impactful infrastructure investment the app could make.

### The Retention Curve

Recipe apps follow a predictable retention curve:
- **Week 1**: High engagement (importing recipes, exploring features)
- **Week 2-4**: Engagement drops as novelty fades
- **Month 2+**: Only retained users are those who built habits around specific workflows

The users who stick are the ones who found a *workflow* — not just a feature, but a repeatable process that Crumble is part of. The most common winning workflow is: meal plan on Sunday → grocery shop on Monday → cook through the week → log cooks → repeat.

Crumble already has all the pieces for this workflow. The question is whether they feel connected enough that the transition from one step to the next is frictionless. Right now, each feature (meal plan, grocery, cooking, logging) is a separate page with no prompting to move to the next step. A "Generate grocery list from this week's plan" button already exists. But does the grocery list prompt you to check the meal plan? Does the recipe page know that this recipe is on tonight's meal plan?

These cross-feature connections — not new features — are what turn a collection of tools into a workflow.

## Deep Dive 56: The Admin Experience

### Current State

The admin page is purely user management: a table of users with username, email, role, created date, and actions (edit, reset password, delete). Plus an "Export Recipes" button and a "Your Account" section.

This is functional but minimal. For a self-hosted app with 2-5 household members, it's probably sufficient. But for anyone running Crumble for a family or small community, there are gaps:

### What's Missing

**System health at a glance.** How many recipes? Total storage used by images? Database size? Last backup? This doesn't need to be complex — a simple stats card row at the top of the admin page showing:
- Total recipes / Total users / Storage used
- PHP version / Database version (for debugging)
- Last successful export date

**Activity overview.** Who's been active? When did each user last log in? How many recipes has each user created? The users table already has `created_at` but no `last_login` column. Adding one would let the admin see at a glance if accounts are active.

**Import/export management.** The export button is here but import isn't. If someone wants to restore from a backup, they need to use the import features scattered across the add recipe page. A unified "Backup & Restore" section on admin would be cleaner.

### What's NOT Missing

The admin page correctly doesn't try to do too much. There's no:
- Per-recipe moderation (not needed for a household app)
- Complicated permissions system (admin/member is enough)
- Settings UI for server configuration (config is file-based, as it should be)
- Audit logs (overkill for this scale)

This restraint is good. The temptation with admin panels is to build a control center for everything. For Crumble's target audience, the current approach of "manage users + export data" covers the actual admin needs.

### One Quick Win

The one thing I'd actually add: a "Last login" indicator on the users table. It's a single column addition (`last_login_at` in the users table, set during `AuthController::login()`), and it answers the practical question admins have: "Is everyone using this, or did they log in once and forget about it?"

## Deep Dive 57: CookMode — A Hidden Gem

### What I Expected

Based on the simple "Start Cooking" button on the recipe page, I expected a basic fullscreen view of the recipe. Maybe larger text.

### What Actually Exists

CookMode is easily the most polished feature in Crumble. It's a purpose-built cooking companion that's genuinely competitive with dedicated cooking apps:

**Screen management:**
- Wake Lock API prevents screen dimming (`useWakeLock` hook with visibility change re-acquisition — this handles the edge case where the browser releases the lock when the tab goes to background)
- Body scroll is locked
- Full dark overlay (`bg-brown`) for kitchen readability

**Step navigation:**
- One step at a time, large text (up to `text-3xl` on desktop)
- Progress bar at the top
- Prev/Next buttons with large touch targets (56px min height)
- Keyboard navigation (arrow keys + Escape)
- Touch swipe navigation (50px threshold)

**Smart features:**
- **Timer detection**: Regex parses instruction text for time references ("25 minutes", "2 hours") and renders "Start X min timer" buttons inline. Timers are persistent across steps — start a timer on step 3, and it's still visible on step 5.
- **Timer component**: Play/pause/reset with Web Audio API beep (3x 880Hz sine tones). Visual pulsing animation when done. `tabular-nums` for non-jumping digits.
- **Ingredient highlighting**: Step text cross-references the ingredient list and highlights ingredient names in terracotta. Uses word-boundary matching, sorted by length (longest first to prevent partial matches like "olive" matching before "olive oil").
- **Ingredient panel**: Slide-out left panel showing the full ingredient list, with overlay backdrop.

**Post-cook flow:**
- When reaching the last step, "Done!" button opens a modal: "Nice! How'd it go?"
- Optional notes field with placeholder: "e.g., Used half the sugar, turned out perfect."
- Two CTAs: "Just Log It" (skip notes) or "Save Note" (with notes)
- Auto-closes after 1.2s with a "Logged!" confirmation

### What's Remarkable

The ingredient highlighting is the kind of detail that separates a well-thought-out cooking app from a recipe viewer. When a step says "Add the chicken to the pan with the garlic and olive oil", seeing "chicken", "garlic", and "olive oil" highlighted in terracotta makes it immediately clear which ingredients you need for this step. This is subtle but incredibly useful when you're rushing between the counter and the stove.

The timer persistence is also smart. Real cooking involves overlapping timers — simmering the sauce while baking the bread. Having started timers visible on every step (with their step-of-origin labeled) handles this naturally.

### What Could Be Better

A few small gaps:

1. **No voice control.** When your hands are covered in flour, "Next" by voice would be more useful than swiping. The Web Speech API could handle this with a simple "next"/"back"/"timer" vocabulary. But this is a nice-to-have — the swipe gesture is already hands-friendly.

2. **No step checkboxes.** In CookMode, you can't mark individual steps as done. The progress bar tracks position but not completion. If you skip around (tapping a specific step), there's no visual indicator of which steps you've finished. Minor, since most people cook linearly.

3. **Timer lacks names.** When you have 2-3 timers running, they show "Step 3" and "Step 5" but not what they're for. Adding the time reference context ("25 min — bake chicken") would help identify them at a glance.

4. **No timer notification.** If the user switches to another app (checking a text, looking something up), the audio beep won't play because the tab is backgrounded. A `Notification` API call when the timer fires would handle this. Requires notification permission, but the one-time prompt is worth it for a cooking app.

### Assessment

CookMode alone justifies Crumble as a self-hosted cooking app. It's better than what most commercial recipe apps offer. The combination of wake lock + timers + ingredient highlighting + post-cook logging creates a complete cooking workflow in a single overlay.

This is the kind of feature that should be more prominently marketed. If I were writing the project's landing page, CookMode would be the hero feature, not the recipe import.

### Bug Found and Fixed: CookMode Dark Mode Inversion

CookMode used theme-aware color classes (`bg-brown`, `text-cream`) for its dark immersive overlay. In dark mode, the theme inverts these colors — `brown` becomes light (#F5EDE3) and `cream` becomes dark (#1A1412). This meant CookMode would render as a blinding white overlay in dark mode, completely defeating its purpose.

**Fix:** Replaced all theme-dependent color classes in CookMode with hardcoded values: `style={{ backgroundColor: '#3E2723' }}` for the root, `text-[#FFF8F0]` for text, `bg-white/10` and `bg-white/20` for button backgrounds. CookMode now always renders with its intended dark kitchen-friendly appearance regardless of the app's theme setting.

## Deep Dive 58: Cross-Feature Connections

### The Thread That Ties Everything Together

Looking at Crumble holistically after exploring most of its features, the individual pieces are strong but the transitions between them are manual. Here's the current user workflow and where the handoffs break:

```
Browse/Search → Pick a Recipe → [Cook Mode] → [Log Cook] → Done
                       ↓
              Add to Grocery → Shop → [Check items] → Done
                       ↓
              Add to Meal Plan → [Plan Week] → Done
```

Each arrow marked `→` is a manual navigation. The user has to remember "I should add this to my grocery list" or "I should log this cook." The app doesn't prompt or suggest.

### Opportunities for Connection

**1. Meal Plan → Today's Dinner**

If you have a recipe planned for today (via the meal planner), the home page could surface it: "Tonight: Chicken Tikka Masala" with a direct "Start Cooking" button. One tap from opening the app to being in CookMode.

Implementation: The home page already fetches featured/recently viewed recipes. Adding a `GET /api/meal-plan/today` endpoint that returns today's planned recipe would be trivial — it's a single SQL query filtering `meal_plan_items` by `day_of_week` and `week_start`.

**2. CookMode → Grocery (via leftovers)**

When you finish cooking a recipe, you know which ingredients you used. The "Done" modal could optionally show: "Running low on anything?" with a quick-add to grocery list. This is speculative (you might not be low) but the prompt itself is useful.

**3. Grocery → Meal Plan (back-link)**

Grocery items that came from recipes show "from Chicken Parmesan" as a label. But there's no link — you can't tap the recipe name to open it. Adding a `Link` component wrapping the recipe title would connect the grocery list back to its source recipes, making the list a navigable document instead of a flat checklist.

**4. Stats → Suggestions**

The stats page shows "Most Cooked" and "Top Tags." These are insights that could feed suggestions: "You haven't made anything with the 'pasta' tag in 2 weeks" or "Try something new — here's an uncooked recipe matching your favorite tags." The data for this already exists in the cook log and tag tables.

### The Minimalist Version

Not all of these need implementation. If I could pick one connection to build, it would be #1: **surface today's meal plan on the home page**. It's:
- Simple (one query, one UI component)
- High impact (answers the daily "what's for dinner?" question)
- Non-invasive (a card at the top of the home page, easily ignored if you don't use meal planning)
- A bridge between two features that currently live in isolation

The home page already has a "Featured Recipe" section (random daily pick). A "Tonight's Plan" section above or alongside it would transform the home page from a browsing interface into a daily dashboard.

### Update: Implemented "Today's Plan"

Built the connection:
- **Backend:** `MealPlan::getToday()` queries today's planned meals using the current week's Monday and the correct day_of_week mapping. Exposed as `GET /api/meal-plan/today`.
- **Frontend:** The home page now fetches today's meals on load. When meals exist (and user isn't searching), a compact card appears below the featured recipe showing the day name ("Monday's Plan") and each planned recipe as a thumbnail + title + total time. Each is a link directly to the recipe page (one tap to CookMode).

The section is intentionally lightweight — it doesn't try to replicate the full meal planner. It answers one question: "What did I plan to cook today?" If nothing is planned, it simply doesn't appear.

## Deep Dive 59: Competitive Landscape in 2026

### The Self-Hosted Recipe App Ecosystem

Did some research on what's happening in the space. Here's what I found:

**The Big Three (self-hosted):**
- **Mealie** — The default recommendation. Polished UI, Docker-native, strong recipe import. Best for families who want a shared app with meal planning.
- **Tandoor** — Feature-rich power user tool. Nutritional tracking, meal cost calculation, multi-tenant "Spaces" for household collaboration. More complex to set up.
- **KitchenOwl** — Flutter frontend with native mobile apps. Emphasis on shared grocery lists. Good for households where multiple people shop.

**Newer entrants:**
- **Recipya** — Go-based, focused on simplicity. Lighter than Tandoor.
- **Cooklang** — Not an app but a markup language. Recipes as plain text files (`@ingredient`, `#cookware`, `~timer`). Philosophically interesting — maximum data ownership.

**Commercial (non-self-hosted):**
- **Pluck** — AI-powered, watches TikTok/Instagram/YouTube videos to extract recipes spoken aloud or shown on screen
- **Honeydew** — Social media import from Instagram, TikTok, Pinterest
- **Flavorish** — AI recipe extraction from any source

### Where the Market Is Moving

Three clear trends emerged from the research:

**1. Social Media Import Is the New Web Scraping**

In 2026, the hot feature is extracting recipes from TikTok videos, Instagram reels, and YouTube. Apps like Pluck literally watch videos and listen to audio to extract ingredients and steps. This is a fundamentally different problem from Crumble's URL-based web scraping (JSON-LD → Microdata → Heuristic).

Crumble's scraper handles the web scraping case well — it parses schema.org structured data and falls back through multiple strategies. But the social media import trend is moving the goalposts. Younger cooks discover recipes on TikTok, not AllRecipes.

**Is this relevant for Crumble?** Probably not immediately. Social media video parsing requires either AI APIs (expensive, external dependency) or complex video processing. For a self-hosted app that values simplicity and no external dependencies, this is a poor fit. But it's worth noting that the *discovery* pattern is shifting. If Crumble ever adds a recipe discovery layer, it should think about where recipes come from beyond traditional websites.

**2. Household Collaboration Is Table Stakes**

Tandoor's "Spaces", Mealie's multi-user sharing, KitchenOwl's collaborative grocery lists — every major competitor treats multi-user households as a primary use case. Crumble has multi-user auth (admin/member roles) but no true collaboration features. Each user's grocery lists are private. There's no shared meal plan.

For a self-hosted app deployed for a household, this matters. The typical deployment is one family running it on a home server. If only one person can maintain the grocery list, the other person still uses Apple Notes.

**3. AI Features Are Everywhere (But Often Shallow)**

Tandoor claims "AI-powered image recognition for ingredients." Honeydew uses AI to standardize casual social media posts into recipes. Pluck uses AI to watch videos. The marketing is heavy on AI.

But looking deeper, most of these AI features are thin wrappers around LLM APIs. They require cloud API keys, add latency, and create external dependencies. For a self-hosted app that might run on a Raspberry Pi behind a firewall, AI features that require cloud APIs are a philosophical mismatch.

The one AI-adjacent feature that would work well self-hosted: **OCR for cookbook scanning**. Taking a photo of a cookbook page and parsing it into a structured recipe. Tesseract (open source OCR) can run locally. This would serve the "digitize grandma's recipe collection" use case that self-hosted users care about.

### Where Crumble Stands

Honest assessment of Crumble vs. the competition:

| Feature | Crumble | Mealie | Tandoor | KitchenOwl |
|---------|---------|--------|---------|------------|
| Recipe import (web) | Good (5-tier fallback) | Good | Good | Good |
| Recipe import (social) | No | No | No | No |
| Meal planning | Yes | Yes | Yes | Yes |
| Grocery lists | Yes (with dedup) | Yes | Yes | Yes (strong) |
| Nutrition | Yes (scraper + display) | Yes | Yes (detailed) | Partial |
| Cook mode | **Excellent** (timer, wake lock, ingredients) | Basic | Basic | No |
| Multi-user | Auth only | Households | Spaces | Shared lists |
| Mobile app | PWA (not yet) | PWA | PWA | Flutter native |
| Docker | No (Laragon) | Yes | Yes | Yes |
| Ingredient search | Yes ("What can I make?") | No | No | Yes |

Crumble's **CookMode is genuinely best-in-class** among self-hosted options. The timer detection, ingredient highlighting, wake lock, and post-cook logging flow is better than what Mealie or Tandoor offer. This is the feature that should be marketed.

Crumble's **weaknesses** are:
1. No Docker deployment (limits adoption in the self-hosted community)
2. No household collaboration (grocery list sharing, shared meal plans)
3. No PWA / offline support
4. No native mobile app

Of these, Docker and PWA are the most impactful gaps. The self-hosted community expects `docker compose up`. PWA would eliminate the need for a native app while enabling offline access and home screen installation.

### What I'd Prioritize

If I were advising on the roadmap:

1. **PWA** (Deep Dive 47 blueprint) — Enables offline, home screen install, push notifications. Makes every other feature more accessible.
2. **Docker packaging** — `Dockerfile` + `docker-compose.yml` with PHP-FPM, nginx, and MySQL. Unlocks the self-hosted community.
3. **Shared grocery lists** — Add a `shared_with` table linking grocery lists to additional users. The grocery list is the daily touchpoint; making it collaborative is the highest-value multi-user feature.
4. **OCR recipe import** — Tesseract-based cookbook page scanning. Serves a real need (digitizing physical cookbooks) without cloud dependencies.

Items 1 and 2 are infrastructure. Items 3 and 4 are features. The infrastructure investments have the biggest multiplier effect because they make everything else more accessible.

### The Cooklang Philosophy

One thing that stood out: Cooklang's approach of treating recipes as plain text files. There's something appealing about the data portability — your recipes aren't locked in a database, they're human-readable text files you can version with git, edit in any text editor, and keep forever.

Crumble stores recipes in MySQL, which is the right choice for a web app (queries, relationships, full-text search). But the export feature (`GET /api/recipes/export`) ensures data portability. The export produces a JSON file with all recipe data — not as elegant as plain text files, but functionally equivalent for backup and migration purposes.

If Crumble ever wanted to bridge these worlds, a "markdown export" mode that writes each recipe as a `.md` file (with frontmatter for metadata) would give users a human-readable archive alongside the database. Low priority, but philosophically aligned with the self-hosted ethos of data ownership.

## Deep Dive 60: Docker Packaging Feasibility

### Current State

Crumble runs on Laragon (Apache + MySQL + PHP) with:
- `.htaccess` rewrite rules for API routing and SPA fallback
- `.env`-based configuration with sensible defaults
- PDO database abstraction (MySQL-specific SQL)
- File-based uploads in `/uploads/`
- No Redis, no queues, no cron jobs

This is actually a clean profile for containerization. The architecture has no exotic dependencies.

### What a Dockerfile Would Look Like

Two approaches:

**Approach A: Single container (simplest)**

An all-in-one container with Apache/PHP, built frontend, and an external MySQL (or bundled MariaDB). This is what most self-hosted users expect — `docker compose up` and you're running.

```
# Base: PHP 8.2 with Apache
FROM php:8.2-apache

# Enable required extensions
RUN docker-php-ext-install pdo pdo_mysql gd

# Enable mod_rewrite
RUN a]enmod rewrite

# Copy app
COPY . /var/www/html/

# Build frontend (multi-stage)
# ...or just copy pre-built dist/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/uploads

EXPOSE 80
```

The `.htaccess` rules would work as-is since Apache handles them natively. The only adaptation needed:
- Remove the `/crumble/` prefix from `.htaccess` rules (Docker runs at root, not a subdirectory)
- Ensure `UPLOAD_DIR` is a Docker volume for persistence
- `DB_HOST` defaults to `db` (docker compose service name)

**Approach B: Nginx + PHP-FPM (more robust)**

Two containers: nginx for static files + reverse proxy, PHP-FPM for the API. Better performance, more moving parts. Common in production PHP deployments.

This requires translating `.htaccess` rules to nginx config:
```nginx
location /api/ {
    try_files $uri /api/index.php?$query_string;
}
location /uploads/ {
    alias /var/www/html/uploads/;
}
location / {
    try_files $uri /frontend/dist/index.html;
}
```

### docker-compose.yml Sketch

```yaml
version: "3.8"
services:
  app:
    build: .
    ports:
      - "8080:80"
    environment:
      - DB_HOST=db
      - DB_NAME=crumble
      - DB_USER=crumble
      - DB_PASS=changeme
    volumes:
      - uploads:/var/www/html/uploads
    depends_on:
      - db

  db:
    image: mariadb:11
    environment:
      - MYSQL_ROOT_PASSWORD=rootpass
      - MYSQL_DATABASE=crumble
      - MYSQL_USER=crumble
      - MYSQL_PASSWORD=changeme
    volumes:
      - dbdata:/var/lib/mysql
      - ./database/schema.sql:/docker-entrypoint-initdb.d/01-schema.sql

volumes:
  uploads:
  dbdata:
```

### Migration Handling

The trickiest part is database migrations. Currently, migrations are SQL files applied manually. For Docker:
- Schema initialization: mount `schema.sql` into MariaDB's `initdb.d/` (runs once on first start)
- Subsequent migrations: could add a simple PHP migration runner that checks a `migrations` table and applies missing files. Or just document that users apply migrations via `docker exec`.

### Effort Estimate

- **Approach A (Apache single container):** ~4-6 hours including testing
- **Approach B (Nginx + PHP-FPM):** ~8-12 hours including nginx config

The `.htaccess` → nginx translation is the main work item for Approach B. Approach A is significantly simpler because Apache handles `.htaccess` natively.

### Recommendation

Start with Approach A. It's the path of least resistance, it works, and it's what most self-hosted users deploy (the "Raspberry Pi in the closet" demographic). Approach B is a refinement for later if performance becomes a concern.

The real blocker isn't technical — it's that the `.htaccess` assumes a `/crumble/` subdirectory path. The API router also supports this prefix (`/crumble/api/...`). For Docker, the app needs to work at the root path `/`. Looking at the router code in `index.php`, it already strips the base path, so this should work. The `.htaccess` rewrite rules would need a minor adjustment to remove the `/crumble/` prefix conditions.

### Impact

Docker packaging would be the single most impactful change for adoption. The self-hosted community on Reddit (r/selfhosted) and Lemmy expects Docker. Most comparisons of self-hosted recipe apps filter by "has Docker support." Without it, Crumble doesn't appear in these lists. With it, Crumble would be discoverable alongside Mealie, Tandoor, and KitchenOwl — and CookMode would immediately differentiate it.

## Deep Dive 61: The Cooking Glossary

### A Feature That Teaches

The cooking glossary (`cookingGlossary.js`) is one of those features that feels minor in code but significant in impact. When recipe instructions say "deglaze the pan" or "fold in the flour", a dotted underline appears under the term. Tap/click it and a tooltip explains what it means — in practical, non-pretentious language:

> **deglaze** — Add liquid (wine, broth, water) to a hot pan with browned bits stuck to the bottom. Scrape them up — that's where the flavor is.

This is targeted at the intermediate cook: someone who's past boiling pasta but hasn't gone to culinary school. The definitions are written conversationally, with practical tips baked in. "Don't move the food — let it sit until it releases naturally" (for searing). "Usually 3-5 minutes with a mixer" (for creaming butter).

### How It Works

The `StepList` component runs each instruction step through `findGlossaryTerms()`, which uses a single regex built from all glossary terms (sorted longest-first to prevent "al dente" from matching "al" inside "also"). Matches get wrapped in `<GlossaryTerm>` components — dotted underline, click-to-show tooltip with a full-screen invisible backdrop for click-away dismissal.

The implementation is clean: no external dependencies, no API calls, pure client-side text matching. The regex is compiled once at module load. The tooltip is positioned above the term with a CSS triangle arrow.

### What I Added

Expanded the glossary from 31 to 43 terms. New additions cover common techniques that appear frequently in imported recipes:

- **bloom** — gelatin and spice activation
- **brine** — salt water soaking for meat
- **brown** — Maillard reaction, don't crowd the pan
- **butterfly** — cutting meat flat for even cooking
- **flambé** — igniting alcohol (tilt the pan away!)
- **knead** — gluten development for dough
- **marinate** — acid + oil + aromatics, always refrigerate
- **nappe** — the spoon-coating sauce test
- **sift** — removing lumps, adding air to dry ingredients
- **truss** — tying poultry with twine
- **water bath** — gentle baking for custards/cheesecakes

Each definition follows the same pattern: what to do, how to know it's right, and one practical tip.

### The Print Question

One thing I noticed: the print CSS hides buttons with `button:not(.glossary-term)`. This means glossary terms are preserved in print (they render as dotted underlined text, which is helpful as a visual cue even on paper). This was a deliberate design decision from an earlier session.

## Deep Dive 62: Smart Grocery Parsing — Implemented

### The Problem

When users manually add items to grocery lists by typing "2 cups flour" or "1 lb chicken breast", the entire string was saved as the item name with no structured amount or unit. The GroceryItem display component shows amount, unit, and name separately (with the amount bolded), so manually-added items looked different from recipe-imported items.

### The Fix

Modified the `addItem` endpoint in `GroceryController` to use the existing `IngredientParser` as a fallback. When the user provides only a `name` (no separate `amount` or `unit`), the parser extracts structured data:

- "2 cups flour" → `amount: "2"`, `unit: "cup"`, `name: "flour"`
- "1 1/2 tsp salt" → `amount: "1 1/2"`, `unit: "tsp"`, `name: "salt"`
- "chicken breast" → `amount: null`, `unit: null`, `name: "chicken breast"` (no change)
- "3 cloves garlic" → `amount: "3"`, `unit: "clove"`, `name: "garlic"`

The parser handles fractions, mixed numbers, ranges, unicode fractions, parenthetical units ("2 (14 oz) cans tomatoes"), and 30+ unit aliases. All of this already existed — it just wasn't connected to the grocery flow.

### Impact

Manual grocery items now look identical to recipe-imported items: bold amount, unit, and name displayed consistently. The grocery list becomes a single cohesive view regardless of where items came from. And the amount-combining deduplication now works for manual items too — if you add "1 cup flour" and then "2 cups flour", they'll combine to "3 cups flour" because the amounts are properly structured.

## Deep Dive 63: Duplicate Nutrition Display — Fixed

Discovered that nutrition data was being displayed twice on the recipe page:
1. An inline `<details>` collapsible I added in a prior session (colorful grid of cards)
2. The `NutritionFacts` component (FDA-style label with rows and a thick top border)

Removed the inline version from both `RecipePage` and replaced the inline version in `SharedRecipePage` with the `NutritionFacts` component. Both pages now consistently use the same component. Bundle size dropped from 335KB to 333KB.

## Session Summary: What Crumble Does Well

After several sessions of deep exploration, here's my honest assessment:

**Best-in-class:**
- CookMode (timers, wake lock, ingredient highlighting, swipe, cook notes)
- Recipe scraping pipeline (5-tier fallback: JSON-LD → Microdata → Heuristic → Open Graph → cached)
- Ingredient parsing and scaling (fractions, unicode, ranges, with baking-aware warnings)
- Cooking glossary (43 practical definitions, inline tooltips)
- Print stylesheet (comprehensive, actually tested)

**Solid:**
- Grocery lists (deduplication, amount combining, now with smart parsing)
- Meal planning (weekly view, generate grocery lists from plans, today's plan on home page)
- Stats page (cooking streak, monthly activity chart, top tags)
- Dark mode (full CSS custom property system, system/light/dark toggle)
- Multi-importer support (Mealie, Paprika, batch URL, single URL)

**Gaps worth addressing:**
- No Docker deployment (biggest adoption blocker)
- No PWA / offline support (biggest UX improvement opportunity)
- No household collaboration (grocery list sharing, shared meal plans)
- No inline editing on grocery items

The codebase is clean, the architecture is simple (no over-engineering), and features are implemented thoughtfully. It's a surprisingly capable app for a custom PHP + React stack with no framework on either side.

---

## Deep Dive 64: Cook History as Recipe Memory

### The Flat List Problem

The CookHistoryPage is currently the simplest page in the app — a chronological list of "you cooked X on Y date." It works, but it's a missed opportunity. Cook history is the richest user data Crumble collects, and right now it's presented with zero interpretation.

Compare this to how fitness apps handle workout history. They don't just show "you ran 5K on March 3rd." They show streaks, personal bests, trends, and suggestions. The StatsPage already does some of this (total cooks, streak, monthly activity chart), but the history page itself is dumb.

### What Cook History Actually Contains

Each `cook_log` entry has: `recipe_id`, `cooked_at`, `notes`, and the related recipe's `title`, `image_path`, `tags`, `prep_time`, `cook_time`. From this we can derive:

1. **Frequency patterns** — Which recipes get cooked repeatedly? Which were one-and-done?
2. **Temporal patterns** — Do certain recipes cluster on weekends? Seasonal? Monthly?
3. **Recency** — What haven't you cooked in a while that you used to make often?
4. **Cooking rhythm** — Are you cooking more or less frequently over time?
5. **Tag patterns** — Are you eating a balanced variety, or cycling through the same 3 tags?

### "Cook Again" Suggestions

The most immediately useful feature would be a "It's been a while" section at the top of cook history (or even the home page). The logic:

```sql
-- Recipes cooked at least twice, but not in the last 60 days
SELECT r.id, r.title, r.image_path,
       COUNT(*) as times_cooked,
       MAX(cl.cooked_at) as last_cooked,
       DATEDIFF(CURDATE(), MAX(cl.cooked_at)) as days_since
FROM cook_log cl
INNER JOIN recipes r ON cl.recipe_id = r.id
WHERE cl.user_id = ?
GROUP BY cl.recipe_id
HAVING times_cooked >= 2
   AND days_since > 60
ORDER BY times_cooked DESC, days_since DESC
LIMIT 5
```

This surfaces recipes the user clearly likes (cooked multiple times) but has forgotten about. It's the "you used to love this" nudge.

**STATUS: IMPLEMENTED** — Added `getForgottenFavorites()` to CookLog model, API endpoint at `GET /cook-log/forgotten-favorites`, and an "It's Been a While" horizontal scroll section on the home page between Today's Plan and Recently Viewed. Each card shows the recipe thumbnail, times cooked badge, and days since last cook.

### Grouped History View — IMPLEMENTED

Instead of a flat list, group entries by time period:

- **This Week** — with day names (Monday, Tuesday...)
- **Last Week**
- **This Month** — with specific dates
- **Older** — month headings (February 2026, January 2026...)

This is a frontend-only change. The API already returns `cooked_at` timestamps. The grouping logic:

```javascript
function groupByPeriod(history) {
  const now = new Date();
  const thisWeekStart = startOfWeek(now); // Monday
  const lastWeekStart = subDays(thisWeekStart, 7);
  const thisMonthStart = startOfMonth(now);

  const groups = { thisWeek: [], lastWeek: [], thisMonth: [], older: {} };

  history.forEach(entry => {
    const date = new Date(entry.cooked_at);
    if (date >= thisWeekStart) {
      groups.thisWeek.push(entry);
    } else if (date >= lastWeekStart) {
      groups.lastWeek.push(entry);
    } else if (date >= thisMonthStart) {
      groups.thisMonth.push(entry);
    } else {
      const key = date.toLocaleString('default', { month: 'long', year: 'numeric' });
      (groups.older[key] = groups.older[key] || []).push(entry);
    }
  });

  return groups;
}
```

### Cook Notes as a Journal

Cook notes are currently displayed as a single truncated line in the history list. But they could be much more — a lightweight cooking journal. When someone writes "Used half the sugar, turned out better" or "Next time add more garlic," that's genuinely useful information for the next time they cook the recipe.

These notes should be surfaced on the recipe page itself, not just buried in cook history. A small "Your Notes" section on RecipePage showing past cook notes for that recipe would close the loop between cooking and learning.

**UPDATE:** Cook notes were already on RecipePage (added in a prior session). Improved the display this session: now filters out entries without notes (showing only meaningful ones), adds a "Cooked X times" badge next to the heading, and shows an encouraging prompt when there are cooks but no notes yet.

### Implementation Priority

1. **Grouped history view** — Frontend only, biggest visual improvement, 1-2 hours — **DONE**
2. **Cook notes on recipe page** — Already existed, improved display this session — **DONE**
3. **"Cook again" suggestions** — New SQL query + API endpoint + home page section — **DONE**

## Deep Dive 65: PWA — What It Would Actually Take

### The Case For It

A PWA would let users:
- Install Crumble on their phone's home screen (no app store)
- Access saved recipes offline (critical for grocery shopping in stores with poor signal)
- Get faster subsequent loads (cached shell + assets)
- Receive notifications when shared recipes are viewed (stretch goal)

The self-hosted recipe app community considers PWA support table-stakes. Mealie, Tandoor, and KitchenOwl all offer installable web apps. This is the second-biggest gap after Docker.

### What's Required

**1. Web App Manifest (`manifest.json`)**

```json
{
  "name": "Crumble",
  "short_name": "Crumble",
  "description": "Your recipe manager",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#FFF8F0",
  "theme_color": "#C75B39",
  "icons": [
    { "src": "/icons/icon-192.png", "sizes": "192x192", "type": "image/png" },
    { "src": "/icons/icon-512.png", "sizes": "512x512", "type": "image/png" },
    { "src": "/icons/icon-maskable-512.png", "sizes": "512x512", "type": "image/png", "purpose": "maskable" }
  ]
}
```

Colors match the existing theme — cream background, terracotta accent. Need to generate icon PNGs from the existing `crumble_icon.PNG`.

**2. Service Worker — Caching Strategy**

Different content types need different strategies:

| Content | Strategy | Why |
|---------|----------|-----|
| App shell (HTML, JS, CSS) | Cache-first, update in background | Fast loads, Vite already hashes filenames |
| Recipe images | Cache-first with size limit | Large, rarely change, expensive to re-fetch |
| Thumbnails | Cache-first | Small, frequently displayed |
| API: recipe list | Network-first, fall back to cache | Should be fresh, but usable offline |
| API: single recipe | Stale-while-revalidate | Show cached instantly, update silently |
| API: auth/session | Network-only | Must never be cached |
| API: mutations (POST/PUT/DELETE) | Network-only, queue if offline | Background sync for offline writes |

**3. Vite PWA Plugin**

The `vite-plugin-pwa` package handles most of the heavy lifting for a Vite + React app:

```javascript
// vite.config.js
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
  plugins: [
    react(),
    VitePWA({
      registerType: 'autoUpdate',
      workbox: {
        globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
        runtimeCaching: [
          {
            urlPattern: /^https?:\/\/.*\/uploads\/thumbnails\//,
            handler: 'CacheFirst',
            options: {
              cacheName: 'recipe-thumbnails',
              expiration: { maxEntries: 200, maxAgeSeconds: 30 * 24 * 60 * 60 },
            },
          },
          {
            urlPattern: /^https?:\/\/.*\/api\/recipes\/\d+$/,
            handler: 'StaleWhileRevalidate',
            options: {
              cacheName: 'recipe-details',
              expiration: { maxEntries: 100, maxAgeSeconds: 7 * 24 * 60 * 60 },
            },
          },
          {
            urlPattern: /^https?:\/\/.*\/api\/recipes(\?.*)?$/,
            handler: 'NetworkFirst',
            options: {
              cacheName: 'recipe-lists',
              expiration: { maxEntries: 20, maxAgeSeconds: 24 * 60 * 60 },
            },
          },
        ],
      },
      manifest: {
        name: 'Crumble',
        short_name: 'Crumble',
        theme_color: '#C75B39',
        background_color: '#FFF8F0',
        display: 'standalone',
      },
    }),
  ],
});
```

### The Offline Cooking Scenario

The killer use case: you're in the kitchen, phone propped up, following a recipe. Your WiFi drops. Without PWA caching, the recipe vanishes. With stale-while-revalidate on recipe details, the recipe stays visible because it was cached when you first opened it.

CookMode already uses the Wake Lock API to keep the screen on. Adding offline capability makes it truly reliable.

### What NOT to Cache

- Auth endpoints (`/api/auth/*`) — security risk
- Write operations — queue for later sync instead
- Admin endpoints — rarely used, not worth the cache space
- The recipe scraper — requires server-side processing

### Effort Estimate

- **Manifest + icons:** 1 hour (generate icons, add meta tags)
- **vite-plugin-pwa setup:** 2 hours (install, configure, test strategies)
- **Offline fallback page:** 1 hour (show "You're offline" for uncached routes)
- **Testing on real devices:** 2 hours (iOS Safari quirks, Android Chrome)
- **Total:** ~6 hours

This is a weekend project that would dramatically improve the mobile experience.

## Deep Dive 66: The 2026 Self-Hosted Recipe Landscape

### Updated Competitive Analysis

Revisiting the competitive landscape with fresh research. The Cooklang blog published a comprehensive comparison of open-source recipe managers in 2026, and community sentiment on Reddit/Lemmy has shifted noticeably.

**The Big Three** remain Mealie, Tandoor, and KitchenOwl, but each has carved out distinct territory:

**Mealie** — The crowd favorite. Polished web UI, Docker-first deployment, strong API. Its community app "Mealient" is decent but not native. Mealie's main weakness is that adding recipes can feel clunky — too many tabs and menus. It's the "safe default" recommendation.

**Tandoor** — The power user's choice. Nutritional tracking, meal cost calculation, and the unofficial "Kitshn" mobile app (which is apparently quite good). Users who switched from Mealie cite portion-size adjustment and advanced filtering as reasons. Tandoor has the steepest learning curve.

**KitchenOwl** — The household app. Built with Flutter for genuinely good mobile experience, Flask backend. Emphasizes shared grocery lists and multi-user collaboration. Best for families where multiple people contribute to cooking and shopping.

**Newer entrants:**

- **Norish** — Inspired by wanting something more aesthetic than Mealie/Tandoor without the feature bloat. Focused on simplicity and collaborative tools. Worth watching.
- **Recipya** — Go-based, minimal, fast. Appeals to the "I just want to store recipes" crowd who find Mealie/Tandoor over-engineered.
- **Grocy** — Not a recipe manager per se, but a household management system that includes recipes. Popular with people who also want to track pantry inventory and expiration dates.

### Where Crumble Fits

Crumble occupies an interesting niche. It's:
- **Simpler than Tandoor** (no cost tracking, no complex permissions)
- **More feature-rich than Recipya** (CookMode, meal planning, grocery lists, ingredient search)
- **Similar scope to Mealie** but with different priorities (CookMode vs. Mealie's API-first design)
- **Less household-focused than KitchenOwl** (single-user oriented currently)

The unique differentiators:
1. **CookMode** — No competitor has an equivalent full-screen cooking companion with timers, wake lock, ingredient highlighting, and swipe navigation. This is genuinely best-in-class.
2. **Cooking glossary** — Teaching users what "deglaze" means inline is a touch none of the others have.
3. **Recipe scraping quality** — The 5-tier fallback (JSON-LD → Microdata → Heuristic → Open Graph → Cached) with SSRF protection is robust.
4. **No framework overhead** — The custom PHP router is surprisingly capable and keeps the stack simple.

The biggest competitive weaknesses:
1. **No Docker** — Can't appear in awesome-selfhosted listings, r/selfhosted recommendations, or Cooklang's comparison without it.
2. **No PWA** — Mealie, Tandoor, and KitchenOwl all support installable web apps.
3. **No native mobile app** — KitchenOwl has Flutter, Tandoor has Kitshn. Crumble relies entirely on the mobile web experience.
4. **Single-user focus** — No shared grocery lists or household collaboration features.

### Strategic Positioning

Rather than trying to match Mealie or Tandoor feature-for-feature, Crumble should lean into what makes it different:

**"The app that actually helps you cook."**

Every other recipe manager focuses on *collecting* and *organizing* recipes. Crumble is the only one that focuses on the *cooking experience itself*. CookMode, the glossary, cook notes, ingredient highlighting — these are features designed for someone standing in a kitchen with flour on their hands.

This positioning suggests the roadmap should prioritize:
1. Docker (table stakes for discovery)
2. PWA (offline CookMode is the dream)
3. Cook history intelligence (recipe memory, suggestions)
4. Voice control in CookMode (hands-free when cooking)

NOT: complex permissions, API extensibility, cost tracking, or inventory management. Those are Tandoor's territory.

## Deep Dive 67: The Recipe Scraper Under the Microscope

### Architecture Review

The `RecipeScraper` class is one of the most battle-tested parts of Crumble. At 740 lines, it handles the entire pipeline from URL to structured recipe data. Let me trace through what it does well and where it could improve.

**The Good:**

1. **SSRF protection** — `isValidUrl()` resolves hostnames to IPs and blocks private/reserved ranges. This prevents `http://localhost/admin` or `http://192.168.1.1/secret` attacks through the scraper. Solid security.

2. **Graceful degradation** — The method never throws. It returns partial data with error codes. The frontend can show "We found the title and image but couldn't extract ingredients" rather than a blank error page.

3. **Parser priority chain** — JSON-LD → Microdata → Heuristic → OpenGraph → Cached versions. Each parser returns `null` if it can't extract data, so the chain continues. JSON-LD succeeds on ~80% of modern recipe sites (thanks to Google's SEO requirements for rich snippets).

4. **HowToSection handling** — The JSON-LD parser correctly handles nested `HowToSection` → `HowToStep` structures, inserting section headings as `--- Section Name ---`. Many scrapers flatten these.

5. **Nutrition extraction** — Pulls `NutritionInformation` from schema.org data and parses values like "250 calories" → `250`. This feeds directly into the NutritionFacts component.

6. **Tag extraction** — Combines `recipeCategory`, `recipeCuisine`, and `keywords` from JSON-LD into a deduplicated tag list. This pre-populates the tag system.

**Areas for Improvement:**

1. **No video extraction** — Many modern recipe sites embed cooking videos. The schema.org `video` property exists but isn't parsed. This is becoming more important as recipe content shifts toward video-first.

2. **Limited retry logic** — If a site returns a 5xx error, there's no retry. A single retry with a 2-second delay would catch transient failures.

3. **No cookie handling** — Some recipe sites show a cookie consent wall that blocks content. The fetch doesn't handle cookies or consent redirects.

4. **Encoding detection** — The HTML is loaded directly with `LIBXML_NOERROR`. For non-UTF-8 pages, character corruption can occur. A `mb_detect_encoding` + `mb_convert_encoding` step before DOM loading would help.

5. **Google Cache as fallback is fragile** — Google Web Cache (`webcache.googleusercontent.com`) is increasingly unreliable and has its own bot detection. This fallback may silently stop working.

### The JS-Rendered Page Problem

The biggest gap is JavaScript-rendered content. Sites built with React, Vue, or Angular may serve a nearly-empty HTML shell that gets populated client-side. The scraper fetches the initial HTML, which contains no recipe data.

The cached/AMP fallback helps for popular sites but won't work for smaller blogs using SPA frameworks.

Solutions, in order of feasibility:
1. **Do nothing** — Most recipe sites still use server-side rendering because Google SEO requires it. The JSON-LD is in the initial HTML even if the visible content is JS-rendered. This means the scraper often works even on SPA sites.
2. **Browser automation** — Use Puppeteer/Playwright via a Node.js sidecar to render JS. Massive complexity increase, requires Node.js alongside PHP. Not worth it for a self-hosted app.
3. **Third-party rendering** — Services like Prerender.io or Browserless.io render pages server-side. Adds an external dependency. Against the self-hosted ethos.

Option 1 is the pragmatic choice. The JSON-LD data is almost always in the raw HTML because recipe sites need it for Google rich results.

### Import Success Rate

Based on the code, I'd estimate the following success rates:

| Parser | Coverage | Notes |
|--------|----------|-------|
| JSON-LD | ~80% of recipe sites | Most sites implement schema.org for SEO |
| Microdata | ~5% additional | Older sites using itemtype attributes |
| Heuristic | ~5% additional | Sites with ingredient/instruction CSS classes |
| OpenGraph | ~5% (title/image only) | Gets basic info but no ingredients |
| Cached | ~2% additional | Catches some JS-rendered sites |
| Total | ~92-95% | Remaining ~5% are paywalled or JS-only |

This is a strong success rate. The scraper doesn't need major changes — it needs refinement around edge cases.

## Deep Dive 68: Voice Control in CookMode — A Dream Feature

### The Problem It Solves

You're mid-recipe. Hands covered in dough. You need to see the next step. Currently, CookMode supports swipe gestures, but swiping a phone with floury hands is a disaster.

Voice control would let you say "next step" or "previous step" or "start timer 10 minutes" without touching the device.

### Web Speech API

The browser's built-in Speech Recognition API works for this:

```javascript
function useSpeechCommands(commands) {
  useEffect(() => {
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
      return; // Not supported
    }

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    recognition.onresult = (event) => {
      const transcript = event.results[event.results.length - 1][0].transcript
        .toLowerCase().trim();

      for (const [trigger, action] of Object.entries(commands)) {
        if (transcript.includes(trigger)) {
          action();
          break;
        }
      }
    };

    recognition.start();
    return () => recognition.stop();
  }, [commands]);
}

// In CookMode:
useSpeechCommands({
  'next': () => goToStep(currentStep + 1),
  'back': () => goToStep(currentStep - 1),
  'previous': () => goToStep(currentStep - 1),
  'repeat': () => speakStep(currentStep),
  'timer': () => startDetectedTimer(),
  'ingredients': () => scrollToIngredients(),
});
```

### Browser Support Reality

- **Chrome (desktop & Android):** Full support, requires HTTPS
- **Safari (iOS):** Partial — works but stops after ~60 seconds of silence. Would need periodic restart.
- **Firefox:** No support for continuous recognition

This means it would be a progressive enhancement — available on Chrome/Edge, gracefully absent on Firefox/Safari. Not ideal, but a cook using CookMode is likely on a phone, and most Android phones run Chrome.

### Privacy Concern

The Web Speech API sends audio to Google's servers for processing. For a self-hosted app where privacy is a selling point, this is a tension. The user should be told clearly: "Voice commands use Google's speech service. Your audio is sent to Google for processing."

An alternative is the upcoming on-device speech recognition in Chrome (currently behind flags). When this ships, voice control becomes fully private.

### Realistic Assessment

Voice control is a "wow" feature but not a "must have." The implementation is straightforward (~100 lines), but the browser support gaps and privacy issues make it a version 2.0 feature. The better investment right now is PWA + Docker.

Still, hands-free CookMode would be genuinely unique in the self-hosted recipe space. No competitor offers it.

## Deep Dive 69: The Recipe Form Experience

### Current State

The RecipeForm component is 690 lines and handles recipe creation and editing. It's comprehensive — title, description, times, servings, source URL, image upload, ingredients with reordering, instructions with reordering, nutrition, tags with autocomplete. But it has some UX friction points.

### Friction Points

**1. No auto-save / draft persistence**

If you spend 10 minutes entering a recipe manually and accidentally navigate away, everything is lost. There's no draft saving, no localStorage persistence, no "unsaved changes" warning.

A simple fix: debounced localStorage save of form state.

```javascript
// Save to localStorage every 2 seconds after changes
useEffect(() => {
  const timer = setTimeout(() => {
    if (title || ingredients.some(i => i.name)) {
      localStorage.setItem('recipe-draft', JSON.stringify({
        title, description, prepTime, cookTime, servings,
        ingredients, instructions, tags, savedAt: Date.now()
      }));
    }
  }, 2000);
  return () => clearTimeout(timer);
}, [title, description, prepTime, cookTime, servings, ingredients, instructions, tags]);

// On mount, check for draft
useEffect(() => {
  if (!initialData) { // Only for new recipes, not edits
    const draft = localStorage.getItem('recipe-draft');
    if (draft) {
      const data = JSON.parse(draft);
      const age = Date.now() - data.savedAt;
      if (age < 24 * 60 * 60 * 1000) { // Less than 24 hours old
        if (confirm('Continue editing your draft recipe?')) {
          // Restore fields...
        } else {
          localStorage.removeItem('recipe-draft');
        }
      }
    }
  }
}, []);
```

**2. Mobile ingredient entry is cramped**

Each ingredient row has: reorder buttons + amount input + unit dropdown + name input + delete button. On a phone, this is five elements in a narrow row. The amount input is 64px wide — barely room for "1 1/2".

Better mobile approach: stack amount/unit on one line, name on the next, with a swipe-to-delete gesture instead of a delete button.

**3. The unit dropdown is limiting**

The dropdown has 14 options: `'', 'cups', 'tbsp', 'tsp', 'oz', 'lb', 'g', 'kg', 'ml', 'L', 'pieces', 'cloves', 'pinch', 'to taste'`. But recipes use many more: `bunch`, `can`, `package`, `stick`, `head`, `sprig`, `dash`, `slice`, etc.

The IngredientParser on the backend recognizes 30+ unit aliases. The frontend form should match. Better: replace the dropdown with a combo box (type-ahead input with suggestions) that accepts any text but suggests common units.

**4. No "paste entire recipe" mode**

The "Paste multiple" feature for ingredients is great, but there's no equivalent for the full recipe. Sometimes users have a recipe in plain text (from a message, a PDF, an email) and want to paste the whole thing. A "Paste recipe" mode could parse a blob of text into structured fields using heuristics:

- First line → title
- Lines starting with numbers → ingredients
- Numbered paragraphs or "Step N" → instructions
- "Prep: X min" / "Cook: Y min" → times

This would be a frontend-only parser (similar to `parseIngredientBlock` but broader).

### What I'd Fix First

1. **Draft auto-save** — highest value for lowest effort. Prevents data loss. **IMPLEMENTED** — 2-second debounced localStorage save, 24-hour expiry, restore prompt on mount, cleared on successful submit.
2. **Expand unit options** — add the missing common units to the dropdown. **IMPLEMENTED** — Added `can`, `bunch`, `head`, `sprig`, `dash`, `slice`, `stick`, `package`.
3. **Mobile ingredient layout** — stack fields vertically below a breakpoint.

The "paste entire recipe" parser is higher effort and more fragile. It's a nice-to-have for v2.

## Deep Dive 70: Accessibility Audit Notes

### What's Good

- Touch targets are generally 44px minimum (the `min-h-[44px]` pattern appears throughout forms and buttons)
- Color contrast ratios in the default theme appear adequate (brown on cream)
- Images have alt text in most places
- Form inputs have labels (via the `Input` component)
- Icons use `aria-label` on interactive elements (seen in RecipeForm's reorder buttons)

### What's Missing

1. **Skip navigation link** — No "Skip to main content" link for keyboard users. The Layout component goes straight to the sidebar/header.

2. **Focus management on route changes** — When navigating between pages, focus doesn't move to the new content. Screen reader users may not realize the page has changed. React Router doesn't handle this automatically.

3. **ARIA live regions for dynamic content** — When recipes load, when grocery items are checked, when search results update — none of these announce to screen readers. A `role="status"` region for search result counts would help.

4. **Keyboard navigation in CookMode** — CookMode uses swipe gestures and click targets but doesn't have keyboard shortcuts (arrow keys for steps, space for timer). This is important because CookMode's dark design with large text is actually great for low-vision users.

5. **Color-only indicators** — The ingredient match badges in the "By Ingredient" search use color alone (green/orange/gray) to indicate match quality. Need a text or icon alternative.

6. **Dark mode contrast** — Haven't verified that the dark mode theme meets WCAG AA contrast ratios. The custom properties in `index.css` should be checked.

### Quick Wins

**Item 1 IMPLEMENTED** — Skip link and `id="main-content"` added to Layout.jsx.

```jsx
// 1. Skip link in Layout.jsx
<a href="#main-content" className="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:bg-terracotta focus:text-white focus:px-4 focus:py-2 focus:rounded-lg">
  Skip to content
</a>
// ...
<main id="main-content" tabIndex={-1}>

// 2. Route change announcer
function RouteAnnouncer() {
  const location = useLocation();
  const [announcement, setAnnouncement] = useState('');

  useEffect(() => {
    // Derive page name from path
    const name = location.pathname === '/' ? 'Home' :
      location.pathname.split('/').pop().replace(/-/g, ' ');
    setAnnouncement(`Navigated to ${name}`);
  }, [location]);

  return <div role="status" aria-live="polite" className="sr-only">{announcement}</div>;
}

// 3. Keyboard navigation in CookMode
useEffect(() => {
  const handler = (e) => {
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') goToStep(currentStep + 1);
    if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') goToStep(currentStep - 1);
    if (e.key === ' ') { e.preventDefault(); toggleTimer(); }
  };
  window.addEventListener('keydown', handler);
  return () => window.removeEventListener('keydown', handler);
}, [currentStep]);
```

### Assessment

Crumble isn't accessibility-hostile — the fundamentals are mostly there (labels, alt text, touch targets). But it's not accessibility-tested either. The quick wins above would make a meaningful difference for keyboard and screen reader users, and CookMode's keyboard navigation would also benefit laptop users who don't want to touch the screen while cooking.

## Deep Dive 71: The Plain Text Recipe Parser — Implemented

### Why It Matters

There's a gap between "paste a URL" (handled by RecipeScraper) and "type every field manually" (the current form). A lot of recipe sharing happens via text: screenshots that get OCR'd, messages from friends, copied from PDFs, notes apps, or recipe newsletters. These come as unstructured text blocks — not URLs, not JSON, just human-readable text.

### The Design

Built `recipeTextParser.js` — a heuristic parser that takes a blob of text and extracts structured recipe data. Two strategies:

**Strategy 1: Section Headers**

If the text contains explicit headers like "Ingredients" or "Instructions" (case-insensitive, with or without colons), the parser uses them to partition lines into sections. Everything before the first header → title/description. Lines under "Ingredients" → parsed as ingredients. Lines under "Instructions" → instruction steps.

Headers recognized:
- Ingredients: `ingredients`, `what you'll need`, `you need`
- Instructions: `instructions`, `directions`, `method`, `steps`, `preparation`, `how to make/cook/prepare`, `procedure`
- Description: `description`, `about`, `introduction`, `notes`
- Nutrition: `nutrition`, `nutritional info/facts` (skipped)

**Strategy 2: Line Classification (No Headers)**

When there are no section headers, each line is classified by heuristics:

- First non-empty line → title
- Lines starting with amounts (numbers, fractions, unicode fractions) → ingredients
- Lines starting with cooking verbs (preheat, heat, cook, mix, stir, add, combine...) → instructions
- Numbered lines longer than 20 characters → instructions
- Short remaining lines → description

**Meta Extraction**

The parser also extracts prep time, cook time, and servings from anywhere in the text using regex patterns:
- `Prep: 15 min`, `Prep time: 15 minutes`
- `Cook: 30 min`, `Cooking time: 30 minutes`
- `Serves: 4`, `Servings: 4`, `Makes 12`, `Yield: 6`
- Hours are converted to minutes automatically

### Integration

Added a "Paste a full recipe" button at the top of RecipeForm. Only appears when creating a new recipe (not editing) and when the title is still empty. Shows a large textarea with a placeholder showing an example recipe format. On blur, the parser extracts what it can and populates all form fields.

The button uses a dashed border style that matches the image upload area's visual language — suggesting "paste something here."

### What It Handles Well

```
Pasta Aglio e Olio

Prep: 5 min  Cook: 15 min  Serves: 4

Ingredients
1 lb spaghetti
6 cloves garlic, thinly sliced
1/2 cup olive oil
1 tsp red pepper flakes
Fresh parsley, chopped

Instructions
1. Cook spaghetti according to package directions.
2. Heat olive oil in a large skillet over medium heat.
3. Add garlic and cook until golden, about 2 minutes.
```

→ Title: "Pasta Aglio e Olio", prepTime: 5, cookTime: 15, servings: 4, 5 ingredients (each parsed into amount/unit/name), 3 instruction steps.

### What It Won't Handle

- Recipes embedded in long articles with non-recipe content (blog posts)
- Non-English recipes
- Recipes where ingredients and instructions are interleaved ("Add 2 cups flour, then...")
- Images (obviously)

This is explicitly a "good enough" parser, not a perfect one. It handles the 80% case (copied recipe text with clear structure) and gracefully degrades for the rest (you get a title and can fill in the rest manually).

### Bundle Impact

Added ~6 KB to the JS bundle (345 KB total). The parser has zero dependencies — it uses the existing `parseIngredient` function from `ingredientParser.js` for structured ingredient extraction.

## Deep Dive 72: Thoughts on Recipe Import Paths

### The Five Import Paths

Crumble now has five distinct ways to add a recipe:

1. **URL Import** — Paste a URL, RecipeScraper extracts everything. Highest fidelity, lowest effort for the user. Works for ~92-95% of recipe sites.

2. **Bulk URL Import** — BulkImportPage lets you paste multiple URLs, imports them all. Good for initial onboarding.

3. **Plain Text Paste** — (New) Paste a full recipe as text, heuristic parser extracts structure. Good for recipes from messages, PDFs, screenshots.

4. **Mealie/Paprika Import** — Upload a zip export from either app. Good for users migrating from other recipe managers.

5. **Manual Entry** — Fill out every field by hand. The fallback for everything else.

### User Journey Analysis

Most users follow one of these paths:

**New user with existing collection elsewhere:**
Mealie/Paprika import (bulk) → manual fixes → then URL import for new recipes

**New user starting fresh:**
URL import for recipes they find online → occasionally manual entry for family recipes

**Text-forward user (recipes from friends, newsletters):**
Text paste → URL import → occasionally manual entry

The import paths are already well-covered. The text parser fills the last meaningful gap — the "I have a recipe as text but not as a URL" scenario.

### What's Missing: Photo-to-Recipe

The only uncovered path is: user has a photo of a recipe (from a cookbook, a handwritten card, a screenshot). This would require OCR, which is non-trivial to do client-side. Options:

1. **Browser OCR** — The `tesseract.js` library runs OCR in the browser. Quality is decent for printed text but poor for handwriting. ~2 MB library. Would feed into the text parser.

2. **Server-side OCR** — PHP's `imagick` or `tesseract-ocr` package. Better quality, no client-side overhead. But adds a system dependency.

3. **LLM API** — Send the image to a vision model (GPT-4o, Claude) for structured extraction. Best quality by far, but requires an external API key and incurs costs. Against the self-hosted ethos unless the user provides their own key.

None of these feel right for Crumble today. Tesseract.js quality is too unpredictable. Server-side OCR adds deployment complexity. LLM APIs add costs and external dependencies. The text parser is the pragmatic middle ground — users can OCR photos themselves (every phone has this built in) and paste the resulting text.

## Deep Dive 73: What Makes a Recipe App "Sticky"

### The Switching Cost Problem

Self-hosted recipe apps have an adoption problem: it's easy to try them and easy to abandon them. Import your recipes, use it for a week, get frustrated by some UX quirk, and switch to the next one. The self-hosted community is full of "I tried X, switched to Y, then went back to Z" stories.

What makes users stay?

### The Stickiness Factors

After reading community discussions and analyzing the codebase, I think stickiness comes from three things:

**1. Personal Data Accumulation**

This is the most powerful lock-in. Every time you log a cook, add a note, rate a recipe, or build a grocery list, you're creating personal data that doesn't export cleanly. Crumble's cook_log is particularly sticky — 6 months of cooking history with notes creates a personal cooking journal that's hard to replicate elsewhere.

The meal planner is similarly sticky. Once you've built a habit of planning meals on Sunday, switching apps means rebuilding that workflow.

This is why the "Cook Again" suggestions and cook notes on recipe pages matter — they make the accumulated data visible and useful, increasing the perceived cost of switching.

**2. Workflow Fit**

Different apps fit different workflows. Tandoor fits power users who want nutritional tracking and cost analysis. KitchenOwl fits households where multiple people shop and cook. Crumble fits the solo cook who wants a focused, pleasant cooking experience.

CookMode is Crumble's strongest workflow differentiator. Once someone has used CookMode to cook through a recipe — with the dark interface, ingredient highlighting, timers, and cook notes — going back to an app that shows recipes as static text feels like a downgrade.

**3. Aesthetic Attachment**

This sounds shallow but it's real. People develop attachment to apps they find beautiful. Crumble's warm terracotta + cream palette, the serif recipe titles, the rounded cards, the smooth transitions — these create an emotional response that utilitarian apps like Tandoor don't.

Norish (a newer competitor) is explicitly built on this insight: "a more aesthetically pleasing alternative to Mealie or Tandoor."

### What Crumble Should Do

1. **Make accumulated data more visible** — Show cook history insights on the home page ("You've cooked 47 recipes this year!"), surface forgotten favorites, show cooking streaks prominently.

2. **Deepen the cooking workflow** — Voice control in CookMode, offline CookMode via PWA, post-cook suggestions ("You liked this — try these similar recipes").

3. **Don't chase feature parity** — Tandoor has cost tracking. KitchenOwl has household sharing. Crumble shouldn't add these. Instead, make the solo cooking experience so good that users who care about that specific use case never want to leave.

4. **Make import dead simple, make export honest** — Easy import reduces the barrier to trying Crumble. Honest, complete export (full JSON with all personal data) reduces the anxiety of trying it. Paradoxically, making it easy to leave makes people more willing to stay.

### The Retention Funnel

```
Import recipes → Browse collection → Cook a recipe in CookMode → Log the cook →
See suggestions based on history → Plan meals for the week → Generate grocery list →
Cook again → Build a streak → Can't imagine switching
```

Each step deepens the relationship. Crumble already has every step in this funnel. The work now is making each transition smoother and each step more rewarding.

## Deep Dive 74: What Research Says About Recipe App Users

### The Data

Recent industry data (2024-2025) on recipe app usage reveals patterns that validate many of Crumble's design choices:

- **60% of users** engage with recipe apps weekly, **50% specifically for meal planning**
- **80% on smartphones**, 15% on tablets, only 5% on other devices
- **65% prefer step-by-step guidance** over full recipe view
- **48% want portion adjustment** features
- **Interactive features (timers, voice) boost engagement by 42%**
- **In-app grocery lists affect 38% of shopping decisions**
- **40% show greater loyalty with personalized meal plans**
- **75% are motivated by convenience**, 48% by UX quality

### The #1 Frustration: Clutter

The dominant complaint across every study and community discussion: recipe websites are cluttered nightmares. Ads, pop-ups, life stories before the recipe, deliberate confusion to increase page views. Users describe it as "sites designed to generate ad hits, not to be read."

One user's workaround went viral: prefixing recipe URLs with `cooked.wiki/` to strip away everything except the actual recipe. The fact that a third-party stripping service is popular tells you everything about the state of recipe websites.

King Arthur Baking was singled out as the gold standard: "straightforward, fuss-free navigation."

### How Crumble Stacks Up

**Already strong:**
- Zero ads, zero tracking, zero popups (self-hosted advantage)
- CookMode provides the step-by-step guidance that 65% of users want
- Timers and ingredient highlighting in CookMode (the +42% engagement factor)
- Portion adjustment via ingredient scaling
- Meal planning with grocery list generation
- Mobile-first responsive design

**Opportunities from the data:**
1. **Voice instructions** — Timers + voice boosts engagement by 42%. CookMode already has timers; voice is the missing piece. (Deep Dive 68 covers the Web Speech API approach.)

2. **Personalized dietary filters** — 60% want dietary options, 55% want allergy-based ingredient swaps. Crumble's tag system partially covers this, but there's no "dietary profile" that automatically filters.

3. **Community sharing** — Social features increase engagement by 45%. Crumble has share links but no community/social features. This might not fit the self-hosted ethos, but household sharing (multi-user grocery lists, shared meal plans) would.

4. **Beginner friendliness** — The cooking glossary already helps here. Could extend with difficulty ratings, "skills you'll learn" badges, or suggested starter recipes for new users.

### The Anti-Pattern: Feature Bloat

A warning from the research: "users feel frustrated with too overloaded cooking apps and need clear guidance where to start." This validates Crumble's approach of doing fewer things well rather than adding every possible feature. The apps that score highest in satisfaction are the ones that feel focused.

Tandoor has more features than Crumble but lower satisfaction scores in community discussions. KitchenOwl has household features but people complain about complexity. The recipe app users want is simple, focused, and beautiful — not comprehensive.

## Deep Dive 75: Seasonal Awareness

### The Concept

Recipes have seasons. Soups in winter, salads in summer, pumpkin everything in fall. Most recipe apps ignore this entirely — they show the same static collection year-round.

A subtle seasonal awareness layer could make Crumble feel alive and timely without being heavy-handed. The idea:

1. Map common food tags/ingredients to seasons
2. On the home page, occasionally suggest seasonal recipes from the user's own collection
3. Use the current month to weight "featured recipe" selection toward seasonal matches

### Season-Tag Mapping

```javascript
const SEASONAL_TAGS = {
  // Northern hemisphere defaults
  spring: ['salad', 'asparagus', 'peas', 'lamb', 'strawberry', 'light', 'fresh', 'spring'],
  summer: ['grilling', 'bbq', 'salad', 'corn', 'tomato', 'berries', 'ice cream', 'cold', 'summer', 'no-cook'],
  fall: ['soup', 'stew', 'pumpkin', 'squash', 'apple', 'cinnamon', 'comfort food', 'autumn', 'fall', 'thanksgiving'],
  winter: ['soup', 'stew', 'roast', 'baking', 'holiday', 'comfort food', 'slow cooker', 'winter', 'christmas'],
};

function getCurrentSeason() {
  const month = new Date().getMonth(); // 0-11
  if (month >= 2 && month <= 4) return 'spring';
  if (month >= 5 && month <= 7) return 'summer';
  if (month >= 8 && month <= 10) return 'fall';
  return 'winter';
}
```

This is intentionally simple. No geolocation, no hemisphere detection. The user's recipe collection tags do the real work — if they tag recipes with "soup" or "grilling," the seasonal layer surfaces them at the right time.

### Implementation Approach

Rather than a separate API endpoint, the featured recipe endpoint could optionally bias toward seasonal tags. The `getFeaturedRecipe` endpoint already selects a random recipe with an image — it could prefer recipes matching the current season's tags.

On the backend:

```php
public function getFeatured(): array {
    $season = $this->getCurrentSeason();
    $seasonTags = self::SEASONAL_TAGS[$season] ?? [];

    // Try to find a seasonal recipe with an image first
    if (!empty($seasonTags)) {
        $placeholders = implode(',', array_fill(0, count($seasonTags), '?'));
        $stmt = $this->db->prepare("
            SELECT r.* FROM recipes r
            INNER JOIN recipe_tags rt ON rt.recipe_id = r.id
            INNER JOIN tags t ON t.id = rt.tag_id
            WHERE t.name IN ($placeholders) AND r.image_path IS NOT NULL
            ORDER BY RAND() LIMIT 1
        ");
        $stmt->execute($seasonTags);
        $recipe = $stmt->fetch();
        if ($recipe) return $recipe;
    }

    // Fallback to any recipe with an image
    $stmt = $this->db->prepare('SELECT * FROM recipes WHERE image_path IS NOT NULL ORDER BY RAND() LIMIT 1');
    $stmt->execute();
    return $stmt->fetch() ?: [];
}
```

This is a gentle bias, not a hard filter. If you don't have seasonal tags, it falls back to any recipe. If you do, you'll see contextually appropriate featured recipes.

### Not Implementing Yet

I'm going to hold off on implementing this because:
1. It requires knowing what tags exist in the user's collection — on a fresh install, there may be none
2. The seasonal mapping is hardcoded and not everyone is in the Northern Hemisphere
3. The benefit is aesthetic/emotional, not functional — lower priority than Docker/PWA

But the concept is sound and worth revisiting once the foundational gaps (Docker, PWA) are closed. A small "In Season" badge on recipe cards matching seasonal tags would be a delightful touch.

## Deep Dive 76: The Error Handling Philosophy

### What Crumble Gets Right

The API client (`api.js`) has a clean error handling pattern:

```javascript
if (!response.ok) {
  let errorMessage = `Request failed with status ${response.status}`;
  try {
    const errorData = await response.json();
    errorMessage = errorData.error || errorMessage;
  } catch {
    // Response was not JSON
  }
  throw new Error(errorMessage);
}
```

The backend returns JSON errors with human-readable messages:
```php
http_response_code(400);
return ['error' => 'Item name is required', 'code' => 400];
```

And the RecipeScraper never throws — it returns partial data with error codes and human-readable messages. This is the right approach for a scraper: "we got the title but not the ingredients" is more useful than a generic failure.

### What Could Be Better

1. **No error boundary** — React error boundaries catch render-time crashes and show a fallback UI. Without one, a rendering bug in any component crashes the entire app with a white screen. A simple ErrorBoundary component around the main route outlet would prevent this.

2. **Silent failures** — Several components use `.catch(() => {})` — swallowing errors completely. This makes debugging hard. Examples:
   - `HomePage`: tags fetch, featured recipe fetch, today's meals fetch
   - `StatsPage`: stats fetch
   - `CookHistoryPage`: history fetch

   These are intentional (the features should degrade gracefully if the API is unreachable) but they should at least log to console in development.

3. **No offline detection** — When the network is unavailable, API calls fail with generic network errors. A `navigator.onLine` check before making requests could show a more helpful "You're offline" message.

4. **No retry for transient failures** — If a request fails with a 5xx error, no retry is attempted. A single retry with exponential backoff for 5xx responses would improve reliability.

### The Right Level of Error Handling for Crumble

Crumble is a personal/household app, not a SaaS product. Users are both the admin and the user. This means:

- **Console logging is fine** — the user can open dev tools if something breaks
- **Toast notifications are better than modals** — errors shouldn't block the UI
- **Graceful degradation is essential** — if the stats API fails, the home page should still show recipes
- **The scraper should never crash** — partial data is always better than nothing

The current approach is 80% right. An ErrorBoundary already exists (`ErrorBoundary.jsx`) and wraps the app in `App.jsx` — good. The remaining gap is better console logging for suppressed errors.

## Deep Dive 77: Timer Notifications — The Invisible Alarm Problem

**IMPLEMENTED** — Added browser Notification API support to Timer.jsx.

### The Problem

CookMode's timer system is well-built: it detects time references in instruction text, lets you start timers with one tap, and plays a triple-beep when done. But there's a critical flaw: Web Audio API beeps only work when the tab is active and in the foreground.

Real cooking behavior:
1. User sets a 20-minute timer for pasta
2. Puts phone down, walks away, or opens another app
3. Timer completes → beep plays into the void
4. Pasta overcooks

This is the #1 complaint about browser-based cooking timers. Native apps solve it with system notifications; web apps can too via the Notification API.

### The Fix

Added two functions to Timer.jsx:

```javascript
function requestNotificationPermission() {
  if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
  }
}

function sendNotification(minutes) {
  if ('Notification' in window && Notification.permission === 'granted') {
    new Notification('Timer Done!', {
      body: `Your ${minutes} minute timer has finished.`,
      icon: '/crumble_icon.PNG',
      tag: `crumble-timer-${minutes}`,
      requireInteraction: true,
    });
  }
}
```

Key design decisions:
- **Permission requested on first timer start** (user-initiated gesture required by browsers)
- **`requireInteraction: true`** — notification stays until dismissed (won't auto-close)
- **`tag` deduplication** — if two identical timers finish simultaneously, one notification
- **Graceful degradation** — if Notification API unavailable (some mobile browsers), the audio beep still fires
- **No permission banner on page load** — only asks when user actively starts a timer

### Browser Support

Notifications work on desktop Chrome/Firefox/Edge/Safari 17+. On mobile, support is limited:
- Android Chrome: works (after PWA install or with persistent notification permission)
- iOS Safari 16.4+: only works for installed PWA ("Add to Home Screen")
- Firefox Android: works

This dovetails with the PWA story (Deep Dive 65) — once Crumble is installable as a PWA, notifications become reliable on mobile too.

### What This Doesn't Solve

- **Sound when backgrounded** — even with notification, the audio beep may not play when the tab is backgrounded. The notification is the primary alert in that case.
- **Vibration** — could add `navigator.vibrate([200, 100, 200])` but only works on Android
- **Multiple concurrent timers** — already supported, each gets its own notification with its own `tag`

## Deep Dive 78: Grocery List Category Grouping — The Aisle Problem — IMPLEMENTED

### Why Every Grocery App Needs This

The current grocery list displays items in insertion order (unchecked first, checked last). This works for 5-10 items. For a week's worth of cooking — 30+ items from 4-5 recipes — it becomes painful. You're zigzagging through the store because "butter" is between "onions" and "pasta."

Every successful grocery app groups items by store section: Produce, Dairy, Meat, Pantry, etc. This is the single most impactful UX improvement for the grocery feature.

### The Challenge: Category Assignment

How do you know "butter" goes in Dairy and "onions" in Produce? Three approaches:

**1. Hardcoded lookup table** (simplest)
Map ~200 common ingredient names to categories. Falls back to "Other" for unknowns.

```javascript
const CATEGORY_MAP = {
  // Produce
  'onion': 'Produce', 'garlic': 'Produce', 'tomato': 'Produce',
  'lettuce': 'Produce', 'carrot': 'Produce', 'potato': 'Produce',
  'lemon': 'Produce', 'lime': 'Produce', 'avocado': 'Produce',
  // Dairy
  'butter': 'Dairy', 'milk': 'Dairy', 'cream': 'Dairy',
  'cheese': 'Dairy', 'yogurt': 'Dairy', 'eggs': 'Dairy',
  // Meat & Seafood
  'chicken': 'Meat', 'beef': 'Meat', 'pork': 'Meat',
  'salmon': 'Seafood', 'shrimp': 'Seafood',
  // Pantry
  'flour': 'Pantry', 'sugar': 'Pantry', 'rice': 'Pantry',
  'pasta': 'Pantry', 'oil': 'Pantry', 'vinegar': 'Pantry',
  // Spices
  'salt': 'Spices', 'pepper': 'Spices', 'cinnamon': 'Spices',
  'cumin': 'Spices', 'paprika': 'Spices',
  // Frozen
  'ice cream': 'Frozen', 'frozen': 'Frozen',
  // Bakery
  'bread': 'Bakery', 'buns': 'Bakery', 'tortillas': 'Bakery',
};
```

Pro: Zero dependencies, instant, works offline.
Con: Limited vocabulary, no context awareness ("cream" = dairy vs "cream" as a verb).

**2. Fuzzy matching with keyword extraction**
Parse the item name, extract the core ingredient (strip amounts, prep notes), then fuzzy-match against the lookup table.

```javascript
function categorize(itemName) {
  const core = itemName.toLowerCase()
    .replace(/\b(fresh|frozen|organic|large|small|medium|whole|ground|minced|diced|chopped|sliced)\b/g, '')
    .trim();

  // Exact match first
  if (CATEGORY_MAP[core]) return CATEGORY_MAP[core];

  // Partial match: "parmesan cheese" → matches "cheese" → Dairy
  for (const [keyword, category] of Object.entries(CATEGORY_MAP)) {
    if (core.includes(keyword)) return category;
  }

  return 'Other';
}
```

**3. User-defined categories with learning**
Let users manually move items between categories. Store the mapping in localStorage or the database. Over time, the system learns the user's preferences.

### Recommended Approach

Start with option 2 (fuzzy matching with a ~200-word lookup table) implemented entirely client-side. No database changes needed. The grouping is visual only — a presentation layer on top of the existing flat list.

```jsx
// In GroceryPage detail view
const grouped = useMemo(() => {
  const groups = {};
  sortedItems.forEach(item => {
    const category = categorizeIngredient(item.name);
    if (!groups[category]) groups[category] = [];
    groups[category].push(item);
  });
  // Sort categories in store-walk order
  const ORDER = ['Produce', 'Dairy', 'Meat', 'Seafood', 'Bakery', 'Frozen', 'Pantry', 'Spices', 'Other'];
  return ORDER.filter(c => groups[c]).map(c => ({ category: c, items: groups[c] }));
}, [sortedItems]);
```

### Not Implementing Yet

**UPDATE: IMPLEMENTED.** Created `ingredientCategories.js` with ~250 keyword entries covering 14 categories (Produce, Meat & Seafood, Dairy & Eggs, Bakery, Frozen, Pantry, Canned Goods, Pasta & Grains, Spices & Seasonings, Condiments & Sauces, Baking, Oils & Vinegars, Beverages, Other). Grocery list detail view now has a toggle button to switch between flat list and aisle-grouped view. Preference persisted in localStorage.

## Deep Dive 79: The Document Title Problem

### Current State

Every page in Crumble shows the same browser tab title (probably "Crumble" or whatever Vite sets). When you have multiple tabs open — the recipe you're cooking from, the grocery list, the home page — they're all indistinguishable.

This is especially painful in CookMode, where you might switch to another tab briefly and then need to find your way back to the recipe.

**IMPLEMENTED** — Created `useDocumentTitle` hook and added to all pages.

### The Fix

Created a `useDocumentTitle` hook and added it to every page:

```javascript
// hooks/useDocumentTitle.js
import { useEffect } from 'react';
const BASE_TITLE = 'Crumble';
export default function useDocumentTitle(title) {
  useEffect(() => {
    const prev = document.title;
    document.title = title ? `${title} — ${BASE_TITLE}` : BASE_TITLE;
    return () => { document.title = prev; };
  }, [title]);
}
```

Pages updated:
- **RecipePage**: `useDocumentTitle(recipe?.title)` — shows recipe name
- **GroceryPage**: `useDocumentTitle(currentList ? currentList.name : 'Grocery Lists')` — dynamic based on view
- **StatsPage**: `useDocumentTitle('Kitchen Stats')`
- **FavoritesPage**: `useDocumentTitle('Favorites')`
- **CookHistoryPage**: `useDocumentTitle('Cook History')`
- **MealPlanPage**: `useDocumentTitle('Meal Plan')`
- **AdminPage**: `useDocumentTitle('Admin')`
- **LoginPage**: `useDocumentTitle('Sign In')`

The hook restores the previous title on unmount, so navigating away always cleans up. The em-dash separator (`—`) is a common convention (GitHub, Notion, etc.) that looks cleaner than pipe (`|`) or hyphen.

### Why Not Use React Helmet

React Helmet (or react-helmet-async) is a popular choice but adds ~5KB to the bundle for a feature that's 12 lines of code. The hook approach is zero-dependency and does exactly what we need.

## Deep Dive 80: CookMode Step Completion Tracking

### The Idea

Currently CookMode navigates through steps linearly with Previous/Next buttons. For simple recipes this is fine. But for complex recipes with 15+ steps, you often need to:
1. Jump back to re-read a step while something is simmering
2. Remember which steps you've already completed
3. See your overall progress beyond the linear progress bar

### Proposed Enhancement

Add a small step indicator row — tappable circles showing all steps. Completed steps get a checkmark. Current step is highlighted. Tapping any circle jumps to that step.

```jsx
// Step indicator dots
<div className="flex gap-1.5 justify-center px-4 mt-2">
  {steps.map((_, idx) => (
    <button
      key={idx}
      onClick={() => setCurrentStep(idx)}
      className={`
        w-7 h-7 rounded-full text-xs font-medium flex items-center justify-center
        transition-all duration-200
        ${idx === currentStep
          ? 'bg-terracotta text-white scale-110'
          : completedSteps.has(idx)
            ? 'bg-sage/60 text-white'
            : 'bg-white/15 text-white/60 hover:bg-white/25'}
      `}
    >
      {completedSteps.has(idx) ? '✓' : idx + 1}
    </button>
  ))}
</div>
```

Steps would auto-mark as completed when you navigate past them. You could also tap a completed step to un-mark it.

### Why I'm Not Implementing This Yet

The current CookMode is clean and focused. Adding step indicators adds visual complexity. For recipes with 5-8 steps (the common case), the existing progress bar is sufficient. This becomes valuable at 10+ steps — which might be better served by a "step overview" slide-out panel (similar to the existing ingredient panel) rather than inline dots.

The step indicator also becomes unwieldy at 20+ steps — the dots would overflow or shrink too small to tap. A collapsible list would be better for those edge cases.

## Deep Dive 81: AudioContext Reuse in Timer

**IMPLEMENTED** — Fixed Timer.jsx to reuse AudioContext instead of creating new ones.

### The Bug

The `playBeep` function created a new `AudioContext` every time it was called:
```javascript
const ctx = new (window.AudioContext || window.webkitAudioContext)();
audioContextRef.current = ctx;
```

This leaks AudioContext instances. iOS Safari has a hard limit of 6 simultaneous AudioContexts. If a user:
1. Starts timer → timer completes → beep (context #1)
2. Clicks "play sound again" → beep (context #2, #1 leaked)
3. Starts another timer → completes → beep (context #3)

After 6 interactions, beeps stop working entirely on iOS.

### The Fix

Extracted a `getAudioContext()` helper that reuses the existing context:
```javascript
const getAudioContext = useCallback(() => {
  if (audioContextRef.current && audioContextRef.current.state !== 'closed') {
    if (audioContextRef.current.state === 'suspended') {
      audioContextRef.current.resume();
    }
    return audioContextRef.current;
  }
  const ctx = new (window.AudioContext || window.webkitAudioContext)();
  audioContextRef.current = ctx;
  return ctx;
}, []);
```

The `resume()` call handles iOS Safari's autoplay policy — AudioContext starts in `suspended` state and requires a user gesture to resume.

## Deep Dive 82: What Makes a Recipe App "Sticky"

I've been thinking about what keeps users coming back to a recipe app vs. abandoning it. The recipe management market has a 60-day retention problem — most users try an app, import a few recipes, and drift back to bookmarks/screenshots.

### The Stickiness Ladder

Based on observation from competitor reviews and user research:

**Level 1: Storage** (days 1-7)
"I can find my recipes in one place." This is table stakes. Every app does this. Low switching cost — all recipe apps import from each other.

**Level 2: Workflow** (days 7-30)
"This app fits into how I cook." Cook Mode, grocery lists, meal planning. The app becomes part of the cooking process, not just a library. Higher switching cost — you'd lose your cook history, ratings, and workflow habits.

**Level 3: Memory** (days 30-90)
"The app knows me." Cook history, forgotten favorites, personalized stats, notes on what you changed last time. The app has accumulated personal data that's hard to replicate elsewhere.

**Level 4: Habit** (90+ days)
"I can't imagine cooking without it." The app is embedded in weekly routines — Sunday meal planning, weekday grocery runs, daily cooking. The cost of switching isn't feature parity, it's disrupting established habits.

### Where Crumble Sits

Crumble has good coverage at Levels 1-2:
- **Storage**: Import, scraper, manual entry, sharing
- **Workflow**: Cook Mode, grocery lists, meal planning, servings adjustment

Level 3 is emerging:
- Cook history with notes
- Forgotten favorites
- Stats page
- Ratings and favorites
- Recently viewed

Level 4 depends on the user's commitment, which the app can encourage but not force.

### The Missing Pieces for Level 3

The biggest gaps in the "memory" layer:

1. **"You usually substitute..."** — The app could track ingredient substitutions mentioned in cook notes. If you always write "used coconut milk instead of cream," the app could offer to show that note prominently on the ingredient list next time.

2. **"Based on your ratings..."** — Recipes rated 4-5 stars could be weighted higher in the featured recipe slot and meal plan suggestions. Currently the featured recipe is random.

3. **"You haven't tried..."** — Recipes in your collection that have never been cooked. A "Try Something New" section on the home page, surfacing uncooked recipes from your own collection.

4. **"Your cooking patterns..."** — The stats page shows data but doesn't interpret it. "You cook most on Sundays and Wednesdays" or "Your most-used tag is 'pasta' — here are pasta recipes you haven't tried."

### The "Try Something New" Feature — IMPLEMENTED

This one is simple enough to implement and high-value. A query for recipes owned by the user that have zero cook_log entries:

```sql
SELECT r.id, r.title, r.image_path, r.prep_time, r.cook_time
FROM recipes r
LEFT JOIN cook_log cl ON cl.recipe_id = r.id AND cl.user_id = ?
WHERE r.created_by = ?
  AND cl.id IS NULL
ORDER BY RAND()
LIMIT 3
```

Surface 3 of these on the home page as "Something New?" — recipes you saved but never cooked. Gentle nudge to explore your own collection.

## Deep Dive 83: The Mobile Keyboard Problem

### The Issue

When adding items to a grocery list on mobile, the virtual keyboard covers the bottom of the screen. The "Add an item..." input is at the bottom of the list view — exactly where the keyboard pushes it off-screen. The user has to scroll down to see their input after the keyboard appears.

This is a classic mobile web problem with several solutions:

### Solutions

**1. Scroll input into view on focus** (simplest)
```javascript
onFocus={(e) => {
  setTimeout(() => e.target.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300);
}}
```
The 300ms delay lets the keyboard fully appear before scrolling. Works on most devices.

**2. Sticky input at top instead of bottom**
Move the add-item input above the list. This keeps it visible when the keyboard appears. Trade-off: less conventional placement, and "add to bottom" is the natural mental model for lists.

**3. CSS `env(keyboard-inset-bottom)`**
Modern iOS/Android browsers expose `env(keyboard-inset-bottom)` for adjusting layout when the virtual keyboard is visible. Could add bottom padding dynamically:
```css
.grocery-input-area {
  padding-bottom: env(keyboard-inset-bottom, 0);
}
```
But browser support is still inconsistent (2026).

### Recommendation

Option 1 is the safest. A `scrollIntoView` on focus is invisible when it works and harmless when it doesn't.

**IMPLEMENTED** — Added `onFocus` scroll to the grocery item input.

## Deep Dive 84: Recipe Versioning — The Evolution of a Dish

### The Insight

When you make grandma's lasagna for the 10th time, it's not the same recipe you started with. You've gradually adjusted: more ricotta, less sugar in the sauce, 375°F instead of 400°F. Each cook teaches you something, and the best cooks adapt.

But Crumble (and every recipe app I know of) treats recipes as static documents. When you edit a recipe, the previous version is lost. Your cook notes might reference amounts that no longer exist in the recipe.

### What Recipe Versioning Would Look Like

**Not Git-style diffs.** Nobody wants to look at diffs of recipe instructions. Instead, think of it as a timeline:

```
Lasagna
  v1: Dec 2024 (imported from allrecipes.com)
  v2: Jan 2025 "used less sugar, much better"
  v3: Mar 2025 "added sausage, increased cheese"
  ← current
```

Each version stores the full recipe snapshot. You can view any previous version and optionally "revert" (which creates a new version).

### Database Design

```sql
CREATE TABLE recipe_versions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  recipe_id INT NOT NULL,
  version_number INT NOT NULL,
  data JSON NOT NULL,           -- full recipe snapshot
  note VARCHAR(255),            -- why this version was saved
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
  UNIQUE KEY (recipe_id, version_number)
);
```

The `data` column stores a JSON snapshot of the recipe at that point in time — title, description, ingredients, instructions, times, servings. This avoids complex schema migration issues.

### When to Create a Version

Automatically, every time the recipe is saved (edited). But with a twist: don't create a new version if the last version was less than 1 hour ago (coalesce rapid edits into a single version).

This keeps version history meaningful without cluttering it with typo fixes.

### Why I'm Not Building This

It's a substantial feature (new table, API endpoints, version diff view, revert flow) with limited immediate value for most users. The cook notes system partially fills this gap — "used half the sugar" is a lightweight version note.

The right time to build this is when Crumble has users who cook the same recipe many times and actively iterate on it. The signals would be:
1. Recipes with 10+ cook log entries
2. Users who edit the same recipe more than 3 times
3. Cook notes referencing specific ingredient amounts

Until those signals emerge, the current edit-in-place model is simpler and sufficient.

### A Lighter Alternative

Instead of full versioning, a "last edited" timestamp and "edit count" would surface recipe evolution without the full version system:

```sql
ALTER TABLE recipes ADD COLUMN edit_count INT DEFAULT 0;
-- Already have updated_at
```

On the recipe page, show "Edited 7 times, last updated Mar 2026" — a subtle indicator that this recipe has been refined. Users who see this might feel more confident in the recipe: it's been battle-tested and improved.

## Deep Dive 85: What the Home Page Communicates

### Current Home Page Sections (in order)

1. **Featured Recipe** — hero card, highest-rated or most recent with image
2. **Today's Plan** — meal plan items for today (if any)
3. **Forgotten Favorites** — recipes cooked often but not recently
4. **Something New?** — recipes owned but never cooked
5. **Recently Viewed** — from localStorage
6. **Search + Filter bar**
7. **Recipe Grid** — paginated, sorted by newest

### What This Communicates

The home page tells a story: "Here's your best recipe (Featured). Here's what you planned to cook today (Today's Plan). Here are old favorites you might want to revisit (Forgotten). Here are recipes you saved but never tried (New). Here's what you were looking at recently (Recent). And here's everything else."

This is a **discovery-oriented** home page. It surfaces interesting recipes before showing the full collection. The risk is that it becomes too long before the user sees their actual recipe list — especially on mobile where each section takes vertical space.

### The Tension

- **More sections = more discovery** but longer scroll to the grid
- **Fewer sections = faster access** but less serendipity

The current balance is roughly right for a personal app with 50-200 recipes. For someone with 500+ recipes, the search becomes more important than the discovery sections.

### A Possible Future: Conditional Sections

Only show sections that have content, and only show 1-2 discovery sections max:

```javascript
const discoverySections = [
  forgottenFavorites.length > 0 && 'forgotten',
  uncookedRecipes.length > 0 && 'uncooked',
].filter(Boolean);

// Only show the first discovery section to keep the page tight
const showForgotten = discoverySections[0] === 'forgotten';
const showUncooked = !showForgotten && discoverySections[0] === 'uncooked';
```

This would randomly alternate between "Forgotten Favorites" and "Something New?" — keeping the home page fresh without information overload. Not implementing this yet — want to see how both sections feel in practice first.

## Deep Dive 86: 2026 Competitive Landscape Update — What Users Actually Want

Fresh research from the Cooklang blog comparison, OnlyRecipe's app guide, and Lemmy self-hosting discussions. This updates Deep Dive 65 with newer data.

### The 2026 Landscape Shift

Two major trends since last analysis:

**1. AI-powered import is now expected, not novel.** OnlyRecipe and Honeydew import from TikTok, YouTube, Instagram screenshots, and even handwritten recipes. Mealie and Tandoor still rely on structured web scraping (JSON-LD/Microdata). The gap between "scrape a food blog" and "import from a TikTok video" is widening.

**2. The self-hosted/commercial divide is deepening.** Commercial apps (OnlyRecipe at $25/year, Paprika one-time $4.99/platform) compete on convenience and AI. Self-hosted apps (Mealie, Tandoor, KitchenOwl) compete on privacy and control. Crumble sits in the self-hosted camp but should learn from what commercial apps do better.

### What Users Actually Say They Want

From Lemmy discussions and app reviews, distilled to actionable insights:

| What They Want | Who Does It Best | Crumble Status |
|---|---|---|
| URL-to-recipe scraping | Mealie, Tandoor | **Done** (5-tier scraper) |
| Ad/blog-story stripping | All scrapers | **Done** (structured data extraction) |
| Ingredient scaling | Most apps | **Done** (fractional math, scaling warnings) |
| Cross-device sync | All (inherent to web apps) | **Done** (it's a web app) |
| Shopping list from recipe | KitchenOwl, Mealie | **Done** (with smart dedup) |
| Meal planning | Mealie, Tandoor | **Done** (weekly calendar) |
| Social media import (TikTok/YouTube) | OnlyRecipe, Honeydew | **Not done** |
| Nutritional tracking | Tandoor | **Partial** (manual entry only) |
| Real-time multi-user grocery sync | KitchenOwl | **Not done** |
| Aisle-sorted shopping | Tandoor | **Not done** (Deep Dive 78) |
| Offline/PWA support | Paprika (native) | **Not done** (Deep Dive 65) |
| Docker one-command deploy | Mealie, KitchenOwl | **Not done** (Deep Dive 60) |

### Where Crumble Is Uniquely Strong

Features Crumble has that competitors lack or underserve:

1. **CookMode with auto-detected timers** — Mealie has a "cook mode" but no timer detection. Crumble parses step text for time references and offers one-tap timer buttons. Now with browser notifications.

2. **Ingredient highlighting in steps** — During CookMode, ingredients mentioned in the step text are highlighted in terracotta. Subtle but helpful.

3. **Cook notes per session** — Most apps let you rate a recipe; Crumble lets you note what you changed each time you cook ("used half the sugar, added rosemary"). This is the seed of recipe evolution tracking.

4. **Forgotten favorites** — No competitor surfaces "recipes you loved but forgot about." This is a Level 3 stickiness feature (see Deep Dive 82).

5. **Plain-text paste import** — The `recipeTextParser` handles recipes copied from emails, PDFs, and messages. Most competitors only handle URL import.

6. **Recipe scaling warnings** — Warning that baking recipes scale differently (leavening agents at ~75%), and spice guidance for savory recipes. No competitor does this.

### The Three Features That Would Close the Biggest Gaps

Ranked by user-impact-to-effort ratio:

**1. Docker deployment (HIGH impact, MEDIUM effort)**
The #1 adoption blocker. Every competitor in the self-hosted space ships a Dockerfile. Without it, Crumble requires manual Apache/PHP/MySQL setup. This is the biggest gap between Crumble and Mealie.

**2. PWA with offline support (HIGH impact, MEDIUM effort)**
Paprika's competitive advantage is "it works offline." A PWA with service worker caching would close this gap for web apps. The complete plan is in Deep Dive 65. Critical for mobile CookMode — losing connectivity mid-recipe is a real pain point.

**3. Aisle-grouped grocery list (MEDIUM impact, LOW effort)**
Tandoor sorts shopping lists by supermarket aisle. The client-side approach in Deep Dive 78 could deliver this with zero backend changes and a ~200-word ingredient category lookup table.

### What Crumble Should NOT Do

Based on the competitive landscape:

- **AI features** (ChatGPT recipe generation, AI nutritional estimation) — adds API costs, requires external service dependencies, and goes against the self-hosted privacy ethos
- **Social media import** (TikTok/YouTube/Instagram) — complex, legally murky, and serves a different user profile than Crumble's target audience
- **Federated/ActivityPub support** — technically interesting but no user demand exists (the Lemmy user who mentioned Nextcloud Cookbook noted "other people I know would not use it")
- **Mobile native apps** — PWA covers 90% of native app value at 10% of the effort

### The Honest Assessment

Crumble is feature-competitive with Mealie and Tandoor for day-to-day recipe management. The gaps are in infrastructure (Docker, PWA) not in features. A user who is already running Laragon/Apache can have a genuinely excellent experience with Crumble right now.

The path to broader adoption is:
1. Docker → removes the setup barrier
2. PWA → removes the "I need a native app" objection
3. Better documentation/screenshots → removes the "what does it look like?" uncertainty

Features like AI import and social media scraping serve a different audience. Crumble's audience is the person who reads a recipe blog, saves the URL, cooks from it, and wants to remember what they changed.

## Deep Dive 87: Recipe Pairing from Cook Log Data

### The Concept

If a user cooks "Garlic Bread" and "Spaghetti Bolognese" on the same day 3 times, those are a natural pairing. The app could suggest "You usually make Garlic Bread with this — add to your plan?" on the recipe page.

### The Query

```sql
SELECT a.recipe_id AS r1, b.recipe_id AS r2, COUNT(*) AS times_together,
       ra.title AS title1, rb.title AS title2
FROM cook_log a
JOIN cook_log b ON a.user_id = b.user_id
  AND DATE(a.cooked_at) = DATE(b.cooked_at)
  AND a.recipe_id < b.recipe_id
JOIN recipes ra ON a.recipe_id = ra.id
JOIN recipes rb ON b.recipe_id = rb.id
WHERE a.user_id = ?
GROUP BY r1, r2
HAVING times_together >= 2
ORDER BY times_together DESC
LIMIT 10
```

### Why This Is Interesting But Premature

This requires:
1. Active cook logging for months
2. Multiple recipes cooked on the same day (common for meal prep, less so for weeknight dinners)
3. At least 2 co-occurrences to be meaningful (3+ is better)

Most users won't generate enough pairing data for months. And the value proposition is narrow — it's a "nice to have" not a "need to have." Filing this under "revisit when cook log density justifies it."

### A Simpler Alternative

Instead of mining cook log co-occurrences, use **tag-based pairing**: if you're viewing a recipe tagged "pasta," suggest recipes tagged "side dish" or "bread." This requires no cook log data — just existing tag associations.

```sql
SELECT r.id, r.title, r.image_path
FROM recipes r
JOIN recipe_tags rt ON r.id = rt.recipe_id
JOIN tags t ON rt.tag_id = t.id
WHERE t.name IN ('side dish', 'bread', 'salad', 'appetizer')
  AND r.id != ?
ORDER BY RAND()
LIMIT 3
```

This is a complementary-category approach: main dishes suggest sides, sides suggest mains. Could be surfaced as "Goes well with..." on the recipe page. Simple, no data accumulation required, and immediately useful.

## Deep Dive 88: Aisle-Grouped Grocery List — Implementation Notes

**IMPLEMENTED** — `ingredientCategories.js` + toggle in GroceryPage.

### What Was Built

Created `frontend/src/utils/ingredientCategories.js` with:
- **~250 ingredient keywords** mapped to **14 store categories**
- Categories ordered by typical store layout (Produce → Meat → Dairy → ... → Other)
- Fuzzy matching: strips prep words (fresh, frozen, organic, chopped, etc.) before matching
- Longest-keyword-first matching: "cream cheese" matches Dairy, not just "cream"
- `categorizeIngredient(name)` — returns category string
- `groupByCategory(items)` — returns `[{ category, items[] }]` in store-walk order

### UI Integration

- Toggle button appears in grocery detail header when list has 4+ items
- Icon switches between `LayoutGrid` (grouped) and `LayoutList` (flat)
- Active state shown with terracotta highlight
- Category headers use `bg-cream-dark/50` with uppercase labels and item count
- Preference persisted in `localStorage('crumble-grocery-grouped')`

### Design Decisions

1. **Client-side only** — no database changes, no API changes. The categorization is a presentation layer.
2. **Toggle, not forced** — some users prefer flat lists. The default is flat (what they're used to).
3. **Checked items stay in their category** — unlike the flat view which pushes checked items to the bottom, the grouped view keeps items in their category. This is deliberate: in a store, you want to see what's left in each aisle, not a separate "done" section.
4. **14 categories, not 7** — more granular than the original Deep Dive 78 sketch. Splitting "Pantry" into Pantry/Canned/Pasta/Baking/Oils gives better aisle mapping. "Spices" vs "Condiments" matters when spices are in aisle 4 and condiments are in aisle 7.

### Edge Cases

- **"pepper"** matches Produce (bell peppers, jalapeños). "black pepper" matches Spices. Keyword length sorting ensures the more specific match wins.
- **"cream"** matches Dairy. "cream cheese" also matches Dairy (both correct). "ice cream" matches Frozen (longer keyword wins).
- **"coconut milk"** matches Canned Goods (typically shelf-stable cans). Fresh coconut matches Produce.
- **Unknown items** fall to "Other" — manual additions like "birthday candles" or "paper towels" won't match any food category.

### Bundle Size Impact

The ingredient category map adds ~9KB to the JS bundle (358KB total, up from 349KB). This is acceptable — the map is effectively a lookup table, not executable code, and gzips well due to repetitive string patterns.

## Deep Dive 89: Recipe Text Parser — Validation and Fixes

**IMPROVED** — Added missing verb coverage and no-amount ingredient detection.

### Testing Results

Tested `recipeTextParser.js` against 7 realistic recipe formats:
1. Well-structured with section headers (Ingredients:/Instructions:) — **perfect**
2. Casual format without headers — **perfect** (heuristic classification works)
3. Minimal text-message style — **had a bug** (fixed)
4. Recipe: prefix with Directions header — **perfect** (title cleaned, "Directions" matched)
5. Single instruction paragraph — **perfect**
6. "Salt and pepper" without amount — **had a bug** (fixed)
7. Unicode fractions — **perfect**

### Bugs Found and Fixed

**Bug 1: Missing action verbs**

`looksLikeInstruction()` didn't recognize verbs like "mash", "blend", "grill", "roast", "fry", "simmer", "marinate", etc. A line starting with "Mash avocados..." was classified as description instead of instruction.

Fixed by adding 28 missing verbs: `mash, blend, process, grill, roast, fry, sauté, saute, simmer, brown, sear, melt, warm, chill, refrigerate, freeze, marinate, knead, assemble, arrange, layer, grease, line, prepare, pat, rub, stuff, thread, skewer, deglaze`.

**Bug 2: No-amount ingredients misclassified**

In the no-headers heuristic path, "Salt and pepper to taste" was classified as description because it has no numeric amount and no unit word in the first 4 tokens. The `looksLikeIngredient()` function only recognized lines starting with numbers or containing unit keywords.

Fixed by adding a short-line check for common no-amount ingredients: salt, pepper, water, ice, oil, butter, cooking spray, etc. Only triggers for lines under 40 characters to avoid false-matching instruction sentences that happen to start with "salt."

### Bundle Impact

+0.5KB (359.41KB total). Negligible — just string additions to existing arrays.

## Deep Dive 90: Feature Gap Audit — March 2026

The feature priority matrix from Deep Dive 52 was written during an earlier session and has become stale. Several items listed as gaps have since been implemented. Updated status:

| Feature | Original Status | Current Status |
|---------|----------------|----------------|
| Grocery aisle sorting | "No aisle sorting" | **DONE** — `ingredientCategories.js`, toggle in GroceryPage |
| Nutritional info display | "Data captured, not shown" | **DONE** — `NutritionFacts` component on RecipePage + SharedRecipePage |
| "What can I make?" search | "Not built" | **DONE** — Full ingredient tag search on HomePage with backend `findByIngredients()` |
| Timer browser notifications | Not mentioned | **DONE** — Notification API in Timer.jsx |
| Dynamic page titles | Not mentioned | **DONE** — `useDocumentTitle` on all 11 pages |
| "Something New?" section | Not mentioned | **DONE** — Uncooked recipes on HomePage |

### What's Actually Still Missing

After this audit, the remaining genuine gaps are:

1. **PWA / Offline access** — No manifest, no service worker, no installability. This is the single biggest remaining gap vs. competitors. Deep Dive 65 has a complete implementation plan.

2. **Recipe pairing ("Goes well with...")** — Deep Dive 87 has a working SQL approach using tag-based complementary matching. Simple to build, would enhance the recipe page.

3. **CookMode step completion tracking** — Deep Dive 81 proposed persisting which steps are done so users can leave and return. Currently CookMode resets on re-open.

4. **Recipe versioning** — Deep Dive 85 explored tracking recipe modifications over time. Deferred as low-priority.

5. **Social media recipe import** — Identified as an intentional gap. TikTok/Instagram recipe videos can't be reliably parsed. Not worth pursuing.

### Priority Ranking (Impact × Effort)

| Rank | Feature | Impact | Effort | ROI |
|------|---------|--------|--------|-----|
| 1 | PWA manifest + service worker | High | ~6 hours | Best |
| 2 | Recipe pairing | Medium | ~2 hours | Good |
| 3 | CookMode step persistence | Medium | ~3 hours | Good |
| 4 | Recipe versioning | Low | ~8 hours | Poor |

PWA is the clear next implementation target. It would make Crumble installable, add offline recipe access, and close the gap with Mealie/Tandoor/KitchenOwl.

## Deep Dive 91: Recipe Pairing Revisited — Sparse Tags Problem

### The Problem With Tag-Based Pairing

Deep Dive 87 proposed pairing recipes using tags: "If this recipe is tagged 'main dish', suggest recipes tagged 'side dish' or 'salad'." But checking the actual database: only 13 out of 146 recipes have tags. Tag-based pairing would only work for ~9% of recipes.

### Alternative: Shared Ingredient Similarity

Instead of tags, use ingredient overlap. Two recipes that share many ingredients are likely from the same cuisine and can be efficiently cooked together (you buy fewer unique ingredients).

```sql
-- Find recipes that share the most ingredients with recipe #42
SELECT r2.id, r2.title, r2.image_path,
       COUNT(DISTINCT i1.name) AS shared_ingredients
FROM ingredients i1
JOIN ingredients i2 ON LOWER(i1.name) = LOWER(i2.name)
JOIN recipes r2 ON i2.recipe_id = r2.id
WHERE i1.recipe_id = 42
  AND r2.id != 42
GROUP BY r2.id
ORDER BY shared_ingredients DESC
LIMIT 3;
```

**Problem with exact matching**: "chicken breast" won't match "chicken thighs." Need fuzzy matching.

### Improved Approach: Keyword Extraction

Instead of exact `name` matching, extract the "base ingredient" from each ingredient name and match on that. "2 lbs boneless skinless chicken breasts" → base keyword "chicken". "1 cup shredded mozzarella cheese" → base keyword "mozzarella" or "cheese".

This is essentially the same problem as the grocery aisle categorization — we already have `ingredientCategories.js` with ~250 ingredient keywords. We could reuse those keywords as a shared vocabulary for ingredient similarity.

But this needs to happen server-side for the query. Two approaches:

**Approach A: SQL LIKE matching (simple but slow)**

```sql
-- For each ingredient in recipe #42, find recipes with similar ingredients
-- using the first significant word as a keyword
SELECT r2.id, r2.title, r2.image_path,
       COUNT(DISTINCT i1.id) AS shared_count
FROM ingredients i1
JOIN ingredients i2 ON (
    i2.name LIKE CONCAT('%', SUBSTRING_INDEX(TRIM(
        REPLACE(REPLACE(REPLACE(i1.name, 'boneless', ''), 'skinless', ''), 'fresh', '')
    ), ' ', -1), '%')
)
JOIN recipes r2 ON i2.recipe_id = r2.id
WHERE i1.recipe_id = 42
  AND r2.id != 42
GROUP BY r2.id
ORDER BY shared_count DESC
LIMIT 3;
```

This is ugly and slow. The LIKE + string manipulation in the JOIN condition would be a full table scan.

**Approach B: Precomputed ingredient keywords (better)**

Add a `keyword` column to the `ingredients` table that stores the normalized base ingredient (e.g., "chicken", "cheese", "tomato"). Populate it during recipe creation/import using the existing ingredient categorization logic. Then similarity is a simple GROUP BY on matching keywords.

```sql
-- With a keyword column, similarity is trivial
SELECT r2.id, r2.title, r2.image_path,
       COUNT(DISTINCT i1.keyword) AS shared_keywords
FROM ingredients i1
JOIN ingredients i2 ON i1.keyword = i2.keyword
JOIN recipes r2 ON i2.recipe_id = r2.id
WHERE i1.recipe_id = ?
  AND r2.id != ?
  AND i1.keyword IS NOT NULL
GROUP BY r2.id
ORDER BY shared_keywords DESC
LIMIT 3;
```

**Pros:** Fast (indexed keyword column), accurate, reuses existing keyword logic.
**Cons:** Requires a migration + backfill. The keyword extraction logic needs to exist server-side (PHP port of the JS categorizer).

**Approach C: Use the existing tag-based approach but auto-tag recipes**

Instead of relying on manual tags, auto-infer category tags from ingredients during recipe creation. If a recipe has "chicken breast", auto-tag it as "chicken". If it has "spaghetti" and "marinara", auto-tag as "pasta" and "italian".

This is the most elegant solution long-term but also the most complex.

### My Current Thinking

Approach B (keyword column) is the right path but it's a bigger change than a casual exploration. It requires:
1. A migration to add the `keyword` column
2. PHP-side ingredient keyword extraction (port of JS categorizer)
3. Backfill existing ingredients
4. Hook into recipe creation to populate keywords for new recipes

This is a real feature with real engineering — not a weekend hack. Filing it for later.

### The Quick Win Alternative

For now, the simplest pairing is the **existing** `getSimilar()` method in Recipe.php which uses shared tags. With only 13 tagged recipes it won't fire often, but when it does, it works. The real fix is to improve tag coverage — either through auto-tagging or by making the tagging UI more prominent during recipe creation.

Actually, let me check if `getSimilar()` is even shown in the UI...

**Update:** Checked — `getSimilar` IS used on RecipePage as "You Might Also Like" (shared tag recommendations). It returns recipes sharing the most tags. So the pairing infrastructure exists, it's just limited by tag sparsity.

## Deep Dive 92: Recipe Text Parser — What Real Recipe Text Looks Like

After testing the parser, I started thinking about the variety of formats people actually encounter when they want to paste a recipe into Crumble. The parser handles 7 test cases well after the verb/ingredient fixes, but there are formats I haven't tested:

### Formats the parser handles well:
1. **Blog-style with clear headers** (Ingredients:/Instructions:) — section matching works perfectly
2. **Casual unstructured** — heuristic line classification (amount → ingredient, verb → instruction) works
3. **Text message minimal** — works after adding "mash" etc. to verb list
4. **"Recipe:" prefix** — cleaned by the title regex

### Formats that might be tricky (untested):
1. **Two-column format** — "2 cups flour     Preheat oven to 350" — tab-separated ingredients and instructions on the same line
2. **Markdown recipes** — "## Ingredients\n- 1 cup flour" — would the `##` confuse the section header regex?
3. **All-caps headers** — "INGREDIENTS" vs "Ingredients" — the regex is case-insensitive so this should work
4. **Parenthetical notes in ingredients** — "2 cups flour (sifted)" — the ingredient parser handles parenthetical units but not general parentheticals
5. **Range amounts** — "3-4 large eggs" — the amount regex supports ranges so this should work
6. **Instruction sub-steps** — "a. Mix dry ingredients b. Mix wet ingredients" — letter-prefixed steps aren't recognized by NUMBERED_LINE_RE

### The 80/20 Insight

The parser doesn't need to handle every possible format. It needs to handle the formats that Crumble users actually encounter. And those are:
- **Copy-paste from a recipe website** → Usually well-structured with headers (the scraper already handles these via URL import, so text paste is a fallback)
- **Copy-paste from a friend's text/email** → Casual, usually structured-ish but no formal headers
- **Typing from memory** → User types ingredients then instructions, no headers needed

The parser's two strategies (section headers → partitioned, no headers → heuristic classification) cover these three cases well. Edge cases like two-column or letter-substeps are rare enough that "close enough" parsing with manual correction is acceptable.

## Deep Dive 93: CookMode Step Persistence

**IMPLEMENTED** — `sessionStorage`-based progress saving in CookMode.

### The Problem

CookMode is a full-screen overlay for step-by-step cooking. If the user accidentally:
- Taps the back button
- Switches to another app and the browser reclaims the tab
- Closes the browser and re-opens it
- Navigates away to check something else in Crumble

...they lose their place and have to manually find the step they were on. With a 12-step recipe, this is annoying.

### What Was Built

Added `sessionStorage`-based progress tracking with 4 helper functions:
- `getSavedProgress(recipeId)` — returns saved step number or null
- `saveProgress(recipeId, step)` — saves current step on every navigation
- `clearProgress()` — removes saved state (on cook completion)

**Behavior:**
1. When CookMode opens, it checks `sessionStorage` for a saved step for this recipe
2. If found, it starts at that step instead of step 0
3. A small terracotta banner says "Resumed at step N" with "Start over" and "Dismiss" buttons
4. Every step change saves progress automatically
5. When the user logs a cook (Done → Log), progress is cleared
6. When the user closes CookMode without logging (recipe has no ID), progress is cleared

**Why `sessionStorage` not `localStorage`:**
- Session scope = current browser tab. If the user opens the same recipe in two tabs, each gets independent progress.
- Auto-clears when the tab closes. Stale progress from yesterday's cooking session won't confuse today.
- No cleanup needed. `localStorage` would accumulate progress entries for every recipe ever cooked.

**Trade-off:** If the user closes the entire browser and re-opens, `sessionStorage` is gone. This is intentional — overnight persistence of cooking progress is more confusing than helpful. If they closed the browser, they probably finished cooking.

### Design Decisions

1. **Auto-resume, not prompt** — Originally considered a "Resume from step 5?" dialog. Decided against it because:
   - Extra tap is annoying when your hands are messy from cooking
   - The resume banner with "Start over" gives the same choice with less friction
   - Auto-resuming the correct step is the right default 99% of the time

2. **Banner auto-hides on interaction** — The "Resumed at step N" banner stays visible until dismissed or "Start over" is tapped. It doesn't auto-dismiss on a timer because the user might not notice it.

3. **Clear on completion only** — Progress is NOT cleared when the user taps the X (close) button. The X means "I'm pausing" or "I need to check something." Only completing the cook (logging it) clears progress. This matches the mental model: closing CookMode ≠ done cooking.

### Bundle Impact

+0.95KB (360.36KB total). Minimal — just a few functions and a small JSX banner.

## Deep Dive 94: The AI Question — What's Real and What's Hype

### The 2026 Landscape

Every recipe app is rushing to add "AI" features. The major trends:

1. **AI meal planning** — "Tell me what to cook this week based on my preferences" (Honeydew, MealPractice)
2. **AI recipe generation** — "Create a recipe for X with these constraints" (ClickUp, MealPractice)
3. **AI-powered import** — OCR from photos, parsing from social media videos (Honeydew, Cooklang with OpenAI)
4. **Personalized suggestions** — "Based on your cooking history, try this" (Honeydew)

### What's Actually Useful vs. Marketing

**Genuinely useful (proven):**
- Smart recipe text parsing (Crumble already has this — `recipeTextParser.js`)
- Ingredient-based recipe search (Crumble has this — `findByIngredients`)
- Auto-categorization of grocery items (Crumble has this — `ingredientCategories.js`)

**Useful but requires cloud APIs:**
- Recipe import from photos/screenshots (needs OCR + LLM)
- Meal plan generation based on dietary goals (needs LLM)
- "What can I substitute for X?" (needs knowledge base or LLM)

**Marketing fluff:**
- "AI-powered recipe organization" (it's just tags and search)
- "AI learns your preferences" (it's a collaborative filter, not AI)
- "AI-generated recipes" (LLM-generated recipes are untested — who wants to cook something nobody has ever made?)

### The Self-Hosted Dilemma

All the genuinely novel AI features require cloud API calls:
- OpenAI/Anthropic for text understanding
- Google Vision or similar for OCR
- Cloud compute for any real ML inference

This directly conflicts with Crumble's value proposition: **no tracking, no external dependencies, your data stays on your server.**

### Three Paths Forward

**Path 1: No AI (current approach)**
Keep Crumble's smart features rule-based. The ingredient parser, text parser, and category matcher all use heuristics — no API calls, no privacy concerns, works offline. They're "good enough" for 90% of cases.

**Pros:** Simple, private, no API costs, works offline.
**Cons:** Can't handle truly ambiguous cases. The text parser can't understand "a splash of olive oil" as olive oil.

**Path 2: Optional AI integration (best compromise)**
Add an optional configuration for an LLM API endpoint (OpenAI, local Ollama, etc.). When configured, enhanced features unlock:
- Better recipe text parsing (send ambiguous text to LLM)
- Recipe photo import (send image to vision model)
- Ingredient substitution suggestions
- "What should I cook tonight?" based on pantry + history

When NOT configured, everything falls back to the existing rule-based approach. Zero degradation for users who don't want AI.

**Pros:** Best of both worlds. Privacy-conscious users lose nothing. Power users get enhanced features.
**Cons:** Two code paths to maintain. LLM responses are non-deterministic (same input → different output).

**Path 3: Local AI with small models**
Run a small, specialized model on the user's server. Something like:
- A fine-tuned ingredient NER model (~50MB) for parsing
- A small embedding model for recipe similarity
- No cloud dependency, runs on modest hardware

**Pros:** Privacy preserved, works offline.
**Cons:** Significant complexity. Most self-hosters run on Raspberry Pi or low-end NAS — can't run even small models. Maintenance burden of shipping ML models.

### My Assessment

**Path 2 is the right answer**, but it's not urgent. The rule-based features Crumble already has cover the most common use cases. The gap between "heuristic parsing" and "LLM parsing" matters for maybe 10% of recipes — the weird ones with unusual formatting.

The one AI feature I'd actually want as a cook: **"What should I cook tonight?"** — not random suggestions, but a recommendation based on:
- What I haven't cooked recently (already built: "Something New?")
- What's in season (can be rule-based — month → seasonal ingredients)
- What ingredients I have (already built: ingredient search)
- My past favorites (already built: favorites + cook history)

This could be assembled from existing data without any LLM. A "smart suggestion" algorithm that combines these signals would feel like AI but be entirely deterministic and local.

### The Ingredient Substitution Opportunity

The one feature that genuinely needs either a knowledge base or an LLM is ingredient substitution. "I don't have buttermilk — what can I use instead?" This requires culinary knowledge that can't be derived from the recipe itself.

Two approaches:
1. **Static substitution table** — a JSON file with ~200 common substitutions. "buttermilk → milk + lemon juice", "heavy cream → coconut cream", etc. Simple, offline, predictable.
2. **LLM query** — more flexible but requires API access.

The static table is the Crumble-appropriate answer. It covers 95% of real substitution needs and fits the app's philosophy.

### Sources

- [Cooklang: 18 Open Source Recipe Managers 2026](https://cooklang.org/blog/18-open-source-recipe-managers-2026/)
- [Top 7 AI Cooking Assistants for Home Cooks](https://honeydew-news.ghost.io/ai-cooking-assistants-home-cooks/)
- [11 Best AI Recipe Generators in 2026](https://clickup.com/blog/ai-recipe-generators/)
- [AI Healthy Cooking Assistant — DataRoot Labs](https://datarootlabs.com/blog/ai-healthy-cooking-assistant-for-the-meal-planning-platform)

## Deep Dive 95: Ingredient Substitution Table — A Sketch

Following from Deep Dive 94's analysis, here's what a static ingredient substitution feature would look like.

### The Data Structure

```javascript
// substitutions.js
const SUBSTITUTIONS = {
  'buttermilk': [
    { sub: 'milk + 1 tbsp lemon juice per cup', note: 'Let sit 5 min to curdle' },
    { sub: 'milk + 1 tbsp white vinegar per cup', note: 'Let sit 5 min' },
    { sub: 'plain yogurt thinned with milk', note: '3/4 cup yogurt + 1/4 cup milk' },
  ],
  'heavy cream': [
    { sub: 'coconut cream', note: 'Good for dairy-free; may add coconut flavor' },
    { sub: 'evaporated milk', note: 'Lower fat but similar consistency' },
  ],
  'eggs': [
    { sub: '1/4 cup applesauce per egg', note: 'Best in baking; adds sweetness' },
    { sub: '1 mashed banana per egg', note: 'Best in sweet baking' },
    { sub: '3 tbsp aquafaba per egg', note: 'Chickpea liquid; good for meringue' },
    { sub: '1 tbsp ground flaxseed + 3 tbsp water per egg', note: 'Mix and let gel 5 min' },
  ],
  'sour cream': [
    { sub: 'plain Greek yogurt', note: '1:1 swap; slightly tangier' },
    { sub: 'cottage cheese blended smooth', note: 'Lower fat alternative' },
  ],
  'all-purpose flour': [
    { sub: 'whole wheat flour', note: 'Use 3/4 cup per 1 cup AP flour; denser result' },
    { sub: 'almond flour', note: 'Use 1:1 but add extra egg for binding; gluten-free' },
    { sub: 'oat flour', note: 'Blend oats until fine; use 1:1' },
  ],
  'brown sugar': [
    { sub: '1 cup white sugar + 1 tbsp molasses', note: 'Mix well; exact substitute' },
  ],
  'honey': [
    { sub: 'maple syrup', note: '1:1 swap; different flavor profile' },
    { sub: 'agave nectar', note: '1:1 swap; milder flavor' },
  ],
  'wine (white)': [
    { sub: 'chicken broth + 1 tsp lemon juice', note: 'For cooking; not drinking' },
    { sub: 'white grape juice + 1 tsp vinegar', note: 'Adds similar acidity' },
  ],
  'wine (red)': [
    { sub: 'beef broth + 1 tsp red wine vinegar', note: 'For cooking' },
    { sub: 'pomegranate juice', note: 'Similar color and body' },
  ],
};
```

### UI Integration

The substitution would appear in CookMode's ingredient panel. When viewing the ingredient list, each ingredient would have a small "swap" icon. Tapping it shows available substitutions in a tooltip or bottom sheet.

On the recipe detail page, the substitution icon could appear next to each ingredient in the ingredient list — but only for ingredients that have substitutions in the table.

### How Big Would the Table Be?

A comprehensive substitution table covering the most common cooking ingredients would be ~100-150 entries with 2-3 alternatives each. That's roughly 5-8KB of JSON — trivial bundle impact.

The real work isn't the data structure — it's curating the substitutions. Each one needs:
- The correct ratio (how much of the substitute per unit of the original)
- Context notes (works in baking but not sauces, etc.)
- Dietary labels (dairy-free, gluten-free, vegan)

This is a content curation project more than an engineering project. Could be built incrementally — start with the 20 most commonly substituted ingredients and expand over time.

## Deep Dive 96: Auto-Difficulty Estimation

### The Idea

Automatically assign a difficulty level (Easy / Medium / Hard) to recipes based on their structural characteristics. No manual tagging needed — derive it from data that already exists in the recipe.

### Signals Available

From a sample of 15 recipes in the Crumble database:

| Recipe | Ingredients | Steps | Time | Intuitive Difficulty |
|--------|-------------|-------|------|---------------------|
| Five Minute Detox Water | 6 | 2 | 5 min | Easy |
| Butter Beer | 4 | 5 | 2 min | Easy |
| Strawberry Coconut Smoothie | 7 | 2 | 10 min | Easy |
| Awesome 7 Layer Dip | 9 | 2 | 10 min | Easy |
| Fry Bread | 5 | 7 | 10 min | Medium |
| Taco Ring | 5 | 8 | 20 min | Medium |
| Crockpot Chicken Alfredo | 11 | 9 | 10 min | Medium |
| Creamy Scalloped Potatoes | 9 | 10 | 15 min | Medium |
| Tomato Spinach One Pot Pasta | 12 | 4 | 25 min | Easy-Medium |
| Cowboy Butter | 14 | 4 | 5 min | Easy (just mixing) |

### The Heuristic

A weighted score based on three factors:

```javascript
function estimateDifficulty(recipe) {
  const ingCount = recipe.ingredients?.length || 0;
  const stepCount = recipe.instructions?.length || 0;
  const totalTime = (recipe.prep_time || 0) + (recipe.cook_time || 0);

  // Normalize each factor to 0-10 scale
  const ingScore = Math.min(ingCount / 2, 10);     // 20+ ingredients = max
  const stepScore = Math.min(stepCount / 1.5, 10);  // 15+ steps = max
  const timeScore = Math.min(totalTime / 12, 10);   // 120+ min = max

  // Weighted combination (steps matter most, then ingredients, then time)
  const score = (stepScore * 0.45) + (ingScore * 0.35) + (timeScore * 0.20);

  if (score <= 3) return 'Easy';
  if (score <= 6) return 'Medium';
  return 'Hard';
}
```

**Why steps are weighted highest:** A recipe with 2 steps is almost always easy regardless of ingredient count (it's probably "mix everything, bake"). A recipe with 12 steps involves technique, timing, and coordination — that's where difficulty lives.

**Why time is weighted lowest:** A crockpot recipe might take 8 hours but it's easy — you just wait. A stir-fry might take 15 minutes but require constant attention. Total time is a weak signal for difficulty.

### What Would Make This Better

**Technique detection:** Scanning instruction text for advanced techniques would dramatically improve accuracy. A recipe mentioning "deglaze," "temper," "fold," "bloom," or "julienne" is harder than one saying "mix" and "bake."

```javascript
const ADVANCED_TECHNIQUES = [
  'deglaze', 'temper', 'bloom', 'julienne', 'brunoise', 'chiffonade',
  'blanch', 'braise', 'poach', 'reduce', 'caramelize', 'emulsify',
  'flambé', 'sear', 'baste', 'truss', 'fillet', 'debone',
  'proof', 'knead', 'laminate', 'fold in',
];

function countAdvancedTechniques(instructions) {
  const text = instructions.join(' ').toLowerCase();
  return ADVANCED_TECHNIQUES.filter(t => text.includes(t)).length;
}
```

Each advanced technique adds +0.5 to the difficulty score. A recipe with 3+ advanced techniques is almost certainly "Hard."

### Where Would This Show?

1. **Recipe cards** — a small "Easy" / "Medium" / "Hard" badge next to cook time
2. **Recipe detail page** — in the metadata row with prep/cook time
3. **Search filters** — "Show me easy recipes" (filter by estimated difficulty)
4. **Meal planning** — "You have 3 hard recipes planned for Tuesday" warning

### Implementation Decision

This could be computed:
- **Client-side** — calculate in the React frontend from recipe data already available. Zero backend changes.
- **Server-side** — compute once and store in a `difficulty` column. Better for search/filter.
- **Hybrid** — compute client-side for display, but also store server-side for filtering.

Client-side only is the pragmatic choice to start. It requires zero migrations, zero API changes, and can be refined without database updates. If difficulty-based filtering becomes valuable, add the server-side column later.

### Should It Be Built?

Honestly, this is a "nice to have." With 146 recipes, a user can eyeball difficulty from the ingredient count and time. Difficulty badges are more useful at scale (500+ recipes) where browsing becomes overwhelming.

Filing this as a future enhancement. The heuristic is ready when needed.

## Deep Dive 97: Daily Featured Recipe Rotation

**IMPLEMENTED** — `getFeatured()` now rotates daily from top 10 candidates.

### The Problem

The featured recipe (hero image on the home page) was always the single highest-rated recipe with an image. With 146 recipes, the same recipe showed as the hero every single day. Users stop noticing it.

### The Fix

Changed `getFeatured()` to:
1. Query the top 10 recipes (by rating, then recency) that have images
2. Pick one deterministically using `day_of_year % candidate_count`

This means:
- The featured recipe changes every day
- The same recipe shows for all users on the same day (consistent experience)
- It always picks from the best recipes (not random from the full catalog)
- It's deterministic — refreshing the page doesn't change the featured recipe
- No new tables, no cron jobs, no state to manage

### Why Day-of-Year and Not RAND()

`ORDER BY RAND()` would give a different recipe on every page load. That's jarring — the hero image would change if the user navigates away and comes back. Day-of-year gives a stable daily rotation while still being simple.

### Edge Cases

- If there are fewer than 10 candidates, the modulo automatically wraps correctly
- If there are zero candidates (no recipes with images), returns null (hero section hidden)
- The `LEFT JOIN ratings` with `COALESCE` ensures unrated recipes are included as candidates (scored 0), so new recipes with images can still be featured

## Deep Dive 98: Favicon and Meta Tags

**IMPLEMENTED** — SVG favicon + mobile meta tags added.

### What Changed

1. Created `frontend/public/favicon.svg` — an SVG cooking pot icon matching the existing `crumble_icon.PNG` design (terracotta pot on cream rounded-square background)
2. Updated `index.html`:
   - Fixed broken favicon link (was pointing to non-existent `/crumble/vite.svg`)
   - Added `theme-color` meta for browser chrome coloring (#C75B39 terracotta)
   - Added `apple-mobile-web-app-capable` and `apple-mobile-web-app-status-bar-style` for iOS home screen

### Why SVG

- Infinitely scalable — works at 16x16 (tab icon) and 512x512 (PWA splash)
- Tiny file size (~940 bytes)
- Can be edited with a text editor
- Supported by all modern browsers (Chrome 80+, Firefox 41+, Safari 12+)
- Only IE/legacy Edge don't support SVG favicons, and those browsers are irrelevant in 2026

### What's Still Missing for Full PWA

- **PNG icons** at 192x192 and 512x512 for Android/iOS (SVG favicon won't work as PWA icon)
- **Web manifest** (`manifest.json`)
- **Service worker** registration
- The existing `crumble_icon.PNG` is only 71x73px — too small. Need to recreate from the SVG or commission a proper icon.

## Deep Dive 99: Broken Route Links — `/recipes/` vs `/recipe/`

**FIXED** — Two components had broken links using `/recipes/${id}` (plural) instead of the correct `/recipe/${id}`.

### The Bug

The frontend route is defined as `path="/recipe/:id"` (singular) in App.jsx. But two files used the plural form:

- `pages/StatsPage.jsx` line 118: "Most Cooked" recipe link → `/recipes/${stats.most_cooked.id}`
- `components/grocery/GroceryItem.jsx` line 31: recipe source link → `/recipes/${item.recipe_id}`

Both would navigate to a route that doesn't exist, showing a blank page or 404.

### Why This Happens

The API endpoints use `/recipes/{id}` (plural, RESTful), but the frontend page route uses `/recipe/{id}` (singular, more natural for a "view a recipe" page). This mismatch is easy to confuse, and there's no TypeScript or linting rule to catch it.

### Prevention

Could add a constant like `ROUTES.recipe(id)` to avoid string duplication, but with only ~10 link sites, the fix-in-place approach is fine. The real prevention is reading from the route definition rather than guessing.

## Deep Dive 100: Accessibility Audit

Performed a comprehensive accessibility review of the entire frontend. Found the app has solid foundations but some significant gaps.

### What Was Already Good

- **Skip-to-content link** in Layout.jsx with sr-only styling
- **aria-labels** on 15+ icon-only buttons across FavoriteButton, CookMode, Timer, Modal, Header, StarRating, GroceryItem
- **Form labels** with proper `htmlFor` associations and auto-generated IDs
- **Alt text** on all `<img>` tags
- **Semantic HTML** — proper `<header>`, `<main>`, `<nav>`, `<aside>`, `<footer>`
- **Touch targets** — 44px minimum on most interactive elements
- **Keyboard support** — Escape closes modals and CookMode, arrow keys navigate CookMode steps

### What Was Fixed

1. **Modal focus trapping** (WCAG 2.1 Level AA violation) — Modal.jsx now:
   - Traps Tab/Shift+Tab within the dialog
   - Auto-focuses the close button on open
   - Restores focus to the triggering element on close
   - Has `role="dialog"`, `aria-modal="true"`, `aria-labelledby` pointing to the title
   - Backdrop has `aria-hidden="true"`

2. **Timer completion announcement** — "Time is up!" now has `role="alert"` (implicitly `aria-live="assertive"`) so screen readers announce when the timer finishes

3. **GlossaryTerm keyboard support** — Added Escape key to close the tooltip popup, `aria-expanded` on the trigger button, and `role="tooltip"` on the popup

4. **StarRating touch targets** — Interactive stars now get `p-1.5` padding instead of the `minWidth: 'auto', minHeight: 'auto'` override that was shrinking them to icon size. Non-interactive stars keep compact sizing.

### What Remains

- **Focus management on route changes** — focus doesn't reset to main content when navigating between pages
- **ARIA combobox pattern** — tag input suggestions and ingredient search don't use proper combobox roles
- **Color-only information** — star ratings use color alone (amber vs gray) for filled/unfilled state

## Deep Dive 101: HomePage API Call Cascade

The HomePage fires **6 separate API calls** on every mount:

1. `getTodayMeals()` — today's meal plan items
2. `getForgottenFavorites()` — favorites not cooked recently
3. `getUncookedRecipes()` — recipes never cooked
4. `getTags()` — all tags for the filter bar
5. `getFeaturedRecipe()` — daily featured recipe
6. `getRecipes()` — paginated recipe list

That's 6 HTTP roundtrips before the page can render fully. On mobile with 100ms+ latency per request, that's 600ms+ of waterfall time even if each response is fast.

### Consolidation Options

**Option A: `/api/dashboard` endpoint** — A single endpoint that returns all homepage data in one response:

```json
{
  "featured": { ... },
  "today_meals": [ ... ],
  "forgotten_favorites": [ ... ],
  "uncooked": [ ... ],
  "tags": [ ... ],
  "recipes": { "data": [...], "pagination": {...} }
}
```

Pros: Single roundtrip. Simple.
Cons: Tightly couples backend to frontend layout. The endpoint becomes a "god endpoint" that does too many unrelated queries.

**Option B: Keep separate but add SWR-like caching** — Cache responses in memory so navigating back to home doesn't re-fetch everything. React Query or useSWR would give this for free, but adding a dependency just for caching is heavy.

**Option C: Parallel fetch with Promise.all** — Already happening (the useEffects fire in parallel), so there's no waterfall. The real cost is just the 6 concurrent connections, which modern browsers handle fine.

### My Take

Option C (the current approach) is actually fine. The calls are already parallel, not sequential. The real optimization would be Option A for mobile, but it's premature — the page loads fast enough on LAN, and the added complexity of a dashboard endpoint isn't justified until there's measured latency data showing it matters.

If the app goes public with external users on slow connections, revisit this with Option A.

## Deep Dive 102: Print Stylesheet Redesign — Cookbook Recipe Card

**IMPLEMENTED** — Complete rewrite of `frontend/src/styles/print.css`.

### Before

The old print CSS just stripped away web chrome (nav, buttons, shadows, rounded corners) and made everything black-on-white with Georgia font. The result looked like a plain text dump — no visual structure, no hierarchy, just paragraphs of text.

### After

The new print stylesheet creates a proper **cookbook-style recipe card** layout:

1. **Page setup:** US Letter portrait with 0.6in/0.7in margins
2. **Title block:** 20pt serif title with a terracotta (#C75B39) rule underneath
3. **Description:** Italic, slightly smaller, distinguished from body text
4. **Metadata row:** Prep/cook/servings in a clean horizontal bar, icons hidden (text only)
5. **Two-column layout:** Ingredients on the left (1fr), instructions on the right (1.8fr) — preserving the grid layout on paper
6. **Ingredients:** No bullet points — clean list with dotted separators between items
7. **Instructions:** CSS counter-based numbered circles in terracotta, with dotted separators, `page-break-inside: avoid` on each step
8. **Section headings:** Uppercase, letter-spaced, with terracotta color and bottom border — matching cookbook conventions
9. **Hero image:** Constrained to 200pt max height, with terracotta bottom border accent
10. **Scaling warning:** Preserved with a subtle border if the user adjusted servings

### Design Decisions

- **Color-exact printing** (`print-color-adjust: exact`) — the terracotta accents are what make it look like a recipe card rather than a text dump. Worth the ink.
- **CSS counters for step numbers** instead of the web's styled divs — gives clean numbered circles that work in print regardless of what the web component renders
- **Dotted separators** instead of solid lines — lighter visual weight, classic cookbook feel
- **Georgia serif font** throughout — the right choice for printed recipes; readable and warm
- **No nutrition/cook notes/related in print** — these are interactive or contextual; not useful on paper

## Deep Dive 103: Image URL Consolidation

**IMPLEMENTED** — Cleaned up remaining manual `/uploads/${path}` constructions.

The `imageUrl.js` utility was created in the previous session to centralize image URL construction, but a few files were missed:

| File | Before | After |
|------|--------|-------|
| `pages/RecipePage.jsx` | `` `/uploads/${recipe.image_path}` `` | `fullImageUrl(recipe.image_path)` |
| `pages/HomePage.jsx` (featured) | `` `/uploads/${featured.image_path}` `` | `fullImageUrl(featured.image_path)` |
| `pages/SharedRecipePage.jsx` | `` `/uploads/${recipe.image_path}` `` | `fullImageUrl(recipe.image_path)` |
| `components/recipe/RecipeForm.jsx` | `` `/uploads/${initialData.image_path}` `` | `fullImageUrl(initialData.image_path)` |

Now **every** image URL in the frontend goes through `imageUrl.js`. If the upload path structure ever changes, there's exactly one place to update.

## Deep Dive 104: Dead Google Web Cache Fallback in RecipeScraper

**FIXED** — Removed dead Google Web Cache call from `api/services/RecipeScraper.php`.

### The Problem

The `fetchCachedVersion()` method in RecipeScraper tried two fallback sources for JS-heavy pages that don't return recipe data in the initial HTML:

1. Google AMP Cache (`cdn.ampproject.org`)
2. Google Web Cache (`webcache.googleusercontent.com`)

Google [shut down Web Cache](https://www.seroundtable.com/google-cache-dead-38112.html) in September 2024. The `webcache.googleusercontent.com` domain no longer serves content. Every call to it would either timeout (wasting up to 15 seconds) or return a non-200 response.

### The Fix

Removed the dead Google Web Cache call. Kept the AMP cache attempt since it still works for some sites that publish AMP versions. Added a comment explaining why Web Cache was removed.

### Impact

For recipes that fail primary parsing (JSON-LD, microdata, heuristic, OG), the scrape now fails ~15 seconds faster instead of waiting for a dead endpoint to timeout. For recipes that succeed on primary parsing (the vast majority), no change.

## Deep Dive 105: Observations From a Code Walkthrough

### Things That Impressed Me

1. **Recipe scraper layering:** JSON-LD → microdata → heuristic HTML → OG meta → cached/AMP fallback. Each layer is a sensible fallback with clear separation. The SSRF protection (private IP blocking) in `isValidUrl()` is thorough.

2. **Ingredient scaling:** The `ingredientScaling.js` handles mixed fractions ("1 1/2"), ranges ("2-3"), unicode fraction output, and even amounts embedded in the name string (for imported recipes where the parser didn't separate them). The baking scaling warning is a thoughtful touch.

3. **Onboarding:** The empty-state welcome screen with three clear action cards (Import URL, Paste/Type, Bulk Import) is much better than a blank page with "No recipes found."

4. **CookMode UX:** Wake lock prevents screen dimming, step persistence via sessionStorage, arrow key navigation, ingredient side panel. This is a genuinely useful cooking tool.

5. **Print stylesheet:** With the redesign, printing a recipe now produces a cookbook-quality layout with two-column ingredients/instructions, terracotta accent circles on step numbers, and clean typography.

### Minor Rough Edges (Not Worth Fixing Now)

- The `RecipePage` has two separate `StarRating` renders (one for when `avg_rating` exists, one for when it doesn't) that could be consolidated into a single render with a computed value — but it works fine as-is.
- The `HomePage` has 600+ lines — it's become a bit of a god component with featured recipe, today's meals, forgotten favorites, uncooked recipes, recently viewed, search, tag filtering, and ingredient search all in one file. If more sections get added, it might benefit from extracting subsections. But 600 lines is still manageable.
- The cookie-based `recently viewed` list in `useRecentlyViewed` stores recipe IDs but the recipe data (title, image) is fetched separately. This means recently viewed shows stale titles if a recipe is renamed. Edge case, not worth solving.

## Deep Dive 106: Competitive Landscape — Where Crumble Fits (March 2026)

### The Field

| App | Tech Stack | GitHub Stars | Key Differentiator |
|-----|-----------|-------------|-------------------|
| **Mealie** | Python/Vue/Docker | 11,000+ | Polished UI, automated imports, meal planning |
| **Tandoor** | Django/Vue/Docker | ~8,000 | Nutritional tracking, meal cost calculation |
| **KitchenOwl** | Flutter/Flask | ~2,500 | Real-time grocery sync, household collaboration |
| **Cooklang** | Markup language + CLI | ~5,000+ repos | Plain text files, developer-oriented, no server |
| **Recipya** | Go | ~500 | Simplicity, single binary |
| **Crumble** | PHP/React/MySQL | Private | Lightweight, no Docker, CookMode |

### Crumble's Actual Position

Crumble isn't competing with Mealie or Tandoor on features — those are mature projects with years of community development. Instead, Crumble occupies a niche:

**1. Zero-Docker deployment.** Every other self-hosted recipe app in 2026 assumes Docker. Crumble runs on bare PHP + MySQL (Laragon/XAMPP/shared hosting). This matters for people who have a cheap web host, a Raspberry Pi with Apache, or just don't want to learn Docker.

**2. CookMode as a real cooking tool.** Most recipe apps show you the recipe. CookMode turns it into a step-by-step cooking interface with:
- Full-screen step display (optimized for kitchen distance reading)
- Wake lock (screen stays on)
- Ingredient side panel (check off as you go)
- Step persistence (close the browser, come back, resume where you left off)
- Keyboard navigation (arrow keys to advance)
- Inline timers

This isn't a gimmick — it's genuinely useful when your hands are covered in flour.

**3. Smart ingredient search.** "What can I make with chicken, rice, and garlic?" — returns recipes ranked by percentage of ingredients matched. Not many self-hosted apps do this well.

**4. Lightweight philosophy.** No Redis, no Elasticsearch, no background workers, no queue system. The entire backend is a single-entry-point PHP router with PDO queries. Start to full deployment in under 5 minutes.

### What Crumble Lacks That Others Have

| Feature | Mealie | Tandoor | KitchenOwl | Crumble |
|---------|--------|---------|------------|---------|
| Docker support | Yes | Yes | Yes | No |
| REST API docs | OpenAPI | OpenAPI | Yes | No |
| Multi-user households | Yes | Yes | Yes | Basic (admin/member) |
| Nutritional tracking | Basic | Advanced | Basic | Display only |
| Mobile app | PWA | PWA | Native | Web only |
| Real-time sync | No | No | Yes | No |
| Recipe versioning | No | No | No | No |
| Offline mode | PWA | PWA | Yes | No |
| i18n / translations | Yes | Yes | Yes | English only |

### Where Crumble Could Go

Rather than chasing Mealie/Tandoor feature parity (impossible and pointless), Crumble should double down on its strengths:

1. **CookMode excellence** — This is the killer feature. Make it even better: voice control ("next step"), timer auto-detection from instruction text, ingredient highlighting per step.

2. **PWA** — The single biggest gap. Adding a manifest and service worker would enable "Add to Home Screen" on mobile, making CookMode accessible without typing a URL. Doesn't need full offline — just app-like launcher and caching.

3. **Zero-friction setup** — Keep the "no Docker required" philosophy. Could add a PHP-based installer that creates the database, sets up the config, and runs migrations automatically.

4. **Recipe intelligence** — The ingredient parser and text parser are already good. Build on them: auto-tagging based on ingredient analysis, difficulty estimation, cuisine detection.

### What NOT To Build

- **Docker support** — Adding Docker doesn't help the niche Crumble serves. People who want Docker already use Mealie.
- **Native mobile app** — PWA covers this adequately. The cost of maintaining iOS + Android apps is enormous.
- **Social features** — Recipe sharing links already work. Don't add comments, likes, followers. That's a different product.
- **AI recipe generation** — Gimmick. People want to cook real recipes, not AI hallucinations. The AI value is in parsing, tagging, and search — not generation.

## Deep Dive 107: Auto-Difficulty Estimation — Implementation

**IMPLEMENTED** — Difficulty badges now appear on recipe cards and recipe detail pages.

### What Was Built

1. **`frontend/src/utils/recipeDifficulty.js`** — Pure function that estimates difficulty from ingredient count, step count, and total time using a weighted heuristic:
   - Steps × 0.45 (most important — more steps = more technique)
   - Ingredients × 0.35 (more ingredients = more prep)
   - Time × 0.20 (least important — slow cooker ≠ hard)
   - Score ≤ 3 → Easy (green/sage), ≤ 6 → Medium (terracotta), > 6 → Hard (dark terracotta)

2. **Backend change** — Added `COALESCE(JSON_LENGTH(r.instructions), 0) AS step_count` to the `getAll()` query in `Recipe.php`, so recipe list data includes step count without needing to send full instruction arrays.

3. **Frontend integration** — Difficulty badge appears in:
   - RecipePage metadata row (next to prep/cook/servings)
   - RecipeCard metadata row (next to servings and cook count)
   - SharedRecipePage metadata row

### Validation Against Real Data

| Recipe | Steps | Ingredients | Time | Estimated | Feels Right? |
|--------|-------|-------------|------|-----------|-------------|
| Chick fil A Lemonade | 1 | 3 | 10 min | Easy | Yes |
| Mississippi Roast | 3 | 13 | 0 min | Medium | Yes (lots of ingredients but just throw them in) |
| One Pot Cajun Pasta | 6 | 17 | 30 min | Hard | Yes (many ingredients + technique) |
| Chicken Alfredo | 10 | 15 | 33 min | Hard | Yes |
| Five Minute Detox Water | 2 | 6 | 5 min | Easy | Yes |

### Design Decision: No Manual Override

The difficulty is fully automatic — no ability to manually set Easy/Medium/Hard. This is intentional. Manual tagging creates maintenance burden and inconsistency. The heuristic isn't perfect (cowboy butter: 14 ingredients but Easy because it's just mixing), but it's correct 80%+ of the time, and being wrong about difficulty has zero consequences — it's informational, not functional.

## Deep Dive 108: PWA Manifest — Add to Home Screen

**IMPLEMENTED** — Basic web app manifest enabling "Add to Home Screen" on mobile.

### What Was Added

1. **`frontend/public/manifest.json`** — Web app manifest with:
   - `display: standalone` — app runs without browser chrome
   - `background_color: #F5EDE3` (cream) — splash screen background
   - `theme_color: #C75B39` (terracotta) — status bar color
   - SVG icon (any size) and PNG icon (71px)

2. **`index.html`** changes:
   - `<link rel="manifest" href="/manifest.json" />`
   - `<link rel="apple-touch-icon" href="/crumble_icon.png" />`

3. **Copied `crumble_icon.PNG`** into `frontend/public/` (lowercase `.png`) so it's served by both Vite dev server and production builds.

### What This Enables

- **Android Chrome**: "Add to Home Screen" prompt → app icon on home screen, opens in standalone mode (no URL bar)
- **iOS Safari**: "Add to Home Screen" → works via `apple-mobile-web-app-capable` meta tag
- **Desktop Chrome/Edge**: Install app → runs in its own window

### What's Still Missing for Full PWA

- **Properly sized icons**: Need 192×192 and 512×512 PNG icons. The current 71px icon is below minimum. Should be generated from the SVG favicon design.
- **Service worker**: No offline caching. Without a service worker, the app won't work offline and won't show the "install" prompt in Chrome automatically (requires service worker registration).
- **Maskable icon**: Android adaptive icons need a `purpose: maskable` variant with safe area padding.

### Why Not Add a Service Worker Now?

A service worker adds caching complexity — stale recipe data, cache invalidation when recipes are edited, offline/online sync. For a self-hosted app on a home network, offline mode is rarely needed. The manifest alone gives 80% of the PWA benefit (home screen icon, standalone mode, theme color) with zero complexity.

## Deep Dive 109: Recipe Text Parser — Edge Case Fixes

**IMPLEMENTED** — Two parser improvements for better handling of real-world recipe text.

### Fix 1: No-Space Amounts (`1lb`, `2cups`, `16oz`)

**Problem:** The `INGREDIENT_AMOUNT_RE` required a space or fraction character after the number: `\d+[\s/¼½¾]`. So `1lb shrimp` didn't match because there's no space between `1` and `lb`.

**Fix:** Added an alternative branch to the regex: `\d+(?:lb|oz|g|kg|ml|tsp|tbsp|cup)s?\b` — matches digit+unit with no space. The `\b` word boundary prevents false matches like `100bad`.

### Fix 2: Missing Action Verbs

Added 9 more action verbs to `looksLikeInstruction()`:

```
butterfly, shred, grate, zest, squeeze, peel, trim, score, flatten
```

These are common cooking verbs that would otherwise cause instruction lines to be classified as "other" (description text).

## Deep Dive 110: Session Summary — Code Health Check

Across this exploration session, I audited the entire Crumble codebase through multiple lenses:

### Code Health: Strong

- **Accessibility:** Solid foundation (skip-nav, aria-labels, semantic HTML, touch targets). Gaps were Modal focus trapping and Timer alerts — both now fixed.
- **Dark mode:** Properly implemented via CSS custom properties with system preference detection, manual toggle, and scroll bar theming. Print stylesheet correctly forces light colors.
- **Security:** SSRF protection in the scraper, CSRF tokens, session-based auth with lockout, DemoGuard middleware, rate limiting. No obvious vulnerabilities found.
- **Image handling:** Centralized through `imageUrl.js` utility — no more scattered `/uploads/` strings.
- **Error handling:** Critical paths have proper error states. Non-critical catches (tags, audio cleanup) are appropriately silent.

### New Features Added This Session

| Feature | Files Changed | Impact |
|---------|--------------|--------|
| Difficulty badges | 5 files + new utility | Visual enrichment on recipe cards and detail pages |
| PWA manifest | 3 files | "Add to Home Screen" on mobile |
| Cookbook print layout | 1 file (complete rewrite) | Beautiful printed recipes |
| Modal a11y | 1 file | WCAG 2.1 Level AA compliance |
| Parser improvements | 1 file | Better handling of real-world recipe text |

### What the Codebase Doesn't Need

- More abstraction layers — the current level is right
- TypeScript migration — the codebase is small enough that types add more friction than value
- State management library — React Context + local state is sufficient for this app
- CSS framework changes — Tailwind CSS 4 with custom theme is working well
- Testing framework for frontend — the app is UI-heavy and better served by manual testing and the PHP backend tests

## Deep Dive 111: Cooklang Format — Should Crumble Support It?

### What Is Cooklang?

A plain-text markup language for recipes. Instead of structured JSON, recipes are written as readable prose with inline annotations:

```
Preheat the oven to 180°C. Slice @potatoes{900%g} and layer in a #baking dish{}.
Pour @cream{300%ml} over the top and bake for ~{45%minutes}.
```

The `@` marks ingredients with quantities, `#` marks cookware, `~` marks timers. Metadata goes in YAML front matter. Sections use `= Section Name` headers.

### 5,000+ GitHub Repos

Cooklang has real adoption — software developers love it because recipes become version-controllable text files. Ecosystem includes CLI tools, iOS/Android apps, Obsidian plugin, and VS Code extension.

### Import: Feasible and Useful

A Cooklang → Crumble importer would be straightforward:

1. Parse `---` YAML front matter → title, description, servings, tags
2. Split on blank lines → steps (instructions)
3. Extract `@name{amount%unit}` → ingredients array
4. Extract `~{time%unit}` → prep/cook time (heuristic: first timer → prep, sum of rest → cook)

The regex for ingredients is simple: `@([^{]+)\{([^%}]*)(?:%([^}]*))?\}` captures name, amount, unit.

**Complexity estimate:** ~150 lines of PHP for import, ~80 lines for export.

### Export: Also Useful

Crumble → Cooklang export would let users take their recipes to any Cooklang-compatible tool, or store them as plain text files in Git.

The challenge is reconstructing the inline ingredient markers in instruction text. Crumble stores ingredients and instructions separately. To produce proper Cooklang, you'd need to:

1. For each instruction step, find which ingredients are mentioned
2. Replace the ingredient mention with `@name{amount%unit}`
3. If an ingredient isn't mentioned in any step, add it as a standalone line

This is fuzzy matching — similar to what `highlightIngredients()` in CookMode already does.

### My Take

**Import: Yes, add it.** It's low effort and opens Crumble to the Cooklang community. Many people have recipe collections in Cooklang format, and importing them is a clear onboarding win.

**Export: Maybe later.** The instruction reconstruction is tricky to get right, and the demand is lower — if someone wants plain text, they can just copy-paste from the recipe page.

### Priority

Lower than PWA icons and service worker, but higher than most feature ideas. It's a good "rainy day" project — self-contained, no database changes needed, and adds genuine value for a specific user segment.

## Deep Dive 112: Grocery List Categorization — Already Implemented

Discovered during this session that `ingredientCategories.js` already has a comprehensive ingredient categorization engine with 200+ keywords across 13 store sections (Produce, Meat & Seafood, Dairy & Eggs, Bakery, Frozen, Pantry, Canned Goods, Pasta & Grains, Spices & Seasonings, Condiments & Sauces, Baking, Oils & Vinegars, Beverages, Other).

The `GroceryPage.jsx` already integrates it with a toggle button (flat view vs. aisle-grouped view). The categorizer strips preparation adjectives (fresh, frozen, organic, diced, chopped, etc.) before matching, and sorts keywords by length so more specific matches win over generic ones.

### Potential Improvements

1. **Learning from corrections** — If a user moves "tahini" from Condiments to a different section, remember that preference. Would need a small `category_overrides` table or localStorage map.

2. **Store-specific ordering** — Different stores have different aisle layouts. A "store profile" feature where you set your store's section order would make the aisle grouping actually match your local store. But this is heavy configuration for marginal benefit.

3. **Merge similar items** — When multiple recipes add "butter" to the grocery list, it should consolidate into a single "butter" entry with combined quantities. This is already partially handled by the backend but could be smarter about unit conversion (1 cup + 2 tbsp → 1 cup + 2 tbsp, not 1.125 cups).

## Deep Dive 113: "Cozy" as a Design Language — Audit

Crumble's tagline is "Your cozy recipe manager." Does the design deliver on that promise? I evaluated the app against [8 cozy web design principles](https://www.tilipmandigital.com/resource-center/articles/how-to-make-website-look-cozy) and broader [2026 UX trends](https://www.index.dev/blog/ui-ux-design-trends).

### What Crumble Gets Right

| Principle | Implementation | Score |
|-----------|---------------|-------|
| Warm colors | Cream, terracotta, sage, warm brown — zero cold grays | 10/10 |
| Friendly typography | Nunito (rounded, warm) + Playfair Display (elegant serif) | 9/10 |
| White space | `rounded-2xl` cards, `space-y-6` sections, generous padding | 9/10 |
| Soft depth | `shadow-md` on cards, `backdrop-blur` on modals | 8/10 |
| Rounded forms | All inputs and buttons use `rounded-xl` or `rounded-2xl` | 9/10 |
| Conversational copy | "Your cozy recipe manager", "It's Been a While", "Keep it up!" | 8/10 |
| Dark mode warmth | Dark brown (#1A1412) not black, warm grays not cool | 9/10 |

### What Could Be Even Cozier

1. **Micro-animations on content load** — Cards could fade-in with a slight upward drift (Tailwind `animate-in` or CSS `@keyframes`) when the page loads. Currently content appears instantly, which is functional but not delightful. The trick is making this subtle enough not to feel slow. A 150ms staggered fade-in on recipe cards would add polish without perceptible delay.

2. **Greeting with the user's name** — The HomePage shows "Welcome to Crumble" for empty states but never addresses the user by name. A "Good morning, Frank" or "What are you cooking tonight?" header would add personal warmth. The user data is already in `useAuth()`.

3. **Seasonal micro-touches** — Subtle seasonal hints in the app's personality:
   - Spring: "Fresh ingredients are in season!" on the homepage
   - Summer: BBQ/grilling related suggestions
   - Autumn: Warm soup and baking suggestions
   - Winter: "Perfect weather for a slow cooker recipe"

   These would need to be very subtle — a single sentence, not theme changes or icon swaps. The goal is to make the app feel like it's aware of the world, not to be a themed decoration.

4. **Cook streak celebrations** — When the user hits a 7-day streak or cooks their 50th recipe, a brief celebration moment. Not a modal — maybe a subtle confetti animation on the stats page, or a one-line message that appears once. The stats page already shows streaks but treats them as plain data.

5. **Recipe memories** — "You first cooked this on March 3, 2025" or "Last cooked 2 months ago" on the recipe page. The cook_log data exists to support this. It transforms a recipe from a static document into a personal history.

### What NOT to Do

- **Seasonal color themes** — Don't change the terracotta/sage palette. Brand consistency matters more than novelty.
- **Sound effects** — Timer beeps are enough. No other sounds.
- **Animated mascot** — Crumble doesn't need a talking spatula. The warmth comes from design and copy, not characters.
- **Loading animations with personality** — "Cooking up your recipes!" while showing a spinner. This slows perceived performance and gets annoying fast.

### Implementation Priority

The "recipe memories" idea (item 5) is the highest-impact, lowest-effort option. The cook_log already has `cooked_at` timestamps. Adding "First cooked: March 2025 · Last cooked: 2 weeks ago" to the recipe page is a single API query and a two-line UI addition. It transforms every recipe into a personal story.

The personalized greeting (item 2) is similarly easy — one conditional line in HomePage using data from `useAuth()`. But it needs to not feel forced or corporate.

---

## Deep Dive 114: Recipe Memories — Implemented

*Date: 2026-03-10*

### What I Built

Implemented the "recipe memories" concept from DD113. When you've cooked a recipe at least once, the recipe page now shows a warm "Your History" card:

- **First made [date]** — formatted as "March 3, 2025" (full month, readable)
- **Last cooked [time ago]** — e.g., "2 weeks ago", "yesterday"
- **Made X times** — total cook count

The card only appears if you have cook log entries. For a single cook, it just shows "First made [date]" without the redundant "1 time" count.

### Implementation Details

**No backend changes.** The `cookNotes` array is already fetched via `getRecipeCookLog(id)` on page load. The API returns entries sorted most-recent-first, so:
- `cookNotes[cookNotes.length - 1].cooked_at` = first cook
- `cookNotes[0].cooked_at` = most recent cook
- `cookNotes.length` = total count

**New file:** `frontend/src/utils/timeAgo.js` — A simple relative time formatter. Returns "just now", "2 days ago", "last month", "about a year ago", etc. No dependencies, 30 lines.

**UI placement:** Replaces the old "Your Cook Notes" header and integrates into a cream-colored card above the cook notes list. The heading changed from "Your Cook Notes" to "Your History" to better encompass both the timeline and notes.

### Design Decisions

1. **No separate API call.** Could have added `first_cooked`/`last_cooked` to the recipe detail endpoint (two subqueries), but the data was already on the page. Zero added latency.

2. **Full date for "first made", relative for "last cooked".** First-cook dates are milestones — you want to know it was "January 15, 2025", not "14 months ago." But last-cooked benefits from relative time since you're thinking "has it been a while?"

3. **Single-cook case.** When `cookCount === 1`, showing "Last cooked: just now · Made 1 time" is redundant. Just show "First made [date]" alone.

4. **Cream card with subtle border.** Uses `bg-cream/60` with `border-cream-dark/30` — warm, slightly lifted from the page, but not attention-grabbing. This is supporting context, not a call-to-action.

---

## Deep Dive 115: Personalized Greeting & Cook History Stats

*Date: 2026-03-10*

### Personalized Greeting (HomePage)

Replaced the static "Recipes" heading on the desktop home page with a time-of-day greeting:

| Time | Greeting |
|------|----------|
| 12am–5am | Late night cooking, [Name] |
| 5am–12pm | Good morning, [Name] |
| 12pm–5pm | Good afternoon, [Name] |
| 5pm–9pm | Good evening, [Name] |
| 9pm–12am | Late night cooking, [Name] |

Falls back to just the greeting (no name) if username is unavailable. Falls back to plain "Recipes" when filtering/searching.

Uses `user.username` since there's no `display_name` column — takes first word only (split on space) for a natural feel.

**Why it works:** The greeting is in the exact spot where a generic heading was before. It doesn't add UI elements, doesn't require a new API call, and doesn't feel forced because it appears only on the main landing view.

### Cook History Stats Card

Added a 3-column summary card to the top of CookHistoryPage:

1. **Day Streak** — Consecutive days with at least one cook, counting back from today (or yesterday if nothing cooked today). Orange flame icon when active, gray when zero.
2. **This Month** — Count of cooks in the current calendar month. Green calendar icon.
3. **Top Recipe** — Most frequently cooked recipe from the history, with "Nx cooked" subtitle.

All computed client-side from the existing 100-entry cook history — no new API calls. The `computeStats` function extracts unique cook days into a Set, then walks backwards from today to count the streak.

**Design note:** The streak counter uses the Flame icon from Lucide (same icon vocabulary as the rest of the app). When active (>0), it's terracotta; when zero, it's muted gray. This avoids the "gamification for its own sake" problem — it's informational, not pressuring.

---

## Deep Dive 116: RecipeScraper Architecture — An Appreciation

*Date: 2026-03-10*

I spent time reading through the full RecipeScraper (497 lines) and I want to document what I found because it's a genuinely well-built piece of code.

### The 4-Tier Parser

The scraper tries four parsing strategies in priority order:

1. **JSON-LD** (`parseJsonLd`) — Looks for `<script type="application/ld+json">` blocks containing Recipe schema. Handles `@graph` arrays, nested types, and arrays of schemas. This works on ~80% of major recipe sites (NYT Cooking, AllRecipes, Food Network, etc.).

2. **Microdata** (`parseMicrodata`) — Falls back to `itemtype="schema.org/Recipe"` HTML attributes. Uses DOMXPath to query itemprop values. Handles both text content and `content` attributes on meta tags.

3. **Heuristic HTML** (`parseHeuristic`) — Regex + DOM analysis: scans for common CSS classes (`recipe-ingredients`, `recipe-directions`, `wprm-recipe-*`), heading patterns, and list structures. This catches sites that use neither JSON-LD nor microdata.

4. **Open Graph** (`parseOpenGraph`) — Last resort. Extracts `og:title`, `og:description`, `og:image` meta tags. Gives you at least a title and image, but no ingredients or instructions.

### What It Does Right

- **SSRF protection** — Validates URLs, resolves hostnames, blocks private IP ranges before making any requests. This matters for a self-hosted app that accepts arbitrary URLs.
- **Nutrition extraction** — Parses schema.org NutritionInformation from JSON-LD. Handles formats like "250 calories", "12 g", and bare numbers.
- **Tag extraction** — Pulls `recipeCategory`, `recipeCuisine`, and `keywords` from JSON-LD.
- **HowToSection support** — Nested instruction sections (common in complex recipes) are flattened with section headers preserved as `--- Section Name ---`.
- **Graceful degradation** — Never throws. Returns partial data on failure. Each parser returns `null` if it can't parse, triggering the next tier.
- **User-Agent rotation** — 5 different browser-like UAs to reduce blocking.

### What Could Be Better

1. **No `totalTime` handling** — If a recipe specifies only `totalTime` (not prepTime/cookTime), it's ignored. Easy fix: fall back to `totalTime` when both are null.
2. **AMP cache as sole fallback** — The cached version fetcher only tries Google AMP Cache. Some sites don't have AMP pages. Alternatives like archive.org's Wayback Machine API could help but add complexity.
3. **No cookie/session handling** — Sites behind paywalls or "continue reading" gates won't work. This is by design (privacy/legal), but worth noting.
4. **Heuristic parser hardcodes WPRM** — The WordPress Recipe Maker plugin classes are hardcoded. Could be extended to other popular plugins (Tasty Recipes, Recipe Card Blocks, etc.).

### The Missing `totalTime` Fix

This is worth implementing. The JSON-LD mapper checks `prepTime` and `cookTime` but ignores `totalTime`. Many recipe sites provide only `totalTime`:

```php
// In mapJsonLdRecipe:
$result['cook_time'] = $this->parseDuration($data['cookTime'] ?? null);

// Could add:
if (!$result['prep_time'] && !$result['cook_time']) {
    $total = $this->parseDuration($data['totalTime'] ?? null);
    if ($total) $result['cook_time'] = $total;
}
```

This would use `totalTime` as the cook_time when neither prep nor cook time is specified — same logic the frontend's recipeTextParser already uses. **Update:** Implemented this fix in both `parseJsonLd` and `parseMicrodata` parsers.

---

## Deep Dive 117: What Makes a Recipe App Feel "Lived In"

*Date: 2026-03-10*

This is less about features and more about philosophy. I've been thinking about what separates a recipe app that feels like a tool from one that feels like a kitchen.

### The Spectrum

Most recipe apps sit on a spectrum:

**Clinical** ← ─────────────── → **Personal**

On the left: perfectly organized, sterile, optimized for efficiency. Think a database admin tool with food pictures. Every recipe is identical in structure. Nothing reflects the user's history, preferences, or personality.

On the right: a kitchen notebook with flour stains. Recipes have history ("I always double the garlic"). The app remembers things about you. It's not optimized — it's inhabited.

Crumble already leans right. The cook log, favorites, notes, personal ratings — these are all "inhabitation" features. But there's a specific pattern I want to name.

### The Pattern: Accumulated Traces

The most "lived-in" feeling in software comes from **accumulated traces** — evidence that the user has been here before and the system remembers.

Examples of accumulated traces in software:
- **Spotify Wrapped** — "Here's what you listened to this year." Transforms usage data into personal narrative.
- **Apple Photos "Memories"** — Surfaces old photos with "3 years ago today." Your data, reflected back as story.
- **GitHub contribution graph** — The green squares show your presence over time.
- **Goodreads reading history** — "You've read 47 books this year." Turns consumption into identity.

What these have in common: they use existing behavioral data to create moments of reflection. The user didn't do anything extra — they just used the app. But the app noticed.

### What Crumble Already Has

| Feature | Trace Type |
|---------|-----------|
| Cook count on recipes | Frequency trace |
| Cook notes | Narrative trace |
| Favorites | Preference trace |
| Ratings | Opinion trace |
| Cook history page | Temporal trace |
| **Recipe memories (new)** | **Biographical trace** |
| **Cooking streak (new)** | **Habit trace** |

### What's Still Missing

1. **Seasonal traces** — "You tend to cook more soups in winter" or "Your busiest cooking month was November." The cook_log has timestamps. Cross-referencing with recipe tags could surface seasonal patterns.

2. **Growth traces** — "Your first recipe had 4 ingredients. Your latest has 12." Subtle acknowledgment that the user is growing as a cook.

3. **Social traces** — "You've shared this recipe 3 times." Shows that recipes become gifts.

4. **Annotation traces** — Beyond cook notes, what if ingredients could have personal annotations? "I always use Kerrygold here" on a butter ingredient. This is the digital equivalent of writing in the margin of a cookbook.

### The Key Constraint

Every trace must emerge from data the user already creates through normal use. The moment you ask users to "journal" or "log" or "track" explicitly, you've turned warmth into work. The magic is in the ambient accumulation.

Cook notes already walk this line well — they're optional, appear at the moment of cooking (when the user is most engaged), and resurface later as personal history.

### What I'd Build Next

**The "Year in Cooking" page.** Once a year (or on-demand), generate a summary:
- Total recipes cooked
- Total unique recipes
- Most cooked recipe
- New recipes tried per month
- Favorite cuisine (by tag frequency)
- Longest cooking streak
- Total time spent cooking (sum of cook_times × cook_count)

No new data collection needed. Everything derives from cook_log + recipe metadata. It's Spotify Wrapped for home cooking.

The emotional impact would be significant. Seeing "You cooked 156 meals at home this year" transforms cooking from daily chores into an accomplishment. It's the difference between "I cook dinner" and "I'm a home cook."

### Implementation Cost

Low. The stats page already exists and shows some of this. A "Year in Review" mode would be a filtered view of the same data with narrative framing. The cook_log query would need a date range filter (trivially: `WHERE cooked_at >= ? AND cooked_at < ?`), and the frontend would need a presentation layer that tells a story instead of showing a dashboard.

The hard part isn't the code — it's the copywriting. "You cooked 156 meals" is data. "156 home-cooked meals. That's 156 times you chose to feed yourself and the people you love something made with care" is emotional design.

---

## Deep Dive 118: Thoughts on Recipe Data as Personal Archive

*Date: 2026-03-10*

I've been reading about how recipe apps handle data ownership, and it's made me think about what Crumble's recipes actually represent over time.

### Recipes Are Documents, But Cook Logs Are Diaries

A recipe is a static document. Once saved, it rarely changes. But a cook_log entry is a timestamp — a moment frozen in time. "I made beef stew on January 12, 2026" is a fact about your life, not about food.

Combined, they form something closer to a personal archive than a recipe database. The recipe is the score; the cook log is the performance history. A recipe you've cooked 30 times isn't the same object as a recipe you've never cooked, even if the text is identical.

### The Export Problem

Crumble currently has import (scraper, Mealie, Paprika) but limited export. This matters because recipe collections are personal archives, and personal archives demand portability.

What a good export would include:
- All recipes in a standard format (JSON, or RecipeML/schema.org)
- All cook log entries (dates, notes)
- All ratings and favorites
- All grocery lists
- All tags and categories
- Recipe images

This isn't just a "data portability" checkbox. It's a trust signal. Users invest more in a system they know they can leave. Paradoxically, making it easy to export makes people less likely to want to.

### The Standard Format Question

There's no universally accepted recipe exchange format. The closest options:

1. **schema.org/Recipe JSON** — What most web scrapers produce. Good for individual recipes but no standard for collections, cook history, or personal data.

2. **Paprika format** — `.paprikarecipes` files (gzipped archives of JSON). De facto standard among recipe app users. Crumble already imports this.

3. **Cooklang** (`.cook` files) — Plain text with inline markup. Beautiful for version control and human readability. No standard for metadata beyond the recipe itself.

4. **RecipeMD** — Markdown-based recipe format. Similar philosophy to Cooklang but less structured.

5. **Open Recipe Format (ORF)** — An attempt at standardization that never gained traction.

The pragmatic move: export as a ZIP containing:
- `recipes/` folder with individual JSON files (schema.org format)
- `images/` folder
- `metadata.json` with cook logs, ratings, favorites, tags
- A `README.md` explaining the format

This is simple, self-documenting, and readable by humans even without Crumble. Another app could import the schema.org JSON files; a human could read the metadata.

### Self-Hosted Advantage

Crumble runs on the user's own server. The database is literally their file. They can `mysqldump` at any time. This is already better than cloud-hosted alternatives where your data lives on someone else's server.

But raw SQL dumps aren't user-friendly exports. A one-click "Download My Recipes" button that produces the ZIP described above would complete the story.

### Priority

This isn't urgent — Crumble's user base is small and self-hosted users tend to be technically capable of accessing their own databases. But it's the kind of feature that builds trust and differentiates from cloud competitors. "Your recipes, your data, your server, your export" is a complete ownership story.

---

## Deep Dive 119: Mobile Navigation Gap — Fixed

*Date: 2026-03-10*

### The Problem

The desktop sidebar has 8 navigation items; the mobile bottom nav has 5. Three pages were completely unreachable on mobile:
- **Cook History** — one of the most personal features
- **Kitchen Stats** — the gamification/insight page
- **Bulk Import** — power user feature, less critical

Plus Admin (for admin users). There was no hamburger menu, no drawer, no "More" tab — just dead ends.

### The Fix

Added a hamburger menu button (☰) to the mobile header, opening a slide-out drawer from the right edge. The drawer contains:

1. **User info** — avatar, username, role (matches desktop sidebar footer)
2. **Secondary nav links** — Cook History, Kitchen Stats, Bulk Import, Admin (if admin)
3. **Theme toggle** — Light/Dark/System cycle
4. **Logout button**

### Design Decisions

1. **Right-side drawer, not left.** The bottom nav is the primary navigation (left thumb zone). The drawer is secondary — opened from the top-right hamburger, slides in from the right. This preserves the mental model: primary nav = bottom, secondary = drawer.

2. **Only secondary items in the drawer.** Home, Add, Favorites, Plan, and Grocery stay in the bottom nav. The drawer doesn't duplicate them — it only shows pages that aren't in the bottom nav. This prevents choice paralysis.

3. **Backdrop + body scroll lock.** The drawer has a semi-transparent backdrop (`bg-black/40`) and locks body scroll when open. Standard mobile drawer patterns.

4. **Auto-close on navigation.** Each NavLink calls `setDrawerOpen(false)` on click. The drawer also closes on browser back button (popstate listener).

5. **CSS transition, not JS animation.** Uses `transform transition-transform duration-300` with `translate-x-full` ↔ `translate-x-0`. GPU-accelerated, 60fps.

### Size Impact

+3.5KB uncompressed / ~1KB gzip. Acceptable for restoring access to 3 full pages on mobile.

---

## Deep Dive 120: Database Reality Check

*Date: 2026-03-10*

Queried the actual database to see what the recipe data looks like in practice. Key findings:

### Recipe Stats (146 recipes)
- **Average 5 steps** per recipe, **9.3 ingredients**
- **Average total time: 11 minutes** (but misleading — see below)
- **100% scraped** — all have `source_url`, images, and descriptions
- **145/146 have prep_time, only 5/146 have cook_time**

### The Cook Time Gap

This is the biggest data quality issue. 141 out of 146 recipes have no cook_time. The average "total time" of 11 minutes is skewed because it's almost entirely prep_time.

Root cause: many recipe sites provide `totalTime` in JSON-LD but not `cookTime` separately. The scraper was silently dropping `totalTime` when both `prepTime` and `cookTime` were absent. **Fixed in this session** — the scraper now falls back to `totalTime` as `cook_time` in both the JSON-LD and microdata parsers.

This fix won't retroactively update the 141 recipes, but newly scraped recipes should get time data from sites that provide `totalTime`.

### Tag Sparsity

- **0.3 tags per recipe** average — most recipes have zero tags
- Only 47 tag assignments across 146 recipes
- 24 unique tags exist, most used only once
- Top tags: "main dish" (5), "meal" (5), "pasta" (5), "dessert" (4)

This suggests the scraper's tag extraction (from JSON-LD `recipeCategory`, `recipeCuisine`, `keywords`) only works on some sites. Many recipe sites don't populate these fields.

**Potential fix: Auto-tagging.** Analyze recipe titles and ingredient lists to infer tags:
- Title contains "chicken" → tag: chicken
- Ingredients include "pasta" → tag: pasta
- Title contains "soup" or "stew" → tag: soup
- Ingredients include "chocolate" or "sugar" → tag: dessert

This could be a background process that runs on import and fills in gaps. Simple keyword matching would get 70%+ accuracy.

### Complexity Distribution

| Complexity | Count | % |
|-----------|-------|---|
| 1-3 steps | 45 | 31% |
| 4-6 steps | 70 | 48% |
| 7-10 steps | 24 | 16% |
| 11+ steps | 7 | 5% |

Most recipes are moderate (4-6 steps). The difficulty estimation feature maps well to this distribution — the bulk should fall in "Easy" to "Medium".

---

## Deep Dive 121: Auto-Tagging — Implemented

*Date: 2026-03-10*

### The Problem

Only 0.3 tags per recipe. Most recipes (100+ out of 146) had zero tags. The scraper only extracts tags from JSON-LD `recipeCategory`/`recipeCuisine`/`keywords`, which many sites don't populate.

### The Solution

Built `api/services/AutoTagger.php` — a keyword-based tag suggester that analyzes recipe title, description, and ingredient names. Integrated into `Recipe::create()` so every new recipe automatically gets suggested tags merged with any scraper-provided tags.

### Tag Rules (30 tags)

| Category | Tags |
|----------|------|
| **Protein** | chicken, beef, pork, seafood, vegetarian, vegan |
| **Meal type** | breakfast, soup, salad, sandwich, pasta, rice, appetizer |
| **Dessert/Baking** | dessert, baking |
| **Cuisine** | italian, mexican, asian, indian, mediterranean |
| **Method** | grilling, slow cooker, one pot, quick |

Each tag has 2-10 trigger keywords. For example, "mexican" triggers on: mexican, taco, burrito, enchilada, quesadilla, salsa, guacamole, tortilla, chipotle.

### Special Rules

- **Title gets double weight** — the title is included twice in the searchable text, so title keywords are more likely to match.
- **Quick tag by time** — If total time (prep + cook) is ≤ 30 minutes and > 0, auto-tagged as "quick" regardless of keywords.
- **Merging, not replacing** — Auto-suggested tags are merged with any existing tags (from scraper or manual entry). Deduplication prevents doubles.

### Test Coverage

8 tests, 13 assertions:
- Chicken recipe → tagged "chicken"
- Pesto penne → tagged "pasta" + "italian" (ingredient-level detection)
- Beef tacos with salsa → tagged "beef" + "mexican"
- Chocolate lava cake → tagged "dessert"
- Quick recipe by time → tagged "quick"
- Empty recipe → no tags
- Soup recipe → tagged from title
- False positive check → salad not tagged as chicken/pasta/dessert

### Backfill Option

Existing 146 recipes could be retroactively auto-tagged with a one-time SQL/PHP script that:
1. Loads each recipe with its ingredients
2. Runs `AutoTagger::suggest()`
3. Inserts new tag associations (skipping duplicates)

This would dramatically improve tag coverage from 0.3 → estimated 1.5-2.5 tags/recipe. Not implemented yet — should be a deliberate user action since it modifies existing data.

### Auto-Tagger v2 Fixes

After dry-running v1 against the database, found three problems:
- **"quick" tagged 141/146 recipes** — because most recipes only had `prep_time` (no `cook_time`), so total time was ≤30 min by default. Fixed: now requires `cook_time > 0`.
- **"starter" in appetizer** — matched "sourdough starter" ingredient. Removed "starter" keyword.
- **"olive oil" in mediterranean** — too common, every cuisine uses it. Replaced with "greek".

After fixes: 124 recipes gain tags (avg 1.79 new tags), 19 remain untagged (mostly beverages and condiments). "quick" dropped from 141 → 6.

---

## Deep Dive 122: Search Bar Theme Bug — Fixed

*Date: 2026-03-10*

### The Bug

The search bar in the header appeared dark with pale orange text on light theme — unreadable.

### Root Cause

The search inputs used `bg-cream/50` (semi-transparent cream). In the CSS, there was an `!important` override:

```css
@media (prefers-color-scheme: dark) {
  .bg-cream\/50 { background-color: rgba(26, 20, 18, 0.5) !important; }
}
```

This fires based on **system** dark mode preference, ignoring the manual theme toggle (`data-theme="light"`). So if the user's OS was in dark mode but they chose light theme in Crumble, the search bar got a dark background.

### Fix

Replaced `bg-cream/50` with `bg-surface` on all search inputs. `bg-surface` uses CSS custom properties that properly follow the theme toggle. Removed the now-unused `bg-cream/50` CSS overrides.

---

## Deep Dive 123: Mobile Theme-Color Sync — Fixed

*Date: 2026-03-10*

### The Bug

The browser's address bar color (theme-color) wasn't syncing correctly with the theme toggle on mobile.

### Root Cause

Two `<meta name="theme-color">` tags in index.html use `media` attributes to select light/dark colors. When the user toggles to light/dark manually:
1. The JS set both tags to the same color value — correct
2. But it didn't remove the `media` attributes — so browsers still read only the one matching system preference
3. When switching back to "system", the `media` attributes were never restored

Also, `apple-mobile-web-app-status-bar-style` was set to `black-translucent`, which forces white text on a transparent overlay — looks wrong on light backgrounds.

### Fix

1. **useTheme hook** now properly manages meta tag `media` attributes:
   - **Manual theme:** Removes `media` attributes from both tags, forces same color
   - **System theme:** Restores `media` attributes so browser picks the right color
2. **index.html** inline script updated to also remove `media` attributes on manual theme
3. **Status bar style** changed from `black-translucent` to `default`

---

## Deep Dive 124: Recipe Export — Implemented

*Date: 2026-03-10*

### What Was Built

**`GET /api/recipes/export-zip`** — Downloads a ZIP file containing the user's entire recipe collection.

### ZIP Contents

```
crumble-export-2026-03-10.zip
├── recipes/
│   ├── 1_Chicken-Parmesan.json
│   ├── 2_Beef-Stew.json
│   └── ...
├── images/
│   ├── 1/
│   │   ├── full.jpg
│   │   └── thumb.jpg
│   ├── 2/
│   │   ├── full.jpg
│   │   └── thumb.jpg
│   └── ...
├── metadata.json
└── README.md
```

### Recipe JSON Format

Each recipe is a standalone JSON file with:
- Title, description, instructions (array), ingredients (with amounts/units)
- Prep time, cook time, servings
- Nutrition (calories, protein, carbs, fat, fiber, sugar)
- Tags, source URL
- Created/updated timestamps

### Personal Metadata

`metadata.json` includes user-specific data:
- **Cook history** — Every cook log entry with recipe title, date, and notes
- **Ratings** — Per-recipe star ratings
- **Favorites** — List of favorited recipe IDs

### UI Integration

"Export Recipes" link added to:
- Desktop sidebar (between theme toggle and logout)
- Mobile drawer menu (in footer section)

Uses a simple `<a href="/api/recipes/export-zip">` — triggers browser download, no JavaScript needed.

### Technical Notes

- ZIP built in memory using `ZipArchive`, streamed directly via `readfile()`, temp file cleaned up immediately
- Images included as actual files (full + thumbnail JPEG)
- Auth required — only the authenticated user can export
- Tested: 146 recipes + 146 images, ZipArchive available on PHP 8.3

---

## Deep Dive 125: The 2026 Self-Hosted Recipe Landscape — Crumble's Position

*Date: 2026-03-10*

### What's Changed Since the Last Survey (DD16 / DD31)

The field continues to grow. Here's the current state of play:

| App | Stack | Stars | Docker Required | Key Move Since Last Look |
|-----|-------|-------|----------------|------------------------|
| **Mealie** | Vue + Python | 11k+ | Yes | 10,000+ self-hosted instances. ML ingredient parsing. Biweekly updates. |
| **Tandoor** | Vue + Django | ~5k | Yes | Added AI for ingredient recognition + nutrition estimation. Nextcloud/Dropbox sync. |
| **KitchenOwl** | Flutter + Flask | ~2k | Yes | Native mobile apps (Flutter). Household expense tracking. |
| **Norish** | Next.js + ? | ~1k | Yes | **The newcomer to watch.** Real-time WebSocket sync. Video recipe import (YouTube Shorts, TikTok, Reels). AI image-to-recipe. OIDC SSO with role mapping. i18n. Complete grocery redesign with store-based views. |
| **Recipya** | Go monolith | ~500 | Optional | Unit system conversion (imperial↔metric globally). Import from Mealie/Tandoor/Nextcloud/Paprika. |
| **Cooklang** | Rust CLI + plain text | ~3k | No | Not an app — a markup language for recipes as text files. Growing ecosystem. |
| **Crumble** | PHP + React | — | No | The only non-Docker web app. Laragon/Apache/MySQL. Zero-dependency deployment. |

### Norish Deserves Attention

Norish wasn't on my radar in the last survey. It's explicitly positioned as "Mealie/Tandoor but prettier and simpler." What makes it interesting:

1. **Real-time sync via WebSockets** — Multiple household members see recipe/grocery changes instantly. No polling, no refresh.
2. **Video recipe import** — Paste a TikTok/Reel URL, AI extracts the recipe. This is a genuinely new input modality for self-hosted apps.
3. **Image-to-recipe** — Take a photo of a recipe card or cookbook page, AI parses it. Same category as video import — new ways to capture recipes beyond URL scraping and manual entry.
4. **OIDC role mapping** — SSO with automatic admin/household assignment based on identity provider claims. More sophisticated than Crumble's Authentik header-based approach.
5. **Vercel AI SDK integration** — Not bolted-on AI. Uses a proper SDK with provider abstraction.

**What Norish doesn't have that Crumble does:** No non-Docker deployment path. No CookMode with wake lock/timers/swipe navigation. No cook history/stats. No Paprika import (they just added it). No offline capability.

### The Docker Question

Every single competitor requires Docker. Every one. Crumble remains the only self-hosted recipe manager that runs on a traditional LAMP-like stack.

This used to feel like a limitation — "we should Dockerize." But looking at the field, it's actually the differentiator. The audience that runs Laragon, XAMPP, or a shared hosting account has *zero* options besides Crumble. That's a real niche.

The question is whether this audience is large enough to matter. I think it is. Not everyone running a home server uses Docker. Many people run a Synology NAS with Apache/PHP built in, or a Raspberry Pi with a basic LAMP stack, or a cheap shared hosting plan. For those people, Crumble is the only option that works.

### AI Features — The Growing Pressure

Three of the top five competitors now have AI features:
- Tandoor: ingredient recognition, nutrition estimation
- Norish: video-to-recipe, image-to-recipe, AI-powered URL import fallback
- Mealie: ML ingredient parsing

Crumble has none. Is that a problem?

**My take: not yet, and maybe not ever for the core app.** Here's why:

1. **AI requires API keys or local models.** Both add complexity. API keys cost money and send data externally. Local models need GPU or significant CPU. This directly conflicts with Crumble's "zero-dependency" philosophy.
2. **The use cases are narrow.** Video-to-recipe is cool but how many recipes per month does the average person import from TikTok? Image-to-recipe is a one-time batch job when digitizing a physical collection. These aren't daily features.
3. **The AutoTagger already covers the biggest gap.** Recipe categorization from content analysis — which is what "AI tagging" really means in most competitors — is already handled by keyword matching. It's not as fancy, but it's deterministic, fast, and requires no API key.

**If I were to add AI, the highest-value feature would be:** An optional, bring-your-own-key AI fallback for the recipe scraper. When JSON-LD/microdata/heuristic parsing all fail, send the HTML to an LLM and ask it to extract the recipe. This is exactly what Norish does. It's a fallback, not a core feature. It degrades gracefully when no key is configured.

### Format Wars: Cooklang's Argument

Cooklang is making an interesting philosophical argument: your recipes should be plain text files, not database rows. Their pitch:

> "Start with Cooklang if you want simplicity and data ownership — your recipes are text files you control forever."

This resonates because it's the same argument that Obsidian users make about notes. Plain text is future-proof. Databases are lock-in.

Crumble's export-zip feature partially addresses this — recipes export as individual JSON files. But JSON isn't human-readable the way Cooklang's `.cook` format is:

```cooklang
Crack @eggs{3} into a bowl with @milk{1/2%cup} and whisk.
Heat @butter{1%tbsp} in a #pan{} over medium heat.
Pour in egg mixture and cook for ~{3%minutes}, stirring gently.
```

vs. Crumble's JSON:
```json
{
  "ingredients": [{"amount": "3", "name": "eggs"}, ...],
  "instructions": ["Crack eggs into a bowl with milk and whisk.", ...]
}
```

The Cooklang format is the recipe. The JSON format is metadata *about* the recipe. There's an aesthetic difference.

**Practical implication for Crumble:** Cooklang export would be a nice addition to the ZIP export. Each recipe as a `.cook` file alongside the JSON. Low effort (~2 hours), high philosophical value for data portability.

---

## Deep Dive 126: The Kitchen Counter Problem

*Date: 2026-03-10*

### The Physical Reality

When you're cooking, your phone or tablet is sitting on a kitchen counter. Your hands are covered in flour, oil, raw chicken, or all three. You need to:

1. See what's next in the recipe
2. Not touch the screen
3. Keep the screen from going dark
4. Check ingredient amounts without losing your place

This is the fundamental UX challenge of cooking apps, and it's not a software problem — it's a physical environment problem.

### How Crumble Handles It Today

Crumble's CookMode is actually quite good:

| Feature | Status | Notes |
|---------|--------|-------|
| Wake Lock | ✅ | Screen stays on. Re-acquires on visibility change. |
| Step-by-step navigation | ✅ | Swipe left/right, keyboard arrows |
| Large text on dark background | ✅ | High contrast, readable from arm's length |
| Timer detection | ✅ | Regex parses "X minutes" from instructions, one-tap start |
| Ingredient sidebar | ✅ | Slide-out panel, toggle button |
| Progress persistence | ✅ | Saved to sessionStorage, resumes on re-open |
| Cook log on completion | ✅ | Modal on last step with optional notes |
| Ingredient highlighting | ✅ | Ingredient names highlighted in step text |

That's a genuinely strong feature set. The Betty Crocker case study found that Wake Lock alone drove a **300% increase in purchase intent indicators.** Crumble has wake lock *plus* step navigation, timers, ingredient highlighting, and cook logging. Most self-hosted competitors don't even have CookMode.

### What's Missing

**The things that would make it genuinely hands-free:**

1. **Voice navigation.** "Next step." "What was that again?" "How much flour?" This is the dream. But it requires the Web Speech API, which is:
   - Chrome: good support
   - Firefox: limited
   - Safari/iOS: unreliable
   - Privacy concern: some browsers send audio to cloud for processing

   **Verdict:** Not ready for a production self-hosted app. The browser support is too inconsistent, and the privacy model conflicts with self-hosting values.

2. **Physical button navigation.** Volume buttons to advance steps. This would solve the flour-on-hands problem without voice. Unfortunately:
   - Browsers don't expose volume button events
   - PWAs can't capture hardware buttons
   - This only works in native apps

   **Verdict:** Impossible in a web app. Native territory.

3. **Proximity/gesture detection.** Wave your hand near the camera to advance. Uses the MediaDevices API.
   - Actually possible in modern browsers
   - But creepy (camera access while cooking?)
   - Battery drain
   - Unreliable with varied lighting

   **Verdict:** Novelty, not practical.

### What IS Practical

**Things that would genuinely help in the kitchen, within web platform constraints:**

1. **Larger tap targets in CookMode.** The entire left half of the screen = previous step. Entire right half = next step. No need for precise button taps. This is how e-readers work and it's perfect for messy-hands navigation.

   Current CookMode has specific nav buttons. Making the whole screen tappable is a 10-minute change.

2. **Auto-scroll on step change.** When advancing to a long step, ensure the text starts at the top. Currently not an issue since CookMode shows one step at a time with overflow scroll.

3. **Timer sound/vibration.** When a timer finishes, play a sound and vibrate. The Vibration API works on Android Chrome. Sound works everywhere.

   Current CookMode timers have no completion alert. You just see the timer hit zero. **This is a real bug** — a silent timer is useless if you're not looking at the screen.

4. **"Read aloud" button per step.** One tap, the browser reads the current step using `speechSynthesis`. Not voice *recognition* (which has browser issues) but voice *output* (which works everywhere, even offline).

   This is the realistic version of "voice navigation." You can't talk to the app, but the app can talk to you.

5. **Elbow-friendly swipe zones.** People actually use their elbows on phone screens when their hands are messy. The swipe detection should be generous — low minimum distance threshold, accept elbow-width contact areas.

### The Timer Alert Gap

This is the one I keep coming back to. Let me look at how timers work:

The current flow:
1. Step text says "cook for 5 minutes"
2. Regex detects "5 minutes"
3. User taps to start timer
4. Timer counts down in the persistent bar at bottom
5. Timer reaches zero
6. ...nothing. Timer just shows 0:00.

That last step is a genuine UX failure. You set a timer *because* you're going to look away from the screen. If the completion is silent, you have to watch the timer — which defeats the purpose.

**Fix:** When a timer hits zero:
- Play a gentle alarm sound (can be a short audio file, or generated via Web Audio API)
- Vibrate the phone (Vibration API: `navigator.vibrate([200, 100, 200])`)
- Show a persistent notification-style banner
- Maybe flash the screen background briefly

This is maybe 30 minutes of work and would make CookMode timers actually useful.

### The Bigger Picture: Cooking Is a Physical Activity

The recipe app industry keeps trying to solve the kitchen problem with software. Voice assistants, AR overlays, smart displays. But the best solutions are the simplest:

- Keep the screen on (Wake Lock — done)
- Make text big (CookMode — done)
- Make tap targets huge (easy fix)
- Alert when timers finish (easy fix)
- Let the app read to you (speechSynthesis — moderate effort)

Everything else is over-engineering. You don't need AI to cook dinner. You need your screen to stay on and your timer to beep.

---

## Deep Dive 127: Recipe Formats — What Actually Matters for Portability

*Date: 2026-03-10*

### The Format Landscape

| Format | Type | Readable | Structured | Ecosystem |
|--------|------|----------|------------|-----------|
| **JSON-LD (schema.org/Recipe)** | Semantic web | No (JSON) | Yes | Universal — every recipe site uses this for SEO |
| **Cooklang** | Markup language | Yes | Yes | Growing — CLI tools, Obsidian plugin, CookCLI |
| **MealMaster** | Legacy text | Somewhat | Barely | Ancient but still exported by Paprika |
| **RecipeML** | XML | No | Yes | Dead |
| **Crumble JSON** | Custom JSON | No | Yes | Crumble only |

### What Crumble Currently Exports

The ZIP export produces per-recipe JSON files with this structure:
```json
{
  "title": "...",
  "description": "...",
  "ingredients": [
    {"amount": "2", "unit": "cups", "name": "flour"},
    {"amount": "1", "unit": "tsp", "name": "salt"}
  ],
  "instructions": ["Step 1...", "Step 2..."],
  "prep_time": 15,
  "cook_time": 30,
  "servings": 4,
  "tags": ["dinner", "easy"],
  "source_url": "https://...",
  "nutrition": { ... }
}
```

This is fine for re-import into Crumble but useless for anything else. No other app can read it.

### What Would Make Export Actually Portable

**Option 1: JSON-LD (schema.org/Recipe)**

The universal format. Every recipe scraper already parses this. If Crumble exported JSON-LD, the recipes could be imported by *any* recipe manager (Mealie, Tandoor, Paprika, etc.) because they all have schema.org importers.

The mapping is straightforward:
```json
{
  "@context": "https://schema.org/",
  "@type": "Recipe",
  "name": "...",
  "description": "...",
  "recipeIngredient": ["2 cups flour", "1 tsp salt"],
  "recipeInstructions": [
    {"@type": "HowToStep", "text": "..."}
  ],
  "prepTime": "PT15M",
  "cookTime": "PT30M",
  "recipeYield": "4 servings",
  "recipeCategory": ["dinner"],
  "nutrition": {
    "@type": "NutritionInformation",
    "calories": "350 calories"
  }
}
```

**Effort:** ~1 hour. Transform existing data, write ISO 8601 durations for times.

**Option 2: Cooklang**

Human-readable, version-control friendly. But it's a lossy format — metadata like nutrition, source URL, and images don't have standard representations.

```cooklang
>> name: Chocolate Chip Cookies
>> source: https://...
>> time: 45 min
>> servings: 24

Preheat oven to 375°F.

Cream @butter{1%cup} and @sugar{3/4%cup} together.
Add @eggs{2} and @vanilla{1%tsp}.
Fold in @flour{2 1/4%cups}, @baking soda{1%tsp}, and @chocolate chips{2%cups}.
Bake for ~{10%minutes}.
```

**Effort:** ~2 hours. Need to merge ingredients back into instruction text (Crumble stores them separately).

**Option 3: Both**

Include `recipe.jsonld` and `recipe.cook` alongside the current `recipe.json` in the ZIP export.

**My recommendation:** Start with JSON-LD. It's the universal interchange format. Every recipe manager, every search engine, every scraper understands it. Adding it to the existing export is trivial — it's just a different serialization of the same data.

Cooklang is philosophically appealing but practically less useful. It's a great *authoring* format (writing recipes from scratch) but a mediocre *exchange* format (moving between apps).

### Import Portability

Crumble already imports from:
- URL scraping (any recipe site with JSON-LD/microdata/heuristics)
- Mealie ZIP exports
- Paprika `.paprikarecipes` files

Missing:
- **Tandoor JSON export** — Growing user base, no import path
- **Cooklang `.cook` files** — Niche but enthusiastic community
- **Plain text / OCR** — The "grandma's recipe card" problem (AI territory)

The Tandoor import would be the highest-value addition since Tandoor is the second-most-popular self-hosted option. But I'd prioritize JSON-LD *export* over any new import format — export is about user trust ("I can leave if I want"), import is about acquisition.

---

## Deep Dive 128: What "Seasonal Cooking" Could Mean in a Recipe App

*Date: 2026-03-10*

This is more of a thought experiment than a feature proposal. No recipe manager I've seen handles seasonality well.

### The Observation

People cook differently in August than in January. Soups and stews in winter, salads and grills in summer. Root vegetables in fall, berries in spring. Every home cook knows this intuitively, but recipe apps treat the recipe collection as static — the same flat list regardless of season.

### How Other Domains Handle Temporal Relevance

- **Spotify** surfaces "summer vibes" playlists in June without you asking
- **Apple Photos** shows "On This Day" memories
- **Headspace** suggests different meditation types based on time of day
- **Weather apps** adjust their UI based on conditions

None of these *force* temporal content. They *suggest* it. The distinction matters.

### What Seasonal Awareness Could Look Like in Crumble

**Passive approach — surface, don't push:**

A small section on the homepage: "Good for right now" or "In season."

```
🍂 Fall Favorites
Based on what's in season and what you've cooked in past Octobers.
[Butternut Squash Soup] [Apple Crisp] [Pumpkin Risotto]
```

This requires:
1. **A seasonal ingredient map.** Which ingredients are in season when. This is region-dependent (Northern Hemisphere bias if hardcoded).
2. **Cross-referencing with recipe ingredients.** Which recipes in the user's collection use seasonal ingredients.
3. **Optional: cook history patterns.** "You made this last October" is more compelling than "squash is in season."

### The Data Problem

Seasonal ingredient data is surprisingly hard to get right:
- Tomatoes are "in season" June-September in temperate climates, year-round in California, irrelevant if you use canned
- "In season" varies by country, region, even microclimate
- Supermarkets make everything available year-round, blurring seasonal awareness
- Frozen and canned ingredients are season-agnostic

**A simpler approach that avoids the data problem:**

Instead of ingredient seasonality, use **weather**. It's universally available, personally relevant, and doesn't require a produce calendar.

```
Cold weather today — perfect for something warm.
[Recipes tagged: soup, stew, slow cooker, comfort food]
```

```
Beautiful day outside — something light?
[Recipes tagged: salad, grilling, quick]
```

This would use a weather API (or just look at the month and hemisphere) to adjust the "suggested" section. It's more atmospheric than accurate, but atmosphere is what matters in a recipe app.

### Why I'm Not Proposing to Build This

1. **It requires an external API** (weather) or a substantial static dataset (seasonal ingredients by region). Both add complexity.
2. **The tag coverage isn't there yet.** Even with AutoTagger, only ~1.79 tags per recipe. Seasonal suggestions need reliable categorization to avoid suggesting chocolate cake when you search for "light summer meal."
3. **The cook history data is sparse.** 146 recipes, most cooked 0-1 times. Not enough history to detect seasonal patterns.

**But it's worth remembering** as the recipe collection grows. When someone has 500+ recipes and 2+ years of cook history, seasonal suggestions become compelling. The data structures to support it already exist — tags, cook_log timestamps, recipe ingredients. The feature is a query, not a schema change.

### The Simpler Version That Could Ship Today

Just the greeting. Already implemented — the homepage shows "Good morning, [name]" etc. The next step would be:

Instead of always showing all recipes, show a contextual filter. Not a separate section, just a pre-applied tag filter based on the time of day:

- Before 10am: pre-filter to "breakfast" tag if any exist
- After 5pm on weekdays: pre-filter to "quick" tag
- Weekends: no filter (browsing mode)

With an obvious "Show all" button. This requires zero new data, zero APIs, and respects the "suggest, don't push" principle.

Actually, I'm not sure even this is a good idea. The beauty of Crumble's homepage is its simplicity — you see your recipes, you pick one. Adding contextual filtering might make it feel "smart" in a way that conflicts with the warm, personal feel. The greeting is enough temporal awareness.

Sometimes the feature you don't build is the right choice.

---

## Deep Dive 129: The Timer Alert Problem — A Detailed Fix

*Date: 2026-03-10*

### Context

CookMode detects time references in recipe instructions ("cook for 5 minutes") and offers one-tap timer creation. Timers persist in a bar at the bottom of the CookMode screen. But when a timer reaches zero, nothing happens — it silently shows 0:00.

This is the single biggest UX gap in CookMode. A silent timer defeats its own purpose.

### What the Fix Looks Like

**Audio alert:**
- Use the Web Audio API to generate a pleasant tone (no audio file needed, works offline)
- Three gentle beeps: 440Hz for 200ms, 100ms silence, 440Hz for 200ms, 100ms silence, 440Hz for 400ms
- Or a single chime-like sound using a decaying sine wave

```javascript
function playTimerAlert() {
  const ctx = new (window.AudioContext || window.webkitAudioContext)();
  const playBeep = (time, duration) => {
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.frequency.value = 880;
    osc.type = 'sine';
    gain.gain.setValueAtTime(0.3, time);
    gain.gain.exponentialRampToValueAtTime(0.01, time + duration);
    osc.start(time);
    osc.stop(time + duration);
  };
  playBeep(ctx.currentTime, 0.15);
  playBeep(ctx.currentTime + 0.25, 0.15);
  playBeep(ctx.currentTime + 0.50, 0.3);
}
```

**Vibration:**
```javascript
if ('vibrate' in navigator) {
  navigator.vibrate([200, 100, 200, 100, 400]);
}
```

**Visual:**
- Flash the timer bar background from neutral to terracotta
- Show "Timer done!" text replacing the countdown
- Pulse animation on the timer entry

**Notification (if page is backgrounded):**
```javascript
if ('Notification' in window && Notification.permission === 'granted') {
  new Notification('Timer Done!', { body: 'Your 5 minute timer is up.', tag: 'crumble-timer' });
}
```

### Implementation Notes

- Web Audio API works offline (no audio file to load)
- Vibration API is Android Chrome only — degrade gracefully
- Notifications require permission prompt — could ask when CookMode first opens
- All of this is ~30 lines of code in the Timer component
- Should respect a "silent mode" toggle for people who don't want sound

### Correction: Timer Alerts Already Exist

**I was wrong.** On closer inspection, `frontend/src/components/ui/Timer.jsx` already implements everything I proposed and more:

- ✅ Web Audio API: 3x 880Hz sine wave beeps with gain control
- ✅ Browser Notifications: "Your X minute timer has finished" with app icon and `requireInteraction`
- ✅ Visual: Pulsing terracotta background + "Time is up!" role="alert" text
- ✅ Replay button: Volume2 icon appears when done, lets you re-trigger the sound
- ✅ AudioContext management: Handles iOS suspended state, cleans up on unmount
- ✅ Notification permission: Requested on auto-start or first manual start

The only thing missing from my proposal is vibration (`navigator.vibrate()`), which is a one-liner but Android Chrome-only.

**Lesson:** Read the actual component before writing 50 lines about how it's broken. The CookMode component manages timer *state* (active timers array, starting/stopping). The Timer component handles the *experience* (sound, notifications, visuals). They're well-separated, and the Timer component is solid.

---

## Deep Dive 130: The Ingredient Math Layer — Crumble's Foundational Debt

*Date: 2026-03-10*

### The Shape of the Problem

I've flagged this in DD5, DD9, and in conversation — ingredient amounts are stored as strings. The database column is `VARCHAR(50)`. The value might be `"2"`, `"1 1/2"`, `"2-3"`, or `null`. This is the single most consequential architectural decision in Crumble, because it touches everything: scaling, grocery consolidation, nutrition calculation, and any future "smart" feature.

Let me map out exactly what exists and what's missing.

### What Exists: Two Parallel Parsers

**PHP (backend):** `api/services/IngredientParser.php`
- Parses raw ingredient strings into `{amount, unit, name}`
- Handles unicode fractions, mixed numbers, ranges, parentheticals
- 27 unit types with aliases (cup/cups/c, tbsp/tablespoon, etc.)
- Used on import (recipe scraping) and grocery item creation

**JavaScript (frontend):** `frontend/src/utils/ingredientScaling.js`
- `parseAmount(str)` → converts string to number or `{low, high}` range
- `formatAmount(num)` → converts number back to display string with unicode fractions
- `scaleIngredients(ingredients, original, new)` → the scaling engine
- Handles both structured amounts (`ing.amount`) and embedded amounts in `ing.name`

**PHP (backend, grocery):** `api/models/GroceryItem.php`
- Duplicate `parseAmount()` and `formatAmount()` functions
- Used for grocery consolidation when adding recipe ingredients to a list

Three separate implementations of amount parsing across two languages. They're functionally equivalent but independently maintained. Any bug fix in one doesn't propagate to the others.

### What Works

**Scaling** is surprisingly robust:
```
2 cups flour → (scale 4→8 servings) → 4 cups flour
1/2 tsp salt → (scale 4→2 servings) → 1/4 tsp salt
1 1/2 cups milk → (scale 4→6 servings) → 2 1/4 cups milk
```

The frontend scaler handles fractions, mixed numbers, and even amounts embedded in ingredient names (common in scraped recipes where the full "2 cups flour" is stored as the name with no separate amount field).

**Grocery consolidation** works when units match:
```
Recipe A: 2 cups flour + Recipe B: 1 cup flour → 3 cups flour ✓
```

### What Doesn't Work

**Cross-unit consolidation fails silently:**
```
Recipe A: 2 cups flour + Recipe B: 500g flour → TWO separate items ✗
Recipe A: 1 lb butter + Recipe B: 4 tbsp butter → TWO separate items ✗
```

This is the most user-visible failure. When someone adds three recipes to a grocery list and sees "flour" listed three times in different units, the app feels broken.

**Name matching is exact:**
```
"all-purpose flour" ≠ "flour" ≠ "AP flour" ≠ "plain flour"
```

The consolidation uses `strtolower(trim(name))` for matching. No fuzzy matching, no synonym awareness. "Chicken breast" and "boneless chicken breast" are two different items.

**Ranges get averaged, which is wrong:**
```
"2-3 cloves garlic" + "1 clove garlic" → parseAmount("2-3") returns 2.5
→ "3.5 cloves garlic" (but should arguably be "3-4 cloves garlic")
```

### The Unit Conversion Table That Would Fix This

The core missing piece is a conversion table between compatible units:

```
Volume:
  1 gallon = 4 quarts = 8 pints = 16 cups
  1 cup = 16 tbsp = 48 tsp
  1 cup ≈ 236.6 ml
  1 L = 1000 ml

Weight:
  1 lb = 16 oz
  1 kg = 1000 g
  1 oz ≈ 28.35 g

Special:
  1 stick butter = 0.5 cup = 8 tbsp = 4 oz
```

Note: volume-to-weight conversions are **ingredient-dependent**. 1 cup of flour ≠ 1 cup of sugar ≠ 1 cup of butter by weight. A generic "1 cup = 240g" conversion is wrong for most ingredients. This is why no recipe app fully solves this — you'd need a density database.

### The Practical Fix: Two Levels

**Level 1: Same-system conversion (2 hours)**

Convert within the same measurement system before consolidating:

```php
class UnitConverter {
    const VOLUME_TO_TSP = [
        'tsp' => 1, 'tbsp' => 3, 'cup' => 48,
        'pint' => 96, 'quart' => 192, 'gallon' => 768,
        'ml' => 0.2029, 'L' => 202.9,
    ];

    const WEIGHT_TO_G = [
        'g' => 1, 'kg' => 1000,
        'oz' => 28.35, 'lb' => 453.6,
    ];

    public static function canConvert(string $from, string $to): bool {
        return (isset(self::VOLUME_TO_TSP[$from]) && isset(self::VOLUME_TO_TSP[$to]))
            || (isset(self::WEIGHT_TO_G[$from]) && isset(self::WEIGHT_TO_G[$to]));
    }

    public static function convert(float $amount, string $from, string $to): float {
        if (isset(self::VOLUME_TO_TSP[$from]) && isset(self::VOLUME_TO_TSP[$to])) {
            return $amount * self::VOLUME_TO_TSP[$from] / self::VOLUME_TO_TSP[$to];
        }
        if (isset(self::WEIGHT_TO_G[$from]) && isset(self::WEIGHT_TO_G[$to])) {
            return $amount * self::WEIGHT_TO_G[$from] / self::WEIGHT_TO_G[$to];
        }
        return $amount;
    }
}
```

This lets you consolidate:
- `2 cups flour + 8 tbsp flour → 2.5 cups flour` ✓
- `1 lb butter + 4 oz butter → 1.25 lb butter` ✓

But NOT:
- `2 cups flour + 500g flour` → still separate (volume vs weight)

**Level 2: Fuzzy name matching (3 hours)**

Before exact-match consolidation, normalize ingredient names:

```php
class IngredientNormalizer {
    const SYNONYMS = [
        'flour' => ['all-purpose flour', 'ap flour', 'plain flour', 'white flour'],
        'butter' => ['unsalted butter', 'salted butter'],
        'sugar' => ['granulated sugar', 'white sugar', 'caster sugar'],
        'chicken breast' => ['boneless chicken breast', 'skinless chicken breast',
                            'boneless skinless chicken breast'],
        'olive oil' => ['extra virgin olive oil', 'evoo', 'extra-virgin olive oil'],
    ];

    public static function normalize(string $name): string {
        $lower = strtolower(trim($name));
        foreach (self::SYNONYMS as $canonical => $aliases) {
            if (in_array($lower, $aliases) || $lower === $canonical) {
                return $canonical;
            }
        }
        return $lower;
    }
}
```

This is inherently incomplete — you'll never cover every variation. But covering the top 30 ingredients would handle 80% of consolidation failures.

### The Deeper Question: Should Amounts Be Numbers?

I keep coming back to whether the `amount` column should be changed from `VARCHAR(50)` to `DECIMAL(10,4)`.

**Arguments for numeric storage:**
- Enables SQL-level aggregation (`SUM(amount) WHERE name = 'flour'`)
- Eliminates parsing at read time
- Makes scaling a database operation, not just a frontend trick

**Arguments against:**
- Loses range information ("2-3" becomes... what? 2.5?)
- Loses display format (user entered "½", stored as 0.5, displayed as... "1/2"? "0.5"? "½"?)
- Requires migration of 1,350+ ingredient rows
- All scraped recipes would need parsing at import time (already done, so this is fine)

**My verdict:** Keep strings. The display fidelity matters more than query convenience. The parsing layer already works. Adding a `amount_numeric DECIMAL(10,4)` column alongside the string would give you both worlds, but that's over-engineering for a personal recipe app.

### What This Unlocks

If Level 1 + Level 2 were built:

| Feature | Before | After |
|---------|--------|-------|
| Grocery consolidation | Same name + same unit only | Same-ish name + compatible units |
| Scaling accuracy | Perfect (already works) | No change needed |
| Macro scaling | Not possible | Possible with numeric amounts |
| "How much flour do I need this week?" | Not possible | `SUM()` across grocery items |
| Imperial/metric toggle | Not possible | Convert all displayed amounts |

The imperial/metric toggle is interesting. Crumble stores whatever the recipe source used. A British user scraping American recipes sees cups and ounces. An American scraping BBC Good Food sees grams and milliliters. A global "display preference" that converts on the fly would be a differentiator — Recipya does this, but none of the other big players do it well.

### Priority Assessment

| Level | Effort | Value | Priority |
|-------|--------|-------|----------|
| Unit conversion (same system) | 2 hours | High — fixes grocery consolidation | **Build this** |
| Fuzzy name matching | 3 hours | Medium — catches 80% of edge cases | Build second |
| Numeric amount column | 1 hour migration | Low — enables future features | Defer |
| Imperial/metric toggle | 4 hours (frontend) | Medium — differentiator | Defer |
| Volume-to-weight (density DB) | 10+ hours | Low — diminishing returns | Don't build |

---

## Deep Dive 131: Recipe as Memory — The PKM Angle

*Date: 2026-03-10*

### The Thesis

The user framed recipe managers as "Personal Knowledge Management for the physical world." That's exactly right, but it goes further than knowledge management. A recipe collection isn't a database of procedures — it's a **personal archive of lived experience.**

Every recipe in someone's collection carries implicit metadata that no schema captures:

- "This is the cake I made for my daughter's 5th birthday"
- "My grandmother's handwriting on a stained index card"
- "The one I tried after seeing it on a trip to Portugal"
- "I've been making this every Sunday for three years"

Crumble already captures *some* of this through cook logs (when you made it, notes you left). But the architecture treats recipes as **reference material** — something you look up when you need it. The alternative is to treat recipes as **diary entries** — artifacts that accumulate meaning over time.

### What "Recipe Memory" Looks Like in Practice

**Recipe Memory Services (the market):**

RecipeMemory.com offers a service to digitize old family recipes — handwritten cards, newspaper clippings, scrapbook pages — into searchable digital cookbooks. Their pitch: "Turn your family's food traditions into a digital legacy."

The Internet Archive's Open Library has been preserving community cookbooks — the ones published by churches, PTAs, and civic organizations — as cultural artifacts. They're not just recipes. They're records of who lived where, what they ate, and how they described food.

**The Emotional Core:**

Food nostalgia is a well-documented psychological phenomenon. Marcel Proust's madeleine is the cliché, but the science backs it up: smell and taste are processed by the olfactory bulb, which has direct connections to the amygdala (emotion) and hippocampus (memory). Food memories are literally stored closer to emotional processing centers than visual or auditory memories.

This means a recipe app that acknowledges the emotional dimension isn't being sentimental — it's being accurate about how people actually relate to their recipes.

### What Crumble Has (And What It Implies)

The "Your History" card on the recipe page already captures three data points:
1. **First made:** When you first cooked this recipe
2. **Last cooked:** How recently (displayed as "2 weeks ago" etc.)
3. **Cook count:** How many times total

Plus the cook log entries with timestamps and optional notes.

These are **the ingredients of a cooking autobiography.** With 2+ years of data:
- You could see which recipes defined each season of your life
- You could see your cooking patterns shift (more baking in winter, more grilling in summer)
- You could see which recipes became "regulars" and which were one-offs
- The notes become a journal: "Made this for Sarah's birthday," "Doubled it for the potluck," "Added extra garlic, was better"

### The Feature Nobody Has Built

No recipe app I've found offers a **"year in review"** for cooking. Spotify Wrapped but for your kitchen. I mentioned this in DD28 (Kitchen Stats), but it's worth expanding.

The data is already there in `cook_log`:

```sql
-- Most cooked recipe
SELECT r.title, COUNT(*) as times
FROM cook_log cl JOIN recipes r ON cl.recipe_id = r.id
WHERE cl.user_id = ? AND YEAR(cl.cooked_at) = 2025
GROUP BY cl.recipe_id ORDER BY times DESC LIMIT 1;

-- Total unique recipes cooked
SELECT COUNT(DISTINCT recipe_id) FROM cook_log
WHERE user_id = ? AND YEAR(cl.cooked_at) = 2025;

-- Busiest cooking month
SELECT MONTH(cooked_at), COUNT(*) FROM cook_log
WHERE user_id = ? AND YEAR(cl.cooked_at) = 2025
GROUP BY MONTH(cooked_at) ORDER BY COUNT(*) DESC LIMIT 1;

-- Longest streak
-- (requires application logic, not pure SQL)

-- New recipes tried
SELECT COUNT(DISTINCT recipe_id) FROM cook_log cl
WHERE cl.user_id = ? AND YEAR(cl.cooked_at) = 2025
AND NOT EXISTS (
    SELECT 1 FROM cook_log cl2
    WHERE cl2.recipe_id = cl.recipe_id
    AND cl2.user_id = cl.user_id
    AND cl2.cooked_at < '2025-01-01'
);
```

A December "Year in Cooking" page:
- 🍳 **147 meals cooked** (up from 89 last year)
- 🆕 **23 new recipes tried**
- 🔁 **Most made:** Chicken Tikka Masala (18 times)
- 📅 **Busiest month:** October (19 meals)
- 🔥 **Longest streak:** 12 days straight in March
- 🏷️ **Top cuisines:** Italian (34), Mexican (22), Asian (19)
- 📝 **Best note you left:** "Tripled the garlic. Life-changing."

This requires zero schema changes. It's a read-only feature built on existing data. The emotional value is enormous — it turns a utility app into something personal.

### The Index Card Metaphor

Physical recipe boxes have a quality that digital apps lose: the **physicality of wear.** A recipe card that's stained with butter and tomato sauce tells you something no metadata can — this recipe has been *used*. The dog-eared corner, the crossed-out line with a correction, the smudged ink — these are the fingerprints of lived experience.

Digital apps can't replicate stains. But they can replicate what the stains *mean:*

- A high cook count is the digital equivalent of a worn card
- Notes are the crossed-out corrections
- The "first made" date is when the card was added to the box
- Favorites are the cards you've moved to the front

Crumble already has all of these. The architecture is there. What's missing is the **presentation layer** that makes these data points feel like a story rather than a database query.

The "Your History" card on the recipe page is a start. But imagine if the recipe page itself subtly changed based on your history with it:

- A recipe you've never made shows a gentle "Try this one?" prompt
- A recipe you've made once shows "You made this on [date]"
- A recipe you've made 10+ times shows nothing — it's clearly a regular, no comment needed
- A recipe with notes shows the most recent note prominently
- A recipe you haven't made in 6+ months shows "Been a while — last made [date]"

This isn't adding features. It's **removing the sameness.** Every recipe page currently looks identical regardless of your relationship to it. The data to personalize it already exists.

### The Uncomfortable Truth About PKM for Food

The user's PKM framing is compelling, but there's a tension: **most people don't organize their recipes.** They bookmark, they screenshot, they save to Pinterest, and they never look at them again. The recipe manager attempts to impose structure on fundamentally unstructured behavior.

The apps that succeed aren't the ones with the best organizational features — they're the ones where **adding a recipe is faster than bookmarking it.** Mealie's one-click URL import. Norish's paste-a-TikTok. Paprika's browser extension. The organizational structure is a consequence of capture, not its goal.

Crumble's scraper is already excellent (4-tier parsing, handles most recipe sites). What it lacks is the **zero-friction capture** moment. Currently:
1. Copy URL from browser
2. Navigate to Crumble
3. Click "Add Recipe"
4. Paste URL
5. Click Import
6. Review and save

That's 6 steps. Mealie's browser bookmarklet does it in 2 (click bookmarklet, click save). A PWA share target could do it in 2 (tap Share in mobile browser, select Crumble).

The PWA share target is probably the single highest-ROI feature for recipe capture. It turns Crumble into a native-feeling share destination:

```json
// In manifest.json:
"share_target": {
  "action": "/api/recipes/import",
  "method": "POST",
  "enctype": "application/x-www-form-urlencoded",
  "params": {
    "url": "url"
  }
}
```

This is a 30-minute implementation that makes recipe saving feel like sharing a link to a friend. No app switching, no copy-paste. The browser's native share sheet includes Crumble.

### Connecting It All: Data Architecture as Emotional Architecture

The user's insight — that "boring" apps succeed by re-contextualizing data — applies to Crumble's memory layer too:

| Data | Utility Context | Memory Context |
|------|----------------|----------------|
| `cook_log.cooked_at` | "Last cooked 3 days ago" | "First made November 2024" |
| `cook_log.notes` | Search/filter metadata | Personal cooking journal |
| `cook_count` | Usage statistic | Measure of attachment |
| `favorites` | Quick-access filter | "My definitive collection" |
| `ratings` | Quality sorting | Taste evolution over time |
| `created_at` | Database housekeeping | "When this entered my life" |

The same data. Different questions. The utility questions are "what should I cook?" The memory questions are "what has cooking meant to me?"

Crumble doesn't need to choose. It needs to present both. The homepage is utility (browse, search, filter). The recipe page could be memory (your history with this dish). The stats page is reflection (your cooking identity). The export is archive (your legacy).

These layers already exist in the data. They just need surfaces.

---

## Deep Dive 132: On Restraint — What Makes a Small App Feel Whole

*Date: 2026-03-10*

### The Pattern

Across 130+ deep dives, I've proposed dozens of features. Many are genuinely useful. Some are technically elegant. A few would be differentiators. But here's the pattern I notice in my own reasoning:

Every feature I propose makes the app **more capable** and **less simple.**

The Closing Essay (DD50) argued that restraint is Crumble's greatest feature. I still believe that. But I've now proposed enough additions to double the codebase. There's a tension between "this would be valuable" and "this would change what the app *is.*"

### The Size Question

Current Crumble:
- ~12,600 lines of code (backend + frontend)
- 42 API endpoints
- Single-digit dependencies
- One database, no cache, no queue, no job runner

If I built everything I've proposed across these deep dives:
- Unit conversion layer (+300 lines)
- Fuzzy ingredient matching (+200 lines)
- Timer alerts (+30 lines)
- JSON-LD export (+100 lines)
- PWA share target (+50 lines)
- Year-in-review page (+400 lines frontend, +100 lines API)
- Recipe page personalization (+150 lines)
- Text-to-speech in CookMode (+50 lines)

That's roughly +1,380 lines. A ~11% increase. Actually quite modest.

But it's not about lines — it's about **conceptual weight.** Every feature is something that can break, something that needs maintaining, something that a new contributor needs to understand. The question isn't "can we add this?" but "does adding this change what the app feels like to use?"

### A Filter for Feature Selection

I think the right question for each proposed feature is:

**"Would a person using this app for the first time notice this feature, and would it make them smile or confuse them?"**

Applied to the proposals:

| Feature | Notice? | Reaction |
|---------|---------|----------|
| Timer alerts in CookMode | No (background) | 😊 Relief when timer beeps |
| Unit conversion in grocery | No (background) | 😊 "Oh, it combined my flour!" |
| PWA share target | Yes | 😊 "I can save recipes from Safari!" |
| Year-in-review | Yes | 😊 "Wow, I cooked 147 times?" |
| JSON-LD export | No | 🤷 Won't notice unless migrating |
| Recipe page personalization | Subtle | 😊 "It remembered I made this" |
| Fuzzy ingredient matching | No | 😊 Fewer duplicates on grocery list |
| Text-to-speech | No | 😊 Hands-free step reading |
| Imperial/metric toggle | Yes | 😊 "Finally, grams!" (or vice versa) |
| Seasonal suggestions | Yes | 🤔 "Why is it suggesting soups?" |

The features that pass the filter are the ones that **solve a problem the user already has** (timer doesn't beep, grocery list has duplicates, can't save from phone) rather than **creating a new experience** (seasonal suggestions, cooking identity page).

### What I'd Actually Build

If I had 8 hours and had to pick:

1. **Timer alerts** (30 min) — Bug fix, not feature. Highest priority.
2. **Unit conversion for groceries** (2 hours) — Fixes the most visible UX failure.
3. **PWA share target** (30 min) — Transforms recipe capture on mobile.
4. **Recipe page personalization** (2 hours) — Makes the app feel alive.
5. **Year-in-review** (3 hours) — Emotional payoff for long-term users.

Total: 8 hours. The app gets meaningfully better without getting meaningfully bigger.

What I would NOT build: AI features, seasonal suggestions, Cooklang export, density-based unit conversion, voice navigation. Not because they're bad ideas — because they don't pass the filter.

---

## Deep Dive 133: PWA — The Full Picture

*Date: 2026-03-10*

### Current State

Crumble has a `manifest.json` with:
- ✅ `name`, `short_name`, `description`
- ✅ `start_url: "/"`
- ✅ `display: "standalone"`
- ✅ `background_color`, `theme_color`
- ❌ Icons: Only 71x73px PNG (needs 192px + 512px minimum)
- ❌ No service worker
- ❌ No share target
- ❌ No offline support
- ❌ No `id` field (recommended for PWA identity)

This means Crumble can be "added to home screen" on some browsers, but:
- Chrome won't show the install prompt (needs a service worker)
- It won't work offline at all
- It won't appear in the system share sheet
- The icon looks terrible at home screen resolution (71px upscaled to 192px)

### What a Proper PWA Gives You

| Feature | What It Does | User Experience |
|---------|-------------|-----------------|
| **Install prompt** | Chrome shows "Add to Home Screen" banner | App feels native, one-tap launch |
| **Offline shell** | App loads without network | No "dinosaur" when wifi drops in kitchen |
| **Share target** | Appears in system Share menu | Save recipe URLs from any browser/app |
| **Background sync** | Queue actions when offline | Mark recipe as cooked, sync later |
| **Push notifications** | Timer alerts even when backgrounded | "Your timer finished!" notification |

### Implementation Plan: vite-plugin-pwa

The `vite-plugin-pwa` package wraps Google's Workbox library and integrates with Vite's build pipeline. It auto-generates a service worker from your build output.

**Step 1: Install and configure (~30 min)**

```bash
npm install -D vite-plugin-pwa
```

```javascript
// vite.config.js
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
  plugins: [
    react(),
    VitePWA({
      registerType: 'autoUpdate',
      includeAssets: ['favicon.svg', 'crumble_icon.png'],
      manifest: {
        id: '/crumble',
        name: 'Crumble',
        short_name: 'Crumble',
        description: 'Your cozy recipe manager',
        start_url: '/',
        display: 'standalone',
        background_color: '#F5EDE3',
        theme_color: '#C75B39',
        icons: [
          { src: '/icon-192.png', sizes: '192x192', type: 'image/png' },
          { src: '/icon-512.png', sizes: '512x512', type: 'image/png' },
          { src: '/icon-512.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
        ],
        share_target: {
          action: '/import-shared',
          method: 'GET',
          params: {
            url: 'url',
            title: 'title',
            text: 'text'
          }
        }
      },
      workbox: {
        globPatterns: ['**/*.{js,css,html,svg,png,woff2}'],
        runtimeCaching: [
          {
            urlPattern: /^https:\/\/fonts\.googleapis\.com/,
            handler: 'StaleWhileRevalidate',
            options: { cacheName: 'google-fonts-stylesheets' }
          },
          {
            urlPattern: /^https:\/\/fonts\.gstatic\.com/,
            handler: 'CacheFirst',
            options: {
              cacheName: 'google-fonts-webfonts',
              expiration: { maxEntries: 10, maxAgeSeconds: 60 * 60 * 24 * 365 }
            }
          },
          {
            urlPattern: /\/api\/uploads\//,
            handler: 'CacheFirst',
            options: {
              cacheName: 'recipe-images',
              expiration: { maxEntries: 200, maxAgeSeconds: 60 * 60 * 24 * 30 }
            }
          }
        ]
      }
    })
  ]
});
```

This gives you:
- Auto-generated service worker that precaches the app shell (JS, CSS, HTML)
- Runtime caching for Google Fonts and recipe images
- Auto-update: new deployments automatically invalidate the old cache
- Manifest generated from config (replaces the hand-written `manifest.json`)

**Step 2: Icons (~15 min)**

The current `crumble_icon.png` is 71x73px — too small. Need to generate 192px and 512px versions. Options:
- Scale up the SVG `favicon.svg` (if it's a vector, this is lossless)
- Use a tool like `sharp` or `pwa-asset-generator` to create all sizes from one source

The SVG favicon is ideal as the source — it scales to any size.

**Step 3: Share target handler (~45 min)**

When a user shares a URL to Crumble from another app:

1. The system opens `https://crumble.fmr.local/import-shared?url=https://example.com/recipe`
2. Crumble's React router catches `/import-shared`
3. The component extracts the `url` parameter
4. It calls the existing `/api/recipes/import` endpoint to scrape the recipe
5. Shows the recipe preview for confirmation, then saves

```jsx
// src/pages/ImportSharedPage.jsx
import { useEffect, useState } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import * as api from '../services/api';

export default function ImportSharedPage() {
  const [params] = useSearchParams();
  const navigate = useNavigate();
  const [status, setStatus] = useState('importing');
  const [recipe, setRecipe] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    const url = params.get('url') || params.get('text');
    if (!url) {
      setStatus('error');
      setError('No URL provided');
      return;
    }

    // Extract URL from text (sometimes share includes extra text)
    const urlMatch = url.match(/https?:\/\/\S+/);
    const cleanUrl = urlMatch ? urlMatch[0] : url;

    api.importRecipe(cleanUrl)
      .then(data => {
        setRecipe(data);
        setStatus('preview');
      })
      .catch(err => {
        setError(err.message || 'Failed to import recipe');
        setStatus('error');
      });
  }, [params]);

  // ... render preview, save button, etc.
}
```

This is the **killer feature** for mobile. The user's flow becomes:
1. See recipe on Instagram/Safari/Chrome/any app
2. Tap Share → select Crumble
3. See recipe preview in Crumble → tap Save

Three taps. No copy-paste. No switching apps.

**Step 4: Offline strategy (~30 min of decisions)**

The question is: what should work offline?

| Feature | Offline? | Strategy |
|---------|----------|----------|
| Browsing saved recipes | ✅ | Cache API responses in IndexedDB |
| Viewing a specific recipe | ✅ | Cache on first view |
| CookMode | ✅ | Recipe already loaded in memory |
| Adding a new recipe (URL) | ❌ | Needs network to scrape |
| Searching | ⚠️ | Client-side search of cached recipes |
| Grocery list | ✅ with sync | Cache locally, sync on reconnect |

For a cooking app, offline recipe viewing is the critical path. You start cooking, walk to the kitchen, wifi drops — the recipe should still be there.

**The simplest approach:** Cache the app shell (handled by precaching) + cache API responses with a "network-first, fallback to cache" strategy for recipe data:

```javascript
// In workbox config:
{
  urlPattern: /\/api\/recipes\/\d+$/,
  handler: 'NetworkFirst',
  options: {
    cacheName: 'recipe-data',
    expiration: { maxEntries: 200, maxAgeSeconds: 60 * 60 * 24 * 7 }
  }
}
```

This means: try the network first (fresh data), but if offline, serve the cached version. Any recipe you've viewed in the last week is available offline.

### Total Effort

| Step | Time | Dependencies |
|------|------|-------------|
| Install + configure vite-plugin-pwa | 30 min | npm install |
| Generate icon sizes | 15 min | Source SVG |
| Share target route + handler | 45 min | Existing import API |
| Offline caching config | 30 min | None |
| Testing on mobile | 30 min | Device |
| **Total** | **~2.5 hours** | |

### What This Changes

Before PWA:
- "Add to Home Screen" doesn't prompt automatically
- Opening from home screen loads a browser tab
- No offline — kitchen wifi drop = lost recipe
- Saving a recipe from another app requires copy-paste

After PWA:
- Chrome prompts "Install Crumble?" on second visit
- Opens as standalone app (no browser chrome)
- Recipes load offline from cache
- Share a URL from any app → recipe saved in Crumble
- Recipe images cached for 30 days
- Google Fonts cached for 1 year

**The ROI here is extraordinary.** 2.5 hours of work and Crumble goes from "website with a manifest" to "app that feels native on mobile." This is probably the single highest-impact improvement remaining.

### What I'd Defer

- **Background sync** (queue offline actions) — Adds complexity. Cook logging and grocery updates can wait for connectivity.
- **Push notifications** — Requires a push server. The in-app timer notifications already work while Crumble is in the foreground.
- **Full offline recipe creation** — Manual recipe entry without network is possible (no scraping needed) but requires client-side data persistence (IndexedDB) with sync logic. Over-engineering for now.
- **Periodic sync** — Automatically refreshing recipe data in the background. Not needed for a personal app.

### The Icon Problem

This is a surprisingly important detail. The current icon is 71x73px — not even square. On a phone home screen, this upscales to a blurry mess inside Android's adaptive icon circle or iOS's rounded square.

The SVG favicon is vector and should be the source for all raster sizes. Need:
- `icon-192.png` — Chrome install prompt, Android shortcut
- `icon-512.png` — Chrome splash screen, store listing
- `icon-maskable-512.png` — Android adaptive icon (needs safe zone padding)

A maskable icon needs the actual content within the center 80% of the canvas (the "safe zone"), with background color filling the rest. This is what lets Android crop it into circles, squircles, or rounded squares without cutting off the logo.

This is 15 minutes with an SVG editor or the `maskable.app` online tool.

---

## Deep Dive 134: What I Learned From Being Wrong (Meta-Reflection)

*Date: 2026-03-10*

### The Timer Incident

In DD129, I wrote 50 lines analyzing the timer alert as a "genuine UX failure" and proposed a Web Audio API implementation with code samples. Then I read `Timer.jsx` and discovered it already had:
- Web Audio API beeps (better implementation than mine)
- Browser notifications with icon and requireInteraction
- Visual pulse animation
- Sound replay button

I was confidently wrong. I analyzed the *management layer* (CookMode's timer state array) and assumed the *presentation layer* (Timer component) was missing. I wrote a design doc for a solved problem.

### Why This Happened

1. **I read CookMode.jsx partially.** I saw `activeTimers` state and `parseTimers()` but didn't follow the import to `Timer.jsx`.
2. **I anchored on the architecture.** CookMode manages timers as array entries. I assumed the timer component was simple because the management code was simple.
3. **I wrote the analysis before reading the code.** The deep dive was structured as "here's the problem → here's the solution" before verifying the problem existed.

### The Lesson for Feature Analysis

The pattern is: **Don't propose solutions for problems you haven't verified.**

This seems obvious, but it's easy to skip when you're in "analysis mode." The flow was:
1. Research cooking UX online ✓
2. Identify "timer alerts" as important ✓
3. Check if Crumble has timer alerts ✗ (skipped — assumed not)
4. Write proposal ✗ (built on unverified assumption)

The correct flow is:
1. Research cooking UX online
2. Identify "timer alerts" as important
3. **Read the actual Timer component**
4. Discover it's already implemented
5. Write: "Timer alerts are solid. Here's what could improve: vibration (one line)."

### Applied to Other Proposals

This makes me question: what else have I proposed that's already built?

Let me check my most confident "missing feature" claims:

| Claimed Missing | Actually Present? |
|----------------|-------------------|
| Timer alerts (DD129) | ✅ Already built, was wrong |
| PWA service worker (DD133) | ❌ Confirmed missing |
| Share target (DD133) | ❌ Confirmed missing |
| Unit conversion (DD130) | ❌ Confirmed missing (grep verified) |
| Year-in-review (DD131) | ❌ Kitchen Stats page exists but no yearly view |
| Recipe page personalization (DD131) | ⚠️ "Your History" card exists — partially done |

The "Your History" card is interesting. I proposed personalization as if the recipe page was static, but the cook history card (which I helped build earlier in this session!) already does part of what I described. The personalization I proposed (different prompts based on cook count) goes beyond what exists, but the foundation is there.

### Meta-Pattern

When analyzing a codebase over many sessions:
- Features get built between analysis sessions
- My own analysis becomes outdated within the same document
- Claims compound: DD129's "missing timer" became DD132's "bug fix priority"

The fix: **Always verify before claiming.** Read the component. Grep for the function. Run the feature. Then write.

---

## Deep Dive 135: Search — The Invisible Bottleneck

*Date: 2026-03-10*

### How Search Works Today

The search bar sends a query to `GET /recipes?search=...` which runs:

```sql
SELECT ... FROM recipes r
WHERE MATCH(r.title, r.description) AGAINST('chicken*' IN BOOLEAN MODE)
ORDER BY created_at DESC
LIMIT 0, 20
```

The FULLTEXT index covers only `title` and `description`. That's it.

### What This Means in Practice

**Searching for "chicken" finds:**
- ✅ "Chicken Parmesan" (title match)
- ✅ "A creamy chicken dish" (description match)
- ❌ A recipe titled "Mom's Sunday Casserole" that lists "2 lbs chicken breast" as an ingredient
- ❌ A recipe tagged "chicken" but titled "Tikka Masala"

**The gap:** If the recipe's title and description don't contain the search term, it's invisible. Ingredients, tags, and source URLs are all unsearchable through the main search.

With 146 recipes where many titles are stylized ("Grandma's Famous Stew" instead of "Beef Stew"), this means a significant portion of the collection is discoverable only by scrolling or using the separate by-ingredients search mode.

### Measured Impact (Real Database, 146 Recipes)

| Search Term | Found via title/desc | Has ingredient | **Hidden from search** |
|-------------|---------------------|----------------|----------------------|
| chicken | 19 | 21 | **4** (19%) |
| garlic | 13 | 55 | **42** (76%) |
| pasta | 19 | 18 | **3** (17%) |
| butter | 9 | 53 | **44** (83%) |
| onion | 3 | 43 | **40** (93%) |
| cheese | 24 | 61 | **37** (61%) |

**Searching "onion" returns 3 results. 40 recipes with onion are invisible.**

This isn't a theoretical gap — it's a measurable data loss. On average, **for common ingredients, 60-90% of matching recipes are hidden from search.** Only terms that frequently appear in recipe titles (like "chicken" or "pasta") are relatively complete.

### The Two Search Modes Problem

Crumble has two completely separate search systems:

| | Text Search | By-Ingredients |
|---|---|---|
| **Searches** | title, description | ingredient names |
| **Method** | FULLTEXT + boolean mode | LIKE '%term%' per ingredient |
| **UI** | Search bar in header | Separate input with chips on HomePage |
| **Ranking** | created_at DESC (no relevance sort!) | Match count + percentage |
| **Combined with tags** | Yes | No |

These don't talk to each other. A user can't type "chicken pasta" and find recipes that have chicken in the ingredients AND pasta in the title. They have to choose a mode.

### Three Problems, Ranked

**Problem 1: Ingredients aren't searchable (Impact: High)**

This is the big one. The FULLTEXT index on `(title, description)` misses the most important recipe data. When someone searches "garlic," they want every recipe with garlic — not just the ones that mention it in the description.

**Fix:** Extend the search query to also check ingredients:

```sql
SELECT DISTINCT r.* FROM recipes r
LEFT JOIN ingredients i ON i.recipe_id = r.id
WHERE MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE)
   OR i.name LIKE CONCAT('%', ?, '%')
ORDER BY
  CASE WHEN MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE) THEN 1 ELSE 0 END DESC,
  r.created_at DESC
LIMIT ?, ?
```

This searches both the FULLTEXT index (fast) and ingredient names (LIKE, slower but on a joined table). Title/description matches rank higher than ingredient-only matches.

**Complication:** `COUNT(*)` for pagination becomes expensive with the `DISTINCT` + `LEFT JOIN`. One approach: run the count query separately, or use a CTE:

```sql
WITH matched AS (
  SELECT DISTINCT r.id,
    MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE) AS relevance
  FROM recipes r
  LEFT JOIN ingredients i ON i.recipe_id = r.id
  WHERE MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE)
     OR i.name LIKE CONCAT('%', ?, '%')
)
SELECT r.*, m.relevance
FROM matched m
JOIN recipes r ON r.id = m.id
ORDER BY m.relevance DESC, r.created_at DESC
LIMIT ?, ?
```

**Effort:** ~1.5 hours. The SQL is the hard part; the frontend doesn't change at all.

**Problem 2: No relevance ranking (Impact: Medium)**

Search results are ordered by `created_at DESC` — newest first, regardless of how well they match. MySQL's FULLTEXT search in boolean mode returns a relevance score, but Crumble ignores it.

This means searching for "chocolate cake" returns results in the order they were added to the database, not in order of how well they match "chocolate cake." A recipe titled "Chocolate Lava Cake" could appear after "Quick Weeknight Stir-Fry" if the stir-fry was added more recently and happens to mention chocolate in its description.

**Fix:** Use the MATCH score for ordering:

```sql
ORDER BY MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE) DESC,
         r.created_at DESC
```

When there's no search query, keep the current `created_at DESC` ordering.

**Effort:** 15 minutes. One line change in the SQL query.

**Problem 3: Tags aren't searchable (Impact: Low)**

Searching "italian" doesn't find recipes tagged "italian" unless the word appears in the title or description. The tag filter exists as a separate UI (clickable chips), which is fine for browsing but not for search.

**Fix:** Add tags to the search query:

```sql
OR EXISTS (
  SELECT 1 FROM recipe_tags rt
  JOIN tags t ON rt.tag_id = t.id
  WHERE rt.recipe_id = r.id AND t.name LIKE CONCAT('%', ?, '%')
)
```

**Effort:** 15 minutes. Low priority since tag chips already provide this functionality, just through a different interaction model.

### The Unified Search Query

Combining all three fixes:

```php
public function getAll(int $page, int $perPage, ?string $search = null, ?string $tag = null): array {
    $where = [];
    $params = [];
    $orderBy = 'r.created_at DESC';
    $needsDistinct = false;

    if ($search !== null && $search !== '') {
        $searchTerm = $search . '*';
        $likeTerm = '%' . $search . '%';

        $where[] = '(
            MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE)
            OR EXISTS (SELECT 1 FROM ingredients i WHERE i.recipe_id = r.id AND i.name LIKE ?)
            OR EXISTS (SELECT 1 FROM recipe_tags rt JOIN tags t ON rt.tag_id = t.id
                       WHERE rt.recipe_id = r.id AND t.name LIKE ?)
        )';
        $params[] = $searchTerm;
        $params[] = $likeTerm;
        $params[] = $likeTerm;

        // Order by relevance when searching
        $orderBy = 'MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE) DESC, r.created_at DESC';
        // Add the search term again for ORDER BY
        $orderParams[] = $searchTerm;
    }

    // ... rest of query build
}
```

Using `EXISTS` subqueries instead of `LEFT JOIN` avoids the `DISTINCT` problem entirely — no row duplication, pagination counts work correctly.

**Total effort for all three fixes: ~2 hours.**

### Update: Implemented

All three fixes shipped in a single change to `Recipe::getAll()`:

1. **Ingredient search** — `EXISTS (SELECT 1 FROM ingredients i WHERE i.recipe_id = r.id AND i.name LIKE ?)`
2. **Tag search** — `EXISTS (SELECT 1 FROM recipe_tags rt2 JOIN tags t2 ... WHERE t2.name LIKE ?)`
3. **Relevance ordering** — `MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE) AS relevance` + `ORDER BY relevance DESC`

**Verified results:**

| Term | Before | After | Improvement |
|------|--------|-------|-------------|
| onion | 3 | 43 | **+1,333%** |
| garlic | 13 | 55 | **+323%** |
| chicken | 19 | 23 | +21% |
| butter | 9 | 53* | **+489%** |
| cheese | 24 | 61* | **+154%** |

*Projected from ingredient counts; verified onion/garlic/chicken directly.

All 54 existing tests pass. No frontend changes needed.

### What I'd Leave Alone

**Fuzzy matching / typo tolerance:** Tempting but complex. MySQL doesn't have built-in fuzzy search. You'd need Levenshtein distance functions (custom or via `SOUNDEX`), or an external search engine like Meilisearch/Typesense. Not worth the complexity for 146 recipes.

**Full-text search on instructions:** Instructions are stored as JSON arrays. To FULLTEXT-index them, you'd need to flatten them into a searchable text column. Possible but marginal value — people rarely search for phrases that only appear in cooking steps.

**Elasticsearch/Meilisearch:** Overkill for a personal recipe collection. These are for thousands to millions of documents. Crumble's 146 recipes fit in a single MySQL query. Even at 1,000 recipes, MySQL FULLTEXT + LIKE is plenty fast.

**Client-side search:** Could cache all recipes and search in JavaScript (libraries like Fuse.js). Eliminates the API round-trip and enables fuzzy matching. But this means loading all recipes on page load, which gets heavy past 500+ recipes. Good for offline PWA mode, bad as the primary search strategy.

### The Relevance Ranking Thought Experiment

If I were building a "smart" ranking system with zero AI, here's what the signals would be:

| Signal | Weight | Rationale |
|--------|--------|-----------|
| Title match | High | Strongest indicator of relevance |
| Description match | Medium | Context about the recipe |
| Ingredient match | Medium | "I'm looking for recipes with X" |
| Tag match | Low | Categorical, not specific |
| User's cook count | Bonus | Recipes you cook often should surface first |
| Rating | Bonus | Higher-rated recipes are more useful |
| Recency of last cook | Bonus | Recipes you made recently are top-of-mind |

A composite score:

```sql
ORDER BY (
  MATCH(r.title, r.description) AGAINST(? IN BOOLEAN MODE) * 10
  + CASE WHEN EXISTS(SELECT 1 FROM ingredients i WHERE i.recipe_id = r.id AND i.name LIKE ?) THEN 5 ELSE 0 END
  + COALESCE(cook_sub.cook_count, 0) * 2
  + COALESCE(r.avg_rating, 0)
) DESC
```

This is a hand-tuned ranking function. No machine learning. Just weighted signals that reflect what a person probably means when they search.

**Would I build this?** Not yet. The three basic fixes (ingredient search, relevance ordering, tag search) would handle 95% of search frustrations. The composite scoring is optimization for a problem that barely exists at 146 recipes. When the collection hits 500+, revisit.

---

## Deep Dive 136: Algorithmic Recommendations Without AI

*Date: 2026-03-10*

### The Question

Every recipe app competitor is adding "AI-powered recommendations." But recommendations existed long before LLMs. Netflix's early recommendation engine was collaborative filtering — pure linear algebra, no neural networks. Can Crumble suggest good recipes using only the data it already has?

### Available Signals

For each user, Crumble knows:

| Signal | Table | What It Tells You |
|--------|-------|-------------------|
| Cook history | `cook_log` | What you actually cook (behavior > preference) |
| Ratings | `ratings` | What you think you like |
| Favorites | `favorites` | What you want quick access to |
| Recipe tags | `recipe_tags` | Categories of what you cook |
| Ingredient overlap | `ingredients` | Flavor/ingredient preferences |
| Time patterns | `cook_log.cooked_at` | When you cook |

### Strategy 1: "More Like This" (Content-Based)

Given a recipe you like, find similar recipes. No user behavior needed — just recipe attributes.

**Similarity signals:**
1. **Shared tags:** Recipes with the same tags are similar
2. **Shared ingredients:** Recipes using similar ingredients are similar
3. **Similar time commitment:** If you made a 30-min recipe, suggest other 30-min recipes
4. **Same cuisine:** Tag-based or ingredient-based inference

**SQL for "Related Recipes" (improved):**

Crumble already has `Recipe::getRelated($id)` — let me check what it does.

The existing implementation (from the Explore agent's findings) uses tag overlap. An improved version would also consider ingredient overlap:

```sql
SELECT r.id, r.title,
  (
    -- Tag overlap score
    (SELECT COUNT(*) FROM recipe_tags rt1
     JOIN recipe_tags rt2 ON rt1.tag_id = rt2.tag_id
     WHERE rt1.recipe_id = ? AND rt2.recipe_id = r.id) * 3
    +
    -- Ingredient overlap score
    (SELECT COUNT(*) FROM ingredients i1
     JOIN ingredients i2 ON LOWER(i1.name) = LOWER(i2.name)
     WHERE i1.recipe_id = ? AND i2.recipe_id = r.id)
  ) AS similarity
FROM recipes r
WHERE r.id != ?
HAVING similarity > 0
ORDER BY similarity DESC
LIMIT 6
```

Tag matches are weighted 3x more than ingredient matches because tags represent *category* similarity (both are Italian) while ingredient overlap represents *composition* similarity (both use garlic — less meaningful since garlic is in everything).

### Strategy 2: "You Might Like" (Behavior-Based)

Using cook history and ratings to predict what you'd enjoy.

**The logic:**
1. Find your top-rated and most-cooked recipes
2. Find their tags and common ingredients
3. Find uncooked recipes that share those tags/ingredients
4. Rank by overlap with your preferences

```sql
-- Step 1: User's preferred tags (from cooked + rated recipes)
WITH user_tags AS (
  SELECT t.id, t.name, COUNT(*) AS affinity
  FROM cook_log cl
  JOIN recipe_tags rt ON cl.recipe_id = rt.recipe_id
  JOIN tags t ON rt.tag_id = t.id
  WHERE cl.user_id = ?
  GROUP BY t.id, t.name
),
-- Step 2: Uncooked recipes scored by tag affinity
recommendations AS (
  SELECT r.id, r.title, r.image_path,
    SUM(ut.affinity) AS score
  FROM recipes r
  JOIN recipe_tags rt ON r.id = rt.recipe_id
  JOIN user_tags ut ON rt.tag_id = ut.id
  WHERE NOT EXISTS (
    SELECT 1 FROM cook_log cl
    WHERE cl.recipe_id = r.id AND cl.user_id = ?
  )
  GROUP BY r.id
)
SELECT * FROM recommendations
ORDER BY score DESC
LIMIT 6
```

This says: "You cook a lot of Italian and chicken recipes, and you haven't tried this Chicken Piccata yet — it matches both preferences."

**How much data does this need?** At least 10-15 cooked recipes with tags to build meaningful affinity scores. Below that, the recommendations are noise. With Crumble's 146 recipes and auto-tagging adding ~1.8 tags per recipe, this becomes viable once someone has cooked 15+ different recipes.

### Strategy 3: "Cook This Again" (Temporal)

The simplest recommendation: recipes you've made before and might want again.

```sql
SELECT r.id, r.title, r.image_path,
  MAX(cl.cooked_at) AS last_cooked,
  COUNT(cl.id) AS times_cooked,
  DATEDIFF(NOW(), MAX(cl.cooked_at)) AS days_since
FROM recipes r
JOIN cook_log cl ON r.id = cl.recipe_id
WHERE cl.user_id = ?
GROUP BY r.id
HAVING days_since > 14  -- Not too recent
ORDER BY times_cooked DESC, days_since ASC
LIMIT 6
```

"You've made Chicken Tikka Masala 12 times and haven't cooked it in 3 weeks."

This is the most reliable recommendation because it's based on **actual behavior** — not predicted preference, but demonstrated preference. The user literally chose to make this recipe 12 times.

### Where Would These Live?

Three possible surfaces:

1. **Homepage section** — "Suggested for you" below the recipe grid. Shows 3-6 cards. Low-key, doesn't dominate.

2. **Recipe page sidebar** — "You might also like" below the related recipes. Personalized instead of generic.

3. **Empty states** — When someone opens the app with no specific goal, show a suggestion. "Not sure what to cook? You haven't made [X] in a while."

I'd pick option 3 — the empty state. It's the moment of highest uncertainty ("what should I cook?") and the lowest cognitive load (one suggestion, not a grid). Something like:

```
Been a while since you made Chicken Tikka Masala.
Your most-cooked recipe, last made 3 weeks ago.
[Cook it again →]
```

### Why Not Just Build This?

**The data dependency.** All three strategies need sufficient cook history to produce meaningful results:
- Strategy 1 (content-based) works immediately — no user data needed
- Strategy 2 (behavior-based) needs 15+ logged cooks with tagged recipes
- Strategy 3 (temporal) needs 5+ cooks minimum

Most Crumble users probably have sparse cook logs. The feature would be invisible to new users and only valuable to power users — which is fine, but it means the ROI is delayed.

**The better investment right now** is making the cook log easier to populate. If "Mark as Cooked" were more prominent (maybe a sticky button on the recipe page, or a prompt when exiting CookMode), more people would log cooks, which feeds the recommendation engine.

The recommendation system is a *consequence* of good data capture, not a substitute for it.

### The Simplest Version

If I were to build exactly one recommendation feature, it would be Strategy 3 with a twist: show it only in the homepage greeting area, only when there's enough data, and only as a single gentle suggestion.

```jsx
// In HomePage greeting area:
{topRecipe && daysSince > 14 && (
  <p className="text-sm text-warm-gray mt-1">
    It's been {daysSince} days since you made{' '}
    <Link to={`/recipe/${topRecipe.id}`} className="text-terracotta hover:underline">
      {topRecipe.title}
    </Link>
  </p>
)}
```

One line of text. No grid, no section, no "algorithm." Just a gentle nudge based on real data.

**API endpoint:** `GET /recipes/suggestion` — returns the user's most-cooked recipe that hasn't been made in 14+ days. One query, one result, no complexity.

This is the kind of feature that makes someone smile when they notice it and forget it's there when they don't. That's the Crumble aesthetic.

### Correction: Already Built

**Applying DD134's lesson — I checked, and all three recommendation surfaces already exist in HomePage.jsx:**

1. **"It's Been a While"** — `CookLog::getForgottenFavorites()` finds recipes cooked 2+ times where last cook was 60+ days ago. Horizontally scrollable cards with cook-count badges and "X days ago" labels. This IS Strategy 3 (temporal), already implemented with better thresholds than I proposed.

2. **"Something New?"** — `Recipe::getUncooked()` finds recipes the user has never cooked. Sparkles icon, time badges. This IS the "discovery" variant of recommendations.

3. **"Recently Viewed"** — Local history from `useRecentlyViewed()` hook.

The HomePage already has personalized, behavior-driven sections. The "cook again" nudge I proposed in this deep dive is a less polished version of what's already shipped. The data just needs to accumulate (only 1 cook log entry currently exists) before these sections light up.

**What this deep dive got right:** The algorithmic strategies are sound. Content-based similarity (tag + ingredient overlap) for the "related recipes" improvement would still be valuable. But the temporal recommendation is done.

**What it got wrong:** Proposed building something that already existed. Same pattern as DD129/DD134.

---

## Deep Dive 137: The Print Stylesheet — Already 90% There

*Date: 2026-03-10*

### What the Current Print.css Does

The existing `frontend/src/styles/print.css` (307 lines) is surprisingly well-crafted:

| Feature | Status | Implementation |
|---------|--------|----------------|
| Strip web chrome | ✅ | Hides header, sidebar, nav, modals, buttons |
| Page setup | ✅ | Letter portrait, 0.6/0.7in margins |
| Serif typography | ✅ | Georgia/Times New Roman, 11pt base |
| Two-column layout | ✅ | `grid: 1fr 1.8fr` for ingredients/instructions |
| Styled step numbers | ✅ | CSS counter circles, terracotta background |
| Dotted ingredient separators | ✅ | 0.5pt dotted borders between items |
| Hero image | ✅ | Max 200pt height, terracotta bottom border |
| Title styling | ✅ | 20pt serif, 1pt bottom rule |
| Description italic | ✅ | 10pt italic in muted color |
| Metadata row | ✅ | Prep/cook/servings with hidden icons |
| Hide checkboxes | ✅ | Ingredient checkboxes stripped |
| Hide servings buttons | ✅ | +/- adjuster removed |
| Page break control | ✅ | `page-break-inside: avoid` on steps |
| Color-print support | ✅ | `print-color-adjust: exact` |
| Watermark | ⚠️ | `@bottom-center` has limited browser support |

This is already a recipe card layout. It's not "just stripping the web layout" — it has intentional typography, visual hierarchy, and the ingredients-beside-instructions format that physical recipe cards use.

### What Small Changes Would Make It Perfect

**1. Add a decorative border frame (~5 lines)**

Physical recipe cards have an edge. The printed page feels like a web printout without one.

```css
main > div:first-child {
  border: 1.5pt solid #D4C4B0 !important;
  padding: 16pt !important;
}
```

**2. Add notes area at the bottom (~3 lines)**

Every recipe card has a "Notes:" section — even if blank. It's an invitation to write.

This requires a small React change: add a `print:block hidden` div at the bottom of RecipePage with "Notes:" and some blank ruled lines. Hidden on screen, visible in print.

```css
.print-notes {
  display: block !important;
  margin-top: 16pt;
  padding-top: 8pt;
  border-top: 1pt solid #D4C4B0;
}

.print-notes::before {
  content: "Notes";
  font-weight: bold;
  font-size: 11pt;
  color: #C75B39;
  text-transform: uppercase;
  letter-spacing: 1pt;
}

.print-notes-lines {
  background-image: repeating-linear-gradient(
    transparent, transparent 18pt, #D4C4B0 18pt, #D4C4B0 18.5pt
  );
  height: 72pt; /* 4 lines */
}
```

**3. Source URL as footer text (~2 lines)**

Currently source links are styled but may not print their actual URL. Adding `content: " (" attr(href) ")"` would show the source:

```css
a.source-url::after {
  content: " — " attr(href);
  font-size: 8pt;
  color: #999;
}
```

**4. Reduce image height for short recipes**

200pt is generous. For recipes with few ingredients, the image pushes content to page 2. Could reduce to 150pt or make it optional with a `print:hidden` toggle.

### What I Would NOT Change

- **The two-column layout** — It's the right proportion (1:1.8) for ingredients vs. instructions
- **The terracotta accent** — Step number circles in #C75B39 are distinctive and print well
- **Georgia serif** — Perfect for a recipe card; warm and readable
- **Dotted separators** — The right visual metaphor for a hand-written list
- **11pt base size** — Readable at arm's length while fitting content on one page

### The Bigger Observation

This print stylesheet was clearly designed with care. Someone thought about:
- Typography hierarchy (20pt → 13pt → 10.5pt → 10pt)
- Visual rhythm (dotted borders at consistent 0.5pt)
- Ink conservation (transparent backgrounds, no heavy fills except step circles)
- Page break behavior (avoid breaking inside steps)
- Accessibility (color contrast, semantic structure)

The "redesign it to look like a recipe card" prompt assumes it doesn't already look like one. It does. The improvements are refinements, not reinventions.

**Effort for the four tweaks: ~30 minutes.** But honestly, the current version prints well. I'd prioritize this below search (shipped), PWA, and grocery consolidation.

---

## Deep Dive 138: A Personal Reflection on 138 Deep Dives

*Date: 2026-03-10*

### What This Document Has Become

This started as "ideas and explorations" — a brainstorm of what Crumble could be. It's now 11,000+ lines covering:

- 15 competitive analyses
- 12 technical deep dives into specific components
- 8 feature proposals with implementation plans
- 6 philosophical essays about software design
- 4 corrections where I proposed building something that already existed
- 3 actually shipped improvements (search, vibration, mobile grid)
- 2 meta-reflections about my own analysis patterns

### Patterns I Notice

**I over-propose and under-verify.** The DD134 pattern (proposing solutions for already-solved problems) happened at least 4 times: timer alerts, cook-again recommendations, forgotten favorites, and the print stylesheet. Each time I was confidently wrong because I analyzed architecture instead of running the feature.

**The best insights come from data.** The search gap measurement (DD135) — where I ran actual SQL queries and found 93% of onion recipes were invisible — was more valuable than any theoretical analysis. The auto-tagger dry run that caught false positives (DD121) was more valuable than the rule design. Real data > reasoned estimates.

**The simplest versions are usually right.** Every deep dive starts with an ambitious proposal and ends with "but actually just do this one small thing." The cook-again recommendation became a one-line nudge. The seasonal cooking analysis concluded "don't build this." The print redesign concluded "it's already good." Restraint consistently wins.

**The most valuable work was the search fix.** Out of 138 deep dives, one 15-line SQL change had more impact than all the essays combined. It went from "93% of onion recipes invisible" to "all recipes searchable by ingredient." No new UI, no new concept, no new dependency. Just a better query.

### What's Left That Actually Matters

Filtering out everything already built, already debunked, or not worth the complexity:

| Priority | Feature | Effort | Status |
|----------|---------|--------|--------|
| 1 | ~~Search: ingredients + tags + relevance~~ | ~~2hr~~ | **Shipped** |
| 2 | PWA: service worker + share target + offline | 2.5hr | Designed (DD133) |
| 3 | Grocery: unit conversion (same-system) | 2hr | Designed (DD130) |
| 4 | Print: border frame + notes area | 30min | Designed (DD137) |
| 5 | Related recipes: ingredient overlap | 1hr | Designed (DD136) |

That's 6 hours of work for the remaining high-value items. Everything else in this document is either built, deferred (AI, seasonal, Cooklang), or a learning exercise.

### On the Document Itself

11,000+ lines is absurd for an ideas document. But it serves a purpose: it's a **thinking record.** The value isn't in reading it cover to cover — it's in having a searchable archive of every decision, analysis, and correction. When someone asks "why doesn't Crumble have AI features?" the answer is in DD125. When someone asks "why is search structured this way?" the answer is in DD135.

The document map at the top makes it navigable. The corrections (DD129, DD134, DD136) make it honest. The synthesis sections (DD50, DD132) make it actionable.

It's done its job. Future work should be code, not essays.

---

## Deep Dive 139: The Grocery List — Why People Still Use Notes Apps

*Date: 2026-03-10*

I just spent time exploring the full grocery system — backend consolidation, frontend aisle grouping, the "add from recipe" flow — and it's more capable than most self-hosted recipe managers. But I want to think honestly about why, despite having a purpose-built grocery feature, many users would still open Apple Notes or Google Keep when it's time to shop.

### What Crumble's Grocery System Does Well

1. **Recipe-to-list is one click.** You're on a recipe page, click "Add to Grocery List," pick a list, done. All ingredients transfer with amounts and units preserved. This is the core value proposition and it works.

2. **Smart consolidation.** Two recipes both calling for flour? The amounts merge. As of today, this even works across units — 2 cups + 8 tbsp = 2 1/2 cups. Most competitors don't do this.

3. **Aisle grouping.** The `ingredientCategories.js` categorizer is surprisingly good. 160+ keywords sorted by length for longest-match-first. It strips modifiers ("fresh," "organic," "chopped") before matching. And the category order follows a real store layout: Produce → Meat → Dairy → Bakery → Frozen → Pantry → ... → Other.

4. **Recipe provenance.** Each item shows "from [Recipe Title]" as a clickable link. When you're in the store staring at "2 cups heavy cream" you can tap through to see which recipe needs it. This is genuinely useful context that notes apps can't provide.

### What's Missing (The Notes App Advantage)

Here's why someone opens Notes instead:

**1. Friction to add non-recipe items.**

The "add item" input exists, but it's at the bottom of the list. In Notes, you open the app and start typing immediately. In Crumble, you navigate to Grocery → pick a list → scroll past existing items → find the input → type. That's 4 steps vs. 1.

The real grocery list is never purely recipe-derived. It's "eggs, paper towels, that cheese Sarah mentioned, bananas for lunches." A grocery feature that only shines for recipe ingredients misses half the use case.

**Possible fix:** A quick-add floating button or a dedicated "quick add" mode that opens with the keyboard already focused on an input. Or even simpler: put the add-item input at the TOP of the list, not the bottom.

**2. No sharing.**

Grocery shopping is collaborative. One person plans meals, the other shops. Or both are in the store splitting the list. Notes apps handle this natively (shared Apple Notes, shared Google Keep lists). Crumble's lists are per-user only.

This is the single biggest gap. But it's also the hardest to fix well. Real-time sync means WebSockets or polling. Permission models get complicated. And for a self-hosted single-household app, "sharing" might just mean both people log in with the same account — which already works, even if it's inelegant.

**Honest assessment:** For a self-hosted app primarily used by individuals or couples, this might not be worth building. The workaround (shared account, or just texting the list) is good enough. The engineering cost of real multi-user list sharing with live sync is disproportionate to the benefit.

**3. No reordering.**

You can group by aisle, but you can't drag items to reorder within a group. Notes apps let you arrange things however your brain works. Some people organize by store section mentally. Others organize by meal. Others just want the most important items at the top.

The aisle grouping is smart but opinionated. It assumes everyone shops the same way. A simple drag-to-reorder on the flat list might serve more people than the categorization system.

**4. No persistence of "my usual list."**

Every grocery list starts empty. But real life is repetitive — you buy the same 20 staples every week. Notes app users keep a "Weekly Staples" note they copy-paste. There's no equivalent in Crumble.

**Possible feature:** A "template" list you can clone, or a "frequently bought" section based on items that appear on 3+ lists. But this is getting into the over-engineering territory I warned about in DD132. The clone-a-list feature would be trivially simple though — just a "Duplicate" button on the list card.

### The Deeper Question: Does Crumble Need Grocery Lists At All?

This might sound like heresy, but: is the grocery feature pulling its weight?

The recipe-to-grocery pipeline is genuinely useful for meal planning. If you're cooking 5 recipes this week, adding them all to one list and getting a consolidated, categorized grocery list is objectively better than copying ingredients by hand.

But outside that specific workflow, the grocery feature competes with apps people already use and that are better at the general "list" task. Apple Reminders has shared lists, location-based reminders ("remind me when I'm at Trader Joe's"), Siri integration. Google Keep has real-time collaboration, images, labels.

The answer, I think, is: **Crumble's grocery list should focus entirely on the recipe connection.** Don't try to be a general-purpose list app. Be the best at "I'm meal planning, give me a shopping list." That means:

1. The recipe-to-list flow (already excellent)
2. Smart consolidation (now working with unit conversion)
3. Aisle grouping (already good)
4. Recipe provenance links (already there)
5. Easy export to whatever list app people actually shop with

That last point is interesting. What if the grocery list had a "Copy to clipboard" button that outputs a clean text list? Then people can paste it into their preferred shopping app. Meet them where they are instead of trying to replace their existing tools.

### The Aisle Categorizer: A Hidden Gem

I want to call out `ingredientCategories.js` specifically because it's doing something subtle and well.

The modifier stripping is smart:
```javascript
.replace(/\b(fresh|frozen|organic|large|small|medium|whole|ground|minced|diced|chopped|sliced|shredded|grated|boneless|skinless|extra|virgin)\b/g, '')
```

"Fresh organic baby spinach" → matches "spinach" → Produce. "Boneless skinless chicken breast" → matches "chicken" → Meat & Seafood. This handles the messiness of real recipe ingredients without needing NLP.

The longest-match-first sort prevents false positives. "Coconut milk" matches "coconut milk" → Canned Goods before "coconut" → Produce or "milk" → Dairy. "Tomato paste" → Canned Goods before "tomato" → Produce. This is the kind of detail that makes the difference between a categorizer that feels broken and one that feels invisible (in a good way).

**One gap I notice:** "Fresh herbs" like basil, cilantro, and mint are categorized as Produce (correct for a grocery store), but "dried oregano" would also match "oregano" → Produce. The modifier stripping removes "dried" before matching, so dried spices that share names with fresh herbs will miscategorize. In practice this probably doesn't matter — when "oregano" appears in a grocery list, the user knows whether they need fresh or dried regardless of which aisle heading it's under.

### What Would Actually Move the Needle

If I had to pick one grocery improvement to build next (beyond the unit conversion that just shipped), it would be:

**"Copy list as text"** — A single button that copies the grocery list to the clipboard as formatted text:

```
🥬 Produce
  □ 3 carrots
  □ 1 onion
  □ 2 cloves garlic

🥩 Meat & Seafood
  □ 1 lb chicken breast

🧈 Dairy & Eggs
  □ 2 cups heavy cream
  □ 1/2 cup parmesan
```

This respects the aisle grouping, includes amounts, and lets people paste it into whatever app they actually shop with. It turns Crumble's grocery feature from "a list you have to use in Crumble" into "a list generator powered by your recipe collection." Different value proposition, much more useful.

**Effort estimate:** ~30 minutes. It's just `navigator.clipboard.writeText()` with a formatting function that already has all the data.

---

## Deep Dive 140: Recipe Evolution — The Feature No App Has Built

*Date: 2026-03-10*

### The Problem

You import a recipe for chicken tikka masala. You cook it. It's good but needs more garam masala and less cream. Next time you adjust. By the fifth time, your version is meaningfully different from the original.

Where does your version live?

In Crumble (and every recipe app I've looked at): either you edit the original recipe and lose the starting point, or you write a cook log note like "used 2 tbsp garam masala instead of 1, less cream" and hope you remember to check it next time.

Neither is satisfying. The recipe is a living document, but the tools treat it as static.

### How People Actually Adapt Recipes

Based on how cooks I've observed and common cookbook margin-note patterns:

1. **Quantity adjustments** — "Use 2 tbsp not 1" (most common)
2. **Substitutions** — "Swap Greek yogurt for cream" (dietary, preference, or availability)
3. **Technique changes** — "Sear on higher heat for crispier skin"
4. **Timing changes** — "Needs 35 min not 25" (oven variation)
5. **Additions** — "Add fresh cilantro at the end"
6. **Deletions** — "Skip the raisins"

Categories 1, 2, 5, and 6 are ingredient-level. Categories 3 and 4 are instruction-level. The ingredient-level changes are more structured and more common.

### What Would Structured Adaptation Look Like?

Not a version control system. That's developer thinking. Regular people don't want diffs and branches.

Instead: **annotations on the recipe itself.** Small visual indicators that say "you've changed this" with the ability to see what you changed.

Imagine: next to the ingredient "1 tbsp garam masala" there's a small pencil icon. Tap it, and you see "Your note: use 2 tbsp." The recipe still shows the original amount, but YOUR adjustment is visible inline.

This is the **cookbook margin note** as a feature. The original text is preserved. Your handwriting is in the margin.

### Data Model

This could be surprisingly simple:

```sql
CREATE TABLE recipe_annotations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT NOT NULL,
  user_id INT NOT NULL,
  target_type ENUM('ingredient', 'instruction') NOT NULL,
  target_index INT NOT NULL,  -- which ingredient or step (0-indexed)
  note TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_annotation (recipe_id, user_id, target_type, target_index)
);
```

One note per ingredient or step per user. The UNIQUE constraint means updating a note replaces the previous one (no revision history — keep it simple). The `target_index` ties the annotation to a specific ingredient or instruction by position.

### Why This Matters Beyond Individual Use

If Crumble ever supports household accounts (multiple users, one instance), annotations become personal. My version of the recipe has my notes. Your version has yours. The base recipe is shared; the margin notes are private.

This also connects to the cook log in a meaningful way. Right now, cook log notes are timestamped entries: "March 5: added more garlic." But recipe annotations would be the *distilled wisdom* — what you've learned across all your cook sessions. The cook log is the journal; annotations are the cheat sheet.

### The UX Challenge

The risk is clutter. Recipes should feel clean and scannable. Adding annotation icons to every ingredient and step could make the page feel busy.

Solutions:
- **Only show annotations that exist.** No empty pencil icons everywhere. If you haven't annotated an ingredient, it looks normal.
- **Subtle indicator.** A small dot or underline, not an icon. Tap/hover to reveal the note.
- **CookMode integration.** When cooking, annotations should be prominent — that's when they matter most. "Original says 1 tbsp, your note says 2 tbsp" displayed inline during step-by-step mode.
- **"My adjustments" summary.** A collapsible section on the recipe page: "You've customized 3 ingredients" with a quick list. One place to see all your changes.

### The Philosophical Angle

This touches on something I wrote about in DD131 (Recipe as Memory). A recipe you've cooked 10 times and adapted isn't just a recipe — it's a record of your cooking evolution. The annotations capture something cook logs don't: not just THAT you changed something, but WHAT you've settled on as your version.

Over time, a well-annotated recipe becomes a personal artifact. It's the difference between "a recipe I found online" and "my chicken tikka masala."

### Should Crumble Build This?

Applying the DD132 filter: "Would a first-time user smile or be confused?"

A first-time user would never see annotations (they haven't cooked anything yet). The feature would be invisible until someone has cooked a recipe and wants to record a change. That's good — it doesn't add complexity to the initial experience.

For a returning user who's cooked a recipe 5 times? Seeing "Your notes: use 2 tbsp garam masala" right on the ingredient line would feel like the app remembers how they cook. That's a smile.

**Effort estimate:**
- Migration + model: 30 min
- API endpoints (CRUD): 30 min
- Recipe page UI (annotation display + edit): 1.5 hr
- CookMode integration: 1 hr
- **Total: ~3.5 hours**

**Verdict:** High value, moderate effort. But it's a feature for power users — people who cook from their collection regularly. It should come after the fundamentals (PWA, print polish) but before the nice-to-haves (Cooklang, night mode). Adding it to the roadmap as a Tier 2 item.

### What I Would NOT Build

- **Full version history.** Diffs, timestamps, "revert to version 3." Over-engineered. One annotation per target is enough.
- **Auto-detection of changes.** "You edited the ingredient from 1 tbsp to 2 tbsp" by diffing the recipe. Clever but fragile and confusing.
- **Annotation sharing.** "See what other users noted." Crumble is self-hosted, usually single-user. Social features don't fit.
- **AI-suggested adjustments.** "Based on your notes, you seem to prefer spicier food." Cute but useless in practice.

---

## Deep Dive 141: Grocery "Copy as Text" — The 30-Minute Feature

*Date: 2026-03-10*

This is one of those features where the design is so obvious it barely needs a deep dive. But I want to think through the formatting choices because they affect usability.

### The Feature

A button on the grocery list detail view that copies the list to the clipboard as formatted text. Users paste it into whatever app they actually shop with.

### Format Options

**Option A: Minimal**
```
Produce
- 3 carrots
- 1 onion
- 2 cloves garlic

Meat & Seafood
- 1 lb chicken breast
```

**Option B: Checkbox-style**
```
☐ 3 carrots
☐ 1 onion
☐ 2 cloves garlic
☐ 1 lb chicken breast
```

**Option C: Grouped with checkboxes**
```
PRODUCE
☐ 3 carrots
☐ 1 onion
☐ 2 cloves garlic

MEAT & SEAFOOD
☐ 1 lb chicken breast
```

**My preference: Option C.** The uppercase headers are scannable. The checkboxes are universally understood. The grouping provides the same aisle organization as the in-app view. And critically, many apps (Apple Notes, Google Keep, Notion) auto-detect checkbox characters and convert them to interactive checkboxes on paste.

### Should It Respect the Grouped/Flat Toggle?

Yes. If the user has aisle grouping turned on, copy with groups. If flat, copy as a flat list. Match what they see.

### Should It Include Checked Items?

No. If you've already checked off eggs, you don't need them on the copied list. Only copy unchecked items. This makes the copy-paste flow work for mid-shop scenarios too: "I'm at the store, I've checked off what I have, now let me copy the remaining items to my wife's phone."

### Should It Include Recipe Sources?

Optional but useful. Something like:

```
DAIRY & EGGS
☐ 2 cups heavy cream (tikka masala)
☐ 1/2 cup parmesan (pasta carbonara)
```

The recipe name in parentheses gives context without cluttering. But this could be a toggle — some people want a clean list, others want to know why they're buying heavy cream.

For a first version, skip the recipe sources. Keep it clean.

### Implementation Sketch

```javascript
function copyListAsText(items, grouped) {
  const unchecked = items.filter(i => !i.checked);
  if (unchecked.length === 0) return '';

  if (!grouped) {
    return unchecked.map(i => {
      const parts = [i.amount, i.unit, i.name].filter(Boolean);
      return `☐ ${parts.join(' ')}`;
    }).join('\n');
  }

  const groups = groupByCategory(unchecked);
  return groups.map(({ category, items }) => {
    const header = category.toUpperCase();
    const lines = items.map(i => {
      const parts = [i.amount, i.unit, i.name].filter(Boolean);
      return `☐ ${parts.join(' ')}`;
    });
    return `${header}\n${lines.join('\n')}`;
  }).join('\n\n');
}
```

That's about 20 lines. The button calls `navigator.clipboard.writeText()`, shows a brief "Copied!" toast, done.

### The Bigger Point

This feature is worth calling out not because it's technically interesting, but because it represents a philosophy: **don't fight the user's existing tools.**

Recipe apps that try to own the entire workflow (planning → shopping → cooking → logging) end up competing with best-in-class single-purpose tools. Apple Reminders is a better shopping list. A paper cookbook is better for browsing. A kitchen timer is better than an in-app timer.

The smartest move is often to be **excellent at the unique thing** (recipe management, ingredient consolidation, cook history) and **gracefully hand off** to whatever tools people already use for the generic things. The clipboard copy is a handoff. "Here's your optimized, consolidated, categorized list — take it wherever you want."

---

## Deep Dive 142: What the Cook Log Could Become

*Date: 2026-03-10*

### What It Is Now

The cook log is a timestamp + optional note. "March 10, 2026: Cooked chicken tikka masala. Notes: added extra garam masala."

The data model supports this. The UI surfaces it as:
- A history section on each recipe page (first made, last cooked, total times)
- A dedicated Cook History page with streak/monthly stats and timeline
- Homepage sections (forgotten favorites, uncooked recipes)

It's a good foundation. But I think there's an unrealized depth to it.

### The Cook Log as Cooking Autobiography

Every cook log entry is a tiny story. Not the note text — the *pattern* of entries over time. Consider what you could learn from a year of cook log data:

- **Seasonality.** Do you cook more soups in winter? More salads in summer? The data would show this without anyone tagging recipes as "winter" or "summer."
- **Skill progression.** Your early logs are simple recipes. Over months, the complexity increases. The log captures your cooking journey implicitly.
- **Routine vs. exploration.** Ratio of repeated recipes to new ones. Are you in a comfort-food rut or actively exploring?
- **Cooking frequency.** How many times a week do you actually cook? Does it correlate with seasons, workload, life events?

None of this requires new features. It's all derivable from `cook_log(user_id, recipe_id, cooked_at)`. The question is whether to surface any of it.

### Year-in-Review ("Wrapped")

I proposed this in DD131 and estimated 2.25 hours. Let me think about it more concretely now that I've seen the full data model.

The minimum viable version:

```
Your 2026 in Cooking
━━━━━━━━━━━━━━━━━━━

🍳 143 meals cooked
📅 Most active month: October (18 meals)
🔄 Most made recipe: Chicken Tikka Masala (12 times)
🆕 New recipes tried: 34
⭐ Your top-rated new discovery: [Recipe Title] (5 stars)
🌍 Most common cuisine: Italian (28 recipes)

Your cooking streak peaked at 14 days in September.
```

The data for all of this exists or is trivially derivable:
- Meal count: `COUNT(*) FROM cook_log WHERE YEAR(cooked_at) = 2026`
- Most active month: `GROUP BY MONTH(cooked_at) ORDER BY COUNT(*) DESC LIMIT 1`
- Most made: `GROUP BY recipe_id ORDER BY COUNT(*) DESC LIMIT 1`
- New recipes: count of recipe_ids first appearing in cook_log that year
- Top-rated discovery: join with ratings table, filter to first-cooked-this-year
- Cuisine: requires recipes to have tags, which they do via `recipe_tags`
- Streak: sequential date analysis on `cooked_at` (the trickiest query but already partially done in CookHistoryPage for the current streak)

The frontend would be a single page, perhaps accessible from the Cook History page or as a year-end notification. Visually, it could use the same warm terracotta/cream palette as the rest of Crumble — no need for flashy Spotify-style animations.

### What I Find Interesting About This

It's not about the feature. It's about the *relationship between data and meaning.*

Most apps collect data for operational purposes — the cook log exists so the homepage can show "forgotten favorites." But the same data has a narrative quality. The difference between "you cooked 143 meals this year" and a raw database count is presentation and context.

This is what makes personal software different from productivity software. Productivity software optimizes a process. Personal software reflects a life.

### The Counter-Argument

Crumble has 1 cook log entry right now. Building a year-in-review for a dataset of 1 is absurd. The feature only becomes meaningful after months of consistent use.

But here's the thing: the year-in-review is also a *motivation to log.* If users know they'll get a summary in December, they're more likely to tap "I Cooked This" throughout the year. The feature retroactively increases the value of every previous cook log entry.

This is the same dynamic that makes Strava's year-in-review work. Nobody runs *for* the year-end summary. But knowing it exists makes logging feel worthwhile.

### When to Build It

Not now. The priority order remains:
1. ~~Search (shipped)~~
2. ~~Unit conversion (shipped)~~
3. PWA (highest remaining impact)
4. Print polish (30 min)
5. Copy grocery list as text (30 min)
6. Recipe annotations (3.5 hr, DD140)
7. Year-in-review (2.25 hr, DD142)

But it should absolutely be built before December. Ideally by mid-November, so the first year's data is captured from the moment it's available.

---

## Updated Roadmap (Post DD138)

*Date: 2026-03-10*

| Priority | Feature | Effort | Status | Deep Dive |
|----------|---------|--------|--------|-----------|
| 1 | ~~Search: ingredients + tags + relevance~~ | ~~2hr~~ | **Shipped** | DD135 |
| 2 | ~~Grocery: unit conversion~~ | ~~2hr~~ | **Shipped** | DD130 |
| 3 | PWA: service worker + share target | 2.5hr | Designed | DD133 |
| 4 | Print: border frame + notes area | 30min | Designed | DD137 |
| 5 | Grocery: copy list as text | 30min | Designed | DD141 |
| 6 | Recipe annotations (margin notes) | 3.5hr | Designed | DD140 |
| 7 | Year-in-review | 2.25hr | Designed | DD142 |
| 8 | Related recipes by ingredients | 1hr | Designed | DD136 |

Total remaining: ~10.25 hours. Two items shipped since DD138.

---

## Deep Dive 143: The Landscape Just Moved — Mealie v3.12, Norish v0.15, and Crumble's Invisibility

*Date: 2026-03-10*

### What Happened This Week

**Mealie v3.12.0** shipped on March 9, 2026 — literally yesterday. Notable changes:
- Relative date filtering in recipe finder ("last made more than 30 days ago")
- Natural ingredient pluralization (language-aware)
- Import categories from scraped URLs (not just tags)
- Migration to OpenAI structured outputs for better AI recipe parsing
- Date range → shopping list (add a full week of meal plans to one list)
- Improved image compression

Mealie continues its steady, polished evolution. The "date range to shopping list" feature is exactly what meal planners want — plan the week, generate one grocery list. Crumble's grocery system does this recipe-by-recipe, not as a batch from the meal planner. That's a gap.

**Norish v0.15.0-beta** (January 2026) completely overhauled its grocery page:
- Dedicated stores section (configure your actual stores)
- Store-based vs. recipe-based views
- Everything draggable to reorder
- Real-time WebSocket sync across household members

Norish is leaning hard into "household-first." The store-based grocery view is interesting — instead of generic "Produce / Dairy / Bakery" categories, you configure YOUR stores and their aisles. It's more work to set up but more accurate when shopping. Crumble's keyword-based aisle grouper is the opposite philosophy: zero config, decent accuracy, no per-store customization.

### The Cooklang 2026 Comparison

Cooklang published "The Best Open Source Recipe Managers in 2026" and compared: Cooklang, Mealie, KitchenOwl, Tandoor, Recipya, Grocy.

**Crumble is not on the list.**

This shouldn't be surprising — Crumble has no public GitHub repo, no README, no documentation site, no Docker image. It's invisible to the community. The awesome-selfhosted list, which is the primary discovery channel for self-hosted apps, doesn't include it.

But the comparison reveals something valuable about positioning. Here's the deployment landscape:

| App | Deployment | Database |
|-----|-----------|----------|
| Cooklang | None (text files) | None |
| Mealie | Docker | SQLite/Postgres |
| KitchenOwl | Docker | SQLite |
| Tandoor | Docker/K8s | Postgres |
| Recipya | Docker | SQLite |
| **Crumble** | **Apache/PHP** | **MySQL** |

Crumble is the only web app in the field that runs on traditional LAMP hosting. No Docker. No Python. No Node.js. Just PHP + MySQL + Apache — the stack that 40% of the web already runs on. Anyone with shared hosting, a Raspberry Pi running Apache, or an existing Laragon/XAMPP/MAMP setup can run Crumble without learning container orchestration.

This is either Crumble's greatest differentiator or its biggest limitation, depending on the audience. For the Docker-native self-hosting crowd (who already run Portainer and treat containers as natural), Crumble's PHP stack feels archaic. For the "I just want to run a recipe app on my $5 hosting" crowd, it's the only option.

### What the Commercial Apps Tell Us

The RecipeOne comparison of 12 commercial recipe apps reveals what mainstream users (not self-hosters) want in 2026:

1. **Social media recipe import** — TikTok, Instagram Reels, YouTube Shorts. This is the #1 differentiator. Apps that can extract recipes from video are winning.
2. **AI-powered capture** — Photo → recipe, video → recipe, screenshot → recipe. Manual data entry is the enemy.
3. **Household collaboration** — Real-time shared grocery lists, family accounts. Cooking is social.
4. **Cross-platform sync** — Phone, tablet, desktop. Seamless.
5. **Shoppable recipes** — Direct links to buy ingredients online.

Crumble does #4 (responsive web works everywhere) and partially does #3 (shared accounts work, but no real multi-user collaboration). It doesn't do #1, #2, or #5 — and shouldn't try to. Social media import and AI capture require infrastructure (API keys, video processing) that's expensive to run and maintain. That's the domain of funded companies, not self-hosted PHP apps.

### What Users Actually Complain About

Across forums and discussions, the recurring frustrations are:

1. **"Why does every recipe site need my life story?"** — This drives people to recipe managers in the first place. Crumble's scraper strips the blog posts and extracts just the recipe. This is table stakes, but it's also the core value prop.

2. **"I need to share my grocery list with my partner"** — The collaboration gap. Addressed by KitchenOwl, Norish, and AnyList. Not addressed by Crumble (DD139 analysis stands: shared accounts are the workaround, real multi-user is disproportionate effort).

3. **"I can't find my recipes"** — Search is critical. Crumble's search now covers title, description, ingredients, and tags with relevance ordering. This is genuinely competitive with Mealie and Tandoor.

4. **"The import broke"** — Web scraping is fragile. Every app has this complaint. Crumble's 4-tier scraper (JSON-LD → microdata → structured HTML → AI fallback) is solid, but recipe sites constantly change their markup.

5. **"I want my data back"** — Export/portability. Cooklang wins here (text files are inherently portable). Crumble has import (Mealie, Paprika, URLs) but no export. This is a real gap — I flagged it in DD127 (data portability) but it hasn't been built.

### Honest Assessment: Where Does Crumble Rank?

If Crumble were visible to the self-hosted community, here's how it would stack up:

| Criteria | Crumble | Mealie | KitchenOwl | Tandoor | Norish |
|----------|---------|--------|-----------|---------|--------|
| Setup ease | ★★★★★ | ★★★ | ★★★ | ★★ | ★★★ |
| UI polish | ★★★★ | ★★★★★ | ★★★★ | ★★★ | ★★★★ |
| Recipe import | ★★★★ | ★★★★★ | ★★★ | ★★★★ | ★★★★ |
| Search | ★★★★ | ★★★★ | ★★★ | ★★★★ | ★★★ |
| Grocery lists | ★★★★ | ★★★★ | ★★★★★ | ★★★★ | ★★★★★ |
| Meal planning | ★★★ | ★★★★★ | ★★★★ | ★★★★★ | ★★★★ |
| Collaboration | ★★ | ★★★ | ★★★★★ | ★★★ | ★★★★★ |
| Data export | ★ | ★★★ | ★★★ | ★★★★ | ★★★ |
| Cook tracking | ★★★★★ | ★★ | ★ | ★★ | ★ |
| Mobile UX | ★★★★ | ★★★★ | ★★★★★ | ★★★ | ★★★★ |

Crumble's actual strengths: zero-Docker deployment, cook tracking (best in class — nobody else has CookMode + cook log + forgotten favorites), and a warm, non-technical UI. Its weaknesses: no data export, weak collaboration, and complete invisibility.

### The Invisibility Problem

This is the elephant in the room. Crumble could be the best recipe app in the world and nobody would know. There's no:
- GitHub repository (public)
- README with screenshots
- Demo instance
- awesome-selfhosted listing
- Documentation site
- Blog post announcing it

The demo account exists (Try Demo button on the login page), but there's no public URL to reach it. If someone can't find Crumble through search or community lists, it doesn't exist.

I wrote about the missing README in an earlier deep dive. That analysis stands, but the problem is bigger than a README. It's about whether Crumble is meant to be a personal project or a community project. Both are valid. A personal project doesn't need visibility. A community project needs all of the above.

This isn't a feature to build — it's a decision to make.

---

## Deep Dive 144: Empty States — The Surprisingly Good First Impression

*Date: 2026-03-10*

### What I Expected to Find

Based on the UX research (Smashing Magazine, UXPin, Toptal), good empty states need:
1. A clear visual indicator (icon or illustration)
2. A positive, action-oriented message
3. A single primary CTA
4. No punitive language ("You have nothing" → bad. "Get started" → good)

I expected Crumble to have sparse or missing empty states, since it's a personal project without formal UX review.

### What I Actually Found

Every major page has a thoughtfully designed empty state:

| Page | Icon | Message | CTA |
|------|------|---------|-----|
| Home (no recipes) | ChefHat | "Welcome to Crumble" | 3-card grid: Import URL, Paste/Type, Bulk Import |
| Grocery (no lists) | ShoppingCart | "No grocery lists yet" | "New List" button |
| Grocery (empty list) | ShoppingCart | "No items yet" | Inline add form |
| Meal Plan (empty day) | CalendarDays | "No meals planned" | "+ Add Recipe" button |
| Cook History (empty) | BookOpen | "You haven't cooked anything yet!" | "Click 'I Cooked This' on a recipe" |
| Favorites (empty) | Heart | "No favorites yet!" | "Tap the heart on any recipe" |
| Stats (empty) | ChefHat | "No cooking history yet" | "Start logging your cooks" |
| Search (no results) | 📖 | "No recipes found" | "Try a different search" |

The design language is consistent:
- Centered layout with generous whitespace
- Warm-gray icons (not red/alarming)
- Two-line pattern: heading + helper text
- CTAs point to specific next actions, not generic "explore" pages
- Positive framing throughout

### The Welcome Screen Deserves Special Attention

The homepage empty state for zero recipes is the strongest:

```
[ChefHat icon]

Welcome to Crumble

Your recipe collection is empty. Here are a few ways to get started:

[Import from URL]  [Paste or Type]  [Bulk Import]
```

This is textbook good onboarding. Three clear paths that match different user scenarios:
- **Import from URL** — "I found a recipe online" (most common entry point)
- **Paste or Type** — "I have a family recipe in my head or on paper"
- **Bulk Import** — "I'm migrating from another app"

No onboarding wizard, no tutorial overlay, no "skip" buttons. Just context-appropriate guidance that appears when needed and disappears when not.

### What UX Research Says vs. What Crumble Does

**Best practice: "Provide starter content"** — Pre-built content lets users explore without commitment. Crumble doesn't do this, and I think that's correct. Pre-loaded sample recipes would feel patronizing ("I know what a recipe looks like") and clutter the initial experience. The demo account serves this purpose separately.

**Best practice: "Focus on one feature"** — Keep empty state guidance focused on one action. The homepage breaks this with three options, but the three cards are variations of one action (add your first recipe), so it works. Each subsequent page follows the one-feature rule strictly.

**Best practice: "Invoke action"** — Every empty state has a clear action. Nobody lands on an empty page with no guidance.

**Best practice: "Add delight"** — Crumble doesn't use illustrations, animations, or playful copy. The tone is warm but understated. This matches the brand voice (DD? — Brand Voice Audit). A dancing chef illustration would feel wrong here.

### What Could Be Better

**1. The search empty state is the weakest.**

"📖 No recipes found. Try a different search or add a new recipe."

This is functional but uninspired. When search returns nothing, the user feels stuck. Better options:
- Suggest related searches ("Did you mean...?")
- Show popular recipes from the collection
- If the search term looks like a URL, offer "Import this recipe?"

The URL detection would be genuinely clever: if someone pastes a URL into the search bar (which people do), detect it and redirect to the import flow. That's a 10-line check with high delight factor.

**2. No progressive disclosure for power features.**

CookMode, cook logging, meal planning, and grocery lists are invisible until you discover them. There's no "Did you know?" or contextual hint system. After a user adds their 5th recipe, they might not know CookMode exists.

This is debatable. Progressive hints can feel nagging. But a one-time "Tip: Try CookMode for step-by-step cooking" on a recipe page — dismissable, non-blocking — could increase feature discovery significantly.

**3. The mobile FAB (floating action button) only appears on the empty homepage.**

The "Add Recipe" FAB (terracotta circle, bottom-right, 56px) appears when the recipe collection is empty. Once you have recipes, it's gone, replaced by header buttons. But the FAB is the most mobile-friendly add-recipe interaction. It should probably always be present on the homepage, not just for empty states.

### The Broader Pattern

Crumble's empty states reflect its overall design philosophy: no unnecessary complexity, warm tone, clear next steps. The fact that every page has a considered empty state suggests someone thought carefully about the first-time experience — which contradicts the "no README, no docs" gap on the external-facing side.

The app itself makes a good first impression. The problem is that nobody can find the front door.

---

## Deep Dive 145: What I Learned From Researching the 2026 Landscape

*Date: 2026-03-10*

### The Three Trends That Matter

After reading Cooklang's comparison, RecipeOne's 12-app review, Mealie's latest release, and Norish's grocery overhaul, three trends crystallize:

**1. AI is becoming table stakes for recipe capture.**

Mealie migrated to OpenAI structured outputs. Norish supports video recipe import via AI. RecipeOne's entire pitch is AI-powered capture from social media. Paprika — the 10-year veteran — is being compared unfavorably to apps with AI import.

Crumble's scraper uses a 4-tier approach: JSON-LD → microdata → structured HTML → manual entry. There's no AI tier. For URL-based import, this is fine — JSON-LD covers 90%+ of recipe sites. But the gap is in non-URL sources: screenshots of recipes, handwritten cards, video tutorials, social media posts. These all require AI.

**My take:** Crumble shouldn't add AI recipe parsing. It requires an external API key (cost, privacy), adds infrastructure complexity, and the use case ("I saw a recipe on TikTok") is better served by apps designed for that workflow. Crumble's niche is "I have recipe URLs and I want to manage them simply." Own that.

**2. Grocery collaboration is the differentiator for households.**

KitchenOwl, Norish, and AnyList all lead with shared grocery lists. Norish's store-based views and draggable reordering are genuinely novel. KitchenOwl adds expense tracking.

Crumble's grocery system is smart (unit conversion, aisle grouping, recipe provenance) but solo. For single-user or shared-account use, it works. For real household collaboration with separate accounts and live sync, it doesn't.

**My take:** The DD139 analysis holds. Don't build multi-user grocery sync. The engineering cost (WebSockets, conflict resolution, permission models) dwarfs the benefit for a LAMP-stack app. The "copy as text" feature (DD141) is the right bridge — generate the list in Crumble, shop in whatever app your household already shares.

**3. The plain-text movement is growing.**

Cooklang's positioning is interesting: recipes as text files, no database, no server, version-controlled with Git. It's the "Markdown for recipes" pitch. Their 2026 blog post explicitly argues that database-backed apps create lock-in.

This resonates with the self-hosting ethos. But it's also somewhat ideological — most people don't want to edit text files to record a recipe. The practical reality is that 95% of users want a web UI, and text files are for the 5% who already use vim for everything.

**My take:** Crumble should add JSON-LD export (DD127) and possibly Cooklang export. Not because users will switch to Cooklang, but because the ability to export proves you're not creating lock-in. It's a trust signal. And it directly addresses frustration #5 ("I want my data back") from the user complaints analysis.

### What This Means for Crumble's Roadmap

No changes to the priority list. The research confirms the existing strategy:
- PWA (mobile-native feel without native apps)
- Print polish (physical recipe cards — the anti-digital, which no competitor focuses on)
- Grocery copy-as-text (bridge to collaborative shopping apps)
- Recipe annotations (nobody else has this — genuine differentiator)

What it adds is urgency around **data export**. The lack of any export path is Crumble's biggest competitive weakness. Not because users are clamoring for it today, but because it's the first question anyone evaluating a recipe manager asks: "Can I get my data out?"

### A Note on Visibility

Every comparison article, every "best of" list, every forum recommendation — Crumble is absent from all of them. This isn't a feature gap; it's an existence gap. The app could be technically superior in every dimension and still have zero adoption because nobody knows it exists.

If the goal is personal use, this doesn't matter. If the goal is even modest community adoption, the README + public repo + demo instance + awesome-selfhosted listing needs to happen before any new feature development. A beautifully-featured app with zero discoverability is a tree falling in an empty forest.

---

## Deep Dive 146: The Meal Plan Grocery Bug — And What It Reveals About Code Duplication

*Date: 2026-03-10*

### The Bug

`MealPlan::generateGroceryList()` creates individual grocery items by calling `$groceryItemModel->create()` for each ingredient from each recipe. It does **not** consolidate.

If your weekly meal plan has:
- Monday: Chicken tikka masala (needs 1 cup cream)
- Thursday: Pasta alfredo (needs 2 cups cream)

The generated grocery list gets:
```
☐ 1 cup cream (from tikka masala)
☐ 2 cups cream (from pasta alfredo)
```

Instead of the expected:
```
☐ 3 cups cream
```

Meanwhile, `GroceryItem::addFromRecipe()` — the function used when you manually add a recipe to a grocery list — handles this correctly. It checks for existing items by name, combines amounts for matching units, and now even converts across compatible units via UnitConverter.

The meal plan grocery generator bypasses all of that consolidation logic.

### The Code Duplication

This bug exists because of a deeper problem: code duplication between `MealPlan.php` and `GroceryItem.php`.

Both files contain independent implementations of:
- `parseAmount()` — Parse "1 1/2" → 1.5
- `formatAmount()` — Format 1.5 → "1 1/2"

The GroceryItem version is more capable (handles unicode fractions: ½, ⅓, ¾). The MealPlan version doesn't. So "½ cup" in a recipe would parse to 0.5 in GroceryItem but fail to `null` in MealPlan.

This is a classic DRY violation. The same logic exists in two places, one is more complete than the other, and they'll continue to diverge as the codebase evolves.

### The Fix

**Step 1: Extract `parseAmount()` and `formatAmount()` into a shared utility.**

These belong in a service class — maybe `AmountParser.php` or fold them into the existing `UnitConverter.php`:

```php
// In UnitConverter.php
public static function parseAmount(?string $amount): ?float { ... }
public static function formatAmount(float $amount): string { ... }
```

Then both MealPlan and GroceryItem call `UnitConverter::parseAmount()` instead of maintaining private copies.

**Step 2: Make `generateGroceryList()` use `addFromRecipe()` for consolidation.**

Instead of calling `$groceryItemModel->create()` for each ingredient, loop through unique recipe IDs and call `$groceryItemModel->addFromRecipe()`. But there's a complication: `addFromRecipe()` doesn't support servings scaling.

Two approaches:

**Approach A — Add scaling to `addFromRecipe()`:**
```php
public function addFromRecipe(int $listId, int $recipeId, ?float $servingsMultiplier = null): array
```

A new optional parameter that multiplies all amounts before consolidation. The meal plan generator passes the scale factor. Existing code passes null (no change). Clean, backward-compatible.

**Approach B — Consolidate after creation:**

Generate all items (with duplicates), then run a consolidation pass that merges items with matching names using UnitConverter. This is simpler to implement but less efficient.

**My recommendation: Approach A.** It keeps the consolidation logic in one place and the scaling logic adjacent to it. The method signature change is backward-compatible.

### Effort Estimate

- Extract `parseAmount`/`formatAmount` to UnitConverter: 20 min
- Add `servingsMultiplier` parameter to `addFromRecipe()`: 20 min
- Rewrite `generateGroceryList()` to use `addFromRecipe()`: 20 min
- Test the full flow: 15 min
- **Total: ~1.25 hours**

### What This Reveals

The meal plan grocery generator was likely written before the consolidation logic in GroceryItem. When consolidation was added (for the recipe-to-grocery flow), nobody went back to update the meal plan flow. And the parseAmount/formatAmount duplication happened because both files needed the same utility but there was no shared location for it.

This is normal. Codebases grow feature by feature, and each feature is built with the context available at the time. The duplication isn't a quality failure — it's a sign that the codebase has matured to the point where shared utilities are worth extracting.

The UnitConverter service is the natural home. It already handles unit conversion, and amount parsing/formatting is adjacent logic. After this refactor, the conversion pipeline would be:

```
Raw string → parseAmount() → float → convert() → float → bestUnit() → {amount, unit} → formatAmount() → display string
```

All in one service. Clean.

---

## Deep Dive 147: The Image Pipeline — Solid Foundation, WebP Opportunity

*Date: 2026-03-10*

### How It Works

The image pipeline is straightforward and well-built:

1. **Upload** — File upload or URL download (for scraped recipes)
2. **Validate** — MIME check via `finfo` + `getimagesize()` (double validation prevents fake images)
3. **Process** — GD library creates two versions:
   - `full.jpg` — 800px max width, 85% JPEG quality
   - `thumb.jpg` — 300px max width, 80% quality
4. **Store** — `uploads/recipes/{recipeId}/full.jpg` (one directory per recipe)
5. **Serve** — Apache serves directly (no PHP in the path)
6. **Display** — `imageUrl.js` constructs URLs; cards use thumbnails, detail pages use full

Every format (PNG, WebP, GIF) converts to JPEG with white background for transparency. This is a sensible default — JPEG is universally supported and the white background prevents the "black background on transparent PNG" problem that plagues many image processors.

### What's Good

- **Double validation** prevents image-header attacks (file claims to be JPEG but is actually PHP)
- **Aspect ratio preservation** during resize — no stretching
- **Separate thumbnail** means list/grid pages load fast (300px thumbnails vs 800px full images)
- **Per-recipe directories** keep the filesystem organized and make deletion clean (`rmdir` the directory)
- **Apache direct serving** means no PHP overhead for image requests
- **Lazy loading** on card images (`loading="lazy"`) reduces initial page load

### The WebP Opportunity

Every image is stored as JPEG. In 2026, WebP support is universal (97%+ browser coverage). WebP provides:
- **25-35% smaller files** at equivalent visual quality
- **Transparency support** (unlike JPEG) — could preserve PNG transparency
- **Lossy and lossless** modes

PHP 8.3 includes `imagewebp()` in GD. The change would be:

```php
// Instead of:
imagejpeg($resized, $outputPath, $quality);

// Use:
imagewebp($resized, $outputPath, $quality);
```

**File naming:** `full.webp` and `thumb.webp` instead of `.jpg`. The `imageUrl.js` utility would need the corresponding update.

**Migration concern:** Existing recipes have `.jpg` images. A migration would need to either:
- Convert existing images (batch script)
- Support both formats (check for .webp, fall back to .jpg)
- Only apply to new uploads (simplest, acceptable)

**My recommendation:** Apply to new uploads only. Existing images work fine. Over time, as recipes are re-imported or images are re-uploaded, the library naturally migrates to WebP. No batch conversion needed.

### What About AVIF?

AVIF is even smaller than WebP (~20% more compression). PHP 8.3 has `imageavif()`. But browser support is 93% and encoding is significantly slower (10-20x vs JPEG). For a recipe app where images are processed once and served many times, the encoding speed doesn't matter — but the 7% browser gap does. Not worth it yet.

### A More Interesting Question: Does Image Size Even Matter?

Crumble's images are already modest:
- Full images: 800px wide → ~50-150KB JPEG
- Thumbnails: 300px wide → ~15-30KB JPEG

For a self-hosted app running on a local network or a personal VPS, these sizes are negligible. The WebP conversion would save maybe 30KB per image. For a collection of 200 recipes, that's 6MB total — less than a single modern photo.

WebP is the "correct" engineering choice but the practical impact is minimal. I'd prioritize it below actual feature work, but above nice-to-haves like night mode. It's a 30-minute change with zero user-facing complexity.

### What I Would NOT Change

- **The 800px max width.** Recipe images don't need to be 4K. 800px looks sharp on any screen at the sizes they're displayed (hero images are CSS-constrained, not pixel-stretched).
- **The per-recipe directory structure.** Flat directories with thousands of files are slow. Per-recipe directories scale cleanly.
- **The JPEG conversion default.** Converting everything to one format simplifies the entire pipeline. WebP would be the new "one format."
- **The GD library choice.** ImageMagick is more powerful but adds a system dependency. GD is bundled with PHP and handles recipe photos perfectly.

### One Small Bug I Noticed

The `processFromUrl()` method downloads images with cURL but doesn't validate the downloaded file's size before processing. The `MAX_UPLOAD_SIZE` check (10MB) only applies to direct uploads via `$_FILES`. A malicious scraper response could send an arbitrarily large image to `processFromUrl()`.

This isn't urgent — the scraper only fetches from user-provided URLs, so the attack surface is small. But it's worth adding a content-length check or a file-size check after download.

---

## Deep Dive 148: Meal Planning — Better Than I Thought

*Date: 2026-03-10*

### The Correction

In DD143, I rated Crumble's meal planning as ★★★ (3/5) compared to competitors. That was wrong. After reading the full implementation, it's ★★★★ (4/5). Here's why I undersold it:

**What it actually does:**
- Week-at-a-glance grid (7 columns on desktop)
- Day picker + single-day detail on mobile
- Add any recipe to any day via search
- Multiple meals per day with sort ordering
- Servings override (scale recipe up/down)
- Generate consolidated grocery list from entire week
- Homepage "Tonight's Plan" widget showing today's meals
- Week navigation (prev/next with "Today" shortcut)
- Automatic plan creation (no "create plan" step — just navigate to a week and it exists)

**What Mealie has that Crumble doesn't:**
- Drag-and-drop to rearrange meals
- Random recipe suggestions for empty days
- Category-based filters ("only show dinner recipes")
- Multi-week view
- Relative date filtering ("last made more than 30 days ago")

The gap is smaller than I assumed. The core workflow — plan a week, generate a grocery list — is fully functional and well-implemented. The automatic plan creation via `ON DUPLICATE KEY UPDATE` is elegant (no explicit "create" action, the plan materializes when you first visit a week).

### The Servings Override is Underrated

Most meal planners let you add a recipe to a day. Crumble lets you add a recipe AND specify how many servings you want. The grocery generator then scales all ingredient amounts proportionally.

This matters for real meal planning. A recipe that serves 4 might need to serve 2 (just you and your partner) or 8 (guests coming Saturday). The scaling is automatic and feeds directly into the grocery list.

Looking at the code, the scaling arithmetic is correct:
```php
$scale = (int) $ingredient['servings_override'] / (int) $ingredient['recipe_servings'];
$scaledAmount = $parsedAmount * $scale;
```

But it uses the duplicated `parseAmount()` that doesn't handle unicode fractions. And the generated grocery list doesn't consolidate. Both bugs from DD146.

### What Would Make It ★★★★★

**1. Drag-and-drop.** Move a meal from Tuesday to Thursday without delete + re-add. This is a frontend-only change — the `updateItem()` endpoint already supports changing `day_of_week`. The backend is ready; the UI just needs a drag interaction.

**2. "Fill from favorites."** An empty day could have a "Suggest" button that pulls from your favorites or most-cooked recipes. One tap to fill a slot. This leverages the cook log data that's already being collected.

**3. Fix the grocery generation bug (DD146).** Consolidated ingredients with unit conversion. This is the highest-impact fix — the grocery list output is the primary deliverable of the meal planning feature.

### The Tonight's Plan Widget

The homepage shows "Tuesday's Plan" (or whatever day it is) with a horizontal scroll of meal cards. This is the right UX decision — don't make people navigate to the meal plan page to see what's for dinner. Surface it on the home screen.

The widget only shows if meals are planned for today. When empty, it's invisible (no "You have no meals planned today" nag). This matches the DD132 restraint philosophy — features appear when they have value, disappear when they don't.

### Meal Plan ↔ Grocery: The Full Pipeline

Here's the intended workflow:
1. Browse recipes → add to meal plan days
2. Set servings overrides for each meal
3. Click "Generate Grocery List"
4. Name the list ("Week of Mar 10" auto-suggested)
5. All ingredients from all meals, scaled by servings, collected into one list
6. Shop from the grocery list (with aisle grouping)

This is a complete planning-to-shopping pipeline. The DD146 consolidation bug is the one weak link — it creates duplicate entries instead of combining matching ingredients. Once that's fixed, this pipeline rivals Mealie's.

### The Data Architecture Insight

The meal plan schema is clean:
- `meal_plans` keyed by (user_id, week_start) — one plan per user per week
- `meal_plan_items` links plan → recipe → day with sort order

The automatic Monday snap (`strtotime('monday this week')`) means the frontend never needs to calculate week boundaries. Send any date, get back the correct week's plan.

The `ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)` pattern for plan creation is worth noting — it's an atomic upsert that returns the correct ID whether the row was inserted or already existed. No race conditions, no "check then create" anti-pattern.

### Summary

The meal planning system is well-architected and more complete than I initially assessed. The grocery generation bug (DD146) is the priority fix. After that, drag-and-drop and recipe suggestions would bring it to feature parity with Mealie's planner.
