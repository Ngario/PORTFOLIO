<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">

<?php
$adminName = session()->get('admin_name') ?? 'Admin';
$isLoggedIn = (bool) session()->get('admin_logged_in');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('admin') ?>">Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <?php if ($isLoggedIn): ?>
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/projects') ?>">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/blog-posts') ?>">Blog Posts</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><span class="navbar-text text-white-50 me-3">Signed in as <?= esc($adminName) ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/logout') ?>">Logout</a></li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/login') ?>">Login</a></li>
                </ul>
            <?php endif ?>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif ?>

    <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

