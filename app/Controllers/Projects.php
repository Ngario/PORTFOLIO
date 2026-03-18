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
 * Uses ProjectModel (`projects` table). All content should come from the database (no placeholders).
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
            $projects = [];
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

        // No placeholders: if DB isn't available, behave as 404 (content is DB-backed).
        if (! $dbOk || $project === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'   => $project['title'] . ' - Projects',
            'project' => $project,
        ];

        return view('projects/view', $data);
    }

    /**
     * Load all projects from the database (ProjectModel).
     * Featured projects are listed first, then by id DESC.
     *
     * @return array<int, array<string, mixed>>|null
     */
    private function getProjectsFromDb(): ?array
    {
        try {
            $model = model(ProjectModel::class);
            return $model->getProjectsFeaturedFirst();
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

}
