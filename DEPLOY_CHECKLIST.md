# Pre-deploy checklist for Render

Use this before (and after) deploying to Render.

---

## 1. Environment variables (Render Dashboard → Environment)

Set these in your Render **Web Service** → **Environment** tab. Never commit real values to Git.

| Variable | Required | Example / notes |
|----------|----------|------------------|
| **CI_ENVIRONMENT** | Yes | `production` (render.yaml can set this) |
| **app.baseURL** | Yes | `https://your-app-name.onrender.com/` (trailing slash, use your real Render URL) |
| **database.default.hostname** | If using DB | Your MySQL host (e.g. Render MySQL or external) |
| **database.default.username** | If using DB | DB user |
| **database.default.password** | If using DB | DB password |
| **database.default.database** | If using DB | DB name |
| **database.default.port** | If using DB | `3306` (or your provider’s port) |
| **encryption.key** | If using sessions/encryption | Generate: `php spark key:generate --show` (32-char hex) |

- **app.baseURL** must match the URL Render gives you (e.g. `https://portfolio-xxxx.onrender.com/`).
- If you don’t use a database yet, you can leave DB vars unset; the app will still run with placeholder data.
- For **encryption.key**, generate once and set the same value on Render so sessions and encrypted data stay valid.

---

## 2. What was changed for production

- **App.php**  
  - `forceGlobalSecureRequests` is set to `true` when `ENVIRONMENT === 'production'` so all requests use HTTPS.

- **Database.php**  
  - In production, DB credentials are read from env vars (`database.default.*`) so no credentials are in the repo.  
  - `DBDebug` is set to `false` in production so query details are not exposed.

- **Encryption.php**  
  - Comment updated: production should set `encryption.key` via environment (CodeIgniter reads it automatically).

- **public/.htaccess**  
  - www → non-www redirect now uses `https://` so production stays on HTTPS.

- **.env.example**  
  - Added as a reference for local dev and for which env vars to set on Render.

---

## 3. Before you deploy

- [ ] **app.baseURL** in Render env matches your service URL (with trailing slash).
- [ ] **CI_ENVIRONMENT** = `production` (so errors are hidden and HTTPS is forced).
- [ ] If you use the database: all **database.default.*** vars set and correct.
- [ ] If you use login/sessions: **encryption.key** set (from `php spark key:generate --show`).
- [ ] No `.env` or secrets committed to Git (`.env` is in `.gitignore`).
- [ ] Optional: remove or restrict **vendor.rar** from the repo so it isn’t deployed (large and not needed for Docker build).

---

## 4. After first deploy

- Open `https://your-app-name.onrender.com/` and confirm the site loads.
- Click links (About, Contact, Projects, Services, Blog) and confirm no 404s.
- If something breaks, check Render **Logs** and **writable/logs/** (if you have a way to view them).

---

## 5. Optional: clean URLs (no `index.php`)

Your app uses `indexPage = 'index.php'`. On Render with Apache and the provided `.htaccess`, RewriteRule sends requests to `index.php`; the default config is fine. If you later switch to a server that strips `index.php` from URLs, set in **App.php** (or via env):

```php
public string $indexPage = '';
```

Then regenerate links; no change is required for Render by default.
