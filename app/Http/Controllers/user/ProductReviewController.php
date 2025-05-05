<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;

class ProductReviewController extends Controller
{
    public function getReviewsByProductId(string $product_id)
    {
        $reviews = ProductReview::where('product_id', $product_id)->get();

        if (!$reviews) {
            return response()->json(['error' => 'No reviews found'], 404);
        }

        return response()->json(['reviews' => $reviews]);
    }


    public function addReviewById(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'product_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'status' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // <-- validation for image
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/reviews'); // store the image in somewhere
        }

        // Add image path to meta_data
        $review = ProductReview::create([
            ...$validated,
            'meta_data' => ['image' => $imagePath]
        ]);

        return response()->json(['review' => $review], 201);
    }


    //
}
