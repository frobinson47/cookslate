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
Status: TODO

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
Status: TODO

Goal: Let a user paste a Pinterest pin URL (`pin.it/xxx` or `pinterest.com/pin/id`) and have the app resolve it to the original recipe source URL, then feed that into the existing `RecipeScraper` import flow.
Why it matters: Many recipes are discovered via Pinterest pins that link back to a blog; resolving the pin removes a manual copy-paste step for users, but see risks below.
Scope: `pin.it` short-link resolution via redirect-follow; canonical pin page fetch and defensive parsing of the embedded JSON blob for the outbound `link` field (no dependency on one specific script-tag id — must degrade gracefully as Pinterest's markup changes); on any resolution failure, return a clear error asking the user to paste the source URL directly instead of failing silently. No new endpoint — likely a pre-resolution step inside `RecipeScraper.php` or a small `PinterestResolver.php` called before `scrape()`. Bulk/board import and any caching of pin content are out of scope.
Expected files or areas: `api/services/RecipeScraper.php` or new `api/services/PinterestResolver.php`; no frontend changes expected beyond existing import field already accepting arbitrary URLs.
Acceptance criteria: Pasting a Pinterest pin with a source link successfully imports the underlying recipe via the existing import flow; pasting a pin with no source link (Idea Pin, native upload) returns a clear, actionable error rather than a silent failure or crash.
Validation: `cd api && vendor/bin/phpunit` for resolver unit tests against saved fixture HTML (do not hit live Pinterest URLs in CI); manual test against a handful of real pin URLs of both kinds (has source link / no source link).
Risks or assumptions: Relies on an undocumented Pinterest JSON blob that has changed shape before — expect maintenance burden. Pinterest's `robots.txt` disallows scraping `/pin/*` pages for most user agents; mitigate by keeping this strictly single-shot/user-triggered, never bulk or background, and only ever extracting the outbound link (no content caching/redistribution) — still worth a final ToS read before shipping. A meaningful fraction of pins have no source link at all and the feature simply won't work for those.
Notes: Forgejo issue #63, milestone Backlog (#35) given fragility — revisit priority once AUTO-002 ships. See conversation research from 2026-07-10 for the oEmbed/API investigation that ruled out cleaner approaches.

## Roadmap v1

## Future Ideas

## Do Not Change Without Explicit Human Approval

- Remote and branch settings.
- Repository visibility and access controls.
- Production infrastructure.
- Features that run external commands.
- Credential handling, telemetry, analytics, billing, or deployment behavior.
