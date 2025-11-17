<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends BaseController
{
    public function checkout(Request $request)
    {
        $user = Auth::user();

        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {

            return $this->errorResponse('Cart is empty', null, 400);
        }

        // Check stock without deducting
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return $this->errorResponse($item->product->name.' is out of stock', null, 400);
            }
        }

        $total = $cart->items->sum('subtotal');

        // Create a pending order
        $order = $user->orders()->create([
            'total_amount' => $total,
            'status' => 'pending',
            // 'payment_gateway' => $request->payment_method,
        ]);

        // Create order items (NO STOCK DEDUCTION YET)
        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quntity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ]);
        }

        // reference to the order
        $tx_ref = 'ORDER_'.$order->id;
        $order->update(['tx_ref' => $tx_ref]);

        // Generate payment request
        $paymentLink = $this->initiatePayment($order);

        return $this->successResponse([
            'order_id' => $order->id,
            'payment_url' => $paymentLink,
        ], 'Payment initiated', 201);

    }

    private function initiatePayment($order)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer SEC-TEST-HctfPOD4otv5THxLZiVt5dQ1XvL6mTxn',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.paychangu.com/payment', [
            'amount' => $order->total_amount,
            'currency' => 'MWK',
            'tx_ref' => $order->tx_ref,
            'callback_url' => 'https://5dbf2abd669c.ngrok-free.app/api/payment/callback',
            'return_url' => 'https://5dbf2abd669c.ngrok-free.app/payment/return-url',
        ]);

        $data = $response->json();

        if (! $response->successful()) {
            return [
                'error' => true,
                'message' => $data['message'] ?? 'Unknown error from PayChangu',
            ];
        }

        $paymentUrl = $data['data']['checkout_url'] ?? null;

        return $paymentUrl;
    }

    public function paymentCallback(Request $request)
    {
        $transactionId = $request->tx_ref;

        $order = Order::where('tx_ref', $transactionId)->first();

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Deduct stock
        foreach ($order->items as $item) {
            $item->product->decrement('stock', $item->quntity);
        }

        // Mark order as paid
        $order->update([
            'status' => 'paid',
        ]);

        // Clear cart
        $order->user->cart->items()->delete();

        return response()->json(['message' => 'Success']);
    }

    public function paymentReturnUrl(Request $request)
    {
        return response()->json($request);
    }
}
