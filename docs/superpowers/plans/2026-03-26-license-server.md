# Cookslate License Server Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build and deploy a Cloudflare Worker at `cookslate.app` that generates RSA-signed JWT license keys when buyers purchase Cookslate Pro ($9.99) via LemonSqueezy, and serves a minimal landing page.

**Architecture:** Single Cloudflare Worker with D1 database. LemonSqueezy handles checkout and payment. Worker receives webhook, generates JWT, stores in D1. Success page displays the key for the buyer to copy. Verify endpoint handles monthly heartbeats from self-hosted instances.

**Tech Stack:** Cloudflare Workers (JavaScript), D1 (SQLite), Web Crypto API (RSA signing), LemonSqueezy (payments)

**Spec:** `docs/superpowers/specs/2026-03-26-license-server-design.md`

**Working directory:** This is a NEW project. The repo will be created at `D:/laragon/www/cookslate-server/`.

---

## File Structure

```
cookslate-server/
├── src/
│   ├── index.js              — Worker entry point, route dispatcher
│   ├── routes/
│   │   ├── landing.js        — GET / — returns landing page HTML
│   │   ├── success.js        — GET /success — post-checkout key display
│   │   ├── webhook.js        — POST /webhook/lemonsqueezy — HMAC verify + JWT gen
│   │   ├── verify.js         — POST /api/license/verify — heartbeat check
│   │   └── lookup.js         — GET /api/license/lookup — fetch key by order
│   ├── lib/
│   │   └── jwt.js            — PEM parsing + RSA JWT signing via Web Crypto API
│   └── html/
│       ├── landing.html      — Minimal landing page
│       └── success.html      — Post-checkout success page with copy button
├── test/
│   ├── jwt.test.js           — JWT signing tests
│   ├── webhook.test.js       — Webhook HMAC + idempotency tests
│   └── verify.test.js        — Verify endpoint tests
├── schema.sql                — D1 table definition
├── wrangler.toml             — Worker config, D1 binding
├── package.json
├── CLAUDE.md
└── .gitignore
```

---

## Task 1: Project Scaffold

**Files:**
- Create: `D:/laragon/www/cookslate-server/` (entire project directory)
- Create: `package.json`
- Create: `wrangler.toml`
- Create: `schema.sql`
- Create: `.gitignore`
- Create: `CLAUDE.md`

- [ ] **Step 1: Create project directory and initialize git**

```bash
mkdir -p D:/laragon/www/cookslate-server
cd D:/laragon/www/cookslate-server
git init
```

- [ ] **Step 2: Create package.json**

Create `package.json`:
```json
{
  "name": "cookslate-server",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev": "wrangler dev",
    "deploy": "wrangler deploy",
    "test": "node --test test/"
  },
  "devDependencies": {
    "wrangler": "^4.0.0"
  }
}
```

- [ ] **Step 3: Create wrangler.toml**

Create `wrangler.toml`:
```toml
name = "cookslate-server"
main = "src/index.js"
compatibility_date = "2026-03-26"

[[d1_databases]]
binding = "DB"
database_name = "cookslate-db"
database_id = "PLACEHOLDER"

[vars]
LEMONSQUEEZY_CHECKOUT_URL = "https://cookslate.lemonsqueezy.com/buy/PLACEHOLDER"
GITHUB_REPO_URL = "https://github.com/frobinson47/cookslate"
```

Note: `database_id` and checkout URL will be filled in during setup tasks.

Add a `rules` section to enable importing `.html` files as text modules:
```toml
[[rules]]
type = "Text"
globs = ["**/*.html"]
```

- [ ] **Step 4: Create schema.sql**

Create `schema.sql`:
```sql
CREATE TABLE IF NOT EXISTS licenses (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT NOT NULL,
  order_id TEXT UNIQUE NOT NULL,
  license_key TEXT NOT NULL,
  tier TEXT DEFAULT 'pro',
  created_at TEXT DEFAULT (datetime('now')),
  last_verified_at TEXT,
  active INTEGER DEFAULT 1
);

CREATE INDEX IF NOT EXISTS idx_licenses_email ON licenses(email);
CREATE INDEX IF NOT EXISTS idx_licenses_order_id ON licenses(order_id);
```

