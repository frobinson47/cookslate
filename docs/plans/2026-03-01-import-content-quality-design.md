# Import & Content Quality — Design Document

**Date:** 2026-03-01
**Status:** Approved

## Overview

Four features to improve how recipes enter and display in Crumble: structured ingredient parsing, broader scraper coverage, print-friendly output, and bulk import. Ordered by dependency — parsing is the foundation, scraper improvements run in parallel, then print and bulk import build on top.

## Roadmap

| # | Feature | Depends on | Scope |
|---|---------|-----------|-------|
| 1 | Smart Ingredient Parsing | — | Backend PHP service + scraper integration + migration endpoint |
| 2 | Improved Scraper Coverage | — (parallel with 1) | Heuristic HTML fallback, AMP/cache, better errors, UA rotation |
| 3 | Print-Friendly View | 1 | Frontend route + print CSS + print button |
| 4 | Bulk Import | 1 + 2 | Multi-URL textarea, Mealie/Paprika importers, review UI |

---

## Feature 1: Smart Ingredient Parsing

### Problem

The scraper stores full ingredient strings (e.g. "2 cups all-purpose flour") in the `name` field with empty `amount` and `unit`. This breaks serving scaling, produces messy grocery lists, and prevents any structured ingredient features.

### Solution

A PHP `IngredientParser` service that splits ingredient strings into `{amount, unit, name}`.

### Parser Design

**Input:** `"1 1/2 cups all-purpose flour"`
**Output:** `{amount: "1 1/2", unit: "cups", name: "all-purpose flour"}`

Parsing strategy (in order):
1. Trim and normalize whitespace
2. Extract leading amount: integers, decimals, fractions (`1/2`), mixed numbers (`1 1/2`), ranges (`2-3`), unicode fractions
3. Match the next token against a known unit list (with aliases)
4. Everything remaining is the ingredient name
5. If no amount is found, return the full string as `name` with null amount/unit

**Unit list (with aliases):**

| Canonical | Aliases |
|-----------|---------|
| cups | cup, c |
| tbsp | tablespoon, tablespoons, T, tbs |
| tsp | teaspoon, teaspoons, t |
| oz | ounce, ounces |
| lb | pound, pounds, lbs |
| g | gram, grams |
| kg | kilogram, kilograms |
| ml | milliliter, milliliters |
| L | liter, liters |
| clove | cloves |
| pinch | pinches |
| piece | pieces, pcs |
| can | cans |
| bunch | bunches |
| sprig | sprigs |
| slice | slices |
| stick | sticks |
| head | heads |
| dash | dashes |
| package | packages, pkg |

### Integration Points

- **RecipeScraper:** Call `IngredientParser::parse()` on each ingredient string during JSON-LD and microdata import, instead of dumping everything into `name`.
- **Import preview:** Frontend already has structured amount/unit/name fields in the recipe form. Parsed data populates these, users can correct before saving.
- **Migration endpoint:** `POST /api/admin/reparse-ingredients` — iterates all recipes where `amount` is null/empty but `name` contains a parseable amount. Re-parses and updates in place. Admin-only. Returns count of updated ingredients.

### Edge Cases

| Input | Parsed as |
|-------|-----------|
| `"salt and pepper to taste"` | `{amount: null, unit: null, name: "salt and pepper to taste"}` |
| `"2 (14 oz) cans diced tomatoes"` | `{amount: "2", unit: "cans", name: "(14 oz) diced tomatoes"}` |
| `"juice of 1 lemon"` | `{amount: "1", unit: null, name: "juice of lemon"}` or full string as name |
| `"1/2 cup butter (, cut into cubes)"` | `{amount: "1/2", unit: "cups", name: "butter (, cut into cubes)"}` |
| `""` or null | `{amount: null, unit: null, name: ""}` — unchanged |

### Files

- **Create:** `api/services/IngredientParser.php` (~100 lines)
- **Modify:** `api/services/RecipeScraper.php` — call parser in `mapJsonLdRecipe()` and `parseMicrodata()`
- **Create:** `api/controllers/AdminController.php` — add reparse endpoint (or add to existing admin routes)
- **Modify:** `api/index.php` — register admin route

