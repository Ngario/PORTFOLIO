<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Downloads') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? '') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="hero-section text-center page-hero" style="padding: 60px 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-2 bg-transparent text-white">
                <li class="breadcrumb-item"><a href="<?= base_url('downloads') ?>" class="text-white">Downloads</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($category['name'] ?? '') ?></li>
            </ol>
        </nav>
        <h1 class="display-6 fw-bold mb-2"><?= esc($category['name'] ?? 'Downloads') ?></h1>
        <p class="lead mb-0">Log in to download files in this category.</p>
        <a href="<?= base_url('downloads') ?>" class="btn btn-outline-light btn-sm mt-3">&larr; All downloads</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($downloads)): ?>
            <p class="text-muted">No downloads in this category yet.</p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($downloads as $d): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm overflow-hidden">
                            <?php if (! empty($d['image'])): ?>
                                <img src="<?= base_url('uploads/' . esc($d['image'])) ?>" class="card-img-top" alt="" style="object-fit:cover;height:160px;">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center text-white" style="height:160px;"><i class="fas fa-file-alt fa-3x"></i></div>
                            <?php endif ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($d['title'] ?? '') ?></h5>
                                <p class="card-text small text-muted"><?= esc(mb_substr(strip_tags($d['description'] ?? ''), 0, 100)) ?>…</p>
                                <a href="<?= base_url('download/' . $d['id']) ?>" class="btn btn-outline-primary btn-sm">View &amp; download</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>
<?= $this->endSection() ?>
