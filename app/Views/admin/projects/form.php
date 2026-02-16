<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?= esc($mode === 'edit' ? 'Edit Project' : 'New Project') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/projects') ?>">&larr; Back</a>
</div>

<?php
$action = $mode === 'edit'
    ? base_url('admin/projects/' . $project['id'])
    : base_url('admin/projects');
?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Card image</label>
                <?php if ($mode === 'edit' && ! empty($project['image'])): ?>
                    <div class="mb-2">
                        <img src="<?= esc(base_url('uploads/' . $project['image'])) ?>" alt="Current" class="img-fluid rounded" style="max-height: 180px;">
                        <p class="form-text text-muted small">Current image. Upload a new file to replace.</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <div class="form-text">Optional. JPG, PNG, GIF or WebP. Used on project cards (homepage and projects list).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" value="<?= esc(old('title', $project['title'] ?? '')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input name="slug" class="form-control" value="<?= esc(old('slug', $project['slug'] ?? '')) ?>" placeholder="auto-generated if empty">
                <div class="form-text">Used for URLs (example: <code>my-project</code>).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="6" class="form-control" required><?= esc(old('description', $project['description'] ?? '')) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tech Stack (comma-separated)</label>
                <input name="tech_stack" class="form-control" value="<?= esc(old('tech_stack', $project['tech_stack'] ?? '')) ?>" placeholder="PHP, MySQL, CodeIgniter 4">
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Demo URL</label>
                    <input name="demo_url" class="form-control" value="<?= esc(old('demo_url', $project['demo_url'] ?? '')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">GitHub URL</label>
                    <input name="github_url" class="form-control" value="<?= esc(old('github_url', $project['github_url'] ?? '')) ?>">
                </div>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured"
                       <?= (int) old('featured', $project['featured'] ?? 0) === 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="featured">Featured</label>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary" type="submit"><?= esc($mode === 'edit' ? 'Update' : 'Create') ?></button>
                <a class="btn btn-outline-secondary" href="<?= base_url('admin/projects') ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

