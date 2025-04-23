<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getAll()
    {
        $category = Category::all();
        if (!$category) {
            return response()->json(['error' => 'category not found'], 404);
        }
        return response()->json(['data' => $category]);
    }
}
