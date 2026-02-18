# Deploying to Render and Using the Database

If your hosted site on Render still shows old static content (e.g. placeholder project cards and images from the codebase instead of data from your database), the app is not using your **production database**. Fix this by setting the correct **environment variables** on Render and (optionally) running migrations on deploy.

---

## 1) Why the hosted site might not use the DB

- **Database config** is read from **environment variables** in production. If those are not set on Render, the app keeps the defaults from `app/Config/Database.php` (e.g. `localhost`, `root`, `portfolio_db`), which do not exist on Render’s servers, so DB connection fails.
- When the DB connection fails, the **Home** controller catches the exception and passes **no projects**; the **homepage view** then shows the **fallback “placeholder” cards** (the hardcoded ones with images like `portimage.jpg` from the repo). So you see “old” content from code, not from the DB.
- **ENVIRONMENT** must be `production` so that:
  - Database config can use env vars (see `Database.php` constructor).
  - Security and base URL behave correctly for production.

---

## 2) Required environment variables on Render

In the Render dashboard: open your **Web Service** → **Environment** tab, and add these variables. Use the **same database** you use locally (e.g. a Render PostgreSQL/MySQL instance, or an external host).

### A) Use production environment

| Key | Value |
|-----|--------|
| `CI_ENVIRONMENT` | `production` |

This makes CodeIgniter run in production mode and use env-based config.

### B) Database (must match your real DB host)

Use the names below; they are read in `app/Config/Database.php`. You can use either **dotted** keys or **UPPERCASE_WITH_UNDERSCORES** (e.g. if Render normalizes env names):

| Key (dotted) | Alternative key | Example value |
|--------------|------------------|---------------|
| `database.default.hostname` | `DATABASE_DEFAULT_HOSTNAME` | Your Aiven host, e.g. `portfolio1-db-portfoliomine.d.aivencloud.com` |
| `database.default.database` | `DATABASE_DEFAULT_DATABASE` | `portfolio_db` |
| `database.default.username` | `DATABASE_DEFAULT_USERNAME` | `avnadmin` |
| `database.default.password` | `DATABASE_DEFAULT_PASSWORD` | Your Aiven password |
| `database.default.port` | `DATABASE_DEFAULT_PORT` | `10956` |

**If you see "No such file or directory" for MySQLi:** the app is using the default host (`localhost`), i.e. the **hostname** env var is not set or not loaded. Fix: set `database.default.hostname` (or `DATABASE_DEFAULT_HOSTNAME`) to your real DB host and redeploy.

**Note:** Your app is currently configured for **MySQL** (`MySQLi`). If your Render database is **PostgreSQL**, you must change the DB driver and possibly the port in `app/Config/Database.php` (and use a PostgreSQL-compatible schema). The env vars above stay the same names.

### C) Base URL (so links and assets work)

| Key | Value |
|-----|--------|
| `app.baseURL` | `https://YOUR-SERVICE-NAME.onrender.com/` |

Replace `YOUR-SERVICE-NAME` with your actual Render service URL (no trailing path unless your app is in a subdirectory).

---

## 3) I only have a local database (phpMyAdmin / XAMPP) — what do I do?

Render **does not provide a built-in MySQL** database. Your app runs on Render’s servers and cannot connect to `localhost` or your PC. You need a **hosted MySQL** (or switch to Render’s PostgreSQL; see below) and then point Render’s env vars at it.

### Option A: Use a hosted MySQL (keeps your app as-is)

