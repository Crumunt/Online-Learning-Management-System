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
    protected $viewTable;
    protected $primaryKey;

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

    public function allv2(?string $table = null, string $columns = "*", ?array $where = null, ?string $orderBy = null, ?int $limit = null)
    {
        $table = $table ?? $this->table;
        return $this->conn->select($table, $columns, $where, null,  $orderBy, $limit);
    }

    // FETCH USER DATA
    public function find(int $id, string $columns = '*')
    {
        $result = $this->conn->select($this->viewTable, $columns, [$this->primaryKey => $id]);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function findWhere(array $conditions, string $columns = '*')
    {
        $result = $this->conn->select($this->table, $columns, $conditions);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function all($table = null, $row = "*", $where = NULL, $not = NULL, $orderBy = null, $limit = null)
    {
        $table ??= $this->table;

        return $this->conn->select($table, $row, $where, $not);
    }

    // public function find(int $id)
    // {
    //     return $this->conn->select($this->table, '*', ['id' => $id]);
    // }

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

    public function deleteById(int $id): bool
    {
        return $this->delete([$this->primaryKey => $id]);
    }


}