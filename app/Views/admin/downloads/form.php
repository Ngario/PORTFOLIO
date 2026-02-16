<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?= esc($mode === 'edit' ? 'Edit Download' : 'New Download') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/downloads') ?>">&larr; Back</a>
</div>

<?php
$action = $mode === 'edit'
    ? base_url('admin/downloads/' . $download['id'])
    : base_url('admin/downloads');
?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" value="<?= esc(old('title', $download['title'] ?? '')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <?php $selected = old('category_id', $download['category_id'] ?? ''); ?>
                <select name="category_id" class="form-select" required>
                    <option value="">Select category</option>
                    <?php foreach (($categories ?? []) as $c): ?>
                        <option value="<?= esc($c['id']) ?>" <?= ((string) $selected === (string) $c['id']) ? 'selected' : '' ?>>
                            <?= esc($c['name'] ?? '') ?> (<?= esc($c['slug'] ?? '') ?>)
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="5" class="form-control"><?= esc(old('description', $download['description'] ?? '')) ?></textarea>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                               <?= (int) old('is_active', $download['is_active'] ?? 1) === 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="is_paid" value="1" id="is_paid"
                               <?= (int) old('is_paid', $download['is_paid'] ?? 0) === 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_paid">Paid download</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Price (if paid)</label>
                    <input name="price" class="form-control" value="<?= esc(old('price', $download['price'] ?? '')) ?>" placeholder="e.g. 500">
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-2">
                <label class="form-label"><?= esc($mode === 'edit' ? 'Replace file (optional)' : 'Upload file') ?></label>
                <input type="file" name="file" class="form-control" <?= $mode === 'edit' ? '' : 'required' ?>>
                <div class="form-text">Allowed: pdf, zip, rar, 7z, doc/docx, ppt/pptx, mp4</div>
            </div>

            <?php if ($mode === 'edit' && ! empty($download['file_path'])): ?>
                <p class="small text-muted mb-0">Current file_path: <code><?= esc($download['file_path']) ?></code></p>
            <?php endif ?>

            <div class="mt-4">
                <button class="btn btn-primary" type="submit"><?= esc($mode === 'edit' ? 'Update' : 'Create') ?></button>
                <a class="btn btn-outline-secondary" href="<?= base_url('admin/downloads') ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

