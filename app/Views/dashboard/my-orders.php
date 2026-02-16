<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'My Orders') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <h1 class="h3 mb-3">My Orders</h1>
        <p class="text-muted">Your orders will appear here.</p>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">&larr; Dashboard</a>
    </div>
</section>
<?= $this->endSection() ?>
