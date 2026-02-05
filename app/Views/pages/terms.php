<?php
/**
 * Terms of Service Page View
 *
 * Receives from Pages controller: $title, $description
 * URL: /terms
 * Legal page - update the content to match your actual terms.
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Terms of Service') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Terms and conditions') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Terms of Service</h1>
        <p class="lead">Please read these terms before using this site</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto legal-content">
                <p class="text-muted small">Last updated: <?= date('F j, Y') ?></p>

                <h2 class="h4 mt-4 mb-2">1. Acceptance of Terms</h2>
                <p>By accessing and using this website and any related services (including downloads, contact forms, and content), you agree to be bound by these Terms of Service. If you do not agree, please do not use the site.</p>

                <h2 class="h4 mt-4 mb-2">2. Use of the Site</h2>
                <p>You may use this site for personal or business purposes in a lawful way. You must not:</p>
                <ul>
                    <li>Use the site in any way that breaks applicable laws or regulations</li>
                    <li>Attempt to gain unauthorized access to any part of the site, servers, or data</li>
                    <li>Use automated tools (e.g. scrapers, bots) without permission</li>
                    <li>Distribute malware or harm the site or its users</li>
                </ul>

                <h2 class="h4 mt-4 mb-2">3. Intellectual Property</h2>
                <p>Content on this site (text, images, code, designs, downloads) is owned by Idd Mumanyi or used with permission. You may not copy, modify, or redistribute it without written permission, except where explicitly allowed (e.g. downloadable resources with their own license).</p>

                <h2 class="h4 mt-4 mb-2">4. Downloads and Purchases</h2>
                <p>Where we offer digital downloads or paid products, separate terms may apply at the point of purchase. Refunds and usage rights will be stated there.</p>

                <h2 class="h4 mt-4 mb-2">5. Disclaimer</h2>
                <p>This site and its content are provided “as is.” We do not guarantee that the site will be error-free or uninterrupted. Use of any information or downloads is at your own risk.</p>

                <h2 class="h4 mt-4 mb-2">6. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, we are not liable for any indirect, incidental, or consequential damages arising from your use of the site or any content or services.</p>

                <h2 class="h4 mt-4 mb-2">7. Changes</h2>
                <p>We may update these terms from time to time. The “Last updated” date at the top will change. Continued use of the site after changes means you accept the updated terms.</p>

                <h2 class="h4 mt-4 mb-2">8. Contact</h2>
                <p>For questions about these terms, contact us via the <a href="<?= base_url('contact') ?>">Contact</a> page.</p>

                <hr class="my-4">
                <a href="<?= base_url() ?>" class="btn btn-outline-primary">&larr; Back to Home</a>
                <a href="<?= base_url('privacy') ?>" class="btn btn-outline-secondary ms-2">Privacy Policy</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
