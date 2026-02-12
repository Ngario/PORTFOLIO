<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProjectModel;

/**
 * Seeds the projects table with sample data.
 * Run: php spark db:seed ProjectSeeder
 *
 * This inserts the same three projects that were previously
 * hard-coded in the controller, so the site shows real DB data.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $model = model(ProjectModel::class);

        $projects = [
            [
                'title'        => 'E-Commerce Platform',
                'slug'         => 'ecommerce-platform',
                'excerpt'      => 'Full-featured online store with cart, checkout, and admin panel.',
                'description'  => 'A custom e-commerce solution built with CodeIgniter 4 and MySQL. Features include product catalog, shopping cart, checkout with M-Pesa integration, order management, and a secure admin dashboard for inventory and orders.',
                'image'        => 'projects/portimage.jpg',
                'tech_stack'   => json_encode(['PHP', 'CodeIgniter 4', 'MySQL', 'Bootstrap', 'JavaScript']),
                'link'         => '#',
                'completed_at' => '2024',
            ],
            [
                'title'        => 'AI-Powered Dashboard',
                'slug'         => 'ai-dashboard',
                'excerpt'      => 'Analytics and insights with smart recommendations.',
                'description'  => 'Dashboard that aggregates data from multiple sources and uses simple AI-style rules to surface insights and recommendations. Built with a modern UI and real-time updates.',
                'image'        => 'projects/Ai.jpg',
                'tech_stack'   => json_encode(['PHP', 'JavaScript', 'Chart.js', 'REST API']),
                'link'         => '#',
                'completed_at' => '2024',
            ],
            [
                'title'        => 'Portfolio Website',
                'slug'         => 'portfolio-website',
                'excerpt'      => 'This very site: projects, blog, and downloads in one place.',
                'description'  => 'A single portfolio and small-business site with homepage sections, project showcase, services, blog, downloads, and contact. Designed to be responsive and easy to maintain.',
                'image'        => 'projects/portimage.jpg',
                'tech_stack'   => json_encode(['PHP', 'CodeIgniter 4', 'MySQL', 'Bootstrap']),
                'link'         => '#', // Homepage; replace with full URL in DB or via admin later
                'completed_at' => '2025',
            ],
        ];

        foreach ($projects as $row) {
            $model->insert($row);
        }
    }
}
