<?php

class ImportController extends BaseController {

    public function getAll() {
        $model = new Import();
        $orders = $model->getAll();
        Response::json(['Import orders' => $orders]);
    }

    public function getById($id) {
        $model = new Import();
        $import_order = $model->getById($id);
        if (!$import_order) {
            Response::json(['error' => 'Import order not found'], 404);
        } else {
            Response::json(['order' => $import_order]);
        }
    }

    public function create($data) {
        $this->validateRequired($data, ['supplier_id', 'employee_id', 'status', 'import_detail']);
        $model = new Import();
        $import_order = $model->create($data);
        if (!$import_order) {
            Response::json(['error' => 'Import order not created'], 500);
        } else {
            Response::json(['message' => 'Import order created successfully', 'order' => $import_order]);
        }
    }
}

?>