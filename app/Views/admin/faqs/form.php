<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?= esc($mode === 'edit' ? 'Edit FAQ' : 'Add FAQ') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/faqs') ?>"><i class="fa fa-arrow-left me-1"></i> Back to list</a>
</div>

<p class="text-muted small mb-3">FAQs are stored in the database and shown on the public site and in the chatbot. They are not downloadable.</p>

<?php
$action = $mode === 'edit'
    ? base_url('admin/faqs/' . (int) $faq['id'])
    : base_url('admin/faqs');
?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Question</label>
                <input name="question" class="form-control" value="<?= esc(old('question', $faq['question'] ?? '')) ?>" required placeholder="e.g. How do I download a resource?">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Answer</label>
                <textarea name="answer" class="form-control" rows="5" required placeholder="Full answer (shown on FAQs page and in chatbot)."><?= esc(old('answer', $faq['answer'] ?? '')) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Sort order</label>
                <input type="number" name="sort_order" class="form-control" style="max-width: 100px;" value="<?= esc(old('sort_order', $faq['sort_order'] ?? 0)) ?>" min="0">
                <div class="form-text">Lower numbers appear first. Leave 0 for default.</div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary" type="submit"><?= $mode === 'edit' ? 'Update FAQ' : 'Add FAQ' ?></button>
                <a class="btn btn-outline-secondary" href="<?= base_url('admin/faqs') ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
