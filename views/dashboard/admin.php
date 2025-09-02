<!-- Main Analytics Cards -->
<div class="row row-cols-xxl-6 row-cols-lg-4 row-cols-md-3 row-cols-2 align-items-center">
    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Admin',
            'card_metric' => $cardData['admin_count'],
            'card_sub_heading' => 'Total count of Admins.',
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Students',
            'card_metric' => $cardData['student_count'],
            'card_sub_heading' => 'Total count of students.',
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Instructors',
            'card_metric' => $cardData['instructor_count'],
            'card_sub_heading' => 'Total count of Instructors',
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Active Courses',
            'card_metric' => $cardData['totalCourses'],
            'card_sub_heading' => 'Total count of active courses.',
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Course Content',
            'card_metric' => $cardData['totalMaterials'],
            'card_sub_heading' => 'Total count of course materials.',
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
                    <?= component('dashboard/distribution-metrics', ['color' => 'primary', 'contentMetric' => 0, 'metricName' => 'Video Lessons']) ?>
                    <?= component('dashboard/distribution-metrics', ['color' => 'primary', 'contentMetric' => $cardData['totalMaterials'], 'metricName' => 'Text Lessons', 'isEdge' => true]) ?>
                </div>
                <div class="row text-center m-t-lg">
                    <?= component('dashboard/distribution-metrics', ['color' => 'success', 'contentMetric' => $cardData['totalMaterials'], 'metricName' => 'Total Modules']) ?>
                    <?= component('dashboard/distribution-metrics', ['color' => 'warning', 'contentMetric' => 0, 'metricName' => 'Total Quizzes', 'isEdge' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row mt-4">
    <!-- Recent Enrollments Table -->
    <div class="col-lg-6">
        <?= component('dashboard/table', ['tableHeader' => 'Recent Enrollments', 'viewUrl' => '/admin/student', 'tableData' => $latestEnrollments]) ?>
    </div>

    <!-- Top Performing Courses -->
    <div class="col-lg-6">
        <?= component('dashboard/table', ['tableHeader' => 'Top Performing Courses', 'viewUrl' => '/admin/courses', 'tableData' => $topPerformingCourses]) ?>
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