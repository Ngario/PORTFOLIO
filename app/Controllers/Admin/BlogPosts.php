<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogPostModel;
use Config\Database;

/**
 * Admin Blog Posts CRUD
 *
 * URLs (protected by `adminauth` filter):
 *  - GET  /admin/blog-posts
 *  - GET  /admin/blog-posts/new
 *  - POST /admin/blog-posts
 *  - GET  /admin/blog-posts/(:num)/edit
 *  - POST /admin/blog-posts/(:num)
 *  - POST /admin/blog-posts/(:num)/delete
 */
class BlogPosts extends BaseController
{
    public function index()
    {
        $model = model(BlogPostModel::class);
        $posts = $model->select('blog_posts.*, users.name as author_name')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->orderBy('blog_posts.id', 'DESC')
            ->findAll();

        return view('admin/blog_posts/index', [
            'title' => 'Manage Blog Posts',
            'posts' => $posts,
        ]);
    }

    public function new()
    {
        $db = Database::connect();
        $categories = $db->table('blog_categories')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('admin/blog_posts/form', [
            'title'      => 'New Blog Post',
            'mode'       => 'create',
            'post'       => [
                'title'        => '',
                'slug'         => '',
                'content'      => '',
                'status'       => 'draft',
                'published_at' => '',
            ],
            'categories' => $categories,
            'selected'   => [],
        ]);
    }

    public function create()
    {
        $model = model(BlogPostModel::class);

        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug'));
        if ($slug === '' && $title !== '') {
            $slug = url_title($title, '-', true);
        }

        $data = [
            'title'        => $title,
            'slug'         => $slug,
            'content'      => (string) $this->request->getPost('content'),
            'status'       => (string) ($this->request->getPost('status') ?: 'draft'),
            'published_at' => $this->request->getPost('published_at') ?: null,
            'author_id'    => (int) (session()->get('admin_user_id') ?? 0),
        ];

        if ($data['title'] === '' || $data['slug'] === '') {
            return redirect()->back()->withInput()->with('error', 'Title and slug are required.');
        }

        $ok = $model->insert($data);
        if ($ok === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to create post.');
        }

        $postId = (int) $model->getInsertID();
        $this->syncCategories($postId, (array) $this->request->getPost('categories'));

        return redirect()->to(base_url('admin/blog-posts'))->with('success', 'Post created.');
    }

    public function edit(int $id)
    {
        $db = Database::connect();

        $model = model(BlogPostModel::class);
        $post = $model->find($id);
        if (! $post) {
            return redirect()->to(base_url('admin/blog-posts'))->with('error', 'Post not found.');
        }

        $categories = $db->table('blog_categories')->orderBy('name', 'ASC')->get()->getResultArray();
        $selectedRows = $db->table('blog_post_category')->select('category_id')->where('post_id', $id)->get()->getResultArray();
        $selected = array_map(static fn ($r) => (int) $r['category_id'], $selectedRows);

        return view('admin/blog_posts/form', [
            'title'      => 'Edit Blog Post',
            'mode'       => 'edit',
            'post'       => $post,
            'categories' => $categories,
            'selected'   => $selected,
        ]);
    }

    public function update(int $id)
    {
        $model = model(BlogPostModel::class);
        $existing = $model->find($id);
        if (! $existing) {
            return redirect()->to(base_url('admin/blog-posts'))->with('error', 'Post not found.');
        }

        $title = trim((string) $this->request->getPost('title'));
        $slug  = trim((string) $this->request->getPost('slug'));
        if ($slug === '' && $title !== '') {
            $slug = url_title($title, '-', true);
        }

        $data = [
            'title'        => $title,
            'slug'         => $slug,
            'content'      => (string) $this->request->getPost('content'),
            'status'       => (string) ($this->request->getPost('status') ?: 'draft'),
            'published_at' => $this->request->getPost('published_at') ?: null,
        ];

        if ($data['title'] === '' || $data['slug'] === '') {
            return redirect()->back()->withInput()->with('error', 'Title and slug are required.');
        }

        $ok = (bool) $model->update($id, $data);
        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Failed to update post.');
        }

        $this->syncCategories($id, (array) $this->request->getPost('categories'));

        return redirect()->to(base_url('admin/blog-posts'))->with('success', 'Post updated.');
    }

    public function delete(int $id)
    {
        $db = Database::connect();
        $db->table('blog_post_category')->where('post_id', $id)->delete();

        $model = model(BlogPostModel::class);
        $model->delete($id);

        return redirect()->to(base_url('admin/blog-posts'))->with('success', 'Post deleted.');
    }

    /**
     * Sync the pivot table blog_post_category for one post.
     *
     * @param int $postId
     * @param array<int|string, mixed> $categoryIds
     */
    private function syncCategories(int $postId, array $categoryIds): void
    {
        $db = Database::connect();

        // Clean input to integers
        $ids = array_values(array_unique(array_filter(array_map(static fn ($v) => (int) $v, $categoryIds))));

        // Delete existing relations
        $db->table('blog_post_category')->where('post_id', $postId)->delete();

        // Insert new relations
        foreach ($ids as $catId) {
            $db->table('blog_post_category')->insert([
                'post_id'     => $postId,
                'category_id' => $catId,
            ]);
        }
    }
}

