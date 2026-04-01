<?php

namespace App\Controllers;

/**
 * Home Controller
 * 
 * Purpose: Handle homepage requests
 * Route: GET /
 * 
 * MVC Flow:
 * 1. User visits homepage
 * 2. Route matches: $routes->get('/', 'Home::index')
 * 3. This controller's index() method executes
 * 4. Controller fetches data from Models
 * 5. Controller passes data to View
 * 6. View renders HTML and returns to browser
 */
class Home extends BaseController
{
    /**
     * Display the homepage
     * 
     * @return string HTML output
     */
    public function index(): string
    {
        helper('upload_storage');

        // ============================================
        // STEP 1: PREPARE DATA ARRAY
        // ============================================
        // This array will be passed to the view
        // Keys become variables in the view (e.g., $data['projects'] becomes $projects)
        $data = [];
        
        // ============================================
        // STEP 2: FETCH PROJECTS DATA (featured first, then latest)
        // ============================================
        try {
            $projectModel = model(\App\Models\ProjectModel::class);
            $data['projects'] = $projectModel->getFeaturedForHome(6);
        } catch (\Throwable) {
            $data['projects'] = [];
        }
        
        // ============================================
        // STEP 3: FETCH SERVICES DATA
        // ============================================
        // TODO: Uncomment when ServiceModel is created
        /*
        $serviceModel = new \App\Models\ServiceModel();
        
        // Get active services
        $data['services'] = $serviceModel
            ->where('is_active', 1)
            ->orderBy('display_order', 'ASC')
            ->findAll();
        */
        
        $data['services'] = [];
        
        // ============================================
        // STEP 4: FETCH BLOG POSTS DATA (from database only)
        // ============================================
        $data['blogs'] = [];
        try {
            $blogModel = model(\App\Models\BlogPostModel::class);
            $posts = $blogModel->getPosts('published_at', 'DESC', false);
            $posts = array_slice($posts, 0, 3);
            foreach ($posts as $post) {
                $data['blogs'][] = [
                    'title'          => $post['title'] ?? '',
                    'slug'           => $post['slug'] ?? '',
                    'content'        => $post['content'] ?? '',
                    'excerpt'        => $post['excerpt'] ?? '',
                    'published_at'   => $post['published_at'] ?? $post['created_at'] ?? null,
                    'created_at'     => $post['created_at'] ?? null,
                    'featured_image' => ! empty($post['image'])
                        ? upload_storage_public_url((string) $post['image'], 'images/placeholder-download.svg')
                        : base_url('images/placeholder-download.svg'),
                    'category_name'  => $post['category_name'] ?? '',
                ];
            }
        } catch (\Throwable $e) {
            $data['blogs'] = [];
        }
        
        // ============================================
        // STEP 5: FETCH ABOUT PAGE CONTENT
        // ============================================
        // TODO: Uncomment when PageModel is created
        /*
        $pageModel = new \App\Models\PageModel();
        
        // Get About page content for preview section
        $aboutPage = $pageModel
            ->where('slug', 'about')
            ->where('is_active', 1)
            ->first();
        
        // Only include first 300 characters for preview
        if ($aboutPage) {
            $aboutPage['content'] = substr(strip_tags($aboutPage['content']), 0, 300) . '...';
        }
        
        $data['aboutPage'] = $aboutPage;
        */
        
        $data['aboutPage'] = null;
        
        // ============================================
        // STEP 6: RETURN VIEW WITH DATA
        // ============================================
        // view() is a CodeIgniter helper function
        // First parameter: view file path (relative to app/Views/)
        // Second parameter: data array to pass to view
        //
        // How it works:
        // - CodeIgniter looks for: app/Views/home/index.php
        // - Extracts $data array into individual variables
        // - $data['projects'] becomes $projects in the view
        // - View can access all these variables
        
        return view('home/index', $data);
        
        // NOTE: If you want to return JSON instead (for API):
        // return $this->response->setJSON($data);
    }
}

