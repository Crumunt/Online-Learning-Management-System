<?php
$status = [
    'approved' => 'primary',
    'pending' => 'success',
    'rejected' => 'danger'
];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-heading">
                <div class="row">
                    <div class="col-md-8">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/<?= $userRole ?>/courses">Courses</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/student/course/placeholder"></a>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-4 text-right">
                        <!-- Role-based action buttons -->
                        <div id="instructor-actions" class="instructor-only" style="display: block;">
                            <a href="/<?= $userRole ?>/courses" class="btn btn-outline-secondary btn-sm mr-2">
                                <i class="fa fa-arrow-left mr-1"></i>Back to Course
                            </a>
                            <?php if (in_array($userRole ?? null, ['instructor', 'admin'])): ?>
                                <div class="btn-group">
                                    <a href="/<?= $userRole ?>/courses/update/<?= $courseData['course_id'] ?>"
                                        class="btn btn-outline-primary btn-sm mr-2">
                                        <i class="fa fa-edit mr-1"></i>Edit Course
                                    </a>
                                    <?php if ($userRole === 'instructor'): ?>
                                        <a href="/instructor/course/<?= $courseData['course_id'] ?>/content/create"
                                            class="btn btn-success btn-sm mr-2">
                                            <i class="fa fa-edit mr-1"></i>Create Content
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="student-actions" class="student-only" style="display: none;">
                            <a href="/student/courses" class="btn btn-outline-secondary btn-sm mr-2">
                                <i class="fa fa-arrow-left mr-1"></i>Back to Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Course Information -->
        <div class="col-lg-8">
            <?php if (in_array($userRole, ['instructor', 'admin'])): ?>
                <!-- Course Information -->
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-info-circle mr-2"></i>Course Information</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Course Title</label>
                                    <div class="form-control-static">
                                        <h4 class="text-navy mb-2"><?= $courseData['title'] ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <div class="form-control-static">
                                        <p class="text-muted">
                                            <?= $courseData['description'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="form-control-static">
                                        <span
                                            class="badge badge-<?= $status[$courseData['status']] ?? 'approved' ?> badge-pill px-3 py-2">
                                            <i
                                                class="fa fa-check-circle mr-1"></i><?= ucfirst($courseData['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Created Date</label>
                                    <div class="form-control-static">
                                        <span class="text-muted">
                                            <i class="fa fa-calendar mr-1"></i><?= $courseData['created_at'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <!-- Content Description -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-info-circle mr-2"></i>Content Overview</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="content-description">
                        <?= htmlspecialchars_decode(stripslashes($contentView['contentToShow']['content'] ?? 'No content has yet to be made.')) ?>
                    </div>
                </div>
            </div>

            <!-- PDF Icon Viewer -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-file-pdf mr-2"></i>Course Material</h5>
                </div>
                <div class="ibox-content">
                    <?php if (!empty($contentView['contentToShow'])): ?>
                        <div class="pdf-icon-viewer-container text-center py-5">
                            <div class="pdf-preview-card" onclick="openFullPDFViewer()">
                                <div class="pdf-info">
                                    <h5 class="mb-2"><?= $contentView['contentToShow']['title'] ?>.pdf</h5>
                                </div>
                            </div>

                            <div class="pdf-actions mt-4">
                                <button class="btn btn-primary btn-lg mr-3" onclick="openFullPDFViewer()">
                                    <i class="fa fa-book-open mr-2"></i>Open PDF Viewer
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa fa-info-circle fa-2x d-block mb-3"></i>
                            <p class="lead mb-0">No content available to display.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Course Statistics -->
            <?php if (in_array($userRole, ['instructor', 'admin'])): ?>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5><i class="fa fa-chart-bar mr-2"></i>Course Statistics</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row text-center">
                            <div class="col-12 mb-3">
                                <div class="widget style1 navy-bg">
                                    <div class="row">
                                        <div class="col-4">
                                            <i class="fa fa-users fa-2x"></i>
                                        </div>
                                        <div class="col-8 text-right">
                                            <span class="h2 font-bold"><?= $courseData['student_count'] ?></span>
                                            <div class="font-bold">Students</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-4">
                                            <i class="fa fa-play-circle fa-2x"></i>
                                        </div>
                                        <div class="col-8 text-right">
                                            <span class="h2 font-bold"><?= $courseData['material_count'] ?></span>
                                            <div class="font-bold">Contents</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Navigation -->
            <div class="ibox">
                <div class="ibox-title">
                    <h5><i class="fa fa-navigation mr-2"></i>Content Navigation</h5>
                </div>
                <div class="ibox-content">
                    <div class="content-list">
                        <h6 class="text-muted mb-2">All Course Content:</h6>
                        <div class="list-group list-group-flush">
                            <?php if ($contentView['hasContent']): ?>
                                <?php foreach ($contentView['courseContentList'] as $content): ?>
                                    <?php if ($contentView['currentTitle'] === $content['title']): ?>
                                        <div class="list-group-item active d-flex justify-content-between align-items-center">
                                            <span><i class="fa fa-file-pdf mr-2"></i><?= $content['title'] ?></span>
                                            <div class="group">
                                                <!-- <i class="fa fa-check-circle text-primary"></i> -->
                                                <?php if (in_array($userRole, ['instructor'])): ?>
                                                    <div class="btn-group">
                                                        <button class="btn btn-danger deleteBtn mx-1"
                                                            data-content-id="<?= $content['id'] ?>">
                                                            <i class="fa fa-trash mr-1"></i> Delete
                                                        </button>
                                                        <a href="/instructor/course/content/edit/<?= $contentView['contentToShow']['id'] ?>"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fa fa-edit mr-1"></i>Edit Content
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <a href="?nextContent=<?= $content['id'] ?>"
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <span><i class="fa fa-file-pdf mr-2"></i><?= $content['title'] ?></span>
                                            <i class="fa fa-clock text-muted"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="list-group-item text-center text-muted">
                                    <i class="fa fa-info-circle mr-2"></i>No content has been published.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function safeJsonEncode($value, $default = null)
{
    return json_encode($value ?? $default);
}
?>

<style>
    .breadcrumb {
        background: transparent;
        padding: 8px 0;
        margin: 0;
    }

    .breadcrumb-item a {
        color: #1ab394;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #676a6c;
    }
</style>

<script>

    const CONFIG = {
        ENDPOINTS: {
            DELETE: '/instructor/course/content/delete',
        },
        MESSAGES: {
            DELETE_SUCCESS: 'Content deleted successfully!',
            DELETE_CONFIRM: 'Are you sure you want to delete this course content?'
        }
    };

    function openFullPDFViewer() {
        const instructor = <?= safeJsonEncode($courseData['instructor']) ?>;
        const file = <?= safeJsonEncode($contentView['filename']) ?>;

        // console.log(instructor, file);
        const url = `/pdf-serve?instructor=${encodeURIComponent(instructor)}&file=${encodeURIComponent(file)}`;
        window.open(url, '_blank')
    }

    $(document).on('click', '.deleteBtn', function () {
        let userId = $(this).data('content-id');
        const isTable = false;
        deleteEntity(userId, CONFIG, isTable);
        window.location.href = '?'
    });
</script>