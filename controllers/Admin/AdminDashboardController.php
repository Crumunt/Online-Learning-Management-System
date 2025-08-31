<?php
declare(strict_types=1);

class AdminDashboardController extends Controller
{
    protected Admin $adminModel;

    protected $requiresAuth = true;

    protected $allowedRoles = ['admin'];

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new Admin();
    }

    public function index()
    {
        $data = [];
        $card_data = array_fill_keys(['admin_count', 'student_count', 'instructor_count', 'total_courses', 'total_materials'], 0);
        $valid_roles = ['admin', 'instructor', 'student'];
        try {
            $result = $this->adminModel->all();
            $course_result = $this->adminModel->all('course_view', 'COUNT(id) as total_courses, SUM(course_content) AS total_materials', ['status' => 'approved'])->fetch_assoc();
            if (!$result || !$course_result) {
                throw new Exception('Failed to fetch user data');
            }


            $card_data['total_courses'] = $course_result['total_courses'];
            $card_data['total_materials'] = $course_result['total_materials'];

            // ITERATE OVER RESULTS AND STORE AS INDEXED ARRAY
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;

                if (in_array($row['role'], $valid_roles)) {
                    $card_data[$row['role'] . '_count']++;
                }
            }

            $enrollment_data = json_encode($this->getWeeklyEnrollmentData());

            $this->view('dashboard/admin', compact('data', 'card_data', 'enrollment_data'));
        } catch (Exception $e) {
            $this->view('dashboard/admin', compact('data', 'card_data'));
        }
    }

    private function getWeeklyEnrollmentData()
    {
        $enroll_result = $this->adminModel->all('student_enrollments_by_day');

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
        $admin = $this->adminModel->find((int) $id);
        if (!$admin) {
            header("HTTP/1.0 404 Not Found");
            // echo "Admin Not Found";
            return;
        }
        $this->view('admin/show', compact('admin'));
    }
}