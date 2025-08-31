<div class="ibox ">
    <div class="ibox-title">
        <h5><?= $card_title ?></h5>
    </div>
    <div class="ibox-content">
        <h1 class="no-margins"><?= number_format($card_metric) ?></h1>
        <div class="d-flex justify-content-between">
            <small><?= $card_sub_heading ?></small>
            <div class="stat-percent font-bold text-navy"><?= number_format($percentage, 2) ?>% <i
                    class="fa fa-level-up"></i></div>
        </div>
    </div>
</div>