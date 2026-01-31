<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic Title - Each page can set its own -->
    <title><?= $this->renderSection('title', true) ? $this->renderSection('title') : 'My Portfolio - Professional Developer' ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= $this->renderSection('description', true) ? $this->renderSection('description') : 'Professional portfolio showcasing projects, services, and blog' ?>">
    <meta name="keywords" content="portfolio, web developer, projects, downloads, services">
    <meta name="author" content="Your Name">
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Bootstrap 5 CSS - Responsive Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 - Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts - Professional Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS - Our styles -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <!-- Additional CSS per page -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Navigation Header -->
    <?= $this->include('components/header') ?>
    
    <!-- Main Content Area - This is where page-specific content loads -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- Footer -->
    <?= $this->include('components/footer') ?>
    
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= base_url('js/main.js') ?>"></script>
    
    <!-- Additional Scripts per page -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
