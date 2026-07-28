#!/usr/bin/env bash
# Deploy cookslate prod using secrets sourced from Infisical instead of a
# hand-maintained plain .env file. Run from /opt/cookslate on the Hetzner box.
#
# Requires /opt/cookslate/.infisical-auth (chmod 600, gitignored) containing:
#   CLIENT_ID=<cookslate-app universal-auth client id>
#   CLIENT_SECRET=<cookslate-app universal-auth client secret>
set -euo pipefail
cd "$(dirname "$0")"

DOMAIN="http://100.119.88.80:8085"
PROJECT_ID="454001e7-e795-45d6-a5c4-a34846dcef91"

# shellcheck source=/dev/null
source ./.infisical-auth

TOKEN=$(infisical login --method=universal-auth \
  --client-id="$CLIENT_ID" --client-secret="$CLIENT_SECRET" \
  --domain="$DOMAIN" --plain --silent)

COMMON=(--token "$TOKEN" --projectId "$PROJECT_ID" --env prod --domain "$DOMAIN" --silent)

# Break-glass mirror so `docker compose up` still works if Infisical is down.
infisical export "${COMMON[@]}" --format dotenv > .env
chmod 600 .env

git pull
docker compose build app
infisical run "${COMMON[@]}" -- docker compose up -d app
