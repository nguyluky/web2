<?php

class User {
    private $db;
    private $table = 'users';

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
 
    public function getByRule($rule) {
        $sql = "SELECT * FROM {$this->table} WHERE rule = ?";
        return $this->db->getRow($sql, [$rule]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, email, password, rule) VALUES (?, ?, ?, ?)";
        return $this->db->query($sql, [$data['name'], $data['email'], $data['password'], $data['rule']]);
    }

    public function updateById($id, $data) {
        $sql = "UPDATE {$this->table} SET name = ?, email = ?, password = ?, rule = ? WHERE id = ?";
        return $this->db->query($sql, [$data['name'], $data['email'], $data['password'], $data['rule'], $id]);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
