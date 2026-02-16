<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?= esc($mode === 'edit' ? 'Edit Category' : 'New Category') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/download-categories') ?>">&larr; Back</a>
</div>

<?php
$action = $mode === 'edit'
    ? base_url('admin/download-categories/' . $category['id'])
    : base_url('admin/download-categories');
?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" class="form-control" value="<?= esc(old('name', $category['name'] ?? '')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input name="slug" class="form-control" value="<?= esc(old('slug', $category['slug'] ?? '')) ?>" placeholder="auto-generated if empty">
                <div class="form-text">Used in URL: <code>/downloads/{slug}</code></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Parent category (optional)</label>
                <?php $selected = old('parent_id', $category['parent_id'] ?? ''); ?>
                <select name="parent_id" class="form-select">
                    <option value="">No parent</option>
                    <?php foreach (($parents ?? []) as $p): ?>
                        <option value="<?= esc($p['id']) ?>" <?= ((string) $selected === (string) $p['id']) ? 'selected' : '' ?>>
                            <?= esc($p['name'] ?? '') ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary" type="submit"><?= esc($mode === 'edit' ? 'Update' : 'Create') ?></button>
                <a class="btn btn-outline-secondary" href="<?= base_url('admin/download-categories') ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

