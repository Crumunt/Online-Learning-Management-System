<?php

declare(strict_types=1);

class AuthController extends Controller
{

    protected Auth $authModel;
    protected Student $student;
    protected $guestOnly = true;

    protected $requiresAuth = false;

    public function __construct()
    {
        parent::__construct();
        $this->authModel = new Auth();
        $this->student = new Student();
    }

    public function login()
    {
        if ($_POST) {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $this->setError(400, 'Invalid Email or Password');
                $this->redirect('/login');
                exit;
            }

            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // * VALIDATE EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->setError(400, 'Invalid Email or Password');
                $this->redirect('/login');
                exit;
            }

            try {
                $userResult = $this->authModel->all(null,'*',['email' => $email]);
                $userData = $userResult ? $userResult->fetch_assoc() : null;

                if (!$userData || !password_verify($password, $userData['password'])) {
                    $this->setError(401, 'Invalid Email or Password');
                    $this->redirect('/login');
                    exit;
                }

                // Get student data
                $studentResult = $this->student->find((int) $userData['id']);
                $studentData = $studentResult ? $studentResult->fetch_assoc() : null;

                if (!$studentData) {
                    $this->setError(500, 'Something went wrong');
                    $this->redirect('/login');
                    exit;
                }

                $_SESSION['user_id'] = $studentData['id'];
                $_SESSION['user_name'] = $studentData['name'];
                $_SESSION['user_email'] = $studentData['email'];
                $_SESSION['user_role'] = $studentData['role'];

                $this->routeOnRole();
                exit;

            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                http_response_code(500);
            }
        }

        $this->view('auth/login');

    }

    public function register()
    {

        if ($_POST) {
            try {
                $this->validateUserData([...$_POST]);
            } catch (InvalidArgumentException $e) {
                http_response_code(422);  // * UNRPOCESSABLE ENTITY
                echo json_encode(['error' => $e->getMessage()]);
                exit; //stops script para mag trigger yung error block nyeta
            }

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

            echo json_encode(['route' => '/student']);
            exit;
        }

        $this->view('auth/register');

    }

    public function logout()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        session_destroy();

        $this->redirect('/login');
        exit;

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

    private function createUserWithDetails($userCredentials, $userDetails)
    {

        try {
            $this->authModel->beginTransaction();

            $userId = $this->authModel->create($userCredentials, 'users');

            $userDetails['user_id'] = $userId;
            $this->authModel->create($userDetails, 'userdetails');

            $this->authModel->commit();

            return $userId;

        } catch (Exception $e) {
            $this->authModel->rollback();
            throw $e;
        }
    }

    private function throwError($err)
    {
        throw new InvalidArgumentException($err);
    }

    private function routeOnRole()
    {
        $user_role = $_SESSION['user_role'] ?? null;
        switch ($user_role) {
            case 'admin':
                $this->redirect('/admin');
                break;
            case 'student':
                $this->redirect('/student');
                break;
            case 'instructor':
                $this->redirect('/instructor');
                break;
            default:
                $this->redirect('/logout');
                break;
        }
    }


}