<?php

class UserController extends BaseController {
    // Get all users
    public function getAll() {
        $model = new User();
        $users = $model->getAll();
        Response::json(['users' => $users]);
    }

    // Get user by ID
    public function getById($id) {
        $model = new User();
        $user = $model->getById($id);

        if (!$user) {
            Response::json(['error' => 'User not found'], 404);
        }
        Response::json(['user' => $user]);
    }

    // Get user by rule
    public function getByRule($rule) {
        $model = new User();
        $users = $model->getByRule($rule);

        if (!$users) {
            Response::json(['error' => 'User not found'], 404);
        }
        Response::json(['users' => $users]);
    }

    // Create new user
    public function create($data) {
        $this->validateRequired($data, ['fullname', 'address', 'phone_number', 'email', 'avatar', 'rule']);
        
        $model = new User();
        $id = $model->create($data);
        
        $user = $model->getById($id);
        Response::json(['message' => 'User created successfully', 'item' => $user], 201);
    }

    // Update a user's information by ID
    public function updateById($id, $data) {
        $this->validateRequired($data, ['fullname', 'address', 'phone_number', 'email', 'avatar', 'rule']);
        $model = new User();
        $user = $model->getById($id);

        if (!$user) {
            Response::json(['error' => 'User not found'], 404);
        }
        
        $success = $model->updateById($id, $data);
        if ($success) {
            $updatedUser = $model->getById($id);
            Response::json(['message' => 'Update user sucessfully', 'user' => $updatedUser]);
        } else {
            Response::json(['error' => 'Failed to update user'], 500);
        }
    }

    // Delete user by ID
    public function deleteById($id) {
        $model = new User();
        $user = $model->getById($id);

        if (!$user) {
            Response::json(['error' => 'User not found'], 404);
        }
        
        $success = $model->deleteById($id);
        if ($success) {
            Response::json(['message' => 'Delete user successfully']);
        } else {
            Response::json(['error' => 'Failed to delete user'], 500);
        }
    }
}

?>