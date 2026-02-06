# Fix HTTP 500 on Render

If your site shows **"This page isn't working" / HTTP ERROR 500** after a successful build, do the following.

---

## 1. See the real error (so we know what to fix)

### Method A: Cookie (try this first – no redeploy)

1. Open **https://portfolio-e9jl.onrender.com/** in Chrome (or any browser).
2. Open **Developer Tools** (F12) → **Application** tab (Chrome) or **Storage** (Firefox).
3. Under **Cookies** → select **https://portfolio-e9jl.onrender.com**.
4. Add a cookie:
   - **Name:** `RENDER_DEBUG`
   - **Value:** `1`
5. **Reload** the page (F5 or Ctrl+R).

You should then see either a **"Fatal error"** page with the message and file/line, or the full exception page. Copy or screenshot it.

### Method B: Environment variables

1. In **Render Dashboard** → your **portfolio** service → **Environment**.
2. Add **Key:** `RENDER_DEBUG` **Value:** `1` (or `SHOW_ERRORS` = `1`).
3. **Save** and wait for redeploy, then open your site URL again.

### Method C: Saved error (if the app loads at all)

After a 500, visit **https://portfolio-e9jl.onrender.com/render-debug**. If a fatal was saved, you’ll see it there.

---

## 2. Set required environment variables

In **Render Dashboard** → **Environment**, ensure these are set:

| Key | Value | Required |
|-----|--------|----------|
| **CI_ENVIRONMENT** | `production` | Yes (often set by Blueprint) |
| **app.baseURL** | `https://portfolio-e9jl.onrender.com/` | **Yes** – exact URL with trailing slash |
| **encryption.key** | (see below) | **Yes** if you use sessions/forms (contact form uses session) |

### Get an encryption key

On your **local** machine (with PHP in path):

```bash
cd C:\xampp\htdocs\portfolio
php spark key:generate --show
```

Copy the long hex string (e.g. `a1b2c3d4...`) and in Render set:

- **Key:** `encryption.key`
- **Value:** that hex string (no quotes)

Then **Save** and let Render redeploy.

---

## 3. If it still fails

- Check **Render → Logs** for the exact PHP/CodeIgniter error after enabling `SHOW_ERRORS` once.
- Confirm **app.baseURL** has no typo and ends with `/`.
- Confirm **encryption.key** is set and is the same value you generated (no spaces, one line).

After fixing, remove **SHOW_ERRORS** from Environment and redeploy so the site shows the normal error page again in production.
