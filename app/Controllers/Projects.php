<?php

namespace App\Controllers;

use App\Models\ProjectModel;

/**
 * Projects Controller
 *
 * Handles:
 *   GET /projects         → index()  = list all projects
 *   GET /projects/123     → view(123) = single project by ID
 *
 * Uses ProjectModel when the `projects` table exists (after "php spark migrate").
 * Falls back to placeholder data otherwise so the site still works.
 */
class Projects extends BaseController
{
    /**
     * List all projects
     * URL: /projects
     */
    public function index()
    {
        $projects = $this->getProjectsFromDb();
        if ($projects === null) {
            $projects = $this->getPlaceholderProjects();
        }

        $data = [
            'title'       => 'My Projects',
            'description' => 'Web apps, designs, and development work',
            'projects'    => $projects,
        ];

        return view('projects/index', $data);
    }

    /**
     * Show a single project by ID
     * URL: /projects/123
     * @param int $id Project ID from the URL
     */
    public function view(int $id)
    {
        $project = null;
        $dbOk    = false;

        try {
            $model  = model(ProjectModel::class);
            $dbOk   = true;
            $project = $model->getProject($id);
        } catch (\Throwable) {
            $dbOk = false;
        }

        // If DB is working but record doesn't exist, it's a real 404.
        if ($dbOk && $project === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // If DB isn't available, fall back to placeholder data.
        if (!$dbOk) {
            $projects = $this->getPlaceholderProjects();
            foreach ($projects as $p) {
                if ((int) $p['id'] === $id) {
                    $project = $p;
                    break;
                }
            }
            if ($project === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $data = [
            'title'   => $project['title'] . ' - Projects',
            'project' => $project,
        ];

        return view('projects/view', $data);
    }

    /**
     * Load all projects from the database (ProjectModel).
     * Returns null if the table doesn't exist or an error occurs.
     *
     * @return array<int, array<string, mixed>>|null
     */
    private function getProjectsFromDb(): ?array
    {
        try {
            $model = model(ProjectModel::class);
            return $model->getProjects();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Load one project by ID from the database.
     * Returns null if not found or table doesn't exist.
     *
     * @return array<string, mixed>|null
     */
    private function getProjectFromDb(int $id): ?array
    {
        try {
            $model = model(ProjectModel::class);
            return $model->getProject($id);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Placeholder data when the database table doesn't exist yet.
     * @return array<int, array<string, mixed>>
     */
    private function getPlaceholderProjects(): array
    {
        return [
            [
                'id'          => 1,
                'title'       => 'E-Commerce Platform',
                'slug'        => 'ecommerce-platform',
                'excerpt'     => 'Full-featured online store with cart, checkout, and admin panel.',
                'description' => 'A custom e-commerce solution built with CodeIgniter 4 and MySQL. Features include product catalog, shopping cart, checkout with M-Pesa integration, order management, and a secure admin dashboard for inventory and orders.',
                'image'       => 'projects/portimage.jpg',
                'tech_stack'  => ['PHP', 'CodeIgniter 4', 'MySQL', 'Bootstrap', 'JavaScript'],
                'link'        => '#',
                'completed_at'=> '2024',
            ],
            [
                'id'          => 2,
                'title'       => 'AI-Powered Dashboard',
                'slug'        => 'ai-dashboard',
                'excerpt'     => 'Analytics and insights with smart recommendations.',
                'description' => 'Dashboard that aggregates data from multiple sources and uses simple AI-style rules to surface insights and recommendations. Built with a modern UI and real-time updates.',
                'image'       => 'projects/Ai.jpg',
                'tech_stack'  => ['PHP', 'JavaScript', 'Chart.js', 'REST API'],
                'link'        => '#',
                'completed_at'=> '2024',
            ],
            [
                'id'          => 3,
                'title'       => 'Portfolio Website',
                'slug'        => 'portfolio-website',
                'excerpt'     => 'This very site: projects, blog, and downloads in one place.',
                'description' => 'A single portfolio and small-business site with homepage sections, project showcase, services, blog, downloads, and contact. Designed to be responsive and easy to maintain.',
                'image'       => 'projects/portimage.jpg',
                'tech_stack'  => ['PHP', 'CodeIgniter 4', 'MySQL', 'Bootstrap'],
                'link'        => base_url(),
                'completed_at'=> '2025',
            ],
        ];
    }
}
