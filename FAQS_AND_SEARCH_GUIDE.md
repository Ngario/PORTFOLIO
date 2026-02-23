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

**Why keep FAQs in the controller for now?**  
So we don’t need a database table yet. Later you can move FAQs to a database and load them here instead of a static array.

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

So the **same** FAQ data is used on both `/faqs` (all FAQs) and `/search?q=...` (filtered). The controller is the place that applies the filter logic.

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

## Part 3: Chatbot (Planned)

Later we can add a **chatbot** (e.g. a floating widget that answers common questions using the same FAQ content or an API). That would involve:

- A small **UI component** (e.g. a button that opens a chat panel).
- **JavaScript** to send the user’s message and display replies (e.g. from an API or from matching FAQs).
- Optionally a **backend endpoint** that takes the user message and returns a bot response (so we can add logic, use AI, or search FAQs on the server).

We’ll document that in a future update to this guide once we implement it.

---

## File Checklist

After completing Parts 1 and 2 you should have:

- **Routes:** `faqs` and `search` in `app/Config/Routes.php`.
- **Controller:** `faqs()` and `search()` in `app/Controllers/Pages.php`.
- **Views:** `app/Views/pages/faqs.php`, `app/Views/pages/search.php`.
- **Footer:** “FAQs” link in `app/Views/components/footer.php`.
- **Header:** Search icon/link in `app/Views/components/header.php`.

If something doesn’t work, check: (1) route spelling, (2) method name matches the route, (3) view file path and variable names (`$faqs`, `$query`, etc.) match what the controller passes.
