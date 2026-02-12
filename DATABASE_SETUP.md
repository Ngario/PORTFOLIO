# Using portfolio_db: Step-by-Step Guide

This guide walks you through **connecting your app to MySQL**, **creating tables**, **putting data in**, and **seeing that data on the site**. Every step is explained so you know what each part does.

---

## Step 1: What You Already Did

You created a database in MySQL named **`portfolio_db`**.

- If your database is **empty (no tables yet)**, you can create tables using **migrations** and fill them using **seeders**.
- If you **already created tables** (but they are empty), you do **not** need migrations to create them again. Instead you connect the app by making your **models match your table + column names**.

For existing tables, read **`EXISTING_TABLES.md`** and run this command to see your exact schema:

```bash
php spark db:show-tables
```

---

## Step 2: How the App Knows Which Database to Use

The app reads database settings from **two** places (in order):

1. **`app/Config/Database.php`**  
   This file defines the **default** connection. In your project it already has:
   - `hostname` = `'localhost'`
   - `username` = `'root'`
   - `password` = `''`
   - `database` = `'portfolio_db'`
   - `DBDriver` = `'MySQLi'` (MySQL)

   So by default the app tries to connect to **MySQL on localhost**, database **portfolio_db**, user **root**, no password. That matches a typical XAMPP setup.

2. **`.env`** (optional)  
   If you **uncomment** and set the database keys in `.env`, they **override** the values in `Database.php`. That is useful for production (e.g. on Render) so you don’t put real credentials in the repo. For local XAMPP you can leave the defaults in `Database.php` and leave the database section in `.env` commented out.

**Summary:**  
- **Config** = “use this host, user, password, database name.”  
- **portfolio_db** is already set as the database name in config.  
- No code change needed for Step 2 if your MySQL user is `root` with no password on localhost.

---

## Step 3: Creating the Table (Migrations)

The app needs **tables** inside `portfolio_db`. You don’t create them by hand in phpMyAdmin (you can, but we use migrations so the schema is in code and repeatable).

- A **migration** is a PHP class with two methods:
  - **`up()`** – “do something” (e.g. create a table).
  - **`down()`** – “undo it” (e.g. drop the table).

Your project has one migration:

- **File:** `app/Database/Migrations/2025-01-27-120000_CreateProjectsTable.php`
- **What it does in `up()`:**  
  Creates a table named **`projects`** with columns: `id`, `title`, `slug`, `excerpt`, `description`, `image`, `tech_stack`, `link`, `completed_at`, `created_at`, `updated_at`.
- **What it does in `down()`:**  
  Drops the `projects` table.

**Run the migration:**

1. Open a terminal (or PowerShell).
2. Go to your project folder:
   ```bash
   cd C:\xampp\htdocs\portfolio
   ```
3. Run:
   ```bash
   C:\xampp\php\php.exe spark migrate
   ```
   (Or `php spark migrate` if `php` is in your PATH.)
4. If it asks **“Running all migrations. Continue? [y,n]”**, type **y** and press Enter.

**What happens when you run it:**

- CodeIgniter reads `Database.php` and connects to **portfolio_db**.
- It looks in `app/Database/Migrations/` for migration files.
- It runs the **`up()`** method of each migration that hasn’t been run before (it tracks this in a table called `migrations`).
- So after this, **portfolio_db** will have:
  - Table **`projects`** (with the columns above).
  - Table **`migrations`** (used by the framework to know which migrations are already done).

**Summary:**  
- **Migrations** = “create or change database structure” in code.  
- **`spark migrate`** = “connect to the DB from config and run every new migration.”  
- After this step, **portfolio_db** has a **projects** table, but it’s still **empty**.

---

## Step 4: Putting Data Into the Table (Seeders)

An empty table is not useful. A **seeder** is a class that **inserts initial or sample data** into tables.

Your project has a seeder:

- **File:** `app/Database/Seeds/ProjectSeeder.php`
- **What it does in `run()`:**  
  Inserts **three sample projects** into the **projects** table (the same ones that were previously hard-coded as “placeholder” data). So after seeding, the table has real rows.

**Run the seeder:**

1. Same terminal, same folder:
   ```bash
   C:\xampp\php\php.exe spark db:seed ProjectSeeder
   ```
2. You should see a short message that the seeder ran.

