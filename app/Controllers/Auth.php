<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Public Auth Controller (member login / register)
 *
 * Handles:
 *   GET  /login              → login form
 *   POST /login              → attempt login (sets session: user_id, user_name, user_email)
 *   GET  /register          → registration form
 *   POST /register           → create user in DB, then log them in
 *   GET  /logout             → clear session, redirect home
 *
 * Uses the same `users` table as admin. New registrations get role = 'user'
 * and status = 'active'. Admin users (role admin/superadmin) can still use
 * /admin/login for the admin panel; they can also use /login to be "members"
 * if you want (same row, different session keys).
 */
class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/login', ['title' => 'Login']);
    }

    public function attemptLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Email and password are required.');
        }

        $userModel = model(UserModel::class);
        $user      = $userModel->findByEmail($email);

        if ($user === null) {
            return redirect()->back()->withInput()->with('error', 'No account found with this email.');
        }

        $status = $user['status'] ?? 'active';
        if (is_string($status) && strtolower($status) !== 'active') {
            return redirect()->back()->withInput()->with('error', 'This account is not active.');
        }

        $hash = (string) ($user['password_hash'] ?? '');
        if ($hash === '' || ! password_verify($password, $hash)) {
            return redirect()->back()->withInput()->with('error', 'Password is incorrect.');
        }

        $this->setMemberSession($user);
        $redirect = session()->getFlashdata('redirect_after_login');
        return redirect()->to($redirect ?: base_url('dashboard'));
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/register', ['title' => 'Register']);
    }

    public function attemptRegister()
    {
        $name     = trim((string) $this->request->getPost('name'));
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $confirm  = (string) $this->request->getPost('password_confirm');

        if ($name === '' || $email === '') {
            return redirect()->back()->withInput()->with('error', 'Name and email are required.');
        }
        if (strlen($password) < 8) {
            return redirect()->back()->withInput()->with('error', 'Password must be at least 8 characters.');
        }
        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }

        $userModel = model(UserModel::class);
        if ($userModel->findByEmail($email) !== null) {
            return redirect()->back()->withInput()->with('error', 'An account with this email already exists.');
        }

        $userId = $userModel->insert([
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'user',
            'status'        => 'active',
            'email_verified_at' => null,
        ]);

        if (! $userId) {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        $user = $userModel->find($userId);
        $this->setMemberSession($user);
        return redirect()->to(base_url('dashboard'))->with('success', 'Welcome! You are now logged in.');
    }

    public function logout()
    {
        session()->remove(['user_id', 'user_name', 'user_email', 'user_photo']);
        return redirect()->to(base_url('/'))->with('success', 'You have been logged out.');
    }

    /**
     * Set session keys so the header and protected routes see the user as logged in.
     *
     * @param array<string, mixed> $user Row from users table
     */
    private function setMemberSession(array $user): void
    {
        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'] ?? 'User',
            'user_email' => $user['email'] ?? '',
        ]);
        // user_photo can be set later from user_profiles if you add profile uploads
    }
}
