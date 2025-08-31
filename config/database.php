<?php

declare(strict_types=1);

class Database
{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'lms_project';
    public $res;
    protected $conn;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->db_name);
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

                // // Combine values from both arrays for binding
                // if (!is_null($where)) {
                //     $values = array_merge($values, array_values($where));
                // }
                // if (!is_null($not)) {
                //     $values = array_merge($values, array_values($not));
                // }
                // if (!is_null($limit)) {
                //     $values = array_merge($values, array_values($limit));
                // }

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
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS UserDetails(
            user_id INT NOT NULL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            address VARCHAR(100) NULL,
            role ENUM('admin','instructor','student'),
            status ENUM('pending','rejected','approved'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES Users(id)
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS Courses(
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_name VARCHAR(100) UNIQUE NOT NULL,
            short_description VARCHAR(300) NULL,
            category_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )");

        $this->conn->query("CREATE TABLE IF NOT EXISTS CourseContent(
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT,
            title VARCHAR(100) NOT NULL,
            content TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (course_id) REFERENCES Courses(id)
        )");


    }

    public function __destruct()
    {
        $this->conn->close();
    }
}