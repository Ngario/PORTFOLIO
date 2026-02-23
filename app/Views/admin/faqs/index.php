<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">FAQs</h1>
    <a class="btn btn-primary btn-sm" href="<?= base_url('admin/faqs/new') ?>"><i class="fa fa-plus me-1"></i> New FAQ</a>
</div>

<p class="text-muted small mb-3">These appear on the public FAQs page, in search results, and are used by the chatbot.</p>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th style="width: 50px;">Order</th>
                <th>Question</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($faqs)): ?>
                <tr><td colspan="3" class="text-center text-muted py-4">No FAQs yet. Add one to show on the site and in the chatbot.</td></tr>
            <?php else: ?>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td><?= (int) ($faq['sort_order'] ?? 0) ?></td>
                        <td><?= esc(mb_substr($faq['question'] ?? '', 0, 80)) ?><?= mb_strlen($faq['question'] ?? '') > 80 ? '…' : '' ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/faqs/' . (int) $faq['id'] . '/edit') ?>">Edit</a>
                            <form method="post" action="<?= base_url('admin/faqs/' . (int) $faq['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