- [ ] **Step 5: Create .gitignore**

Create `.gitignore`:
```
node_modules/
.dev.vars
.wrangler/
```

- [ ] **Step 6: Create CLAUDE.md**

Create `CLAUDE.md`:
```markdown
# CLAUDE.md

## Project Overview
Cookslate license server — Cloudflare Worker at cookslate.app. Handles Pro license purchases via LemonSqueezy, generates RSA-signed JWT license keys, serves a minimal landing page.

## Commands
- `npm run dev` — local dev server (wrangler dev)
- `npm run deploy` — deploy to Cloudflare
- `npm test` — run tests
- `wrangler d1 execute cookslate-db --command "SQL HERE"` — query D1

## Architecture
Single Worker with D1 database. Routes in `src/routes/`, JWT signing in `src/lib/jwt.js`, HTML templates in `src/html/`.

## Secrets (Cloudflare Worker secrets)
- `LICENSE_PRIVATE_KEY` — RSA private key PEM for JWT signing
- `LEMONSQUEEZY_WEBHOOK_SECRET` — HMAC secret for webhook verification
```

- [ ] **Step 7: Create directory structure**

```bash
mkdir -p src/routes src/lib src/html test
```

- [ ] **Step 8: Install dependencies**

```bash
cd D:/laragon/www/cookslate-server
npm install
```

- [ ] **Step 9: Commit**

```bash
git add -A
git commit -m "chore: scaffold cookslate-server project"
```

---

## Task 2: JWT Signing Library

**Files:**
- Create: `src/lib/jwt.js`
- Create: `test/jwt.test.js`

- [ ] **Step 1: Write the JWT test**

Create `test/jwt.test.js`:
```javascript
import { describe, it, before } from 'node:test';
import assert from 'node:assert/strict';
import { generateKeyPair } from 'node:crypto';
import { promisify } from 'node:util';

const generateKeyPairAsync = promisify(generateKeyPair);

// We test the pure functions (base64url encoding, PEM parsing, JWT structure)
// but can't test Web Crypto signing in Node.js (different API).
// Full integration testing happens via wrangler dev.

describe('JWT utilities', () => {
  it('base64url encodes correctly', async () => {
    // Dynamic import to handle module
    const { base64urlEncode } = await import('../src/lib/jwt.js');
    const input = new TextEncoder().encode('{"alg":"RS256","typ":"JWT"}');
    const result = base64urlEncode(input);
    // Should not contain +, /, or =
    assert.ok(!result.includes('+'), 'should not contain +');
    assert.ok(!result.includes('/'), 'should not contain /');
    assert.ok(!result.includes('='), 'should not contain =');
  });

  it('parsePem strips headers and returns ArrayBuffer', async () => {
    const { parsePem } = await import('../src/lib/jwt.js');
    const fakePem = '-----BEGIN PRIVATE KEY-----\nYUJj\n-----END PRIVATE KEY-----';
    const result = parsePem(fakePem);
    assert.ok(result instanceof ArrayBuffer, 'should return ArrayBuffer');
    assert.ok(result.byteLength > 0, 'should not be empty');
  });

  it('buildJwtPayload creates correct claims', async () => {
    const { buildJwtPayload } = await import('../src/lib/jwt.js');
    const claims = buildJwtPayload('test@example.com', 'pro', 'order_123');
    const parsed = JSON.parse(new TextDecoder().decode(claims));
    assert.equal(parsed.email, 'test@example.com');
    assert.equal(parsed.tier, 'pro');
    assert.equal(parsed.order_id, 'order_123');
    assert.ok(parsed.iat > 0, 'should have iat');
    assert.ok(parsed.exp > parsed.iat, 'exp should be after iat');
    // 100-year expiry check (roughly 3153600000 seconds)
    assert.ok(parsed.exp - parsed.iat > 3_000_000_000, 'exp should be ~100 years from iat');
  });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `cd D:/laragon/www/cookslate-server && npm test`
Expected: FAIL — module not found

- [ ] **Step 3: Implement jwt.js**

Create `src/lib/jwt.js`:
```javascript
/**
 * RSA JWT signing for Cloudflare Workers using Web Crypto API.
 *
 * Exports:
 *   signJwt(privateKeyPem, email, tier, orderId) → JWT string
 *   base64urlEncode(buffer) → string
 *   parsePem(pemString) → ArrayBuffer
 *   buildJwtPayload(email, tier, orderId) → Uint8Array
 */

