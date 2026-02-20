<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BlogPostModel – matches your `blog_posts` table.
 * Columns: id, title, slug, content, author_id, status, published_at, created_at
 *
 * Categories are in blog_categories + blog_post_category (many-to-many).
 * Author name comes from users table (author_id).
 */
class BlogPostModel extends Model
{
    protected $table = 'blog_posts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    /** Table has created_at but no updated_at. */
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'author_id',
        'status',
        'published_at',
        'image',
    ];

    /**
     * Get published posts, most recent first.
     * Optionally join users to get author name (author_name).
     *
     * @return array<int, array<string, mixed>>
     */
    public function getPosts(string $orderBy = 'published_at', string $direction = 'DESC', bool $withAuthor = true): array
    {
        $builder = $this->where('status', 'published')->orderBy($orderBy, $direction);
        if ($withAuthor) {
            $builder->select('blog_posts.*, users.name as author_name')
                ->join('users', 'users.id = blog_posts.author_id', 'left');
        }
        $rows = $builder->findAll();
        return array_map([$this, 'addExcerptForList'], $rows);
    }

    /**
     * Get one published post by slug, with author name.
     *
     * @return array<string, mixed>|null
     */
    public function getBySlug(string $slug): ?array
    {
        $row = $this->select('blog_posts.*, users.name as author_name')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->where('blog_posts.status', 'published')
            ->where('blog_posts.slug', $slug)
            ->first();
        return $row === null ? null : $this->addExcerptAndCategories($row);
    }

    /**
     * Get published posts in a category (by category slug).
     * Uses blog_post_category and blog_categories.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getByCategory(string $categorySlug, string $orderBy = 'published_at', string $direction = 'DESC'): array
    {
        $rows = $this->select('blog_posts.*, users.name as author_name')
            ->join('users', 'users.id = blog_posts.author_id', 'left')
            ->join('blog_post_category', 'blog_post_category.post_id = blog_posts.id')
            ->join('blog_categories', 'blog_categories.id = blog_post_category.category_id')
            ->where('blog_posts.status', 'published')
            ->where('blog_categories.slug', $categorySlug)
            ->orderBy('blog_posts.' . $orderBy, $direction)
            ->findAll();
        return array_map([$this, 'addExcerptForList'], $rows);
    }

    /**
     * Add excerpt for list/card display.
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function addExcerptForList(array $row): array
    {
        if (isset($row['content']) && is_string($row['content'])) {
            $row['excerpt'] = mb_substr(strip_tags($row['content']), 0, 160);
            if (mb_strlen($row['content']) > 160) {
                $row['excerpt'] .= '…';
            }
        } else {
            $row['excerpt'] = $row['excerpt'] ?? '';
        }
        $row['author'] = $row['author_name'] ?? '';
        return $row;
    }

    /**
     * Add excerpt (first ~160 chars of content) and category names for the view.
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function addExcerptAndCategories(array $row): array
    {
        if (isset($row['content']) && is_string($row['content'])) {
            $row['excerpt'] = mb_substr(strip_tags($row['content']), 0, 160);
            if (mb_strlen($row['content']) > 160) {
                $row['excerpt'] .= '…';
            }
        } else {
            $row['excerpt'] = '';
        }
        $postId = $row['id'] ?? null;
        if ($postId) {
            $db     = $this->db;
            $result = $db->table('blog_post_category')
                ->select('blog_categories.name, blog_categories.slug')
                ->join('blog_categories', 'blog_categories.id = blog_post_category.category_id')
                ->where('blog_post_category.post_id', $postId)
                ->get()
                ->getResultArray();
            $row['categories']     = array_column($result, 'name');
            $row['category_slugs'] = array_column($result, 'slug');
            $row['category']      = $row['categories'][0] ?? 'Uncategorized';
            $row['category_slug']  = $row['category_slugs'][0] ?? 'uncategorized';
        } else {
            $row['categories']     = [];
            $row['category_slugs'] = [];
            $row['category']       = 'Uncategorized';
            $row['category_slug']  = 'uncategorized';
        }
        return $row;
    }
}
