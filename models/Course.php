<?php

class Course extends Model
{
    protected $table = 'course_view';
    protected $viewTable = 'course_view';
    protected $primaryKey = 'id';

    public function getAvailableCourses($studentId)
    {
        $enrolled_result = $this->all('courses_enrolled', 'course_id', ['student_id' => $studentId]);
        $enrolled_course_ids = [];
        while ($row = $enrolled_result->fetch_assoc()) {
            $enrolled_course_ids[] = $row['course_id'];
        }

        if (empty($enrolled_course_ids)) {
            $courses = $this->all('course_view', 'DISTINCT *', ['status' => 'approved'])->fetch_all(MYSQLI_ASSOC);
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
        $courses_result = $this->fetchCourseByStatus('approved');


        $enrolled_lookup = array_flip($enrolled_course_ids);
        $available_courses = [];

        while ($course = $courses_result->fetch_assoc()) {
            $course_id = $course['id'];

            // * SKIP IF NASA ARRAY NA YUNG ID NG COURSE
            if (!isset($enrolled_lookup[$course_id]) && !isset($available_courses[$course_id])) {
                $available_courses[$course_id] = $course; // Use course_id as key
            }
        }

        return array_values($available_courses);
    }

    public function fetchCourseByStatus(string $status)
    {
        return $this->all('course_view', '*', ['status' => $status]);
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