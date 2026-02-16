<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Register') ?><?= $this->endSection() ?>
<?= $this->section('description') ?>Create an account<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-4">Register</h1>
                        <p class="text-muted small mb-3">Create an account to access downloads (books, software, etc.).</p>

                        <form method="post" action="<?= current_url() ?>">
                            <div class="mb-3">
                                <label class="form-label">Full name</label>
                                <input type="text" name="name" class="form-control" value="<?= esc(old('name')) ?>" required autocomplete="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>" required autocomplete="email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                                <div class="form-text">At least 8 characters.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm password</label>
                                <input type="password" name="password_confirm" class="form-control" required minlength="8" autocomplete="new-password">
                            </div>
                            <button class="btn btn-primary w-100 mb-2" type="submit">Create account</button>
                            <p class="text-center small text-muted mb-0">
                                Already have an account? <a href="<?= base_url('login') ?>">Login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
