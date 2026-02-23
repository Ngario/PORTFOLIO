<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?= esc($mode === 'edit' ? 'Edit FAQ' : 'New FAQ') ?></h1>
    <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/faqs') ?>">&larr; Back</a>
</div>

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
                <label class="form-label">Question</label>
                <input name="question" class="form-control" value="<?= esc(old('question', $faq['question'] ?? '')) ?>" required placeholder="e.g. How do I download a resource?">
            </div>
            <div class="mb-3">
                <label class="form-label">Answer</label>
                <textarea name="answer" class="form-control" rows="5" required placeholder="Full answer shown on FAQs page and in chatbot."><?= esc(old('answer', $faq['answer'] ?? '')) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Sort order</label>
                <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $faq['sort_order'] ?? 0)) ?>" min="0">
                <div class="form-text">Lower numbers appear first on the FAQs page.</div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary" type="submit"><?= $mode === 'edit' ? 'Update' : 'Create' ?></button>
                <a class="btn btn-outline-secondary" href="<?= base_url('admin/faqs') ?>">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
