<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================
// DEBUG (last fatal error – remove in production when done)
// ============================================
$routes->get('render-debug', 'Pages::renderDebug');

// ============================================
// HOMEPAGE
// ============================================
$routes->get('/', 'Home::index');

// ============================================
// ADMIN DASHBOARD (login + protected CRUD)
// ============================================
// Login routes are NOT protected (otherwise you can never reach them)
$routes->get('admin/login', 'Admin\\Auth::login');
$routes->post('admin/login', 'Admin\\Auth::attemptLogin');
$routes->get('admin/logout', 'Admin\\Auth::logout');

// Everything else under /admin is protected by the adminauth filter
$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'Admin\\Dashboard::index');

    // Projects CRUD
    $routes->get('projects', 'Admin\\Projects::index');
    $routes->get('projects/new', 'Admin\\Projects::new');
    $routes->post('projects', 'Admin\\Projects::create');
    $routes->get('projects/(:num)/edit', 'Admin\\Projects::edit/$1');
    $routes->post('projects/(:num)', 'Admin\\Projects::update/$1');
    $routes->post('projects/(:num)/delete', 'Admin\\Projects::delete/$1');

    // Blog posts CRUD
    $routes->get('blog-posts', 'Admin\\BlogPosts::index');
    $routes->get('blog-posts/new', 'Admin\\BlogPosts::new');
    $routes->post('blog-posts', 'Admin\\BlogPosts::create');
    $routes->get('blog-posts/(:num)/edit', 'Admin\\BlogPosts::edit/$1');
    $routes->post('blog-posts/(:num)', 'Admin\\BlogPosts::update/$1');
    $routes->post('blog-posts/(:num)/delete', 'Admin\\BlogPosts::delete/$1');
});

// ============================================
// STATIC PAGES (About, Contact, Terms, Privacy)
// ============================================
$routes->get('about', 'Pages::about');
$routes->get('contact', 'Pages::contact');
$routes->post('contact/send', 'Pages::sendMessage');  // Form submission
$routes->get('terms', 'Pages::terms');
$routes->get('privacy', 'Pages::privacy');

// ============================================
// PROJECTS
// ============================================
$routes->get('projects', 'Projects::index');           // All projects
$routes->get('projects/(:num)', 'Projects::view/$1');  // Single project by ID

// ============================================
// SERVICES
// ============================================
$routes->get('services', 'Services::index');           // All services
$routes->get('services/(:num)', 'Services::view/$1');  // Single service by ID

// ============================================
// BLOG
// ============================================
$routes->get('blog', 'Blog::index');                    // All blog posts
$routes->get('blog/(:segment)', 'Blog::view/$1');       // Single post by slug
$routes->get('blog/category/(:segment)', 'Blog::category/$1');  // Posts by category

// ============================================
// DOWNLOADS
// ============================================
$routes->get('downloads', 'Downloads::index');                    // All downloads
$routes->get('downloads/(:segment)', 'Downloads::category/$1');   // By category (software, books, etc.)
$routes->get('download/(:num)', 'Downloads::view/$1');            // Single download page
$routes->get('download/file/(:num)', 'Downloads::file/$1', ['filter' => 'auth']);  // Actual file – members only

// ============================================
// AUTHENTICATION
// ============================================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// ============================================
// USER DASHBOARD (Require login)
// ============================================
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('profile/update', 'Dashboard::updateProfile');
    $routes->get('my-downloads', 'Dashboard::myDownloads');
    $routes->get('my-orders', 'Dashboard::myOrders');
    $routes->get('settings', 'Dashboard::settings');
});

// ============================================
// CHECKOUT & PAYMENTS
// ============================================
$routes->get('cart', 'Cart::index');
$routes->post('cart/add/(:num)', 'Cart::add/$1');
$routes->post('cart/remove/(:num)', 'Cart::remove/$1');
$routes->get('checkout', 'Checkout::index');
$routes->post('checkout/process', 'Checkout::process');
$routes->get('payment/callback', 'Payment::callback');  // M-Pesa callback
