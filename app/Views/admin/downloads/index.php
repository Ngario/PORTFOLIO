<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Downloads</h1>
    <div>
        <a class="btn btn-outline-secondary btn-sm me-2" href="<?= base_url('admin/download-categories') ?>">Categories</a>
        <a class="btn btn-primary btn-sm" href="<?= base_url('admin/downloads/new') ?>"><i class="fa fa-plus me-1"></i> New</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Active</th>
                <th>Paid</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($downloads)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No downloads yet.</td></tr>
            <?php else: ?>
                <?php foreach ($downloads as $d): ?>
                    <tr>
                        <td><?= esc($d['id']) ?></td>
                        <td><?= esc($d['title'] ?? '') ?></td>
                        <td><?= esc($d['category_name'] ?? '-') ?></td>
                        <td><?= ! empty($d['is_active']) ? 'Yes' : 'No' ?></td>
                        <td>
                            <?= ! empty($d['is_paid']) ? 'Yes' : 'No' ?>
                            <?php if (! empty($d['is_paid']) && ! empty($d['price'])): ?>
                                <span class="text-muted">(<?= esc($d['price']) ?>)</span>
                            <?php endif ?>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/downloads/' . $d['id'] . '/edit') ?>">Edit</a>
                            <form method="post" action="<?= base_url('admin/downloads/' . $d['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this download?');">
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

<p class="text-muted small mt-3 mb-0">
    Files upload to <code>public/uploads/downloads</code>. The public site reads the downloads from the database.
</p>
<?= $this->endSection() ?>

