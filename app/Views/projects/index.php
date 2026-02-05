<?php
/**
 * Projects List View
 *
 * Receives from controller: $title, $description, $projects
 * Extends: layouts/main.php
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Projects') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'My projects and work') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">My Projects</h1>
        <p class="lead">Web applications, designs, and development work</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($projects as $project): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 overflow-hidden">
                    <a href="<?= base_url('projects/' . $project['id']) ?>" class="text-decoration-none text-dark">
                        <img src="<?= base_url('images/' . ($project['image'] ?? 'projects/placeholder.jpg')) ?>" 
                             class="card-img-top" 
                             alt="<?= esc($project['title']) ?>"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($project['title']) ?></h5>
                            <p class="card-text text-muted small"><?= esc($project['excerpt']) ?></p>
                            <?php if (! empty($project['tech_stack'])): ?>
                                <div class="mb-2">
                                    <?php foreach (array_slice($project['tech_stack'], 0, 3) as $tech): ?>
                                        <span class="badge bg-primary me-1"><?= esc($tech) ?></span>
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>
                            <span class="text-primary small fw-bold">View project &rarr;</span>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <div class="mt-4 text-center">
            <a href="<?= base_url() ?>#projects" class="btn btn-outline-primary">Back to Home</a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
