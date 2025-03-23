<?php

require_once '../models/Item.php';
class ItemController extends BaseController {
    
    // Get all items
    public function getAll($data) {
        $model = new Item();
        $items = $model->getAll();
        Response::json(['items' => $items]);
    }
    
    // Get item by ID
    public function getById($data, $id) {
        $model = new Item();
        $item = $model->getById($id);
        
        if (!$item) {
            Response::json(['error' => 'Item not found'], 404);
        }
        
        Response::json(['item' => $item]);
    }
    
    // Create a new item
    public function create($data) {
        // Validate required fields
        $this->validateRequired($data, ['name', 'description']);
        
        $model = new Item();
        $id = $model->create($data);
        
        $item = $model->getById($id);
        Response::json(['message' => 'Item created successfully', 'item' => $item], 201);
    }
    
    // Update an item
    public function update($data, $id) {
        $model = new Item();
        $item = $model->getById($id);
        
        if (!$item) {
            Response::json(['error' => 'Item not found'], 404);
        }
        
        $success = $model->update($id, $data);
        
        if ($success) {
            $updatedItem = $model->getById($id);
            Response::json(['message' => 'Item updated successfully', 'item' => $updatedItem]);
        } else {
            Response::json(['error' => 'Failed to update item'], 500);
        }
    }
    
    // Delete an item
    public function delete($data, $id) {
        $model = new Item();
        $item = $model->getById($id);
        
        if (!$item) {
            Response::json(['error' => 'Item not found'], 404);
        }
        
        $success = $model->delete($id);
        
        if ($success) {
            Response::json(['message' => 'Item deleted successfully']);
        } else {
            Response::json(['error' => 'Failed to delete item'], 500);
        }
    }
}