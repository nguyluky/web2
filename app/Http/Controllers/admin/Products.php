<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Products extends Controller
{
    public function getAll(Request $request)
    {
        return response()->json([
            'message' => 'Hello World',
            'data' => $request->all()
        ]);
    }

    public function create(Request $request)
    {
        return response()->json([
            'message' => 'Hello World',
            'data' => $request->all()
        ]);
    }

    public function getById(Request $request, $id)
    {
        return response()->json([
            'message' => 'Hello World',
            'data' => $request->all()
        ]);
    }

}
