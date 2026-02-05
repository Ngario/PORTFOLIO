# How Pages, Routes, Controllers, and Views Work Together

This document explains how the **Services**, **Projects**, **Blog**, **Terms of Service**, and **Privacy Policy** pages are built in your portfolio, and how the same pattern applies across the whole site.

---

## 1. The Big Picture: Request → Response

When someone visits a URL, this is what happens:

```
Browser request (e.g. /portfolio/services)
       ↓
public/index.php (single entry point)
       ↓
Framework loads app/Config/Routes.php
       ↓
Router matches URL to a rule → decides Controller + method
       ↓
Controller runs → loads data (e.g. from DB or placeholder)
       ↓
Controller returns a View (HTML)
       ↓
Response sent back to the browser
```

So: **URL** is defined in **Routes** → **Routes** point to a **Controller method** → that method prepares **data** and chooses a **View**. The view is the HTML template that receives that data.

---

## 2. Routes: The Map from URL to Controller

File: **`app/Config/Routes.php`**

Routes define **which URL** triggers **which controller and method**.

| URL you visit | Route rule | Controller | Method | What it does |
|---------------|------------|-------------|--------|--------------|
| `/portfolio/` | `'/'` | `Home` | `index` | Homepage |
| `/portfolio/about` | `'about'` | `Pages` | `about` | About page |
| `/portfolio/contact` | `'contact'` | `Pages` | `contact` | Contact page |
| **`/portfolio/projects`** | **`'projects'`** | **`Projects`** | **`index`** | List all projects |
| **`/portfolio/projects/2`** | **`'projects/(:num)'`** | **`Projects`** | **`view`** with `$1` = 2 | Single project (ID 2) |
| **`/portfolio/services`** | **`'services'`** | **`Services`** | **`index`** | List all services |
| **`/portfolio/services/1`** | **`'services/(:num)'`** | **`Services`** | **`view`** with `$1` = 1 | Single service (ID 1) |
| **`/portfolio/blog`** | **`'blog'`** | **`Blog`** | **`index`** | List all blog posts |
| **`/portfolio/blog/my-post-slug`** | **`'blog/(:segment)'`** | **`Blog`** | **`view`** with `$1` = slug | Single post by slug |
| **`/portfolio/blog/category/tutorials`** | **`'blog/category/(:segment)'`** | **`Blog`** | **`category`** with `$1` = tutorials | Posts in that category |
| **`/portfolio/terms`** | **`'terms'`** | **`Pages`** | **`terms`** | Terms of Service |
| **`/portfolio/privacy`** | **`'privacy'`** | **`Pages`** | **`privacy`** | Privacy Policy |

- **`(:num)`** = a number (e.g. 1, 2, 123). Passed to the method as the first argument.
- **`(:segment)`** = a URL segment (letters, numbers, hyphens). Passed to the method as the first argument.

So:
- `projects/(:num)` → `Projects::view/$1` means: when URL is `/projects/5`, the router calls `Projects->view(5)`.
- `blog/(:segment)` → `Blog::view/$1` means: when URL is `/blog/getting-started-codeigniter-4`, the router calls `Blog->view('getting-started-codeigniter-4')`.

**Why this file matters:**  
All “what URL does what” lives in one place. To add a new page, you add a route, then create (or reuse) a controller method and a view.

---

## 3. Controllers: Logic and Data

Controllers live in **`app/Controllers/`**. Each controller is a class; each **method** is one “action” (one page or one API endpoint).

### What a controller does

1. **Receives the request** (and any URL parameters the route passed).
2. **Loads or prepares data** (from database, APIs, or placeholder arrays).
3. **Returns a view** with that data: `return view('folder/viewname', $data);`

### Controllers we added

| Controller | File | Methods | Purpose |
|------------|------|---------|--------|
| **Projects** | `app/Controllers/Projects.php` | `index()`, `view($id)` | List projects; show one project by ID |
| **Services** | `app/Controllers/Services.php` | `index()`, `view($id)` | List services; show one service by ID |
| **Blog** | `app/Controllers/Blog.php` | `index()`, `view($slug)`, `category($category)` | List posts; show one post by slug; list posts by category |
| **Pages** | `app/Controllers/Pages.php` | `about()`, `contact()`, `sendMessage()`, **`terms()`**, **`privacy()`** | Static pages; Terms and Privacy only pass title/description and render a view |

### Example: Projects controller

- **`index()`**  
  - Builds a list of projects (right now from `getPlaceholderProjects()`).  
  - Passes `$data = ['title' => '...', 'projects' => $projects]` to the view.  
  - Returns `view('projects/index', $data)` → so the template is `app/Views/projects/index.php`.

- **`view($id)`**  
  - Finds the project with that ID (or throws 404).  
  - Passes that single `$project` to the view.  
  - Returns `view('projects/view', $data)` → template `app/Views/projects/view.php`.

**Why controllers matter:**  
They hold all the “logic”: what to load, what to do when something is missing (e.g. 404), and which view to show. Views only receive data and output HTML; they don’t fetch from the database.

---

## 4. Views: The HTML Templates

Views live in **`app/Views/`**, grouped by “section”:

- **`app/Views/layouts/main.php`** – Master layout (header, footer, placeholders for title and content).
- **`app/Views/components/header.php`** and **`footer.php`** – Included by the layout.
- **`app/Views/pages/`** – About, Contact, **Terms**, **Privacy**.
- **`app/Views/projects/`** – **index.php** (list), **view.php** (single project).
- **`app/Views/services/`** – **index.php** (list), **view.php** (single service).
- **`app/Views/blog/`** – **index.php** (list), **view.php** (single post), **category.php** (posts in one category).

