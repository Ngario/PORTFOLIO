<?php
/**
 * Single Service View
 * Receives: $title, $service
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? $service['name']) ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc(mb_substr(strip_tags($service['description'] ?? $service['name'] ?? ''), 0, 160)) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 60px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0 bg-transparent text-white">
                <li class="breadcrumb-item"><a href="<?= base_url('services') ?>" class="text-white">Services</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($service['title']) ?></li>
            </ol>
        </nav>
        <div class="mt-3">
            <i class="fas fa-cog fa-3x text-white mb-3"></i>
        </div>
        <h1 class="display-6 fw-bold mb-2"><?= esc($service['name']) ?></h1>
        <p class="text-white-50"><?= esc(mb_substr(strip_tags($service['description'] ?? ''), 0, 120)) ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="mb-4"><?= nl2br(esc($service['description'] ?? '')) ?></div>
                <?php if (isset($service['price']) && $service['price'] !== null && $service['price'] !== ''): ?>
                    <p class="fw-bold text-primary"><?= esc($service['price']) ?></p>
                <?php endif ?>
                <a href="<?= base_url('contact') ?>" class="btn btn-primary">Request this service</a>
                <a href="<?= base_url('services') ?>" class="btn btn-outline-secondary ms-2">&larr; All Services</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
