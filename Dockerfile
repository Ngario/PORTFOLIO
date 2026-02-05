# =============================================================================
# DOCKERFILE - CodeIgniter 4 Portfolio
# =============================================================================
#
# WHAT IS A DOCKERFILE?
# ---------------------
# A Dockerfile is a text file that contains instructions to BUILD a Docker IMAGE.
# Think of it like a recipe: each line adds one step. When you run "docker build",
# Docker reads this file from top to bottom and creates a snapshot (image) of
# your application + everything it needs to run (PHP, Apache, your code).
#
# IMAGE vs CONTAINER (important!)
# ------------------------------
# - IMAGE = The built snapshot (like a saved template). Built once, reused

# - CONTAINER = A running instance of that image (like opening the app).
#   You can run many containers from one image.
#
# =============================================================================

# -----------------------------------------------------------------------------
# STEP 1: Choose the base image
# -----------------------------------------------------------------------------
# FROM = "Start from this existing image"
#
# php:8.1-apache means:
#   - Official PHP image from Docker Hub (hub.docker.com)
#   - Version 8.1 (matches your composer.json "php": "^8.1")
#   - Variant "apache" = PHP + Apache web server pre-installed
#
# Why Apache? CodeIgniter uses .htaccess for URL rewriting. Apache reads
# .htaccess; PHP's built-in server does not. So we use Apache.
#
# This line creates the first "layer" of your image. Everything after
# builds on top of this.
# -----------------------------------------------------------------------------
FROM php:8.1-apache

# -----------------------------------------------------------------------------
# STEP 1.5: Install required PHP extensions for CodeIgniter 4
# -----------------------------------------------------------------------------
# CodeIgniter (via composer.lock) requires:
#   - ext-intl  (needs libicu-dev)
#   - ext-mbstring (needs libonig-dev for oniguruma)
#   - ext-mysqli (for MySQL; no extra libs)
# These are NOT all enabled by default in php:8.1-apache, so we compile them.
#
# Required Debian packages:
#   libicu-dev   = for intl
#   libonig-dev  = for mbstring (oniguruma)
#   libzip-dev, zip, unzip = for Composer / general use
# -----------------------------------------------------------------------------
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libicu-dev \
        libonig-dev \
        libzip-dev \
        zip \
        unzip \
    ; \
    docker-php-ext-configure intl; \
    docker-php-ext-install -j$(nproc) intl mbstring mysqli; \
    rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------------------------------
# STEP 2: Enable Apache mod_rewrite
# -----------------------------------------------------------------------------
# RUN = "Execute a command while building the image"
#
# a2enmod rewrite = Apache command to enable the "rewrite" module.
# CodeIgniter's .htaccess uses RewriteRule to send all requests to index.php.
# Without mod_rewrite, URLs like /about would not work (404).
#
# The result of this command is saved as a new layer in the image.
# -----------------------------------------------------------------------------
RUN a2enmod rewrite

# -----------------------------------------------------------------------------
# STEP 3: Set Apache's document root to /var/www/html/public
# -----------------------------------------------------------------------------
# By default, Apache serves files from /var/www/html (project root).
# CodeIgniter MUST be served from the "public" folder only (security:
# app/, writable/, .env must not be web-accessible).
#
# ENV = Set an environment variable (available at build and run time).
# We set APACHE_DOCUMENT_ROOT so Apache serves from public/.
#
# The two sed commands edit Apache config files and replace
# /var/www/html with /var/www/html/public so that:
#   - Requests go to public/
#   - public/index.php is the entry point
#   - CSS, JS, images in public/ are served correctly
# -----------------------------------------------------------------------------
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# -----------------------------------------------------------------------------
# STEP 4: Install Composer inside the image
# -----------------------------------------------------------------------------
# COPY --from=composer:2 = Copy a file FROM another image (multi-stage style).
# We don't install Composer via apt; we take the official composer image's
# binary and copy it into our image. That way we get a known-good Composer.
#
# /usr/bin/composer = Where the composer binary lives in the composer image.
# We copy it to /usr/bin/composer in OUR image so we can run "composer" later.
# -----------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------------------------
# STEP 5: Set working directory
# -----------------------------------------------------------------------------
# WORKDIR = "From now on, all commands run in this folder"
# So when we COPY . ., files go into /var/www/html.
# When we RUN composer install, it runs in /var/www/html and uses
# composer.json from there.
# -----------------------------------------------------------------------------
WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# STEP 6: Copy your application code into the image
# -----------------------------------------------------------------------------
# COPY . . = Copy everything from the BUILD CONTEXT (your project folder on
# your machine) into the current WORKDIR in the image (/var/www/html).
#
# What gets copied? Everything except what's in .dockerignore (see below).
# So: app/, public/, composer.json, .htaccess, etc. Not: vendor/, .git, .env
# (we list those in .dockerignore).
#
# Important: This runs on the machine doing "docker build" (your PC or Render).
# So the paths are relative to where you run "docker build" (usually project root).
# -----------------------------------------------------------------------------
COPY . .

# -----------------------------------------------------------------------------
# STEP 7: Install PHP dependencies with Composer
# -----------------------------------------------------------------------------
# RUN composer install = Same as on your PC, but runs INSIDE the image
# during build. So the image will contain the vendor/ folder with
# CodeIgniter and all dependencies. When the container runs, it doesn't
# need to run composer again.
#
# --no-dev = Don't install dev dependencies (testing tools, etc.). Keeps
# image smaller and production-safe.
#
# --optimize-autoloader = Creates a class map for faster autoloading in production.
# -----------------------------------------------------------------------------
RUN composer install --no-dev --optimize-autoloader

# -----------------------------------------------------------------------------
# STEP 8: Fix permissions for writable folder
# -----------------------------------------------------------------------------
# CodeIgniter writes to writable/ (logs, cache, session). Apache runs as
# user "www-data". So we give www-data ownership of writable/ so the app
# can write there at runtime.
#
# chown -R = Change owner recursively for all files and folders inside writable/.
# -----------------------------------------------------------------------------
RUN chown -R www-data:www-data /var/www/html/writable

# -----------------------------------------------------------------------------
# STEP 9: Expose port 80
# -----------------------------------------------------------------------------
# EXPOSE 80 = Document that this image listens on port 80 (Apache's default).
# It doesn't actually open the port; it's metadata. When you run the
# container, you map host port (e.g. 8080) to container port 80.
# Render does this automatically: it maps PORT (e.g. 10000) to 80.
# -----------------------------------------------------------------------------
EXPOSE 80

# -----------------------------------------------------------------------------
# No CMD or ENTRYPOINT needed
# -----------------------------------------------------------------------------
# The base image (php:8.1-apache) already has a default command that
# starts Apache. So when the container runs, Apache starts and serves
# your app. We don't need to override it.
# -----------------------------------------------------------------------------
