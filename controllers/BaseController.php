<?php
class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Validate required fields in data
    protected function validateRequired($data, $required) {
        $missing = [];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            Response::json([
                'error' => 'Missing required fields',
                'fields' => $missing
            ], 400);
            exit;
        }
        
        return true;
    }
}