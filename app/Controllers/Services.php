<?php

namespace App\Controllers;

/**
 * Services Controller
 *
 * Handles:
 *   GET /services       → index()  = list all services
 *   GET /services/123   → view(123) = single service by ID
 *
 * Later: load from Service model and services table.
 */
class Services extends BaseController
{
    public function index()
    {
        $services = $this->getPlaceholderServices();

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
        $services = $this->getPlaceholderServices();
        $service  = null;
        foreach ($services as $s) {
            if ((int) $s['id'] === $id) {
                $service = $s;
                break;
            }
        }

        if ($service === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => $service['title'] . ' - Services',
            'service' => $service,
        ];

        return view('services/view', $data);
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
