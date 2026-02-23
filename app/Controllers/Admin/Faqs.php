<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FaqModel;

/**
 * Admin FAQs CRUD
 *
 * URLs (protected by adminauth filter):
 *  - GET  /admin/faqs
 *  - GET  /admin/faqs/new
 *  - POST /admin/faqs
 *  - GET  /admin/faqs/(:num)/edit
 *  - POST /admin/faqs/(:num)
 *  - POST /admin/faqs/(:num)/delete
 */
class Faqs extends BaseController
{
    public function index()
    {
        $model = model(FaqModel::class);
        $faqs = $model->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();

        return view('admin/faqs/index', [
            'title' => 'FAQs',
            'faqs'  => $faqs,
        ]);
    }

    public function new()
    {
        return view('admin/faqs/form', [
            'title' => 'New FAQ',
            'mode'  => 'create',
            'faq'   => ['question' => '', 'answer' => '', 'sort_order' => 0],
        ]);
    }

    public function create()
    {
        $model = model(FaqModel::class);
        $question = trim((string) $this->request->getPost('question'));
        $answer   = trim((string) $this->request->getPost('answer'));
        $sortOrder = (int) $this->request->getPost('sort_order');

        if ($question === '' || $answer === '') {
            return redirect()->back()->withInput()->with('error', 'Question and answer are required.');
        }

        $ok = $model->insert([
            'question'   => $question,
            'answer'     => $answer,
            'sort_order' => $sortOrder,
        ]);

        if ($ok === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create FAQ.');
        }

        return redirect()->to(base_url('admin/faqs'))->with('success', 'FAQ created.');
    }

    public function edit(int $id)
    {
        $model = model(FaqModel::class);
        $faq = $model->find($id);
        if (! $faq) {
            return redirect()->to(base_url('admin/faqs'))->with('error', 'FAQ not found.');
        }

        return view('admin/faqs/form', [
            'title' => 'Edit FAQ',
            'mode'  => 'edit',
            'faq'   => $faq,
        ]);
    }

    public function update(int $id)
    {
        $model = model(FaqModel::class);
        if (! $model->find($id)) {
            return redirect()->to(base_url('admin/faqs'))->with('error', 'FAQ not found.');
        }

        $question  = trim((string) $this->request->getPost('question'));
        $answer    = trim((string) $this->request->getPost('answer'));
        $sortOrder = (int) $this->request->getPost('sort_order');

        if ($question === '' || $answer === '') {
            return redirect()->back()->withInput()->with('error', 'Question and answer are required.');
        }

        $ok = (bool) $model->update($id, [
            'question'   => $question,
            'answer'     => $answer,
            'sort_order' => $sortOrder,
        ]);

        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to update FAQ.');
        }

        return redirect()->to(base_url('admin/faqs'))->with('success', 'FAQ updated.');
    }

    public function delete(int $id)
    {
        $model = model(FaqModel::class);
        $model->delete($id);
        return redirect()->to(base_url('admin/faqs'))->with('success', 'FAQ deleted.');
    }
}
