<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <div class="p-4 text-white" style="background: linear-gradient(135deg, #36b9cc, #2c9faf);">
                    <h2 class="mb-0">
                        <?php if(isset($role)): ?>
                            <i class="fa fa-user me-2"></i> <?= ucfirst($role) ?>
                        <?php else: ?>
                            <i class="fa fa-book me-2"></i> Course
                        <?php endif; ?>
                        Details
                    </h2>
                </div>

                <div class="card-body p-4 bg-light">

                   <?= $slot ?>

                </div>

                <!-- Footer / Actions -->
                <div class="card-footer bg-white d-flex justify-content-between">
                    <button class="btn btn-outline-secondary btn-lg rounded-pill shadow-sm"
                        onclick="window.history.back()">
                        <i class="fa fa-arrow-left me-2"></i> Back
                    </button>
                    <div>
                        <a href="/admin/<?= $role ?>/update/<?= $user_id ?>" class="btn btn-secondary btn-lg rounded-pill shadow-sm">
                            <i class="fa fa-edit me-2"></i> Edit
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>