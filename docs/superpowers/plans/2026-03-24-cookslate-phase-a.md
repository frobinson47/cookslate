# Cookslate Phase A: Rename + Open Core Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rename Crumble to Cookslate, implement license key gating, split existing features into free/pro tiers, and create a public-ready README.

**Architecture:** The rename is a find-and-replace across ~35 files. The license system adds a new `License` model that reads a signed JWT from `config/license.key` and exposes `License::isActive()`. Pro features (meal planning, stats, annotations, export, multi-user) are moved into `api/pro/` and `frontend/src/pro/` directories, conditionally loaded by the router and React app based on license status.

**Tech Stack:** PHP 8 (backend), React 18 + Vite + Tailwind CSS 4 (frontend), MySQL, JWT (license keys)

**Spec:** `docs/superpowers/specs/2026-03-24-cookslate-rename-and-monetization-design.md`

---

## File Structure Overview

### New Files
- `api/config/license.php` — License model: reads key, validates JWT, caches status
- `api/config/license.key` — (gitignored) Plain text license key file
- `api/pro/controllers/MealPlanController.php` — Moved from `api/controllers/`
- `api/pro/controllers/StatsController.php` — Moved from `api/controllers/`
- `api/pro/controllers/ExportController.php` — New: JSON-LD + Cooklang export
- `api/pro/models/MealPlan.php` — Moved from `api/models/`
- `api/pro/models/RecipeAnnotation.php` — Moved from `api/models/`
- `frontend/src/pro/pages/MealPlanPage.jsx` — Moved from `frontend/src/pages/`
- `frontend/src/pro/pages/StatsPage.jsx` — Moved from `frontend/src/pages/`
- `frontend/src/pro/components/recipe/AnnotationNote.jsx` — Moved from `frontend/src/components/recipe/`
- `frontend/src/pro/hooks/useLicense.js` — Hook to check license status
- `frontend/src/pages/SettingsPage.jsx` — New: License activation UI
- `LICENSE` — MIT license for free-tier code
- `LICENSE-BSL.md` — BSL 1.1 license for pro-tier code
- `README.md` — Public-facing README with screenshots, install guide, feature list
- `.env.example` — Root-level env example (references api/.env.example)

### Modified Files
- `api/index.php` — Rename references, add license routes, conditionally load pro routes
- `api/config/constants.php` — APP_NAME → 'Cookslate'
- `api/.env.example` — Rename header
- `frontend/index.html` — Title, icon refs, localStorage key
- `frontend/vite.config.js` — PWA manifest name, proxy target
- `frontend/package.json` — Package name
- `frontend/src/App.jsx` — Conditional pro routes based on license status
- `frontend/src/components/layout/Header.jsx` — Brand text
- `frontend/src/components/layout/Sidebar.jsx` — Brand text
- `frontend/src/components/layout/Layout.jsx` — GitHub link
- `frontend/src/pages/HomePage.jsx` — Welcome text
- `frontend/src/pages/LoginPage.jsx` — Brand text, GitHub link
- `frontend/src/pages/SharedRecipePage.jsx` — Brand text
- `frontend/src/hooks/useDocumentTitle.js` — BASE_TITLE
- `frontend/src/hooks/useTheme.js` — localStorage key
- `frontend/src/hooks/useRecentlyViewed.js` — localStorage key
- `frontend/src/components/recipe/CookMode.jsx` — localStorage key
- `frontend/src/components/recipe/RecipeForm.jsx` — localStorage key
- `frontend/src/components/ui/Timer.jsx` — Icon ref, notification tag
- `frontend/src/pages/AdminPage.jsx` — Export filename
- `frontend/src/pages/GroceryPage.jsx` — localStorage key
- `frontend/src/styles/print.css` — Watermark text
- `frontend/src/utils/recipeCardGenerator.js` — Canvas text
- `.htaccess` — URL path references
- `.gitignore` — Add config/license.key
- `CLAUDE.md` — Update project description
- `api/controllers/RecipeController.php` — Export filename, temp file prefix
- `api/controllers/OAuthController.php` — Error log prefix
- `api/middleware/RateLimiter.php` — Temp dir prefix
- `api/services/ImageProcessor.php` — Temp file prefix
- `api/install.php` — Brand references
- `api/composer.json` — Package name

---

## Task 1: Backend Rename — Constants and Config

**Files:**
- Modify: `api/config/constants.php`
- Modify: `api/.env.example`
- Modify: `api/composer.json`
- Modify: `api/install.php`

- [ ] **Step 1: Update APP_NAME constant**

In `api/config/constants.php`, change line 2:
```php
define('APP_NAME', 'Cookslate');
```

- [ ] **Step 2: Update .env.example header**

In `api/.env.example`, change the comment on line 1:
```
# Cookslate Environment Configuration
```

- [ ] **Step 3: Update composer.json package name**

In `api/composer.json`, change the name field:
```json
"name": "cookslate/api",
```

- [ ] **Step 4: Update install.php brand references**

In `api/install.php`:
- Line 3: Change comment to `* Cookslate Install Wizard`
- Line 31: Change to `'Cookslate is already installed. Delete install.php for security.'`
- Line 95: Change default db name to `'cookslate_db'` (only affects new installs)
- Line 195: Change to `'message' => "Cookslate installed! Admin user..."`

- [ ] **Step 5: Update error log prefixes in index.php**

In `api/index.php`:
- Line 4: Change comment to `* Cookslate API Router / Entry Point`
- Line 67: Change comment to `// Try multiple base paths (Caddy proxy sends /api/*, direct access sends /cookslate/api/*)`
- Line 68: Add `'/cookslate/api'` to the basePaths array (keep `/crumble/api` for backwards compat):
  ```php
  $basePaths = ['/cookslate/api', '/crumble/api', '/api'];
  ```
