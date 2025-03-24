<?php

class Supplier {

    private $db;
    private $table = 'suppliers';

    // get all of supplier list
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->getRows($sql);
    }

    // get the supplier's information by ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }

    // add new supplier's information
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, address, phone, email, created_at) VALUES (?, ?, ?, ?, NOW())";
        return $this->db->insert($sql, [$data['name'], $data['address'], $data['phone'], $data['email']]);
    }

    // update supplier's information by ID
    public function updateById($id, $data) {
        $fields = [];
        $values = [];

        // Build dynamic update fields
        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'address', 'phone', 'email'])) {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (empty($fields)) {
            return true;  // Nothing to update
        }

        $values[] = $id;  // Add ID for WHERE clause

        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . ", updated_at = NOW() WHERE id = ?";
        $this->db->query($sql, $values);
        return true;
    }

    // delete supplier's information by ID
    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}

?>