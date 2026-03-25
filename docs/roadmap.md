# Cookslate Roadmap

*Extracted 2026-03-10 from ideas-and-explorations.md (148 deep dives).*
*Items sorted by complexity, then by value within each tier.*

## Already Shipped

| Feature | What Changed |
|---------|-------------|
| Search: ingredients + tags + relevance | FULLTEXT + EXISTS subqueries. +1,333% onion results. |
| Grocery: unit conversion | UnitConverter.php with volume/weight tables. bestUnit display logic. |
| Mobile recipe grid | 2-column grid, compact cards, square images on mobile. |
| Timer vibration | `navigator.vibrate()` on timer completion in CookMode. |
| parseAmount/formatAmount extraction | Moved from GroceryItem to UnitConverter as shared static methods. |

---

## Low Complexity

### Print: border frame + notes area — 30 min | Value: Medium
Add decorative 1.5pt border frame around printed recipe. Add ruled "Notes:" section at bottom (print-only) for handwriting.

**Files:** `frontend/src/styles/print.css`, `frontend/src/pages/RecipePage.jsx`

### Grocery: copy list as text — 30 min | Value: High
Button on grocery list detail that copies unchecked items to clipboard. Formatted with `☐` checkboxes and category headers (respects grouped/flat toggle). ~20 lines JS using `navigator.clipboard.writeText()`.

**Files:** `frontend/src/pages/GroceryPage.jsx`

### WebP image conversion — 30 min | Value: Low
Switch `imagejpeg()` → `imagewebp()` in ImageProcessor. Update filenames `.jpg` → `.webp`. Apply to new uploads only (existing images stay as JPEG). Saves 25-35% file size.

**Files:** `api/services/ImageProcessor.php`, `frontend/src/utils/imageUrl.js`

### Recipe page dead code cleanup — 45 min | Value: Low
Remove ~150 lines of commented-out card sharing code from RecipePage.jsx. Recoverable from git. Clean up StarRating display.

**Files:** `frontend/src/pages/RecipePage.jsx`

### Scraper: nutrition + tag extraction from JSON-LD — 55 min | Value: Medium
Extract `nutrition` object fields (calories, protein, carbs, fat, fiber, sugar) and tags from `recipeCategory`/`recipeCuisine`/`keywords` in scraped JSON-LD. Auto-populates the dormant nutrition columns. ~30 lines in `mapJsonLdRecipe()`.

**Files:** `api/services/RecipeScraper.php`

### JSON-LD recipe export — 1 hr | Value: High
Export recipes as schema.org/Recipe JSON-LD. ISO 8601 durations, ingredient/instruction arrays, tag mapping. Critical trust signal — answers "can I get my data out?"

**Files:** `api/controllers/RecipeController.php`, `api/models/Recipe.php`

### Related recipes by ingredients — 1 hr | Value: Medium
Enhance `Recipe::getRelated()` with weighted scoring: (tag matches × 3) + (ingredient name matches). Case-insensitive ingredient overlap. Limit 6 results.

**Files:** `api/models/Recipe.php`

### Meal plan grocery bug — 1.25 hr | Value: High
**Bug:** `MealPlan::generateGroceryList()` calls `create()` per ingredient, bypassing consolidation. Two recipes needing flour = two separate entries. **Fix:** Add optional `servingsMultiplier` param to `addFromRecipe()`, rewrite `generateGroceryList()` to use it. Remove duplicate `parseAmount`/`formatAmount` from MealPlan.php (already extracted to UnitConverter).

**Files:** `api/models/MealPlan.php`, `api/models/GroceryItem.php`

---

## Medium Complexity

### Cooking journal UI — 1.5 hr | Value: Medium
CookButton already logs; add modal with optional notes textarea ("How'd it go?"). Display "Your Cook Notes" section on RecipePage showing previous cooks + notes in reverse chronological order.

**Files:** `frontend/src/components/recipe/CookButton.jsx`, `frontend/src/pages/RecipePage.jsx`, `api/models/CookLog.php`

### IngredientParser.js — 1.5 hr | Value: High
Port PHP IngredientParser to JS (~100 lines). Enables "Paste all ingredients" textarea in AddRecipePage — multi-line text split, parsed per-line. Removes server round-trip for ingredient parsing.

