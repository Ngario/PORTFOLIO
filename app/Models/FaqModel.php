<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * FaqModel – matches the `faqs` table.
 * Columns: id, question, answer, sort_order, created_at, updated_at
 * Used by: FAQs page, search page, chatbot, and admin FAQs CRUD.
 */
class FaqModel extends Model
{
    protected $table         = 'faqs';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'question',
        'answer',
        'sort_order',
    ];

    /**
     * Get all FAQs ordered by sort_order (and id as tiebreaker).
     * Returns array of ['id', 'question', 'answer', 'sort_order', ...] for views.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllOrdered(): array
    {
        $rows = $this->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->findAll();
        return array_map(function ($row) {
            return [
                'id'         => (int) ($row['id'] ?? 0),
                'question'   => $row['question'] ?? '',
                'answer'     => $row['answer'] ?? '',
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ];
        }, $rows);
    }

    /**
     * Search FAQs by keyword (question or answer, case-insensitive).
     *
     * @return array<int, array<string, mixed>>
     */
    public function search(string $keyword): array
    {
        if ($keyword === '') {
            return $this->getAllOrdered();
        }
        $pattern = '%' . $keyword . '%';
        $builder = $this->builder();
        $builder->groupStart()
            ->like('question', $pattern)
            ->orLike('answer', $pattern)
            ->groupEnd()
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC');
        $rows = $builder->get()->getResultArray();
        return array_map(function ($row) {
            return [
                'id'         => (int) ($row['id'] ?? 0),
                'question'   => $row['question'] ?? '',
                'answer'     => $row['answer'] ?? '',
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ];
        }, $rows);
    }

    /**
     * Find best matching FAQ for chatbot: first FAQ whose question or answer contains the message (case-insensitive).
     * If none match, returns null (caller can show a fallback message).
     *
     * @return array{question: string, answer: string}|null
     */
    public function findBestMatchForMessage(string $message): ?array
    {
        $message = trim($message);
        if ($message === '') {
            return null;
        }
        $all = $this->getAllOrdered();
        $msgLower = mb_strtolower($message);
        foreach ($all as $faq) {
            $q = mb_strtolower($faq['question'] ?? '');
            $a = mb_strtolower($faq['answer'] ?? '');
            if (str_contains($q, $msgLower) || str_contains($a, $msgLower)
                || str_contains($msgLower, $q) || str_contains($msgLower, $a)) {
                return ['question' => $faq['question'], 'answer' => $faq['answer']];
            }
        }
        // Word-based match: any FAQ that contains any word from the message
        $words = array_filter(preg_split('/\s+/', $msgLower), static fn($w) => strlen($w) > 2);
        foreach ($all as $faq) {
            $q = mb_strtolower($faq['question'] ?? '');
            $a = mb_strtolower($faq['answer'] ?? '');
            $text = $q . ' ' . $a;
            foreach ($words as $word) {
                if (str_contains($text, $word)) {
                    return ['question' => $faq['question'], 'answer' => $faq['answer']];
                }
            }
        }
        return null;
    }
}
