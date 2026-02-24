<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CvStorageModel – single-row table `cv_storage` for the site CV (PDF).
 * Columns: id, filename, content (LONGBLOB), mime_type, updated_at.
 * Admin uploads via form; public /download-cv serves from this table.
 */
class CvStorageModel extends Model
{
    protected $table         = 'cv_storage';
    protected $primaryKey   = 'id';
    protected $useAutoIncrement = true;
    protected $returnType   = 'array';
    protected $useTimestamps = false;
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'filename',
        'content',
        'mime_type',
        'updated_at',
    ];

    /** Default row id for the single CV document */
    public const CV_ROW_ID = 1;

    /**
     * Get the current CV row (id=1). Returns null if no CV has been uploaded yet.
     *
     * @return array{id: int, filename: string, content: string, mime_type: string, updated_at: string}|null
     */
    public function getCv(): ?array
    {
        $row = $this->find(self::CV_ROW_ID);
        return $row ?: null;
    }

    /**
     * Get CV metadata only (no blob) for admin display.
     *
     * @return array{filename: string, updated_at: string}|null
     */
    public function getCvInfo(): ?array
    {
        $row = $this->select('filename, updated_at')->find(self::CV_ROW_ID);
        if (! $row) {
            return null;
        }
        return [
            'filename'   => $row['filename'] ?? '',
            'updated_at' => $row['updated_at'] ?? null,
        ];
    }

    /**
     * Save or replace the CV. If row id=1 does not exist, insert; else update.
     *
     * @param string $filename Original filename (e.g. QuickCV.pdf)
     * @param string $content  Binary PDF content
     * @param string $mimeType Default application/pdf
     */
    public function saveCv(string $filename, string $content, string $mimeType = 'application/pdf'): bool
    {
        $existing = $this->find(self::CV_ROW_ID);
        $data = [
            'filename'   => $filename,
            'content'    => $content,
            'mime_type'  => $mimeType,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            return (bool) $this->update(self::CV_ROW_ID, $data);
        }

        return (bool) $this->insert($data);
    }
}
