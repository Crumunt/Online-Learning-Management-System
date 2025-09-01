<?php

declare(strict_types=1);


require_once __DIR__ . '/../config/database.php';
class Model
{
    /**
     * Summary of conn
     * @var mysqli
     */
    protected $conn;
    protected $table;
    protected $view_table;

    public function __construct()
    {
        $this->conn = new Database();
    }

    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    public function commit()
    {
        return $this->conn->commit();
    }

    public function rollback()
    {
        return $this->conn->rollback();
    }

    public function all($table = null, $row = "*", $where = NULL, $not = NULL)
    {
        $table ??= $this->table;

        return $this->conn->select($table, $row, $where, $not);
    }

    public function find(int $id)
    {
        return $this->conn->select($this->table, '*', ['id' => $id]);
    }

    public function create(array $data, $table = null)
    {
        $targetTable = $table ?? $this->table;
        return $this->conn->insert($targetTable, $data);
    }

    public function update($id, array $data, $table = null)
    {
        $targetTable = $table ?? $this->table;
        return $this->conn->update($targetTable, $data, $id);
    }

    public function delete($conditions, $table = null)
    {
        $targetTable = $table ?? $this->table;
        return $this->conn->destroy($targetTable, $conditions);
    }
    

}