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
                    <?php foreach ($tableData['tableData'] as $row): ?>
                        <tr>
                            <?php foreach ($tableData['columns'] as $col): ?>
                                <td><?= ($col === 'enrolled_at') ? date('M d, Y', strtotime($row[$col])) : ucwords($row[$col]) ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>