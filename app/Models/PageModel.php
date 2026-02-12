<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PageModel
 *
 * Optional model if you want pages (Terms/Privacy/About/etc) to come from the DB.
 * This points to YOUR existing `pages` table (or whatever you named it).
 */
class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Replace with your exact columns from `php spark db:show-tables` / DESCRIBE.
     */
    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'meta_description',
        'is_published',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function getBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }
}

