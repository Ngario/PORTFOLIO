<?php

namespace App\Controllers;

use App\Models\BlogPostModel;

/**
 * Blog Controller
 *
 * Handles:
 *   GET /blog                    → index() = list all published posts from DB
 *   GET /blog/my-post-slug       → view('my-post-slug') = single post by slug
 *   GET /blog/category/tech      → category('tech') = posts in that category
 *
 * All content comes from the database (BlogPostModel). No placeholder posts.
 */
class Blog extends BaseController
{
    /**
     * List all blog posts (from database only; no placeholders).
     * URL: /blog
     */
    public function index()
    {
        $posts = $this->getPostsFromDb();
        if ($posts === null) {
            $posts = [];
        }

        $data = [
            'title'       => 'Blog',
            'description' => 'Articles, tutorials, and updates',
            'posts'       => $posts,
        ];

        return view('blog/index', $data);
    }

    /**
     * Single post by slug
     * URL: /blog/my-post-slug
     * Note: "blog/category/xyz" is matched by category() so slugs must not be "category".
     */
    public function view(string $slug)
    {
        if ($slug === 'category') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $post = null;
        $dbOk = false;

        try {
            $model = model(BlogPostModel::class);
            $dbOk  = true;
            $post  = $model->getBySlug($slug);
        } catch (\Throwable) {
            $dbOk = false;
        }

        // If DB is working but record doesn't exist, it's a real 404.
        if ($dbOk && $post === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (! $dbOk || $post === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => $post['title'] . ' - Blog',
            'post'    => $post,
        ];

        return view('blog/view', $data);
    }

    /**
     * Posts in a category
     * URL: /blog/category/tech
     */
    public function category(string $category)
    {
        $posts = $this->getPostsByCategoryFromDb($category);
        if ($posts === null) {
            $posts = [];
        }

        $data = [
            'title'       => 'Blog - ' . ucfirst($category),
            'description' => 'Posts in ' . ucfirst($category),
            'category'    => $category,
            'posts'       => $posts,
        ];

        return view('blog/category', $data);
    }

    /**
     * Load all posts from the database (published only).
     * Returns null only when the DB/table is not available.
     * Tries with author join first; if that fails (e.g. users table issue), tries without join.
     *
     * @return array<int, array<string, mixed>>|null
     */
    private function getPostsFromDb(): ?array
    {
        $model = model(BlogPostModel::class);
        try {
            return $model->getPosts('published_at', 'DESC', true);
        } catch (\Throwable $e) {
            try {
                return $model->getPosts('published_at', 'DESC', false);
            } catch (\Throwable) {
                return null;
            }
        }
    }

    /**
     * Load posts for a category from the database.
     * Returns null only when the DB/table is not available.
     *
     * @return array<int, array<string, mixed>>|null
     */
    private function getPostsByCategoryFromDb(string $category): ?array
    {
        try {
            $model = model(BlogPostModel::class);
            return $model->getByCategory($category);
        } catch (\Throwable) {
            return null;
        }
    }
}
