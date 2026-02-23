<?php
/**
 * FAQs Page View
 *
 * Extends: layouts/main.php (same header/footer as rest of site)
 * Shows: List of FAQs in a Bootstrap accordion (click question to expand answer)
 * Data: $title, $description, $faqs (array of ['question' => ..., 'answer' => ...])
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($title ?? 'FAQs') ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Frequently asked questions') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="hero-section text-center" style="padding: 80px 0;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Frequently Asked Questions</h1>
        <p class="lead">Quick answers to common questions</p>
    </div>
</section>

<!-- FAQs Accordion -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (! empty($faqs)): ?>
                    <div class="accordion" id="faqAccordion">
                        <?php foreach ($faqs as $i => $faq): ?>
                            <?php
                            $id = 'faq-' . $i;
                            $question = $faq['question'] ?? '';
                            $answer = $faq['answer'] ?? '';
                            $show = $i === 0; // First item open by default
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $show ? '' : 'collapsed' ?>" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#<?= esc($id) ?>"
                                            aria-expanded="<?= $show ? 'true' : 'false' ?>" aria-controls="<?= esc($id) ?>">
                                        <?= esc($question) ?>
                                    </button>
                                </h2>
                                <div id="<?= esc($id) ?>" class="accordion-collapse collapse <?= $show ? 'show' : '' ?>"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?= nl2br(esc($answer)) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No FAQs available at the moment. Check back later or <a href="<?= base_url('contact') ?>">contact me</a> with your question.</p>
                <?php endif; ?>

                <div class="mt-4 text-center">
                    <a href="<?= base_url('search') ?>" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-2"></i>Search FAQs
                    </a>
                    <a href="<?= base_url('contact') ?>" class="btn btn-primary">
                        <i class="fas fa-envelope me-2"></i>Contact Me
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
