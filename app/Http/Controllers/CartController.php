<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Show the cart contents.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add a product to the cart (or increase quantity if already present).
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) $request->input('quantity', 1);
        $cart = Session::get('cart', []);

        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock) {
            return back()->with('error', "Only {$product->stock} in stock for {$product->name}.");
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $newQty,
        ];

        Session::put('cart', $cart);

        return back()->with('success', "{$product->name} added to cart.");
    }

    /**
     * Update the quantity of one cart line.
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::findOrFail($productId);

            if ($request->quantity > $product->stock) {
                return back()->with('error', "Only {$product->stock} in stock.");
            }

            $cart[$productId]['quantity'] = (int) $request->quantity;
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove a single product from the cart.
     */
    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Empty the whole cart.
     */
    public function clear()
    {
        Session::forget('cart');

        return back()->with('success', 'Cart cleared.');
    }
}
