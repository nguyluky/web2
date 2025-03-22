<?php

class WarrantyControllers extends BaseController {
    public function getAll() {
        $model = new WarrantyControllers();
        $warranty = $model->getAll();
        Response::json(['warrenty' => $warranty]);
    }

    public function getById($id) {
        $model = new WarrantyControllers();
        $warranty = $model->getById($id);
        if (!$warranty) {
            Response::json(['error' => 'Warranty not found'], 404);
        } else {
            Response::json(['warranty' => $warranty]);
        }
    }

    public function getByAccountId($accountId) {
        $model = new WarrantyControllers();
        $warranty = $model->getByAccountId($accountId);
        if (!$warranty) {
            Response::json(['error' => 'Warranty not found'], 404);
        } else {
            Response::json(['warranty' => $warranty]);
        }
    }

    public function getByProductId($productId) {
        $model = new WarrantyControllers();
        $warranty = $model->getByProductId($productId);
        if (!$warranty) {
            Response::json(['error' => 'Warranty not found'], 404);
        } else {
            Response::json(['warranty' => $warranty]);
        }
    }

    public function update($id, $data) {
        $this->validateRequired($data, ['product_id', 'account_id', 'supplier_id', 'issue_date', 'expiration_date']);
        $model = new WarrantyControllers();
        $warranty = $model->getById($id);
        if (!$warranty) {
            Response::json(['error' => 'Warranty not found'], 404);
        } else {
            $success = $model->update($id, $data);
            if ($success) {
                $updatedWarranty = $model->getById($id);
                Response::json(['message' => 'Warranty updated successfully', 'warranty' => $updatedWarranty]);
            } else {
                Response::json(['error' => 'Failed to update warranty'], 500);
            }
        }
    }
}
?>