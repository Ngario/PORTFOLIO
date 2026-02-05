<?php

namespace App\Controllers;

/**
 * Projects Controller
 *
 * Handles:
 *   GET /projects         → index()  = list all projects
 *   GET /projects/123     → view(123) = single project by ID
 *
 * Later you will load real data from a Project model and the projects table.
 * For now we use placeholder data so the pages render.
 */
class Projects extends BaseController
{
    /**
     * List all projects
     * URL: /projects
     */
    public function index()
    {
        // TODO: Replace with: $projectModel = model('ProjectModel'); $projects = $projectModel->findAll();
        $projects = $this->getPlaceholderProjects();

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
        $projects = $this->getPlaceholderProjects();
        $project  = null;
        foreach ($projects as $p) {
            if ((int) $p['id'] === $id) {
                $project = $p;
                break;
            }
        }

        if ($project === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => $project['title'] . ' - Projects',
            'project' => $project,
        ];

        return view('projects/view', $data);
    }

    /**
     * Placeholder data until you connect a Project model and database.
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
