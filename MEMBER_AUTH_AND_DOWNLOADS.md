# Member Login, Register, and Protected Downloads – What Each File Does

This document explains **every file** involved in: (1) **public login/register** from the main site, (2) **saving user data in the database**, and (3) **restricting downloads to logged-in members only**. Use it as a reference to understand the flow and where to change things.

---

## 1. Big picture: two kinds of “login”

| Purpose | URL | Session keys | Who |
|--------|-----|----------------|-----|
| **Admin panel** | `/admin/login` | `admin_logged_in`, `admin_user_id`, `admin_name`, `admin_role` | You (admin) |
| **Site members** | `/login` | `user_id`, `user_name`, `user_email` | Visitors who register |

Both use the **same `users`** table. Admin users have `role = 'admin'` or `'superadmin'`; new registrations get `role = 'user'`. The header “Login” and “Register” are for **members**, not admin.

---

## 2. Routes (URLs → controllers)

**File:** `app/Config/Routes.php`

- **`GET /login`** → `Auth::login`  
  Shows the login form (main site).

- **`POST /login`** → `Auth::attemptLogin`  
  Receives email + password, checks DB, sets `user_id`, `user_name`, `user_email`, then redirects to dashboard or the page they tried to open.

- **`GET /register`** → `Auth::register`  
  Shows the registration form.

- **`POST /register`** → `Auth::attemptRegister`  
  Validates input, inserts a new row into `users`, then logs them in (same session keys as login).

- **`GET /logout`** → `Auth::logout`  
  Clears `user_id`, `user_name`, `user_email` and redirects to home.

- **`GET /download/file/(:num)`** → `Downloads::file/$1` **with `['filter' => 'auth']`**  
  This is the **actual file download**. The `auth` filter runs first: if there is no `user_id` in session, the user is redirected to `/login`. Only logged-in members ever reach `Downloads::file()`.

- **`GET /dashboard`**, **`GET /dashboard/profile`**, etc.  
  All under the `$routes->group('dashboard', ['filter' => 'auth'], ...)`. So every dashboard URL requires a logged-in member; otherwise the user is sent to `/login`.

**Why this matters:**  
- **Register/Login/Logout** are the only way members get (or lose) `user_id` in session.  
- **Downloads** and **dashboard** are “protected” by the **auth filter**, not by the controller alone. The filter runs before the controller.

---

## 3. Auth filter (protects dashboard and download)

**File:** `app/Filters/AuthFilter.php`  
**Registered in:** `app/Config/Filters.php` as **`auth`**.

**What it does:**
- Runs **before** any route that has `'filter' => 'auth'`.
- If `session()->get('user_id')` is empty → redirect to `/login` and store the current URL in flash data (`redirect_after_login`) so after login we can send the user back.
- If `user_id` is set → does nothing; the request continues to the controller.

So: **no login → no dashboard, no file download.** The controller never runs for guests on those routes.

---

## 4. Public Auth controller (login / register / logout)

**File:** `app/Controllers/Auth.php`

- **`login()`**  
  If already logged in (`user_id`), redirect to dashboard. Otherwise show the login view.

- **`attemptLogin()`**  
  1. Read `email` and `password` from POST.  
  2. Load user with `UserModel->findByEmail($email)`.  
  3. If no user → “No account found with this email.”  
  4. If user exists but `status !== 'active'` → “This account is not active.”  
  5. If `password_hash` is empty or `password_verify($password, $hash)` fails → “Password is incorrect.”  
  6. Otherwise call `setMemberSession($user)` (sets `user_id`, `user_name`, `user_email`) and redirect to `redirect_after_login` or dashboard.

- **`register()`**  
  If already logged in, redirect to dashboard. Otherwise show the register view.

- **`attemptRegister()`**  
  1. Validate: name, email, password length (≥ 8), password = password_confirm.  
  2. If email already exists in `users` → “An account with this email already exists.”  
  3. Insert into `users`: `name`, `email`, `password_hash` (from `password_hash(..., PASSWORD_DEFAULT)`), `role = 'user'`, `status = 'active'`, `email_verified_at = null`.  
  4. Load the new user and call `setMemberSession($user)`, then redirect to dashboard with a success message.

- **`logout()`**  
  Remove `user_id`, `user_name`, `user_email`, `user_photo` from session and redirect to home.

**So:**  
- **Registration** = one new row in `users` (data saved in DB) + immediate login (session set).  
- **Login** = no new row; we only read from `users` and set session.  
- **Logout** = no DB change; we only clear session.

---

## 5. User model (how we read/write `users`)

**File:** `app/Models/UserModel.php`

- **`$table = 'users'`**  
  All methods use the `users` table.

- **`$allowedFields`**  
  Lists columns that can be set on insert/update: `name`, `email`, `password_hash`, `role`, `email_verified_at`, `status`.  
  So when we `$userModel->insert([...])` in `attemptRegister()`, only these fields are allowed; `id`, `created_at`, `updated_at` are handled by the model/DB.

- **`findByEmail($email)`**  
  Returns one row where `LOWER(email) = strtolower($email)`. Used for both “does this email exist?” and “get user for login”.

