<?php

namespace App\Http\Controllers\user;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    public function getById(string $id)
    {

        // get product by id including variants and images and category and reviews, sell count, and user reviews
        $product = Product::with(['product_variants', 'product_images'])
            ->where('id', $id)
            ->with(['category'])
            ->with(['product_reviews'])
            ->with(['product_reviews.account.profile'])
            ->first();
        
        return response()->json(['product' => $product]);
    }

    public function getByCategory(string $category_id)
    {
        $product = Product::where('category_id', $category_id)->get();
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    // 1.4. Lấy sản phẩm nổi bật
    public function getFeaturedProduct() {
        // TODO
    }

    public function getNewProduct() {
        $limit = request()->input('limit', 10);
        $data = Product::orderBy('created_at', 'desc')->with(['product_images'])->limit($limit)->get();


        return response()->json([
            'message' => 'Lấy sản phẩm mới thành công',
            'data' => $data
        ]);
    }

    // 1.5. Lấy sản phẩm liên quan _KHÓ_
    public function getRelatedProduct() {
        // TODO
    }

    public function searchProduct(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'created_at_desc');
        $category_id = $request->input('category');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        // take the rest of the filters
        $filters_raw = $request->except(['query', 'limit', 'sort', 'min_price', 'max_price', 'category']);
        $filters = array_filter($filters_raw, function ($value) {
            return !is_null($value) && $value !== '';
        });

        // Convert comma-separated values to arrays
        $filters = array_map(function ($value) {
            return explode(',', $value);
        }, $filters);

        // Start with base query for name searching
        $productsQuery = Product::query();
        
        if ($query) {
            $productsQuery->where('name', 'like', "%$query%");
        }

        \Log::info("Filters: $category_id");

        // Apply category filter
        // get pruducts by category id and subcategory id
        if ($category_id) {
            $subcategories = Category::where('parent_id', $category_id)->pluck('id')->toArray();
            $categoryIds = array_merge([$category_id], $subcategories);

            $productsQuery->whereIn('category_id', $categoryIds);
        }
        
        // Fetch all potential products first (before applying specification filters)
        // but after applying name search and with limit
        $potentialProducts = $productsQuery->with(['product_variants', 'product_images'])->get();
        
        // If we have specification filters to apply
        if (!empty($filters)) {
            $matchingProductIds = [];
            
            foreach ($potentialProducts as $product) {
                $productSpecifications = $product->toArray()['specifications'];
                $productVariants = $product->product_variants;

                foreach ($productVariants as $variant) {
                    $variantSpecifications = $variant->toArray()['specifications'];
                    $variantSpecifications = array_merge($productSpecifications, $variantSpecifications);


                    $isMatching = true;
                    foreach ($filters as $key => $values) {
                        if (isset($variantSpecifications[$key])) {
                            $specValues = explode(',', $variantSpecifications[$key]);
                            $isMatching = false;
                            foreach ($values as $value) {
                                if (in_array($value, $specValues)) {
                                    $isMatching = true;
                                    break;
                                }
                            }
                        } else {
                            $isMatching = false;
                        }

                        if (!$isMatching) {
                            break;
                        } 
                    }

                    if ($isMatching) {
                        $matchingProductIds[] = $product->id;
                        break; // No need to check other variants for this product
                    }
                }

            }
            
            // Now create a new query with only the matching product IDs
            $productsQuery = $productsQuery->whereIn('id', $matchingProductIds);
        }        

        // Apply price filters
        if ($min_price) {
            $productsQuery->where('base_price', '>=', $min_price);
        }

        
        if ($max_price) {
            $productsQuery->where('base_price', '<=', $max_price);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('base_price', 'desc');
                break;
            case 'created_at_asc':
                $productsQuery->orderBy('created_at', 'asc');
                break;
            default:
                $productsQuery->orderBy('created_at', 'desc');
        }
        
        // // Make sure we load relationships
        $productsQuery->with(['product_images', 'product_variants']);
        
        // Paginate results
        $results = $productsQuery->paginate($limit);
        $results->appends($request->all());
        
        return response()->json($results);
    }

    public function getByName(string $name)
    {
        $product = Product::where('name', $name);
        if (!$product) {
            return response()->json(['error' => 'product not found'], 404);
        }
        return response()->json(['product' => $product]);
    }
}
