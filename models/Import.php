<?php

class Import {
    private $db;
    private $table = 'import';
    
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->getRows($sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, description, created_at) 
                VALUES (?, ?, NOW())";
        return $this->db->insert($sql, [
            $data['name'],
            $data['description']
        ]);
    }
}

?>