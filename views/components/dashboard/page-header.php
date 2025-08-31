<div class="col-12">
    <div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fa fa-<?= $headerIcon ?> me-2"></i>
                <?= ucfirst($management_type) ?> Management
            </h1>
        </div>
        <?php if ($hasAdd ?? null): ?>
            <div>
                <a href="<?= $route ?>" class="btn btn-success me-2">
                    <i class="fa fa-plus me-1"></i> Add <?= ucfirst($management_type) ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>