<?php

/*
 |--------------------------------------------------------------------------
 | ERROR DISPLAY
 |--------------------------------------------------------------------------
 | When SHOW_ERRORS or RENDER_DEBUG env is set, or cookie RENDER_DEBUG=1,
 | show full errors so you can fix 500s on Render.
 */
error_reporting(E_ALL & ~E_DEPRECATED);
$showErrors = (getenv('SHOW_ERRORS') && getenv('SHOW_ERRORS') !== '0')
    || (getenv('RENDER_DEBUG') && getenv('RENDER_DEBUG') !== '0')
    || (isset($_COOKIE['RENDER_DEBUG']) && $_COOKIE['RENDER_DEBUG'] === '1');
ini_set('display_errors', $showErrors ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');

defined('CI_DEBUG') || define('CI_DEBUG', $showErrors);
