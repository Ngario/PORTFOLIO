<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Blog Posts</h1>
    <a class="btn btn-primary btn-sm" href="<?= base_url('admin/blog-posts/new') ?>"><i class="fa fa-plus me-1"></i> New</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($posts)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No posts yet.</td></tr>
            <?php else: ?>
                <?php foreach ($posts as $p): ?>
                    <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><?= esc($p['title'] ?? '') ?></td>
                        <td><code><?= esc($p['slug'] ?? '') ?></code></td>
                        <td><?= esc($p['status'] ?? '') ?></td>
                        <td><?= esc($p['author_name'] ?? '') ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/blog-posts/' . $p['id'] . '/edit') ?>">Edit</a>
                            <form method="post" action="<?= base_url('admin/blog-posts/' . $p['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this post?');">
                                <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

