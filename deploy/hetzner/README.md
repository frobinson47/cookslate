# Hetzner deployment

Cookslate prod (`home.cookslate.app`) and demo (`demo.cookslate.app`) run on a shared Hetzner CX33 box (`204.168.148.56`) co-tenanted with hookhouse-pro.

## Topology

```
Internet
   |
   v
+--------------------+
| caddy              |  <- standalone, /opt/caddy/
|  (Caddy 2)         |     joins 3 external networks below
+--------------------+
   |        |        |
   v        v        v
hookhouse  cookslate  cookslate-demo
(:3002)    (:80)      (:80)
```

Caddy was originally bundled inside `/opt/hookhouse/docker-compose.yml`, but
was moved out into `/opt/caddy/` to allow independent restarts when adding new
sites without bouncing the hookhouse app. Each app stack defines its own bridge
network (`<project>_default`); Caddy connects to all three as `external: true`.

## Files in this directory

- `caddy/docker-compose.yml` — standalone Caddy stack
- `caddy/Caddyfile` — routes for all three apps

These are point-in-time snapshots. The live source of truth is
`/opt/caddy/` on the Hetzner box.

## Cookslate-specific compose

The cookslate prod and demo compose files live on the box at:

- `/opt/cookslate/docker-compose.yml` (prod, port 127.0.0.1:8080)
- `/opt/cookslate-demo/docker-compose.yml` (demo, port 127.0.0.1:8081)

The build context for both is the `cookslate` repo; demo builds from
`../cookslate` (Option A — repo + compose colocated).

## DNS

Cloudflare A records for `home.cookslate.app` and `demo.cookslate.app` point
to `204.168.148.56` with **proxy OFF (gray cloud)** so Caddy can solve ACME
HTTP-01 challenges directly.

## Deploy workflow

Prod secrets (`APP_ENCRYPTION_KEY`, `APP_URL`, `COOKSLATE_API_KEY`, `EMAIL_FROM`,
`RESEND_API_KEY`, `USDA_API_KEY`) live in Infisical (`Cookslate` project, `prod`
environment), not a hand-maintained `.env`. Deploy via the wrapper script, which
pulls secrets from Infisical, writes a break-glass `.env` mirror, and recreates
the app container:

```bash
ssh root@204.168.148.56
/opt/cookslate/deploy.sh   # see deploy/hetzner/cookslate/deploy.sh
cd /opt/cookslate-demo && docker compose build app && docker compose up -d app
```

`deploy.sh` requires `/opt/cookslate/.infisical-auth` (chmod 600, gitignored) —
the `cookslate-app` machine identity's Universal Auth `CLIENT_ID`/`CLIENT_SECRET`,
scoped to viewer access on the Cookslate Infisical project. Demo still uses a
plain `.env` (only `APP_ENCRYPTION_KEY`) — not yet migrated.

Demo seed reset runs hourly via root crontab:
`0 * * * * /opt/cookslate-demo/seed/reset-demo.sh >> /opt/cookslate-demo/seed/reset.log 2>&1`
