<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Admin Auth Controller
 *
 * Why this file exists:
 * - Handles GET /admin/login (shows form)
 * - Handles POST /admin/login (checks credentials against `users` table)
 * - Handles GET /admin/logout (clears admin session)
 *
 * What it uses:
 * - UserModel (`app/Models/UserModel.php`) to read users from DB
 * - PHP password_verify() to check the password against `password_hash`
 * - CodeIgniter session to remember you are logged in
 */
class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, go to dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to(base_url('admin'));
        }

        return view('admin/auth/login', [
            'title' => 'Admin Login',
        ]);
    }

    public function attemptLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Email and password are required.');
        }

        $users = model(UserModel::class);
        $user  = $users->findByEmail($email);

        // 1) Email not found
        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'Invalid login details.');
        }

        // 2) Only allow admin roles into /admin
        $role = (string) ($user['role'] ?? '');
        if (! in_array($role, ['admin', 'superadmin'], true)) {
            return redirect()->back()->withInput()->with('error', 'You do not have admin access.');
        }

        // 3) Optional: block disabled users
        $status = $user['status'] ?? 'active';
        if (is_string($status) && strtolower($status) !== 'active') {
            return redirect()->back()->withInput()->with('error', 'This account is not active.');
        }

        // 4) Verify password (plain password vs stored password_hash)
        $hash = (string) ($user['password_hash'] ?? '');
        if ($hash === '' || ! password_verify($password, $hash)) {
            return redirect()->back()->withInput()->with('error', 'Invalid login details.');
        }

        // 5) Mark admin session as logged in
        session()->set([
            'admin_logged_in' => true,
            'admin_user_id'   => $user['id'],
            'admin_name'      => $user['name'] ?? 'Admin',
            'admin_role'      => $role,
        ]);

        $redirect = session()->getFlashdata('admin_redirect');
        return redirect()->to($redirect ?: base_url('admin'));
    }

    public function logout()
    {
        session()->remove([
            'admin_logged_in',
            'admin_user_id',
            'admin_name',
            'admin_role',
        ]);

        return redirect()->to(base_url('admin/login'));
    }
}

