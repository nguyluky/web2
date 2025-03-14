<?php
class Item {
    private $db;
    private $table = 'items';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all items
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->getRows($sql);
    }
    
    // Get item by ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    // Create a new item
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, description, created_at) 
                VALUES (?, ?, NOW())";
        return $this->db->insert($sql, [
            $data['name'],
            $data['description']
        ]);
    }
    
    // Update an item
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        // Build dynamic update fields
        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'description'])) {
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
    
    // Delete an item
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->db->query($sql, [$id]);
        return true;
    }
}