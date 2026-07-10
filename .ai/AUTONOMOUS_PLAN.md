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
Status: TODO

Goal: Move stray image assets (`Built_By_Indies_2nd_Place_Launch.PNG`, `cookslate-icon-160.png`, `cookslate-icon-32.png`, `cookslate-product-1600x1200.png`, `cookslate_banner.PNG`, `outpost.PNG`) currently sitting untracked at the repo root into an appropriate directory, and update any references.
Why it matters: Loose, untracked binary assets at the repo root clutter `git status` output, make it unclear which images are actually used by the app vs. one-off marketing screenshots, and risk being lost since they aren't committed anywhere.
Scope: Repo-root PNG files only. Do not touch already-organized assets under `frontend/public/` unless consolidating an obvious duplicate (e.g. `cookslate-icon-160.png` exists both at root and in `frontend/public/`).
Expected files or areas: Repo root, `frontend/public/`, possibly a new `docs/assets/` or `docs/marketing/` directory for non-app marketing images (e.g. launch badges, banners).
Acceptance criteria: No stray PNG/marketing image files remain at repo root; each asset lives under `frontend/public/` (if used by the app) or `docs/assets/` (if marketing-only); any code or docs referencing these paths still resolve correctly.
Validation: `cd frontend && npm run build` succeeds; visually confirm favicon/icons still load in a local dev run; `git status` shows no stray root-level image files.
Risks or assumptions: Assumes none of these images are referenced by absolute root paths in deployment configs (e.g. Caddy, docker-compose) — verify before moving.
Notes: Root-level duplicates of `cookslate-icon-160.png`, `cookslate-icon-32.png`, and `cookslate-product-1600x300.png` already exist under `frontend/public/` — confirm which copy is canonical before deleting either.

## Roadmap v1

## Future Ideas

## Do Not Change Without Explicit Human Approval

- Remote and branch settings.
- Repository visibility and access controls.
- Production infrastructure.
- Features that run external commands.
- Credential handling, telemetry, analytics, billing, or deployment behavior.
