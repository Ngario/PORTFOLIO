<?php
/**
 * Single Project View
 *
 * Receives: $title, $project (single item with title, description, image, tech_stack, etc.)
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? $project['title']) ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($project['excerpt'] ?? $project['title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 60px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 bg-transparent text-white">
                <li class="breadcrumb-item"><a href="<?= base_url('projects') ?>" class="text-white">Projects</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($project['title']) ?></li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold mt-3 mb-2"><?= esc($project['title']) ?></h1>
        <?php if (! empty($project['completed_at'])): ?>
            <p class="text-white-50">Completed <?= esc($project['completed_at']) ?></p>
        <?php endif ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <img src="<?= base_url('images/' . ($project['image'] ?? 'projects/placeholder.jpg')) ?>" 
                     class="img-fluid rounded shadow mb-4" 
                     alt="<?= esc($project['title']) ?>">
                <p class="lead"><?= esc($project['excerpt'] ?? '') ?></p>
                <div class="mb-4"><?= nl2br(esc($project['description'])) ?></div>
                <?php if (! empty($project['tech_stack'])): ?>
                    <h5 class="mb-2">Tech Stack</h5>
                    <p>
                        <?php foreach ($project['tech_stack'] as $tech): ?>
                            <span class="badge bg-primary me-2 mb-2"><?= esc($tech) ?></span>
                        <?php endforeach ?>
                    </p>
                <?php endif ?>
                <?php if (! empty($project['link']) && $project['link'] !== '#'): ?>
                    <a href="<?= esc($project['link']) ?>" class="btn btn-primary" target="_blank" rel="noopener">Visit Project</a>
                <?php endif ?>
                <div class="mt-4">
                    <a href="<?= base_url('projects') ?>" class="btn btn-outline-secondary">&larr; All Projects</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