// Module-level cache for imported CryptoKey
let cachedKey = null;

/**
 * Base64url encode a buffer (no padding).
 */
export function base64urlEncode(buffer) {
  const bytes = buffer instanceof Uint8Array ? buffer : new Uint8Array(buffer);
  let binary = '';
  for (const byte of bytes) {
    binary += String.fromCharCode(byte);
  }
  return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
}

/**
 * Strip PEM headers and decode base64 to ArrayBuffer.
 */
export function parsePem(pem) {
  const lines = pem.split('\n').filter(line =>
    !line.startsWith('-----') && line.trim().length > 0
  );
  const base64 = lines.join('');
  const binary = atob(base64);
  const buffer = new ArrayBuffer(binary.length);
  const view = new Uint8Array(buffer);
  for (let i = 0; i < binary.length; i++) {
    view[i] = binary.charCodeAt(i);
  }
  return buffer;
}

/**
 * Build the JWT payload as a Uint8Array.
 */
export function buildJwtPayload(email, tier, orderId) {
  const now = Math.floor(Date.now() / 1000);
  const hundredYears = 100 * 365.25 * 24 * 60 * 60;
  const payload = {
    email,
    tier,
    order_id: orderId,
    iat: now,
    exp: Math.floor(now + hundredYears),
  };
  return new TextEncoder().encode(JSON.stringify(payload));
}

/**
 * Import a PKCS#8 PEM private key into a CryptoKey (cached).
 */
async function getSigningKey(privateKeyPem) {
  if (cachedKey) return cachedKey;
  const keyData = parsePem(privateKeyPem);
  cachedKey = await crypto.subtle.importKey(
    'pkcs8',
    keyData,
    { name: 'RSASSA-PKCS1-v1_5', hash: 'SHA-256' },
    false,
    ['sign']
  );
  return cachedKey;
}

/**
 * Generate a signed RS256 JWT.
 */
