<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminAuth filter
 *
 * Protects /admin routes.
 * - If not logged in, redirect to /admin/login
 * - If logged in but not admin role, show 403
 */
class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        $loggedIn = (bool) $session->get('admin_logged_in');
        if (! $loggedIn) {
            // Remember where user was going (optional)
            $session->setFlashdata('admin_redirect', current_url());
            return redirect()->to(base_url('admin/login'));
        }

        $role = (string) ($session->get('admin_role') ?? '');
        if (! in_array($role, ['admin', 'superadmin'], true)) {
            return service('response')->setStatusCode(403)->setBody('Forbidden');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}