- Line 540: Change to `error_log('Cookslate DB Error: ' . $e->getMessage());`
- Line 548: Change to `error_log('Cookslate Error: ' . $e->getMessage());`

- [ ] **Step 6: Update error log prefixes in OAuthController.php**

In `api/controllers/OAuthController.php`, change all `'Crumble OAuth2` prefixes to `'Cookslate OAuth2`:
- Line 124, 138, 224, 254

- [ ] **Step 7: Update temp file/dir prefixes**

In `api/controllers/RecipeController.php`:
- Line 383: Change `'crumble_export_'` to `'cookslate_export_'`
- Line 431: Change `"# Crumble Recipe Export\n\n"` to `"# Cookslate Recipe Export\n\n"`
- Line 450: Change `"crumble-export-"` to `"cookslate-export-"`

In `api/services/ImageProcessor.php`:
- Line 165: Change `'crumble_img_'` to `'cookslate_img_'`

In `api/middleware/RateLimiter.php`:
- Line 9: Change `'/crumble_ratelimit'` to `'/cookslate_ratelimit'`

- [ ] **Step 8: Run backend tests to verify nothing broke**

Run: `cd D:/laragon/www/crumble/api && vendor/bin/phpunit`
Expected: All tests pass (temp dir prefixes in tests will still work since they use their own prefixes)

- [ ] **Step 9: Commit**

```bash
git add api/config/constants.php api/.env.example api/composer.json api/install.php api/index.php api/controllers/OAuthController.php api/controllers/RecipeController.php api/services/ImageProcessor.php api/middleware/RateLimiter.php
git commit -m "refactor: rename backend brand references from Crumble to Cookslate"
```

---

## Task 2: Frontend Rename — Display Text and Config

**Files:**
- Modify: `frontend/index.html`
- Modify: `frontend/vite.config.js`
- Modify: `frontend/package.json`
- Modify: `frontend/src/hooks/useDocumentTitle.js`
- Modify: `frontend/src/components/layout/Header.jsx`
- Modify: `frontend/src/components/layout/Sidebar.jsx`
- Modify: `frontend/src/components/layout/Layout.jsx`
- Modify: `frontend/src/pages/HomePage.jsx`
- Modify: `frontend/src/pages/LoginPage.jsx`
- Modify: `frontend/src/pages/SharedRecipePage.jsx`
- Modify: `frontend/src/pages/AdminPage.jsx`
- Modify: `frontend/src/utils/recipeCardGenerator.js`
- Modify: `frontend/src/styles/print.css`

- [ ] **Step 1: Update index.html**

In `frontend/index.html`:
- Line 7: Change `href="/crumble_icon.png"` to `href="/cookslate_icon.png"`
- Line 14: Change `<title>Crumble - Recipe Manager</title>` to `<title>Cookslate - Recipe Manager</title>`
- Line 18: Change `'crumble-theme'` to `'cookslate-theme'`

Note: Rename the actual icon file `frontend/public/crumble_icon.png` to `frontend/public/cookslate_icon.png` (if it exists).

- [ ] **Step 2: Update vite.config.js**

In `frontend/vite.config.js`:
- Line 44: Change `name: 'Crumble'` to `name: 'Cookslate'`
- Line 45: Change `short_name: 'Crumble'` to `short_name: 'Cookslate'`
- Line 46: Change description to `'Your recipes. Your way.'`
- Lines 79, 83: Change proxy target to `'http://cookslate.fmr.local'`

Note: The Laragon vhost will need to be updated separately — not part of this plan.

- [ ] **Step 3: Update package.json**

In `frontend/package.json`:
- Line 2: Change `"name": "crumble-frontend"` to `"name": "cookslate-frontend"`

Then regenerate the lock file:
```bash
cd D:/laragon/www/crumble/frontend && npm install
```

- [ ] **Step 4: Update display text in layout components**

In `frontend/src/hooks/useDocumentTitle.js`:
- Line 3: Change `const BASE_TITLE = 'Crumble'` to `const BASE_TITLE = 'Cookslate'`

In `frontend/src/components/layout/Header.jsx`:
- Line 56: Change `Crumble` text to `Cookslate`

In `frontend/src/components/layout/Sidebar.jsx`:
- Line 43: Change `Crumble` text to `Cookslate`

In `frontend/src/components/layout/Layout.jsx`:
- Line 77: Change GitHub link to `"https://github.com/frobinson47/cookslate"`

- [ ] **Step 5: Update page-level brand text**

In `frontend/src/pages/HomePage.jsx`:
- Line 526: Change `Welcome to Crumble` to `Welcome to Cookslate`

In `frontend/src/pages/LoginPage.jsx`:
- Line 71: Change `Crumble` heading to `Cookslate`
- Line 158: Change GitHub link to `"https://github.com/frobinson47/cookslate"`

In `frontend/src/pages/SharedRecipePage.jsx`:
- Lines 53, 90, 224: Change all `Crumble` text to `Cookslate`

In `frontend/src/pages/AdminPage.jsx`:
- Line 143: Change `crumble-export-` to `cookslate-export-`

- [ ] **Step 6: Update print/canvas brand references**

In `frontend/src/styles/print.css`:
- Line 341: Change comment to `/* ===== COOKSLATE WATERMARK ===== */`
- Line 344: Change `content: "Crumble — crumble.fmr.local"` to `content: "Cookslate — cookslate.fmr.local"`

