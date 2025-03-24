<?php

class Warranty {

    private $db;
    private $table = 'warranty';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->getRows($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    public function getByAccountId($accountId) {
       $sql = "SELECT * FROM {$this->table} WHERE account_id = ?";
         return $this->db->getRow($sql, [$accountId]);
    }

    public function getByProductId($productId) {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ?";
        return $this->db->getRow($sql, [$productId]);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET account_id = ?, product_id = ?, start_date = ?, end_date = ?, status = ? WHERE id = ?";
        return $this->db->query($sql, [$data['account_id'], $data['product_id'], $data['start_date'], $data['end_date'], $data['status'], $id]);
    }
}

?>