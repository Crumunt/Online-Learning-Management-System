<div class="row mb-3">
    <div class="col-sm-4 text-muted"><i class="fa fa-<?= $icon ?? 'id-card' ?> me-2 text-primary"></i> <?= $label ?>
    </div>
    <?php if ($text ?? null): ?>
        <div class="col-sm-8 font-weight-medium"><?= $text ?></div>
    <?php endif; ?>

    <?php if ($slot): ?>
        <?= $slot ?>
    <?php endif; ?>
</div>