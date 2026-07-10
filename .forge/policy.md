# Cookslate Policy

## Allowed paths

- api/**
- frontend/**
- database/**
- docs/**
- aar/**
- design-exploration/**
- README.md
- CONTRIBUTING.md
- .gitignore
- .ai/**
- .forge/**

## Prohibited paths

- .env
- .env.*
- **/*secret*
- **/*api_token*
- **/*.pem
- **/*.key
- **/*credential*

## Human approval required

- Adding network access or external service calls.
- Running external commands from product code.
- Changing repository visibility, licensing, or access controls.
- Adding telemetry, analytics, tracking, or personal-data collection.

## Validation expectations

- Backend: `cd api && vendor/bin/phpunit` for changed test areas.
- Frontend: `cd frontend && npm run lint` and manual verification for UI changes.
- Record unavailable validation honestly in `.ai/AUTONOMOUS_STATE.md`.
