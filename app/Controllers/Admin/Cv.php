<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CvStorageModel;

/**
 * Admin CV management: upload or replace the site CV (stored in DB).
 * GET  /admin/cv         – show form and current CV info
 * POST /admin/cv/upload  – handle file upload, save to cv_storage
 */
class Cv extends BaseController
{
    public function index()
    {
        $model = model(CvStorageModel::class);
        $info = $model->getCvInfo();

        return view('admin/cv/index', [
            'title'   => 'My CV',
            'cvInfo'  => $info,
        ]);
    }

    public function upload()
    {
        $file = $this->request->getFile('cv_file');

        if (! $file || ! $file->isValid()) {
            return redirect()->to(base_url('admin/cv'))->withInput()->with('error', 'Please choose a valid PDF file.');
        }

        if ($file->getClientExtension() !== 'pdf' && $file->getClientMimeType() !== 'application/pdf') {
            return redirect()->to(base_url('admin/cv'))->withInput()->with('error', 'Only PDF files are allowed.');
        }

        $content = file_get_contents($file->getTempName());
        if ($content === false || strlen($content) === 0) {
            return redirect()->to(base_url('admin/cv'))->withInput()->with('error', 'Could not read the file.');
        }

        $model = model(CvStorageModel::class);
        $filename = $file->getClientName();
        $mimeType = $file->getClientMimeType() ?: 'application/pdf';

        if (! $model->saveCv($filename, $content, $mimeType)) {
            return redirect()->to(base_url('admin/cv'))->withInput()->with('error', 'Failed to save CV.');
        }

        return redirect()->to(base_url('admin/cv'))->with('success', 'CV updated. Visitors can download it from the site.');
    }
}
