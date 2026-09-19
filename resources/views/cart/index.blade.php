@extends('layouts.shop')

@section('content')
    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 mb-6">Your Cart</h1>

    @if (empty($cart))
        <div class="empty-state">
            <p class="text-slate-500">Your cart is empty.</p>
            <a href="{{ route('home') }}" class="btn-muted block-link mt-4">Browse products &rarr;</a>
        </div>
    @else
        <div class="card-lg">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-2">Product</th>
                        <th class="py-2">Price</th>
                        <th class="py-2">Quantity</th>
                        <th class="py-2">Subtotal</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $item)
                        <tr class="border-b border-slate-100">
                            <td class="py-3">{{ $item['name'] }}</td>
                            <td class="py-3">${{ number_format($item['price'], 2) }}</td>
                            <td class="py-3">
                                <form action="{{ route('cart.update', $item['product_id']) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-input w-20">
                                    <button type="submit" class="btn-muted">Update</button>
                                </form>
                            </td>
                            <td class="py-3">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td class="py-3">
                                <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
                <span class="text-xl font-semibold text-slate-900">Total: ${{ number_format($total, 2) }}</span>

                @auth
                    <a href="{{ route('checkout.index') }}" class="btn-primary">Proceed to Checkout</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Log in to Checkout</a>
                @endauth
            </div>
        </div>
    @endif
@endsection
