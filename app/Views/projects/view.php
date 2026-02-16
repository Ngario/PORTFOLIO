<?php
/**
 * Single Project View
 *
 * Receives: $title, $project (single item with title, description, image, tech_stack, etc.)
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? $project['title']) ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc(mb_substr(strip_tags($project['description'] ?? $project['title'] ?? ''), 0, 160)) ?><?= $this->endSection() ?>

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
        <?php if (! empty($project['created_at'])): ?>
            <p class="text-white-50"><?= esc($project['created_at']) ?></p>
        <?php endif ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if (! empty($project['image'])): ?>
                    <img src="<?= esc(base_url('uploads/' . $project['image'])) ?>" class="img-fluid rounded shadow-sm mb-4" alt="<?= esc($project['title']) ?>">
                <?php endif ?>
                <div class="mb-4"><?= nl2br(esc($project['description'] ?? '')) ?></div>
                <?php if (! empty($project['tech_stack'])): ?>
                    <h5 class="mb-2">Tech Stack</h5>
                    <p>
                        <?php
                        $techs = is_array($project['tech_stack']) ? $project['tech_stack'] : (is_string($project['tech_stack']) ? (json_decode($project['tech_stack'], true) ?: []) : []);
                        foreach ($techs as $tech): ?>
                            <span class="badge bg-primary me-2 mb-2"><?= esc(is_string($tech) ? $tech : (string) $tech) ?></span>
                        <?php endforeach ?>
                    </p>
                <?php endif ?>
                <?php if (! empty($project['demo_url'])): ?>
                    <a href="<?= esc($project['demo_url']) ?>" class="btn btn-primary me-2" target="_blank" rel="noopener">View Demo</a>
                <?php endif ?>
                <?php if (! empty($project['github_url'])): ?>
                    <a href="<?= esc($project['github_url']) ?>" class="btn btn-outline-secondary" target="_blank" rel="noopener">GitHub</a>
                <?php endif ?>
                <div class="mt-4">
                    <a href="<?= base_url('projects') ?>" class="btn btn-outline-secondary">&larr; All Projects</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
