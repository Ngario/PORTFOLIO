<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds installation_instructions (TEXT) to downloads table.
 * Used for software: step-by-step install notes. Optional for other categories.
 * Run: php spark migrate
 */
class AddInstallationInstructionsToDownloads extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('downloads')) {
            return;
        }
        if ($this->db->fieldExists('installation_instructions', 'downloads')) {
            return;
        }
        $this->forge->addColumn('downloads', [
            'installation_instructions' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'description',
            ],
        ]);
    }

    public function down(): void
    {
        if ($this->db->tableExists('downloads') && $this->db->fieldExists('installation_instructions', 'downloads')) {
            $this->forge->dropColumn('downloads', 'installation_instructions');
        }
    }
}
