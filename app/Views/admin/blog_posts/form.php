<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?= esc($mode === 'edit' ? 'Edit Blog Post' : 'New Blog Post') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/blog-posts') ?>">&larr; Back</a>
</div>

<?php
$action = $mode === 'edit'
    ? base_url('admin/blog-posts/' . $post['id'])
    : base_url('admin/blog-posts');
?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" value="<?= esc(old('title', $post['title'] ?? '')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input name="slug" class="form-control" value="<?= esc(old('slug', $post['slug'] ?? '')) ?>" placeholder="auto-generated if empty">
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <?php $status = old('status', $post['status'] ?? 'draft'); ?>
                    <select name="status" class="form-select">
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                    </select>
                    <div class="form-text">Public blog page only shows <code>published</code> posts.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Published at</label>
                    <input name="published_at" class="form-control" value="<?= esc(old('published_at', $post['published_at'] ?? '')) ?>" placeholder="YYYY-MM-DD HH:MM:SS">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Featured / placeholder image</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <div class="form-text">Optional. JPG, PNG, GIF, WebP. Shown on blog listing and post header.</div>
                <?php if (! empty($post['image'])): ?>
                    <p class="small text-muted mt-1 mb-0">Current: <img src="<?= base_url('uploads/' . esc($post['image'])) ?>" alt="" style="max-height:60px;"> <code><?= esc($post['image']) ?></code></p>
                <?php endif ?>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label">Content</label>
                <textarea name="content" rows="10" class="form-control" required><?= esc(old('content', $post['content'] ?? '')) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Categories</label>
                <select class="form-select" name="categories[]" multiple size="6">
                    <?php foreach (($categories ?? []) as $cat): ?>
                        <?php $id = (int) ($cat['id'] ?? 0); ?>
                        <option value="<?= esc($id) ?>" <?= in_array($id, $selected ?? [], true) ? 'selected' : '' ?>>
                            <?= esc($cat['name'] ?? '') ?> (<?= esc($cat['slug'] ?? '') ?>)
                        </option>
                    <?php endforeach ?>
                </select>
                <div class="form-text">Hold Ctrl (Windows) to select multiple.</div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary" type="submit"><?= esc($mode === 'edit' ? 'Update' : 'Create') ?></button>
                <a class="btn btn-outline-secondary" href="<?= base_url('admin/blog-posts') ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

