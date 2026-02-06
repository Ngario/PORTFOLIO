<?php
/**
 * Production error view.
 * Show real error when SHOW_ERRORS or RENDER_DEBUG env is set, OR when cookie RENDER_DEBUG=1.
 */
$showDebug = (getenv('SHOW_ERRORS') && getenv('SHOW_ERRORS') !== '0')
    || (getenv('RENDER_DEBUG') && getenv('RENDER_DEBUG') !== '0')
    || (isset($_COOKIE['RENDER_DEBUG']) && $_COOKIE['RENDER_DEBUG'] === '1');
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <title><?= $showDebug ? esc($title ?? 'Error') : lang('Errors.whoops') ?></title>
    <style>
        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
        .debug-block { text-align: left; max-width: 900px; margin: 20px auto; padding: 20px; background: #1e1e1e; color: #d4d4d4; font-family: monospace; font-size: 13px; border-radius: 8px; overflow-x: auto; }
        .debug-block h2 { color: #569cd6; margin-top: 0; }
        .debug-block .msg { color: #ce9178; margin: 10px 0; }
        .debug-block .file { color: #9cdcfe; }
        .debug-block pre { margin: 10px 0; white-space: pre-wrap; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container text-center">
        <?php if ($showDebug && isset($message)): ?>
            <h1 class="headline">Error (500)</h1>
            <p class="lead">Render debug is ON – fix the error below, then set SHOW_ERRORS=0 and RENDER_DEBUG=0.</p>
            <div class="debug-block">
                <h2><?= esc($type ?? 'Exception') ?></h2>
                <p class="msg"><strong>Message:</strong> <?= nl2br(esc($message)) ?></p>
                <?php if (! empty($file)): ?>
                    <p class="file"><strong>File:</strong> <?= esc($file) ?> <strong>Line:</strong> <?= esc((string) ($line ?? '')) ?></p>
                <?php endif; ?>
                <?php if (! empty($trace) && is_array($trace)): ?>
                    <p><strong>Stack trace:</strong></p>
                    <pre><?php
                    foreach (array_slice($trace, 0, 15) as $i => $frame) {
                        $file = $frame['file'] ?? '[internal]';
                        $line = $frame['line'] ?? '';
                        $func = ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? '');
                        echo esc("#{$i} {$file}({$line}): {$func}\n");
                    }
                    ?></pre>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <h1 class="headline"><?= lang('Errors.whoops') ?></h1>
            <p class="lead"><?= lang('Errors.weHitASnag') ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
