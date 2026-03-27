# Cookslate License Server — Design Spec

*Cloudflare Worker that handles Pro license purchases via LemonSqueezy, generates signed JWT license keys, and serves a minimal landing page.*

---

## 1. Overview

A single Cloudflare Worker deployed to `cookslate.app` that:

1. Serves a minimal landing/marketing page
2. Receives LemonSqueezy webhooks when someone buys Cookslate Pro ($9.99 one-time)
3. Generates RSA-signed JWT license keys and stores them in D1
4. Provides a post-checkout success page where buyers copy their key
5. Handles license verification heartbeats from self-hosted instances

This lives in a **separate private repo** (`cookslate-server`) — self-hosters never see this code. The public `cookslate` repo only contains the client-side license validation (reads JWT, verifies against bundled public key).

This repo will also house Stripe/SaaS billing for Cookslate Cloud (Phase B) when that's built later.

---

## 2. Endpoints

| Route | Method | Auth | Purpose |
|-------|--------|------|---------|
| `/` | GET | None | Minimal landing page HTML |
| `/success` | GET | None | Post-checkout page, displays license key |
| `/api/license/verify` | POST | None | Self-hosted heartbeat. Body: `{key: "jwt..."}`. Returns `{valid: true/false}` |
| `/api/license/lookup` | GET | Query `?order_id=X&email=Y` | Success page fetches the generated key |
| `/webhook/lemonsqueezy` | POST | HMAC signature | LemonSqueezy webhook — generates JWT on purchase |

### CORS

- `/api/license/verify` needs CORS headers (called from self-hosted instances on any domain)
- `/api/license/lookup` does NOT need CORS (called from the success page on the same origin)
- `/webhook/lemonsqueezy` does NOT need CORS (server-to-server)

---

## 3. Purchase Flow

```
Buyer clicks "Get Pro" on cookslate.app
    ↓
Redirected to LemonSqueezy hosted checkout
    ↓
Buyer pays $9.99
    ↓
LemonSqueezy sends POST /webhook/lemonsqueezy
    ↓
Worker verifies HMAC signature
    ↓
Worker generates signed JWT:
  {email, tier: "pro", order_id, iat, exp: 100 years}
    ↓
Worker stores in D1: email, order_id, license_key, created_at
    ↓
LemonSqueezy redirects buyer to /success?order_id=X&email=Y
    ↓
Success page calls /api/license/lookup → displays key
    ↓
Buyer copies key → pastes in Cookslate Settings → Pro unlocked
```

### JWT Claims

```json
{
  "email": "buyer@example.com",
  "tier": "pro",
  "order_id": "ls_order_abc123",
  "iat": 1774000000,
  "exp": 4927600000
}
```

- Signed with RS256 using the RSA private key (same keypair from Phase A)
- 100-year expiry — effectively permanent for a one-time purchase
- `order_id` included for traceability (lookup, revocation)

### Webhook HMAC Verification

LemonSqueezy signs webhooks with HMAC-SHA256. The Worker:
1. Reads the raw request body
2. Computes `HMAC-SHA256(body, LEMONSQUEEZY_WEBHOOK_SECRET)`
3. Compares against the `X-Signature` header
4. Rejects if they don't match (returns 401)

### Idempotency

If the same `order_id` is received twice (LemonSqueezy retries), the Worker returns the existing license key from D1 instead of generating a new one. The `order_id` column has a UNIQUE constraint.

---

## 4. Database (Cloudflare D1)

**Database name:** `cookslate-db`

```sql
CREATE TABLE licenses (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL,
  order_id TEXT UNIQUE NOT NULL,
  license_key TEXT NOT NULL,
  tier TEXT DEFAULT 'pro',
  created_at TEXT DEFAULT (datetime('now')),
  last_verified_at TEXT,
  active INTEGER DEFAULT 1
);

CREATE INDEX idx_licenses_email ON licenses(email);
CREATE INDEX idx_licenses_order_id ON licenses(order_id);
```

**One table. No migrations framework** — schema applied via D1 MCP or `wrangler d1 execute`.

---

## 5. Verify Endpoint (Heartbeat)

Self-hosted Cookslate instances call `POST /api/license/verify` monthly:

**Request:** `{key: "eyJhbG..."}`

