<?php
/**
 * Search Page View
 *
 * Extends: layouts/main.php
 * Shows: A search form and filtered FAQ results when ?q= is present.
 * Data: $query (current search term), $faqs (filtered list), $title, $description
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($query !== '' ? "Search: {$query}" : ($title ?? 'Search')) ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= esc($description ?? 'Search FAQs and site content') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="hero-section text-center" style="padding: 60px 0 40px;">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Search</h1>
        <p class="lead">Find answers in our FAQs</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Search Form: GET so URL is /search?q=... and user can bookmark/share -->
                <form action="<?= base_url('search') ?>" method="get" class="mb-4">
                    <div class="input-group input-group-lg">
                        <input type="search" name="q" class="form-control" placeholder="Type a question or keyword..."
                               value="<?= esc($query ?? '') ?>" aria-label="Search FAQs">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>

                <?php if ($query !== ''): ?>
                    <h2 class="h5 mb-3">Results for &ldquo;<?= esc($query) ?>&rdquo;</h2>
                    <?php if (! empty($faqs)): ?>
                        <div class="accordion" id="searchAccordion">
                            <?php foreach ($faqs as $i => $faq): ?>
                                <?php
                                $id = 'search-faq-' . $i;
                                $question = $faq['question'] ?? '';
                                $answer = $faq['answer'] ?? '';
                                ?>
                                <div class="accordion-item">
                                    <h3 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#<?= esc($id) ?>"
                                                aria-expanded="false" aria-controls="<?= esc($id) ?>">
                                            <?= esc($question) ?>
                                        </button>
                                    </h3>
                                    <div id="<?= esc($id) ?>" class="accordion-collapse collapse" data-bs-parent="#searchAccordion">
                                        <div class="accordion-body">
                                            <?= nl2br(esc($answer)) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-muted small mt-3"><?= count($faqs) ?> result(s)</p>
                    <?php else: ?>
                        <p class="text-muted">No FAQs match your search. Try different keywords or <a href="<?= base_url('faqs') ?>">browse all FAQs</a>.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted">Enter a keyword above to search FAQs (e.g. &ldquo;download&rdquo;, &ldquo;account&rdquo;, &ldquo;contact&rdquo;).</p>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?= base_url('faqs') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>See all FAQs
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
