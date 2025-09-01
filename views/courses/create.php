<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Create New Course</h5>
                    <div class="ibox-tools">
                        <a href="/instructor/courses" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Courses
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form action="" method="POST" id="courseForm">

                        <!-- Course Title -->
                        <div class="form-group">
                            <label for="course_name" class="form-label required">Course Title</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" value=""
                                placeholder="Enter course title" required>
                            <small class="form-text text-muted">
                                Choose a clear, descriptive title that tells students what they'll learn.
                            </small>
                        </div>

                        <!-- Course Description -->
                        <div class="form-group">
                            <label for="description" class="form-label required">Course Description</label>
                            <textarea class="form-control" id="description" name="short_description" rows="6" maxlength="250"
                                placeholder="Describe what students will learn, prerequisites, and course objectives..."
                                required></textarea>
                            <small class="form-text text-muted">
                                Provide a detailed description of the course content, learning outcomes, and any
                                prerequisites.
                            </small>
                            <div class="char-counter-group">
                                <span id="charCount" class="char-count">250</span>
                                <span class="char-label">characters remaining</span>
                            </div>
                        </div>

                        <!-- Status Field (Hidden) -->
                        <input type="hidden" name="status" value="pending">

                        <!-- Status Info Alert -->
                        <div class="form-group d-flex">
                            <div class="form-group w-50 pr-2">
                                <label for="difficulty" class="form-label required">Course Difficulty</label>
                                <select class="form-control" name="difficulty" id="difficulty">
                                    <option value="beginner" selected>Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                            </div>

                            <div class="alert alert-info w-50 pl-2" role="alert">
                                <i class="fa fa-info-circle"></i>
                                <strong>Course Status:</strong> Your course will be set to <strong>Pending</strong>
                                status and will require admin approval before being published to students.
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                                        <i class="fa fa-save"></i> Create Course
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="/instructor/courses" class="btn btn-outline-secondary btn-block">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="ibox mt-4">
                <div class="ibox-title">
                    <h5>Course Creation Tips</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="fa fa-lightbulb-o"></i> Writing a Great Title</h6>
                            <ul class="list-unstyled text-muted small">
                                <li>• Keep it clear and specific</li>
                                <li>• Include the skill level (Beginner, Advanced)</li>
                                <li>• Mention key technologies or concepts</li>
                                <li>• Limit to 60 characters when possible</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="fa fa-file-text-o"></i> Writing Effective Descriptions
                            </h6>
                            <ul class="list-unstyled text-muted small">
                                <li>• Start with learning outcomes</li>
                                <li>• List prerequisites if any</li>
                                <li>• Mention course duration estimate</li>
                                <li>• Include target audience</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Enhanced form styling */
    .form-label.required::after {
        content: " *";
        color: #ed5565;
        font-weight: bold;
    }

    .form-control {
        border-radius: 6px;
        border: 1px solid #e5e6e7;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #1ab394;
        box-shadow: 0 0 0 0.2rem rgba(26, 179, 148, 0.25);
    }

    .form-control.is-invalid {
        border-color: #ed5565;
        box-shadow: 0 0 0 0.2rem rgba(237, 85, 101, 0.25);
    }

    .invalid-feedback {
        display: block;
        font-size: 0.875rem;
        color: #ed5565;
        margin-top: 0.25rem;
    }

    /* Form actions styling */
    .form-actions {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e7eaec;
    }

    /* Button enhancements */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn-primary {
        background-color: #1ab394;
        border-color: #1ab394;
    }

    .btn-primary:hover {
        background-color: #17a085;
        border-color: #17a085;
    }

    .btn-outline-secondary {
        color: #676a6c;
        border-color: #e5e6e7;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f8f9;
        color: #676a6c;
        border-color: #d1d3d4;
    }

    /* Alert styling */
    .alert-info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #31708f;
        border-radius: 6px;
    }

    /* Help card styling */
    .ibox-content h6 {
        margin-bottom: 12px;
        font-weight: 600;
    }

    .list-unstyled li {
        margin-bottom: 4px;
        padding-left: 0;
    }

    /* Textarea resize */
    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    /* ibox enhancements */
    .ibox {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        overflow: hidden;
    }

    .ibox-title {
        background-color: #fafafa;
        border-bottom: 1px solid #e7eaec;
        padding: 20px;
    }

    .ibox-content {
        padding: 30px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-lg-8.offset-lg-2 {
            margin-left: 0;
        }

        .form-actions .col-md-6 {
            margin-bottom: 10px;
        }

        .ibox-content {
            padding: 20px;
        }
    }

    /* Loading state for submit button */
    .btn.loading {
        position: relative;
        color: transparent;
    }

    .btn.loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>

    const CONFIG = {
        ENDPOINTS: {
            URL: "/instructor/course/create",
            METHOD: "POST",
        },
        MESSAGES: {
            CREATED_SUCCESS: 'Course created successfully!',
        }

    }

    $(document).ready(function () {
        var $form = $('#courseForm');
        var $submitBtn = $('#submitBtn');

        // Form validation using jQuery
        $form.on('submit', function (e) {
            e.preventDefault()

            let datas = $(this).serializeArray();
            var data_array = {};
            $.map(datas, function (data) {
                data_array[data['name']] = data['value'];
            });

            console.log(data_array)
            $.ajax({
                url: CONFIG.ENDPOINTS.URL,
                method: CONFIG.ENDPOINTS.METHOD,
                data: {
                    ...data_array,
                },
                before: function () {
                    $submitBtn.addClass('loading').prop('disabled', true);
                },
                success: function (result) {
                    console.log(result)
                    var toastText = CONFIG.MESSAGES.CREATED_SUCCESS;
                    var toastIcon = 'success';
                    generateToast(toastText, toastIcon, 'Success');

                    resetForm();
                },
                error: function (jqXHR) {
                    var res = JSON.parse(jqXHR.responseText)
                    var toastText = res.message;
                    console.log(res)
                    var toastIcon = 'error';
                    generateToast(toastText, toastIcon, 'ERROR');
                },
                complete: function () {
                    $submitBtn.removeClass('loading').prop('disabled', false);
                }
            });

            // Add loading state

        });

        // Remove validation errors on input
        $('#title, #description').on('input', function () {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        });

        // Show error function
        function showError(selector, message) {
            var $input = $(selector);
            $input.addClass('is-invalid');

            if ($input.siblings('.invalid-feedback').length === 0) {
                $input.after('<div class="invalid-feedback">' + message + '</div>');
            }
        }

        // Auto-resize textarea
        $('textarea').on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Form field focus animations
        $('.form-control').on('focus', function () {
            $(this).closest('.form-group').addClass('focused');
        }).on('blur', function () {
            $(this).closest('.form-group').removeClass('focused');
        });

        // Character counter for title (optional enhancement)
        $('#title').on('input', function () {
            var length = $(this).val().length;
            var maxLength = 100;

            if (length > maxLength) {
                $(this).addClass('is-invalid');
                if ($(this).siblings('.char-counter').length === 0) {
                    $(this).after('<small class="char-counter text-danger">Title is too long (' + length + '/' + maxLength + ' characters)</small>');
                } else {
                    $(this).siblings('.char-counter').text('Title is too long (' + length + '/' + maxLength + ' characters)');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).siblings('.char-counter').remove();
            }
        });

        $('#description').on('input', function () {
            const TEXTAREA_MAX_LENGTH = 250;
            let currLength = $(this).val().length;
            let remainingCharacters = TEXTAREA_MAX_LENGTH - currLength

            $('#charCount').html(remainingCharacters);
        })
    });
</script>