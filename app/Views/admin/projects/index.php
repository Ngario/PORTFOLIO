<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Projects</h1>
    <a class="btn btn-primary btn-sm" href="<?= base_url('admin/projects/new') ?>"><i class="fa fa-plus me-1"></i> New</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Featured</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($projects)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No projects yet.</td></tr>
            <?php else: ?>
                <?php foreach ($projects as $p): ?>
                    <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><?= esc($p['title'] ?? '') ?></td>
                        <td><code><?= esc($p['slug'] ?? '') ?></code></td>
                        <td><?= ! empty($p['featured']) ? 'Yes' : 'No' ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/projects/' . $p['id'] . '/edit') ?>">Edit</a>
                            <form method="post" action="<?= base_url('admin/projects/' . $p['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this project?');">
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

