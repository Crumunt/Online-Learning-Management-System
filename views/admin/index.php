<?php
// Simulated database analytics - replace with actual queries later
$analytics_data = [
    // User statistics
    'total_students' => 1247,
    'total_instructors' => 89,
    'new_students_this_month' => 156,
    'active_students_today' => 342,

    // Course statistics
    'total_courses' => 156,
    'public_courses' => 142,
    'private_courses' => 14,
    'courses_created_this_month' => 12,

    // Enrollment statistics
    'total_enrollments' => 3421,
    'enrollments_this_month' => 234,
    'enrollment_growth_rate' => 15.5,

    // Quiz and assessment statistics
    'total_quizzes' => 445,
    'quiz_attempts_today' => 78,
    'average_quiz_score' => 76.8,
    'quizzes_completed_this_week' => 456,

    // Content statistics
    'total_modules' => 789,
    'total_lessons' => 2156,
    'video_lessons' => 1234,
    'text_lessons' => 922,

    // Performance metrics
    'course_completion_rate' => 68.5,
    'student_retention_rate' => 82.3,
    'instructor_activity_rate' => 91.2
];

// Sample recent activities based on your database structure
$recent_enrollments = [
    ['student_name' => 'Sarah Johnson', 'course_title' => 'Advanced JavaScript', 'instructor' => 'Dr. Smith', 'enrolled_at' => '2 hours ago'],
    ['student_name' => 'Mike Chen', 'course_title' => 'Python Data Science', 'instructor' => 'Prof. Davis', 'enrolled_at' => '4 hours ago'],
    ['student_name' => 'Emma Rodriguez', 'course_title' => 'Web Design Fundamentals', 'instructor' => 'Ms. Johnson', 'enrolled_at' => '6 hours ago'],
    ['student_name' => 'James Wilson', 'course_title' => 'Mobile App Development', 'instructor' => 'Dr. Brown', 'enrolled_at' => '1 day ago'],
    ['student_name' => 'Lisa Wang', 'course_title' => 'Machine Learning Basics', 'instructor' => 'Prof. Miller', 'enrolled_at' => '1 day ago']
];

$top_courses = [
    ['title' => 'JavaScript Fundamentals', 'instructor' => 'Dr. Smith', 'enrollments' => 145, 'modules' => 8, 'avg_score' => 84.2, 'visibility' => 'public'],
    ['title' => 'Python for Beginners', 'instructor' => 'Prof. Johnson', 'enrollments' => 132, 'modules' => 6, 'avg_score' => 78.9, 'visibility' => 'public'],
    ['title' => 'React Development', 'instructor' => 'Ms. Davis', 'enrollments' => 128, 'modules' => 10, 'avg_score' => 81.5, 'visibility' => 'public'],
    ['title' => 'Data Science with R', 'instructor' => 'Dr. Wilson', 'enrollments' => 119, 'modules' => 12, 'avg_score' => 77.3, 'visibility' => 'private'],
    ['title' => 'UI/UX Design', 'instructor' => 'Mr. Brown', 'enrollments' => 98, 'modules' => 7, 'avg_score' => 88.1, 'visibility' => 'public']
];

$instructor_stats = [
    ['name' => 'Dr. Sarah Smith', 'courses' => 8, 'total_enrollments' => 456, 'avg_quiz_score' => 84.2, 'modules_created' => 45],
    ['name' => 'Prof. John Davis', 'courses' => 6, 'total_enrollments' => 389, 'avg_quiz_score' => 78.9, 'modules_created' => 38],
    ['name' => 'Ms. Emily Johnson', 'courses' => 5, 'total_enrollments' => 367, 'avg_quiz_score' => 81.5, 'modules_created' => 32],
    ['name' => 'Dr. Michael Wilson', 'courses' => 7, 'total_enrollments' => 403, 'avg_quiz_score' => 77.3, 'modules_created' => 41],
    ['name' => 'Prof. Lisa Brown', 'courses' => 4, 'total_enrollments' => 298, 'avg_quiz_score' => 88.1, 'modules_created' => 28]
];
?>

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
            'card_sub_heading' => '+' . $analytics_data['new_students_this_month'] . ' this month',
            'percentage' => 12
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Total Instructors',
            'card_metric' => $card_data['instructor_count'],
            'card_sub_heading' => '+' . $analytics_data['enrollments_this_month'] . ' this month',
            'percentage' => round($analytics_data['enrollment_growth_rate'])
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Active Courses',
            'card_metric' => $analytics_data['total_courses'],
            'card_sub_heading' => $analytics_data['public_courses'] . ' public, ' . $analytics_data['private_courses'] . ' private',
            'percentage' => 8
        ]) ?>
    </div>

    <div class="col">
        <?= component('dashboard/card', [
            'card_title' => 'Course Content',
            'card_metric' => $analytics_data['total_lessons'],
            'card_sub_heading' => 'Lessons across ' . $analytics_data['total_modules'] . ' modules',
            'percentage' => 18
        ]) ?>
    </div>

