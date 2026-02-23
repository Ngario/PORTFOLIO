# FAQs, Search, and Chatbot — Step-by-Step Guide

This document explains how the **FAQs page**, **header search**, and (later) **chatbot** were added to the portfolio. Each part is broken into small steps so you can follow and learn.

---

## How This Project Is Structured (Quick Recap)

- **Framework:** CodeIgniter 4 (PHP).
- **Routes:** Defined in `app/Config/Routes.php`. A route maps a URL (e.g. `/faqs`) to a **controller method** (e.g. `Pages::faqs`).
- **Controllers:** In `app/Controllers/`. They load data and choose which **view** to show.
- **Views:** In `app/Views/`. They extend `layouts/main`, which already includes the header and footer. So every page shares the same nav bar and footer; only the main content changes.
- **Footer:** `app/Views/components/footer.php` — appears on every page.
- **Header:** `app/Views/components/header.php` — the navigation bar at the top.

When you add a new page you typically:
1. Add a **route** (URL → controller method).
2. Add a **controller method** that returns a view (and optionally passes data).
3. Create a **view** file that extends `layouts/main` and fills the `content` section.
4. Optionally add a **link** in the header or footer so users can reach the page.

---

## Part 1: The FAQs Page

### What We’re Building

A single page at **`/faqs`** that lists Frequently Asked Questions in an accordion (click a question to expand the answer). We also add a link to this page in the **footer**.

### Step 1.1 — Why a Route First?

The route is the “front door”: it defines the URL and which controller method will run when someone visits that URL. Without a route, visiting `/faqs` would give a 404.

**What we did:** In `app/Config/Routes.php`, in the “Static Pages” section, we added:

```php
$routes->get('faqs', 'Pages::faqs');
```

- **`faqs`** — the URL path (so the full URL is `yoursite.com/faqs`).
- **`Pages::faqs`** — the controller class `Pages` and the method `faqs` that will be called.

So when a user goes to `/faqs`, CodeIgniter calls `App\Controllers\Pages::faqs()`.

---

### Step 1.2 — The Controller Method

The controller’s job is to:
1. Prepare any data the page needs (e.g. page title, list of FAQs).
2. Return the view with that data.

**What we did:** In `app/Controllers/Pages.php` we added a method named `faqs()`:

- It builds an array `$data` with:
  - `title` — for the browser tab and page heading.
  - `description` — for SEO meta description.
  - `faqs` — an array of question/answer pairs (so the view can loop over them and build the accordion).

Then it calls `return view('pages/faqs', $data);`. That means: “Use the view file `app/Views/pages/faqs.php` and pass it the `$data` array.” Inside the view, we access these as `$title`, `$description`, and `$faqs`.

**Note:** As of Part 4, FAQs are loaded from the database via `FaqModel::getAllOrdered()` instead of a static array.

---

### Step 1.3 — The View File

The view is the HTML (and a bit of PHP) that actually renders the page. It:

- **Extends** `layouts/main` so it gets the same header and footer as the rest of the site.
- **Defines sections** that the layout expects: `title`, `description`, and `content`.
- In the **content** section it outputs a hero area and an **accordion** of FAQs.

**Bootstrap accordion:**  
We use Bootstrap 5’s accordion component. Each FAQ is one “item”: a clickable question (accordion button) and a collapsible answer (accordion body). The `data-bs-toggle="collapse"` and `data-bs-target` (and the matching `id` on the collapse div) are what make “click to expand/collapse” work. No custom JavaScript is required for the accordion itself.

**File created:** `app/Views/pages/faqs.php`

---

### Step 1.4 — Linking from the Footer

So users can find the FAQs page, we added a link in the footer. The footer is in `app/Views/components/footer.php`. We added a new list item under “Quick Links” (or under “Legal”, depending on where you prefer):

- **Link text:** “FAQs” (or “FAQ”).
- **URL:** `<?= base_url('faqs') ?>` — `base_url()` builds the full site URL; `'faqs'` is the path we defined in the route.

So when someone clicks “FAQs” in the footer, they go to `/faqs` and see the FAQs page.

---

### Summary of Part 1

| Step   | File / Place            | What we did |
|--------|-------------------------|-------------|
| 1.1    | `app/Config/Routes.php` | Added route `get('faqs', 'Pages::faqs')`. |
| 1.2    | `app/Controllers/Pages.php` | Added `faqs()` method that passes `title`, `description`, and `faqs` to the view. |
| 1.3    | `app/Views/pages/faqs.php` | New view: extends main layout, renders FAQ accordion from `$faqs`. |
| 1.4    | `app/Views/components/footer.php` | Added “FAQs” link pointing to `base_url('faqs')`. |

