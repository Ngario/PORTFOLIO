<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Seeds default FAQs so the site has content after migration.
 * Safe to run: only inserts if the table is empty (no duplicate seeds).
 * Run: php spark migrate
 */
class SeedDefaultFaqs extends Migration
{
    private const DEFAULTS = [
        [
            'question' => 'How do I download a resource?',
            'answer'   => 'Create an account or log in, then go to Downloads. Click the resource you want and use the download button. Some items are free; others may require purchase or membership.',
            'sort_order' => 1,
        ],
        [
            'question' => 'Do I need to create an account?',
            'answer'   => 'For free public downloads you can browse without an account. To download files and access member-only content, you need to register. Registration is free.',
            'sort_order' => 2,
        ],
        [
            'question' => 'How can I contact you for a project?',
            'answer'   => 'Use the Contact page to send a message. You can also reach me via the email or phone listed in the footer. I typically reply within 1–2 business days.',
            'sort_order' => 3,
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer'   => 'For paid downloads and services, payment options are shown at checkout. This site may support M-Pesa and other methods depending on configuration.',
            'sort_order' => 4,
        ],
        [
            'question' => 'Can I use your code or templates in my projects?',
            'answer'   => 'It depends on the license of each resource. Check the download or project page for licensing terms. Many resources are for personal or commercial use with attribution.',
            'sort_order' => 5,
        ],
        [
            'question' => 'Where can I see your portfolio projects?',
            'answer'   => 'Go to the Projects page from the main menu or homepage. Each project has a description, technologies used, and links to live demos or repositories where applicable.',
            'sort_order' => 6,
        ],
    ];

    public function up(): void
    {
        if (! $this->db->tableExists('faqs')) {
            return;
        }

        $count = $this->db->table('faqs')->countAll();
        if ($count > 0) {
            return; // Already has data; don't duplicate
        }

        $now = date('Y-m-d H:i:s');
        foreach (self::DEFAULTS as $row) {
            $this->db->table('faqs')->insert([
                'question'   => $row['question'],
                'answer'     => $row['answer'],
                'sort_order' => $row['sort_order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists('faqs')) {
            return;
        }
        $questions = array_column(self::DEFAULTS, 'question');
        $this->db->table('faqs')->whereIn('question', $questions)->delete();
    }
}
