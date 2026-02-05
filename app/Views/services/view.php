<?php
/**
 * Single Service View
 * Receives: $title, $service
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? $service['title']) ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($service['excerpt'] ?? $service['title']) ?><?= $this->endSection() ?>

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
            <i class="<?= esc($service['icon'] ?? 'fas fa-cog') ?> fa-3x text-white mb-3"></i>
        </div>
        <h1 class="display-6 fw-bold mb-2"><?= esc($service['title']) ?></h1>
        <p class="text-white-50"><?= esc($service['excerpt'] ?? '') ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <p class="lead"><?= esc($service['excerpt']) ?></p>
                <div class="mb-4"><?= nl2br(esc($service['description'])) ?></div>
                <?php if (! empty($service['price_from'])): ?>
                    <p class="fw-bold text-primary"><?= esc($service['price_from']) ?></p>
                <?php endif ?>
                <a href="<?= base_url('contact') ?>" class="btn btn-primary">Request this service</a>
                <a href="<?= base_url('services') ?>" class="btn btn-outline-secondary ms-2">&larr; All Services</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