**Worker logic:**
1. Decode the JWT payload (without signature verification — the Worker doesn't need to re-verify its own signatures)
2. Look up by `order_id` from the JWT claims in D1
3. If found and `active = 1`: update `last_verified_at`, return `{valid: true}`
4. If found and `active = 0`: return `{valid: false, reason: "revoked"}`
5. If not found: return `{valid: false, reason: "unknown"}`

**Why not verify the signature server-side:** The Worker generated these JWTs — if the order_id exists in D1, the key is legitimate. This keeps the verify endpoint simple and avoids needing the private key for verification.

---

## 6. Project Structure

**Repo:** `cookslate-server` (private, on Forgejo)

```
cookslate-server/
├── src/
│   ├── index.js          — Worker entry, route dispatcher
│   ├── routes/
│   │   ├── landing.js    — GET / — HTML landing page
│   │   ├── success.js    — GET /success — post-checkout key display
│   │   ├── webhook.js    — POST /webhook/lemonsqueezy — HMAC + JWT gen
│   │   ├── verify.js     — POST /api/license/verify — heartbeat
│   │   └── lookup.js     — GET /api/license/lookup — fetch key by order
│   ├── lib/
│   │   └── jwt.js        — RSA JWT signing via Web Crypto API
│   └── html/
│       ├── landing.html  — Minimal placeholder landing page
│       └── success.html  — Success page with copy button
├── schema.sql            — D1 table definition
├── wrangler.toml         — Worker config, D1 binding
├── package.json
└── CLAUDE.md
```

### Secrets (Cloudflare Worker secrets, never in code)

- `LICENSE_PRIVATE_KEY` — RSA private key PEM (from `api/config/license.private.pem`)
- `LEMONSQUEEZY_WEBHOOK_SECRET` — from LemonSqueezy dashboard

### wrangler.toml

```toml
name = "cookslate-server"
main = "src/index.js"
compatibility_date = "2026-03-26"

[[d1_databases]]
binding = "DB"
database_name = "cookslate-db"
database_id = "<created-via-mcp>"

[vars]
LEMONSQUEEZY_CHECKOUT_URL = "https://cookslate.lemonsqueezy.com/buy/<product-id>"
GITHUB_REPO_URL = "https://github.com/frobinson47/cookslate"
```

---

## 7. JWT Signing (Web Crypto API)

Cloudflare Workers don't have Node.js `crypto` — they use the Web Crypto API. The signing flow:

1. On first request, import the PEM private key into a `CryptoKey` via `crypto.subtle.importKey()`
2. Cache the imported key in a module-level variable (persists across requests in the same isolate)
3. Sign `header.payload` with `crypto.subtle.sign("RSASSA-PKCS1-v1_5", key, data)`
4. Base64url-encode the signature
5. Return `header.payload.signature`

The PEM needs to be parsed (strip headers, base64-decode to DER) before import. This is a well-known pattern for Workers.

---

## 8. Landing Page

Minimal static HTML served from the Worker. Warm palette (terracotta #C75B39, cream #F5EDE3, sage).

**Content:**
- "Cookslate" heading + "Your recipes. Your way." tagline
- One-liner: "A recipe manager that remembers how you cook — not just what you cook. Self-hosted on any PHP hosting, no Docker required."
- Two-column (or stacked on mobile) feature comparison: Free vs Pro
- "Get Pro — $9.99" button → LemonSqueezy checkout URL (from env var)
- "Self-host free" button → GitHub repo URL (from env var)
- Footer with GitHub link

**Styling:** Inline CSS in the HTML template. No external stylesheets, no build step. Keep it under 5KB.

---

## 9. Success Page

Shown after LemonSqueezy redirects to `/success?order_id=X&email=Y`.

**Flow:**
1. Page loads with a "Loading your license key..." spinner
2. JavaScript calls `/api/license/lookup?order_id=X&email=Y`
3. On success: displays the key in a monospace box with a "Copy to clipboard" button
4. Instructions: "1. Open your Cookslate instance → 2. Settings > License → 3. Paste and Activate"
5. On failure (webhook hasn't fired yet): auto-retry every 2 seconds for up to 10 seconds, then show "Your key is being generated, please refresh in a moment"

**Styling:** Same warm palette as landing page. Inline CSS.

---

## 10. Setup Steps (One-Time)

1. Install wrangler: `npm install -g wrangler`
2. Authenticate: `wrangler login`
3. Create Forgejo repo: `cookslate-server` (private)
4. Create D1 database via Cloudflare MCP
5. Apply schema via MCP D1 query
6. Write Worker code
7. Set secrets: `wrangler secret put LICENSE_PRIVATE_KEY`, `wrangler secret put LEMONSQUEEZY_WEBHOOK_SECRET`
8. Deploy: `wrangler deploy`
9. Configure `cookslate.app` as custom domain in Cloudflare dashboard
10. Create LemonSqueezy account, product ($9.99 one-time), and webhook pointing to `cookslate.app/webhook/lemonsqueezy`
11. Set LemonSqueezy checkout redirect URL to `cookslate.app/success`

---

## 11. What's NOT in Scope

- Stripe integration (Phase B — Cookslate Cloud SaaS)
- Email delivery of license keys (LemonSqueezy sends a receipt; the key is displayed on the success page)
- Admin dashboard for managing licenses (query D1 directly via wrangler or MCP for now)
- Refund handling (handle manually — set `active = 0` in D1 for chargebacks)
- Rate limiting on endpoints (Cloudflare's built-in DDoS protection is sufficient at this scale)
