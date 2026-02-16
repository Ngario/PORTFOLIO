<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DownloadModel – matches your `downloads` table.
 *
 * Columns: id, category_id, title, description, file_path, file_size,
 *          is_paid, price, is_active, created_at
 */
class DownloadModel extends Model
{
    protected $table = 'downloads';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'category_id', 'title', 'description', 'file_path', 'file_size',
        'is_paid', 'price', 'is_active',
    ];

    /** Only active downloads */
    public function getActive(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        return $this->where('is_active', 1)->orderBy($orderBy, $direction)->findAll();
    }

    public function getByCategory(int $categoryId): array
    {
        return $this->where('category_id', $categoryId)->where('is_active', 1)->findAll();
    }
}