**So:**  
- **Saving user data** = `UserModel->insert(...)` in `Auth::attemptRegister()`.  
- **Checking credentials** = `UserModel->findByEmail()` + `password_verify()` in `Auth::attemptLogin()`.

---

## 6. Auth views (login and register forms)

**Files:**  
- `app/Views/auth/login.php`  
- `app/Views/auth/register.php`

Both:
- **Extend** `layouts/main` (same header/footer as the rest of the site).
- Show flash messages: `session()->getFlashdata('error')` and `session()->getFlashdata('success')`.
- **Login:** form POSTs to `current_url()` (so it goes to the same URL as the page, which is routed to `Auth::attemptLogin` for POST). Fields: `email`, `password`.
- **Register:** form POSTs to `current_url()` (same idea for `Auth::attemptRegister`). Fields: `name`, `email`, `password`, `password_confirm`.

So: **the main-page “Login” and “Register” links** point to `/login` and `/register`, which use these views and the public `Auth` controller. Data is not “saved” in the view; the view only sends the form to the controller, and the controller saves to the DB (register) or checks the DB (login).

---

## 7. Header (when to show Login/Register vs user menu)

**File:** `app/Views/components/header.php`

- **If `session()->has('user_id')`**  
  Show the user dropdown (name, dashboard, profile, my-downloads, my-orders, settings, logout).  
  Links use `base_url('dashboard/...')` and `base_url('logout')` so they match the routes above.

- **Else**  
  Show “Login” and “Register” links to `base_url('login')` and `base_url('register')`.

So: **after login or register**, the controller sets `user_id` (and name/email); the header then shows the member menu instead of Login/Register.

---

## 8. Downloads controller (list vs actual file)

**File:** `app/Controllers/Downloads.php`

- **`index()`**  
  Lists categories and downloads (from `download_categories` and `downloads`). No login required.

- **`category($slug)`**  
  Lists downloads in one category. No login required.

- **`view($id)`**  
  Shows one download’s page (title, description, “Download” button). No login required to **view** the page.

- **`file($id)`**  
  Sends the actual file (e.g. PDF). This **route** is protected by the **auth** filter, so only logged-in members reach this method.  
  - Loads the download by `id`, checks `is_active` and `file_path`.  
  - Builds path: `FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $item['file_path']` (so `file_path` in DB is relative to `public/uploads/`).  
  - Optionally inserts a row into `download_logs` (user_id, download_id, downloaded_at).  
  - Returns the file with `$this->response->download(...)`.

So: **only logged-in members can download files** because the **route** to `file()` has `['filter' => 'auth']`. Guests never reach `file()`; they are redirected to `/login` first.

---

## 9. Download model and views

**File:** `app/Models/DownloadModel.php`  
- Uses table `downloads`.  
- Methods like `getActive()`, `getByCategory()` only return rows where `is_active = 1`.

**Files:**  
- `app/Views/downloads/index.php` – list categories and all downloads.  
- `app/Views/downloads/category.php` – list downloads in one category.  
- `app/Views/downloads/view.php` – one download; if logged in, “Download” links to `download/file/(id)`; if not, “Login to download”.

So: **anyone can browse**; **only members can hit the download link** that goes to `download/file/(id)`.

---

## 10. Dashboard controller and views

**File:** `app/Controllers/Dashboard.php`  
- All methods are behind the `dashboard` group, which has `['filter' => 'auth']`, so only logged-in members can access.  
- `index()`, `profile()`, `myDownloads()`, `myOrders()`, `settings()` just load simple views (and optionally flash messages).

**Files:**  
- `app/Views/dashboard/index.php`, `profile.php`, `my-downloads.php`, `my-orders.php`, `settings.php`  
  Placeholder pages that you can later fill with real profile edits, download history, orders, etc.

So: **dashboard and all its links are member-only** because of the **auth** filter on the `dashboard` group in `Routes.php`.

---

## 11. Summary: “How is data saved?” and “Who can download?”

- **Registration:**  
  Form → `Auth::attemptRegister()` → `UserModel->insert(...)` → **one new row in `users`** (name, email, password_hash, role, status, etc.). Then session is set so the user is “logged in”.

- **Login:**  
  Form → `Auth::attemptLogin()` → `UserModel->findByEmail()` + `password_verify()` → **no new row**; we only **read** from `users` and set session.

- **Who can download:**  
  Only requests that pass the **auth** filter (i.e. have `user_id` in session) can reach `Downloads::file()`. So: **only logged-in members** can download; everyone else is redirected to `/login` first.

If you want to change validation, fields saved on register, or what “members” can do, you now know which file to edit (Auth controller, UserModel, or filters/routes).

---

## 12. Where download files live

- **On disk:** Put files under **`public/uploads/`** (e.g. `public/uploads/books/mybook.pdf`).
- **In DB:** In the `downloads` table, set **`file_path`** to the path **relative to `uploads`**, e.g. `books/mybook.pdf` (no leading slash).  
  So `FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $item['file_path']` points to the real file.
- Create the `public/uploads` folder (and subfolders like `books/`) if they don’t exist.
