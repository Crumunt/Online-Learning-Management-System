<?php

declare(strict_types=1);

class CourseController extends Controller
{


    protected Course $course;

    public function __construct()
    {
        parent::__construct();

        $this->course = new Course();
    }

    public function serve()
    {
        $instructor = $_GET['instructor'] ?? null;
        $file = $_GET['file'] ?? null;

        if (!$instructor || !$file) {
            http_response_code(400);
            exit('Missing required parameters.');
        }

        // Build file path
        $filepath = __DIR__ . '/../../storage/uploads/' . $instructor . '/' . $file;
        $filename = realpath($filepath);
        // Validate file
        if (!$filepath || !file_exists($filepath)) {
            var_dump($filepath);
            http_response_code(404);
            exit('PDF file not found.');
        }

        // Set headers for inline PDF viewing
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Content-Length: ' . filesize($filepath));

        // Clear buffer and send file
        ob_clean();
        flush();
        readfile($filepath);
        exit;

    }

}