After Part 1 you have a working FAQs page and a footer link to it. Next we add the header search and make it actionable.

---

## Part 2: Search Button in the Header and Search Page

### What We’re Building

- A **search icon/button** in the header that takes the user to a **search page**.
- A **search page** at `/search` with an input. Submitting the form (or pressing Enter) sends the user to `/search?q=...`. The page shows **filtered FAQs** based on the search term, so the search is actionable.

### Step 2.1 — Route for Search

We need a URL for the search page. In `app/Config/Routes.php` we added:

```php
$routes->get('search', 'Pages::search');
```

So visiting `/search` or `/search?q=something` calls `Pages::search()`. The `q` parameter is read inside the controller from the query string.

---

### Step 2.2 — Controller Method for Search

In `app/Controllers/Pages.php` we added a method `search()` that:

1. Reads the search term: `$query = $this->request->getGet('q');` — “get the GET parameter named `q`”.
2. Reuses the same list of FAQs we use on the FAQs page (or loads from DB if you later move FAQs to a table).
3. If there is a search term, **filters** the FAQs: e.g. keeps only items where the question or answer contains the search term (case-insensitive).
4. Passes to the view:
   - `query` — the current search term (to show in the input and in “Results for …”).
   - `faqs` — the filtered list (or full list if no query).
   - `title` / `description` for the page.

So the **same** FAQ data is used on both `/faqs` (all FAQs) and `/search?q=...` (filtered). As of Part 4, that data comes from the database via `FaqModel::search($query)`.

---

### Step 2.3 — Search View

We created `app/Views/pages/search.php` that:

- Extends `layouts/main` (same header and footer).
- Shows a search form: input name `q`, method GET, action `base_url('search')`. So submitting the form goes to `/search?q=...`.
- If `$query` is set, shows a “Results for …” heading and lists the filtered FAQs (e.g. same accordion or a simple list). If there are no results, we show a “No FAQs match” message.
- Optionally we can add a link “See all FAQs” pointing to `base_url('faqs')`.

This makes the search **actionable**: the user can type a word, press Enter, and see which FAQs match.

---

### Step 2.4 — Search Button in the Header

We added a search button in `app/Views/components/header.php` in the main navigation (e.g. before Contact or after Blog). The button is a link:

- **Icon:** Font Awesome search icon (`fa-search`).
- **Link:** `href="<?= base_url('search') ?>"` — so clicking it goes to the search page.
- **Accessibility:** We use `aria-label="Search"` so screen readers know what the button does.

So the flow is: **Click search icon → go to `/search` → type in the box → submit → see filtered FAQs.** That’s how the header search is made actionable.

---

### Summary of Part 2

| Step   | File / Place            | What we did |
|--------|-------------------------|-------------|
| 2.1    | `app/Config/Routes.php` | Added route `get('search', 'Pages::search')`. |
| 2.2    | `app/Controllers/Pages.php` | Added `search()` method: gets `q`, filters FAQs, passes `query` and `faqs` to view. |
| 2.3    | `app/Views/pages/search.php` | New view: search form (GET), results list from `$faqs`, “See all FAQs” link. |
| 2.4    | `app/Views/components/header.php` | Added search icon link to `base_url('search')`. |

---

## Part 3: Chatbot

### What We’re Building

A **floating chat button** (bottom-right) that opens a **chat panel**. The user types a question; the front end sends it to a **backend API**; the server finds the best matching FAQ from the database and returns the answer as JSON. The reply is shown in the panel without a page reload. So the chatbot is powered by the **same FAQ data** as the FAQs and search pages.

### Step 3.1 — Backend: Chat API

We need an endpoint that accepts a user message and returns a bot reply.

- **Route:** `POST /chat/message` → `Chat::message` (in `app/Config/Routes.php`).
- **Controller:** `App\Controllers\Chat`. Method `message()`:
  - Reads the user message from the request body (JSON `{"message": "..."}` or form field `message`).
  - Uses `FaqModel::findBestMatchForMessage($message)` to find the first FAQ whose question or answer contains the message (or a word from it), case-insensitive.
  - If a match is found, returns that FAQ’s **answer** as the reply.
  - If no match, returns a short fallback message (e.g. “Try the FAQs page or contact us”).
  - Responds with **JSON**: `{"reply": "…"}` and `Content-Type: application/json`.

