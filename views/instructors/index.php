<?php


$instructorCount = $data->num_rows;

$users = $data->fetch_all(MYSQLI_ASSOC);

$approvedInstructors = array_filter($users, function ($user) {
    return $user['status'] === 'approved';
});

$courseCount = array_sum(array_column($users, 'courses_count'));

$hasAdd = str_contains($_SERVER['REQUEST_URI'], 'admin');
$user_role = $_SESSION['user_role'] ?? null;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Enhanced Page Header with Gradient Background -->
    <div class="row">
        <?= component('dashboard/page-header', ['management_type' => 'instructor', 'route' => "/{$user_role}/instructor/create",'headerIcon' => 'graduation-cap', 'hasAdd' => $hasAdd]) ?>
    </div>

    <!-- Quick Stats Row with Modern Cards and Shadows -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="ibox" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="ibox-content p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-primary font-weight-bold mb-0" style="font-size: 24px;">
                                <?= $instructorCount ?>
                            </h5>
                            <span class="text-muted small">Total Instructors</span>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-chalkboard-teacher fa-3x text-primary" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="ibox" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="ibox-content p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-success font-weight-bold mb-0" style="font-size: 24px;">
                                <?= count($approvedInstructors) ?>
                            </h5>
                            <span class="text-muted small">Active Instructors</span>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-user-check fa-3x text-success" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="ibox" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="ibox-content p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-info font-weight-bold mb-0" style="font-size: 24px;"><?= $courseCount ?>
                            </h5>
                            <span class="text-muted small">Total Courses</span>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-book-open fa-3x text-info" style="opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table with Enhanced Styling -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                <div class="ibox-title"
                    style="background-color: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #e7eaec;">
                    <h5 style="font-weight: 600; color: #495057;"><i class="fa fa-table mr-2"></i> Instructor Directory
                    </h5>
                    <div class="ibox-tools">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-toggle="dropdown"
                                style="border: 1px solid #dee2e6;">
                                <i class="fa fa-filter"></i> Filter <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-filter="all">All Instructors</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="active">Active Only</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="inactive">Inactive Only</a></li>
                                <li><a class="dropdown-item" href="#" data-filter="recent">Recently Joined</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="ibox-content p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTables-ajax" id="instructorsTable"
                            style="margin-bottom: 0;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th width="60">ID</th>
                                    <th width="250">Instructor</th>
                                    <th width="200">Email</th>
                                    <th width="100">Status</th>
                                    <th width="120">Courses</th>
                                    <th width="120">Joined</th>
                                    <th width="160" class="no-sort">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="instructorTBody">
                                <?php if (count($users) === 0): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle fa-2x mb-2 text-warning"></i><br>
                                            <strong>No data found</strong><br>
                                            <small>There are currently no records to display.</small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .page-title {
        color: white;
    }

    .ibox {
        border: none;
        background: white;
    }

    .ibox-content {
        border: none;
    }

    .badge {
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .progress {
        background-color: #f3f6fd;
    }

    .progress-bar {
        transition: width 1s ease-in-out;
    }

    .btn-outline-primary,
    .btn-outline-info,
    .btn-outline-warning,
    .btn-outline-secondary {
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover,
    .btn-outline-info:hover,
    .btn-outline-warning:hover,
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .table td,
    .table th {
        vertical-align: middle;
        padding: 12px;
    }

    .table-hover tbody tr:hover {
        background-color: #f3f6fd;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.5rem;
        margin-left: 0.25rem;
        border-radius: 4px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #6777ef;
        color: white !important;
        border-color: #6777ef;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #6777ef;
        color: white !important;
        border-color: #6777ef;
    }

    .dataTables_filter input {
        border-radius: 4px;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
</style>

<!-- Enhanced JavaScript -->
<script>
    let table;

    // * CONFIGGGGGGG
    const CONFIG = {
        ENDPOINTS: {
            FETCH: '<?= $_SERVER['REQUEST_URI'] ?>',
            DELETE: '/admin/instructor/delete'
        },
        MESSAGES: {
            DELETE_SUCCESS: 'Instructor deleted successfully!',
            DELETE_CONFIRM: 'Are you sure you want to delete this instructor?'
        }
    };


    $(document).ready(() => {
        // * UNIQUE COLUMNS
        const columns = [
             {
                data: "name"
            },
            {
                data: "email"
            },
            {
                data: "course_count",
                render: (data) => `<span class="badge badge-primary px-3 py-2">${data ?? 0}</span><br><small class="text-muted">courses taught</small>`,
            },
        ];

        const HAS_Status = true;
        table = initDataTable(CONFIG.ENDPOINTS.FETCH, columns, HAS_Status);
    });

    $(document).on('click', '.deleteBtn', function () {
        let userId = $(this).attr('data-key');

        deleteEntity(userId, CONFIG);

    });


</script>