<?php

namespace App\Http\Controllers\user;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CartController extends Controller
{
    public function addCart(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer',
            'product_variant_id' => 'required|integer',
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

    public function getAllCart(int $profile_id) // maybe wrong
    {
        $carts = Cart::where('profile_id', $profile_id);
        if (!$carts) {
            return response()->json(['error' => 'carts not found'], 404);
        }
        return response()->json(['carts' => $carts]);
    }

    public function updateCart(int $variant_id, int $profile_id, int $quantity) { // maybe wrong
        $cart = Cart::where('profile_id', $profile_id)->where('product_variant_id', $variant_id)->first();
        if ($cart) {
            $cart->amount = $quantity;
            $cart->save();
        }
    }

    public function deleteCart(int $variant_id, int $profile_id) { // maybe wrong
        $cart = Cart::where('profile_id', $profile_id)->where('product_variant_id', $variant_id)->first();
        if ($cart) {
            $cart->delete();
        } else {
            return response()->json(['error' => 'cart not found'], 404);
        }
    }

    // 3.5. Áp dụng mã giảm giá
    public function promotion() {
        // TODO
    }
}
