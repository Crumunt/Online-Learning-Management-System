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
        return $this->find($userId);
    }

    // GET ENROLLED CLASSES
    public function enrollments($studentId)
    {
        return $this->all('courses_enrolled', '*', ['student_id' => $studentId]);
    }

}