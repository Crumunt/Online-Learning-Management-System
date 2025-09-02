<div class="ibox">
    <div class="ibox-title">
        <h5><?= $tableHeader ?></h5>
        <div class="ibox-tools">
            <a href="<?= $viewUrl ?>" class="btn btn-primary btn-xs">View All</a>
        </div>
    </div>
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <?php foreach ($tableData['headers'] as $header): ?>
                            <th><?= ucfirst($header) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tableData['tableData'])): ?>
                        <tr>
                            <td colspan="100%" class="text-center text-muted py-5">
                                <i class="fa fa-info-circle fa-2x mb-3 text-warning d-block"></i>
                                <strong>No data found</strong><br>
                                <small>There are currently no <?= strtolower($tableHeader) ?>.</small>
                            </td> 
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tableData['tableData'] as $row): ?>
                            <tr>
                                <?php foreach ($tableData['columns'] as $col): ?>
                                    <td><?= ($col === 'enrolled_at') ? date('M d, Y', strtotime($row[$col])) : ucwords($row[$col]) ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>