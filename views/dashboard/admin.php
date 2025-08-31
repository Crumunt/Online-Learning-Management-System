<!-- Main Analytics Cards -->
<div class="row row-cols-xxl-6 row-cols-lg-4 row-cols-md-3 row-cols-2 align-items-center">
    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Admin',
            'card_metric' => $card_data['admin_count'],
            'card_sub_heading' => '',
            'percentage' => 5
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Students',
            'card_metric' => $card_data['student_count'],
            'card_sub_heading' => '+',
            'percentage' => 12
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Instructors',
            'card_metric' => $card_data['instructor_count'],
            'card_sub_heading' => '+',
            'percentage' => 1
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Active Courses',
            'card_metric' => $card_data['total_courses'],
            'card_sub_heading' => '',
            'percentage' => 8
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Course Content',
            'card_metric' => $card_data['total_materials'],
            'card_sub_heading' => '',
            'percentage' => 18
        ]) ?>
    </div>

</div>

<!-- Analytics Charts Section -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Enrollment Trend</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <canvas id="activityChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Content Distribution</h5>
            </div>
            <div class="ibox-content">
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <h3 class="text-primary">0</h3>
                        <p class="m-b-xs">Video Lessons</p>
                        <div class="progress progress-mini">
                            <div class="progress-bar progress-bar-primary" style="width: 0">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-info">1</h3>
                        <p class="m-b-xs">Text Lessons</p>
                        <div class="progress progress-mini">
                            <div class="progress-bar progress-bar-info" style="width: <?= (1 / 1) * 100 ?>%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center m-t-lg">
                    <div class="col-6 border-right">
                        <h3 class="text-success">1</h3>
                        <p class="m-b-xs">Total Modules</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning">0</h3>
                        <p class="m-b-xs">Total Quizzes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row mt-4">
    <!-- Recent Enrollments Table -->
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Recent Enrollments</h5>
                <div class="ibox-tools">
                    <a href="/admin/enrollments" class="btn btn-primary btn-xs">View All</a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Enrolled</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Courses -->
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Top Performing Courses</h5>
                <div class="ibox-tools">
                    <a href="/admin/courses" class="btn btn-primary btn-xs">View All</a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Enrollments</th>
                                <th>Modules</th>
                                <th>Avg Score</th>
                                <th>Status</th>
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

<!-- Chart initialization -->
<script>
    $(document).ready(function () {
        // Activity chart showing enrollments and quiz attempts
        const enrollmentData = <?= $enrollment_data ?>;
        var ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: enrollmentData.labels,
                datasets: [{
                    label: 'New Enrollments',
                    data: enrollmentData.data,
                    borderColor: '#1ab394',
                    backgroundColor: 'rgba(26, 179, 148, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>