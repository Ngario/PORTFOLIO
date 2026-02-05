<?php
/**
 * Privacy Policy Page View
 *
 * Receives from Pages controller: $title, $description
 * URL: /privacy
 * Legal page - update to match how you actually collect and use data.
 */
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($title ?? 'Privacy Policy') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'How we handle your data') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="hero-section text-center page-hero" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Privacy Policy</h1>
        <p class="lead">How we collect, use, and protect your information</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto legal-content">
                <p class="text-muted small">Last updated: <?= date('F j, Y') ?></p>

                <h2 class="h4 mt-4 mb-2">1. Who We Are</h2>
                <p>This site is operated by Idd Mumanyi (“we”, “us”). This policy explains how we handle your personal data when you use this website.</p>

                <h2 class="h4 mt-4 mb-2">2. Data We Collect</h2>
                <p>We may collect:</p>
                <ul>
                    <li><strong>Information you give us:</strong> Name, email, and message when you use the contact form; account details if you register; payment-related data if you make a purchase.</li>
                    <li><strong>Automatically collected data:</strong> IP address, browser type, device info, and pages visited (e.g. via server logs or analytics) to run and improve the site.</li>
                </ul>

                <h2 class="h4 mt-4 mb-2">3. How We Use Your Data</h2>
                <p>We use your data to:</p>
                <ul>
                    <li>Respond to your messages and provide support</li>
                    <li>Process orders and deliver digital products</li>
                    <li>Improve the site, fix errors, and understand how it is used</li>
                    <li>Comply with legal obligations</li>
                </ul>
                <p>We do not sell your personal data to third parties.</p>

                <h2 class="h4 mt-4 mb-2">4. Cookies and Similar Technologies</h2>
                <p>We may use cookies and similar technologies for session management (e.g. keeping you logged in), preferences, and analytics. You can control cookies through your browser settings.</p>

                <h2 class="h4 mt-4 mb-2">5. Sharing and Disclosure</h2>
                <p>We may share data with:</p>
                <ul>
                    <li>Service providers (e.g. hosting, payment processors) who help run the site, under strict confidentiality</li>
                    <li>Authorities when required by law</li>
                </ul>

                <h2 class="h4 mt-4 mb-2">6. Security</h2>
                <p>We take reasonable steps to protect your data (e.g. secure hosting, HTTPS, careful handling of passwords). No method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>

                <h2 class="h4 mt-4 mb-2">7. Your Rights</h2>
                <p>Depending on where you live, you may have the right to access, correct, delete, or restrict use of your data, or to object to processing. To exercise these rights or ask questions, use the <a href="<?= base_url('contact') ?>">Contact</a> page.</p>

                <h2 class="h4 mt-4 mb-2">8. Retention</h2>
                <p>We keep your data only as long as needed for the purposes above (e.g. replying to messages, fulfilling orders, or as required by law).</p>

                <h2 class="h4 mt-4 mb-2">9. Changes to This Policy</h2>
                <p>We may update this privacy policy. The “Last updated” date will change, and we encourage you to review it periodically.</p>

                <h2 class="h4 mt-4 mb-2">10. Contact</h2>
                <p>For privacy-related questions or requests, contact us via the <a href="<?= base_url('contact') ?>">Contact</a> page.</p>

                <hr class="my-4">
                <a href="<?= base_url() ?>" class="btn btn-outline-primary">&larr; Back to Home</a>
                <a href="<?= base_url('terms') ?>" class="btn btn-outline-secondary ms-2">Terms of Service</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
