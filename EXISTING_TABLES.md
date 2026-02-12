# Using Your Existing Database Tables

You already created tables in **portfolio_db** and they are empty. This project can use those tables **without** running migrations that create tables. You only need to point each **Model** at your real table name and column names.

---

## 1. Do not run migrations that create tables you already have

- If you run `php spark migrate` and one of the migrations tries to create a table that **already exists** (e.g. `projects`), MySQL will error: *"Table 'projects' already exists"*.
- **Options:**
  - **Skip that migration:** Delete or rename the migration file that creates the table you already have (e.g. `app/Database/Migrations/2025-01-27-120000_CreateProjectsTable.php`), then run `php spark migrate` only for any *other* migrations you still need.
  - **Or** simply don’t run migrations for tables you’ve already created. Your app will work as long as the **model** uses the correct table name and columns.

---

## 2. See what tables and columns you have

To wire the app to your existing tables, you need the **exact table names** and **column names**.

### Option A: List tables and columns from the command line

From your project folder run:

```bash
php spark db:show-tables
```

This connects to **portfolio_db** (using your `app/Config/Database.php` / `.env`) and prints every table and its columns. Use this output to set each model’s `$table` and `$allowedFields` to match your database.

### Option B: Use MySQL or phpMyAdmin

1. In MySQL (or phpMyAdmin), select the database **portfolio_db**.
2. List tables:
   ```sql
   SHOW TABLES;
   ```
3. For each table you want to use, get the columns:
   ```sql
   DESCRIBE projects;
   DESCRIBE services;
   -- etc.
   ```

Write down the **table name** and the **column names** (ignore types for the model; we only need names for `$allowedFields`).

---

## 3. Point a model at your existing table

Each model must use:

- **`$table`** = the **exact** table name (e.g. `'projects'`, `'services'`, `'blog_posts'`).
- **`$allowedFields`** = list of **all columns that can be written** when inserting or updating (usually everything except `id`; often you omit `created_at` and `updated_at` if the model handles them via `useTimestamps`).

**Example:** If your `projects` table has columns:  
`id`, `title`, `slug`, `excerpt`, `body`, `image`, `url`, `created_at`, `updated_at`  

then in **ProjectModel** you’d set:

```php
protected $table = 'projects';

protected $allowedFields = [
    'title',
    'slug',
    'excerpt',
    'body',
    'image',
    'url',
];
```

(No `id`, `created_at`, `updated_at` in `$allowedFields` — those are handled by the primary key and timestamps.)

If your table has **different** column names (e.g. `description` instead of `body`, or `link` instead of `url`), use **your** column names in `$allowedFields` and adjust any controller or view code that reads those keys.

---

## 4. Models wired to your schema (already done)

These models are already aligned with the tables you listed:

| Table         | Model           | File                         | Columns used |
|---------------|-----------------|------------------------------|---------------|
| **projects**  | ProjectModel    | `app/Models/ProjectModel.php`   | id, title, slug, description, tech_stack, demo_url, github_url, featured, created_at |
| **services**  | ServiceModel    | `app/Models/ServiceModel.php`   | id, name, description, price, is_active, created_at. List only shows rows where `is_active = 1`. |
| **blog_posts**| BlogPostModel   | `app/Models/BlogPostModel.php`  | id, title, slug, content, author_id, status, published_at, created_at. Joins **users** (author name) and **blog_post_category** + **blog_categories** (categories). Only `status = 'published'` is shown. |
| **pages**     | PageModel       | `app/Models/PageModel.php`     | id, slug, title, content, published, created_at, updated_at |

Other tables (users, orders, payments, downloads, contact_messages, etc.) do not have models yet. Add a new model in `app/Models/` and set `$table` and `$allowedFields` to match when you need them.

---

## 5. Controllers using the database

- **Projects** – Already uses **ProjectModel**; it will read from your existing `projects` table once the model’s `$table` and `$allowedFields` match your schema.
- **Services** – Can be switched to **ServiceModel** and your `services` table (same idea).
- **Blog** – Can be switched to **BlogPostModel** and your blog table.
- **Pages** – Can use **PageModel** and your `pages` table for dynamic content.

---

## 6. Optional: Seed data into your existing tables

If you want **initial data** in your existing (but empty) tables:

- Use **Seeders** that insert rows into those tables.
- In the seeder, use the **same column names** as in your real table.
- Run: `php spark db:seed ProjectSeeder` (or whatever seeder you create).

Do **not** create migrations that create these tables again; seeders only **insert** data.

---

## Summary

1. **Existing tables** = no need to run migrations that create those tables.
2. Run **`php spark db:show-tables`** (or `SHOW TABLES` / `DESCRIBE` in MySQL) to get table and column names.
3. In each model, set **`$table`** and **`$allowedFields`** to match your database.
4. Controllers that use those models will then read/write your existing tables.
