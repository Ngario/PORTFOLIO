<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Download Categories</h1>
    <a class="btn btn-primary btn-sm" href="<?= base_url('admin/download-categories/new') ?>"><i class="fa fa-plus me-1"></i> New</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Parent</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No categories yet.</td></tr>
            <?php else: ?>
                <?php
                $byId = [];
                foreach ($categories as $c) { $byId[(int) $c['id']] = $c; }
                ?>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td><?= esc($c['id']) ?></td>
                        <td><?= esc($c['name'] ?? '') ?></td>
                        <td><code><?= esc($c['slug'] ?? '') ?></code></td>
                        <td>
                            <?php
                            $pid = $c['parent_id'] ?? null;
                            echo $pid ? esc($byId[(int) $pid]['name'] ?? ('#' . $pid)) : '-';
                            ?>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/download-categories/' . $c['id'] . '/edit') ?>">Edit</a>
                            <form method="post" action="<?= base_url('admin/download-categories/' . $c['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this category?');">
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
    These categories appear in the public site’s Downloads dropdown and under <code>/downloads</code>.
</p>
<?= $this->endSection() ?>

