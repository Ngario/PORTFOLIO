<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Manage FAQs</h1>
    <a class="btn btn-primary" href="<?= base_url('admin/faqs/new') ?>"><i class="fa fa-plus me-1"></i> Add FAQ</a>
</div>

<div class="alert alert-info py-2 mb-3">
    <small><strong>Access only.</strong> FAQs are stored in the database and shown on the public FAQs page, in search, and in the chatbot. They are not available for download; visitors can only read them on the site.</small>
</div>

<p class="text-muted small mb-3">All FAQs and answers below are loaded from the database. Add or edit entries to update what appears on the site.</p>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0 align-middle">
            <thead class="table-dark">
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 70px;">Order</th>
                <th>Question</th>
                <th>Answer</th>
                <th class="text-end" style="width: 180px;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($faqs)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No FAQs in the database yet. Click <strong>Add FAQ</strong> to create one.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($faqs as $faq): ?>
                    <?php
                    $question = $faq['question'] ?? '';
                    $answer = $faq['answer'] ?? '';
                    $qPreview = mb_strlen($question) > 60 ? mb_substr($question, 0, 60) . '…' : $question;
                    $aPreview = mb_strlen($answer) > 80 ? mb_substr($answer, 0, 80) . '…' : $answer;
                    ?>
                    <tr>
                        <td class="text-muted"><?= (int) ($faq['id'] ?? 0) ?></td>
                        <td><?= (int) ($faq['sort_order'] ?? 0) ?></td>
                        <td><?= esc($qPreview) ?></td>
                        <td class="text-muted small"><?= esc($aPreview) ?></td>
                        <td class="text-end">
                            <a class="btn btn-outline-primary btn-sm" href="<?= base_url('admin/faqs/' . (int) $faq['id'] . '/edit') ?>"><i class="fa fa-edit me-1"></i> Edit</a>
                            <form method="post" action="<?= base_url('admin/faqs/' . (int) $faq['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
                                <?= csrf_field() ?>
                                <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fa fa-trash me-1"></i> Delete</button>
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
