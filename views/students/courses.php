<div class="container-fluid">
    <div class="wrapper wrapper-content animated fadeInRight">
        <!-- Page Header -->
        <?php if ($data->num_rows > 0): ?>
            <div class="row">
                <div class="col-12">
                    <div class="page-header">
                        <h1 class="page-title">Enrolled Courses</h1>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">

            <?php if ($data->num_rows === 0): ?>
                <div class="col-12 text-center my-5">
                    <div class="empty-state">
                        <div class="icon text-primary mb-3">
                            <i class="fa fa-book fa-4x"></i>
                        </div>
                        <h2 class="font-bold">No Courses Enrolled</h2>
                        <p class="text-muted mb-4">
                            You haven’t enrolled in any courses yet. Start learning today!
                        </p>
                        <a href="/student" class="btn btn-primary btn-lg">
                            <i class="fa fa-search"></i> Browse Courses
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php while ($row = $data->fetch_assoc()): ?>
                <div class="col-md-4 courseCard">
                    <div class="ibox course-card">
                        <div class="ibox-content">
                            <div class="course-header">
                                <h4 class="course-title"><?= $row['title'] ?></h4>
                                <p class="course-description text-muted">
                                    <?= $row['description'] ?>
                                </p>
                            </div>

                            <div class="course-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Instructor:</span>
                                    <span class="meta-value"><?= $row['instructor_name'] ?></span>
                                </div>
                            </div>

                            <div class="course-actions">
                                <div class="d-flex flex-column">
                                    <a href="/student/course/<?= $row['course_id'] ?>/show"
                                        class="btn btn-outline-primary btn-block mr-2">
                                        View Course
                                    </a>
                                    <button data-course-id="<?= $row['course_id'] ?>"
                                        class="btn btn-warning btn-block unenrollBtn">
                                        Unenroll
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<style>
    /* Enhanced Inspinia-style course cards */
    .wrapper {
        padding: 30px 0;
    }

    .course-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        background: #fff;
        margin-bottom: 30px;
        position: relative;
    }

    .course-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1ab394, #23c6c8);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
    }

    .course-card:hover::before {
        opacity: 1;
    }

    .ibox-content {
        padding: 30px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-header {
        margin-bottom: 20px;
    }

    .course-title {
        color: #676a6c;
        font-weight: 600;
        font-size: 1.4rem;
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .course-description {
        color: #8b91a5;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .course-meta {
        margin-bottom: 25px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .meta-label {
        font-weight: 600;
        color: #676a6c;
        min-width: 80px;
    }

    .meta-value {
        color: #8b91a5;
    }

    .course-actions {
        margin-top: auto;
    }

    .btn-outline-primary {
        border-color: #1ab394;
        color: #1ab394;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        background-color: #1ab394;
        border-color: #1ab394;
        color: white;
        transform: translateY(-1px);
    }

    .btn-success {
        background-color: #1ab394;
        border-color: #1ab394;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-success:hover {
        background-color: #17a085;
        border-color: #17a085;
        transform: translateY(-1px);
    }

    .btn {
        padding: 12px 20px;
        font-size: 0.9rem;
        height: 42px;
        line-height: 1.2;
    }

    /* Page header styling */
    .page-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e7eaec;
    }

    .page-title {
        color: #676a6c;
        font-weight: 300;
        font-size: 2.2rem;
        margin-bottom: 10px;
    }

    .page-subtitle {
        color: #8b91a5;
        font-size: 1.1rem;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .wrapper {
            padding: 20px 15px;
        }

        .ibox-content {
            padding: 20px;
        }

        .btn-block+.btn-block {
            margin-left: 0;
            margin-top: 0;
        }

        .d-flex {
            flex-direction: column;
        }

        .mr-2 {
            margin-right: 0 !important;
            margin-bottom: 10px;
        }
    }

    /* Enhanced animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .course-card {
        animation: fadeInUp 0.6s ease forwards;
    }

    .col-md-4:nth-child(2) .course-card {
        animation-delay: 0.1s;
    }

    .col-md-4:nth-child(3) .course-card {
        animation-delay: 0.2s;
    }

    .col-md-4:nth-child(4) .course-card {
        animation-delay: 0.3s;
    }

    /* Inspinia-style improvements */
    .wrapper-content {
        padding: 20px;
    }

    .animated.fadeInRight {
        animation-name: fadeInUp !important;
    }

    /* Ensure consistent button heights */
    .course-actions .btn {
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    /* Hover effects for better UX */
    .course-card .ibox-content:hover .course-title {
        color: #1ab394;
        transition: color 0.2s ease;
    }

    .empty-state {
        padding: 40px 20px;
        border-radius: 10px;
        background: #f9f9f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: inline-block;
        max-width: 500px;
    }

    .empty-state .icon i {
        opacity: 0.7;
    }
</style>

<script>

    $(document).ready(function () {
        $('.unenrollBtn').on('click', function () {
            const courseId = $(this).data('course-id');
            const buttonElement = $(this);

            unenrollStudent(courseId, buttonElement);
        });
    })

    async function unenrollStudent(courseId, buttonElement) {
        const courseCard = buttonElement.closest('.course-card');

        // SPINNER ON CLICK
        buttonElement.prop('disabled', true);
        const originalText = buttonElement.text();
        buttonElement.html('<i class="fa fa-spinner fa-spin"></i> un-Enrolling...');


        try {
            // * await pauses the function until the Promise is settled
            const response = await fetch('/student/removeEnrollment', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ course_id: courseId }),
            });

            const result = await response.json();

            if (response.ok) {
                console.log(result);
                showToast('Course un-enrolled successfully', "success", "Success");
                removeCourseCard(courseCard)
            } else {
                throw new Error(result.error || "Course un-enrollment failed");
            }
        } catch (error) {
            console.error("Course un-enrollment error:", error);
            showToast(error.message, "error", "ERROR");
        }



    }

    function removeCourseCard(courseCard) {
        courseCard.css({
            'transition': 'all 0.4s ease',
            'transform': 'scale(0.95)',
            'opacity': '0.7'
        });

        const button = courseCard.find('.enrollBtn');
        if (button.length) {
            button.html('<i class="fa fa-check"></i> Enrolled!')
                .removeClass('btn-outline-primary')
                .addClass('btn-success')
                .css('background-color', '#28a745');
        }

        // Wait a bit, then start fade out
        setTimeout(function () {
            courseCard.css({
                'opacity': '0',
                'transform': 'translateX(100%) scale(0.8)',
                'max-height': $courseCard.height() + 'px'
            });
        }, 800);

        setTimeout(function () {
            courseCard.remove();
            checkForEmptyState();
            showMessage(successMessage, 'success');
        }, 1200);
    }

    function checkForEmptyState() {
        const remainingCourses = $('.course-card').length;

        if (remainingCourses === 0) {
            // Create and show empty state
            const emptyStateHTML = `
            <div class="col-12 text-center my-5">
                    <div class="empty-state">
                        <div class="icon text-primary mb-3">
                            <i class="fa fa-book fa-4x"></i>
                        </div>
                        <h2 class="font-bold">No Courses Enrolled</h2>
                        <p class="text-muted mb-4">
                            You haven’t enrolled in any courses yet. Start learning today!
                        </p>
                        <a href="/student" class="btn btn-primary btn-lg">
                            <i class="fa fa-search"></i> Browse Courses
                        </a>
                    </div>
                </div>
        `;

            // Find the row containing courses and add empty state
            $('.wrapper-content .row:last-child').html(emptyStateHTML);

            // Also update the page header
            $('.page-title').text('All Caught Up!');
            $('.page-subtitle').text('You\'ve enrolled in all available courses');

            // Animate empty state
            $('#empty-state').hide().fadeIn(500);
        }
    }

</script>