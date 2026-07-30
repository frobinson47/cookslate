# Autonomous Forge Roadmap

## Product vision

Cookslate uses Autonomous Forge to keep a clear improvement plan, choose small tasks, check results, and record what happened.

## Product scope and non-goals

This roadmap tracks incremental improvements. It is not a replacement for project management, issue tracking, or deployment tooling.

## Current architecture

To be documented as the project evolves.

## Current implementation status

Roadmap v1 is complete. Roadmap v2 (AUTO-023 through AUTO-025) is in progress.

## Technical debt

None documented yet.

## Prioritized roadmap

### AUTO-001 — Organize loose marketing/branding assets at repo root
Priority: P2
Status: DONE

Goal: Move stray image assets (`Built_By_Indies_2nd_Place_Launch.PNG`, `cookslate-icon-160.png`, `cookslate-icon-32.png`, `cookslate-product-1600x1200.png`, `cookslate_banner.PNG`, `outpost.PNG`) currently sitting untracked at the repo root into an appropriate directory, and update any references.
Why it matters: Loose, untracked binary assets at the repo root clutter `git status` output, make it unclear which images are actually used by the app vs. one-off marketing screenshots, and risk being lost since they aren't committed anywhere.
Scope: Repo-root PNG files only. Do not touch already-organized assets under `frontend/public/` unless consolidating an obvious duplicate (e.g. `cookslate-icon-160.png` exists both at root and in `frontend/public/`).
Expected files or areas: Repo root, `frontend/public/`, possibly a new `docs/assets/` or `docs/marketing/` directory for non-app marketing images (e.g. launch badges, banners).
Acceptance criteria: No stray PNG/marketing image files remain at repo root; each asset lives under `frontend/public/` (if used by the app) or `docs/assets/` (if marketing-only); any code or docs referencing these paths still resolve correctly.
Validation: `cd frontend && npm run build` succeeds; visually confirm favicon/icons still load in a local dev run; `git status` shows no stray root-level image files.
Risks or assumptions: Assumes none of these images are referenced by absolute root paths in deployment configs (e.g. Caddy, docker-compose) — verify before moving.
Notes: Root-level duplicates of `cookslate-icon-160.png`, `cookslate-icon-32.png`, and `cookslate-product-1600x300.png` already exist under `frontend/public/` — confirm which copy is canonical before deleting either.

### AUTO-002 — Import from image (BYOK vision LLM recipe extraction)
Priority: P2
Status: DONE

Goal: Let a user upload a photo (cookbook page, handwritten card, screenshot) and have a vision-capable LLM extract structured recipe data, returned for preview the same way URL import works today.
Why it matters: Differentiating import path competitors (Tandoor/Mealie/Paprika) don't offer; broadens what recipes can be captured beyond scrapeable web pages.
Scope: New provider-agnostic image-import service and endpoint; per-user BYOK API key storage/settings UI; frontend upload flow feeding the existing recipe-preview/edit screen. Default provider OpenAI (gpt-4o-mini or current vision equivalent); abstract behind an interface so Claude can be added later without a rewrite. Multi-provider support and bulk import are explicitly out of scope for this task.
Expected files or areas: `api/services/` (new `ImageRecipeImporter.php`, mirroring the `MealieImporter`/`PaprikaImporter` pattern), `api/controllers/RecipeController.php` (new `POST /recipes/import-image`), user settings model/migration for an encrypted API key column, `frontend/src/` upload UI and settings page.
Acceptance criteria: User can add their own OpenAI key in settings (masked, encrypted at rest, never returned by any API response or logged); uploading a recipe photo returns parsed-but-unsaved recipe data via the same preview/edit flow as URL import; clear error states for missing key, oversized image, and extraction failure.
Validation: `cd api && vendor/bin/phpunit` for new service/controller tests; manual test uploading a real recipe photo with a live OpenAI key; confirm the key is never present in API responses or logs.
Risks or assumptions: API key must be encrypted at rest, not a plain DB column; user is billed directly by OpenAI, so cost must be made clear in the UI before each import; needs upload size/rate limits to bound cost exposure from a misconfigured key.
Notes: Forgejo issue #62, milestone Differentiators (#31). See conversation research from 2026-07-10 for provider tradeoffs (OpenAI chosen as default over Claude for broader existing-key coverage among self-hosters).

