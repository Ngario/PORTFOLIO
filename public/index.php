<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Catch fatal errors and show them (env SHOW_ERRORS/RENDER_DEBUG, or cookie RENDER_DEBUG=1).
// Use 200 so some hosts don't replace the response body with a generic error page.
register_shutdown_function(function (): void {
    $err = error_get_last();
    if ($err === null) {
        return;
    }
    $fatals = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR];
    if (! in_array($err['type'], $fatals, true)) {
        return;
    }
    $show = (getenv('SHOW_ERRORS') && getenv('SHOW_ERRORS') !== '0')
        || (getenv('RENDER_DEBUG') && getenv('RENDER_DEBUG') !== '0')
        || (isset($_COOKIE['RENDER_DEBUG']) && $_COOKIE['RENDER_DEBUG'] === '1');
    if (! $show) {
        return;
    }
    $text = $err['message'] . "\n in " . $err['file'] . ' on line ' . $err['line'];
    $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR . 'logs';
    if (is_dir($dir) && is_writable($dir)) {
        @file_put_contents($dir . DIRECTORY_SEPARATOR . 'last-fatal.txt', $text);
    }
    if (! headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
        http_response_code(200);
    }
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Fatal error</title></head><body>';
    echo '<h1>Fatal error</h1><pre>' . htmlspecialchars($text) . '</pre>';
    echo '<p>Set SHOW_ERRORS=1 or RENDER_DEBUG=1 in Render Environment, or add cookie RENDER_DEBUG=1, then reload.</p></body></html>';
});

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '../vendor/autoload.php';
require FCPATH . '../app/Config/Paths.php';
// Paths.php defines the Config\Paths class; we must instantiate it by full class name.
$paths = new \Config\Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
