<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getAll() {
        $category = Category::all();
        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }
        return response()->json(['category' => $category]);
    }
}