### AUTO-003 — Import from Pinterest pin (resolve to source URL)
Priority: P3
Status: DONE

Goal: Let a user paste a Pinterest pin URL (`pin.it/xxx` or `pinterest.com/pin/id`) and have the app resolve it to the original recipe source URL, then feed that into the existing `RecipeScraper` import flow.
Why it matters: Many recipes are discovered via Pinterest pins that link back to a blog; resolving the pin removes a manual copy-paste step for users, but see risks below.
Scope: `pin.it` short-link resolution via redirect-follow; canonical pin page fetch and defensive parsing of the embedded JSON blob for the outbound `link` field (no dependency on one specific script-tag id — must degrade gracefully as Pinterest's markup changes); on any resolution failure, return a clear error asking the user to paste the source URL directly instead of failing silently. No new endpoint — likely a pre-resolution step inside `RecipeScraper.php` or a small `PinterestResolver.php` called before `scrape()`. Bulk/board import and any caching of pin content are out of scope.
Expected files or areas: `api/services/RecipeScraper.php` or new `api/services/PinterestResolver.php`; no frontend changes expected beyond existing import field already accepting arbitrary URLs.
Acceptance criteria: Pasting a Pinterest pin with a source link successfully imports the underlying recipe via the existing import flow; pasting a pin with no source link (Idea Pin, native upload) returns a clear, actionable error rather than a silent failure or crash.
Validation: `cd api && vendor/bin/phpunit` for resolver unit tests against saved fixture HTML (do not hit live Pinterest URLs in CI); manual test against a handful of real pin URLs of both kinds (has source link / no source link).
Risks or assumptions: Relies on an undocumented Pinterest JSON blob that has changed shape before — expect maintenance burden. Pinterest's `robots.txt` disallows scraping `/pin/*` pages for most user agents; mitigate by keeping this strictly single-shot/user-triggered, never bulk or background, and only ever extracting the outbound link (no content caching/redistribution) — still worth a final ToS read before shipping. A meaningful fraction of pins have no source link at all and the feature simply won't work for those.
Notes: Forgejo issue #63, milestone Backlog (#35) given fragility — revisit priority once AUTO-002 ships. See conversation research from 2026-07-10 for the oEmbed/API investigation that ruled out cleaner approaches.

## Roadmap v1

### AUTO-004 — Receipt Scanning
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #69: snap a photo of a receipt, extract every line item/price/store, and prefill spending history + pantry automatically.
Why it matters: Manual line-item entry is what makes people quit tracking pantry/spending. Full line-item extraction (not totals-only) is required for both category breakdowns and pantry prefill to work at all — decided explicitly over the totals-only alternative.
Scope: Full line-item vision extraction; new spending-history subsystem (shopping_trips/shopping_trip_items); pantry quantity tracking (previously just name + always-stocked boolean). Broken into 5 sub-tasks, Forgejo #70-#74.
Expected files or areas: database/migrations/022_pantry_quantity.sql, 023_shopping_trips.sql; api/services/ReceiptVisionParser.php + OpenAiReceiptParser.php; api/models/ShoppingTrip.php + ShoppingTripItem.php; api/controllers/ShoppingTripController.php; api/models/Pantry.php (quantity support); frontend/src/pages/ScanReceiptPage.jsx + SpendingHistoryPage.jsx; frontend/src/components/receipt/*; frontend/src/hooks/useShoppingTrips.js.
Acceptance criteria: User uploads a receipt photo, reviews/edits extracted store/date/total/line-items, confirms — trip + items persist, matched pantry entries get quantity/unit prefilled (accumulated when units match). Spending History page lists trips and shows a per-trip category breakdown.
Validation: 20 new PHPUnit tests (7 vision parser, 4 pantry quantity, 6 shopping trip model) — all pass alongside full existing suite (263 tests). Frontend verified via `npm run build` (not `npm run lint` — see risks).
Risks or assumptions: Receipt images aren't persisted to disk yet (receipt_image_path stays null), matching the same simplification the recipe-image-import feature made for its source image. Pantry quantity accumulation doesn't attempt unit conversion — mismatched units just overwrite rather than sum. `npm run lint` is broken repo-wide (ESLint 9 needs eslint.config.js migration from .eslintrc.*) — pre-existing, unrelated, not fixed as part of this task.
Notes: Imported from Forgejo issue #69 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/69). Sub-tasks: #70 (schema), #71 (vision parser), #72 (backend), #73 (upload/review frontend), #74 (spending history + pantry UI).

### AUTO-005 — Migrate cookslate prod secrets from plain .env to Infisical
Priority: P1
Status: DONE

Goal: Imported from Forgejo issue #64: Migrate cookslate prod secrets from plain .env to Infisical
Why it matters: A plaintext .env on the prod box is the exact pattern behind a prior real incident (a leaked API key scraped and abused within minutes). Infisical is this org's mandated secrets source of truth.
Scope: Cookslate prod only (home.cookslate.app on hookhouse-pro). Demo (demo.cookslate.app) still uses a plain .env with only APP_ENCRYPTION_KEY — not migrated, out of scope for this task.
Expected files or areas: New Infisical project "Cookslate" (workspaceId 454001e7-e795-45d6-a5c4-a34846dcef91), scoped `cookslate-app` machine identity (viewer), `/opt/cookslate/.infisical-auth` on the server, `deploy/hetzner/cookslate/deploy.sh`, `deploy/hetzner/README.md`.
Acceptance criteria: All 6 prod secrets (APP_ENCRYPTION_KEY, APP_URL, COOKSLATE_API_KEY, EMAIL_FROM, RESEND_API_KEY, USDA_API_KEY) live in Infisical prod env; deploy script pulls from Infisical via a scoped machine identity and writes a break-glass .env mirror; app container recreated successfully with Infisical-sourced values.
Validation: Verified login + `infisical export` returns the same 6 keys with byte-identical values (accounting for dotenv quote-wrapping) as the original .env; ran `deploy.sh` for real and confirmed the app container came up healthy.
Risks or assumptions: cookslate-app identity has viewer-only access, scoped to this one project. The break-glass .env mirror means a stale plaintext copy still exists on disk after each deploy — acceptable per org policy (same pattern used for every other app).
Notes: Imported from Forgejo issue #64 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/64). While wiring this, found the server's docker-compose.yml had drifted from the repo (restart: unless-stopped, 127.0.0.1-only port binding, external "web" network for Caddy, and the RESEND_API_KEY/EMAIL_FROM/APP_URL env vars) — reconciled by pulling the server's live config back into the repo rather than overwriting it. Also found 7 Card Art feature files and 9 modified files on the server that were byte-identical to already-merged commits (6ffe9f0/44df2df) — safe leftovers from an earlier direct-to-server deploy, discarded so `git pull` works cleanly again.

### AUTO-011 — Remove CLAUDE.md from repo and gitignore it
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #49: Remove CLAUDE.md from repo and gitignore it
Why it matters: CLAUDE.md was tracked and syncing to GitHub via the Forgejo push mirror; keeping it untracked avoids publishing internal project notes.
Scope: Already resolved prior to this import.
Expected files or areas: `.gitignore`, `CLAUDE.md`.
Acceptance criteria: `CLAUDE.md` is untracked and matched by `.gitignore`.
Validation: `git ls-files | grep CLAUDE.md` returns nothing; `git status` shows no pending changes for the file.
Risks or assumptions: None.
Notes: Already done in commit aec3539 (2026-04-22), predating this import. Verified still untracked/gitignored on 2026-07-27. Forgejo issue #49 closed as already resolved.

### AUTO-012 — cookslate.com domain acquisition
Priority: P2
Status: SKIPPED

Goal: Imported from Forgejo issue #34: cookslate.com domain acquisition
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #34 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/34). Skipped — nothing actionable until closer to the domain's 2026-10-20 expiry; revisit then.

### AUTO-013 — Native mobile app
Priority: P2
Status: SKIPPED

Goal: Imported from Forgejo issue #33: Native mobile app
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #33 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/33). Skipped — explicitly waiting for customer demand before scoping.

### AUTO-014 — Batch editing for recipes
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #32: multi-select + bulk tag/delete/add-to-collection, referencing Tandoor's batch editing for recipes/foods/users/shopping.
Why it matters: Manually editing recipes one at a time (tagging, cleaning up, organizing into collections) doesn't scale once a library grows past a few dozen recipes.
Scope: Recipes only (matches the issue title; foods/users/shopping batch editing from the Tandoor comparison are separate, not opened here). Multi-select lands on the HomePage grid density view only, per user confirmation — list/compact density and other recipe-listing pages (Favorites, Discover, Collections) don't get it in this pass.
Expected files or areas: api/models/Recipe.php (addTags), Collection.php (addRecipesBulk); api/controllers/RecipeController.php (bulkDelete/bulkTag), CollectionController.php (addRecipesBulk); api/index.php routing; frontend/src/components/recipe/RecipeCard.jsx (selection mode), BulkActionToolbar.jsx (new), RecipeGrid.jsx; frontend/src/pages/HomePage.jsx.
Acceptance criteria: User taps Select, checks multiple recipe cards, and can bulk-tag (additive), bulk-delete (with confirm), or bulk-add-to-collection from a floating toolbar. Recipes the user doesn't own are silently skipped (reported in the response) rather than failing the whole batch, matching the existing single-recipe delete/edit authorization model.
Validation: 7 new PHPUnit tests (5 Recipe bulk ops, 2 Collection bulk ops) — all pass alongside full suite (270 tests). Frontend verified via `npm run build` (lint still broken repo-wide, unrelated — see AUTO-004 notes).
Risks or assumptions: Bulk add-to-collection only checks collection ownership (not per-recipe), matching the existing single addRecipe endpoint's behavior — collections are personal folders over a shared recipe library, not per-recipe ACLs.
Notes: Imported from Forgejo issue #32 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/32). Lesson from AUTO-004: deploy to prod as part of "done," not just push to Forgejo — applied here.

### AUTO-015 — More import formats (Nextcloud Cookbook, RecipeSage)
Priority: P3
Status: DONE

Goal: Imported from Forgejo issue #31: expand import compatibility beyond Mealie/Paprika/Tandoor to Nextcloud Cookbook and RecipeSage.
Why it matters: Broadens the set of self-hosted/other tools users can migrate from.
Scope: New POST /recipes/import-nextcloud and /recipes/import-recipesage endpoints, wired into BulkImportPage's existing zip-format picker.
Expected files or areas: api/services/NextcloudCookbookImporter.php, RecipeSageImporter.php (new); api/controllers/RecipeController.php; api/index.php; frontend/src/services/api.js, pages/BulkImportPage.jsx.
Acceptance criteria: Uploading a Nextcloud Cookbook or RecipeSage export ZIP produces the same parsed-but-unsaved recipe preview flow as Mealie/Tandoor/Paprika imports.
Validation: 7 new PHPUnit tests against synthetic sample ZIPs (built with ZipArchive in-test, not real exports) — all pass alongside full suite (283 tests). `npm run build` succeeds.
Risks or assumptions: Nextcloud Cookbook importer reuses the same schema.org/Recipe JSON-LD parsing TandoorImporter already handles (both tools export this shape) — reasonably confident. RecipeSageImporter was built from documented format knowledge only, NOT verified against a real RecipeSage export file (confirmed with user to proceed this way) — field names/shapes may need adjustment if a real export surfaces differences, particularly around the ingredients/instructions string-vs-array shape and free-text time parsing.
Notes: Imported from Forgejo issue #31 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/31).

### AUTO-016 — Pantry-based recipe search (What Can I Make?)
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #30: search recipes scored by pantry coverage, originally estimated at 6.5hr (new pantry table, new scoring endpoint, new frontend page).
Why it matters: Self-hosted recipe managers don't offer this; helps users cook from what they already have rather than shopping for every recipe.
Scope: The pantry table (AUTO-004) and an ingredient-coverage-scoring endpoint (`byIngredients`/`findByIngredients`, already backing HomePage's manually-typed "What can I make?" mode) already existed from unrelated earlier work — this collapsed to wiring a "Use My Pantry" shortcut that pulls the user's pantry list and feeds it into the existing scored search, rather than building new infrastructure.
Expected files or areas: frontend/src/pages/HomePage.jsx.
Acceptance criteria: In ingredient-search mode, tapping "Use My Pantry" populates the ingredient list from the user's pantry and shows scored results; an empty pantry shows a clear message instead of silently falling back to the normal grid.
Validation: `npm run build` succeeds. Not manually click-tested in a live browser this pass.
Risks or assumptions: Matching is by exact/normalized ingredient name only (same as the existing manual ingredient search) — no fuzzy matching or unit awareness.
Notes: Imported from Forgejo issue #30 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/30). Estimate dropped from 6.5hr to a small UI change once the AUTO-004 pantry work and the existing ingredient-search endpoint were accounted for.

### AUTO-017 — Cooklang export
Priority: P3
Status: DONE

Goal: Imported from Forgejo issue #29: convert recipes to Cooklang plain-text format for data portability.
Why it matters: Export matters more than import for portability messaging (users trust a tool more if they can leave with their data).
Scope: Already resolved prior to this import — no code changes made this pass.
Expected files or areas: api/services/CooklangExporter.php.
Acceptance criteria: Recipes export as valid Cooklang plain text.
Validation: `vendor/bin/phpunit tests/Unit/CooklangTest.php` passes (3 tests, 20 assertions). Confirmed `/recipes/export-cooklang` is wired in api/index.php and exposed in the UI (Sidebar.jsx export menu, "Cooklang (.cook)" link).
Risks or assumptions: None — fully implemented and already user-facing.
Notes: Imported from Forgejo issue #29 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/29). Closed as already-resolved.

### AUTO-018 — Recipe page dead code cleanup
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #28: remove ~150 lines of commented-out card sharing code from RecipePage.jsx, clean up StarRating display.
Why it matters: Dead code clutters the file and confuses future readers about what's actually live.
Scope: Already resolved prior to this import — no code changes made this pass.
Expected files or areas: frontend/src/pages/RecipePage.jsx.
Acceptance criteria: No large commented-out blocks remain; StarRating renders once, cleanly.
Validation: Scanned the file for runs of 5+ consecutive commented lines (none found) and for any "card sharing"-related dead code (none found); StarRating is a single clean instance with no surrounding cruft.
Risks or assumptions: None — evidently cleaned up in a later refactor without the Forgejo issue or roadmap being updated.
Notes: Imported from Forgejo issue #28 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/28). Closed as already-resolved.

### AUTO-019 — WebP image conversion
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #27: switch imagejpeg() to imagewebp() in ImageProcessor for new uploads only (25-35% smaller files); existing JPEG images stay as-is.
Why it matters: Smaller image payloads improve load time, especially on mobile.
Scope: Already resolved prior to this import — no code changes made this pass.
Expected files or areas: api/services/ImageProcessor.php, frontend/src/utils/imageUrl.js.
Acceptance criteria: New recipe/card-art image uploads are saved as .webp; legacy .jpg images continue to resolve correctly.
Validation: Verified by reading current code: ImageProcessor::resizeAndSave/processCardArtUpload already call imagewebp() and write full.webp/thumb.webp/{filename}.webp; imageUrl.js's thumbImageUrl() already handles both `.webp` (new) and `.jpg` (legacy) via regex.
Risks or assumptions: None — this was evidently completed in an earlier session (image-related commits from the "production readiness"/"launch prep" era) without the Forgejo issue or roadmap ever being updated to reflect it.
Notes: Imported from Forgejo issue #27 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/27). Closed as already-resolved.

### AUTO-020 — Tandoor comparison matrix listing
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #24: Tandoor comparison matrix listing
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #24 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/24).

### AUTO-021 — Reddit r/selfhosted standalone post
Priority: P2
Status: DONE

Goal: Imported from Forgejo issue #23: Reddit r/selfhosted standalone post
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #23 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/23).

### AUTO-022 — Year-in-review cooking stats page
Priority: P3
Status: DONE

Goal: Imported from Forgejo issue #17: total meals, most active month, most-made recipe, new recipes tried, streak peak, top cuisine — a "Your Year in Cooking" recap, derivable from existing cook_log/ratings/recipe_tags.
Why it matters: A shareable annual recap is a proven free-tier engagement/virality pattern (Spotify Wrapped-style) — distinct from the ongoing Pro Kitchen Stats dashboard, which already covers similar ground but as a permanent analytics view.
Scope: Free, standalone page (not Pro-gated) — confirmed with user given heavy overlap with the existing Pro StatsController. "Top cuisine" mapped to "top tag" since recipes have no dedicated cuisine field. "Streak peak" is new (existing Pro stats only tracks the current streak, not the longest-ever).
Expected files or areas: api/models/CookLog.php (getYearInReview), api/controllers/CookLogController.php, api/index.php; frontend/src/pages/YearInReviewPage.jsx (new), App.jsx, Sidebar.jsx.
Acceptance criteria: GET /cook-log/year-in-review?year=YYYY returns total meals, most active month, most-made recipe, new recipes tried, streak peak, and top tag for that year; frontend page lets the user pick a year and shows an empty state if nothing was logged.
Validation: 6 new PHPUnit tests (total/unique counts, most active month, most-made recipe, new-recipes exclusion logic, streak-peak calculation, empty-year zeroing) — all pass alongside full suite (276 tests). `npm run build` succeeds.
Risks or assumptions: Not manually click-tested in a live browser this pass.
Notes: Imported from Forgejo issue #17 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/17).

## Roadmap v2

Sourced from competitive research against Mealie, Tandoor Recipes, Nextcloud Cookbook, Paprika, and the 2026 AI-pantry-scan app wave (FEATURE_ENHANCEMENTS.md, 2026-07-29), since the v1 Forgejo backlog was fully exhausted. User approved the top 3 recommendations.

### AUTO-023 — Pantry expiration tracking + Use It Soon surfacing
Priority: P1
Status: DONE

Goal: Imported from Forgejo issue #75. Add an optional expiration date to pantry items and surface a "Use It Soon" card so users see what's about to go bad.
Why it matters: Every commercial and AI-pantry competitor (Paprika, Pantry AI, RecipeScan) leads with expiry reminders as the core food-waste-reduction hook. Cookslate's pantry has quantity tracking (AUTO-004) but no time dimension at all.
Scope: Add expiration_date to the pantry table; surface a "Use It Soon" HomePage card (same pattern as the existing "Forgotten Favorites"/"Something New" cards). No push notifications or reminders in this pass — just in-app surfacing.
Expected files or areas: New migration (024_pantry_expiration.sql); api/models/Pantry.php; frontend/src/components/grocery/PantrySection.jsx (date input), frontend/src/pages/HomePage.jsx (new card).
Acceptance criteria: User can optionally set/edit an expiration date per pantry item; items expiring within N days (start with 3) surface in a dedicated HomePage card; items with no expiration date set behave exactly as today (fully optional field).
Validation: PHPUnit tests for the new pantry query; npm run build; manual check that the card only shows when relevant.
Risks or assumptions: None — purely additive, nullable column.
Notes: Imported from Forgejo issue #75 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/75).

### AUTO-024 — Pantry photo scan (fridge/shelf inventory from one photo)
Priority: P1
Status: TODO

Goal: Imported from Forgejo issue #76. Extend the existing receipt-scanning vision pipeline to a second mode: photograph a fridge/pantry/freezer shelf and bulk-add identified items to the pantry, no receipt needed.
Why it matters: Every dedicated AI-pantry app in 2026 leads with exactly this, and Tandoor already ships it as a differentiator against Mealie. ~80% of the infrastructure already exists from AUTO-004 (OpenAI vision pipeline, BYOK key handling, Pantry model with quantities).
Scope: New vision parser (new prompt, reusing the same OpenAI vision call pattern as OpenAiReceiptParser) that returns item name + rough quantity only (no price/store/date). Reuses ScanReceiptPage's upload+review UX with a simpler review form. Writes straight to Pantry::add(), skips shopping_trips entirely.
Expected files or areas: api/services/OpenAiPantryScanParser.php (new, or extend OpenAiReceiptParser); api/controllers/ (new endpoint or reuse ShoppingTripController pattern); frontend: new PantryScanPage or extend ScanReceiptPage with a mode toggle; entry point button next to "Scan Receipt".
Acceptance criteria: User uploads a fridge/pantry photo, reviews an editable list of identified items + estimated quantities, confirms — items added to pantry (accumulating quantities per the existing Pantry::add() logic from AUTO-004, including AUTO-023's expiration date if the user sets one during review).
Validation: PHPUnit tests against synthetic sample vision responses (mirroring the OpenAiReceiptParserTest pattern); npm run build.
Risks or assumptions: Same BYOK/cost-exposure considerations as receipt scanning — shares the existing rate limit bucket. Depends on AUTO-023 shipping first if the review form should let users set expiration dates during pantry-scan (soft dependency, not a hard blocker).
Notes: Imported from Forgejo issue #76 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/76).

### AUTO-025 — Home Assistant integration (read-only sensors + dashboard card)
Priority: P1
Status: TODO

Goal: Imported from Forgejo issue #77. Expose a small, stable read-only API surface (today's meal plan, pantry "use it soon" items) for Home Assistant REST sensor consumption, plus documentation with an example Lovelace card config.
Why it matters: Mealie's official Home Assistant integration has 2,555+ active installs — real evidence the self-hosted recipe-app audience already runs Home Assistant. Reuses the existing read-only external API key infrastructure (COOKSLATE_API_KEY) rather than building new auth.
Scope: Two new read-only endpoints scoped to the existing external API key middleware. No official HACS integration in this pass — a documented REST sensor YAML config is the v1 target, not a published HA custom component.
Expected files or areas: api/controllers/ (new ExternalController methods or extend the existing one), api/index.php routing; new docs/home-assistant.md with copy-pasteable `rest` sensor + card YAML.
Acceptance criteria: GET /external/today-meal and GET /external/pantry-alerts return read-only data authenticated via the existing external API key; docs/home-assistant.md has a working example a user can copy into configuration.yaml.
Validation: PHPUnit tests for the new endpoints (auth required, correct data shape); manual test with a real API key via curl.
Risks or assumptions: Depends on AUTO-023 (pantry expiration) for the pantry-alerts endpoint to have real data to return — sequence after AUTO-023.
Notes: Imported from Forgejo issue #77 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/77).

## Future Ideas

## Do Not Change Without Explicit Human Approval

- Remote and branch settings.
- Repository visibility and access controls.
- Production infrastructure.
- Features that run external commands.
- Credential handling, telemetry, analytics, billing, or deployment behavior.
