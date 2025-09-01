<?php
declare(strict_types=1);

class User extends Model
{
    protected $table = "users";
    protected $viewTable = "user_view";
    protected $primaryKey = "id";
    public function findByEmail($email)
    {
        return $this->findWhere(['email' => $email]);
    }

    public function findByRole($role)
    {
        return $this->all($this->viewTable, '*', ['role' => $role]);
    }

    public function fetchData(int $userId)
    {
        return $this->findv2($userId);
    }

    // GET ENROLLED CLASSES
    public function enrollments($studentId)
    {
        return $this->all('courses_enrolled', '*', ['student_id' => $studentId]);
    }

    public function processCourseData($courseData)
    {
        return [
            'course_id' => $courseData['id'] ?? $courseData['course_id'],
            'instructor' => $courseData['instructor_name'],
            'title' => $courseData['title'],
            'description' => $courseData['description'] ?? null,
            'created_at' => date('M d, Y', strtotime($courseData['created_at'] ?? $courseData['enrolled_at'])),
            'student_count' => $courseData['enrollments'] ?? 0,
            'material_count' => $courseData['course_content'] ?? 0,
            'status' => $courseData['status'] ?? null,
        ];
    }

    public function processContentView($contentData)
    {
        $validContent = array_filter($contentData, 'is_array');

        $targetID = $this->getTargetContentId();

        $currentContent = $this->findCurrentContent($validContent, $targetID);
        $contentList = $this->prepareContentList($validContent);

        return [
            'currentTitle' => $currentContent['title'] ?? null,
            'filename' => $currentContent['file_name'] ?? null,
            'contentToShow' => $currentContent,
            'courseContentList' => $contentList,
            'hasContent' => !empty($validContent)
        ];
    }

    public function getTargetContentId()
    {
        return isset($_GET['nextContent']) ? intval($_GET['nextContent']) : null;
    }

    public function findCurrentContent($validContent, $targetId)
    {
        if ($targetId) {
            // Find the content with matching ID
            // * SO FOREACH IS MUCH BETTER COMPARED TO ARRAY_FILTER WHEN IT COMES TO SEARCHING FOR A SINGLE DATA
            foreach ($validContent as $content) {
                if (isset($content['id']) && $content['id'] === $targetId) {
                    return $content;
                }
            }
        }

        return reset($validContent) ?: null;
    }

    public function prepareContentList($validContent)
    {
        return array_map(function ($content) {
            return [
                'id' => $content['id'] ?? null,
                'title' => $content['title'] ?? 'Untitled',
            ];
        }, $validContent);
    }

}