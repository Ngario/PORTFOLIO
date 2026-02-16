## Admin Dashboard + Database Learning Guide

This guide explains **exactly how the admin dashboard works**, how it **connects to your DB**, and which **files you edit** when you want to change how data is stored or displayed.

---

## 1) How “connecting to the database” works (in CodeIgniter)

### A) Database config (the connection settings)

- **File:** `app/Config/Database.php`
- **What it contains:** hostname, username, password, database name (`portfolio_db`), driver (`MySQLi`)

Whenever your app runs a model query, CodeIgniter uses these settings to create a DB connection.

**Important:** the PHP you use must have the `mysqli` extension enabled.

---

### B) Models (PHP ↔ MySQL tables)

A **Model** represents a table.

Key properties:
- **`$table`**: the table name in MySQL
- **`$primaryKey`**: usually `id`
- **`$allowedFields`**: which columns are allowed for insert/update
- **`$useTimestamps`**: if true, CI tries to auto-fill `created_at` and `updated_at`

If `$allowedFields` does not match your table columns, inserts/updates will fail or ignore fields.

---

## 2) Your schema → models (what we changed and why)

You shared your real schema; we updated models to match it.

### Projects

- **Table:** `projects`
- **Columns:** `id, title, slug, description, image, tech_stack, demo_url, github_url, featured, created_at`
- **Model:** `app/Models/ProjectModel.php`

**Key changes:**
- `image` — optional card/thumbnail image path (e.g. `projects/abc.jpg`). Upload via admin project form; stored under `public/uploads/projects/`. Shown on homepage Featured Projects, `/projects` list, and single project page.
- `tech_stack` is stored as JSON in DB (example: `["PHP","MySQL"]`) and decoded for views
- `useTimestamps` is **off** if your table doesn’t have `updated_at`

### Services

- **Table:** `services`
- **Columns:** `id, name, description, price, is_active, created_at`
- **Model:** `app/Models/ServiceModel.php`

**Key changes:**
- Views now use `name` and `price` (instead of `title` / `price_from`)
- `getServices()` filters: only `is_active = 1`

### Blog posts

- **Table:** `blog_posts`
- **Columns:** `id, title, slug, content, author_id, status, published_at, created_at`
- **Pivot:** `blog_post_category (post_id, category_id)`
- **Categories:** `blog_categories (id, name, slug)`
- **Users:** `users (id, name, ...)`
- **Model:** `app/Models/BlogPostModel.php`

**Key changes:**
- Public blog only shows `status = 'published'`
- Blog post view shows:
  - author name via join on `users` (`author_id → users.id`)
  - categories via joins on pivot table

### Pages

- **Table:** `pages`
- **Columns:** `id, slug, title, content, published, created_at, updated_at`
- **Model:** `app/Models/PageModel.php`

**Key changes:**
- `useTimestamps` is **on** (your table has both created_at and updated_at)

---

## 3) How the site updates immediately when you post from admin

The flow is always:

**Admin form (view)** → **Admin controller** → **Model** → **MySQL table**

Then on the public site:

**Public controller** → **Model** → **MySQL table** → **Public view**

So the moment you insert/update in admin, the public pages read the updated rows next request.

---

## 4) Admin dashboard: what files exist and what each does

### A) Routes (URLs → controllers)

- **File:** `app/Config/Routes.php`

Admin routes added:
- `GET /admin/login` → `Admin\Auth::login`
- `POST /admin/login` → `Admin\Auth::attemptLogin`
- `GET /admin/logout` → `Admin\Auth::logout`

Protected routes (require admin login):
- `GET /admin` → `Admin\Dashboard::index`
- `GET /admin/projects` etc…
- `GET /admin/blog-posts` etc…

### B) Filter (protect /admin)

- **File:** `app/Filters/AdminAuth.php`
- **Registered in:** `app/Config/Filters.php` as alias **`adminauth`**

What it does:
- If not logged in → redirect to `/admin/login`
- If logged in but role not `admin|superadmin` → 403

### C) Controllers (logic)

- **Login/logout:** `app/Controllers/Admin/Auth.php`
  - checks email/password against the `users` table
  - uses `password_verify()` on `password_hash`
  - stores session keys:
    - `admin_logged_in`, `admin_user_id`, `admin_name`, `admin_role`

- **Dashboard:** `app/Controllers/Admin/Dashboard.php`

- **Projects CRUD:** `app/Controllers/Admin/Projects.php`
  - stores `tech_stack` as JSON

- **Blog Posts CRUD:** `app/Controllers/Admin/BlogPosts.php`
  - writes to `blog_posts`
  - syncs categories into `blog_post_category`

### D) Admin Views (HTML forms)

- Layout: `app/Views/admin/layout.php`
- Login: `app/Views/admin/auth/login.php`
- Dashboard: `app/Views/admin/dashboard.php`
- Projects:
  - list: `app/Views/admin/projects/index.php`
  - form: `app/Views/admin/projects/form.php`
- Blog posts:
  - list: `app/Views/admin/blog_posts/index.php`
  - form: `app/Views/admin/blog_posts/form.php`

---

## 5) Create an admin user (required for /admin login)

Your admin login uses your existing `users` table:
- `email` must exist
- `role` must be `admin` (or `superadmin`)
- `password_hash` must be a real password hash (not plain text)

### Generate a password hash

Run:

```bash
php -r "echo password_hash('Admin123!', PASSWORD_DEFAULT) . PHP_EOL;"
```

Copy the output and store it into `users.password_hash`.

### Example SQL (edit values)

```sql
INSERT INTO users (name, email, password_hash, role, status, created_at, updated_at)
VALUES ('Admin', 'admin@example.com', '$2y$10$....', 'admin', 'active', NOW(), NOW());
```

---

## 6) How to update data (practical examples)

### Add a Project from admin

1. Login: `/admin/login`
2. Go to Projects: `/admin/projects`
3. Click New
4. Submit → data is inserted into `projects`

Public page reads from DB:
- `/projects`

### Add a Blog post from admin

1. Login
2. Go to Blog Posts: `/admin/blog-posts`
3. Click New
4. Choose status:
   - `draft` (won’t show on public blog)
   - `published` (will show)
5. Select categories (uses `blog_categories`)
6. Submit

Public blog reads only `published`:
- `/blog`

---

## 7) How to “change tables” safely (and what you must update in code)

If you change DB columns (example: rename `projects.demo_url` → `projects.live_url`), you must update:

1. The model (`$allowedFields`, and any code reading that column)
2. Admin forms (input name must match the column)
3. Public views (where you display it)

Best practice: use **migrations** to change structure (so schema changes are in code). If you change in phpMyAdmin, do the matching code updates immediately.

---

## 8) Admin Downloads and Download CV

**Admin Downloads (nested):** In the admin nav, **Downloads** is a dropdown containing:
- **Manage Downloads** – list/add/edit/delete download items (category, title, description, file upload; files stored in `public/uploads/downloads/`).
- **Categories** – list/add/edit/delete download categories (e.g. Software, Ebooks, Videos). These are the same categories used on the public site.

**Public Downloads dropdown:** The main site header "Downloads" menu is built from the `download_categories` table. Add or edit categories in admin to change the dropdown. If the DB is unavailable, the header falls back to static links (Software, Books, Videos, Tutorials).

**Download CV:** The homepage has a "Download CV" button linking to `/download-cv`. It is served by `Pages::downloadCv()` and is **free for everyone** (no login). Put your PDF at `public/uploads/cv/cv.pdf`.

