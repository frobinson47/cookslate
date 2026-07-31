<!-- auto-docs:start:header -->
# Cookslate

> A recipe manager that remembers how you cook — not just what you cook

| Field | Value |
|-------|-------|
| Status | beta |
| Phase | grow |
| Target launch | 2026-07-01 |
| Stack | PHP 8.1, React 18, Vite, Tailwind CSS 4, MySQL 8 |
<!-- auto-docs:end:header -->
<a href="https://builtbyindies.com/products/cookslate" target="_blank"><img src="https://images.builtbyindies.com/badges/featured-on-builtbyindies.svg" alt="Featured on BuiltByIndies" width="250" /></a>  <a href="https://builtbyindies.com/products/cookslate" target="_blank"><img src="https://images.builtbyindies.com/badges/winner-week-02-builtbyindies.svg" alt="#2 Product of the Week" width="250" /></a>

<!-- auto-docs:preserved:start -->
**Live demo:** [demo.cookslate.app](https://demo.cookslate.app) (login: demo / demo -- fully interactive, resets hourly)

**No Docker required.** Runs on any PHP 8.1+ hosting with MySQL. Docker Compose included if you prefer it.

### Features

**Core (free, MIT licensed):**
- Import recipes from any URL -- paste a link, structured data is scraped automatically
- Import recipes from a photo using your own OpenAI key (BYOK vision)
- Resolve Pinterest pins to their original recipe source automatically
- Cook Mode -- step-by-step instructions with built-in timers, screen wake lock, and vibration alerts
- Grocery lists with smart consolidation (combines "1 cup milk" + "2 cups milk" automatically)
- Full-text search across titles, descriptions, and ingredients
- Bulk import from Mealie, Paprika, Tandoor, Nextcloud Cookbook, and RecipeSage
- Recipe collections for organizing recipes into custom groups
- Private recipes (visible only to their creator) and shareable public recipe links
- Multi-select bulk actions -- bulk delete, bulk tag, bulk add-to-collection
- Barcode scanning with Open Food Facts lookup for packaged/branded products
- Pantry-based recipe search -- "What can I make with what I have?"
- Cook log with cooking history and "forgotten favorites" resurfacing
- Home Assistant integration -- read-only pantry expiration alerts sensor
- Self-service password reset via email, plus admin-generated reset links
- Installable PWA with share-target support (share a recipe URL straight from your phone)
- JSON-LD and Cooklang export for data portability
- Authentik SSO support (zero-library, header-based)
- Mobile-first responsive design with 44px+ tap targets

**Pro tier ($29.99 one-time, BSL licensed):**
- Meal planning with drag-and-drop weekly calendar, iCal export, and saved/reusable weekly templates
- Shoppable grocery quantities (converts "2 cups milk" to "Milk - 1 gallon")
- Pantry tracking with expiration dates, a "Use It Soon" home page card, and always-stocked auto-detection on grocery lists
- Receipt scanning -- snap a grocery receipt photo (BYOK vision) to auto-fill a shopping trip and prefill pantry quantities
- Pantry photo scan -- identify fridge/pantry/freezer contents from a single photo (BYOK vision)
- Multi-source barcode nutrition -- scanned receipt/pantry items auto-enrich with Open Food Facts nutrition data
- AI-generated recipe card art from your own OpenAI key, plus custom card image uploads
- Recipe cost-per-serving estimate shown on recipe cards
- Recipe annotations (margin notes on ingredients and steps)
- Ingredient database with USDA nutrition data, package sizes, and substitutions
- Year in Cooking -- annual cooking stats and recap
- Home Assistant integration -- read-only "today's planned meal" sensor
- Household tier ($49.99) supports up to 5 users, with shared collections, meal plans, grocery lists, and pantry across the household, plus a read-only Viewer role for guests
<!-- auto-docs:preserved:end -->

<!-- auto-docs:start:description -->
## What is this?

Self-hosted recipe management app with Cook Mode, grocery lists, and pantry tracking

**Target users:** Home cooks, privacy-conscious families, self-hosting enthusiasts
<!-- auto-docs:end:description -->

<!-- auto-docs:start:pricing -->
## Pricing

| Tier | Price | What you get |
|------|-------|-------------|
| Free | $0 | Full recipe management, import, search, Cook Mode, grocery lists |
| Pro | $29.99 one-time | Meal planning, shoppable quantities, pantry, annotations, nutrition |
| Household | $49.99 one-time | Everything in Pro for up to 5 users |

Open-core model: free tier is MIT licensed, Pro/Household features are BSL 1.1 (converts to MIT in 2029).
<!-- auto-docs:end:pricing -->



<!-- auto-docs:start:docs -->
## Documentation

- [Setup Guide](docs/SETUP.md) -- getting the dev environment running
- [Architecture](docs/ARCHITECTURE.md) -- stack, components, and decisions
- [Changelog](docs/CHANGELOG.md) -- release history
<!-- auto-docs:end:docs -->

<!-- auto-docs:start:screenshots -->
## Screenshots

![Recipe dashboard](screenshots/main_page.png)
*Recipe dashboard with featured recipe, tag filters, and recent activity*

![Recipe detail](screenshots/recipe_page.png)
*Recipe detail view with ingredients, instructions, and Cook Mode launcher*

![Cook Mode](screenshots/cook_mode.png)
*Cook Mode -- step-by-step instructions with ingredient sidebar*

![Grocery list](screenshots/grocery_list.png)
*Grocery list generated from meal plan with source recipe tracking*

![Discover recipes](screenshots/discover.png)
*Discover page for browsing world recipes by category*

![Dark mode](screenshots/dark_mode.png)
*Full dark mode support*

<!-- auto-docs:end:screenshots -->

<!-- auto-docs:start:contact -->
## Contact

https://github.com/frobinson47/cookslate/issues
<!-- auto-docs:end:contact -->

---

_Generated by `auto-docs` -- Re-render with `python scripts/docs_tool.py generate cookslate`_
