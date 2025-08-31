<?php
declare(strict_types=1);

use function PHPSTORM_META\map;

class UserManagementController extends Controller
{

    protected Student $studentModel;
    protected Instructor $instructorModel;

    protected $allowedRoles = ['admin'];

    private $defaultTables = [
        'user_credentials' => 'users',
        'user_details' => 'user_view'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->studentModel = new Student();
        $this->instructorModel = new Instructor();
    }

    public function students()
    {
        $table = 'student_view';
        $data = $this->studentModel->all($table, '*');

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

    public function instructors()
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

    private function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function createStudent()
    {
        $this->create('students');
    }

    public function createInstructor()
    {
        $this->create('instructors');
    }

    private function create($create_type)
    {
        $this->view("admin/$create_type/create");
    }

    // TODO asynch via AJAX
    public function storeUser()
    {
        try {
            $this->validateUserData([...$_POST]);
            $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $credentials = [
                'email' => $_POST['email'],
                'password' => $hashed_password
            ];

            $user_details = [];
            $allowed_fields = ['name', 'address', 'role', 'status'];
            foreach ($_POST as $key => $value) {
                if (in_array($key, $allowed_fields)) {
                    $user_details[$key] = $value;
                }
            }

            $this->createUserWithDetails($credentials, $user_details);
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
                'message' => 'An error occurred while creating the course'
            ]);
            exit;
        }


    }

    private function createUserWithDetails($userCredentials, $userDetails, $tables = null)
    {


        $table = $tables ?? $this->defaultTables;

        try {
            $this->studentModel->beginTransaction();

            $userId = $this->studentModel->create($userCredentials, $table['user_credentials']);

            $userDetails['user_id'] = $userId;
            $this->studentModel->create($userDetails, 'user_details');

            $this->studentModel->commit();
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the course'
            ]);
            exit;
        }
    }

    public function show($id)
    {
        $data = $this->studentModel->find((int) $id)->fetch_assoc();

        if ($data['role'] === 'student') {
            $this->view('admin/students/show', compact('data'));
        } elseif ($data['role'] === 'instructor') {
            $this->view('admin/instructors/show', compact('data'));
        }

    }

    public function edit($id)
    {
        $data = $this->studentModel->find((int) $id)->fetch_assoc();

        if ($data['role'] === 'student') {
            $this->view('admin/students/update', compact('data'));
        } elseif ($data['role'] === 'instructor') {
            $this->view('admin/instructors/update', compact('data'));
        }
    }

    public function update()
    {

        // * VALIDATE EMAIL
        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->throwError('Invalid email format');
        }

        $credentials = [
            'email' => $_POST['email']
        ];

        $user_details = [];
        $allowed_fields = ['name', 'address', 'role', 'status'];
        foreach ($_POST as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $user_details[$key] = $value;
            }
        }

        $user_id = (int) $_POST['id'];

        $this->updateUserWithDetails($user_id, $credentials, $user_details);

        return http_response_code(200);
    }

    private function updateUserWithDetails($userID, $userCredentials, $userDetails, $tables = null)
    {
        $table = $tables ?? $this->defaultTables;

        try {
            $this->studentModel->beginTransaction();

            $this->studentModel->update(['id' => $userID], $userCredentials, $table['user_credentials']);

            $this->studentModel->update(['user_id' => $userID], $userDetails, 'user_details');

            $this->studentModel->commit();
            return;

        } catch (Exception $e) {
            $this->studentModel->rollback();
            http_response_code(500); // Internal Server Error

            // Return generic error to client
            echo json_encode([
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the course'
            ]);
            exit;
        }

    }

    public function destroy()
    {

        try {

            $data = json_decode(file_get_contents("php://input"), true);
            $userId = $data['table_id'] ?? null;

            $this->studentModel->beginTransaction();

            $this->studentModel->delete(['id' => $userId], 'users');

            $this->studentModel->commit();
            echo http_response_code(200);

        } catch (Exception $e) {
            $this->studentModel->rollback();
            throw $e;
        }
    }

    private function validateUserData($data)
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->throwError('Invalid email format!');
        }
        if (empty($data['password']) || empty($data['confirmPassword'])) {
            $this->throwError("Password fields are required");
        }

        if ($data['password'] !== $data['confirmPassword']) {
            $this->throwError("Passwords do not match");
        }

        if (strlen($data['password']) < 8) {
            $this->throwError("Password must be at least 8 characters long");
        }
    }

    private function throwError($err)
    {
        throw new InvalidArgumentException($err);
    }

}