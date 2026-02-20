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
        'category_id', 'title', 'description', 'image', 'file_path', 'file_size',
        'is_paid', 'price', 'is_active',
    ];

    /** Only active downloads; returns [] on query failure (e.g. table missing). */
    public function getActive(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        try {
            return $this->where('is_active', 1)->orderBy($orderBy, $direction)->findAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** Returns [] on query failure. */
    public function getByCategory(int $categoryId): array
    {
        try {
            return $this->where('category_id', $categoryId)->where('is_active', 1)->findAll();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
