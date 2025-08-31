<div class="container-fluid">
    <div class="wrapper wrapper-content animated fadeInRight">
        <!-- Page Header -->
        <div class="row">
            <?php if (!empty($data)): ?>
                <div class="col-12">
                    <div class="page-header">
                        <h1 class="page-title">Featured Courses</h1>
                        <p class="page-subtitle">Expand your skills with our carefully curated learning programs</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <!-- Course Card 1 -->
            <?php if (empty($data)): ?>
                <div class="col-md-6 offset-md-3 my-5">
                    <div class="ibox empty-card text-center">
                        <div class="ibox-content py-5">
                            <div class="icon text-warning mb-3">
                                <i class="fa fa-exclamation-circle fa-3x"></i>
                            </div>
                            <h3 class="font-bold mb-2">No More Courses Available</h3>
                            <p class="text-muted mb-4">
                                You’ve reached the end of our available courses.
                                Check out the courses you’re already enrolled in.
                            </p>
                            <a href="/student/courses" class="btn btn-primary">
                                <i class="fa fa-book"></i> Browse Enrolled Courses
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($data as $row): ?>
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
                                    <a href="/course/show/<?= $row['id'] ?>"
                                        class="btn btn-outline-primary btn-block mr-2">
                                        Learn More
                                    </a>
                                    <button class="btn btn-success btn-block enrollBtn"
                                        data-course-id="<?= $row['id'] ?>">
                                        Enroll Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
</style>

<script>

    $(document).ready(function () {
        $('.enrollBtn').on('click', function () {
            let courseId = $(this).data('course-id');
            enrollStudent(courseId, $(this));
        });
    });

    async function enrollStudent(courseId, buttonElement) {
        const courseCard = buttonElement.closest('.courseCard');

        console.log(courseCard);
        // SPINNER ON CLICK
        buttonElement.prop('disabled', true);
        const originalText = buttonElement.text();
        buttonElement.html('<i class="fa fa-spinner fa-spin"></i> Enrolling...');


        try {
            // * await pauses the function until the Promise is settled
            const response = await fetch('/student/enroll', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ course_id: courseId }),
            });

            const result = await response.json();

            if (response.ok) {
                console.log(result);
                showToast('Course enrolled successfully', "success", "Success");
                removeCourseCard(courseCard)
            } else {
                throw new Error(result.error || "Course enrollment failed");
            }
        } catch (error) {
            console.error("Course enrollment error:", error);
            showToast(error.message, "error", "ERROR");
        }



    }

    function removeCourseCard(courseCard) {
        courseCard.css({
            'transition': 'all 0.4s ease',
            'transform': 'scale(0.95)',
            'opacity': '0.7'
        });

        courseCard.find('.enrollBtn').html('<i class="fa fa-check"></i> Enrolled!')
            .removeClass('btn-outline-primary')
            .addClass('btn-success')
            .css('background-color', '#28a745');

        courseCard.fadeOut(400, function () {
            $(this).remove();
            checkForEmptyState();
        });
    }

    function checkForEmptyState() {
        const remainingCourses = $('.course-card').length;

        if (remainingCourses === 0) {
            // Create and show empty state
            const emptyStateHTML = `
            <div class="col-md-6 offset-md-3 my-5" id="empty-state">
                <div class="ibox empty-card text-center">
                    <div class="ibox-content py-5">
                        <div class="icon text-warning mb-3">
                            <i class="fa fa-exclamation-circle fa-3x"></i>
                        </div>
                        <h3 class="font-bold mb-2">No More Courses Available</h3>
                        <p class="text-muted mb-4">
                            You've reached the end of our available courses.
                            Check out the courses you're already enrolled in.
                        </p>
                        <a href="/student/courses" class="btn btn-primary">
                            <i class="fa fa-book"></i> Browse Enrolled Courses
                        </a>
                    </div>
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