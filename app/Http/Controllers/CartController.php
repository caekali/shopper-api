<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $cart = $user->cart()->first();
        if (! $cart) {
            $cart = $user->cart()->create([
                'total_amount' => 0,
            ]);
        }

        return $this->successResponse($cart->load('items.product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $cart = $user->cart()->first();
        if (! $cart) {
            $cart = $user->cart()->create([
                'total_amount' => 0,
            ]);
        }

        $product = Product::findOrFail($data['product_id']);
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->subtotal = $cartItem->quantity * $product->price;
            $cartItem->save();
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $data['quantity'],
            ]);
        }

        $cart->total_amount = $cart->items()->sum('subtotal');
        $cart->save();

        return $this->successResponse([
            'cart' => $cart->load('items.product'),
        ], 'Product added to cart successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();

        $cart = $user->cart()->first();
        if (! $cart) {
            return $this->errorResponse(message: 'Cart Not Found', code: 404);

        }

        $product = Product::findOrFail($data['product_id']);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        $cartItem->delete();

        return $this->successResponse(message: 'Cart Item cleared');

    }

    public function clear(Request $request)
    {
        $user = Auth::user();

        $cart = $user->cart()->first();

        if ($cart) {
            $cart->delete();

            return $this->successResponse(message: 'Cart cleared');
        }

        return $this->errorResponse(message: 'Cart Not Found', code: 404);
    }

    
}
