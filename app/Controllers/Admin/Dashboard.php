<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Admin Dashboard (home page)
 *
 * URL: GET /admin
 * Protected by the `adminauth` filter (see `app/Filters/AdminAuth.php`).
 */
class Dashboard extends BaseController
{
    public function index()
    {
        return view('admin/dashboard', [
            'title' => 'Admin Dashboard',
        ]);
    }
}

