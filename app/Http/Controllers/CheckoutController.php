<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page and create a pending Razorpay order.
     */
    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // Razorpay expects the amount in paise (smallest currency unit)
        $razorpayOrder = $api->order->create([
            'receipt' => 'order_rcpt_' . uniqid(),
            'amount' => (int) round($total * 100),
            'currency' => 'INR',
        ]);

        return view('checkout.index', [
            'cart' => $cart,
            'total' => $total,
            'razorpayOrderId' => $razorpayOrder['id'],
            'razorpayKey' => config('services.razorpay.key'),
        ]);
    }

    /**
     * Verify the payment signature Razorpay's JS checkout sends back,
     * then persist the Order + OrderItems and decrement stock.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', 'Payment verification failed. Please try again.');
        }

        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $order = DB::transaction(function () use ($cart, $total, $request) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'paid',
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                // Database-level decrement — avoids the read-modify-write race
                // condition, same pattern as the stock API endpoints.
                Product::where('id', $item['product_id'])
                    ->where('stock', '>=', $item['quantity'])
                    ->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        Session::forget('cart');

        return redirect()->route('checkout.success', $order)->with('success', 'Payment successful!');
    }

    /**
     * Order confirmation page.
     */
    public function success(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        return view('checkout.success', compact('order'));
    }
}
