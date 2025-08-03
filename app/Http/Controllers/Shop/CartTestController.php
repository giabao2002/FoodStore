<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartTestController extends Controller
{
    /**
     * A diagnostic tool to check the cart functionality
     */
    public function test()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'authenticated' => false,
                'message' => 'User is not authenticated'
            ]);
        }

        $user = Auth::user();

        // Get cart items
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        // Return diagnostic information
        return response()->json([
            'status' => 'success',
            'authenticated' => true,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'cart_count' => $cartItems->count(),
            'cart_items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'subtotal' => $item->quantity * $item->product->price
                ];
            })
        ]);
    }
}