In `frontend/src/utils/recipeCardGenerator.js`:
- Line 10: Change comment to `// Colors matching Cookslate design tokens`
- Line 245: Change to `ctx.fillText('Made with Cookslate', PADDING, y + 18);`

In `frontend/src/components/ui/Timer.jsx`:
- Line 16: Change `icon: '/crumble_icon.PNG'` to `icon: '/cookslate_icon.png'`
- Line 17: Change `tag: \`crumble-timer-` to `tag: \`cookslate-timer-`

- [ ] **Step 7: Run frontend lint**

Run: `cd D:/laragon/www/crumble/frontend && npm run lint`
Expected: No errors

- [ ] **Step 8: Commit**

```bash
git add frontend/
git commit -m "refactor: rename frontend brand references from Crumble to Cookslate"
```

---

## Task 3: Frontend Rename — localStorage Keys

**Important:** Changing localStorage keys means existing users lose their stored preferences (theme, cook progress, drafts, recently viewed, grocery grouping). We add a one-time migration that copies old keys to new keys.

**Files:**
- Create: `frontend/src/utils/storageMigration.js`
- Modify: `frontend/src/hooks/useTheme.js`
- Modify: `frontend/src/hooks/useRecentlyViewed.js`
- Modify: `frontend/src/components/recipe/CookMode.jsx`
- Modify: `frontend/src/components/recipe/RecipeForm.jsx`
- Modify: `frontend/src/pages/GroceryPage.jsx`
- Modify: `frontend/src/App.jsx` (or main.jsx)

- [ ] **Step 1: Create storage migration utility**

Create `frontend/src/utils/storageMigration.js`:
```javascript
const KEY_MAP = {
  'crumble-theme': 'cookslate-theme',
  'crumble_recently_viewed': 'cookslate_recently_viewed',
  'crumble-cook-progress': 'cookslate-cook-progress',
  'crumble-recipe-draft': 'cookslate-recipe-draft',
  'crumble-grocery-grouped': 'cookslate-grocery-grouped',
};

export function migrateLocalStorage() {
  if (localStorage.getItem('cookslate-storage-migrated')) return;
  for (const [oldKey, newKey] of Object.entries(KEY_MAP)) {
    const value = localStorage.getItem(oldKey);
    if (value !== null && localStorage.getItem(newKey) === null) {
      localStorage.setItem(newKey, value);
      localStorage.removeItem(oldKey);
    }
  }
  localStorage.setItem('cookslate-storage-migrated', '1');
}
```

- [ ] **Step 2: Call migration on app startup**

In the app entry point (e.g., `frontend/src/App.jsx` or `frontend/src/main.jsx`), import and call at the top level:
```javascript
import { migrateLocalStorage } from './utils/storageMigration';
migrateLocalStorage();
```

- [ ] **Step 3: Update all localStorage key references**

In `frontend/src/hooks/useTheme.js`:
- Change `'crumble-theme'` to `'cookslate-theme'`

In `frontend/src/hooks/useRecentlyViewed.js`:
- Change `'crumble_recently_viewed'` to `'cookslate_recently_viewed'`

In `frontend/src/components/recipe/CookMode.jsx`:
- Change `'crumble-cook-progress'` to `'cookslate-cook-progress'`

In `frontend/src/components/recipe/RecipeForm.jsx`:
- Change `'crumble-recipe-draft'` to `'cookslate-recipe-draft'`

In `frontend/src/pages/GroceryPage.jsx`:
- Change `'crumble-grocery-grouped'` to `'cookslate-grocery-grouped'`

Also update `frontend/index.html` line 18 (the pre-render theme script):
- Change `'crumble-theme'` to `'cookslate-theme'`

- [ ] **Step 4: Run frontend lint and dev server smoke test**

Run: `cd D:/laragon/www/crumble/frontend && npm run lint`
Expected: No errors

Run: `cd D:/laragon/www/crumble/frontend && npm run build`
Expected: Build succeeds

- [ ] **Step 5: Commit**

```bash
git add frontend/src/utils/storageMigration.js frontend/src/hooks/ frontend/src/components/recipe/CookMode.jsx frontend/src/components/recipe/RecipeForm.jsx frontend/src/pages/GroceryPage.jsx frontend/src/App.jsx frontend/index.html
git commit -m "refactor: migrate localStorage keys from crumble to cookslate"
```

---

## Task 4: Root Files Rename and Documentation

**Files:**
- Modify: `.htaccess`
- Modify: `.gitignore`
- Modify: `CLAUDE.md`
- Modify: `docs/roadmap.md` (header only)
- Modify: `docs/future_ideas.md` (header only)

- [ ] **Step 1: Update .htaccess**

In `.htaccess`, add cookslate paths alongside crumble paths for backwards compat:
- Line 10: Change `^/crumble/uploads/` to `^/(cookslate|crumble)/uploads/`
- Line 14: Change `^/crumble/frontend/dist/` to `^/(cookslate|crumble)/frontend/dist/`

- [ ] **Step 2: Update .gitignore**

Add to `.gitignore`:
```
api/config/license.key
```

- [ ] **Step 3: Update CLAUDE.md**

Replace all instances of "Crumble" with "Cookslate" and "crumble" with "cookslate" in `CLAUDE.md`. Key changes:
- "Crumble is a self-hosted recipe management app" → "Cookslate is a self-hosted recipe management app"
- `crumble.fmr.local` → `cookslate.fmr.local`
- `/crumble/api/` → `/cookslate/api/` (note: both prefixes are now supported)

- [ ] **Step 4: Update docs headers**

