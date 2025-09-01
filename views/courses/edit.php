<?php
$user_role = $_SESSION['user_role'] ?? null;

$course_status = $data['status'];
$course_diff = $data['difficulty'];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h2><i class="fa fa-edit mr-2"></i>Edit Course</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/instructor">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/instructor/course">Courses</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/instructor/course/show/<?= $data['id'] ?>">Course Details</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Edit Course
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="/instructor/course/show/<?= $data['id'] ?>"
                            class="btn btn-outline-secondary btn-sm mr-2">
                            <i class="fa fa-arrow-left mr-1"></i>Back to Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="course-edit-form" method="POST">
        <div class="row">
            <!-- Course Information -->
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-info-circle mr-2"></i>Course Information</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="course-title">Course Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="course-title" name="course_name"
                                        value="<?= $data['title'] ?>" required placeholder="Enter course title">
                                    <small class="form-text text-muted">Choose a clear, descriptive title for your
                                        course</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="course-description">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="course-description" name="short_description" rows="6"
                                        required
                                        placeholder="Describe what students will learn in this course"><?= $data['description'] ?></textarea>
                                    <small class="form-text text-muted">Provide a detailed description of the course
                                        content and learning objectives</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course-status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="course-status" name="status" required>
                                        <?php if (!in_array($course_status, ['draft', 'archived']) && $user_role !== 'admin'): ?>
                                            <option value="<?= $course_status ?>" selected><?= ucfirst($course_status) ?>
                                            </option>
                                        <?php endif; ?>
                                        <?php if ($user_role === 'admin'): ?>
                                            <option value="pending" <?= ($course_status === 'pending') ? 'selected' : '' ?>>
                                                Pending</option>
                                            <option value="approved" <?= ($course_status === 'approved') ? 'selected' : '' ?>>
                                                Approved</option>
                                            <option value="rejected" <?= ($course_status === 'rejected') ? 'selected' : '' ?>>
                                                Rejected</option>
                                        <?php endif; ?>
                                        <option value="draft">Draft</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <small class="form-text text-muted">Control the visibility and accessibility of your
                                        course</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course-level">Difficulty Level</label>
                                    <select class="form-control" id="course-level" name="difficulty">
                                        <option value="">Select Level</option>
                                        <option value="beginner" <?= $course_diff == 'beginner' ? 'selected' : '' ?>>
                                            Beginner</option>
                                        <option value="intermediate" <?= $course_diff == 'intermediate' ? 'selected' : '' ?>>
                                            Intermediate</option>
                                        <option value="advanced" <?= $course_diff == 'advanced' ? 'selected' : '' ?>>
                                            Advanced</option>
                                    </select>
                                </div>
                            </div>

                            <!-- HIDDEN -->
                            <input type="hidden" name="course_id" value="<?= $data['id'] ?>">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content text-center">
                        <button type="button" class="btn btn-outline-secondary mr-2" onclick="window.history.back()">
                            <i class="fa fa-times mr-1"></i>Cancel
                        </button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Inspinia Theme Styles */
    .wrapper-content {
        padding: 20px;
    }

    .page-heading {
        border-bottom: 1px solid #e7eaec;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .page-heading h2 {
        font-size: 24px;
        font-weight: 600;
        color: #676a6c;
        margin: 0 0 10px 0;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 12px;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
        color: #c4c4c4;
    }

    .ibox {
        clear: both;
        margin-bottom: 25px;
        margin-top: 0;
        padding: 0;
    }

    .ibox-title {
        border-color: #e7eaec;
        border-image: none;
        border-style: solid solid none;
        border-width: 1px 1px 0;
        color: inherit;
        margin-bottom: 0;
        padding: 14px 15px 7px;
        min-height: 48px;
        background: #fff;
        border-radius: 2px 2px 0 0;
    }

    .ibox-content {
        background-color: #ffffff;
        color: inherit;
        padding: 15px 20px 20px 20px;
        border-color: #e7eaec;
        border-image: none;
        border-style: solid;
        border-width: 1px;
        border-radius: 0 0 2px 2px;
    }

    .ibox-title h5 {
        display: inline-block;
        font-size: 14px;
        margin: 0 0 7px;
        padding: 0;
        text-overflow: ellipsis;
        float: left;
        font-weight: 600;
    }

    .ibox-tools {
        display: block;
        float: right;
        margin-top: 0;
        position: relative;
        padding: 0;
    }

    .form-control {
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        width: 100%;
    }

    .form-control:focus {
        border-color: #1ab394;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(26, 179, 148, 0.6);
        outline: 0;
    }

    .text-danger {
        color: #ed5565 !important;
    }

    .text-navy {
        color: #1ab394 !important;
    }

    .btn-primary {
        background-color: #1ab394;
        border-color: #1ab394;
        color: #FFFFFF;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: #18a689;
        border-color: #18a689;
    }

    .btn-outline-primary {
        border-color: #1ab394;
        color: #1ab394;
    }

    .btn-outline-primary:hover {
        background-color: #1ab394;
        border-color: #1ab394;
        color: #fff;
    }

    .btn-outline-secondary {
        border-color: #c4c4c4;
        color: #676a6c;
    }

    .btn-outline-secondary:hover {
        background-color: #676a6c;
        border-color: #676a6c;
        color: #fff;
    }

    .custom-control-input:checked~.custom-control-label::before {
        background-color: #1ab394;
        border-color: #1ab394;
    }

    .custom-control-input:focus~.custom-control-label::before {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.2rem rgba(26, 179, 148, 0.25);
    }

    .thumbnail-preview img {
        max-width: 100%;
        height: auto;
        border: 2px dashed #e5e6e7;
        padding: 10px;
    }

    .course-stats {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 3px;
        border: 1px solid #e7eaec;
    }

    .stat-item {
        font-size: 14px;
    }

    .animated.fadeInRight {
        animation-name: fadeInRight;
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translate3d(100%, 0, 0);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .collapse-link {
        color: inherit;
        cursor: pointer;
    }

    .collapse-link:hover {
        color: #1ab394;
        text-decoration: none;
    }

    .form-text {
        font-size: 0.875em;
    }

    .custom-file-label::after {
        background-color: #1ab394;
        border-color: #1ab394;
    }
</style>

<script>

    const CONFIG = {
        ENDPOINTS: {
            URL: '/<?= $user_role ?>/course/update',
            METHOD: 'POST',
        },
        MESSAGES: {
            UPDATED_SUCCESS: 'Course updated successfully'
        }
    }

    $(document).ready(function () {
        var form = $('#course-edit-form');
        var submitBtn = $('#submitBtn');

        // Form validation using jQuery
        submitBtn.on('click', function (e) {
            e.preventDefault()

            let datas = form.serializeArray();
            var data_array = {};
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });

            console.log(datas)
            $.ajax({
                url: CONFIG.ENDPOINTS.URL,
                method: CONFIG.ENDPOINTS.METHOD,
                data: {
                    ...data_array,
                },
                before: function () {
                    submitBtn.addClass('loading').prop('disabled', true);
                },
                success: function (result) {
                    var toastText = CONFIG.MESSAGES.UPDATED_SUCCESS;
                    var toastIcon = 'success';
                    generateToast(toastText, toastIcon, 'Success');

                    submitBtn.removeClass('loading').prop('disabled', false);
                },
                error: function (jqXHR) {
                    var res = JSON.parse(jqXHR.responseText)
                    var toastText = res.message;
                    console.log(res)
                    var toastIcon = 'error';
                    generateToast(toastText, toastIcon, 'ERROR');
                }
            });

            // Add loading state
            // $submitBtn.addClass('loading').prop('disabled', true);
        });
    })
</script>