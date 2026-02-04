<?php
/**
 * Contact Page View
 * 
 * Shows: Contact form for inquiries
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($title ?? 'Contact Me') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Get in touch') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="hero-section text-center" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Contact Me</h1>
        <p class="lead">Let's discuss your project</p>
    </div>
</section>

<!-- Contact Form -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->has('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card shadow">
                    <div class="card-body p-5">
                        <form action="<?= base_url('contact/send') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <label for="name" class="form-label">Your Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= old('name') ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Your Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?= old('subject') ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" 
                                          rows="6" required><?= old('message') ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="row mt-5">
                    <div class="col-md-4 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                <h5>Email</h5>
                                <p class="text-muted">your@email.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                                <h5>Phone</h5>
                                <p class="text-muted">+254 123 456 789</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                                <h5>Location</h5>
                                <p class="text-muted">Nairobi, Kenya</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
