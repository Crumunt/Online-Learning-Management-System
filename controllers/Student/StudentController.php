<?php

declare(strict_types=1);

class StudentController extends Controller
{

    protected $allowedRoles = ['student'];

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

        $student_id = $_SESSION['user_id'];

        $data = $this->course->getAvailableCourses($student_id);

        $this->view('dashboard/student', compact('data'));
    }


    public function courses()
    {

        $student_id = $_SESSION['user_id'];

        $data = $this->course->all('courses_enrolled', '*', ['student_id' => $student_id]);

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
        $enrollmentResult = $this->course->all('courses_enrolled', 'instructor_name, title, course_id, enrolled_at', [
            'student_id' => $studentId,
            'course_id' => $courseId
        ]);

        $hasRecord = $enrollmentResult ? $enrollmentResult->fetch_assoc() : null;

        if (!$hasRecord) {
            throw new InvalidArgumentException('Student is not enrolled in this course.');
        }

        // Fetch course content
        $contentResult = $this->course->all('course_content', '*', ['course_id' => $courseId]);
        $contentData = $contentResult ? $contentResult->fetch_all(MYSQLI_ASSOC) : [];

        $courseData = $this->course->processCourseData($hasRecord);
        $contentView = $this->course->processContentView($contentData);


        $this->view('courses/content/show', compact('courseData', 'contentView', 'userRole'));
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
            $this->user->beginTransaction();

            $this->user->create($enrollment_data, 'enrollments');

            $this->user->commit();

            echo json_encode(['success' => true, 'message' => 'Enrolled successfully']);

        } catch (Exception $e) {
            $this->user->rollback();
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
            $this->user->beginTransaction();

            $this->user->delete(['student_id' => $student_id, 'course_id' => $course_id], 'enrollments');

            $this->user->commit();

            echo json_encode(['success' => true, 'message' => 'Enrolled successfully']);

        } catch (Exception $e) {
            $this->user->rollback();
            throw $e;
        }
    }

}