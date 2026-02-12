<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ServiceModel
 *
 * This model is meant to point to YOUR existing services table.
 *
 * After you run: php spark db:show-tables
 * 1) Set $table to your real table name (maybe "services")
 * 2) Update $allowedFields so it matches your real column names
 */
class ServiceModel extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    /**
     * Keep this OFF by default because your existing table might not have
     * created_at/updated_at columns. Turn it on only if your table has them.
     */
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * IMPORTANT: Replace these with the exact columns in your table.
     * These match what the current services pages expect.
     */
    protected $allowedFields = [
        'title',
        'slug',
        'excerpt',
        'description',
        'icon',
        'price_from',
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getServices(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        return $this->orderBy($orderBy, $direction)->findAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getService(int $id): ?array
    {
        return $this->find($id);
    }
}

