<?php

namespace App\Controllers;

use App\Models\BlogPostModel;

/**
 * Blog Controller
 *
 * Handles:
 *   GET /blog                    → index() = list all posts
 *   GET /blog/my-post-slug       → view('my-post-slug') = single post by slug
 *   GET /blog/category/tech      → category('tech') = posts in that category
 *
 * Uses BlogPostModel when the posts table exists.
 * Falls back to placeholder data otherwise so the site still works.
 */
class Blog extends BaseController
{
    /**
     * List all blog posts
     * URL: /blog
     */
    public function index()
    {
        $posts = $this->getPostsFromDb();
        if ($posts === null) {
            $posts = $this->getPlaceholderPosts();
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

        // If DB isn't available, fall back to placeholder data.
        if (!$dbOk) {
            $posts = $this->getPlaceholderPosts();
            foreach ($posts as $p) {
                if (($p['slug'] ?? '') === $slug) {
                    $post = $p;
                    break;
                }
            }
            if ($post === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
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
            $allPosts = $this->getPlaceholderPosts();
            $posts    = array_filter($allPosts, static function ($p) use ($category) {
                return strtolower($p['category'] ?? '') === strtolower($category);
            });
            $posts = array_values($posts);
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
     * Load all posts from the database.
     * Returns null only when the DB/table is not available.
     *
     * @return array<int, array<string, mixed>>|null
     */
    private function getPostsFromDb(): ?array
    {
        try {
            $model = model(BlogPostModel::class);
            return $model->getPosts();
        } catch (\Throwable) {
            return null;
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

    private function getPlaceholderPosts(): array
    {
        return [
            [
                'id'         => 1,
                'title'      => 'Getting Started with CodeIgniter 4',
                'slug'       => 'getting-started-codeigniter-4',
                'excerpt'    => 'A quick guide to setting up your first CI4 project and understanding the folder structure.',
                'content'    => 'CodeIgniter 4 is a modern PHP framework with a small footprint and clear documentation. This post walks you through installation via Composer, the roles of the app and public folders, and how routes map URLs to controllers.',
                'category'   => 'tutorials',
                'author'     => 'Idd Mumanyi',
                'image'      => 'blog/Ai.jpg',
                'published_at'=> '2025-01-15',
            ],
            [
                'id'         => 2,
                'title'      => 'Building a Portfolio Site from Scratch',
                'slug'       => 'building-portfolio-from-scratch',
                'excerpt'    => 'How to plan and build a portfolio with projects, services, and a blog using PHP and Bootstrap.',
                'content'    => 'A portfolio site needs a clear structure: homepage, about, projects, services, blog, and contact. We look at routing, controllers, and views, and how to keep the design consistent with a main layout.',
                'category'   => 'tutorials',
                'author'     => 'Idd Mumanyi',
                'image'      => 'blog/portimage.jpg',
                'published_at'=> '2025-01-20',
            ],
            [
                'id'         => 3,
                'title'      => 'Why I Use PHP and CodeIgniter in 2025',
                'slug'       => 'why-php-codeigniter-2025',
                'excerpt'    => 'PHP remains a solid choice for server-rendered apps. Here is why I still pick CodeIgniter for many projects.',
                'content'    => 'PHP powers a huge share of the web. CodeIgniter 4 gives you a simple MVC structure, good performance, and easy deployment on shared hosting or Docker. This post explains my reasoning for choosing it for portfolios and small business sites.',
                'category'   => 'opinion',
                'author'     => 'Idd Mumanyi',
                'image'      => 'blog/Ai.jpg',
                'published_at'=> '2025-01-25',
            ],
        ];
    }
}
