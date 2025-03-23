<?php

require_once '../models/Supplier.php';

class SupplierController extends BaseController {
    // get all of supplier list
    public function getAll() {
        $model = new Supplier();
        $suppliers = $model->getAll();
        Response::json(['suppliers' => $suppliers]);
    }

    // get the supplier's information by ID
    public function getById($id) {
        $model = new Supplier();
        $supplier = $model->getById($id);
        
        if (!$supplier) {
            Response::json(['error' => 'Supplier not found'], 404);
        }
        
        Response::json(['supplier' => $supplier]);
    }

    // add new supplier's information
    public function create($data) {
        // Validate required fields
        $this->validateRequired($data, ['name', 'phone_number', 'email', 'status']);

        $model = new Supplier();
        $id = $model->create($data);
        
        $supplier = $model->getById($id);
        Response::json(['message' => 'Supplier created successfully', 'supplier' => $supplier], 201);
        
    }

    // update supplier's information by ID
    public function updateById($id, $data) {
        $this->validateRequired($data, ['name', 'phone_number', 'email', 'status']);
        $model = new Supplier();
        $supplier = $model->getById($id);
        
        if (!$supplier) {
            Response::json(['error' => 'Supplier not found'], 404);
        }
        
        $success = $model->updateById($id, $data);
        
        if ($success) {
            $updatedSupplier = $model->getById($id);
            Response::json(['message' => 'Supplier updated successfully', 'supplier' => $updatedSupplier]);
        } else {
            Response::json(['error' => 'Failed to update supplier'], 500);
        }
    }

    // delete supplier's information by ID
    public function deleteById($id) {
        $model = new Supplier();
        $supplier = $model->getById($id);
        
        if (!$supplier) {
            Response::json(['error' => 'Supplier not found'], 404);
        }
        
        $success = $model->deleteById($id);
        
        if ($success) {
            Response::json(['message' => 'Supplier deleted successfully']);
        } else {
            Response::json(['error' => 'Failed to delete supplier'], 500);
        }
    }
}

?>