<?php

namespace App\Controllers;

use App\Models\ServiceModel;

/**
 * Services Controller
 *
 * Handles:
 *   GET /services       → index()  = list all services
 *   GET /services/123   → view(123) = single service by ID
 *
 * Uses ServiceModel when the `services` table exists.
 * Falls back to placeholder data otherwise so the site still works.
 */
class Services extends BaseController
{
    public function index()
    {
        $services = $this->getServicesFromDb();
        if ($services === null) {
            $services = $this->getPlaceholderServices();
        }

        $data = [
            'title'       => 'Services',
            'description' => 'What I offer: development, design, and consulting',
            'services'    => $services,
        ];

        return view('services/index', $data);
    }

    /**
     * Single service by ID
     * URL: /services/123
     */
    public function view(int $id)
    {
        $service = null;
        $dbOk    = false;

        try {
            $model  = model(ServiceModel::class);
            $dbOk   = true;
            $service = $model->getService($id);
        } catch (\Throwable) {
            $dbOk = false;
        }

        // If DB is working but record doesn't exist, it's a real 404.
        if ($dbOk && $service === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // If DB isn't available, fall back to placeholder data.
        if (!$dbOk) {
            $services = $this->getPlaceholderServices();
            foreach ($services as $s) {
                if ((int) $s['id'] === $id) {
                    $service = $s;
                    break;
                }
            }
            if ($service === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $data = [
            'title'   => $service['title'] . ' - Services',
            'service' => $service,
        ];

        return view('services/view', $data);
    }

    /**
     * Load all services from the database (ServiceModel).
     * Returns null only when the DB/table is not available.
     *
     * @return array<int, array<string, mixed>>|null
     */
    private function getServicesFromDb(): ?array
    {
        try {
            $model = model(ServiceModel::class);
            return $model->getServices();
        } catch (\Throwable) {
            return null;
        }
    }

    private function getPlaceholderServices(): array
    {
        return [
            [
                'id'          => 1,
                'title'       => 'Web Development',
                'slug'        => 'web-development',
                'excerpt'     => 'Custom websites and web applications built to your needs.',
                'description' => 'From simple brochure sites to full web applications: responsive design, clean code, and maintainable architecture. I use PHP, CodeIgniter, MySQL, and modern front-end tools to deliver on time.',
                'icon'        => 'fas fa-laptop-code',
                'price_from'  => 'Contact for quote',
            ],
            [
                'id'          => 2,
                'title'       => 'UI/UX & Graphics Design',
                'slug'        => 'ui-ux-graphics',
                'excerpt'     => 'Interfaces and visuals that look great and are easy to use.',
                'description' => 'User interface design, logos, social graphics, and marketing visuals. I focus on clarity, consistency, and a professional look that matches your brand.',
                'icon'        => 'fas fa-palette',
                'price_from'  => 'Contact for quote',
            ],
            [
                'id'          => 3,
                'title'       => 'API & Backend Development',
                'slug'        => 'api-backend',
                'excerpt'     => 'REST APIs and server logic for apps and integrations.',
                'description' => 'Design and build APIs, database structure, and business logic. Secure, documented, and ready to integrate with mobile apps, front-ends, or third-party services.',
                'icon'        => 'fas fa-server',
                'price_from'  => 'Contact for quote',
            ],
            [
                'id'          => 4,
                'title'       => 'Consulting & Training',
                'slug'        => 'consulting-training',
                'excerpt'     => 'Guidance and training on web tech and best practices.',
                'description' => 'One-on-one or team sessions on PHP, CodeIgniter, database design, or deployment. I can also review your codebase and suggest improvements.',
                'icon'        => 'fas fa-chalkboard-teacher',
                'price_from'  => 'Hourly or project-based',
            ],
        ];
    }
}