In `docs/roadmap.md`:
- Line 1: Change `# Crumble Roadmap` to `# Cookslate Roadmap`

In `docs/future_ideas.md`:
- Line 1: Change `# Crumble — Future Ideas` to `# Cookslate — Future Ideas`

Leave the body text of docs as-is — they are historical documents and "Crumble" references in the competitive analysis and deep dives are accurate for when they were written.

- [ ] **Step 5: Commit**

```bash
git add .htaccess .gitignore CLAUDE.md docs/roadmap.md docs/future_ideas.md
git commit -m "refactor: rename root files and docs from Crumble to Cookslate"
```

---

## Task 5: License Model — Backend

**Files:**
- Create: `api/config/license.php`
- Modify: `api/index.php` (add license status route)
- Modify: `.gitignore` (already done in Task 4)

- [ ] **Step 1: Write the License model test**

Create `api/tests/Unit/LicenseTest.php`:
```php
<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/license.php';

class LicenseTest extends TestCase
{
    private string $testKeyFile;
    private string $testPublicKey;
    private string $testPrivateKey;

    protected function setUp(): void
    {
        $this->testKeyFile = sys_get_temp_dir() . '/cookslate_test_license_' . uniqid() . '.key';

        // Generate a test RSA key pair
        $config = ['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA];
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $details = openssl_pkey_get_details($res);
        $this->testPrivateKey = $privateKey;
        $this->testPublicKey = $details['key'];
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testKeyFile)) {
            unlink($this->testKeyFile);
        }
    }

    public function testNoKeyFileReturnsInactive(): void
    {
        $license = new License('/nonexistent/path.key', $this->testPublicKey);
        $this->assertFalse($license->isActive());
        $this->assertEquals('free', $license->tier());
    }

    public function testEmptyKeyFileReturnsInactive(): void
    {
        file_put_contents($this->testKeyFile, '');
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertFalse($license->isActive());
    }

    public function testValidJwtReturnsActive(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro', 'email' => 'test@example.com']);
        file_put_contents($this->testKeyFile, $jwt);
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertTrue($license->isActive());
        $this->assertEquals('pro', $license->tier());
    }

    public function testExpiredJwtReturnsInactive(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro', 'exp' => time() - 3600]);
        file_put_contents($this->testKeyFile, $jwt);
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertFalse($license->isActive());
    }

    public function testTamperedJwtReturnsInactive(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro']);
        file_put_contents($this->testKeyFile, $jwt . 'tampered');
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertFalse($license->isActive());
    }

    public function testStatusReturnsStructuredArray(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro', 'email' => 'user@test.com']);
        file_put_contents($this->testKeyFile, $jwt);
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $status = $license->status();
        $this->assertTrue($status['active']);
        $this->assertEquals('pro', $status['tier']);
        $this->assertEquals('user@test.com', $status['email']);
    }

    private function createTestJwt(array $claims): string
    {
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $defaults = ['tier' => 'pro', 'email' => 'test@example.com', 'iat' => time(), 'exp' => time() + 86400 * 365];
        $payload = json_encode(array_merge($defaults, $claims));

        $base64Header = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64Payload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        $signingInput = "$base64Header.$base64Payload";
        openssl_sign($signingInput, $signature, $this->testPrivateKey, OPENSSL_ALGO_SHA256);
        $base64Sig = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64Header.$base64Payload.$base64Sig";
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

Run: `cd D:/laragon/www/crumble/api && vendor/bin/phpunit tests/Unit/LicenseTest.php`
Expected: FAIL — `License` class not found

- [ ] **Step 3: Implement the License model**

Create `api/config/license.php`:
```php
<?php

class License
{
    private bool $active = false;
    private string $tier = 'free';
    private string $email = '';
    private array $claims = [];

    public function __construct(?string $keyFilePath = null, ?string $publicKey = null)
    {
        $keyFile = $keyFilePath ?? __DIR__ . '/license.key';
        $pubKey = $publicKey ?? $this->bundledPublicKey();

        if (!file_exists($keyFile)) {
            return;
        }

        $jwt = trim(file_get_contents($keyFile));
        if (empty($jwt)) {
            return;
        }

        $this->claims = $this->validateJwt($jwt, $pubKey);
        if (!empty($this->claims)) {
            $this->active = true;
            $this->tier = $this->claims['tier'] ?? 'pro';
            $this->email = $this->claims['email'] ?? '';
        }
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function tier(): string
    {
        return $this->tier;
    }

    public function status(): array
    {
        return [
            'active' => $this->active,
            'tier' => $this->tier,
            'email' => $this->email,
        ];
    }

    private function validateJwt(string $jwt, string $publicKey): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return [];
        }

        [$base64Header, $base64Payload, $base64Sig] = $parts;

        // Verify signature
        $signingInput = "$base64Header.$base64Payload";
        $signature = base64_decode(strtr($base64Sig, '-_', '+/'));
        if ($signature === false) {
            return [];
        }

        $valid = openssl_verify($signingInput, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($valid !== 1) {
            return [];
        }

        // Decode payload
        $payload = json_decode(base64_decode(strtr($base64Payload, '-_', '+/')), true);
        if (!is_array($payload)) {
            return [];
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return [];
        }

        return $payload;
    }

    private function bundledPublicKey(): string
    {
        $keyFile = __DIR__ . '/license.pub';
        if (file_exists($keyFile)) {
            return file_get_contents($keyFile);
        }
        // No public key bundled — offline validation disabled
        return '';
    }

    /**
     * Singleton for use in route gating.
     */
    private static ?License $instance = null;

