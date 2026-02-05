<?php
/**
 * About Page View
 * 
 * Extends: layouts/main.php (master template)
 * Shows: Detailed information about you
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($title ?? 'About Me') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Learn more about me') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="hero-section text-center" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">About Me</h1>
        <p class="lead">Get to know me better</p>
    </div>
</section>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Who I Am</h2>
                <p class="lead">
                    Hi! I'm a passionate full-stack developer with expertise in building 
                    modern web applications.
                </p>
                <p>
                    With years of experience in web development, I specialize in 
                    <strong>PHP, CodeIgniter, JavaScript, MySQL</strong>, and modern 
                    frontend frameworks. I'm dedicated to creating user-friendly, 
                    responsive, and scalable applications.
                </p>
                
                <h3 class="mt-5 mb-3">My Skills</h3>
                <div class="mb-4">
                    <span class="badge bg-primary me-2 mb-2">PHP</span>
                    <span class="badge bg-primary me-2 mb-2">CodeIgniter 4</span>
                    <span class="badge bg-primary me-2 mb-2">MySQL</span>
                    <span class="badge bg-primary me-2 mb-2">PostgresQL</span>
                    <span class="badge bg-primary me-2 mb-2">TypeScript</span>
                    <span class="badge bg-primary me-2 mb-2">Node.js</span>
                    <span class="badge bg-primary me-2 mb-2">Bootstrap</span>
                    <span class="badge bg-primary me-2 mb-2">Git</span>
                </div>
                
                <h3 class="mt-5 mb-3">What I Do</h3>
                <ul>
                    <li>Custom web application development</li>
                    <li>Responsive website design</li>
                    <li>Database design and optimization</li>
                    <li>API development and integration</li>
                    <li>E-commerce solutions</li>
                </ul>
                
                <div class="mt-5">
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-envelope me-2"></i>Get In Touch
                    </a>
                    <a href="<?= base_url('projects') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-folder-open me-2"></i>View My Work
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
