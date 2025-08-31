<?php
if (true) {
    $pageMode = 'Edit';
    $contentTitle = 'dummy title';
    $content = 'dummy content';
    $contentStatus = 'approved';
} else {
    $pageMode = 'Create';
}
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-heading">
                <div class="row">
                    <div class="col-md-8">
                        <h2><i class="fa fa-<?= $pageMode === 'Edit' ? 'edit' : 'plus' ?> mr-2"></i><?= $pageMode ?>
                            Course Content</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/instructor">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/instructor/course">Courses</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/instructor/course/show/<?= $courseId ?>">Course Details</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <?= $pageMode ?> Content
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="/instructor/course/show/<?= $courseId ?>"" class=" btn btn-outline-secondary btn-sm
                            mr-2">
                            <i class="fa fa-arrow-left mr-1"></i>Back to Course
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="content-entry-form" enctype="multipart/form-data" data-endpoint="CREATE">
        <div class="row">
            <!-- Content Information -->
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-file-alt mr-2"></i>Content Information</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content-title">Content Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="content-title" name="title" required
                                        placeholder="Enter content title (e.g., Introduction to HTML)">
                                    <small class="form-text text-muted">Choose a clear, descriptive title for this
                                        content</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content-description">Content Description <span
                                            class="text-danger">*</span></label>
                                    <textarea id="tiny"
                                        placeholder="Provide a detailed description of what this content covers. Include learning objectives, key concepts, and any prerequisites."></textarea>
                                    <!-- <textarea class="form-control" id="content-description" name="content" rows="8"
                                        required
                                        placeholder="Provide a detailed description of what this content covers. Include learning objectives, key concepts, and any prerequisites."></textarea> -->
                                    <small class="form-text text-muted">Describe what students will learn from this
                                        content module</small>
                                </div>
                            </div>
                            <div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="content-type">Content Type</label>
                                    <select class="form-control" id="content-type" name="file_type">
                                        <option value="document" selected>Document (PDF)</option>
                                    </select>
                                    <small class="form-text text-muted">Select the type of content you're
                                        creating</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="content-status">Status</label>
                                    <select class="form-control" id="content-status" name="status">
                                        <option value="draft" selected>Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <small class="form-text text-muted">Control the visibility of this content</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-upload mr-2"></i>Module File</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="module-file">PDF Module File <span class="text-danger">*</span></label>
                                    <div class="file-upload-area">
                                        <div class="file-drop-zone" id="file-drop-zone"
                                            onclick="document.getElementById('module-file').click()">
                                            <div class="file-drop-content">
                                                <i class="fa fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Drop PDF file here or click to browse</h5>
                                                <p class="text-muted">Maximum file size: 5MB</p>
                                            </div>
                                        </div>
                                        <input type="file" class="form-control-file d-none" id="module-file"
                                            name="module_file" accept=".pdf" onchange="handleFileSelect(event)">
                                    </div>
                                    <small class="form-text text-muted">Upload the PDF file that students will access
                                        for this content module</small>
                                </div>

                                <!-- File Preview -->
                                <div id="file-preview" class="file-preview" style="display: none;">
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-file-pdf fa-2x text-danger mr-3"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" id="file-name">filename.pdf</h6>
                                                <small class="text-muted" id="file-size">File size</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="removeFile()">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        <button type="button" class="btn btn-outline-secondary mr-2" onclick="cancelContent()">
                            <i class="fa fa-times mr-1"></i>Cancel
                        </button>
                        <button type="submit" name="course_id" value="<?= $courseId ?>" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i>Save Content
                        </button>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fa fa-info-circle mr-1"></i>
                                Content will be saved as draft and can be published when ready.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HIDDEN INPUT -->
        <input type="hidden" id="hidden_id" name="course_id" value="">
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

    /* File Upload Styles */
    .file-upload-area {
        margin-bottom: 15px;
    }

    .file-drop-zone {
        border: 2px dashed #e5e6e7;
        border-radius: 5px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .file-drop-zone:hover {
        border-color: #1ab394;
        background-color: #f0fffe;
    }

    .file-drop-zone.dragover {
        border-color: #1ab394;
        background-color: #e8f9f8;
    }

    .file-drop-content i {
        display: block;
        margin: 0 auto 15px;
    }

    .file-preview {
        margin-top: 15px;
    }

    .content-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 3px;
        border: 1px solid #e7eaec;
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

    .alert-info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #31708f;
    }
</style>

<script src="/resources/js/plugins/tinymce/js/tinymce/tinymce.min.js"></script>

