<?php
class OrderController extends BaseController {

    public function getAll() {
        $model = new Order();
        $orders = $model->getAll();
        Response::json(['orders' => $orders]);
    }

    public function getById($id) {
        $model = new Order();
        $order = $model->getById($id);
        if (!$order) {
            Response::json(['error' => 'Order not found'], 404);
        } else {
            Response::json(['order' => $order]);
        }
    }

    public function create($data) {
        $this->validateRequired($data, ['account_id', 'status', 'employee_id', 'order_detail']);
        $model = new Order();
        $order = $model->create($data);
        if (!$order) {
            Response::json(['error' => 'Order not created'], 500);
        } else {
            Response::json(['message' => 'Order created successfully', 'order' => $order]);
        }
    }
}

?>