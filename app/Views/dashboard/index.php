<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Dashboard') ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif ?>
        <h1 class="h3 mb-3">Dashboard</h1>
        <p class="text-muted">Welcome, <?= esc(session()->get('user_name')) ?>.</p>
        <div class="row g-3 mt-2">
            <div class="col-md-4"><a href="<?= base_url('dashboard/profile') ?>" class="card text-decoration-none text-dark"><div class="card-body">My Profile</div></a></div>
            <div class="col-md-4"><a href="<?= base_url('dashboard/my-downloads') ?>" class="card text-decoration-none text-dark"><div class="card-body">My Downloads</div></a></div>
            <div class="col-md-4"><a href="<?= base_url('downloads') ?>" class="card text-decoration-none text-dark"><div class="card-body">Browse Downloads</div></a></div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
