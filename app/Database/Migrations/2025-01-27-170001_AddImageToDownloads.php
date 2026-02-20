<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds `image` column to `downloads` for cover/placeholder image.
 * Safe to run: only adds if table exists and column missing.
 */
class AddImageToDownloads extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('downloads') && ! $this->db->fieldExists('image', 'downloads')) {
            $this->forge->addColumn('downloads', [
                'image' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->tableExists('downloads') && $this->db->fieldExists('image', 'downloads')) {
            $this->forge->dropColumn('downloads', 'image');
        }
    }
}
