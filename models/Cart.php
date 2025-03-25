<?php

class Cart {
    private $db;
    private $table = 'cart';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (profile_id, product_id, amount) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$data['profile_id'], $data['product_id'], $data['amount']]);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->getRows($sql);
    }

    public function getByAccountId($accountId) {
        $sql = "SELECT * FROM {$this->table} WHERE profile_id = ?";
        return $this->db->getRow($sql, [$accountId]);
    }

    public function getByProductId($accountId, $productId) {
        $sql = "SELECT * FROM {$this->table} WHERE profile_id = ? AND product_id = ?";
        return $this->db->getRow($sql, [$accountId, $productId]);
    }

    public function update($accountId, $productId, $data) {
        $sql = "UPDATE {$this->table} SET amount = ? WHERE profile_id = ? AND product_id = ?";
        return $this->db->query($sql, [$data['amount'], $accountId, $productId]);
    }

    public function delete($accountId, $productId) {
        $sql = "DELETE FROM {$this->table} WHERE profile_id = ? AND product_id = ?";
        return $this->db->query($sql, [$accountId, $productId]);
    }
}

?>