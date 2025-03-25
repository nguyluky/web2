<?php

class Warranty {

    private $db;
    private $table = 'warranty';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY issue_date DESC";
        return $this->db->getRows($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    public function getByProductId($productId) {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ?";
        return $this->db->getRows($sql, [$productId]);
    }

    public function getBySupplierId($supplierId) {
        $sql = "SELECT * FROM {$this->table} WHERE supplier_id = ?";
        return $this->db->getRows($sql, [$supplierId]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (product_id, supplier_id, issue_date, expiration_date, status, note) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->db->query($sql, [$data['product_id'], $data['supplier_id'], $data['issue_date'], $data['expiration_date'], $data['status'], $data['note']]);
    }

    public function updateById($id, $data) {
        $sql = "UPDATE {$this->table} SET product_id = ?, supplier_id = ?, issue_date = ?, expiration_date = ?, status = ?, note = ? WHERE id = ?";
        return $this->db->query($sql, [$data['product_id'], $data['supplier_id'], $data['issue_date'], $data['expiration_date'], $data['status'], $data['note'], $id]);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
?>