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
        // ============================================
        // STEP 1: PREPARE DATA ARRAY
        // ============================================
        // This array will be passed to the view
        // Keys become variables in the view (e.g., $data['projects'] becomes $projects)
        $data = [];
        
        // ============================================
        // STEP 2: FETCH PROJECTS DATA
        // ============================================
        // TODO: Uncomment when ProjectModel is created
        /*
        $projectModel = new \App\Models\ProjectModel();
        
        // Get 4 featured or latest projects for homepage
        $data['projects'] = $projectModel
            ->where('is_featured', 1)  // Featured projects first
            ->orWhere('status', 'published')  // Or published projects
            ->orderBy('created_at', 'DESC')  // Most recent first
            ->limit(4)  // Only show 4 on homepage
            ->find();
        */
        
        // For now, pass empty array (view will show placeholders)
        $data['projects'] = [];
        
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
        // STEP 4: FETCH BLOG POSTS DATA
        // ============================================
        // TODO: Uncomment when BlogModel is created
        /*
        $blogModel = new \App\Models\BlogModel();
        
        // Get 3 latest published blog posts
        $data['blogs'] = $blogModel
            ->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->limit(3)
            ->find();
        */
        
        $data['blogs'] = [];
        
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

