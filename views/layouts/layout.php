<?php require 'base.php' ?>
<div id="wrapper">

    <?php include($componentPath . '/nav/sidebar.php') ?>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <?php include($componentPath . '/nav/topbar.php') ?>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <!-- SLOT :> -->
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include($componentPath . '/footer.php') ?>

    </div>
</div>

<?php require 'script-base.html' ?>