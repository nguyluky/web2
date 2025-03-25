<?php

class Supplier {

    private $db;
    private $table = 'supplier';

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
        $sql = "INSERT INTO {$this->table} (name, tax, contact_name, phone_number, email, status) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->db->query($sql, [$data['name'], $data['tax'], $data['contact_name'], $data['phone_number'], $data['email'], $data['status']]);
    }

    public function updateById($id, $data) {
        $sql = "UPDATE {$this->table} SET name = ?, tax = ?, contact_name = ?, phone_number = ?, email = ?, status = ? WHERE id = ?";
        return $this->db->query($sql, [$data['name'], $data['tax'], $data['contact_name'], $data['phone_number'], $data['email'], $data['status'], $id]);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
?>
