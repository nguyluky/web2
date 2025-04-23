<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function addCart(Request $request) {
        $validated = $request->validate([
            'profile_id' => 'required|integer',
            'product_variant_id' => 'required|integer',
            'amount' => 'nullable|integer',
        ]);

        $cart = Cart::create($validated);
        return response()->json(['cart' => $cart], 201);
    }

    public function getAllCart() {
        // TODO
    }
}
