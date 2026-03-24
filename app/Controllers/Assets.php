<?php

namespace App\Controllers;

class Assets extends BaseController
{
    /**
     * Serve uploaded files from either public/uploads or writable/uploads.
     */
    public function upload(string $path = '')
    {
        helper('upload_storage');

        $resolved = upload_storage_resolve_file($path);
        if ($resolved === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($resolved) ?: 'application/octet-stream';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody((string) file_get_contents($resolved));
    }
}
