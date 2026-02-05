<?php
/**
 * Single Blog Post View
 * Receives: $title, $post
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? $post['title']) ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($post['excerpt'] ?? $post['title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 60px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 bg-transparent text-white">
                <li class="breadcrumb-item"><a href="<?= base_url('blog') ?>" class="text-white">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($post['title']) ?></li>
            </ol>
        </nav>
        <span class="badge bg-light text-dark mt-2"><?= esc(ucfirst($post['category'] ?? 'Uncategorized')) ?></span>
        <h1 class="display-6 fw-bold mt-3 mb-2"><?= esc($post['title']) ?></h1>
        <?php if (! empty($post['published_at']) || ! empty($post['author'])): ?>
            <p class="text-white-50 small">
                <?= ! empty($post['published_at']) ? esc($post['published_at']) : '' ?>
                <?= ! empty($post['author']) ? ' &middot; ' . esc($post['author']) : '' ?>
            </p>
        <?php endif ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if (! empty($post['image'])): ?>
                    <img src="<?= base_url('images/' . $post['image']) ?>" 
                         class="img-fluid rounded shadow mb-4" 
                         alt="<?= esc($post['title']) ?>">
                <?php endif ?>
                <p class="lead"><?= esc($post['excerpt'] ?? '') ?></p>
                <div class="blog-content"><?= nl2br(esc($post['content'] ?? '')) ?></div>
                <div class="mt-4">
                    <a href="<?= base_url('blog/category/' . ($post['category'] ?? 'uncategorized')) ?>" class="badge bg-secondary text-decoration-none me-2"><?= esc(ucfirst($post['category'] ?? 'Uncategorized')) ?></a>
                    <a href="<?= base_url('blog') ?>" class="btn btn-outline-secondary mt-2">&larr; All posts</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