</div>

<!-- Analytics Charts Section -->
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Enrollment & Quiz Activity Trends</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-9">
                        <canvas id="activityChart" height="200"></canvas>
                    </div>
                    <div class="col-lg-3">
                        <div class="text-center">
                            <h2 class="no-margins text-primary"><?= $analytics_data['active_students_today'] ?></h2>
                            <small>Active students today</small>
                            <div class="m-t-sm">
                                <i class="fa fa-arrow-up text-success"></i> 8% increase
                            </div>
                        </div>
                        <div class="text-center m-t-lg">
                            <h2 class="no-margins text-success"><?= $analytics_data['quizzes_completed_this_week'] ?>
                            </h2>
                            <small>Quizzes completed this week</small>
                            <div class="m-t-sm">
                                <i class="fa fa-arrow-up text-success"></i> 15% increase
                            </div>
                        </div>
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
                        <h3 class="text-primary"><?= $analytics_data['video_lessons'] ?></h3>
                        <p class="m-b-xs">Video Lessons</p>
                        <div class="progress progress-mini">
                            <div class="progress-bar progress-bar-primary"
                                style="width: <?= ($analytics_data['video_lessons'] / $analytics_data['total_lessons']) * 100 ?>%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-info"><?= $analytics_data['text_lessons'] ?></h3>
                        <p class="m-b-xs">Text Lessons</p>
                        <div class="progress progress-mini">
                            <div class="progress-bar progress-bar-info"
                                style="width: <?= ($analytics_data['text_lessons'] / $analytics_data['total_lessons']) * 100 ?>%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center m-t-lg">
                    <div class="col-6 border-right">
                        <h3 class="text-success"><?= $analytics_data['total_modules'] ?></h3>
                        <p class="m-b-xs">Total Modules</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-warning"><?= $analytics_data['total_quizzes'] ?></h3>
                        <p class="m-b-xs">Total Quizzes</p>
                    </div>
                </div>
                <div class="text-center m-t-lg">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-muted">Performance Metrics</h4>
                            <div class="m-t-sm">
                                <small>Retention Rate:
                                    <strong><?= $analytics_data['student_retention_rate'] ?>%</strong></small><br>
                                <small>Instructor Activity:
                                    <strong><?= $analytics_data['instructor_activity_rate'] ?>%</strong></small>
                            </div>
                        </div>
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
                            <?php foreach ($recent_enrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong><?= $enrollment['student_name'] ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="text-primary font-weight-bold"><?= $enrollment['course_title'] ?></span>
                                    </td>
                                    <td><?= $enrollment['instructor'] ?></td>
                                    <td><small class="text-muted"><?= $enrollment['enrolled_at'] ?></small></td>
                                </tr>
                            <?php endforeach; ?>
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
                            <?php foreach ($top_courses as $course): ?>
                                <tr>
                                    <td>
                                        <strong><?= $course['title'] ?></strong><br>
                                        <small class="text-muted">by <?= $course['instructor'] ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?= $course['enrollments'] ?></span>
                                    </td>
                                    <td><?= $course['modules'] ?></td>
                                    <td>
                                        <span
                                            class="text-<?= $course['avg_score'] > 80 ? 'success' : ($course['avg_score'] > 70 ? 'warning' : 'danger') ?>">
                                            <?= $course['avg_score'] ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-<?= $course['visibility'] == 'public' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($course['visibility']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
        var ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'New Enrollments',
                    data: [23, 31, 28, 45, 38, 22, 19],
                    borderColor: '#1ab394',
                    backgroundColor: 'rgba(26, 179, 148, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Quiz Attempts',
                    data: [67, 89, 78, 95, 82, 56, 43],
                    borderColor: '#23c6c8',
                    backgroundColor: 'rgba(35, 198, 200, 0.1)',
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