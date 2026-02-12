<?php
/**
 * Services List View
 * Receives: $title, $description, $services
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Services') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Services I offer') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Services</h1>
        <p class="lead">What I offer: development, design, and consulting</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body py-4">
                        <div class="mb-3">
                            <i class="fas fa-cog fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title"><?= esc($service['name']) ?></h5>
                        <?php $short = isset($service['description']) ? mb_substr(strip_tags($service['description']), 0, 100) : ''; if (mb_strlen($service['description'] ?? '') > 100) { $short .= '…'; } ?>
                        <p class="card-text text-muted small"><?= esc($short) ?></p>
                        <?php if (isset($service['price']) && $service['price'] !== null && $service['price'] !== ''): ?>
                            <p class="small fw-bold text-primary mb-2"><?= esc($service['price']) ?></p>
                        <?php endif ?>
                        <a href="<?= base_url('services/' . $service['id']) ?>" class="btn btn-outline-primary btn-sm">Learn more</a>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
        <div class="mt-4 text-center">
            <a href="<?= base_url('contact') ?>" class="btn btn-primary">Get in touch</a>
            <a href="<?= base_url() ?>#services" class="btn btn-outline-secondary ms-2">Back to Home</a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
