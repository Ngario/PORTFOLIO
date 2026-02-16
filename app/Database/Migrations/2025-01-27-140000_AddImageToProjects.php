<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds `image` column to `projects` for card/thumbnail image path.
 * Safe to run on existing tables: only adds column if missing.
 * Run: php spark migrate
 */
class AddImageToProjects extends Migration
{
    public function up(): void
    {
        if (! $this->db->fieldExists('image', 'projects')) {
            $this->forge->addColumn('projects', [
                'image' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'description',
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('image', 'projects')) {
            $this->forge->dropColumn('projects', 'image');
        }
    }
}
