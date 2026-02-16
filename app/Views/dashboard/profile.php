<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Profile') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <h1 class="h3 mb-3">My Profile</h1>
        <p class="text-muted"><?= esc(session()->get('user_name')) ?> — <?= esc(session()->get('user_email')) ?></p>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">&larr; Back to dashboard</a>
    </div>
</section>
<?= $this->endSection() ?>