<script>

    const CONFIG = {
        ENDPOINTS: {
            CREATE: {
                FETCH: '/instructor/course/content/create',
                METHOD: 'POST'
            },
            UPDATE: {
                FETCH: '/instructor/course/content/update',
                METHOD: 'POST'
            }
        },
        MESSAGES: {
            CREATE: {
                SUCCESS: 'Course creation success!',
                ERROR: 'Upload failed. Please try again.',
            },
            UPDATE: {
                SUCCESS: 'Course update success!',
                ERROR: 'Update failed. Please try again.',
            }
        },
        TOAST: {
            ICON: {
                SUCCESS: 'success',
                WARNING: 'warning',
                ERROR: 'error'
            }
        }
    };


    $(document).ready(function () {
        tinymce.init({
            selector: 'textarea#tiny',
            setup: function (editor) {
                if (window.location.href.indexOf('course/content/edit') !== -1) {
                    editor.on('init', function () {
                        $.ajax({
                            url: '<?= $_SERVER['REQUEST_URI'] ?>',
                            method: 'GET',
                            success: function (data) {
                                var res = JSON.parse(data);

                                const decodedData = decodeHtml(res.contentData);
                                $('#content-entry-form').data('endpoint', 'UPDATE');
                                $('#hidden_id').val(res.id);
                                $('#content-title').val(res.contentTitle);
                                $('#content-status').val(res.status);
                                showFilePreview([{ filename: res.file_name, filesize: res.file_size }]);
                                editor.setContent(decodedData);
                            }
                        });
                    })
                }
            },
            height: 500,
            license_key: 'gpl',
            menubar: false,
            // Toolbar configuration
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat',

            // Content styling
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        function getContentId() {
            const urlParts = window.location.href.split('/');
            return urlParts[urlParts.length - 1];
        }

        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }

        $('#content-entry-form').submit(function (e) {
            e.preventDefault(); // Prevent default form submission

            const content = tinymce.get('tiny').getContent();

            let formData = new FormData(this);

            // * SET TINMYCE VAL
            formData.set('content', content);

            let fileInput = this.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                let file = fileInput.files[0];

                // Client-side validation (server-side validation is still required!)
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

                if (file.size > maxSize) {
                    alert('File size must be less than 5MB');
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert('Only JPEG, PNG, GIF, and PDF files are allowed');
                    return;
                }
            }

            const endpointKey = ($(this).data('endpoint') || '').toUpperCase();;
            const currentEndpoint = CONFIG.ENDPOINTS[endpointKey];
            $.ajax({
                url: currentEndpoint.FETCH,
                method: currentEndpoint.METHOD,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    // loader
                },
                success: function (res) {
                    generateToast(CONFIG.MESSAGES[endpointKey].SUCCESS, CONFIG.TOAST.ICON.SUCCESS);

                    setTimeout(() => {
                        window.location.href = '/instructor/course';
                    }, 3000);
                },
                error: function (error) {
                    var res = JSON.parse(error.responseText)
                    console.log(res)
                    var toastText = res.message;
                    generateToast(toastText, CONFIG.TOAST.ICON.ERROR, CONFIG.MESSAGES[endpointKey].ERROR);
                },
                complete: function () {
                    resetForm();
                    removeFile();
                }
            });
        });
    })


    // Cancel function
    function cancelContent() {
        if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
            // In a real application, redirect to course details
            alert('Redirecting to course...');
            window.location.href = '/instructor/course'
        }
    }

    // File handling functions
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            // Validate file type
            if (file.type !== 'application/pdf') {
                alert('Please select a PDF file only');
                event.target.value = '';
                return;
            }

            // Validate file size (50MB limit)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                event.target.value = '';
                return;
            }

            // Show file preview
            showFilePreview(file);
        }
    }

    function showFilePreview(file) {
        const preview = $('#file-preview');
        const fileName = $('#file-name');
        const fileSize = $('#file-size');
        if (file instanceof File) {
            fileName.text(file.name);
            fileSize.text(formatFileSize(file.size));
        } else {
            const file_data = file[0];
            fileName.text(file_data.filename);
            fileSize.text(formatFileSize(file_data.filesize));
        }
        preview.css('display', 'block');

        // Hide drop zone
        $('#file-drop-zone').css('display', 'none');
    }

    function removeFile() {
        const fileInput = $('#module-file');
        const preview = $('#file-preview');
        const dropZone = $('#file-drop-zone');
        const filePath = $('#file-path');


        console.log(fileInput)

        fileInput.val();
        filePath.val();
        preview.css('display', 'none');
        dropZone.css('display', 'block')
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Drag and drop functionality
    document.addEventListener('DOMContentLoaded', function () {
        const dropZone = document.getElementById('file-drop-zone');
        const fileInput = document.getElementById('module-file');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropZone.classList.add('dragover');
        }

        function unhighlight() {
            dropZone.classList.remove('dragover');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect({ target: { files: files } });
            }
        }

        // Form change detection
        let formChanged = false;
        const form = document.getElementById('content-entry-form');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(function (input) {
            input.addEventListener('change', function () {
                formChanged = true;
            });
        });

        // Warn about unsaved changes when leaving page
        window.addEventListener('beforeunload', function (e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });

        // Don't warn when form is submitted
        form.addEventListener('submit', function () {
            formChanged = false;
        });
    });
</script>