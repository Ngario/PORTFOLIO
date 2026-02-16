<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

/**
 * Admin Projects CRUD
 *
 * URLs (all protected by `adminauth` filter):
 *  - GET  /admin/projects           list
 *  - GET  /admin/projects/new       create form
 *  - POST /admin/projects           create action
 *  - GET  /admin/projects/(:num)/edit  edit form
 *  - POST /admin/projects/(:num)       update action
 *  - POST /admin/projects/(:num)/delete delete action
 */
class Projects extends BaseController
{
    public function index()
    {
        $model = model(ProjectModel::class);
        $projects = $model->orderBy('id', 'DESC')->findAll();

        return view('admin/projects/index', [
            'title'    => 'Manage Projects',
            'projects' => $projects,
        ]);
    }

    public function new()
    {
        return view('admin/projects/form', [
            'title'   => 'New Project',
            'mode'    => 'create',
            'project' => [
                'title'       => '',
                'slug'        => '',
                'description' => '',
                'tech_stack'  => '',
                'demo_url'    => '',
                'github_url'  => '',
                'featured'    => 0,
            ],
        ]);
    }

    public function create()
    {
        $model = model(ProjectModel::class);

        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug'));
        $desc  = trim((string) $this->request->getPost('description'));

        if ($slug === '' && $title !== '') {
            $slug = url_title($title, '-', true);
        }

        $techCsv = trim((string) $this->request->getPost('tech_stack'));
        $techs   = $techCsv === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $techCsv))));

        $data = [
            'title'       => $title,
            'slug'        => $slug,
            'description' => $desc,
            'tech_stack'  => json_encode($techs),
            'demo_url'    => trim((string) $this->request->getPost('demo_url')),
            'github_url'  => trim((string) $this->request->getPost('github_url')),
            'featured'    => $this->request->getPost('featured') ? 1 : 0,
        ];

        if ($data['title'] === '' || $data['slug'] === '') {
            return redirect()->back()->withInput()->with('error', 'Title and slug are required.');
        }

        $imagePath = $this->handleProjectImageUpload();
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }

        $ok = $model->insert($data) !== false;
        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to create project.');
        }

        return redirect()->to(base_url('admin/projects'))->with('success', 'Project created.');
    }

    public function edit(int $id)
    {
        $model = model(ProjectModel::class);
        $project = $model->find($id);
        if (! $project) {
            return redirect()->to(base_url('admin/projects'))->with('error', 'Project not found.');
        }

        // tech_stack is stored as JSON in DB; show as comma-separated in the form
        $techs = [];
        if (isset($project['tech_stack']) && is_string($project['tech_stack'])) {
            $decoded = json_decode($project['tech_stack'], true);
            $techs = is_array($decoded) ? $decoded : [];
        }
        $project['tech_stack'] = implode(', ', $techs);

        return view('admin/projects/form', [
            'title'   => 'Edit Project',
            'mode'    => 'edit',
            'project' => $project,
        ]);
    }

    public function update(int $id)
    {
        $model = model(ProjectModel::class);
        $existing = $model->find($id);
        if (! $existing) {
            return redirect()->to(base_url('admin/projects'))->with('error', 'Project not found.');
        }

        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug'));
        $desc  = trim((string) $this->request->getPost('description'));

        if ($slug === '' && $title !== '') {
            $slug = url_title($title, '-', true);
        }

        $techCsv = trim((string) $this->request->getPost('tech_stack'));
        $techs   = $techCsv === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $techCsv))));

        $data = [
            'title'       => $title,
            'slug'        => $slug,
            'description' => $desc,
            'tech_stack'  => json_encode($techs),
            'demo_url'    => trim((string) $this->request->getPost('demo_url')),
            'github_url'  => trim((string) $this->request->getPost('github_url')),
            'featured'    => $this->request->getPost('featured') ? 1 : 0,
        ];

        if ($data['title'] === '' || $data['slug'] === '') {
            return redirect()->back()->withInput()->with('error', 'Title and slug are required.');
        }

        $imagePath = $this->handleProjectImageUpload();
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }

        $ok = (bool) $model->update($id, $data);
        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to update project.');
        }

        return redirect()->to(base_url('admin/projects'))->with('success', 'Project updated.');
    }

    public function delete(int $id)
    {
        $model = model(ProjectModel::class);
        $model->delete($id);
        return redirect()->to(base_url('admin/projects'))->with('success', 'Project deleted.');
    }

    /**
     * Handle optional project image upload. Saves to public/uploads/projects/.
     * Returns path relative to uploads/ (e.g. 'projects/abc.jpg') or null if no valid file.
     */
    private function handleProjectImageUpload(): ?string
    {
        $file = $this->request->getFile('image');
        if ($file === null || ! $file->isValid() || $file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowed, true)) {
            return null;
        }

        $dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'projects';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $newName = $file->getRandomName();
        if (! $file->hasMoved() && $file->move($dir, $newName)) {
            return 'projects/' . $newName;
        }

        return null;
    }
}

