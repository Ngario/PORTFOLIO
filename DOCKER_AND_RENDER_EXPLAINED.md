# Docker and Render – Deep Explanation

This document explains **what Docker is**, **how your Dockerfile works**, and **how Render uses it** so you understand the full flow.

---

## Part 1: Why We Use Docker

### The problem without Docker

- **On your PC:** You have XAMPP (Apache + PHP + MySQL). Your app runs because Apache serves `public/`, runs PHP, and reads `.htaccess`.
- **On Render:** There is no XAMPP. Render can run **containers**. So we put your app **inside a container** that has PHP + Apache + your code. Then Render runs that container.

So: **Docker is the way we give Render “a small server” that looks like your XAMPP setup (PHP + Apache + your app).**

### What Docker gives you

1. **Same environment everywhere**  
   Your PC, your friend’s PC, and Render all run the **same** PHP version, Apache config, and code. No “it works on my machine.”

2. **Isolation**  
   The app runs in a “box” (container). It doesn’t mess with the host; the host doesn’t mess with it.

3. **Portability**  
   You build an **image** once. That same image can run on Render, on another cloud, or on your PC with Docker.

---

## Part 2: Core Ideas (Image vs Container)

### Image

- **What it is:** A read-only snapshot: OS layer + PHP + Apache + your app + `composer install` result.
- **How you get it:** `docker build -t portfolio .` (Docker reads the Dockerfile and builds the image.)
- **Where it lives:** On the machine where you ran `docker build` (or in Render’s registry after they build it).

Think of the image as the **template** or **recipe result**: everything needed to run the app, but not “running” yet.

### Container

- **What it is:** A **running** instance of an image. As long as the container runs, Apache is running and serving your app.
- **How you get it:** `docker run -p 8080:80 portfolio` (start a container from image `portfolio`, map port 8080 on your PC to port 80 in the container).

So: **image = template, container = actual running process.**

- One image → many containers (e.g. multiple instances for scaling).
- If you stop the container, the app stops. The image is still there to start another container.

---

## Part 3: What Each Part of the Dockerfile Does

Your Dockerfile is the **recipe** for the image. Docker runs it **step by step**. Each instruction adds a **layer** to the image.

### 1) `FROM php:8.1-apache`

- **Meaning:** Start from the official image that has PHP 8.1 and Apache.
- **Why this image:** Your app needs PHP (CodeIgniter) and Apache (to use `.htaccess`). This image has both.
- **Result:** First layer = Linux + PHP 8.1 + Apache.

### 2) `RUN a2enmod rewrite`

- **Meaning:** In that image, run the command `a2enmod rewrite` (enable Apache’s rewrite module).
- **Why:** CodeIgniter’s `public/.htaccess` uses `RewriteRule`. Apache only obeys that if `mod_rewrite` is enabled. Without it, URLs like `/about` won’t work.
- **Result:** Next layer = same as before + rewrite enabled.

### 3) `ENV APACHE_DOCUMENT_ROOT` + `RUN sed ...`

- **Meaning:** Set the directory Apache uses as “web root” to `/var/www/html/public`, and edit Apache config so it actually uses that.
- **Why:** CodeIgniter must be served from `public/` only. Everything else (`app/`, `writable/`, `.env`) must stay outside the web root. So we tell Apache: “Document root is `public/`.”
- **Result:** When someone visits `https://yoursite.com/`, Apache looks in `public/` (so `public/index.php` is the entry point).

### 4) `COPY --from=composer:2 /usr/bin/composer /usr/bin/composer`

- **Meaning:** Copy the `composer` binary from the official Composer image into our image.
- **Why:** We need Composer only to run `composer install` **during build**. We don’t need a full Composer image at runtime, just the binary.
- **Result:** Our image can run the `composer` command.

### 5) `WORKDIR /var/www/html`

- **Meaning:** “Current directory” for the next commands is `/var/www/html`.
- **Why:** So when we `COPY . .`, files go into `/var/www/html`, and when we run `composer install`, it runs in the project root where `composer.json` is.
- **Result:** All following commands and paths are relative to `/var/www/html`.

### 6) `COPY . .`

- **Meaning:** Copy the **build context** (your project folder, except what’s in `.dockerignore`) into the image at `/var/www/html`.
- **What’s excluded:** Things in `.dockerignore` (e.g. `vendor/`, `.git`, `.env`, `tests/`, `writable/cache/*`, etc.). So we don’t copy secrets or unnecessary files.
- **Result:** Image now contains `app/`, `public/`, `composer.json`, `writable/`, etc., but not `vendor/` (we install that next).

### 7) `RUN composer install --no-dev --optimize-autoloader`

- **Meaning:** Inside the image, run Composer to install dependencies. No dev packages, and optimize the autoloader for production.
- **Why:** CodeIgniter needs the `vendor/` folder (framework and dependencies). We don’t copy your local `vendor/`; we build a clean one in the image so it matches the PHP version and OS inside the container.
- **Result:** Image now has a full `vendor/` and is ready to run the app.

