# Contributing to Cookslate

Thanks for your interest in improving Cookslate. This guide covers how to get set up and what's expected in a contribution.

## Development Setup

Cookslate runs on Laragon/Apache with MySQL. See `CLAUDE.md` for architecture details.

### Frontend (`frontend/`)

```bash
npm install
npm run dev       # Vite dev server on port 5176, proxies /api to cookslate.fmr.local
npm run build     # Production build to dist/
npm run lint      # ESLint
```

### Backend (`api/`)

```bash
composer install
vendor/bin/phpunit                          # All tests
vendor/bin/phpunit tests/Unit               # Unit tests only
vendor/bin/phpunit tests/Unit/SomeTest.php  # Single test file
```

### Database

- Schema: `database/schema.sql`
- Migrations: `database/migrations/` — numbered SQL files, applied manually. Add new migrations rather than editing applied ones.

## Making Changes

1. Open an issue first for anything beyond a trivial fix, so the change can be discussed before you invest time.
2. Keep pull requests scoped to a single concern — don't mix refactors with feature work.
3. Follow existing conventions in the codebase (see `CLAUDE.md` for architecture and coding conventions).
4. Add or update tests for backend changes under `api/tests/`.
5. Run the relevant test/lint commands above before opening a PR.

## Pull Requests

- Describe what changed and why, not just what.
- Reference the related issue if one exists.
- Keep commits focused; squash noisy WIP commits before requesting review.

## Reporting Bugs

Include steps to reproduce, expected vs. actual behavior, and relevant logs or screenshots.
