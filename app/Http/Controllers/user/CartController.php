<?php

namespace App\Http\Controllers\user;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Cart;

class CartController extends Controller
{
    public function addCart(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:profiles,id',
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'amount' => 'nullable|integer|min:1',
        ]);

        $cart = Cart::where('profile_id', $request->profile_id)->where('product_variant_id', $request->product_variant_id)->first();
        if (!$cart) {
            $cart = Cart::create($validated);
        } else {
            $cart->amount = $request->amount;
            $cart->save();
        }

        return response()->json(['cart' => $cart], 201);
    }

    public function getAllCart()
    {

        $user = auth()->user();

        // $profile_id = $user->id;
        return response()->json(['carts' => $user->profile->carts]);


        // $carts = Cart::where('profile_id', $profile_id);
        // if ($carts->isEmpty()) {
        //     return response()->json(['error' => 'carts not found'], 404);
        // }
        // return response()->json(['carts' => $carts]);
    }

    public function updateCart(int $variant_id, int $profile_id, int $quantity) { // Tú đã sửa lại
        $user = auth()->user();
        if (!$user || !$user->profile) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = Cart::where('profile_id', $user->profile->id)
        ->where('product_variant_id', $variant_id)
        ->first();
        if ($cart) {
            $cart->amount = $quantity;
            $cart->save();
            return response()->json(['cart' => $cart]);
        }
        return response()->json(['error' => 'Cart not found'], 404);
    }

    public function deleteCart(int $variant_id, int $profile_id) {  // Tú đã sửa lại
            $user = auth()->user();
            if (!$user || !$user->profile) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $cart = Cart::where('profile_id', $user->profile->id)
                ->where('product_variant_id', $variant_id)
                ->first();
        
            if ($cart) {
                $cart->delete();
                return response()->json(['message' => 'Đã xóa giỏ hàng thành công']);
            }
        
            return response()->json(['error' => 'Cart not found'], 404);
        }

    // 3.5. Áp dụng mã giảm giá
    public function promotion() {
        // TODO chưa hiểu
    }
}