### 8) `RUN chown -R www-data:www-data /var/www/html/writable`

- **Meaning:** Make the `writable/` directory (and everything inside) owned by the user `www-data`.
- **Why:** Apache runs as `www-data`. CodeIgniter writes logs, cache, and sessions to `writable/`. If `writable/` isn’t writable by `www-data`, you get permission errors.
- **Result:** At runtime, the app can write to `writable/`.

### 9) `EXPOSE 80`

- **Meaning:** Document that this image is intended to listen on port 80 (Apache’s default).
- **Note:** This does **not** open the port by itself; it’s metadata. When you run the container, you (or Render) map a port on the host to port 80 in the container (e.g. Render maps their `PORT` to 80).
- **Result:** Anyone using this image knows “connect to port 80 inside the container.”

### No `CMD` or `ENTRYPOINT`

- The base image `php:8.1-apache` already has a default command that **starts Apache**. So when the container starts, Apache starts and serves your app. We don’t need to override it.

---

## Part 4: How `.dockerignore` Fits In

- **When:** Used during `docker build`, when Docker runs `COPY . .`.
- **What it does:** Tells Docker to **ignore** certain files or folders in your project directory, so they are **not** copied into the image.
- **Why:**
  - **`/vendor`** – We run `composer install` inside the image. Copying your local `vendor/` would be wrong (different OS/PHP) and would be overwritten anyway.
  - **`.env`** – Contains secrets and local config. On Render you set environment variables in the dashboard; you don’t want to bake `.env` into the image.
  - **`.git`** – Not needed to run the app; makes the image smaller.
  - **`/tests`** – Not needed in production.
  - **`writable/cache/*`, `writable/logs/*`, etc.** – Runtime data from your PC; we don’t want to ship that.

So: **Dockerfile = “what to put in the image and how to prepare it.”**  
**`.dockerignore` = “what not to copy from your project when doing `COPY . .`.”**

---

## Part 5: Build and Run Locally (Optional but Good to Try)

From your **project root** (where `Dockerfile` and `composer.json` are):

```bash
# Build the image (creates the “template”)
docker build -t portfolio .

# Run a container (start the app)
docker run -p 8080:80 portfolio
```

- **`-t portfolio`** – Name the image `portfolio`.
- **`-p 8080:80`** – Map your PC’s port 8080 to the container’s port 80.

Then open: **http://localhost:8080**

You should see your CodeIgniter app. Stop with `Ctrl+C` or `docker stop <container_id>`.

---

## Part 6: How Render Uses This

1. **You connect your Git repo** to Render and (if you use it) point to `render.yaml`.
2. **Render runs a build:**  
   - It uses your **Dockerfile** (and build context = your repo).  
   - So it runs the same steps: `FROM php:8.1-apache`, `RUN a2enmod rewrite`, `COPY . .`, `RUN composer install`, etc.  
   - Result: Render has an **image** for your app.
3. **Render runs a container** from that image:  
   - The container starts Apache (default command from the base image).  
   - Render maps their public URL (and `PORT`) to port 80 in the container.  
   - So visitors hit Render’s URL and get your CodeIgniter app.

So: **Render doesn’t “run PHP or Apache” by itself; it runs your Docker container, which contains PHP + Apache + your app.** That’s why the Dockerfile is necessary and why “Render will execute without Docker” wouldn’t work for CodeIgniter the way you want.

---

## Part 7: What You Must Set on Render (Environment)

Your app expects configuration. Since we **don’t** copy `.env` into the image (for security and flexibility), you set config via **Environment** in the Render dashboard:

1. **CI_ENVIRONMENT**  
   Set to `production` (you can put this in `render.yaml` or in the dashboard).

2. **app.baseURL**  
   **Critical.** Must be the **exact** URL Render gives your service, with a **trailing slash**, e.g.  
   `https://portfolio-xxxx.onrender.com/`  
   If this is wrong, links, assets (CSS/JS/images), and redirects will break.

After the first deploy, copy the service URL from Render and set:

- **Key:** `app.baseURL`  
- **Value:** `https://YOUR-SERVICE-NAME.onrender.com/`  
(Replace with your real URL, keep the trailing slash.)

You can add more later (e.g. database) as needed.

---

## Part 8: Summary

- **Dockerfile** = Recipe to build an **image**: PHP 8.1 + Apache + your code + `composer install` + correct document root and permissions.
- **Image** = Template. **Container** = Running instance of that template.
- **`.dockerignore`** = What to exclude when copying your project into the image.
- **Render** = Builds your image from the Dockerfile, runs a container from that image, and maps the public URL to your app.
- **No `.env` in image** = You configure the app with **Environment** in Render (especially `app.baseURL` and `CI_ENVIRONMENT`).

Once you set `app.baseURL` correctly on Render, your app should run the same as on XAMPP, but on the internet.
