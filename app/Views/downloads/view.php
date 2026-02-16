<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Download') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($download['description'] ?? '') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('downloads') ?>">Downloads</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($download['title'] ?? '') ?></li>
                    </ol>
                </nav>
                <h1 class="h3 mb-3"><?= esc($download['title'] ?? '') ?></h1>
                <div class="mb-4"><?= nl2br(esc($download['description'] ?? '')) ?></div>

                <?php if (session()->get('user_id')): ?>
                    <a href="<?= base_url('download/file/' . $download['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                <?php else: ?>
                    <p class="text-muted">You must be logged in to download.</p>
                    <a href="<?= base_url('login') ?>?redirect=<?= urlencode(current_url()) ?>" class="btn btn-primary">Login to download</a>
                <?php endif ?>
                <a href="<?= base_url('downloads') ?>" class="btn btn-outline-secondary ms-2">&larr; Back to downloads</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
