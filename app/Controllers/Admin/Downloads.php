<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DownloadModel;
use Config\Database;

/**
 * Admin Downloads CRUD
 *
 * URLs (protected by adminauth filter via routes group):
 *  - GET  /admin/downloads
 *  - GET  /admin/downloads/new
 *  - POST /admin/downloads
 *  - GET  /admin/downloads/(:num)/edit
 *  - POST /admin/downloads/(:num)
 *  - POST /admin/downloads/(:num)/delete
 *
 * Uploads are stored under: public/uploads/downloads/
 * DB stores file_path relative to public/uploads/ (example: downloads/myfile.pdf)
 */
class Downloads extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $rows = $db->table('downloads')
            ->select('downloads.*, download_categories.name as category_name')
            ->join('download_categories', 'download_categories.id = downloads.category_id', 'left')
            ->orderBy('downloads.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/downloads/index', [
            'title'     => 'Manage Downloads',
            'downloads' => $rows,
        ]);
    }

    public function new()
    {
        $db = Database::connect();
        $categories = $db->table('download_categories')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/downloads/form', [
            'title'      => 'New Download',
            'mode'       => 'create',
            'download'   => [
                'category_id' => '',
                'title'       => '',
                'description' => '',
                'image'       => '',
                'is_paid'     => 0,
                'price'       => '',
                'is_active'   => 1,
                'file_path'   => '',
                'file_size'   => '',
            ],
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $model = model(DownloadModel::class);

        $data = $this->readDownloadPostData();
        if ($data === null) {
            return redirect()->back()->withInput()->with('error', 'Title and category are required.');
        }

        // Handle file upload (required for create)
        $upload = $this->handleUpload();
        if ($upload === null) {
            $msg = $this->getUploadErrorMessage();
            return redirect()->back()->withInput()->with('error', $msg);
        }
        $data['file_path'] = $upload['file_path'];
        $data['file_size'] = $upload['file_size'];
        $imagePath = $this->handleDownloadImageUpload();
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }

        $ok = $model->insert($data);
        if ($ok === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create download.');
        }

        return redirect()->to(base_url('admin/downloads'))->with('success', 'Download created.');
    }

    public function edit(int $id)
    {
        $model = model(DownloadModel::class);
        $download = $model->find($id);
        if (! $download) {
            return redirect()->to(base_url('admin/downloads'))->with('error', 'Download not found.');
        }

        $db = Database::connect();
        $categories = $db->table('download_categories')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/downloads/form', [
            'title'      => 'Edit Download',
            'mode'       => 'edit',
            'download'   => $download,
            'categories' => $categories,
        ]);
    }

    public function update(int $id)
    {
        $model = model(DownloadModel::class);
        $existing = $model->find($id);
        if (! $existing) {
            return redirect()->to(base_url('admin/downloads'))->with('error', 'Download not found.');
        }

        $data = $this->readDownloadPostData();
        if ($data === null) {
            return redirect()->back()->withInput()->with('error', 'Title and category are required.');
        }

        // File upload is optional on edit; if uploaded, replace file_path/file_size.
        $upload = $this->handleUpload(false);
        if ($upload !== null) {
            $data['file_path'] = $upload['file_path'];
            $data['file_size'] = $upload['file_size'];
        }
        $imagePath = $this->handleDownloadImageUpload();
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }

        $ok = (bool) $model->update($id, $data);
        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to update download.');
        }

        return redirect()->to(base_url('admin/downloads'))->with('success', 'Download updated.');
    }

    public function delete(int $id)
    {
        $db = Database::connect();
        // Remove logs/access rows if you want (optional). We'll just delete the download row.
        $model = model(DownloadModel::class);
        $model->delete($id);
        return redirect()->to(base_url('admin/downloads'))->with('success', 'Download deleted.');
    }

    /**
     * Read and normalize POST data for downloads table.
     *
     * @return array<string, mixed>|null
     */
    private function readDownloadPostData(): ?array
    {
        $title = trim((string) $this->request->getPost('title'));
        $categoryId = (int) $this->request->getPost('category_id');

        if ($title === '' || $categoryId <= 0) {
            return null;
        }

        $isPaid = $this->request->getPost('is_paid') ? 1 : 0;
        $price  = trim((string) $this->request->getPost('price'));
        $isActive = $this->request->getPost('is_active') ? 1 : 0;

        return [
            'category_id' => $categoryId,
            'title'       => $title,
            'description' => (string) $this->request->getPost('description'),
            'is_paid'     => $isPaid,
            'price'       => $isPaid ? $price : null,
            'is_active'   => $isActive,
        ];
    }

    /**
     * Handle file upload from form input name="file".
     *
     * @param bool $required If true, upload is required.
     * @return array{file_path: string, file_size: int}|null
     */
    private function handleUpload(bool $required = true): ?array
    {
        $file = $this->request->getFile('file');
        if ($file === null) {
            return $required ? null : null;
        }

        // No file chosen (common when form re-displayed or user didn't select)
        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
            return $required ? null : null;
        }

        if (! $file->isValid()) {
            return $required ? null : null;
        }

        $ext = strtolower((string) $file->getClientExtension());
        $allowed = ['pdf', 'zip', 'rar', '7z', 'doc', 'docx', 'ppt', 'pptx', 'mp4', 'epub'];
        if (! in_array($ext, $allowed, true)) {
            return null;
        }

        $targetDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'downloads';
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $safeName = url_title(pathinfo($file->getClientName(), PATHINFO_FILENAME), '-', true);
        if ($safeName === '') {
            $safeName = 'file';
        }
        $newName = $safeName . '-' . date('YmdHis') . '.' . $ext;

        if (! $file->move($targetDir, $newName, true)) {
            return $required ? null : null;
        }

        return [
            'file_path' => 'downloads' . '/' . $newName,
            'file_size' => (int) $file->getSize(),
        ];
    }

    /**
     * User-friendly message when file upload is missing or invalid.
     */
    private function getUploadErrorMessage(): string
    {
        $file = $this->request->getFile('file');
        if ($file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE && $file->getError() !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE   => 'File exceeds server limit. Try a smaller file.',
                UPLOAD_ERR_FORM_SIZE  => 'File too large.',
                UPLOAD_ERR_PARTIAL    => 'Upload was interrupted. Try again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server upload error. Try again later.',
                UPLOAD_ERR_CANT_WRITE => 'Server could not save file. Try again.',
                UPLOAD_ERR_EXTENSION => 'Upload blocked by server.',
            ];
            $msg = $errors[$file->getError()] ?? $file->getErrorString();
            return 'Upload failed: ' . $msg . ' Allowed types: PDF, ZIP, RAR, 7Z, DOC, DOCX, PPT, PPTX, MP4, EPUB.';
        }
        return 'Please upload a file. Allowed: PDF, ZIP, RAR, 7Z, DOC, DOCX, PPT, PPTX, MP4, EPUB.';
    }

    /**
     * Handle cover/placeholder image upload for download. Saves to public/uploads/downloads/covers/
     *
     * @return string|null Path relative to uploads/ (e.g. downloads/covers/abc.jpg) or null
     */
    private function handleDownloadImageUpload(): ?string
    {
        $file = $this->request->getFile('cover_image');
        if ($file === null || ! $file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $ext = strtolower((string) $file->getClientExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (! in_array($ext, $allowed, true)) {
            return null;
        }
        $targetDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'downloads' . DIRECTORY_SEPARATOR . 'covers';
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }
        $safeName = url_title(pathinfo($file->getClientName(), PATHINFO_FILENAME), '-', true) ?: 'cover';
        $newName = $safeName . '-' . date('YmdHis') . '.' . $ext;
        $file->move($targetDir, $newName, true);
        return 'downloads/covers/' . $newName;
    }
}

