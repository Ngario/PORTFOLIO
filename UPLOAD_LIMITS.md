# Fixing "POST Content Length exceeds the limit" and "413 Request Entity Too Large"

When uploading large files in the admin (e.g. software .zip, books, PDFs), you may see:

- **413 Request Entity Too Large** — The **web server** (Apache) is rejecting the request before it reaches PHP. The request body is larger than Apache’s limit.
- **POST Content Length exceeds the limit** — PHP’s `post_max_size` is too low. Increase it in php.ini or .htaccess.

**Software downloads** are often large; this project is set up for **512 MB** PHP limits and **1 GB** Apache body limit.

---

## If you still get 413 after .htaccess changes (XAMPP)

Apache may **ignore** `LimitRequestBody` in `.htaccess` unless `AllowOverride` includes it. Do this:

1. Open **`C:\xampp\apache\conf\httpd.conf`** in an editor (as Administrator).
2. Search for **`LimitRequestBody`**. If you find it (e.g. `LimitRequestBody 52428800`), change it to:
   ```apache
   LimitRequestBody 1073741824
   ```
   (1 GB in bytes). If you don’t find it, add that line inside the `<Directory "C:/xampp/htdocs">` block (or the block that contains your document root).
3. Save and **restart Apache** from the XAMPP Control Panel.

Then try the upload again.

---

## 1. XAMPP – PHP limits (php.ini)

1. Open **`C:\xampp\php\php.ini`** in an editor (as Administrator if needed).
2. Find and set (or add) these lines. Use **128M** or **256M** if you upload very large files:

   ```ini
   upload_max_filesize = 512M
   post_max_size = 512M
   ```

3. Save the file and **restart Apache** from the XAMPP Control Panel.

---

## 2. This project’s `.htaccess` (Apache)

`public/.htaccess` already contains:

- **LimitRequestBody 1073741824** — Apache may allow request bodies up to 1 GB (fixes **413 Request Entity Too Large** if your server honours it in .htaccess).
- PHP limits (when using mod_php):

```apache
<IfModule mod_php.c>
    php_value upload_max_filesize 512M
    php_value post_max_size 512M
</IfModule>
```

So if your server runs Apache with **mod_php** and allows `php_value` in `.htaccess`, these limits apply when you use this project. No extra step needed.

If you still see the error, the server may be using **PHP-FPM** or **CGI**, which ignore `.htaccess` PHP settings. Then use **php.ini** (see below) or your host’s control panel.

---

## 3. Hosted / shared hosting

- Use the host’s **PHP settings** or **php.ini** (e.g. `public/php.ini` or the path they give you).
- Set at least:

  ```ini
  upload_max_filesize = 512M
  post_max_size = 512M
  ```

- **Render / PHP-FPM:** Set environment variables if your stack supports them, or use a custom `php.ini` in the app and ensure it’s used at runtime. Otherwise, keep uploads under the default limit (e.g. compress files or use external storage).

---

## 4. Quick reference

| Setting              | Meaning                          | Suggested |
|----------------------|----------------------------------|-----------|
| `upload_max_filesize`| Max size of one uploaded file    | `512M` (for large software) |
| `post_max_size`      | Max total size of the POST body  | `512M` (must be ≥ upload size) |

After changing these, restart the web server (or PHP-FPM) and try the upload again.
