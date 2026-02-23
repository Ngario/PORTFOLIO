<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Creates the `faqs` table for storing questions and answers.
 * Used by: FAQs page, search page, and chatbot.
 * Run: php spark migrate
 */
class CreateFaqsTable extends Migration
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
            'question' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'answer' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Lower = first on FAQs page',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('sort_order');
        $this->forge->createTable('faqs');
    }

    public function down(): void
    {
        $this->forge->dropTable('faqs');
    }
}
