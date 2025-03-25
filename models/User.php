<?php

class Account {

    private $db;
    private $table = 'account';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created DESC";
        return $this->db->getRows($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (username, password, rule, status, created, updated) VALUES (?, ?, ?, ?, NOW(), NOW())";
        return $this->db->insert($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['rule'],
            $data['status']
        ]);
    }

    public function updateById($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['username', 'password', 'rule', 'status'])) {
                if ($key == 'password') {
                    $value = password_hash($value, PASSWORD_BCRYPT);
                }
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . ", updated = NOW() WHERE id = ?";
        return $this->db->query($sql, $values);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
?>