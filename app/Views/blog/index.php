<?php
/**
 * Blog List View
 * Receives: $title, $description, $posts
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Blog') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Blog posts and articles') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Blog</h1>
        <p class="lead">Articles, tutorials, and updates</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4">
                <article class="card h-100 shadow-sm border-0 overflow-hidden">
                    <a href="<?= base_url('blog/' . ($post['slug'] ?? $post['id'])) ?>" class="text-decoration-none text-dark">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2"><?= esc(ucfirst($post['category'] ?? 'Uncategorized')) ?></span>
                            <h5 class="card-title"><?= esc($post['title']) ?></h5>
                            <p class="card-text text-muted small"><?= esc($post['excerpt'] ?? '') ?></p>
                            <?php if (! empty($post['published_at'])): ?>
                                <p class="small text-muted mb-0"><?= esc($post['published_at']) ?></p>
                            <?php endif ?>
                            <span class="text-primary small fw-bold">Read more &rarr;</span>
                        </div>
                    </a>
                </article>
            </div>
            <?php endforeach ?>
        </div>
        <div class="mt-4 text-center">
            <a href="<?= base_url() ?>#blogs" class="btn btn-outline-primary">Back to Home</a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
