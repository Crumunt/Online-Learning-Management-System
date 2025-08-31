<?php

declare(strict_types=1);

class StudentController extends Controller
{

    protected $allowedRoles = ['student'];

    protected Student $studentModel;
    protected Course $courseModel;

    public function __construct()
    {
        parent::__construct();
        $this->studentModel = new Student();
        $this->courseModel = new Course();
    }

    public function index()
    {

        $student_id = $_SESSION['user_id'];
        // $courses_enrolled = $this->courseModel->all('courses_enrolled', 'course_id', ['student_id' => $student_id])->fetch_assoc();
        // $courses = $this->courseModel->all('courses_enrolled', '*', ['course_status' => 'approved']);

        // $data = [];
        // foreach ($courses as $course) {
        //     if (!in_array($course['course_id'], $courses_enrolled)) {
        //         array_push($data, $course);
        //     }
        // }
        $data = $this->getAvailableCourses($student_id);

        $this->view('dashboard/student', compact('data'));
    }

    private function getAvailableCourses($student_id)
    {
        // GET ALL COURSES KUNG SAAN NAKA ENROLL SI STUDENT
        $enrolled_result = $this->courseModel->all('courses_enrolled', 'course_id', ['student_id' => $student_id]);
        $enrolled_course_ids = [];
        while ($row = $enrolled_result->fetch_assoc()) {
            $enrolled_course_ids[] = $row['course_id'];
        }

        // KAPAG WALA LAMAN RETURN LAHAT NG AVAILABLE COURSES
        if (empty($enrolled_course_ids)) {
            $courses = $this->courseModel->all('course_view', 'DISTINCT *', ['status' => 'approved'])->fetch_all(MYSQLI_ASSOC);
            $unique_courses = [];
            foreach ($courses as $course) {
                $unique_courses[$course['id']] = [
                    'id' => $course['id'],
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'instructor_name' => $course['instructor_name']
                ];
            }
            return $unique_courses;
        }

        // FEETCH LAHAT NG COURSES NA APPROVED YUNG STATS
        $courses_result = $this->courseModel->all('course_view', '*', ['status' => 'approved']);

        // * FLIPS THE ARRAY, BASICALLY YUNG KEYS LIKE 'course_id' ETC IS MAGIGING VALUE AND YUNG VALUE YUNG MAGIGING KEYS
        $enrolled_lookup = array_flip($enrolled_course_ids);
        $available_courses = [];

        while ($course = $courses_result->fetch_assoc()) {
            $course_id = $course['id'];

            // * SKIP IF NASA ARRAY NA YUNG ID NG COURSE
            if (!isset($enrolled_lookup[$course_id]) && !isset($available_courses[$course_id])) {
                $available_courses[$course_id] = $course; // Use course_id as key
            }
        }

        $available_courses = array_values($available_courses);

        return $available_courses;
    }

    public function courses()
    {

        $student_id = $_SESSION['user_id'];

        $data = $this->courseModel->all('courses_enrolled', '*', ['student_id' => $student_id]);

        $this->view('students/courses', compact('data'));

    }

    public function showCourse($courseId)
    {
        $studentId = $_SESSION['user_id'] ?? null;
        $userRole = $_SESSION['user_role'] ?? null;

        if (!$studentId || !$courseId) {
            throw new InvalidArgumentException('Invalid user or course identifier.');
        }

        // Check enrollment
        $enrollmentResult = $this->courseModel->all('courses_enrolled', 'instructor_name, title, course_id, enrolled_at', [
            'student_id' => $studentId,
            'course_id' => $courseId
        ]);

        $hasRecord = $enrollmentResult ? $enrollmentResult->fetch_assoc() : null;

        if (!$hasRecord) {
            throw new InvalidArgumentException('Student is not enrolled in this course.');
        }

        // Fetch course content
        $contentResult = $this->courseModel->all('coursecontent', '*', ['course_id' => $courseId]);
        $content_data = $contentResult ? $contentResult->fetch_all(MYSQLI_ASSOC) : [];

        $course_data = [
            'course_instructor' => $hasRecord['instructor_name'],
            'course_title' => $hasRecord['title'],
            'course_description' => $hasRecord['description'] ?? null,
            'course_created_at' => date('M d, Y', strtotime($hasRecord['created_at'] ?? $hasRecord['enrolled_at'])),
            'student_count' => $hasRecord['enrollments'] ?? 0,
            'material_count' => $hasRecord['course_content'] ?? 0,
            'course_status' => $hasRecord['status'] ?? null,
        ];


        $this->view('courses/content/show', compact('content_data', 'course_data'));
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $student_id = $_SESSION['user_id'];
        $courseId = $data['course_id'] ?? null;

        $enrollment_data = [
            'course_id' => $courseId,
            'student_id' => $student_id,
        ];

        try {
            $this->studentModel->beginTransaction();

            $this->studentModel->create($enrollment_data, 'enrollments');

            $this->studentModel->commit();

            echo json_encode(['success' => true, 'message' => 'Enrolled successfully']);

        } catch (Exception $e) {
            $this->studentModel->rollback();
            throw $e;
        }

        exit;
    }

    public function destroy()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $student_id = (int) $_SESSION['user_id'];
        $course_id = $data['course_id'] ?? null;

        try {
            $this->studentModel->beginTransaction();

            $this->studentModel->delete(['student_id' => $student_id, 'course_id' => $course_id], 'enrollments');

            $this->studentModel->commit();

            echo json_encode(['success' => true, 'message' => 'Enrolled successfully']);

        } catch (Exception $e) {
            $this->studentModel->rollback();
            throw $e;
        }
    }

}