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
                        <?php if (! empty($category) && ! empty($category['slug'])): ?>
                            <li class="breadcrumb-item"><a href="<?= base_url('downloads/' . esc($category['slug'])) ?>"><?= esc($category['name'] ?? '') ?></a></li>
                        <?php endif ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($download['title'] ?? '') ?></li>
                    </ol>
                </nav>
                <?php
                $imgSrc = ! empty($download['image']) ? base_url('uploads/' . esc($download['image'])) : base_url('images/placeholder-download.svg');
                $placeholderUrl = base_url('images/placeholder-download.svg');
                ?>
                <img src="<?= $imgSrc ?>" class="img-fluid rounded mb-3" alt="" style="max-height:280px;object-fit:cover;background:#e9ecef;"
                     onerror="this.onerror=null; this.src='<?= esc($placeholderUrl) ?>';">
                <h1 class="h3 mb-3"><?= esc($download['title'] ?? '') ?></h1>
                <div class="mb-4"><?= nl2br(esc($download['description'] ?? '')) ?></div>

                <?php if (! empty($download['installation_instructions'])): ?>
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h2 class="h6 mb-2"><i class="fas fa-cog me-2"></i>Installation instructions</h2>
                            <div class="small"><?= nl2br(esc($download['installation_instructions'])) ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->get('user_id')): ?>
                    <a href="<?= base_url('download/file/' . $download['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                <?php else: ?>
                    <p class="text-muted">You must be logged in to download.</p>
                    <a href="<?= base_url('login') ?>?redirect=<?= urlencode(current_url()) ?>" class="btn btn-primary">Login to download</a>
                <?php endif ?>
                <?php if (! empty($category) && ! empty($category['slug'])): ?>
                    <a href="<?= base_url('downloads/' . esc($category['slug'])) ?>" class="btn btn-outline-secondary ms-2">&larr; Back to <?= esc($category['name'] ?? 'category') ?></a>
                <?php endif ?>
                <a href="<?= base_url('downloads') ?>" class="btn btn-outline-secondary ms-2">&larr; All downloads</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
