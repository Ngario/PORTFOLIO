<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DownloadCategoryModel;

/**
 * Admin Download Categories CRUD
 *
 * URLs (protected by adminauth filter via routes group):
 *  - GET  /admin/download-categories
 *  - GET  /admin/download-categories/new
 *  - POST /admin/download-categories
 *  - GET  /admin/download-categories/(:num)/edit
 *  - POST /admin/download-categories/(:num)
 *  - POST /admin/download-categories/(:num)/delete
 */
class DownloadCategories extends BaseController
{
    public function index()
    {
        $model = model(DownloadCategoryModel::class);
        $categories = $model->orderBy('name', 'ASC')->findAll();

        return view('admin/download_categories/index', [
            'title'      => 'Download Categories',
            'categories' => $categories,
        ]);
    }

    public function new()
    {
        $model = model(DownloadCategoryModel::class);
        $parents = $model->orderBy('name', 'ASC')->findAll();

        return view('admin/download_categories/form', [
            'title'    => 'New Download Category',
            'mode'     => 'create',
            'category' => ['name' => '', 'slug' => '', 'parent_id' => null],
            'parents'  => $parents,
        ]);
    }

    public function create()
    {
        $model = model(DownloadCategoryModel::class);

        $name = trim((string) $this->request->getPost('name'));
        $slug = trim((string) $this->request->getPost('slug'));
        $parentId = $this->request->getPost('parent_id');
        $parentId = ($parentId === '' || $parentId === null) ? null : (int) $parentId;

        if ($slug === '' && $name !== '') {
            $slug = url_title($name, '-', true);
        }

        if ($name === '' || $slug === '') {
            return redirect()->back()->withInput()->with('error', 'Name and slug are required.');
        }

        $ok = $model->insert([
            'name'      => $name,
            'slug'      => $slug,
            'parent_id' => $parentId,
        ]);

        if ($ok === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create category.');
        }

        return redirect()->to(base_url('admin/download-categories'))->with('success', 'Category created.');
    }

    public function edit(int $id)
    {
        $model = model(DownloadCategoryModel::class);
        $category = $model->find($id);
        if (! $category) {
            return redirect()->to(base_url('admin/download-categories'))->with('error', 'Category not found.');
        }

        $parents = $model->where('id !=', $id)->orderBy('name', 'ASC')->findAll();

        return view('admin/download_categories/form', [
            'title'    => 'Edit Download Category',
            'mode'     => 'edit',
            'category' => $category,
            'parents'  => $parents,
        ]);
    }

    public function update(int $id)
    {
        $model = model(DownloadCategoryModel::class);
        if (! $model->find($id)) {
            return redirect()->to(base_url('admin/download-categories'))->with('error', 'Category not found.');
        }

        $name = trim((string) $this->request->getPost('name'));
        $slug = trim((string) $this->request->getPost('slug'));
        $parentId = $this->request->getPost('parent_id');
        $parentId = ($parentId === '' || $parentId === null) ? null : (int) $parentId;

        if ($slug === '' && $name !== '') {
            $slug = url_title($name, '-', true);
        }

        if ($name === '' || $slug === '') {
            return redirect()->back()->withInput()->with('error', 'Name and slug are required.');
        }

        $ok = (bool) $model->update($id, [
            'name'      => $name,
            'slug'      => $slug,
            'parent_id' => $parentId,
        ]);

        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to update category.');
        }

        return redirect()->to(base_url('admin/download-categories'))->with('success', 'Category updated.');
    }

    public function delete(int $id)
    {
        $model = model(DownloadCategoryModel::class);
        $model->delete($id);
        return redirect()->to(base_url('admin/download-categories'))->with('success', 'Category deleted.');
    }
}

