<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductsRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Products extends Controller
{
    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $date_start = $request->query('date_start');
        $date_end = $request->query('date_end');
        $limt = $request->query('limit', 10);

        $query = Product::query();
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%');
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($date_start && $date_end) {
            $query->whereBetween('created_at', [$date_start, $date_end]);
        }
        if ($date_start && !$date_end) {
            $query->where('created_at', '>=', $date_start);
        }
        if (!$date_start && $date_end) {
            $query->where('created_at', '<=', $date_end);
        }

        $request = $query->paginate($limt);
        $request->appends([
            'search' => $search,
            'category_id' => $categoryId,
            'status' => $status,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'limit' => $limt
        ]);
        return $request;
    }

    public function create(CreateProductsRequest $request)
    {

        $validatedData = $request->validated();
        Product::insert($validatedData);
        return $validatedData;
    }

    public function getById(Request $request, $id)
    {
        return response()->json([
            'message' => 'Hello World',
            'data' => $request->all()
        ]);
    }



}
