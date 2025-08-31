<?php
$courseCount = $data->num_rows;

$allCourses = $data->fetch_all(MYSQLI_ASSOC);

$approvedCourses = array_filter($allCourses, function ($course) {
    return $course['status'] === 'approved';
});

$totalEnrollments = array_sum(array_column($allCourses, 'enrollments'));

$hasAdd = str_contains($_SERVER['REQUEST_URI'], '/instructor');

$user_role = $_SESSION['user_role'];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Enhanced Page Header with Gradient Background -->
    <div class="row">
        <?= component('dashboard/page-header', ['management_type' => 'course', 'route' => "/{$user_role}/course/create", 'headerIcon' => 'book', 'hasAdd' => $hasAdd]) ?>
    </div>

    <!-- Quick Stats Row with Modern Cards and Shadows -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="ibox" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="ibox-content p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-primary font-weight-bold mb-0" style="font-size: 24px;">
                                <?= $courseCount ?? 0; ?>
                            </h5>
                            <span class="text-muted small">Total Courses</span>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-book fa-3x text-primary" style="opacity: 0.2;"></i>
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
                                <?= count($approvedCourses); ?>
                            </h5>
                            <span class="text-muted small">Published Courses</span>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-check-circle fa-3x text-success" style="opacity: 0.2;"></i>
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
                            <h5 class="text-info font-weight-bold mb-0" style="font-size: 24px;">
                                <?= $totalEnrollments ?? 0; ?>
                            </h5>
                            <span class="text-muted small">Total Enrollments</span>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-users fa-3x text-info" style="opacity: 0.2;"></i>
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
                    <h5 style="font-weight: 600; color: #495057;"><i class="fa fa-table mr-2"></i> Course Directory</h5>
                </div>
                <div class="ibox-content p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTables-ajax" style="margin-bottom: 0;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th width="60">ID</th>
                                    <th width="300">Course Title</th>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <th width="200">Instructor</th>
                                    <?php endif; ?>
                                    <th width="120">Enrollments</th>
                                    <th width="100">Status</th>
                                    <th width="120">Created</th>
                                    <th width="160" class="no-sort">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced JavaScript -->
<script>
    let table;

    const CONFIG = {
        ENDPOINTS: {
            FETCH: '<?= $_SERVER['REQUEST_URI'] ?>',
            DELETE: '/admin/course/delete'
        },
        MESSAGES: {
            DELETE_SUCCESS: 'Course deleted successfully!',
            DELETE_CONFIRM: 'Are you sure you want to delete this course?'
        }
    };


    $(document).ready(() => {
        let columns = [{
            data: 'title',
        }];

        if (window.location.href.indexOf('/admin') > 1) {
            isCourse = true;
            columns.push(
                {
                    data: 'instructor_name'
                }
            )
        }

        columns.push({
            data: "enrollments",
            render: (data) => `<span class="badge badge-primary px-3 py-2">${data}</span><br><small class="text-muted">Enrollments</small>`,
        });

        const HAS_STATUS = true;
        table = initDataTable(CONFIG.ENDPOINTS.FETCH, columns, HAS_STATUS);

    });




    $(document).on('click', '.deleteBtn', function () {
        let courseId = $(this).attr('data-key');

        deleteEntity(courseId, CONFIG);

    });

</script>