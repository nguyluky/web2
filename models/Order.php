<?php

class Order {
    private $db;
    private $table = 'orders';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->getRows($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (account_id, status, employee_id, payment_method, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->insert($sql, [
            $data['account_id'],
            $data['status'],
            $data['employee_id'],
            $data['payment_method']
        ]);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['status', 'employee_id', 'payment_method'])) {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id = ?";
        return $this->db->query($sql, $values);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}

?>