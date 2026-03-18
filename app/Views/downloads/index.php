<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Downloads') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? '') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Downloads</h1>
        <p class="lead">Books, software, and resources. Log in to download.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (! empty($dbError)): ?>
            <div class="alert alert-warning">
                Downloads are temporarily unavailable on this server (database connection/migrations). If you are on the deployed site, make sure the production database is connected and migrations have been run.
            </div>
        <?php endif ?>
        <?php if (! empty($categories)): ?>
            <div class="row g-3 mb-4">
                <?php foreach ($categories as $cat): ?>
                    <div class="col-md-4">
                        <a href="<?= base_url('downloads/' . esc($cat['slug'] ?? '')) ?>" class="card text-decoration-none text-dark h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($cat['name'] ?? '') ?></h5>
                                <span class="text-primary small">View downloads &rarr;</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <h2 class="h5 mb-3">All downloads</h2>
        <?php if (empty($downloads)): ?>
            <p class="text-muted"><?= ! empty($dbError) ? 'No downloads can be loaded right now.' : 'No downloads yet.' ?></p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($downloads as $d): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm overflow-hidden">
                            <?php
                            $imgSrc = ! empty($d['image']) ? base_url('uploads/' . esc($d['image'])) : base_url('images/placeholder-download.svg');
                            $placeholderUrl = base_url('images/placeholder-download.svg');
                            ?>
                            <img src="<?= $imgSrc ?>" class="card-img-top" alt="" style="object-fit:cover;height:160px;background:#e9ecef;"
                                 onerror="this.onerror=null; this.src='<?= esc($placeholderUrl) ?>';">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($d['title'] ?? '') ?></h5>
                                <p class="card-text small text-muted"><?= esc(mb_substr(strip_tags($d['description'] ?? ''), 0, 100)) ?>…</p>
                                <a href="<?= base_url('download/' . $d['id']) ?>" class="btn btn-outline-primary btn-sm">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>
<?= $this->endSection() ?>
