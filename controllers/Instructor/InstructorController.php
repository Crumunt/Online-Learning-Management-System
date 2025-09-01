<?php
declare(strict_types=1);

class InstructorController extends Controller
{
    protected $requiresAuth = true;
    protected $guestOnly = false;
    protected $allowedRoles = ['instructor'];

    protected Course $course;
    protected User $user;

    public function __construct()
    {
        parent::__construct();

        $this->course = new Course();
        $this->user = new User();
    }

    public function index()
    {
        $instructor_id = $_SESSION['user_id'] ?? null;
        $user_role = $_SESSION['user_role'] ?? null;
        if (!$instructor_id) {
            $this->redirect('/login');
        }

        if (!isset($user_role) || $user_role !== 'instructor') {
            $this->redirect('/login');
        }

        $course_view_result = $this->user->all('course_view', 'COUNT(*) AS course_count, SUM(course_content) AS learning_material_count', ['instructor_id' => $instructor_id, 'status' => 'approved'])->fetch_all(MYSQLI_ASSOC);
        $course_count = 0;
        $learning_material_count = 0;
        if (!empty($course_view_result)) {
            $course_count = $course_view_result[0]['course_count'] ?? 0;
            $learning_material_count = $course_view_result[0]['learning_material_count'] ?? 0;
        }


        $student_data = $this->user->all('courses_enrolled', 'student_id, student_name, enrolled_at, title', ['instructor_id' => $instructor_id])->fetch_all(MYSQLI_ASSOC);
        $student_count = count($student_data);

        $this->view('dashboard/instructor', compact('course_count', 'student_count', 'learning_material_count', 'student_data'));
    }

    public function courses()
    {
        $instructor_id = $_SESSION['user_id'] ?? null;
        $data = $this->course->all('course_view', '*', ['instructor_id' => $instructor_id]);

        if ($this->isAjaxRequest()) {
            $datas = [];

            while ($row = $data->fetch_assoc()) {
                array_push($datas, $row);
            }
            echo json_encode($datas);
            return;
        }
        $this->view('courses/index', compact('data'));
    }

    public function students()
    {
        $instructor_id = $_SESSION['user_id'] ?? null;
        $data = $this->course->all('courses_enrolled', '*', ['instructor_id' => $instructor_id]);

        if ($this->isAjaxRequest()) {
            $datas = [];

            while ($row = $data->fetch_assoc()) {
                array_push($datas, $row);
            }
            echo json_encode($datas);
            return;
        }

        $this->view('students/index', compact('data'));
    }

    public function create()
    {
        $this->view('courses/create');
    }

    public function storeCourse()
    {
        try {
            $instructor_id = $_SESSION['user_id'] ?? null;

            if (!$instructor_id) {
                http_response_code(401);
                echo json_encode(['error' => 'Authentication required']);
                exit;
            }

            $requiredFields = ['course_name', 'short_description'];
            $allowedFields = ['course_name', 'short_description', 'status', 'difficulty'];
            $data = [];

            // * VALIDATE REQUIRED FIELDS
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new InvalidArgumentException("Field '{$field}' is required");
                }
            }

            // * VALIDTE AND SANITIZE REQUIRED FIELDS
            foreach ($allowedFields as $field) {
                if (isset($_POST[$field]) && !empty($_POST[$field])) {
                    $data[$field] = $this->validateAndSanitizeField($field, $_POST[$field]);
                }
            }

            // * IF STATUS IS NOT SENT SA POSTTTT
            if (!isset($data['status'])) {
                $data['status'] = 'pending';
            }
            $this->validateCourseData($data);
            $data['instructor_id'] = $instructor_id;

            $this->course->beginTransaction();

            $courseId = $this->course->create($data, 'courses');

            if (!$courseId) {
                throw new Exception("Failed to create course");
            }

