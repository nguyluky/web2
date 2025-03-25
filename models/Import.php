<?php

class Import {
    private $db;
    private $table = 'import';
    
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
        $sql = "INSERT INTO {$this->table} (supplier_id, employee_id, status, created_at) 
                VALUES (?, ?, ?, NOW())";
        return $this->db->insert($sql, [
            $data['supplier_id'],
            $data['employee_id'],
            $data['status']
        ]);
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, ['supplier_id', 'employee_id', 'status'])) {
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
