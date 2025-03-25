<?php
class Product {
    private $db;
    private $table = 'product';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Get all products
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->db->getRows($sql);
    }
    
    // Get product by ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->getRow($sql, [$id]);
    }
    
    // Create a new product
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, category_id, attributes) VALUES (?, ?, ?)";
        return $this->db->insert($sql, [
            $data['name'],
            $data['category_id'],
            json_encode($data['attributes'])
        ]);
    }
    
    // Update a product
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'category_id', 'attributes'])) {
                $fields[] = "$key = ?";
                $values[] = ($key === 'attributes') ? json_encode($value) : $value;
            }
        }
        
        if (empty($fields)) {
            return false; // Nothing to update
        }
        
        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id = ?";
        return $this->db->query($sql, $values);
    }
    
    // Delete a product
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}