So the “brain” of the chatbot is: **match user text against FAQ content in the DB and return the answer**. No AI required; you can later swap in an AI API if you want.

**Files:** `app/Controllers/Chat.php`, route in `app/Config/Routes.php`.

### Step 3.2 — Front end: Chat Widget HTML

The chat **UI** is a reusable component included on every page via the main layout.

- **Component:** `app/Views/components/chat_widget.php`. It contains:
  - A **floating button** (e.g. “chat bubble” icon) that toggles the panel.
  - A **panel** (hidden by default) with:
    - Header (“FAQ Bot” + close button).
    - A **messages** area (one initial bot message: “Ask me anything from our FAQs…”).
    - An **input** and **Send** button (form or button that triggers send).
  - **Data attributes** on the widget root: `data-api-url="<?= base_url('chat/message') ?>"` (so JS knows where to POST) and `data-csrf="..."` (for CSRF token when the app has CSRF enabled).

The layout (`app/Views/layouts/main.php`) includes this component after the footer so the widget appears on every public page.

### Step 3.3 — Front end: Chat JavaScript

**File:** `public/js/chat.js`.

- **Open/close:** Clicking the floating button adds/removes a class (e.g. `is-open`) on the panel; CSS controls visibility and animation.
- **Send message:** On form submit or “Send” click:
  - Read the input value, trim it.
  - Append a **user bubble** to the messages area (e.g. “You: …”).
  - **POST** to the URL from `data-api-url`, with body `JSON.stringify({ message: message })`, headers `Content-Type: application/json` and, if present, `X-CSRF-TOKEN` from `data-csrf`.
  - On success: parse JSON, take `data.reply`, append a **bot bubble** with that text.
  - On error: append a bot bubble with a generic “Something went wrong…” message.
- **Loading:** Optionally disable the send button or show a spinner while the request is in flight.

So the flow is: **User types → JS sends POST → Chat controller uses FaqModel → returns JSON → JS shows reply in the panel.**

### Step 3.4 — Styling the Chat Widget

**File:** `public/css/style.css` (or a dedicated block).

- **Floating button:** Fixed position (e.g. bottom-right), z-index above content, circular, primary color.
- **Panel:** Positioned above the button, card-style, dark background, scrollable messages area, input at bottom. When `is-open`, the panel is visible; otherwise hidden (opacity/visibility/transform).

This keeps the chatbot visible but not in the way, and the panel only opens when the user clicks the button.

### Summary of Part 3

| Step   | What we did |
|--------|--------------|
| 3.1    | Added `POST /chat/message` route and `Chat::message()` that uses `FaqModel::findBestMatchForMessage()` and returns JSON `{ reply }`. |
| 3.2    | Created `components/chat_widget.php` (button + panel + data-api-url + data-csrf) and included it in `layouts/main.php`; added `chat.js` script. |
| 3.3    | Implemented `chat.js`: toggle panel, send message via fetch, display user and bot bubbles, CSRF header. |
| 3.4    | Added CSS for `.chat-widget`, `.chat-toggle`, `.chat-panel`, `.chat-message` (user/bot). |

---

## Part 4: FAQs (and Answers) from the Database

### What We’re Building

Move FAQ **questions and answers** from hardcoded arrays in the controller into a **database table**. The FAQs page, search page, and chatbot all read from this table. You can also add **admin CRUD** so you can create, edit, and delete FAQs without changing code.

### Step 4.1 — Table: `faqs`

We added a **migration** that creates the table `faqs` with columns:

- **id** — primary key, auto-increment.
- **question** — TEXT, the FAQ question.
- **answer** — TEXT, the FAQ answer.
- **sort_order** — INT (default 0). Lower values appear first on the FAQs page.
- **created_at**, **updated_at** — DATETIME, for auditing.

**File:** `app/Database/Migrations/2026-02-23-100000_CreateFaqsTable.php`. Run: `php spark migrate`.

### Step 4.2 — Seed Default FAQs

A second migration **seeds** the table with the same six FAQs that were previously in code (e.g. “How do I download?”, “Do I need an account?”, etc.). It only inserts if the table is empty, so it’s safe to run after the create migration.

**File:** `app/Database/Migrations/2026-02-23-100001_SeedDefaultFaqs.php`.

### Step 4.3 — FaqModel

