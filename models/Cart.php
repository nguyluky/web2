<?php

class Cart {
    private $db;
    private $table = 'cart';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (account_id, product_id, quantity) VALUES (?, ?, ?)";
        return $this->db->query($sql, [$data['account_id'], $data['product_id'], $data['quantity']]);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->getRows($sql);
    }

    public function getByAccountId($accountId) {
        $sql = "SELECT * FROM {$this->table} WHERE account_id = ?";
        return $this->db->getRow($sql, [$accountId]);
    }

    public function getByProductId($accountId, $productId) {
        $sql = "SELECT * FROM {$this->table} WHERE account_id = ? AND product_id = ?";
        return $this->db->getRow($sql, [$accountId, $productId]);
    }

    public function update($accountId, $productId, $data) {
        $sql = "UPDATE {$this->table} SET quantity = ? WHERE account_id = ? AND product_id = ?";
        return $this->db->query($sql, [$data['quantity'], $accountId, $productId]);
    }

    public function delete($accountId, $productId) {
        $sql = "DELETE FROM {$this->table} WHERE account_id = ? AND product_id = ?";
        return $this->db->query($sql, [$accountId, $productId]);
    }
}

?>