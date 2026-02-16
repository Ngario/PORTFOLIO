<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Admin Login</h1>

                <form method="post" action="<?= current_url() ?>">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Sign in</button>
                </form>

                <p class="text-muted small mt-3 mb-0">
                    Your user must exist in the <code>users</code> table and have role <code>admin</code> (or <code>superadmin</code>).
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