export async function signJwt(privateKeyPem, email, tier, orderId) {
  const key = await getSigningKey(privateKeyPem);

  const header = base64urlEncode(
    new TextEncoder().encode(JSON.stringify({ alg: 'RS256', typ: 'JWT' }))
  );
  const payload = base64urlEncode(buildJwtPayload(email, tier, orderId));
  const signingInput = new TextEncoder().encode(`${header}.${payload}`);

  const signature = await crypto.subtle.sign(
    'RSASSA-PKCS1-v1_5',
    key,
    signingInput
  );

  return `${header}.${payload}.${base64urlEncode(signature)}`;
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `cd D:/laragon/www/cookslate-server && npm test`
Expected: 3 tests pass

- [ ] **Step 5: Commit**

```bash
git add src/lib/jwt.js test/jwt.test.js
git commit -m "feat: add JWT signing library with Web Crypto API"
```

---

## Task 3: Route Dispatcher (Worker Entry Point)

**Files:**
- Create: `src/index.js`

- [ ] **Step 1: Create the route dispatcher**

Create `src/index.js`:
```javascript
import { handleLanding } from './routes/landing.js';
import { handleSuccess } from './routes/success.js';
import { handleWebhook } from './routes/webhook.js';
import { handleVerify } from './routes/verify.js';
import { handleLookup } from './routes/lookup.js';

export default {
  async fetch(request, env, ctx) {
    const url = new URL(request.url);
    const path = url.pathname;
    const method = request.method;

    // CORS preflight for verify endpoint
    if (method === 'OPTIONS' && path === '/api/license/verify') {
      return new Response(null, {
        status: 204,
        headers: {
          'Access-Control-Allow-Origin': '*',
          'Access-Control-Allow-Methods': 'POST, OPTIONS',
          'Access-Control-Allow-Headers': 'Content-Type',
          'Access-Control-Max-Age': '86400',
        },
      });
    }

    try {
      // Route dispatch
      if (path === '/' && method === 'GET') {
        return handleLanding(env);
      }
      if (path === '/success' && method === 'GET') {
        return handleSuccess(env);
      }
      if (path === '/webhook/lemonsqueezy' && method === 'POST') {
        return handleWebhook(request, env);
      }
      if (path === '/api/license/verify' && method === 'POST') {
        return handleVerify(request, env);
      }
      if (path === '/api/license/lookup' && method === 'GET') {
        return handleLookup(url, env);
      }

      return new Response('Not found', { status: 404 });
    } catch (err) {
      console.error('Worker error:', err);
      return Response.json({ error: 'Internal server error' }, { status: 500 });
    }
  },
};
```

- [ ] **Step 2: Create stub route files so the Worker can load**

Create each route file as a stub that returns a placeholder response. These will be implemented in subsequent tasks.

`src/routes/landing.js`:
```javascript
export function handleLanding(env) {
  return new Response('Cookslate — coming soon', {
    headers: { 'Content-Type': 'text/html; charset=utf-8' },
  });
}
```

`src/routes/success.js`:
```javascript
export function handleSuccess(env) {
  return new Response('Success page — coming soon', {
    headers: { 'Content-Type': 'text/html; charset=utf-8' },
  });
}
```

`src/routes/webhook.js`:
```javascript
export async function handleWebhook(request, env) {
  return Response.json({ error: 'Not implemented' }, { status: 501 });
}
```

`src/routes/verify.js`:
```javascript
export async function handleVerify(request, env) {
  return Response.json({ error: 'Not implemented' }, { status: 501 });
}
```

`src/routes/lookup.js`:
```javascript
export async function handleLookup(url, env) {
  return Response.json({ error: 'Not implemented' }, { status: 501 });
}
```

- [ ] **Step 3: Commit**

```bash
git add src/
git commit -m "feat: add route dispatcher and stub route handlers"
```

---

## Task 4: Webhook Handler

**Files:**
- Modify: `src/routes/webhook.js`
- Create: `test/webhook.test.js`

- [ ] **Step 1: Write webhook tests**

Create `test/webhook.test.js`:
```javascript
import { describe, it } from 'node:test';
import assert from 'node:assert/strict';

// Only test the pure data extraction logic. HMAC verification uses Web Crypto
// (not available in Node.js test runner) — tested via wrangler dev integration.
import { extractOrderData } from '../src/routes/webhook.js';

describe('Webhook handler', () => {
  it('extractOrderData pulls email and order_id from LemonSqueezy payload', () => {
    const payload = {
      meta: { event_name: 'order_created' },
      data: {
        id: 'order_abc',
        attributes: {
          user_email: 'buyer@test.com',
          first_order_item: { product_name: 'Cookslate Pro' },
        },
      },
    };
    const result = extractOrderData(payload);
    assert.equal(result.email, 'buyer@test.com');
    assert.equal(result.orderId, 'order_abc');
  });

  it('extractOrderData returns null for non-order_created events', () => {
    const payload = {
      meta: { event_name: 'subscription_created' },
      data: { id: '123', attributes: { user_email: 'a@b.com' } },
    };
    assert.equal(extractOrderData(payload), null);
  });

  it('extractOrderData returns null for missing email', () => {
    const payload = {
      meta: { event_name: 'order_created' },
      data: { id: '123', attributes: {} },
    };
    assert.equal(extractOrderData(payload), null);
  });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `cd D:/laragon/www/cookslate-server && npm test`
Expected: FAIL — functions not exported

- [ ] **Step 3: Implement webhook handler**

Replace `src/routes/webhook.js`:
```javascript
import { signJwt } from '../lib/jwt.js';

/**
 * Extract order data from LemonSqueezy webhook payload.
 * Returns null if this isn't an order_created event.
 * Exported for testing.
 */
export function extractOrderData(payload) {
  if (payload?.meta?.event_name !== 'order_created') {
    return null;
  }
  const email = payload.data?.attributes?.user_email;
  const orderId = String(payload.data?.id || '');
  if (!email || !orderId) return null;
  return { email, orderId };
}

/**
 * Compute HMAC-SHA256 using Web Crypto API (Worker environment).
 */
async function computeHmac(body, secret) {
  const encoder = new TextEncoder();
  const key = await crypto.subtle.importKey(
    'raw',
    encoder.encode(secret),
    { name: 'HMAC', hash: 'SHA-256' },
    false,
    ['sign']
  );
  const sig = await crypto.subtle.sign('HMAC', key, encoder.encode(body));
  return Array.from(new Uint8Array(sig))
    .map(b => b.toString(16).padStart(2, '0'))
    .join('');
}

/**
 * POST /webhook/lemonsqueezy
 */
export async function handleWebhook(request, env) {
  const body = await request.text();
  const signature = request.headers.get('X-Signature') || '';

  // Verify HMAC
  const expectedSig = await computeHmac(body, env.LEMONSQUEEZY_WEBHOOK_SECRET);
  if (expectedSig !== signature) {
    return Response.json({ error: 'Invalid signature' }, { status: 401 });
  }

  // Parse payload
  let payload;
  try {
    payload = JSON.parse(body);
  } catch {
    return Response.json({ error: 'Invalid JSON' }, { status: 400 });
  }

  // Extract order data
  const orderData = extractOrderData(payload);
  if (!orderData) {
    // Not an order_created event — acknowledge and ignore
    return Response.json({ ok: true, message: 'Event ignored' });
  }

  const { email, orderId } = orderData;

  // Idempotency check — if order already exists, return existing key
  const existing = await env.DB.prepare(
    'SELECT license_key FROM licenses WHERE order_id = ?'
  ).bind(orderId).first();

  if (existing) {
    return Response.json({ ok: true, license_key: existing.license_key });
  }

  // Generate JWT
  const jwt = await signJwt(env.LICENSE_PRIVATE_KEY, email, 'pro', orderId);

  // Store in D1
  await env.DB.prepare(
    'INSERT INTO licenses (email, order_id, license_key, tier) VALUES (?, ?, ?, ?)'
  ).bind(email, orderId, jwt, 'pro').run();

  return Response.json({ ok: true, license_key: jwt });
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `cd D:/laragon/www/cookslate-server && npm test`
Expected: All webhook tests pass (the Node.js-compatible tests for HMAC and data extraction)

- [ ] **Step 5: Commit**

```bash
git add src/routes/webhook.js test/webhook.test.js
git commit -m "feat: implement LemonSqueezy webhook handler with HMAC verification"
```

---

## Task 5: Verify and Lookup Endpoints

**Files:**
- Modify: `src/routes/verify.js`
- Modify: `src/routes/lookup.js`
- Create: `test/verify.test.js`

- [ ] **Step 1: Write verify tests**

Create `test/verify.test.js`:
```javascript
import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import { decodeJwtPayload } from '../src/routes/verify.js';

describe('Verify endpoint helpers', () => {
  it('decodeJwtPayload extracts claims from a JWT string', () => {
    // Build a fake JWT (header.payload.signature)
    const payload = { order_id: 'test_123', email: 'a@b.com', tier: 'pro' };
    const b64 = btoa(JSON.stringify(payload)).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
    const fakeJwt = `eyJhbGciOiJSUzI1NiJ9.${b64}.fakesig`;
    const result = decodeJwtPayload(fakeJwt);
    assert.equal(result.order_id, 'test_123');
    assert.equal(result.email, 'a@b.com');
  });

  it('decodeJwtPayload returns null for malformed JWT', () => {
    assert.equal(decodeJwtPayload('not-a-jwt'), null);
    assert.equal(decodeJwtPayload(''), null);
  });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `cd D:/laragon/www/cookslate-server && npm test`
Expected: FAIL — decodeJwtPayload not exported

- [ ] **Step 3: Implement verify.js**

Replace `src/routes/verify.js`:
```javascript
/**
 * Decode the payload section of a JWT without verifying the signature.
 * Exported for testing.
 */
export function decodeJwtPayload(jwt) {
  if (!jwt || typeof jwt !== 'string') return null;
  const parts = jwt.split('.');
  if (parts.length !== 3) return null;
  try {
    const json = atob(parts[1].replace(/-/g, '+').replace(/_/g, '/'));
    return JSON.parse(json);
  } catch {
    return null;
  }
}

/**
 * POST /api/license/verify
 * Self-hosted instances call this monthly to heartbeat their license.
 */
export async function handleVerify(request, env) {
  const corsHeaders = {
    'Access-Control-Allow-Origin': '*',
    'Content-Type': 'application/json',
  };

  let body;
  try {
    body = await request.json();
  } catch {
    return Response.json({ valid: false, reason: 'invalid request' }, { status: 400, headers: corsHeaders });
  }

  const key = body?.key;
  if (!key) {
    return Response.json({ valid: false, reason: 'missing key' }, { status: 400, headers: corsHeaders });
  }

  // Decode JWT to get order_id
  const claims = decodeJwtPayload(key);
  if (!claims?.order_id) {
    return Response.json({ valid: false, reason: 'invalid key format' }, { headers: corsHeaders });
  }

  // Look up in D1
  const row = await env.DB.prepare(
    'SELECT active FROM licenses WHERE order_id = ?'
  ).bind(claims.order_id).first();

  if (!row) {
    return Response.json({ valid: false, reason: 'unknown' }, { headers: corsHeaders });
  }

  if (!row.active) {
    return Response.json({ valid: false, reason: 'revoked' }, { headers: corsHeaders });
  }

  // Update last_verified_at
  await env.DB.prepare(
    'UPDATE licenses SET last_verified_at = datetime(\'now\') WHERE order_id = ?'
  ).bind(claims.order_id).run();

  return Response.json({ valid: true }, { headers: corsHeaders });
}
```

- [ ] **Step 4: Implement lookup.js**

Replace `src/routes/lookup.js`:
```javascript
/**
 * GET /api/license/lookup?order_id=X&email=Y
 * Called by the success page to retrieve the generated license key.
 */
export async function handleLookup(url, env) {
  const orderId = url.searchParams.get('order_id');
  const email = url.searchParams.get('email');

  if (!orderId || !email) {
    return Response.json(
      { error: 'order_id and email are required' },
      { status: 400 }
    );
  }

  const row = await env.DB.prepare(
    'SELECT license_key FROM licenses WHERE order_id = ? AND email = ?'
  ).bind(orderId, email).first();

  if (!row) {
    return Response.json(
      { error: 'License not found — it may still be generating. Please retry.' },
      { status: 404 }
    );
  }

  return Response.json({ license_key: row.license_key });
}
```

- [ ] **Step 5: Run tests**

Run: `cd D:/laragon/www/cookslate-server && npm test`
Expected: All tests pass

- [ ] **Step 6: Commit**

```bash
git add src/routes/verify.js src/routes/lookup.js test/verify.test.js
git commit -m "feat: implement verify and lookup endpoints"
```

---

## Task 6: Landing Page

**Files:**
- Create: `src/html/landing.html`
- Modify: `src/routes/landing.js`

- [ ] **Step 1: Create landing.html**

Create `src/html/landing.html` — a minimal, responsive HTML page with inline CSS. Warm palette (terracotta `#C75B39`, cream `#F5EDE3`, dark `#1A1412`, sage `#6B7F5E`).

Content:
- "Cookslate" heading + "Your recipes. Your way." tagline
- One-liner description
- Two feature columns: Free vs Pro ($9.99)
- "Get Pro — $9.99" button (href will be `{{CHECKOUT_URL}}` placeholder)
- "Self-host free" button (href will be `{{GITHUB_URL}}` placeholder)
- Footer with GitHub link
- Responsive: stacks to single column on mobile
- Total size under 5KB

The HTML should use `{{CHECKOUT_URL}}` and `{{GITHUB_URL}}` as template placeholders that the route handler will replace with env vars.

- [ ] **Step 2: Update landing.js to serve the HTML**

Replace `src/routes/landing.js`:
```javascript
import html from '../html/landing.html';

export function handleLanding(env) {
  const page = html
    .replace(/\{\{CHECKOUT_URL\}\}/g, env.LEMONSQUEEZY_CHECKOUT_URL)
    .replace(/\{\{GITHUB_URL\}\}/g, env.GITHUB_REPO_URL);

  return new Response(page, {
    headers: { 'Content-Type': 'text/html; charset=utf-8' },
  });
}
```

Note: Cloudflare Workers can import `.html` files as strings when using the default module format. If this doesn't work, use `fs` or inline the HTML as a template literal.

- [ ] **Step 3: Commit**

```bash
git add src/html/landing.html src/routes/landing.js
git commit -m "feat: add landing page"
```

---

## Task 7: Success Page

**Files:**
- Create: `src/html/success.html`
- Modify: `src/routes/success.js`

- [ ] **Step 1: Create success.html**

Create `src/html/success.html` — post-checkout page with:
- "Thank you!" heading
- "Loading your license key..." spinner (initial state)
- License key display area (hidden initially): monospace box + "Copy to clipboard" button
- Instructions: "1. Open Cookslate → 2. Settings > License → 3. Paste and Activate"
- Inline JavaScript that:
  - Reads `order_id` and `email` from URL query params
  - Calls `GET /api/license/lookup?order_id=X&email=Y`
  - On success: shows key, enables copy button
  - On 404: retries every 2 seconds, up to 5 retries (10 seconds total)
  - After retries exhausted: shows "Your key is being generated, please refresh in a moment"
- Same warm palette as landing page
- `{{CHECKOUT_URL}}` placeholder not needed here

- [ ] **Step 2: Update success.js to serve the HTML**

Replace `src/routes/success.js`:
```javascript
import html from '../html/success.html';

export function handleSuccess(env) {
  return new Response(html, {
    headers: { 'Content-Type': 'text/html; charset=utf-8' },
  });
}
```

- [ ] **Step 3: Commit**

```bash
git add src/html/success.html src/routes/success.js
git commit -m "feat: add post-checkout success page with key display"
```

---

## Task 8: Create D1 Database and Forgejo Repo

**Files:**
- Modify: `wrangler.toml` (fill in database_id)

This task involves infrastructure setup, not code.

- [ ] **Step 1: Create D1 database via Cloudflare MCP**

Use the Cloudflare MCP tool `d1_database_create` to create a database named `cookslate-db`.

- [ ] **Step 2: Apply schema via MCP**

Use `d1_database_query` to run the SQL from `schema.sql` against the new database.

- [ ] **Step 3: Update wrangler.toml with database ID**

Replace the `PLACEHOLDER` in `wrangler.toml` `database_id` with the actual ID returned from step 1.

- [ ] **Step 4: Create Forgejo repo**

```bash
curl -s -X POST "https://forgejo.familytechlab.com/api/v1/user/repos" \
  -H "Content-Type: application/json" \
  -H "Authorization: token $(cat ~/.forgejo-token)" \
  -d '{"name": "cookslate-server", "private": true, "description": "Cookslate license server — Cloudflare Worker"}'
```

- [ ] **Step 5: Add Forgejo remote and push**

```bash
cd D:/laragon/www/cookslate-server
git remote add origin https://forgejo.familytechlab.com/frank/cookslate-server.git
git push -u origin master
```

- [ ] **Step 6: Commit wrangler.toml update**

```bash
git add wrangler.toml
git commit -m "chore: add D1 database ID to wrangler config"
git push
```

---

## Task 9: Install Wrangler and Deploy

- [ ] **Step 1: Install wrangler globally**

```bash
npm install -g wrangler
```

- [ ] **Step 2: Authenticate with Cloudflare**

```bash
wrangler login
```

This opens a browser for OAuth. Follow the prompts.

- [ ] **Step 3: Set the LICENSE_PRIVATE_KEY secret**

```bash
cd D:/laragon/www/cookslate-server
cat D:/laragon/www/crumble/api/config/license.private.pem | wrangler secret put LICENSE_PRIVATE_KEY
```

Verify the secret was stored:
```bash
wrangler secret list
```
Expected: Shows `LICENSE_PRIVATE_KEY` in the list.

- [ ] **Step 4: Set a temporary LEMONSQUEEZY_WEBHOOK_SECRET**

Until LemonSqueezy is set up, use a placeholder:
```bash
echo "placeholder-will-replace-after-lemonsqueezy-setup" | wrangler secret put LEMONSQUEEZY_WEBHOOK_SECRET
```

- [ ] **Step 5: Deploy**

```bash
cd D:/laragon/www/cookslate-server
wrangler deploy
```

Expected: Deployed successfully, outputs the Worker URL.

- [ ] **Step 6: Test the deployed Worker**

```bash
curl https://cookslate-server.<your-account>.workers.dev/
```

Expected: Returns the landing page HTML.

```bash
curl -X POST https://cookslate-server.<your-account>.workers.dev/api/license/verify -H "Content-Type: application/json" -d '{"key":"fake"}'
```

Expected: Returns `{"valid":false,"reason":"invalid key format"}`

- [ ] **Step 7: Configure cookslate.app custom domain**

This is done in the Cloudflare dashboard:
1. Go to Workers & Pages > cookslate-server > Settings > Triggers
2. Add Custom Domain: `cookslate.app`
3. Cloudflare will automatically configure DNS

After this, `https://cookslate.app/` should serve the landing page.

---

## Task 10: LemonSqueezy Setup

This is a manual configuration task — no code changes.

- [ ] **Step 1: Create LemonSqueezy account**

Go to https://lemonsqueezy.com and sign up.

- [ ] **Step 2: Create a Store**

Name: "Cookslate"

- [ ] **Step 3: Create a Product**

- Name: "Cookslate Pro"
- Price: $9.99 (one-time payment)
- Description: "Unlock meal planning, cook tracking stats, recipe annotations, multi-user support, data export, and PWA offline mode for your self-hosted Cookslate instance."
- Redirect after purchase URL: `https://cookslate.app/success?order_id={order_id}&email={user_email}` (LemonSqueezy replaces `{order_id}` and `{user_email}` with actual values)

- [ ] **Step 4: Create a Webhook**

- URL: `https://cookslate.app/webhook/lemonsqueezy`
- Events: `order_created`
- Copy the webhook signing secret

- [ ] **Step 5: Update the Worker secret with real webhook secret**

```bash
echo "<paste-real-secret-here>" | wrangler secret put LEMONSQUEEZY_WEBHOOK_SECRET
```

- [ ] **Step 6: Update wrangler.toml checkout URL**

Replace the `LEMONSQUEEZY_CHECKOUT_URL` placeholder in `wrangler.toml` with the actual LemonSqueezy checkout URL for the product.

```bash
cd D:/laragon/www/cookslate-server
# Edit wrangler.toml with the real checkout URL
wrangler deploy
git add wrangler.toml
git commit -m "chore: add LemonSqueezy checkout URL"
git push
```

---

## Task 11: End-to-End Verification

- [ ] **Step 1: Test the landing page**

Visit `https://cookslate.app/` in a browser. Verify:
- Page loads with correct branding
- "Get Pro" button links to LemonSqueezy checkout
- "Self-host free" button links to GitHub repo
- Page is responsive on mobile

- [ ] **Step 2: Test the webhook with a simulated payload**

```bash
# Generate a test HMAC signature
SECRET="<your-real-webhook-secret>"
BODY='{"meta":{"event_name":"order_created"},"data":{"id":"test_order_001","attributes":{"user_email":"test@example.com"}}}'
SIG=$(echo -n "$BODY" | openssl dgst -sha256 -hmac "$SECRET" | awk '{print $2}')

curl -X POST https://cookslate.app/webhook/lemonsqueezy \
  -H "Content-Type: application/json" \
  -H "X-Signature: $SIG" \
  -d "$BODY"
```

Expected: `{"ok":true,"license_key":"eyJhbG..."}`

- [ ] **Step 3: Test the lookup endpoint**

```bash
curl "https://cookslate.app/api/license/lookup?order_id=test_order_001&email=test@example.com"
```

Expected: `{"license_key":"eyJhbG..."}`

- [ ] **Step 4: Test the verify endpoint**

Use the JWT from step 2:
```bash
curl -X POST https://cookslate.app/api/license/verify \
  -H "Content-Type: application/json" \
  -d '{"key":"<jwt-from-step-2>"}'
```

Expected: `{"valid":true}`

- [ ] **Step 5: Test the full key in Cookslate**

Copy the JWT from step 2, go to your local Cookslate instance (cookslate.fmr.local), navigate to Settings > License, paste the key, click Activate. Pro features should unlock.

- [ ] **Step 6: Clean up test data**

```bash
wrangler d1 execute cookslate-db --command "DELETE FROM licenses WHERE order_id = 'test_order_001'"
```

- [ ] **Step 7: Test a real LemonSqueezy purchase (optional)**

If LemonSqueezy is in test mode, make a test purchase through the actual checkout flow and verify the full end-to-end: checkout → webhook → success page → key display → activate in Cookslate.