### How a view is used

1. Controller calls: `return view('projects/index', $data);`
2. CodeIgniter looks for **`app/Views/projects/index.php`**.
3. That file receives the variables in `$data` as PHP variables (e.g. `$projects`, `$title`).
4. The view usually **extends** the layout and fills **sections** (e.g. `content`, `title`):

```php
<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
  <!-- page HTML here, e.g. loop over $projects -->
<?= $this->endSection() ?>
```

So every page shares the same header/footer and structure; only the inner “content” and title change.

### Why each view exists

| View | Used by | Receives | Purpose |
|------|--------|----------|--------|
| **projects/index.php** | `Projects::index()` | `$title`, `$description`, `$projects` | Grid of project cards with links to each project |
| **projects/view.php** | `Projects::view($id)` | `$title`, `$project` | Single project: image, description, tech stack |
| **services/index.php** | `Services::index()` | `$title`, `$description`, `$services` | Grid of service cards |
| **services/view.php** | `Services::view($id)` | `$title`, `$service` | Single service: description, “Request this service” CTA |
| **blog/index.php** | `Blog::index()` | `$title`, `$description`, `$posts` | Grid of blog post cards |
| **blog/view.php** | `Blog::view($slug)` | `$title`, `$post` | Single post: title, date, content |
| **blog/category.php** | `Blog::category($category)` | `$title`, `$category`, `$posts` | List of posts in one category |
| **pages/terms.php** | `Pages::terms()` | `$title`, `$description` | Full Terms of Service text |
| **pages/privacy.php** | `Pages::privacy()` | `$title`, `$description` | Full Privacy Policy text |

**Why views matter:**  
They define *how* each page looks. Changing the design or structure of “all project cards” or “single blog post” is done in one view file, without touching the controller logic.

---

## 5. How It All Fits Together (Example: “Services” Page)

1. User goes to **`http://localhost/portfolio/services`**.
2. **Routes:** Rule `'services'` → `Services::index` → framework calls `Services->index()`.
3. **Controller:** `Services->index()` runs:
   - Gets list from `getPlaceholderServices()` (later: from a Service model).
   - Builds `$data = ['title' => '...', 'services' => $services]`.
   - Returns `view('services/index', $data)`.
4. **View:** `app/Views/services/index.php` runs:
   - Extends `layouts/main` (so header/footer are the same as the rest of the site).
   - Uses `$title` and `$services` to render the hero and the service cards.
5. User sees the Services list page with the same header/footer as Home or Projects.

Same idea for:
- **Single service:** `/services/1` → `Services::view(1)` → `services/view.php` with one `$service`.
- **Blog list:** `/blog` → `Blog::index()` → `blog/index.php`.
- **Single post:** `/blog/getting-started-codeigniter-4` → `Blog::view('getting-started-codeigniter-4')` → `blog/view.php`.
- **Terms/Privacy:** `/terms` and `/privacy` → `Pages::terms()` and `Pages::privacy()` → `pages/terms.php` and `pages/privacy.php`.

---

## 6. Placeholder Data vs Real Database

Right now, Projects, Services, and Blog use **placeholder arrays** inside the controller (e.g. `getPlaceholderProjects()`, `getPlaceholderServices()`, `getPlaceholderPosts()`). That way the pages work and you can click through everything without a database.

When you add models and tables:

1. **Projects**  
   - Create a **Project** model and use the **projects** table.  
   - In `Projects::index()`: `$projects = model('ProjectModel')->findAll();`  
   - In `Projects::view($id)`: `$project = model('ProjectModel')->find($id);` and throw 404 if not found.

2. **Services**  
   - Same idea with a **Service** model and **services** table.

3. **Blog**  
   - Use a **BlogPost** (or similar) model and **blog_posts** table.  
   - `index()`: get all (or paginated) posts.  
   - `view($slug)`: find by slug.  
   - `category($category)`: find where category = $category.

The **views don’t need to change** as long as you pass the same variable names and structure (e.g. each item has `title`, `excerpt`, `image`, etc.). Only the controller switches from “placeholder array” to “model->find()”.

---

## 7. Quick Reference: Files Involved

| Purpose | File |
|--------|------|
| Define which URL runs which controller | `app/Config/Routes.php` |
| List + single project | `app/Controllers/Projects.php` → `app/Views/projects/index.php`, `view.php` |
| List + single service | `app/Controllers/Services.php` → `app/Views/services/index.php`, `view.php` |
| List + single post + category | `app/Controllers/Blog.php` → `app/Views/blog/index.php`, `view.php`, `category.php` |
| Terms of Service | `app/Controllers/Pages.php` → `app/Views/pages/terms.php` |
| Privacy Policy | `app/Controllers/Pages.php` → `app/Views/pages/privacy.php` |
| Shared layout (header/footer) | `app/Views/layouts/main.php`, `components/header.php`, `components/footer.php` |
| Styling for inner pages | `public/css/style.css` (e.g. `.page-hero`, `.legal-content`) |

---

## 8. Summary

- **Routes** = map URL → Controller + method (and URL parameters).
- **Controllers** = run logic, load data, return a view with `$data`.
- **Views** = HTML templates that extend the layout and use `$data` to render the page.

For **Services**, **Projects**, **Blog**, **Terms**, and **Privacy**, we added the routes (already in `Routes.php`), the controller methods, and the view files. Terms and Privacy use the existing **Pages** controller; the rest use **Projects**, **Services**, and **Blog** controllers. Once you connect real models and tables, you only change the controller code; the routes and views stay the same.
