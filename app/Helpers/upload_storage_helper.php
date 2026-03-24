<?php

if (! function_exists('upload_storage_write_dir')) {
    /**
     * Resolve a writable upload directory for a subpath.
     * Prefers public/uploads for local setups, falls back to writable/uploads for hosted environments.
     */
    function upload_storage_write_dir(string $subPath = ''): ?string
    {
        $normalizedSubPath = trim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $subPath), DIRECTORY_SEPARATOR);
        $bases = [
            FCPATH . 'uploads',
            WRITEPATH . 'uploads',
        ];

        foreach ($bases as $base) {
            $target = rtrim($base, DIRECTORY_SEPARATOR);
            if ($normalizedSubPath !== '') {
                $target .= DIRECTORY_SEPARATOR . $normalizedSubPath;
            }

            if (! is_dir($target) && ! @mkdir($target, 0775, true) && ! is_dir($target)) {
                continue;
            }

            if (is_writable($target)) {
                return $target;
            }
        }

        return null;
    }
}

if (! function_exists('upload_storage_resolve_file')) {
    /**
     * Resolve a stored upload file from relative path (e.g. "blog_posts/abc.jpg").
     */
    function upload_storage_resolve_file(string $relativePath): ?string
    {
        $cleanRelative = trim(str_replace(['\\', '..'], ['/', ''], $relativePath), '/');
        if ($cleanRelative === '') {
            return null;
        }

        $bases = [
            FCPATH . 'uploads',
            WRITEPATH . 'uploads',
        ];

        foreach ($bases as $base) {
            $fullPath = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanRelative);
            $resolved = realpath($fullPath);
            if ($resolved !== false && is_file($resolved)) {
                return $resolved;
            }
        }

        return null;
    }
}
