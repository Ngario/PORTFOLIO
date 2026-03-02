# Fixing "POST Content Length exceeds the limit" / large file uploads

When uploading large files in the admin (e.g. books, PDFs, images), you may see:

```text
POST Content Length of XXXXX bytes exceeds the limit of 41943040 bytes
```

That means the request is larger than PHP’s `post_max_size` (here, 40 MB). Increase the limits as below.

---

## 1. XAMPP (local, Windows)

1. Open **`C:\xampp\php\php.ini`** in an editor (as Administrator if needed).
2. Find and set (or add) these lines. Use **128M** or **256M** if you upload very large files:

   ```ini
   upload_max_filesize = 128M
   post_max_size = 128M
   ```

3. Save the file and **restart Apache** from the XAMPP Control Panel.

---

## 2. This project’s `.htaccess` (when using Apache + mod_php)

`public/.htaccess` already contains:

```apache
<IfModule mod_php.c>
    php_value upload_max_filesize 128M
    php_value post_max_size 128M
</IfModule>
```

So if your server runs Apache with **mod_php** and allows `php_value` in `.htaccess`, these limits apply when you use this project. No extra step needed.

If you still see the error, the server may be using **PHP-FPM** or **CGI**, which ignore `.htaccess` PHP settings. Then use **php.ini** (see below) or your host’s control panel.

---

## 3. Hosted / shared hosting

- Use the host’s **PHP settings** or **php.ini** (e.g. `public/php.ini` or the path they give you).
- Set at least:

  ```ini
  upload_max_filesize = 128M
  post_max_size = 128M
  ```

- **Render / PHP-FPM:** Set environment variables if your stack supports them, or use a custom `php.ini` in the app and ensure it’s used at runtime. Otherwise, keep uploads under the default limit (e.g. compress files or use external storage).

---

## 4. Quick reference

| Setting              | Meaning                          | Suggested |
|----------------------|----------------------------------|-----------|
| `upload_max_filesize`| Max size of one uploaded file    | `128M`    |
| `post_max_size`      | Max total size of the POST body  | `128M` (must be ≥ upload size) |

After changing these, restart the web server (or PHP-FPM) and try the upload again.
