<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DownloadCategoryModel – matches your `download_categories` table.
 *
 * Columns: id, name, slug, parent_id
 */
class DownloadCategoryModel extends Model
{
    protected $table = 'download_categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'name',
        'slug',
        'parent_id',
    ];
}

