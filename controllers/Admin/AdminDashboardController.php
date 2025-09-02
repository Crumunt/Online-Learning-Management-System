<?php
declare(strict_types=1);

class AdminDashboardController extends Controller
{
    protected User $user;
    protected Course $course;
    protected $requiresAuth = true;

    protected $allowedRoles = ['admin'];

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->course = new Course();
    }

    public function index()
    {
        $data = [];
        $cardData = array_fill_keys(['admin_count', 'student_count', 'instructor_count', 'total_courses', 'total_materials'], 0);
        $valid_roles = ['admin', 'instructor', 'student'];
        try {
            $result = $this->user->all('user_view');
            $course_result = $this->user->all('course_view', 'COUNT(id) as total_courses, SUM(course_content) AS total_materials', ['status' => 'approved'])->fetch_assoc();
            if (!$result || !$course_result) {
                throw new Exception('Failed to fetch user data');
            }


            $cardData['totalCourses'] = $course_result['total_courses'];
            $cardData['totalMaterials'] = $course_result['total_materials'];

            // ITERATE OVER RESULTS AND STORE AS INDEXED ARRAY
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;

                if (in_array($row['role'], $valid_roles)) {
                    $cardData[$row['role'] . '_count']++;
                }
            }

            $enrollment_data = json_encode($this->getWeeklyEnrollmentData());
            $latestEnrollments = $this->getLatestEnrollments();
            $topPerformingCourses = $this->getTopPerformingCourses();
            $this->view('dashboard/admin', compact('data', 'cardData', 'enrollment_data', 'latestEnrollments', 'topPerformingCourses'));
        } catch (Exception $e) {
            $this->view('dashboard/admin', compact('data', 'cardData'));
        }
    }

    private function getLatestEnrollments()
    {
        $data = $this->user->all('courses_enrolled', 'student_name, title, instructor_name, enrolled_at', null, null, 'enrolled_at DESC', 5)->fetch_all(MYSQLI_ASSOC);
        return [
            'headers' => [
                'Student',
                'Course',
                'Instructor',
                'Enrolled'
            ],
            'columns' => ['student_name', 'title', 'instructor_name', 'enrolled_at'],
            'tableData' => $data
        ];
    }

    private function getTopPerformingCourses()
    {
        $data = $this->course->all('course_view', 'title, difficulty, instructor_name, status, enrollments', null, null, 'enrollments DESC', 5)->fetch_all(MYSQLI_ASSOC);
        return [
            'headers' => [
                'Instructor',
                'Course',
                'Difficulty',
                'Enrollments',
                'Status'
            ],
            'columns' => ['instructor_name', 'title', 'difficulty', 'enrollments', 'status'],
            'tableData' => $data
        ];
    }

    private function getWeeklyEnrollmentData()
    {
        $enroll_result = $this->user->all('student_enrollments_by_day');

        $enrollments = array_fill_keys(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], 0);
        while ($row = $enroll_result->fetch_assoc()) {
            $enrollments[$row['day_name']] = (int) $row['daily_count'];
        }

        return [
            'labels' => array_keys($enrollments),
            'data' => array_values($enrollments)
        ];
    }

    public function show($id)
    {
        $admin = $this->user->find((int) $id);
        if (!$admin) {
            header("HTTP/1.0 404 Not Found");
            // echo "Admin Not Found";
            return;
        }
        $this->view('admin/show', compact('admin'));
    }
}