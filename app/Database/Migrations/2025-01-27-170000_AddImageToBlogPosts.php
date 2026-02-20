<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds `image` column to `blog_posts` for featured/placeholder image.
 * Safe to run: only adds if table and column exist check.
 */
class AddImageToBlogPosts extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('blog_posts') && ! $this->db->fieldExists('image', 'blog_posts')) {
            $this->forge->addColumn('blog_posts', [
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
        if ($this->db->tableExists('blog_posts') && $this->db->fieldExists('image', 'blog_posts')) {
            $this->forge->dropColumn('blog_posts', 'image');
        }
    }
}
