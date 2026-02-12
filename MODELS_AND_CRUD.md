# Models and CRUD in CodeIgniter 4

This guide explains **what models are**, **how they store and read data**, and **how to do CRUD** (Create, Read, Update, Delete) in your portfolio app.

---

## 1. What is a model?

In **MVC** (Model–View–Controller):

- **Model** = talks to the **database** (one table or related tables). It fetches rows, inserts, updates, deletes. It does **not** send HTTP or render HTML.
- **Controller** = gets the request, calls the **model** to get/save data, then passes data to the **view** and returns the response.
- **View** = HTML (and maybe a bit of PHP) that receives data from the controller.

So: **Model = data layer**. Controllers use models to get or change data; views only display what the controller gives them.

---

## 2. Where models live

- **File:** `app/Models/ProjectModel.php` (one class per “thing”, e.g. Project, User, BlogPost).
- **Namespace:** `App\Models`.
- **Base class:** `CodeIgniter\Model` (or your own base that extends it).

The model class tells CodeIgniter:

- Which **table** to use (e.g. `projects`).
- Which **column** is the primary key (e.g. `id`).
- Which columns are allowed when inserting/updating (`$allowedFields`).
- Optional: timestamps, validation, return type (array vs object), etc.

---

## 3. How the model talks to the database

- **Database config** is in `app/Config/Database.php` (host, user, password, database name). It uses the `default` connection (e.g. MySQL).
- When you use a model (e.g. `model('ProjectModel')`), CodeIgniter creates a **database connection** from that config and the model runs queries on the **table** you set (e.g. `projects`).
- So: **config** → **connection** → **model** → **table**.

### Important: “table must exist”

You have **two valid ways** to make sure the table exists:

- **Migrations way (schema in code):** create/alter tables using migrations (recommended when you want the database structure versioned in your project).
- **Existing tables way (schema already in MySQL):** if you already created tables manually, you do **not** need migrations to create them again. You just make sure each model’s `$table` and `$allowedFields` match your real MySQL table and columns.

Tip: this project includes a command that prints your real schema:

```bash
php spark db:show-tables
```

---

## 4. CRUD: the four operations

| Operation | Meaning        | Model method (typical)     | SQL idea        |
|----------|----------------|----------------------------|-----------------|
| **C**reate | Add a new row  | `$model->insert($data)`    | `INSERT`        |
| **R**ead   | Get row(s)     | `$model->find($id)`, `$model->findAll()` | `SELECT`  |
| **U**pdate | Change a row   | `$model->update($id, $data)` | `UPDATE`     |
| **D**elete | Remove a row   | `$model->delete($id)`      | `DELETE`        |

You call these from the **controller**; the model runs the SQL for you.

---

## 5. Create (insert)

**Controller:**

```php
$projectModel = model('ProjectModel');

$data = [
    'title'        => 'My New App',
    'slug'         => 'my-new-app',
    'excerpt'      => 'Short description.',
    'description'  => 'Long description.',
    'image'        => 'projects/photo.jpg',
    'tech_stack'   => json_encode(['PHP', 'MySQL']),  // if stored as JSON
    'link'         => 'https://example.com',
    'completed_at' => '2025',
];

$projectModel->insert($data);
// New row is in the database; primary key is auto-filled if you use $useAutoIncrement.
$newId = $projectModel->getInsertID();
```

- Only columns in **`$allowedFields`** in the model are used; others are ignored.
- **Insert** adds one row; the model uses the table and connection from config.

---

## 6. Read (select)

**Get one row by primary key:**

```php
$projectModel = model('ProjectModel');
$project = $projectModel->find(1);  // id = 1
// $project is an array (or object if you set returnType = 'object').
```

**Get all rows:**

```php
$projects = $projectModel->findAll();
```

**Get with conditions (query builder):**

```php
$projects = $projectModel->where('completed_at', '2025')->findAll();
$project  = $projectModel->where('slug', 'my-new-app')->first();
```

**Order and limit:**

```php
$projects = $projectModel->orderBy('created_at', 'DESC')->limit(10)->findAll();
```

- **find($id)** = one row by primary key.
- **findAll()** = all rows (optionally after `where`, `orderBy`, etc.).
- **first()** = first row of the current query.

---

## 7. Update

**Controller:**

```php
$projectModel = model('ProjectModel');

$data = [
    'title'       => 'Updated title',
    'description' => 'Updated description',
];

$projectModel->update(1, $data);  // update row with id = 1
```

- Only keys in **`$allowedFields`** are updated.
- First argument = primary key value; second = array of column → value.

---

## 8. Delete

**Controller:**

```php
$projectModel = model('ProjectModel');
$projectModel->delete(1);  // delete row with id = 1
```

- Deletes the row with that primary key (or use query builder for more complex deletes).

---

## 9. Migrations: creating the table

The model expects a **table** to exist. You create or change tables with **migrations** (versioned schema).

- **Run migrations:** `php spark migrate` (uses `app/Config/Database.php` and runs files in `app/Database/Migrations/`).
- Each migration has **up()** (create/alter) and **down()** (undo).
- Example: a migration that creates a `projects` table with `id`, `title`, `slug`, `excerpt`, `description`, `image`, `tech_stack`, `link`, `completed_at`, `created_at`, `updated_at`.

After you run **migrate**, the `projects` table exists and `ProjectModel` can do CRUD on it.

---

## 10. End-to-end flow (example: list projects)

1. User visits **/projects**.
2. **Route** calls `Projects::index()`.
3. **Controller** does:
   - `$projectModel = model('ProjectModel');`
   - `$projects = $projectModel->orderBy('created_at', 'DESC')->findAll();`
4. **Model** runs `SELECT ... FROM projects ORDER BY created_at DESC` and returns rows.
5. **Controller** passes `$projects` to the view: `return view('projects/index', ['projects' => $projects]);`
6. **View** loops over `$projects` and outputs HTML.

So: **Request → Route → Controller → Model (DB) → Controller → View → Response.**

---

## 11. Quick reference: model properties (common)

| Property          | Purpose                                      |
|------------------|----------------------------------------------|
| `$table`         | Database table name (e.g. `'projects'`)     |
| `$primaryKey`    | Primary key column (e.g. `'id'`)             |
| `$allowedFields` | Columns that can be set in insert/update     |
| `$useTimestamps` | If true, set `created_at` / `updated_at`     |
| `$returnType`    | `'array'` or `'object'` for result rows      |

---

## 12. Summary

- **Models** = data layer: one class per table (or main entity), in `app/Models/`.
- **CRUD** = Create (`insert`), Read (`find`, `findAll`, `where`, `first`), Update (`update`), Delete (`delete`).
- **Controllers** call the model, then pass data to the view. Views do not call the model.
- **Tables** are created/updated with **migrations**; run `php spark migrate` so the table exists before using the model.

Your project includes an example **ProjectModel** and a **migration** for the `projects` table. Use them as a template for other models (e.g. Blog, Service, ContactMessage).