**Files:** `frontend/src/utils/ingredientParser.js`, `frontend/src/pages/AddRecipePage.jsx`

### Night mode — 1.75 hr | Value: Medium
CSS variable overrides behind `prefers-color-scheme: dark`. Warm palette (dark brown #1C1410, cream text #F5EDE3). Migrate 49 `bg-white` occurrences across 27 files. CSS-only, zero JSX logic changes. Optional manual toggle adds +30 min.

**Files:** `frontend/src/index.css` + 27 component files

### Accessibility fixes — 2 hr | Value: Medium
Focus traps in CookMode + ingredient panel. Skip navigation link. Color contrast improvements for warm-gray text. `role="alert"` on timer completion. Keyboard nav for CookMode steps.

**Files:** `frontend/src/components/recipe/CookMode.jsx`, modal components, `frontend/src/components/layout/Layout.jsx`

### CookMode bug fixes — 2 hr | Value: High
Fix timer persistence (don't clear timers on step navigation). Wire "Done!" button to cook log with optional notes modal. Auto-start timers. These are the highest-impact cooking UX fixes.

**Files:** `frontend/src/components/recipe/CookMode.jsx`, `frontend/src/components/ui/Timer.jsx`

### Year-in-review — 2.25 hr | Value: Medium
"Your 2026 in Cooking" stats page: total meals, most active month, most-made recipe, new recipes tried, streak peak, top cuisine. All derivable from existing cook_log + ratings + recipe_tags. Build before November.

**Files:** `api/models/CookLog.php`, `frontend/src/pages/YearInReviewPage.jsx`

### PWA: service worker + share target — 2.5 hr | Value: High
Install vite-plugin-pwa. Precache app shell, runtime cache recipes/images. Generate proper icons (192px + 512px). Share target for receiving recipe URLs from system share sheet. Offline-first with 7-day cache. Highest-impact remaining improvement for mobile users.

**Files:** `vite.config.js`, `frontend/public/manifest.json`, new `ImportSharedPage.jsx`, icon assets

### README + public presence — 3 hr | Value: Critical
Create README.md with one-line pitch, feature screenshots, 7-step install, tech stack badges, demo link. Create `.env.example`. Prerequisite for any community adoption. Without this, Crumble is invisible.

**Files:** `README.md`, `api/.env.example`

### Recipe annotations (margin notes) — 3.5 hr | Value: High
New `recipe_annotations` table: recipe_id, user_id, target_type (ingredient/instruction), target_index, note. CRUD endpoints. Subtle pencil icon on annotated items. CookMode displays annotations inline. The "cookbook marginalia" feature — no competitor has this.

**Files:** Migration, `api/models/RecipeAnnotation.php`, `api/controllers/RecipeController.php`, `frontend/src/pages/RecipePage.jsx`, `frontend/src/components/recipe/CookMode.jsx`

### Add recipe flow — 4 hr | Value: High
Free-text ingredient input (parse on blur). "Paste all ingredients" textarea. Auto-add row on Enter. LocalStorage draft recovery. Ingredient entry is the UX bottleneck for recipe creation.

**Files:** `frontend/src/pages/AddRecipePage.jsx`, `frontend/src/utils/ingredientParser.js`

---

## High Complexity

### Cooklang export — 2 hr | Value: Low
Convert recipes to Cooklang plain-text format. Import adds +3 hr. Export matters more for data portability messaging. Import is nice-to-have.

**Files:** `api/services/CooklangExporter.php`

### Pantry-based recipe search — 6.5 hr | Value: Medium
"What Can I Make?" feature. Pantry table tracking user-owned ingredients, search endpoint scoring recipes by pantry coverage. No competitor has this in self-hosted space. High effort, moderate value — defer until core features are solid.

**Files:** Migration, `api/models/Pantry.php`, new endpoint, new frontend page

---

## Suggested Build Order

**Quick wins (2.5 hr):** Grocery copy text → Print polish → Meal plan bug fix
**Core UX (6.5 hr):** CookMode fixes → PWA → IngredientParser.js + Add recipe flow
**Differentiators (6 hr):** Recipe annotations → Year-in-review → Cooking journal
**Polish (5 hr):** Night mode → Accessibility → JSON-LD export → README
**Deferred:** Pantry search, Cooklang, WebP
