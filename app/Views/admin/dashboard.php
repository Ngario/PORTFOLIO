<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Dashboard</h1>
    <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary btn-sm">View site</a>
</div>

<div class="row g-3">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Projects</h5>
                <p class="card-text text-muted">Add or edit projects shown on the site.</p>
                <a class="btn btn-primary btn-sm" href="<?= base_url('admin/projects') ?>">Manage Projects</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Blog Posts</h5>
                <p class="card-text text-muted">Create drafts or publish posts.</p>
                <a class="btn btn-primary btn-sm" href="<?= base_url('admin/blog-posts') ?>">Manage Blog Posts</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

