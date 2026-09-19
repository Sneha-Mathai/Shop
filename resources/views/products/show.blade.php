@extends('layouts.shop')

@section('content')
    <a href="{{ route('home') }}" class="back-link">&larr; Back to products</a>

    <div class="card-lg panel-medium mt-6">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $product->name }}</h1>

        @if ($product->description)
            <p class="mt-4 text-slate-600">{{ $product->description }}</p>
        @endif
        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4 flex items-center gap-3">
    @csrf
    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-input w-20">
    <button type="submit" class="btn-primary" @if($product->stock < 1) disabled @endif>Add to Cart</button>
</form>

        <div class="product-footer">
            <span class="price-lg">${{ number_format($product->price, 2) }}</span>

            @if ($product->stock > 0)
                <span class="stock-in-lg">{{ $product->stock }} in stock</span>
            @else
                <span class="stock-out-lg">Out of stock</span>
            @endif
        </div>

        @auth
            @if (auth()->user()->is_admin)
                <div class="card-actions">
                    <a href="{{ route('products.edit', $product) }}" class="btn-muted">Edit product</a>

                    <form action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          class="js-confirm-delete"
                          data-confirm-message="Delete this product?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete product</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
@endsection