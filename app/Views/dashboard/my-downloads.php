<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'My Downloads') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <h1 class="h3 mb-3">My Downloads</h1>
        <p class="text-muted">Your download history will appear here.</p>
        <a href="<?= base_url('downloads') ?>" class="btn btn-primary">Browse downloads</a>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary ms-2">&larr; Dashboard</a>
    </div>
</section>
<?= $this->endSection() ?>
