<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ServiceModel – matches your `services` table.
 * Columns: id, name, description, price, is_active, created_at
 */
class ServiceModel extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    /** Your table has created_at but no updated_at. */
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'name',
        'description',
        'price',
        'is_active',
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getServices(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        return $this->where('is_active', 1)->orderBy($orderBy, $direction)->findAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getService(int $id): ?array
    {
        return $this->find($id);
    }
}

