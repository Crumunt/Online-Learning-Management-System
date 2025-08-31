<?php require('base.php') ?>

<style>
    .container {
        height: 100vh;
    }
</style>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeInDown">
            <div class="card-body p-4">
                <?= $content ?>
            </div>

            <!-- Footer -->
            <div class="card-footer text-center text-muted small">
                &copy; 2014 - <?= date('Y') ?> Inspinia Admin Theme. All rights reserved.
            </div>
        </div>
    </div>
</div>

<?php require 'script-base.html' ?>