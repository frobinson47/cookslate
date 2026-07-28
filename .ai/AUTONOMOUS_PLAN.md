# Autonomous Forge Roadmap

## Product vision

Cookslate uses Autonomous Forge to keep a clear improvement plan, choose small tasks, check results, and record what happened.

## Product scope and non-goals

This roadmap tracks incremental improvements. It is not a replacement for project management, issue tracking, or deployment tooling.

## Current architecture

To be documented as the project evolves.

## Current implementation status

Roadmap v1 is in progress.

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

### AUTO-006 — Blog: What is a self-hosted recipe manager? (top-of-funnel AEO)
Priority: P3
Status: TODO

Goal: Imported from Forgejo issue #61: Blog: What is a self-hosted recipe manager? (top-of-funnel AEO)
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #61 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/61).

### AUTO-007 — Blog: Migrating from Plan to Eat to Cookslate (AEO)
Priority: P3
Status: TODO

Goal: Imported from Forgejo issue #60: Blog: Migrating from Plan to Eat to Cookslate (AEO)
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #60 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/60).

### AUTO-008 — Blog: Self-hosted recipe manager comparison roundup (Mealie vs Tandoor vs Cookslate vs Nextcloud Cookbook)
Priority: P3
Status: TODO

Goal: Imported from Forgejo issue #59: Blog: Self-hosted recipe manager comparison roundup (Mealie vs Tandoor vs Cookslate vs Nextcloud Cookbook)
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #59 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/59).

### AUTO-009 — Blog: Best Mealie alternatives in 2026 (AEO)
Priority: P3
Status: TODO

Goal: Imported from Forgejo issue #58: Blog: Best Mealie alternatives in 2026 (AEO)
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #58 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/58).

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
Status: TODO

Goal: Imported from Forgejo issue #31: More import formats (Nextcloud Cookbook, RecipeSage)
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #31 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/31).

### AUTO-016 — Pantry-based recipe search (What Can I Make?)
Priority: P2
Status: TODO

Goal: Imported from Forgejo issue #30: Pantry-based recipe search (What Can I Make?)
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #30 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/30).

### AUTO-017 — Cooklang export
Priority: P3
Status: TODO

Goal: Imported from Forgejo issue #29: Cooklang export
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #29 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/29).

### AUTO-018 — Recipe page dead code cleanup
Priority: P2
Status: TODO

Goal: Imported from Forgejo issue #28: Recipe page dead code cleanup
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #28 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/28).

### AUTO-019 — WebP image conversion
Priority: P2
Status: TODO

Goal: Imported from Forgejo issue #27: WebP image conversion
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #27 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/27).

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
Status: TODO

Goal: Imported from Forgejo issue #17: Year-in-review cooking stats page
Why it matters: TBD
Scope: TBD
Expected files or areas: TBD
Acceptance criteria: TBD
Validation: TBD
Risks or assumptions: None.
Notes: Imported from Forgejo issue #17 (https://forgejo.familytechlab.com/fmrdigital/cookslate/issues/17).

## Future Ideas

## Do Not Change Without Explicit Human Approval

- Remote and branch settings.
- Repository visibility and access controls.
- Production infrastructure.
- Features that run external commands.
- Credential handling, telemetry, analytics, billing, or deployment behavior.
