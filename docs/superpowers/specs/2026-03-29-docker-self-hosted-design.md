# Docker Compose for Self-Hosted Cookslate

*Ships in the public repo so buyers/self-hosters can `docker compose up` and have Cookslate running.*

---

## 1. Overview

A Docker Compose setup that runs the full Cookslate stack (PHP + Apache + MySQL) in two containers. Zero dependencies beyond Docker — no manual PHP/MySQL/Node install needed.

```
git clone https://github.com/frobinson47/cookslate.git
cd cookslate
docker compose up -d
# Visit http://localhost:8080 → install wizard
```

---

## 2. Files

| File | Purpose |
|------|---------|
| `Dockerfile` | Multi-stage build: Node builds frontend, PHP 8.1 Apache serves everything |
| `docker-compose.yml` | App + MySQL containers, volumes, networking |
| `docker/apache.conf` | Apache vhost with mod_rewrite for the API router |
| `docker/entrypoint.sh` | Waits for MySQL, applies schema on first boot, generates .env |
| `.dockerignore` | Excludes node_modules, .git, .env, etc. from build context |

---

## 3. Container Architecture

### cookslate-app (PHP 8.1 + Apache)

- Base image: `php:8.1-apache`
- Extensions: `pdo_mysql`, `gd`, `openssl`, `curl`
- Apache mod_rewrite enabled
- Document root: `/var/www/html`
- Port: 8080 on host → 80 in container
- Multi-stage build:
  - Stage 1 (`node:18-alpine`): `npm ci && npm run build` in `/frontend`
  - Stage 2 (`php:8.1-apache`): copies app code + built `frontend/dist/`
- Named volume for `/var/www/html/uploads` (recipe images persist)

### cookslate-db (MySQL 8)

- Base image: `mysql:8`
- Port: 3307 on host → 3306 in container (avoids conflict with host MySQL)
- Named volume for `/var/lib/mysql` (data persists)
- Environment: `MYSQL_DATABASE=cookslate_db`, `MYSQL_USER=cookslate`, `MYSQL_PASSWORD=cookslate`, `MYSQL_ROOT_PASSWORD=cookslate_root`
- Schema applied by entrypoint, not MySQL's `/docker-entrypoint-initdb.d/` (we need the app entrypoint to control timing)

---

## 4. Entrypoint Script

`docker/entrypoint.sh` runs on every container start:

1. **Wait for MySQL** — loop `mysqladmin ping` until MySQL is reachable (max 30 seconds)
2. **Generate .env** — if `api/.env` doesn't exist, create one with container-appropriate defaults:
   ```
   DB_HOST=cookslate-db
   DB_PORT=3306
   DB_NAME=cookslate_db
   DB_USER=cookslate
   DB_PASS=cookslate
   CORS_ORIGINS=http://localhost:8080
   APP_ENV=production
   APP_URL=http://localhost:8080
   ```
3. **Apply schema** — if the `recipes` table doesn't exist, run `database/schema.sql` via `mysql` CLI
4. **Fix permissions** — `chown -R www-data:www-data uploads/`
5. **Start Apache** — `exec apache2-foreground` (standard PHP image entrypoint)

---

## 5. Apache Config

`docker/apache.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html

    # Frontend SPA — serve index.html for non-file, non-API requests
    <Directory /var/www/html/frontend/dist>
        AllowOverride None
        FallbackResource /frontend/dist/index.html
    </Directory>

    # API — rewrite to index.php
    <Directory /var/www/html/api>
        AllowOverride All
        Require all granted
    </Directory>

    # Uploads
    Alias /uploads /var/www/html/uploads
    <Directory /var/www/html/uploads>
        Require all granted
    </Directory>

    # Route / to frontend
    RewriteEngine On
    RewriteRule ^/api(/.*)?$ /api/index.php [L,QSA]
    RewriteRule ^/uploads(/.*)?$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ /frontend/dist/index.html [L]
</VirtualHost>
```

---

## 6. Dockerfile (Multi-Stage)

```dockerfile
# Stage 1: Build frontend
FROM node:18-alpine AS frontend-build
WORKDIR /app/frontend
COPY frontend/package*.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build

# Stage 2: PHP + Apache
FROM php:8.1-apache
RUN docker-php-ext-install pdo_mysql \
    && apt-get update && apt-get install -y libpng-dev libjpeg-dev libwebp-dev default-mysql-client \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/
COPY --from=frontend-build /app/frontend/dist /var/www/html/frontend/dist
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /var/www/html/uploads/recipes \
    && chown -R www-data:www-data /var/www/html/uploads

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
```

---

## 7. docker-compose.yml

```yaml
services:
  app:
    build: .
    container_name: cookslate-app
    ports:
      - "8080:80"
    volumes:
      - uploads:/var/www/html/uploads
    depends_on:
      db:
        condition: service_healthy
    environment:
      - DB_HOST=db
      - DB_PORT=3306
      - DB_NAME=cookslate_db
      - DB_USER=cookslate
      - DB_PASS=cookslate

  db:
    image: mysql:8
    container_name: cookslate-db
    ports:
      - "3307:3306"
    volumes:
      - dbdata:/var/lib/mysql
    environment:
      MYSQL_DATABASE: cookslate_db
      MYSQL_USER: cookslate
      MYSQL_PASSWORD: cookslate
      MYSQL_ROOT_PASSWORD: cookslate_root
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 5s
      timeout: 3s
      retries: 10

volumes:
  uploads:
  dbdata:
```

---

## 8. .dockerignore

```
node_modules/
frontend/node_modules/
frontend/dist/
.git/
.wrangler/
api/.env
api/config/license.key
api/config/license.private.pem
api/vendor/
*.md
docs/
aar/
```

---

## 9. What's NOT in Scope

- Docker setup for the SaaS/Cloud version (Phase B — different stack)
- HTTPS/TLS (users put a reverse proxy in front)
- Automatic updates
- Composer install (the PHP backend has zero Composer dependencies in production — phpunit is dev-only)
