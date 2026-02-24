<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">My CV</h1>
</div>

<p class="text-muted small mb-3">The CV is stored in the database. Anyone can download it from the public link <a href="<?= base_url('download-cv') ?>" target="_blank" rel="noopener"><?= base_url('download-cv') ?></a>. Upload a new PDF to replace the current file.</p>

<?php if ($cvInfo): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6 mb-2">Current CV</h2>
            <p class="mb-1"><strong>File:</strong> <?= esc($cvInfo['filename']) ?></p>
            <p class="mb-0 text-muted small"><strong>Last updated:</strong> <?= $cvInfo['updated_at'] ? esc(date('d M Y, H:i', strtotime($cvInfo['updated_at']))) : '—' ?></p>
            <a href="<?= base_url('download-cv') ?>" class="btn btn-outline-primary btn-sm mt-2" target="_blank" rel="noopener"><i class="fa fa-download me-1"></i> Download current CV</a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning mb-4">No CV in the database yet. Upload a PDF below to make it available for download.</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <h2 class="h6 mb-3"><?= $cvInfo ? 'Replace CV' : 'Upload CV' ?></h2>
        <form method="post" action="<?= base_url('admin/cv/upload') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">PDF file</label>
                <input type="file" name="cv_file" class="form-control" accept=".pdf,application/pdf" required>
                <div class="form-text">Only PDF. This file will be stored in the database and served at /download-cv.</div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload me-1"></i> <?= $cvInfo ? 'Replace' : 'Upload' ?> CV</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