    public static function getInstance(): License
    {
        if (self::$instance === null) {
            self::$instance = new License();
        }
        return self::$instance;
    }

    /**
     * Reset the singleton (used after activate/deactivate).
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Convenience static method for route guards.
     */
    public static function checkActive(): bool
    {
        return self::getInstance()->isActive();
    }
}
```

- [ ] **Step 4: Run the test to verify it passes**

Run: `cd D:/laragon/www/crumble/api && vendor/bin/phpunit tests/Unit/LicenseTest.php`
Expected: All 6 tests pass

- [ ] **Step 5: Add license routes to the router**

In `api/index.php`, add a new case in the switch statement (after the `admin` case, before the root case):

```php
        // ── License Routes ──────────────────────────────────────────────
        case 'license':
            require_once __DIR__ . '/config/license.php';

            if ($id === 'status' && $method === 'GET') {
                // GET /license/status — returns license tier info
                $license = License::getInstance();
                $response = $license->status();
            } elseif ($id === 'activate' && $method === 'POST') {
                // POST /license/activate — writes key and validates
                require_once __DIR__ . '/middleware/Auth.php';
                Auth::requireAdmin();

                $data = json_decode(file_get_contents('php://input'), true);
                $key = trim($data['key'] ?? '');
                if (empty($key)) {
                    http_response_code(400);
                    $response = ['error' => 'License key is required'];
                } else {
                    $keyFile = __DIR__ . '/config/license.key';
                    file_put_contents($keyFile, $key);
                    // Validate the written key
                    $license = new License($keyFile);
                    if ($license->isActive()) {
                        $response = ['message' => 'License activated', 'status' => $license->status()];
                    } else {
                        unlink($keyFile);
                        http_response_code(400);
                        $response = ['error' => 'Invalid license key'];
                    }
                }
            } elseif ($id === 'deactivate' && $method === 'POST') {
                // POST /license/deactivate — removes key
                require_once __DIR__ . '/middleware/Auth.php';
                Auth::requireAdmin();

                $keyFile = __DIR__ . '/config/license.key';
                if (file_exists($keyFile)) {
                    unlink($keyFile);
                }
                License::reset();
                $response = ['message' => 'License deactivated'];
            }
            break;
```

- [ ] **Step 6: Run all backend tests**

Run: `cd D:/laragon/www/crumble/api && vendor/bin/phpunit`
Expected: All tests pass

- [ ] **Step 7: Commit**

```bash
git add api/config/license.php api/tests/Unit/LicenseTest.php api/index.php .gitignore
git commit -m "feat: add License model with JWT validation and API routes"
```

---

## Task 6: License UI — Frontend Settings Page

**Files:**
- Create: `frontend/src/pro/hooks/useLicense.js`
- Create: `frontend/src/pages/SettingsPage.jsx`
- Modify: `frontend/src/App.jsx` (add settings route)
- Modify: `frontend/src/components/layout/Sidebar.jsx` (add settings link)

- [ ] **Step 1: Create the useLicense hook**

Create `frontend/src/pro/hooks/useLicense.js`:
```javascript
import { useState, useEffect, createContext, useContext } from 'react';
import api from '../../services/api';

const LicenseContext = createContext({ active: false, tier: 'free', email: '', loading: true });

export function LicenseProvider({ children }) {
  const [license, setLicense] = useState({ active: false, tier: 'free', email: '', loading: true });

  useEffect(() => {
    api.get('/license/status')
      .then(data => setLicense({ ...data, loading: false }))
      .catch(() => setLicense(prev => ({ ...prev, loading: false })));
  }, []);

  const refresh = () => {
    api.get('/license/status')
      .then(data => setLicense({ ...data, loading: false }));
  };

  return (
    <LicenseContext.Provider value={{ ...license, refresh }}>
      {children}
    </LicenseContext.Provider>
  );
}

export function useLicense() {
  return useContext(LicenseContext);
}
```

- [ ] **Step 2: Create the Settings page**

Create `frontend/src/pages/SettingsPage.jsx`:
```jsx
import { useState } from 'react';
import { KeyRound, Check, X } from 'lucide-react';
import { useLicense } from '../pro/hooks/useLicense';
import api from '../services/api';

