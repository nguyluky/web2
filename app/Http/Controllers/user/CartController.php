<?php

namespace App\Http\Controllers\user;

use App\Models\Product;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Cart;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="Cart",
 *   description="Shopping cart endpoints"
 *   
 * )
 */
class CartController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/cart",
     *   tags={"Cart"},
     *   summary="Thêm sản phẩm vào giỏ hàng",
     *   description="Thêm sản phẩm mới hoặc cập nhật số lượng sản phẩm đã có trong giỏ hàng",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"product_variant_id","amount"},
     *       @OA\Property(property="product_variant_id", type="integer", description="ID của biến thể sản phẩm"),
     *       @OA\Property(property="amount", type="integer", description="Số lượng sản phẩm")
     *     )
     *   ),
     *   @OA\Response(
     *     response=201, 
     *     description="Thêm vào giỏ hàng thành công",
     *     @OA\JsonContent(
     *       @OA\Property(property="cart", type="object",
     *         @OA\Property(property="profile_id", type="integer"),
     *         @OA\Property(property="product_variant_id", type="integer"),
     *         @OA\Property(property="amount", type="integer"),
     *         @OA\Property(property="created_at", type="string", format="date-time"),
     *         @OA\Property(property="updated_at", type="string", format="date-time")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="Không được phép truy cập",
     *     @OA\JsonContent(@OA\Property(property="error", type="string", example="Unauthorized"))
     *   ),
     *   @OA\Response(
     *     response=422, 
     *     description="Dữ liệu không hợp lệ",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="errors", type="object")
     *     )
     *   )
     * )
     */
    public function addCart(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->profile) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        

        $validated = $request->validate([
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'amount' => 'nullable|integer|min:1',
        ]);

        $validated['profile_id'] = $user->profile->id;

        $cart = Cart::where('profile_id', $validated['profile_id'])
                    ->where('product_variant_id', $validated['product_variant_id'])
                    ->first();

        if (!$cart) {
            $cart = Cart::create($validated);
        } else {
            Cart::where('profile_id', $validated['profile_id'])
            ->where('product_variant_id', $validated['product_variant_id'])
            ->update(['amount' => $cart->amount + $validated['amount']]);
        }

        // Return the updated or newly created cart
        $cart = Cart::where('profile_id', $validated['profile_id'])
                    ->where('product_variant_id', $validated['product_variant_id'])
                    ->first();
        return response()->json(['cart' => $cart], 201);
        // return response()->json(['cart' => "hello"], 201);
    }

    /**
     * @OA\Get(
     *   path="/api/cart",
     *   tags={"Cart"},
     *   summary="Lấy danh sách giỏ hàng của người dùng",
     *   description="Trả về danh sách sản phẩm trong giỏ hàng của người dùng đã đăng nhập",
     *   @OA\Response(
     *     response=200, 
     *     description="Danh sách giỏ hàng được lấy thành công",
     *     @OA\JsonContent(
     *       @OA\Property(property="carts", type="array", 
     *         @OA\Items(
     *           type="object",
     *           @OA\Property(property="profile_id", type="integer"),
     *           @OA\Property(property="product_variant_id", type="integer"),
     *           @OA\Property(property="amount", type="integer"),
     *           @OA\Property(property="product_variant", type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="product_id", type="integer"),
     *             @OA\Property(property="sku", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="original_price", type="number"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="attributes", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(property="product", type="object",
     *               @OA\Property(property="id", type="integer"),
     *               @OA\Property(property="sku", type="string"),
     *               @OA\Property(property="name", type="string"),
     *               @OA\Property(property="slug", type="string"),
     *               @OA\Property(property="description", type="string"),
     *               @OA\Property(property="category_id", type="integer"),
     *               @OA\Property(property="base_price", type="number"),
     *               @OA\Property(property="base_original_price", type="number"),
     *               @OA\Property(property="status", type="string"),
     *               @OA\Property(property="specifications", type="string"),
     *               @OA\Property(property="features", type="string"),
     *               @OA\Property(property="meta_data", type="object", nullable=true),
     *               @OA\Property(property="created_at", type="string", format="date-time"),
     *               @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *           )
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, description="Không được phép truy cập", 
     *     @OA\JsonContent(@OA\Property(property="error", type="string", example="Unauthorized"))
     *   ),
     *   security={{"bearerAuth":{}}},
     * )
     */
    public function getAllCart()
    {
        $user = auth()->user();
        if (!$user || !$user->profile) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = Cart::with(['product_variant.product'])->where('profile_id', $user->profile->id)->get();

        return response()->json(['carts' => $cart]);
    }

    /**
     * @OA\Put(
     *   path="/api/cart/{variant_id}",
     *   tags={"Cart"},
     *   summary="Cập nhật số lượng sản phẩm trong giỏ hàng",
     *   description="Cập nhật số lượng của một sản phẩm đã có trong giỏ hàng",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="variant_id", 
     *     in="path", 
     *     required=true, 
     *     description="ID của biến thể sản phẩm",
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"amount"},
     *       @OA\Property(property="amount", type="integer", description="Số lượng mới của sản phẩm")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="Cập nhật giỏ hàng thành công",
     *     @OA\JsonContent(
     *       @OA\Property(property="cart", type="object",
     *         @OA\Property(property="profile_id", type="integer"),
     *         @OA\Property(property="product_variant_id", type="integer"),
     *         @OA\Property(property="amount", type="integer"),
     *         @OA\Property(property="created_at", type="string", format="date-time"),
     *         @OA\Property(property="updated_at", type="string", format="date-time")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="Không được phép truy cập",
     *     @OA\JsonContent(@OA\Property(property="error", type="string", example="Unauthorized"))
     *   ),
     *   @OA\Response(
     *     response=404, 
     *     description="Không tìm thấy sản phẩm trong giỏ hàng",
     *     @OA\JsonContent(@OA\Property(property="error", type="string", example="Cart not found"))
     *   ),
     *   @OA\Response(
     *     response=422, 
     *     description="Dữ liệu không hợp lệ",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string"),
     *       @OA\Property(property="errors", type="object")
     *     )
     *   )
     * )
     */
    public function updateCart(Request $request, int $variant_id) {
        $user = auth()->user();
        if (!$user || !$user->profile) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('profile_id', $user->profile->id)
            ->where('product_variant_id', $variant_id)
            ->first();

        if ($cart) {
            // update cart directly with a query since the model doesn't have a standard primary key
            Cart::where('profile_id', $user->profile->id)
                ->where('product_variant_id', $variant_id)
                ->update(['amount' => $validated['amount']]);
                
            // Refresh the model with updated data
            $cart = Cart::where('profile_id', $user->profile->id)
                ->where('product_variant_id', $variant_id)
                ->first();
                
            return response()->json(['cart' => $cart]);
        }
        return response()->json(['error' => 'Cart not found'], 404);
    }

    /**
     * @OA\Delete(
     *   path="/api/cart/{variant_id}",
     *   tags={"Cart"},
     *   summary="Xóa sản phẩm khỏi giỏ hàng",
     *   description="Xóa hoàn toàn một sản phẩm khỏi giỏ hàng",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="variant_id", 
     *     in="path", 
     *     required=true, 
     *     description="ID của biến thể sản phẩm",
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="Xóa sản phẩm thành công",
     *     @OA\JsonContent(@OA\Property(property="message", type="string", example="Đã xóa giỏ hàng thành công"))
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="Không được phép truy cập",
     *     @OA\JsonContent(@OA\Property(property="error", type="string", example="Unauthorized"))
     *   ),
     *   @OA\Response(
     *     response=404, 
     *     description="Không tìm thấy sản phẩm trong giỏ hàng",
     *     @OA\JsonContent(@OA\Property(property="error", type="string", example="Cart not found"))
     *   )
     * )
     */
    public function deleteCart(int $variant_id) {
        $user = auth()->user();
        if (!$user || !$user->profile) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $cart = Cart::where('profile_id', $user->profile->id)
            ->where('product_variant_id', $variant_id)
            ->first();
    
        if ($cart) {
            Cart::where('profile_id', $user->profile->id)
                ->where('product_variant_id', $variant_id)
                ->delete();
            return response()->json(['message' => 'Đã xóa giỏ hàng thành công']);
        }
    
        return response()->json(['error' => 'Cart not found'], 404);
    }

    // 3.5. Áp dụng mã giảm giá
    public function promotion() {
        // TODO chưa hiểu
    }
}
