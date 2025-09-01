<?php
declare(strict_types=1);

class AdminInstructorController extends Controller
{
    protected Instructor $instructorModel;

    public function __construct()
    {
        parent::__construct();
        $this->instructorModel = new Instructor();
    }

    public function index()
    {
        $table = 'instructor_view';
        $data = $this->instructorModel->all($table, '*');

        if ($this->isAjaxRequest()) {
            $datas = [];

            while ($row = $data->fetch_assoc()) {
                array_push($datas, $row);
            }
            echo json_encode($datas);
            return;
        }

        $this->view('instructors/index', compact('data'));
    }

    public function create()
    {
        $this->view('admin/instructors/create');
    }

    public function store()
    {
        try {
            $this->validateAdminAccess();

            [$credentials, $user_details] = $this->validateStudentData();

            $tables = [
                'credentials' => 'users',
                'details' => 'user_details'
            ];
            $this->instructorModel->beginTransaction();

            $userId = $this->instructorModel->create($credentials, $tables['credentials']);

            if (!$userId) {
                throw new Exception("Failed to create user credentials");
            }

            $user_details['user_id'] = $userId;
            $this->instructorModel->create($user_details, $tables['details']);

            $this->instructorModel->commit();
        } catch (InvalidArgumentException $e) {
            http_response_code(422);  // * UNRPOCESSABLE ENTITY
            echo json_encode([
                'error' => 'Validation Error',
                'message' => $e->getMessage()
            ]);
            exit; //stops script para mag trigger yung error block nyeta
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the instructor.'
            ]);
            exit;
        }
    }

    public function show($instructor_id)
    {
        $data = $this->instructorModel->find((int) $instructor_id)->fetch_assoc();

        $this->view('admin/instructors/show', compact('data'));
    }

    public function edit($instructor_id)
    {
        $data = $this->instructorModel->find((int) $instructor_id)->fetch_assoc();
        $this->view('admin/instructors/update', compact('data'));
    }

    public function destroy()
    {
        try {

            $data = json_decode(file_get_contents("php://input"), true);
            $userId = $data['table_id'] ?? null;

            $this->instructorModel->beginTransaction();

            $this->instructorModel->delete(['id' => $userId], 'users');

            $this->instructorModel->commit();
            echo http_response_code(200);

        } catch (Exception $e) {
            $this->instructorModel->rollback();
            throw $e;
        }
    }

    private function validateAdminAccess()
    {
        $user_id = $_SESSION['user_id'] ?? null;
        $user_role = $_SESSION['user_role'] ?? null;
        if (!$user_id || !$user_role) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }

        if ($user_role !== 'admin') {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid authentication.']);
            exit;
        }
    }

    private function validateStudentData($isUpdate = false)
    {
        $required_fields = $isUpdate ?
            [
                'email',
                'name',
            ] :
            [
                'email',
                'password',
                'confirm_password',
                'name',
            ];

        $allowed_field_map =
            [
                'email' => 'credentials',
                'name' => 'user_details',
                'address' => 'user_details',
                'status' => 'user_details'
            ];

        if (!$isUpdate) {
            $allowed_field_map['password'] = 'credentials';
            $allowed_field_map['role'] = 'user_details';
        }
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new InvalidArgumentException("{$field} missing.");
            }
        }
        // * VALIDATE PASSWORD
        if (!$isUpdate && $_POST['password'] !== $_POST['confirm_password']) {
            throw new InvalidArgumentException("Passwords do not match");
        }

        $credentials = [];
        $user_details = [];
        foreach ($allowed_field_map as $field => $target) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                continue;
            }

            $data = $this->validateAndSanitizeField($field, $_POST[$field]);
            ${$target}[$field] = $data;
        }


        if (!isset($user_details['status'])) {
            $user_details['status'] = $this->validateAndSanitizeField('status', 'pending');
        }

        if (!$isUpdate && !isset($user_details['role'])) {
            $user_details['role'] = $this->validateAndSanitizeField('role', 'student');
        }

        return [$credentials, $user_details];
    }

    private function validateAndSanitizeField($field, $value)
    {
        $value = trim($value);

        switch ($field) {
            case 'email':
                $has_record = $this->instructorModel->all('users', '*', ['email' => $value]);
                if ($has_record->num_rows > 0) {
                    throw new InvalidArgumentException('Email is already used.');
                }
                if (strlen($value) > 100) {
                    throw new InvalidArgumentException("Email cannot exceed 100 characters");
                }
                if (strlen($value) < 3) {
                    throw new InvalidArgumentException("Email must be at least 3 characters");
                }
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException('Invalid email format.');
                }
                return trim($value);
            case 'password':
                if (strlen($value) < 8) {
                    throw new InvalidArgumentException('Password too short.');
                }
                return password_hash($value, PASSWORD_BCRYPT);
            case 'name':
                if (strlen($value) > 250) {
                    throw new InvalidArgumentException("Name is too long.");
                }
                if (strlen($value) < 2) {
                    throw new InvalidArgumentException("Name must be at least 2 characters");
                }
                return trim($value);
            case 'address':
                if (strlen($value) < 10) {
                    throw new InvalidArgumentException("Address must at least be 10 characters.");
                }
                if (strlen($value) > 500) {
                    throw new InvalidArgumentException("Address is too long");
                }
                return trim($value);
            case 'status':
                $allowedStatuses = ['pending', 'approved', 'rejected'];
                if (!in_array($value, $allowedStatuses)) {
                    throw new InvalidArgumentException('Invalid status value');
                }
                return trim($value);
            case 'role':
                $allowedRoles = ['instructor'];
                if (!in_array($value, $allowedRoles)) {
                    throw new InvalidArgumentException('Invalid role value.');
                }
                return trim($value);
            default:
                return trim($value);
        }
    }
}