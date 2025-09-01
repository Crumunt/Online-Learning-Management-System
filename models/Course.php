<?php

class Course extends Model
{
    protected $table = 'course_view';

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
}