**File:** `app/Models/FaqModel.php`.

- **Table:** `faqs`, **allowed fields:** `question`, `answer`, `sort_order`, timestamps.
- **getAllOrdered()** — returns all FAQs ordered by `sort_order` then `id`. Used by the FAQs page.
- **search($keyword)** — returns FAQs where `question` or `answer` contains the keyword (LIKE). Used by the search page.
- **findBestMatchForMessage($message)** — returns the first FAQ whose question or answer (or words in the message) match; used by the chatbot. Returns `['question' => ..., 'answer' => ...]` or `null`.

So one model serves: **FAQs page**, **search**, and **chatbot**.

### Step 4.4 — Wire Pages and Chat to the Model

- **Pages::faqs()** — instead of a private `getFaqs()` array, we call `FaqModel::getAllOrdered()` and pass the result to the view. The view still expects `$faqs` with `question` and `answer` keys.
- **Pages::search()** — instead of filtering an in-memory array, we call `FaqModel::search($query)` and pass the result as `$faqs`.
- **Chat::message()** — uses `FaqModel::findBestMatchForMessage($message)` and returns the matched FAQ’s answer (or a fallback string).

No change to the **view** structure; only the data source is now the database.

### Step 4.5 — Admin CRUD for FAQs

So you can manage FAQs without touching code or the DB directly:

- **Routes** (inside the `admin` group, so they’re protected by admin auth):
  - `GET  /admin/faqs` → list all FAQs.
  - `GET  /admin/faqs/new` → show form for new FAQ.
  - `POST /admin/faqs` → create.
  - `GET  /admin/faqs/(:num)/edit` → show edit form.
  - `POST /admin/faqs/(:num)` → update.
  - `POST /admin/faqs/(:num)/delete` → delete.
- **Controller:** `App\Controllers\Admin\Faqs` with methods `index`, `new`, `create`, `edit`, `update`, `delete`. All use `FaqModel` to read/write the `faqs` table.
- **Views:** `app/Views/admin/faqs/index.php` (table of questions + order + Edit/Delete), `app/Views/admin/faqs/form.php` (question, answer, sort_order). Both extend `admin/layout`.
- **Admin nav:** In `app/Views/admin/layout.php`, add a “FAQs” link to `base_url('admin/faqs')`.

After this, **FAQs and answers** are fully driven by the database and editable in the admin.

### Summary of Part 4

| Step   | What we did |
|--------|--------------|
| 4.1    | Migration `CreateFaqsTable`: table `faqs` with id, question, answer, sort_order, created_at, updated_at. |
| 4.2    | Migration `SeedDefaultFaqs`: insert default FAQs if table is empty. |
| 4.3    | `FaqModel`: getAllOrdered(), search($keyword), findBestMatchForMessage($message). |
| 4.4    | Pages::faqs() and Pages::search() use FaqModel; Chat::message() uses findBestMatchForMessage(). |
| 4.5    | Admin routes + Admin\Faqs controller + admin/faqs/index + form views + “FAQs” in admin nav. |

---

## File Checklist

After completing all parts you have:

- **Routes:** `faqs`, `search`, `POST chat/message` in `app/Config/Routes.php`; admin routes for `admin/faqs`, `admin/faqs/new`, etc.
- **Controllers:** `Pages::faqs()`, `Pages::search()`; `Chat::message()`; `Admin\Faqs` (index, new, create, edit, update, delete).
- **Models:** `App\Models\FaqModel` (table `faqs`).
- **Migrations:** `CreateFaqsTable`, `SeedDefaultFaqs`.
- **Views:** `pages/faqs.php`, `pages/search.php`; `components/chat_widget.php`; `admin/faqs/index.php`, `admin/faqs/form.php`.
- **Layout:** `layouts/main.php` includes `components/chat_widget` and script `js/chat.js`.
- **Footer:** “FAQs” link in `components/footer.php`.
- **Header:** Search link in `components/header.php`.
- **CSS:** Chat widget styles in `public/css/style.css`.
- **JS:** `public/js/chat.js` for chat open/close and send/receive.

**Run migrations:** `php spark migrate` (or `php spark migrate --all`) so the `faqs` table exists and is seeded.

If something doesn’t work: (1) Check route and method names, (2) Ensure view variables match controller data (`$faqs`, `$query`), (3) Ensure migrations have run, (4) If chat POST fails, check CSRF (header `X-CSRF-TOKEN` and `data-csrf` on the widget).