export default function SettingsPage() {
  const { active, tier, email, refresh } = useLicense();
  const [key, setKey] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const handleActivate = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);
    try {
      const result = await api.post('/license/activate', { key: key.trim() });
      setSuccess(result.message);
      setKey('');
      refresh();
    } catch (err) {
      setError(err.message || 'Invalid license key');
    } finally {
      setLoading(false);
    }
  };

  const handleDeactivate = async () => {
    setLoading(true);
    try {
      await api.post('/license/deactivate');
      setSuccess('License deactivated');
      refresh();
    } catch (err) {
      setError(err.message || 'Failed to deactivate');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-2xl mx-auto">
      <h1 className="text-2xl font-bold font-display text-warm-900 dark:text-warm-100 mb-6">Settings</h1>

      <div className="bg-white dark:bg-warm-800 rounded-xl p-6 shadow-sm border border-warm-200 dark:border-warm-700">
        <div className="flex items-center gap-3 mb-4">
          <KeyRound className="w-5 h-5 text-terracotta" />
          <h2 className="text-lg font-semibold text-warm-900 dark:text-warm-100">License</h2>
        </div>

        {active ? (
          <div>
            <div className="flex items-center gap-2 text-green-600 dark:text-green-400 mb-2">
              <Check className="w-4 h-4" />
              <span className="font-medium">Cookslate Pro — Active</span>
            </div>
            <p className="text-sm text-warm-500 mb-4">Licensed to {email}</p>
            <button
              onClick={handleDeactivate}
              disabled={loading}
              className="text-sm text-warm-500 hover:text-red-500 transition-colors"
            >
              Deactivate license
            </button>
          </div>
        ) : (
          <div>
            <p className="text-sm text-warm-600 dark:text-warm-400 mb-4">
              Enter your license key to unlock Pro features: meal planning, cook stats, annotations, multi-user support, and more.
            </p>
            <form onSubmit={handleActivate} className="flex gap-2">
              <input
                type="text"
                value={key}
                onChange={(e) => setKey(e.target.value)}
                placeholder="Paste your license key"
                className="flex-1 px-3 py-2 text-sm border border-warm-300 dark:border-warm-600 rounded-lg bg-white dark:bg-warm-700 text-warm-900 dark:text-warm-100"
              />
              <button
                type="submit"
                disabled={loading || !key.trim()}
                className="px-4 py-2 text-sm font-medium text-white bg-terracotta rounded-lg hover:bg-terracotta/90 disabled:opacity-50"
              >
                Activate
              </button>
            </form>
          </div>
        )}

        {error && (
          <div className="mt-3 flex items-center gap-2 text-red-500 text-sm">
            <X className="w-4 h-4" />
            {error}
          </div>
        )}
        {success && (
          <div className="mt-3 flex items-center gap-2 text-green-600 text-sm">
            <Check className="w-4 h-4" />
            {success}
          </div>
        )}
      </div>

      <p className="mt-4 text-center text-sm text-warm-400">
        <a href="https://cookslate.com" target="_blank" rel="noopener noreferrer" className="hover:text-terracotta transition-colors">
          Get a license at cookslate.com
        </a>
      </p>
    </div>
  );
}
```

- [ ] **Step 3: Wire up LicenseProvider and routes**

In `frontend/src/App.jsx`:
- Import `LicenseProvider` from `./pro/hooks/useLicense`
- Wrap the app content with `<LicenseProvider>...</LicenseProvider>`
- Import `SettingsPage` and add a route: `<Route path="/settings" element={<SettingsPage />} />`

In `frontend/src/components/layout/Sidebar.jsx`:
- Add a "Settings" link with the `Settings` icon from lucide-react, pointing to `/settings`

- [ ] **Step 4: Run frontend lint and build**

Run: `cd D:/laragon/www/crumble/frontend && npm run lint && npm run build`
Expected: Both pass

- [ ] **Step 5: Commit**

```bash
git add frontend/src/pro/hooks/useLicense.js frontend/src/pages/SettingsPage.jsx frontend/src/App.jsx frontend/src/components/layout/Sidebar.jsx
git commit -m "feat: add license activation UI and settings page"
```

---

## Task 7: Pro Tier Split — Move Backend Pro Features

**Files:**
- Create: `api/pro/controllers/MealPlanController.php` (moved)
- Create: `api/pro/controllers/StatsController.php` (moved)
- Create: `api/pro/models/MealPlan.php` (moved)
- Create: `api/pro/models/RecipeAnnotation.php` (moved)
- Modify: `api/index.php` — gate pro routes behind License::checkActive()

- [ ] **Step 1: Create pro directory structure**

```bash
mkdir -p D:/laragon/www/crumble/api/pro/controllers D:/laragon/www/crumble/api/pro/models
```

- [ ] **Step 2: Move pro backend files**

```bash
cd D:/laragon/www/crumble
git mv api/controllers/MealPlanController.php api/pro/controllers/MealPlanController.php
git mv api/controllers/StatsController.php api/pro/controllers/StatsController.php
git mv api/models/MealPlan.php api/pro/models/MealPlan.php
git mv api/models/RecipeAnnotation.php api/pro/models/RecipeAnnotation.php
```

- [ ] **Step 3: Update require paths in moved files**

In each moved controller/model, update any `require_once __DIR__ . '/../models/...'` paths to point to the correct relative locations. For example, in `api/pro/controllers/MealPlanController.php`, `__DIR__ . '/../models/Database.php'` should become `__DIR__ . '/../../models/Database.php'` (since we're now one level deeper).

Check each moved file for `require_once` statements and update them.

Also update `api/pro/models/MealPlan.php` — its requires to Database.php need to go up two levels: `__DIR__ . '/../../models/Database.php'`.

- [ ] **Step 4: Gate pro routes in the router**

In `api/index.php`, wrap the pro route cases with a license check. Modify the `meal-plan`, `stats`, and `annotations` sections:

For the `meal-plan` case:
```php
        case 'meal-plan':
            require_once __DIR__ . '/config/license.php';
            if (!License::checkActive()) {
                http_response_code(403);
                $response = ['error' => 'Pro license required', 'code' => 403, 'upgrade' => true];
                break;
            }
            require_once __DIR__ . '/pro/controllers/MealPlanController.php';
            // ... rest of routing unchanged
```

**Design decision — CookLogController stays free:** Free users can still log cooks (the CookButton works in cook mode). They just can't view stats, year-in-review, or forgotten favorites — those are gated via StatsController. This is intentional: it lets free users build up data that becomes valuable when they upgrade, and the basic "I cooked this" action shouldn't feel restricted.

For the `stats` case:
```php
        case 'stats':
            require_once __DIR__ . '/config/license.php';
            if (!License::checkActive()) {
                http_response_code(403);
                $response = ['error' => 'Pro license required', 'code' => 403, 'upgrade' => true];
                break;
            }
            require_once __DIR__ . '/pro/controllers/StatsController.php';
            // ... rest unchanged