**What happens when you run it:**

- CodeIgniter again uses the same database config and connects to **portfolio_db**.
- It loads the class **ProjectSeeder** from `app/Database/Seeds/ProjectSeeder.php`.
- It calls **`run()`**, which uses the **ProjectModel** (or the database directly) to **insert** three rows into **projects**.
- After this, **projects** has 3 rows (e.g. “E-Commerce Platform”, “AI-Powered Dashboard”, “Portfolio Website”).

**Summary:**  
- **Seeder** = “insert this data into the database.”  
- **`spark db:seed ProjectSeeder`** = “run the ProjectSeeder so the projects table has sample data.”  
- After this step, **portfolio_db** has a **projects** table **with data**.

---

## Step 5: How the Site Uses This Data (Request → Controller → Model → Database)

When someone opens **/projects** in the browser:

1. **Route**  
   The router (from `app/Config/Routes.php`) matches the URL to the **Projects** controller and the **index** method.

2. **Controller**  
   In **`app/Controllers/Projects.php`**, the **index()** method:
   - Calls **getProjectsFromDb()**, which uses **ProjectModel** to load projects from the database.
   - If that succeeds and returns rows, the controller passes those rows to the view.
   - If the table doesn’t exist or an error occurs, it falls back to the old placeholder array so the page still works.

3. **Model**  
   **ProjectModel** (`app/Models/ProjectModel.php`):
   - Is tied to the **projects** table and uses the same database config (so it uses **portfolio_db**).
   - **getProjects()** runs something like: `SELECT * FROM projects ORDER BY created_at DESC` and returns the rows.
   - It also decodes the **tech_stack** JSON column into an array for the view.

4. **View**  
   The **projects/index** view receives the list of projects and loops over them to show cards. So the HTML is the same; only the **source of the data** changes from “placeholder array” to “rows from **portfolio_db.projects**”.

**Summary:**  
- **Request** → **Route** → **Controller** → **Model** → **Database** → back to **Controller** → **View** → **Response**.  
- The **database** used is the one in **Database.php** (and optionally overridden by `.env`), i.e. **portfolio_db**.  
- So after you run the migration and the seeder, the **Projects** page is showing data **from portfolio_db**.

---

## Step 6: Verify That You’re Using portfolio_db

1. **Run the migration** (if you haven’t yet):
   ```bash
   C:\xampp\php\php.exe spark migrate
   ```
2. **Run the seeder**:
   ```bash
   C:\xampp\php\php.exe spark db:seed ProjectSeeder
   ```
3. **In MySQL (phpMyAdmin or command line):**
   - Select database **portfolio_db**.
   - You should see table **projects** and table **migrations**.
   - Open **projects** and you should see **3 rows** (the seeded projects).
4. **In the browser:**  
   Open **http://localhost/portfolio/projects** (or https if you use it). You should see the same three projects. Those rows are coming from **portfolio_db**.

If you see the three projects on the page and the same three rows in the **projects** table in **portfolio_db**, then the app is correctly using **portfolio_db** for that data.

---

## Quick Reference: Commands and Files

| What you want to do        | Command / File |
|----------------------------|----------------|
| Point app to DB            | `app/Config/Database.php` (and optionally `.env`) |
| Create/update tables       | `php spark migrate` (runs `app/Database/Migrations/`) |
| Insert sample data         | `php spark db:seed ProjectSeeder` (runs `app/Database/Seeds/ProjectSeeder.php`) |
| Where projects data comes from | **Projects** controller → **ProjectModel** → table **projects** in **portfolio_db** |

---

## If Something Fails

- **“Connection refused” or “Unknown database”**  
  - Check that MySQL is running (XAMPP Control Panel).  
  - Check that the database **portfolio_db** exists (create it in phpMyAdmin if needed).  
  - Check **hostname**, **username**, **password**, and **database** in `app/Config/Database.php` (and in `.env` if you use it).

- **“Table 'portfolio_db.projects' doesn't exist”**  
  Run the migration: `php spark migrate`.

- **Projects page is empty or still shows old placeholder**  
  Run the seeder: `php spark db:seed ProjectSeeder`, then refresh the page.

Once the migration and seeder have run successfully, your app is using **portfolio_db** for the projects data, and each step above explains what that means.
