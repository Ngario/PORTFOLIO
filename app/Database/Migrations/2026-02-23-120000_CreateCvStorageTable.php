<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Creates the `cv_storage` table to store the site CV (PDF) in the database.
 * One row (id=1): admin uploads/replaces the file; public /download-cv serves it.
 * Run: php spark migrate
 */
class CreateCvStorageTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'filename' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Original filename e.g. QuickCV.pdf',
            ],
            'content' => [
                'type' => 'LONGBLOB',
                'null' => true,
                'comment' => 'PDF binary content',
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => 'application/pdf',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cv_storage');
    }

    public function down(): void
    {
        $this->forge->dropTable('cv_storage');
    }
}