---

## Feature 2: Improved Scraper Coverage

### Problem

Some recipe sites fail to import entirely — they lack JSON-LD, use non-standard microdata, render via JavaScript, or block the request.

### Solution

Multiple improvements layered into the existing scraper.

### 2a. Heuristic HTML Fallback (4th parsing strategy)

When JSON-LD, microdata, and Open Graph all fail, attempt to extract recipe content from common HTML patterns:

- **Title:** `<h1>` or `<h2>` containing the page's `<title>` text
- **Ingredients:** `<li>` elements inside containers whose heading/class/id contains "ingredient" (case-insensitive)
- **Instructions:** `<li>` or `<p>` elements inside containers whose heading/class/id contains "instruction", "direction", "method", or "step"
- **Image:** Largest `<img>` in the article/main content area
- **Times/servings:** Scan for patterns like "Prep: 15 min", "Serves 4" near the top of the page

This won't work on every site, but it catches many WordPress recipe blogs that use custom themes without structured data.

### 2b. Google AMP/Cache Fallback

For sites that render recipes via JavaScript (React/Vue SPAs), the HTML we fetch is an empty shell. Before giving up:

1. Try the Google AMP cache URL: `https://cdn.ampproject.org/c/s/{domain}/{path}`
2. Try the Google Web Cache: `https://webcache.googleusercontent.com/search?q=cache:{url}`

These return pre-rendered HTML that our existing parsers can handle. If both fail, fall through to the heuristic parser or return an error.

### 2c. Better Error Reporting

Replace the generic `"Could not parse recipe data from this URL"` with specific error codes:

| Error | Message shown to user |
|-------|----------------------|
| `fetch_failed` | "Couldn't reach this website. Check the URL and try again." |
| `fetch_blocked` | "This website blocked our request. Try copying the recipe manually." |
| `fetch_timeout` | "The website took too long to respond. Try again later." |
| `parse_failed` | "Found the page but couldn't find recipe data. You can enter it manually." |
| `invalid_url` | "This doesn't look like a valid URL." |

Return both `error_code` and `error_message` in the API response. Frontend displays the message with appropriate styling.

### 2d. User-Agent Rotation

Replace the single hardcoded Chrome UA with a small pool of realistic UAs. Pick one randomly per request:

- Chrome 120 (Windows)
- Chrome 120 (Mac)
- Firefox 121 (Windows)
- Safari 17 (Mac)
- Edge 120 (Windows)

### Files

- **Modify:** `api/services/RecipeScraper.php` — add `parseHeuristic()`, AMP/cache fetching, error codes, UA pool
- **Modify:** `frontend/src/pages/AddRecipePage.jsx` — display specific error messages from import response

---

## Feature 3: Print-Friendly View

### Problem

Users want to print recipes or save them as PDF. Currently the recipe page has navigation, buttons, and interactive elements that clutter a printout.

### Solution

A dedicated print layout triggered by a button on the recipe page, using the browser's native print dialog (which also handles save-as-PDF).

### Design

**Print button:** Add a Printer icon button to the recipe page action bar, next to "Start Cooking" and "Add to Grocery List". Clicking it calls `window.print()`.

**Print stylesheet (`@media print`):**
- Hide: navigation, header, back button, action buttons, footer, grocery modal, delete modal, cook mode, tag badges, source URL link
- Show: title, description, metadata (prep/cook/total time, servings), ingredients list, instructions list
- Layout: single column, no shadows/rounded corners, black text on white
- Typography: system serif font, 12pt body, 16pt title
- Ingredients: bulleted list, no checkboxes
- Instructions: numbered list, clear spacing between steps
- Servings: prints the currently adjusted serving count and scaled amounts — if the user scaled to 12 servings, the print reflects that
- Page break: avoid breaking inside an instruction step

**No separate route needed.** The print stylesheet applied to the existing RecipePage is sufficient — it just hides/resyles elements for print context.

### Files

