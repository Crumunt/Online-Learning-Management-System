<?php

declare(strict_types=1);

class Database
{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'lms_project_v2';
    public $res;
    protected $conn;

    private $defaultPassword = "password123";
    private $hashedPassword;

    public function __construct()
    {
        $this->hashedPassword = password_hash($this->defaultPassword, PASSWORD_BCRYPT);
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password);

            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            $query = "CREATE DATABASE IF NOT EXISTS {$this->db_name}";
            if (!$this->conn->query($query)) {
                throw new Exception("Error creating database: " . $this->conn->error);
            }

            $this->conn->select_db($this->db_name);

            $this->setup();
        } catch (Exception $e) {
            die("Database connection error! . <br>" . $e);
        }
    }

    public function beginTransaction()
    {
        return $this->conn->begin_transaction();
    }

    public function commit()
    {
        return $this->conn->commit();
    }

    public function rollback()
    {
        return $this->conn->rollback();
    }

    public function select($table, $row = "*", $where = NULL, $not = NULL, $limit = NULL)
    {
        try {
            if (!is_null($where) || !is_null($not)) {
                $cond = $types = "";
                $values = array();

                if (!is_null($where)) {
                    foreach ($where as $key => $value) {
                        $cond .= $key . " = ? AND ";
                        $types .= substr(gettype($value), 0, 1);
                        $values[] = $value;
                    }
                }

                // Handle NOT conditions
                if (!is_null($not)) {
                    foreach ($not as $key => $value) {
                        $cond .= $key . " != ? AND ";
                        $types .= substr(gettype($value), 0, 1);
                        $values[] = $value;
                    }
                }

                $cond = substr($cond, 0, -5); // Remove last " AND "

                $query = "SELECT $row FROM $table WHERE $cond";
                if (!is_null($limit) && is_numeric($limit)) {
                    $query .= " LIMIT " . intval($limit);
                }

                $stmt = $this->conn->prepare($query);

                if (!empty($values)) {
                    $stmt->bind_param($types, ...$values);
                }
            } else {
                $query = "SELECT $row FROM $table";
                if (!is_null($limit) && is_numeric($limit)) {
                    $query .= " LIMIT " . intval($limit);
                }
                $stmt = $this->conn->prepare($query);
            }

            $stmt->execute();
            return $stmt->get_result();
        } catch (Exception $e) {
            die("Error requesting data!. <br>" . $e);
        }
    }

    public function insert($table, $data)
    {
        try {
            $table_columns = implode(',', array_keys($data));
            $prep = $types = "";
            foreach ($data as $key => $value) {
                $prep .= '?,';
                $types .= substr(gettype($value), 0, 1);
            }
            $prep = substr($prep, 0, -1);
            $stmt = $this->conn->prepare("INSERT INTO $table($table_columns) VALUES ($prep)");
            $stmt->bind_param($types, ...array_values($data));
            $stmt->execute();
            $last_id = $stmt->insert_id;
            $stmt->close();
            return $last_id;
        } catch (Exception $e) {
            die("Error while inserting data!. <br>" . $e);
        }
    }

    public function destroy($table, $conditions)
    {
        try {
            $types = $cond = "";
            if (count($conditions) === 1) {
                $key = array_keys($conditions)[0];
                $cond .= $key . " = ?";
                $types .= substr(gettype($conditions[$key]), 0, 1);
            } else {
                foreach ($conditions as $key => $value) {
                    $cond .= $key . ' = ? AND ';
                    $types .= substr(gettype($value), 0, 1);
                }
                $cond = substr($cond, 0, -5); //removes last 
            }
            $stmt = $this->conn->prepare("DELETE FROM $table WHERE $cond");
            $stmt->bind_param($types, ...array_values($conditions));
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error processing data!. <br>" . $e);
        }
    }

    public function update($table, $data, $id)
    {
        try {
            $prep = $types = $cond = "";
            foreach ($data as $key => $value) {
                $prep .= $key . '=?,';
                $types .= substr(gettype($value), 0, 1);
            }

            $cond_key = array_keys($id)[0];
            $cond_value = $id[$cond_key];
            $params = array_merge(array_values($data), [$cond_value]);

            $cond .= $cond_key . "=?";
            $prep = substr($prep, 0, -1);
            $types .= 'i';

            $stmt = $this->conn->prepare("UPDATE $table SET $prep WHERE $cond");
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die("Error while updating data!. <br>" . $e);
        }
    }

    private function setup()
    {

        $this->conn->query("CREATE TABLE IF NOT EXISTS Users(
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) UNIQUE NOT NULL,
            password varchar(300) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS User_Details(
            user_id INT NOT NULL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            address VARCHAR(100) NULL,
            role ENUM('admin','instructor','student'),
            status ENUM('pending','rejected','approved'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS Courses(
            id INT AUTO_INCREMENT PRIMARY KEY,
            instructor_id INT,
            course_name VARCHAR(100) UNIQUE NOT NULL,
            short_description VARCHAR(300) NULL,
            status ENUM('pending','approved','rejected','draft','archived'),
            difficulty ENUM('beginner','intermediate','advanced'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY(`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS Course_Content(
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT,
            title VARCHAR(100) NOT NULL,
            content TEXT NULL,
            file_name VARCHAR(255),
            file_type VARCHAR(100),
            file_size BIGINT(20),
            status ENUM('active', 'inactive', 'draft', 'archived'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (course_id) REFERENCES Courses(id)  ON DELETE CASCADE
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS `enrollments` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `course_id` int(11) NOT NULL,
            `student_id` int(11) NOT NULL,
            `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
            FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        )");

        $views = [
            'user_view' => "
            CREATE OR REPLACE VIEW user_view AS 
            SELECT u.id AS id, u.email AS email, ud.name AS name, 
                   ud.address AS address, ud.role AS role, ud.status AS status, 
                   u.created_at AS created_at 
            FROM (users u JOIN user_details ud ON(u.id = ud.user_id)) 
            ORDER BY u.id ASC
        ",
            'student_view' => "
            CREATE OR REPLACE VIEW student_view AS 
            SELECT s.id AS id, s.email AS email, ud.name AS name, 
                   ud.status AS status, ud.role AS role, 
                   COUNT(e.student_id) AS courses_enrolled, s.created_at AS created_at 
            FROM ((users s JOIN user_details ud ON(s.id = ud.user_id AND ud.role = 'student')) 
                  LEFT JOIN enrollments e ON(s.id = e.student_id)) 
            GROUP BY s.id, s.email, ud.name
        ",
            'instructor_view' => "
            CREATE OR REPLACE VIEW instructor_view AS 
            SELECT i.id AS id, i.email AS email, ud.name AS name, 
                   ud.status AS status, COUNT(c.id) AS courses_count, 
                   i.created_at AS created_at 
            FROM ((users i JOIN user_details ud ON(i.id = ud.user_id AND ud.role = 'instructor')) 
                  LEFT JOIN courses c ON(c.instructor_id = i.id)) 
            GROUP BY i.id, i.email, ud.name
        ",
            'course_view' => "
            CREATE OR REPLACE VIEW course_view AS 
            SELECT c.id AS id, c.course_name AS title, c.short_description AS description, 
                   c.difficulty AS difficulty, c.status AS status, 
                   u.name AS instructor_name, c.instructor_id AS instructor_id, 
                   COUNT(DISTINCT e.student_id) AS enrollments, 
                   COUNT(DISTINCT cc.id) AS course_content, c.created_at AS created_at 
            FROM (((courses c LEFT JOIN course_content cc ON(c.id = cc.course_id)) 
                   JOIN user_details u ON(c.instructor_id = u.user_id)) 
                   LEFT JOIN enrollments e ON(c.id = e.course_id)) 
            GROUP BY c.id, c.course_name, c.short_description, c.status, u.name, 
                     c.instructor_id, c.created_at 
            ORDER BY COUNT(DISTINCT e.student_id) DESC
        ",
            'courses_enrolled' => "
            CREATE OR REPLACE VIEW courses_enrolled AS 
            SELECT i.user_id AS instructor_id, i.name AS instructor_name, 
                   c.id AS course_id, c.course_name AS title, c.status AS course_status, 
                   c.short_description AS description, s.user_id AS student_id, 
                   s.name AS student_name, e.enrolled_at AS enrolled_at 
            FROM (((enrollments e JOIN user_details s ON(e.student_id = s.user_id)) 
                   JOIN courses c ON(e.course_id = c.id AND c.status = 'approved')) 
                   JOIN user_details i ON(c.instructor_id = i.user_id)) 
            ORDER BY i.user_id DESC
        ",
            'student_enrollments_by_day' => "
            CREATE OR REPLACE VIEW student_enrollments_by_day AS 
            SELECT CAST(user_details.created_at AS DATE) AS enrollment_date, 
                   DAYNAME(user_details.created_at) AS day_name, 
                   DAYOFWEEK(user_details.created_at) AS day_number, 
                   YEAR(user_details.created_at) AS enrollment_year, 
                   WEEK(user_details.created_at) AS enrollment_week, 
                   COUNT(0) AS daily_count 
            FROM user_details 
            WHERE user_details.role = 'student' 
            GROUP BY CAST(user_details.created_at AS DATE), 
                     DAYNAME(user_details.created_at), 
                     DAYOFWEEK(user_details.created_at) 
            ORDER BY CAST(user_details.created_at AS DATE) DESC
        "
        ];

        foreach ($views as $viewName => $viewSql) {
            if (!$this->conn->query($viewSql)) {
                echo "Error creating {$viewName}: {$this->conn->error}";
            }
        }

        $users = [
            'admin' => [
                [
                    'email' => 'admin@gmail.com',
                    'password' => $this->hashedPassword,
                ],
                [
                    'name' => 'System Admin',
                    'role' => 'admin',
                    'status' => 'approved',
                ]
            ],
            'instructor' => [
                [
                    'email' => 'instructor@gmail.com',
                    'password' => $this->hashedPassword
                ],
                [
                    'name' => 'Instructor',
                    'role' => 'instructor',
                    'status' => 'approved',
                ]
            ],
            'student' => [
                [
                    'email' => 'student@gmail.com',
                    'password' => $this->hashedPassword
                ],
                [
                    'name' => 'Student',
                    'role' => 'student',
                    'status' => 'approved',
                ]
            ]
        ];

        foreach ($users as $userType => $userData) {
            $userCredentials = $userData[0];
            $user_details = $userData[1];

            $hasRecord = $this->select('users', 'email', ['email' => $userCredentials['email']]);
            if ($hasRecord->num_rows === 0) {
                // CREATE CREDENTIALS
                $userId = $this->insert('users', $userCredentials);

                $user_details['user_id'] = $userId;

                $this->insert('user_details', $user_details);
            }
        }

    }

    public function __destruct()
    {
        $this->conn->close();
    }
}