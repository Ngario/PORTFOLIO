<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter (member / site login)
 *
 * Used for: dashboard, download/file, and any route that should require
 * a logged-in member (not admin). If the user is not logged in, we redirect
 * to the public login page and remember where they wanted to go.
 *
 * Session keys we expect when "logged in": user_id (and optionally user_name, user_email).
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('user_id');
        if (empty($userId)) {
            session()->setFlashdata('redirect_after_login', current_url());
            return redirect()->to(base_url('login'));
        }
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
