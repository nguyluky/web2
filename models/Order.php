<?php

class Order {
    private $db;
    private $table = 'orders';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->getRows($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (account_id, total, created_at) VALUES (?, ?, NOW())";
        return $this->db->insert($sql, [$data['account_id'], $data['total']]);
    }
}

?>