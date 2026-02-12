<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Project Model
 *
 * Handles CRUD for the `projects` table.
 * - Create: $this->insert($data)
 * - Read:   $this->find($id), $this->findAll(), $this->where(...)->first()
 * - Update: $this->update($id, $data)
 * - Delete: $this->delete($id)
 *
 * Table must exist in your database (created by migrations OR already created by you).
 * Tip: run "php spark db:show-tables" to see your table + columns, then ensure
 * $table and $allowedFields match your real schema.
 */
class ProjectModel extends Model
{
    /** @var string Table name */
    protected $table = 'projects';

    /** @var string Primary key column */
    protected $primaryKey = 'id';

    /** @var bool Use auto-increment for primary key */
    protected $useAutoIncrement = true;

    /** @var string Return type: array or object */
    protected $returnType = 'array';

    /**
     * Your table has created_at but no updated_at, so we don't auto-set timestamps.
     */
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Must match your `projects` table columns (id and created_at handled separately if needed).
     */
    protected $allowedFields = [
        'title',
        'slug',
        'description',
        'tech_stack',
        'demo_url',
        'github_url',
        'featured',
    ];

    /**
     * Get a single project by ID.
     * Decodes tech_stack JSON into an array for the view.
     */
    public function getProject(int $id): ?array
    {
        $row = $this->find($id);
        if ($row === null) {
            return null;
        }
        return $this->decodeTechStack($row);
    }

    /**
     * Get all projects, optionally ordered.
     * Decodes tech_stack for each row.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getProjects(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $rows = $this->orderBy($orderBy, $direction)->findAll();
        return array_map([$this, 'decodeTechStack'], $rows);
    }

    /**
     * Get project by slug (for pretty URLs).
     */
    public function getBySlug(string $slug): ?array
    {
        $row = $this->where('slug', $slug)->first();
        if ($row === null) {
            return null;
        }
        return $this->decodeTechStack($row);
    }

    /**
     * Decode tech_stack JSON string to array for use in views.
     *
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function decodeTechStack(array $row): array
    {
        if (isset($row['tech_stack']) && is_string($row['tech_stack'])) {
            $decoded = json_decode($row['tech_stack'], true);
            $row['tech_stack'] = is_array($decoded) ? $decoded : [];
        }
        return $row;
    }

    /**
     * Save a project (insert or update).
     * Encodes tech_stack if it's an array.
     *
     * @param array<string, mixed> $data
     */
    public function saveProject(array $data, ?int $id = null): bool
    {
        if (isset($data['tech_stack']) && is_array($data['tech_stack'])) {
            $data['tech_stack'] = json_encode($data['tech_stack']);
        }
        if ($id !== null) {
            return (bool) $this->update($id, $data);
        }
        return $this->insert($data) !== false;
    }
}
