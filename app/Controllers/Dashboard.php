<?php

namespace App\Controllers;

/**
 * Public user Dashboard (member area)
 *
 * All routes under /dashboard are protected by the 'auth' filter,
 * so only logged-in members can access. Session keys: user_id, user_name, user_email.
 *
 *   GET /dashboard           → index
 *   GET /dashboard/profile  → profile (edit profile page)
 *   POST /dashboard/profile/update
 *   GET /dashboard/my-downloads
 *   GET /dashboard/my-orders
 *   GET /dashboard/settings
 */
class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboard/index', [
            'title' => 'Dashboard',
        ]);
    }

    public function profile()
    {
        return view('dashboard/profile', [
            'title' => 'My Profile',
        ]);
    }

    public function updateProfile()
    {
        // Placeholder: add form handling and user_profiles update when you're ready
        return redirect()->to(base_url('dashboard/profile'))->with('success', 'Profile updated.');
    }

    public function myDownloads()
    {
        return view('dashboard/my-downloads', [
            'title' => 'My Downloads',
        ]);
    }

    public function myOrders()
    {
        return view('dashboard/my-orders', [
            'title' => 'My Orders',
        ]);
    }

    public function settings()
    {
        return view('dashboard/settings', [
            'title' => 'Settings',
        ]);
    }
}
