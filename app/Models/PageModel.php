<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PageModel – matches your `pages` table.
 * Columns: id, slug, title, content, published, created_at, updated_at
 */
class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'slug',
        'title',
        'content',
        'published',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }
}

