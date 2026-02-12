<?php
/**
 * Blog Category View
 * Receives: $title, $description, $category, $posts
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Blog') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Blog category') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 bg-transparent text-white">
                <li class="breadcrumb-item"><a href="<?= base_url('blog') ?>" class="text-white">Blog</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc(ucfirst($category)) ?></li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mt-3 mb-2"><?= esc(ucfirst($category)) ?></h1>
        <p class="lead">Posts in this category</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($posts)): ?>
            <p class="text-center text-muted">No posts in this category yet.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($posts as $post): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="card h-100 shadow-sm border-0 overflow-hidden">
                        <a href="<?= base_url('blog/' . ($post['slug'] ?? $post['id'])) ?>" class="text-decoration-none text-dark">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($post['title']) ?></h5>
                                <p class="card-text text-muted small"><?= esc($post['excerpt'] ?? '') ?></p>
                                <span class="text-primary small fw-bold">Read more &rarr;</span>
                            </div>
                        </a>
                    </article>
                </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <div class="mt-4 text-center">
            <a href="<?= base_url('blog') ?>" class="btn btn-outline-primary">&larr; All posts</a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
