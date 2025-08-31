<?php

declare(strict_types=1);

class Controller
{

    protected $requiresAuth = true;
    protected $allowedRoles = [];
    protected $guestOnly = false;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->validateAccess();
    }

    private function validateAccess()
    {

        if ($_SERVER['REQUEST_URI'] === '/logout') {
            return;
        }

        // Redirect if guest-only page and user is logged in
        if ($this->guestOnly && isset($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'];
            $this->redirect("/{$role}");
            exit;
        }

        // Check authentication
        if ($this->requiresAuth && !isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            exit;
        }

        // Check role permissions
        if (!empty($this->allowedRoles)) {
            $userRole = $_SESSION['user_role'] ?? null;
            if (!in_array($userRole, $this->allowedRoles)) {
                $this->redirect('/unauthorized');
                exit;
            }
        }
    }

    protected function setError(int $statusCode, string $message)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['error'] = $message;
        $_SESSION['error_code'] = $statusCode;
        http_response_code($statusCode);

    }

    protected function view(string $path, array $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../views/{$path}.php";

        if (!file_exists($viewFile)) {
            throw new RuntimeException("View {$path} not found");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $componentPath = __DIR__ . "/../views/components";

        $prefix = str_contains($path, 'auth') ? 'auth-' : '';
        $layoutPath = __DIR__ . "/../views/layouts/{$prefix}layout.php";

        require $layoutPath;
    }

    protected function json($data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    protected function redirect(string $route, int $statusCode = 302)
    {
        // Clean any output that might have been sent
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set appropriate status code
        http_response_code($statusCode);

        // Send redirect header
        header("Location: {$route}");
        exit();
    }

    protected function isAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}