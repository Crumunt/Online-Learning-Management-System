<?php
$status_state = [
    'approved' => 'success',
    'pending' => 'info',
    'rejected' => 'danger',
];


?>
<div class="col-sm-8">
    <span class="badge badge-<?= $status_state[$status] ?> badge-pill px-3 py-2" style="font-size: 0.85em;">
        <?= ucfirst($status) ?>
    </span>
</div>