- **Modify:** `frontend/src/pages/RecipePage.jsx` — add Print button
- **Create:** `frontend/src/styles/print.css` — print media query styles
- **Modify:** `frontend/src/main.jsx` or `index.css` — import print.css

---

## Feature 4: Bulk Import

### Problem

Adding recipes one URL at a time is tedious. Users migrating from other apps (Mealie, Paprika) need a way to bring their collection over.

### Solution

Two import modes: multi-URL paste and app export file upload.

### 4a. Multi-URL Import

**UI:** A new section on the Add Recipe page (or a dedicated `/import` page) with a textarea. Users paste URLs, one per line. A "Import All" button starts processing.

**Backend:** `POST /api/recipes/import-batch` accepts `{urls: string[]}`. Processes sequentially, returns an array of results:

```json
{
  "results": [
    {"url": "...", "status": "success", "recipe": {...}},
    {"url": "...", "status": "error", "error_code": "parse_failed", "error_message": "..."}
  ]
}
```

**Frontend flow:**
1. User pastes URLs, clicks Import
2. Progress indicator shows: "Importing 3 of 12..."
3. Results screen shows a table: recipe title, status (success/failed), action buttons
4. Successful recipes: "Save" (saves with parsed data) or "Review" (opens in recipe form for editing)
5. Failed recipes: "Enter manually" (opens blank form with source_url prefilled)
6. "Save All Successful" button for quick batch save

### 4b. Mealie Import

**Format:** Mealie exports a `.zip` containing `recipes/` folders. Each folder has a JSON file with recipe data and optional image files.

**Backend:** `POST /api/recipes/import-mealie` accepts the uploaded zip. Extracts and parses each recipe JSON into Crumble's format. Ingredient strings are run through the IngredientParser (Feature 1). Images are saved to `uploads/`.

**Mealie JSON mapping:**

| Mealie field | Crumble field |
|-------------|---------------|
| `name` | `title` |
| `description` | `description` |
| `prepTime` | `prep_time` (parse duration) |
| `cookTime` | `cook_time` (parse duration) |
| `recipeYield` | `servings` |
| `recipeIngredient[]` | `ingredients[]` (through parser) |
| `recipeInstructions[].text` | `instructions[]` |
| `image` | image file in same folder |

### 4c. Paprika Import

**Format:** `.paprikarecipes` files are gzipped archives. Each entry is a JSON object with recipe data.

**Backend:** `POST /api/recipes/import-paprika` accepts the uploaded file. Decompresses and parses each recipe.

**Paprika JSON mapping:**

| Paprika field | Crumble field |
|--------------|---------------|
| `name` | `title` |
| `description` | `description` |
| `prep_time` | `prep_time` (parse "X min" string) |
| `cook_time` | `cook_time` |
| `servings` | `servings` |
| `ingredients` (newline-separated string) | `ingredients[]` (split + parse each line) |
| `directions` (newline-separated string) | `instructions[]` (split into steps) |
| `photo_data` (base64) | image file |
| `source` | `source_url` |

### Shared Review UI

Both app import flows land on the same review screen as multi-URL import: a table of parsed recipes with save/review/skip actions per recipe and a "Save All" bulk action.

### Files

- **Create:** `api/controllers/ImportController.php` — batch URL import, Mealie import, Paprika import endpoints
- **Create:** `api/services/MealieImporter.php` — zip extraction and JSON mapping
- **Create:** `api/services/PaprikaImporter.php` — gzip extraction and JSON mapping
- **Modify:** `api/index.php` — register import routes
- **Create:** `frontend/src/pages/BulkImportPage.jsx` — URL textarea, file upload, review table
- **Modify:** `frontend/src/App.jsx` — add `/import` route
- **Modify:** navigation — add link to bulk import

---

## Implementation Order

1. **Feature 1** (Ingredient Parser) and **Feature 2** (Scraper Improvements) — can be built in parallel
2. **Feature 3** (Print View) — quick win after Feature 1
3. **Feature 4** (Bulk Import) — builds on both Features 1 and 2
