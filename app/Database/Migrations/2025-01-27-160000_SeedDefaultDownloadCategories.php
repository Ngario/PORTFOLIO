<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Seeds default download categories so /downloads/software, /downloads/books, etc. work.
 * Safe to run multiple times: only inserts if slug does not exist.
 * Run: php spark migrate
 */
class SeedDefaultDownloadCategories extends Migration
{
    private const DEFAULTS = [
        ['name' => 'Software', 'slug' => 'software'],
        ['name' => 'Books', 'slug' => 'books'],
        ['name' => 'Tutorials', 'slug' => 'tutorials'],
        ['name' => 'Videos', 'slug' => 'videos'],
    ];

    public function up(): void
    {
        if (! $this->db->tableExists('download_categories')) {
            return;
        }

        $existing = $this->db->table('download_categories')->select('slug')->get()->getResultArray();
        $existingSlugs = array_column($existing, 'slug');

        foreach (self::DEFAULTS as $row) {
            if (in_array($row['slug'], $existingSlugs, true)) {
                continue;
            }
            $this->db->table('download_categories')->insert([
                'name'      => $row['name'],
                'slug'      => $row['slug'],
                'parent_id' => null,
            ]);
            $existingSlugs[] = $row['slug'];
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists('download_categories')) {
            return;
        }
        $slugs = array_column(self::DEFAULTS, 'slug');
        $this->db->table('download_categories')->whereIn('slug', $slugs)->delete();
    }
}