```

For the `annotations` sub-route inside `recipes` case (around line 247-273), wrap it:
```php
                } elseif ($subResource === 'annotations') {
                    require_once __DIR__ . '/config/license.php';
                    if (!License::checkActive()) {
                        http_response_code(403);
                        $response = ['error' => 'Pro license required', 'code' => 403, 'upgrade' => true];
                    } else {
                        require_once __DIR__ . '/pro/models/RecipeAnnotation.php';
                        // ... existing annotation handling code
                    }
```

- [ ] **Step 5: Run backend tests**

Run: `cd D:/laragon/www/crumble/api && vendor/bin/phpunit`
Expected: All tests pass. (Existing tests for MealPlan/Stats should still work if they exist — if any tests import moved files directly, update their paths.)

- [ ] **Step 6: Commit**

```bash
git add api/pro/ api/index.php
git commit -m "feat: move pro features behind license gate (meal plan, stats, annotations)"
```

---

## Task 8: Pro Tier Split — Move Frontend Pro Features

**Files:**
- Create: `frontend/src/pro/pages/MealPlanPage.jsx` (moved)
- Create: `frontend/src/pro/pages/StatsPage.jsx` (moved)
- Create: `frontend/src/pro/components/recipe/AnnotationNote.jsx` (moved)
- Modify: `frontend/src/App.jsx` — conditionally render pro routes

- [ ] **Step 1: Create pro frontend directory structure**

```bash
mkdir -p D:/laragon/www/crumble/frontend/src/pro/pages D:/laragon/www/crumble/frontend/src/pro/components/recipe
```

- [ ] **Step 2: Move pro frontend files**

```bash
cd D:/laragon/www/crumble
git mv frontend/src/pages/MealPlanPage.jsx frontend/src/pro/pages/MealPlanPage.jsx
git mv frontend/src/pages/StatsPage.jsx frontend/src/pro/pages/StatsPage.jsx
git mv frontend/src/components/recipe/AnnotationNote.jsx frontend/src/pro/components/recipe/AnnotationNote.jsx
```

- [ ] **Step 3: Update import paths in moved files**

In each moved file, update relative imports. For example, in `frontend/src/pro/pages/MealPlanPage.jsx`, imports like `'../services/api'` become `'../../services/api'`, and `'../components/...'` become `'../../components/...'`.

Review each moved file's imports and fix all relative paths.

- [ ] **Step 4: Update App.jsx to conditionally load pro routes**

In `frontend/src/App.jsx`:

Replace the direct imports of moved pages with lazy imports:
```javascript
import { lazy, Suspense } from 'react';
const MealPlanPage = lazy(() => import('./pro/pages/MealPlanPage'));
const StatsPage = lazy(() => import('./pro/pages/StatsPage'));
```

Wrap pro routes with a license check component:
```jsx
function ProRoute({ children }) {
  const { active, loading } = useLicense();
  if (loading) return null;
  if (!active) return <Navigate to="/settings" replace />;
  return <Suspense fallback={null}>{children}</Suspense>;
}
```

Update the route definitions:
```jsx
<Route path="/meal-plan" element={<ProRoute><MealPlanPage /></ProRoute>} />
<Route path="/stats" element={<ProRoute><StatsPage /></ProRoute>} />
```

- [ ] **Step 5: Update Sidebar/BottomNav to hide pro links when inactive**

In `frontend/src/components/layout/Sidebar.jsx` and `frontend/src/components/layout/BottomNav.jsx`:
- Import `useLicense`
- Conditionally render the Meal Plan, Stats nav links only when `active` is true
- If not active, optionally show a subtle "Pro" badge or simply hide the links

- [ ] **Step 6: Update any components that import AnnotationNote**

To maintain the spec's isolation rule ("free-tier components import nothing from `src/pro/`"), create a thin bridge component in the pro directory that the free-tier pages load via dynamic import:

Create `frontend/src/pro/ProAnnotations.jsx`:
```jsx
import AnnotationNote from './components/recipe/AnnotationNote';
export default AnnotationNote;
```

In the free-tier pages that currently render AnnotationNote (`RecipePage.jsx`, `CookMode.jsx`):
1. Remove the direct import of AnnotationNote
2. Use a `useLicense()` check + dynamic import that references only the bridge:

```jsx
import { lazy, Suspense } from 'react';
import { useLicense } from '../pro/hooks/useLicense';

const AnnotationNote = lazy(() => import('../pro/ProAnnotations'));

// In JSX:
const { active } = useLicense();
// ...
{active && <Suspense fallback={null}><AnnotationNote ... /></Suspense>}
```

**Note:** `useLicense` is the one intentional exception to the "no pro imports" rule — it's a context hook that free-tier code needs to read license status. It returns data only, no UI. The actual pro components are always loaded via dynamic import from the pro directory, so they're code-split into a separate chunk and never bundled with the free tier.

- [ ] **Step 7: Run frontend lint and build**

Run: `cd D:/laragon/www/crumble/frontend && npm run lint && npm run build`
Expected: Both pass with no errors

- [ ] **Step 8: Commit**

```bash
git add frontend/src/pro/ frontend/src/App.jsx frontend/src/components/layout/Sidebar.jsx frontend/src/components/layout/BottomNav.jsx frontend/src/pages/RecipePage.jsx frontend/src/components/recipe/CookMode.jsx
git commit -m "feat: move pro frontend features behind license gate"
```

---

## Task 9: License Files and README

**Files:**
- Create: `LICENSE` (MIT)
- Create: `LICENSE-BSL.md` (BSL 1.1 for pro code)
- Create: `README.md`

- [ ] **Step 1: Create MIT license**

Create `LICENSE`:
```
MIT License

Copyright (c) 2026 Frank Robinson

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

- [ ] **Step 2: Create BSL license for pro code**

Create `LICENSE-BSL.md`:
```markdown
# Business Source License 1.1

**Licensor:** Frank Robinson
**Licensed Work:** Cookslate Pro (files in `api/pro/` and `frontend/src/pro/`)
**Change Date:** 2029-03-24 (3 years from initial release)
**Change License:** MIT

## Terms

The Licensed Work is provided under the terms of this Business Source License.

**Grant of Rights:** You may use, copy, and modify the Licensed Work for internal, non-production purposes (evaluation, development, testing). Production use requires a valid Cookslate Pro license key, available at https://cookslate.com.

**Change Date:** On the Change Date, the Licensed Work automatically converts to the Change License (MIT), and all restrictions of this license cease to apply.

**Limitations:** You may not use the Licensed Work in a production environment without a valid license key. You may not offer the Licensed Work as a hosted service to third parties without a separate commercial agreement.

For license purchases, visit https://cookslate.com.
```

- [ ] **Step 3: Create README.md**

Create `README.md`:
```markdown
# Cookslate

**Your recipes. Your way.**

A recipe manager that remembers how *you* cook — not just what you cook. Self-hosted on any PHP hosting, no Docker required.

## Features

**Free (open source):**
- Import recipes from any URL
- Full-text search across titles, descriptions, and ingredients
- Tags, favorites, and ratings
- Cook Mode — step-by-step with timers, wake lock, and vibration alerts
- Mobile-friendly responsive design
- Dark mode

**Pro ($9.99 one-time):**
- Meal planning with weekly view
- Grocery list generation from meal plans
- Cook tracking — journal, stats, forgotten favorites
- Recipe annotations (margin notes)
- Multi-user household support (up to 5)
- Data export (JSON-LD, Cooklang)
- PWA with offline support

## Quick Start

1. Clone the repo to your web server's document root
2. Copy `api/.env.example` to `api/.env` and configure database credentials
3. Create a MySQL database and import `database/schema.sql`
4. Run `cd frontend && npm install && npm run build`
5. Point your web server to the project root (Apache with mod_rewrite, or Caddy)
6. Visit your site and log in with the default admin credentials
7. Start importing recipes!

## Requirements

- PHP 8.1+ with GD, PDO MySQL, OpenSSL extensions
- MySQL 8.0+
- Node.js 18+ (for building the frontend)
- Apache with mod_rewrite (or Caddy/Nginx with equivalent config)

## Tech Stack

- **Backend:** PHP (custom microframework, no dependencies)
- **Frontend:** React 18 + Vite + Tailwind CSS 4
- **Database:** MySQL
- **Icons:** Lucide React

## License

- Free-tier code (`api/`, `frontend/src/`, excluding `pro/` directories): [MIT](LICENSE)
- Pro-tier code (`api/pro/`, `frontend/src/pro/`): [BSL 1.1](LICENSE-BSL.md) — converts to MIT on 2029-03-24

## Links

- [Get a Pro license](https://cookslate.com)
- [Report a bug](https://github.com/frobinson47/cookslate/issues)
```

- [ ] **Step 4: Create a placeholder public key file**

Create `api/config/license.pub` as an empty placeholder:
```
-----BEGIN PUBLIC KEY-----
(Replace with your actual RSA public key when the license server is built)
-----END PUBLIC KEY-----
```

This file will be replaced with the real public key once the license key server is built (Phase A follow-up). Until then, license validation only works via the online endpoint — which is fine for the initial release.

- [ ] **Step 5: Commit**

```bash
git add LICENSE LICENSE-BSL.md README.md api/config/license.pub
git commit -m "docs: add MIT + BSL licenses, README, and license public key placeholder"
```

---

## Task 10: Final Verification

- [ ] **Step 1: Run full backend test suite**

Run: `cd D:/laragon/www/crumble/api && vendor/bin/phpunit`
Expected: All tests pass

- [ ] **Step 2: Run frontend build**

Run: `cd D:/laragon/www/crumble/frontend && npm run build`
Expected: Build succeeds with no errors

- [ ] **Step 3: Search for remaining "Crumble" references in source code**

Run: `grep -ri "crumble" --include="*.php" --include="*.jsx" --include="*.js" --include="*.css" --include="*.html" D:/laragon/www/crumble/api D:/laragon/www/crumble/frontend`

Expected: Only legitimate hits remain:
- `api/services/AutoTagger.php` — "crumble" as a food term (apple crumble)
- `api/index.php` — `/crumble/api` in basePaths (backwards compat)
- `.htaccess` — `crumble` in regex (backwards compat)
- Docs files — historical references

- [ ] **Step 4: Verify the free tier works without a license key**

1. Ensure no `config/license.key` file exists
2. Visit the app — all free features should work normally
3. Meal Plan and Stats routes should return 403
4. Settings page should show the "Enter license key" form
5. Sidebar should not show Meal Plan or Stats links

- [ ] **Step 5: Verify pro features work with a test license key**

Generate a test JWT (using the test helper pattern from LicenseTest), write it to `config/license.key`, and verify:
1. `/api/license/status` returns `{active: true, tier: "pro"}`
2. Meal Plan and Stats routes work
3. Annotations work
4. Settings page shows "Pro — Active"

- [ ] **Step 6: Final commit if any cleanup needed**

```bash
git add -A
git commit -m "chore: final cleanup for Cookslate Phase A rename"
```
