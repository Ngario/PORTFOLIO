<?php
/**
 * Router script for PHP built-in server (php -S).
 *
 * Why this exists:
 * - Apache uses `.htaccess` (mod_rewrite) to send most requests to `index.php`.
 * - PHP's built-in server does NOT read `.htaccess`.
 * - This file emulates the same behavior: serve real static files if they exist,
 *   otherwise forward the request to CodeIgniter's front controller (index.php).
 *
 * Use it like:
 * php -S 0.0.0.0:$PORT -t public public/router.php
 */

// If the requested resource exists as a real file/directory under /public,
// let the built-in server serve it directly (CSS, JS, images, etc.).
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$fullPath = __DIR__ . $path;

if ($path !== '/' && (is_file($fullPath) || is_dir($fullPath))) {
    return false;
}

// Otherwise, route everything through CodeIgniter.
require __DIR__ . '/index.php';

