<?php

class CartController extends BaseController {
    public function create($data) {
        $this->validateRequired($data, ['account_id', 'product_id', 'quantity']);
        $model = new Cart();
        $success = $model->create($data);
        if ($success) {
            Response::json(['message' => 'Cart created successfully']);
        } else {
            Response::json(['error' => 'Failed to create cart'], 500);
        }
    }
    public function getByAccountId($id) {
        $model = new Cart();
        $carts = $model->getByAccountId($id);
        if ($carts) {
            Response::json(['carts' => $carts]);
        } else {
            Response::json(['error' => 'Cart not found'], 404);
        }
    }

    public function getByProductId($account_id, $product_id) {
        $model = new Cart();
        $carts = $model->getByProductId($account_id, $product_id);
        if ($carts) {
            Response::json(['carts' => $carts]);
        } else {
            Response::json(['error' => 'Cart not found'], 404);
        }
    }

    public function update($account_id, $product_id, $data) {
        $this->validateRequired($data, ['product_id', 'quantity']);
        $model = new Cart();
        $cart = $model->getByProductId($account_id, $product_id);
        if (!$cart) {
            Response::json(['error' => 'Cart not found'], 404);
        } else {
            $success = $model->update($account_id, $product_id, $data);
            if ($success) {
                Response::json(['message' => 'Cart updated successfully']);
            } else {
                Response::json(['error' => 'Failed to update cart'], 500);
            }
        }
    }

    public function delete($account_id, $product_id) {
        $model = new Cart();
        $cart = $model->getByProductId($account_id, $product_id);
        if (!$cart) {
            Response::json(['error' => 'Cart not found'], 404);
        } else {
            $success = $model->delete($account_id, $product_id);
            if ($success) {
                Response::json(['message' => 'Cart deleted successfully']);
            } else {
                Response::json(['error' => 'Failed to delete cart'], 500);
            }
        }
    }
}

?>