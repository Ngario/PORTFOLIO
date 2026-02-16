<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Login') ?><?= $this->endSection() ?>
<?= $this->section('description') ?>Log in to your account<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-4">Login</h1>
                        <p class="text-muted small mb-3">Sign in to access your dashboard and downloads.</p>

                        <form method="post" action="<?= current_url() ?>">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>" required autocomplete="email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required autocomplete="current-password">
                            </div>
                            <button class="btn btn-primary w-100 mb-2" type="submit">Sign in</button>
                            <p class="text-center small text-muted mb-0">
                                Don't have an account? <a href="<?= base_url('register') ?>">Register</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