            $this->course->commit();
        } catch (InvalidArgumentException $e) {
            $this->course->rollback();
            http_response_code(400); // Bad Request
            echo json_encode([
                'error' => 'Validation Error',
                'message' => $e->getMessage()
            ]);
            exit;
        } catch (Exception $e) {
            $this->course->rollback();
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the course'
            ]);
            exit;
        }
    }

    private function validateCourseData($data, $courseId = null)
    {
        if (!$courseId) {
            $courses = $this->course->all('courses', 'course_name', ['course_name' => $data['course_name']])->num_rows;
        } else {
            $courses = $this->course->all('courses', 'course_name', ['course_name' => $data['course_name']], ['id' => $courseId])->num_rows;

        }

        if ($courses > 0) {
            throw new InvalidArgumentException('A course with this title already exists');
        }
    }

    public function show($courseId)
    {
        try {
            $instructor_id = $_SESSION['user_id'] ?? null;
            if (!$instructor_id) {
                throw new Exception('Invalid user.');
            }

            $data = null;
            $courseDetails = null;
            $courseContent = [];

            $courseResult = $this->course->find((int) $courseId);
            $data = ($courseResult && $courseResult->num_rows > 0) ? $courseResult->fetch_assoc() : null;

            $courseDetailsResult = $this->course->all('course_view', 'enrollments, course_content', ['instructor_id' => $instructor_id, 'id' => $courseId]);
            $courseDetails = ($courseDetailsResult && $courseDetailsResult->num_rows > 0) ? $courseDetailsResult->fetch_assoc() : null;

            $courseContentResult = $this->course->all('course_content', 'id, title, file_type', ['course_id' => $courseId]);
            if ($courseContentResult && $courseContentResult->num_rows > 0) {
                while ($row = $courseContentResult->fetch_assoc()) {
                    $courseContent[] = $row;
                }
            }

            $this->view('courses/content/show', compact('data', 'courseDetails', 'courseContent'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function showEdit($courseId)
    {
        $instructor_id = $_SESSION['user_id'] ?? null;
        $data = $this->course->find((int) $courseId)->fetch_assoc();

        $this->view('courses/edit', compact('data'));
    }

    public function showContent($courseId)
    {
        $instructorId = $_SESSION['user_id' ?? null];
        $userRole = $_SESSION['user_role'] ?? null;

        if (!$courseId || !$instructorId) {
            throw new InvalidArgumentException('Invalid user or course identifier.');
        }

        // CHECK IF HAS COURSE
        $hasRecord = $this->course->all('course_view', '*', ['id' => $courseId, 'instructor_id' => $instructorId])->fetch_assoc();

        if (!$hasRecord) {
            throw new Exception('Course not found', 404);
        }

        $contentResult = $this->course->all('course_content', '*', ['course_id' => $courseId]);
        $contentData = $contentResult ? $contentResult->fetch_all(MYSQLI_ASSOC) : [];

        $courseData = $this->user->processCourseData($hasRecord);

        $contentView = $this->user->processContentView($contentData);


        $this->view('courses/content/show', compact('courseData', 'userRole', 'contentView'));
    }


    public function showEditContent($contentId)
    {
        $contentData = $this->user->all('course_content', '*', ['id' => $contentId])->fetch_assoc();

        if (empty($contentData)) {
            throw new InvalidArgumentException('Course Content was not found.');
        }
        if ($this->isAjaxRequest()) {
            $data = [
                'id' => $contentId,
                'contentTitle' => $contentData['title'],
                'contentData' => htmlspecialchars_decode(stripslashes($contentData['content'])),
                'file_name' => $contentData['file_path'],
                'file_size' => $contentData['file_size'] ?? 0,
                'status' => $contentData['status']
            ];

            echo json_encode($data);
            exit;
        }

        $this->view('courses/content/edit');
    }

    public function update()
    {
        try {

            $requiredFields = ['course_id', 'title', 'description'];
            $allowedFields = ['title', 'description', 'status', 'difficulty'];
            $data = [];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new InvalidArgumentException("Field '{$field}' is required");
                }
            }

            foreach ($allowedFields as $field) {
                if (isset($_POST[$field]) && !empty($_POST[$field])) {
                    $data[$field] = $this->validateAndSanitizeField($field, $_POST[$field]);
                }
            }

            if (!isset($data['status'])) {
                $data['status'] = 'pending';
            }

            if (!isset($data['difficulty'])) {
                $data['difficulty'] = 'beginner';
            }

            $course_id = $this->validateAndSanitizeField('course_id', $_POST['course_id']);
            $this->validateCourseData($data, $course_id);

            $this->course->beginTransaction();

            $courseId = $this->course->update(['id' => $course_id], $data, 'courses');

            if (!$course_id) {
                throw new Exception("Failed to update course");
            }

            $this->course->commit();
        } catch (InvalidArgumentException $e) {
            $this->course->rollback();
            http_response_code(400); // Bad Request
            echo json_encode([
                'error' => 'Validation Error',
                'message' => $e->getMessage()
            ]);
            exit;
        } catch (Exception $e) {
            $this->course->rollback();
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while updating the course'
            ]);
            exit;
        }
    }

    public function createContent($courseId)
    {
        $this->view('courses/content/create', compact('courseId'));
    }

    public function storeContent()
    {
        try {

            $requiredFields = ['title', 'content', 'file_type', 'course_id'];
            $allowedFields = ['title', 'content', 'file_type', 'status', 'course_id'];
            $data = [];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new InvalidArgumentException("Field '{$field}' is required");
                }
            }

            foreach ($allowedFields as $field) {
                if (isset($_POST[$field]) && !empty($_POST[$field])) {
                    $data[$field] = $this->validateAndSanitizeField($field, $_POST[$field]);
                }
            }

            // VALIDATE AND UPLOAD FILE TO STORAGE
            $file = $this->storeFiles();
            $data['file_name'] = $file['file_name'];
            $data['file_size'] = $file['file_size'];


            if (!isset($data['status'])) {
                $data['status'] = 'pending';
            }

            $this->course->beginTransaction();

            $this->course->create($data, 'course_content');

            $this->course->commit();

        } catch (InvalidArgumentException $e) {
            $this->course->rollback();
            http_response_code(400); // Bad Request
            echo json_encode([
                'error' => 'Validation Error',
                'message' => $e->getMessage()
            ]);
            exit;
        } catch (Exception $e) {
            $this->course->rollback();
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the course content'
            ]);
            exit;
        }
    }

    private function storeFiles()
    {

        $instructor_name = $_SESSION['user_name'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['module_file'])) {
            throw new InvalidArgumentException('No file uploaded or invalid request method.');
        }

        $file = $_FILES['module_file'];
        $fileSize = $file['size'];

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {

            $phpFileUploadErrors = array(
                0 => 'There is no error, the file uploaded with success',
                1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            );

            throw new InvalidArgumentException('File upload error: ' . $phpFileUploadErrors[$file['error']]);
        }

        // Validate file size (e.g., 5MB limit)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new InvalidArgumentException('File too large.');
        }

        // Validate file type
        $allowedTypes = ['application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            throw new InvalidArgumentException('Invalid file type.');
        }

        // Sanitize filename and prevent collisions
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = bin2hex(random_bytes(16)) . '.' . $extension;

        // * UPLOAD FILES BASED SA NAME NI BOSSING BAKIT BA TRIP Q NGA EH PARA ORGANIZED 🌟
        $targetDirectory = __DIR__ . "/../../storage/uploads/{$instructor_name}/";
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true); // More restrictive permissions
        }

        $targetPath = $targetDirectory . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new InvalidArgumentException('Failed to store uploaded file.');
        }

        return [
            'file_name' => $safeName,
            'file_size' => $fileSize
        ];
    }

    public function updateContent()
    {
        try {
            $requiredFields = ['title', 'content', 'course_id'];
            $allowedFields = ['title', 'content', 'status'];
            $data = [];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new InvalidArgumentException("Field '{$field}' is required");
                }
            }

            foreach ($allowedFields as $field) {
                if (isset($_POST[$field]) && !empty($_POST[$field])) {
                    $data[$field] = $this->validateAndSanitizeField($field, $_POST[$field]);
                }
            }

            if (
                $_SERVER['REQUEST_METHOD'] === 'POST' &&
                isset($_FILES['module_file']) &&
                $_FILES['module_file']['error'] === UPLOAD_ERR_OK
            ) {
                $file = $this->storeFiles();
                $data['file_path'] = $file['file_name'];
                $data['file_size'] = $file['file_size'];
            }

            $contentId = $_POST['course_id'];

            $this->course->beginTransaction();

            $this->course->update(['id' => $contentId], $data, 'course_content');

            $this->course->commit();

        } catch (InvalidArgumentException $e) {
            $this->course->rollback();
            http_response_code(400); // Bad Request
            echo json_encode([
                'error' => 'Validation Error',
                'message' => $e->getMessage(),
                'data' => $_FILES,
            ]);
            exit;
        } catch (Exception $e) {
            $this->course->rollback();
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the course content'
            ]);
            exit;
        }
    }

    public function destroyCourseContent()
    {
        try {
            header('Content-Type: application/json');

            $data = json_decode(file_get_contents("php://input"), true);
            $content_id = $data['table_id'] ?? null;

            $this->course->beginTransaction();
            $this->course->delete(['id' => $content_id], 'course_content');
            $this->course->commit();

            http_response_code(200);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $this->course->rollback();

            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function validateAndSanitizeField($field, $value)
    {
        $value = trim($value);

        switch ($field) {
            case 'title':
                if (strlen($value) > 100) {
                    throw new InvalidArgumentException("Title cannot exceed 100 characters");
                }
                if (strlen($value) < 3) {
                    throw new InvalidArgumentException("Title must be at least 3 characters");
                }
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            case 'description':
            case 'content':
                if (strlen($value) > 250) {
                    throw new InvalidArgumentException("Description cannot exceed 250 characters");
                }
                if (strlen($value) < 10) {
                    throw new InvalidArgumentException("Description must be at least 10 characters");
                }
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            case 'status':
                $allowedStatuses = ['pending', 'approved', 'rejected', 'active', 'inactive', 'archived', 'draft'];
                if (!in_array($value, $allowedStatuses)) {
                    throw new InvalidArgumentException("Invalid status value");
                }
                return $value;
            case 'difficulty':
                $allowedDifficulties = ['beginner', 'intermediate', 'advanced'];
                if (!in_array($value, $allowedDifficulties)) {
                    throw new InvalidArgumentException('Invalid difficulty value');
                }
                return $value;
            default:
                return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }

}