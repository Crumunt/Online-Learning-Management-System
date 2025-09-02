<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Router.php';
require_once __DIR__ . '/../app/Controller.php';
require_once __DIR__ . '/../app/Model.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helper.php';

spl_autoload_register(function ($class) {
    $basePaths = [
        __DIR__ . '/../controllers/',
        __DIR__ . '/../models/'
    ];

    foreach ($basePaths as $basePath) {
        // Try to find the class file recursively
        $file = findClassFile($basePath, $class);
        if ($file && file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

/**
 * Recursively search for a class file in the given directory and its subdirectories
 *
 * @param string $dir The directory to search in
 * @param string $className The class name to find
 * @return string|null The full path to the class file if found, null otherwise
 */
function findClassFile(string $dir, string $className): ?string
{
    // First, try the direct path (class file directly in the base directory)
    $directFile = $dir . $className . '.php';
    if (file_exists($directFile)) {
        return $directFile;
    }

    // If not found directly, search recursively in subdirectories
    if (!is_dir($dir)) {
        return null;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $filename = $file->getBasename('.php');
            if ($filename === $className) {
                return $file->getPathname();
            }
        }
    }

    return null;
}

$router = new Router($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// AUTH
$router->get('/login', ['AuthController', 'login']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/register', ['AuthController', 'register']);
$router->post('/register', ['AuthController', 'register']);
$router->get('/logout', ['AuthController', 'logout']);


// web routes similar to Laravel!
$router->get('/admin', ['AdminDashboardController', 'index']);

// ADMIN - INSTRUCTOR
$router->get('/admin/instructor', ['AdminInstructorController', 'index']);
$router->get('/admin/instructor/create', ['AdminInstructorController', 'create']);
$router->post('/admin/instructor/create', ['AdminInstructorController', 'store']);
$router->get('/admin/instructor/show/(\d+)', ['AdminInstructorController', 'show']);
$router->get('/admin/instructor/update/(\d+)', ['AdminInstructorController', 'edit']);
$router->post('/admin/instructor/update', ['AdminInstructorController', 'update']);
$router->post('/admin/instructor/delete', ['AdminInstructorController', 'destroy']);


// ADMIN - COURSE
$router->get('/admin/courses', ['CourseManagementController', 'index']);
$router->get('/admin/courses/show/(\d+)', ['CourseManagementController', 'show']);
$router->get('/admin/courses/update/(\d+)', ['CourseManagementController', 'edit']);
$router->post('/admin/course/update', ['CourseManagementController', 'update']);
$router->post('/admin/course/delete', ['CourseManagementController', 'destroy']);


// ADMIN - STUDENT
$router->get('/admin/student', ['AdminStudentController', 'index']);
$router->get('/admin/student/create', ['AdminStudentController', 'create']);
$router->post('/admin/student/create', ['AdminStudentController', 'store']);
$router->get('/admin/student/show/(\d+)', ['AdminStudentController', 'show']);
$router->post('/admin/student/delete', ['AdminStudentController', 'destroy']);
$router->get('/admin/student/update/(\d+)', ['AdminStudentController', 'edit']);
$router->post('/admin/student/update', ['AdminStudentController', 'update']);

// STUDENT
$router->get('/student', ['StudentController', 'index']);
$router->get('/student/courses', ['StudentController', 'courses']);
$router->post('/student/enroll', ['StudentController', 'create']);
$router->post('/student/removeEnrollment', ['StudentController', 'destroy']);
$router->get('/student/course/(\d+)/show', ['StudentController', 'showCourse']);

// INSTRUCTOR
$router->get('/instructor', ['InstructorController', 'index']);
$router->get('/instructor/courses', ['InstructorController', 'courses']);
$router->get('/instructor/students', ['InstructorController', 'students']);
$router->get('/instructor/course/create', ['InstructorController', 'create']);
$router->post('/instructor/course/create', ['InstructorController', 'storeCourse']);
$router->get('/instructor/courses/show/(\d+)', ['InstructorController', 'showContent']);
$router->get('/instructor/courses/update/(\d+)', ['InstructorController', 'showEdit']);
$router->post('/instructor/courses/update', ['InstructorController', 'update']);
$router->get('/instructor/course/(\d+)/content/create', ['InstructorController', 'createContent']);
$router->post('/instructor/course/content/create', ['InstructorController', 'storeContent']);
$router->get('/instructor/course/content/edit/(\d+)', ['InstructorController', 'showEditContent']);
$router->post('/instructor/course/content/update', ['InstructorController', 'updateContent']);
$router->post('/instructor/course/content/delete', ['InstructorController', 'destroyCourseContent']);


$router->get('/pdf-serve', ['CourseController', 'serve']);

$router->dispatch();