<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel – matches your `users` table.
 *
 * Columns:
 *  - id, name, email, password_hash, role, email_verified_at, status, created_at, updated_at
 *
 * This model is used for Admin login (and can be reused for normal user auth later).
 */
class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'name',
        'email',
        'password_hash',
        'role',
        'email_verified_at',
        'status',
    ];

    /**
     * Find one user by email (case-insensitive).
     * Returns null if not found or on DB error (e.g. missing users table).
     *
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        try {
            return $this->where('LOWER(email)', strtolower($email))->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Find one user by ID. Returns null on DB error so auth flows don't crash.
     *
     * @return array<string, mixed>|null
     */
    public function find($id, $columns = '*'): ?array
    {
        try {
            $row = parent::find($id, $columns);
            return is_array($row) ? $row : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