1. **Create a MySQL database in the cloud** (free or cheap options):
   - **PlanetScale** (free tier, MySQL-compatible): [planetscale.com](https://planetscale.com) — note: they use a different connection style (HTTP); your app may need the PlanetScale driver or a “connect” proxy.
   - **Railway** (free tier, then paid): [railway.app](https://railway.app) — add a MySQL plugin, get host/user/pass/database.
   - **Aiven** (free tier for MySQL): [aiven.io](https://aiven.io) — create a MySQL service, get host, port, user, password.
   - **Other options:** FreeSQLDatabase, db4free, or any hosted MySQL your host supports.

2. **Get the connection details** from the provider (hostname, port `3306`, database name, username, password). You’ll use these in Render’s env vars.

3. **Export your local database from phpMyAdmin:**
   - Open phpMyAdmin → select database `portfolio_db`.
   - **Export** tab → choose **Quick** or **Custom** → format **SQL** → Export. Save the `.sql` file.

4. **Import into the hosted MySQL:**
   - Use the provider’s web UI (e.g. “Console” or “Query”) or a client (e.g. MySQL Workbench, or `mysql -h HOST -u USER -p DATABASE < export.sql`) to run the SQL file so the hosted DB has the same tables and data.

5. **Set Render environment variables** (section 2) using the **hosted** DB’s hostname, database name, username, and password (not `localhost` or your local password).

6. **Redeploy** the Render web service. The site will then use the hosted MySQL.

### Option B: Use Render’s PostgreSQL

Render has a built-in **PostgreSQL** service (easy to add in the same dashboard). If you use that:

- The app must use the **PostgreSQL** driver instead of MySQLi (change `app/Config/Database.php`: `DBDriver` to `Postgre`, and any MySQL-specific options).
- Port is `5432`.
- You’d export your MySQL schema/data and convert or recreate it in Postgres (table structures are similar; some types and SQL differ). This is more work but keeps everything on Render.

**Recommendation:** If you want the least change, use **Option A** with a hosted MySQL (e.g. Railway or Aiven), export from phpMyAdmin, import there, then set the env vars on Render.

### If you chose Aiven (MySQL)

Aiven **requires SSL**. Set these on Render and add the CA certificate as below.

| Key | Value (from your Aiven Overview) |
|-----|----------------------------------|
| `database.default.hostname` | `portfolio1-db-portfoliomine.d.aivencloud.com` |
| `database.default.port` | `10956` |
| `database.default.database` | `defaultdb` *(or create a DB named `portfolio_db` in Aiven and use that)* |
| `database.default.username` | `avnadmin` |
| `database.default.password` | *(Click “Show” / reveal in Aiven and copy the real password; do not use a placeholder.)* |

**SSL (required for Aiven):**

1. In Aiven Console → your service **Overview** → **CA certificate** → **Show** / download.
2. Copy the **entire PEM content** (including `-----BEGIN CERTIFICATE-----` and `-----END CERTIFICATE-----`).
3. On Render → **Environment** → **Add**:
   - **Key:** `database.default.encrypt.ssl_ca_content`
   - **Value:** paste the full CA certificate (multiline is fine).

The app will write this to a temp file and use it for the MySQL SSL connection. Without it, the connection to Aiven will fail.

**Note:** If you want to use database name `portfolio_db` (as locally), create that database in Aiven (e.g. via **Query** or **Databases** in the service) and set `database.default.database` = `portfolio_db`. Otherwise keep `defaultdb` and run your migrations/import there.

---

## 4) After setting env vars

1. **Save** the environment variables in Render.
2. **Redeploy** the service (e.g. “Manual Deploy” → “Deploy latest commit”) so the new env is picked up.
3. After deploy, the app will connect to the database you configured; the homepage and other pages should show **projects (and other data) from the DB**, not the static placeholder cards.

---

## 5) Run migrations on deploy (optional but recommended)

To ensure the **production** database has the same structure (e.g. `projects.image`), run migrations as part of the build.

- **Build command** example (if you use PHP on Render):

  ```bash
  composer install --no-dev --optimize-autoloader && php spark migrate --all
  ```

- Or run migrations once manually via Render’s **Shell** (if available):  
  `php spark migrate --all`

If you already ran migrations on the same DB from your local machine, you may not need to run them again; only if the hosted DB was never migrated or is a different database.

---

## 6) Project images and uploads on Render

- **Uploaded files** (project card images, downloads, CV) are stored under `public/uploads/`. On Render, the **filesystem is ephemeral**: it is wiped on each new deploy.
- So:
  - **New uploads** made on the hosted admin will **disappear** after the next deploy.
  - **Image paths** in the DB (e.g. `projects.image = 'projects/abc.jpg'`) are correct, but the file may no longer exist on the server, so the card can break or show a missing image.

**Options:**

1. **Use a persistent disk** (e.g. Render **Disk** mounted at a path) and store uploads there, and ensure your app reads/writes to that path.
2. **Use external storage** (e.g. **AWS S3** or similar) and store only URLs in the DB; change the admin upload logic to upload to S3 and save the URL in `projects.image`.
3. **Accept ephemeral uploads**: re-upload project images after each deploy if you rarely deploy.

---

## 7) Quick checklist

- [ ] `CI_ENVIRONMENT` = `production`
- [ ] `database.default.hostname`, `database.default.database`, `database.default.username`, `database.default.password` (and optionally `database.default.port`) set to your **real** hosted DB.
- [ ] **Aiven only:** `database.default.encrypt.ssl_ca_content` = full CA certificate PEM (from Aiven Overview).
- [ ] `app.baseURL` = your Render app URL (e.g. `https://YOUR-SERVICE-NAME.onrender.com/`).
- [ ] Redeploy after changing env vars.
- [ ] (Optional) Run `php spark migrate --all` in build or via Shell so the DB has the right tables/columns.

Once the app uses the production database, the site will show DB-driven content (including projects and their images when the files exist). Placeholder cards from the code only appear when the DB returns no projects (e.g. connection failed or table empty).
