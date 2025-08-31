<!-- Page Heading -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2>Instructor Dashboard</h2>
    </div>
</div>

<!-- Main Content -->
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Stats Row -->
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Total Courses</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= $course_count ?></h1>
                    <small>Published courses</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Total Students</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= $student_count ?></h1>
                    <small>Students enrolled</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Learning Materials</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><?= $learning_material_count ?></h1>
                    <small>Uploaded resources</small>
                </div>
            </div>
        </div>
    </div>


    <!-- Active Courses -->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Latest Enrollees</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Student Name</th>
                                <th>Enrolled at</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($student_data as $data): ?>
                                <tr>
                                    <td><?= $data['title'] ?></td>
                                    <td><?= $data['student_name'] ?></td>
                                    <td><?= date('M d, Y', strtotime($data['enrolled_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>