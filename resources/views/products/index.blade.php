@extends('layouts.shop')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Our products</h1>
        <p class="mt-1 text-sm text-slate-500">Browse what we currently have in stock.</p>
    </div>

    @if ($products->isEmpty())
        <div class="empty-state">
            <p class="text-slate-500">No products have been added yet.</p>

            @auth
                @if (auth()->user()->is_admin)
                    <a href="{{ route('products.create') }}" class="btn-muted">
                        Add the first product &rarr;
                    </a>
                @endif
            @endauth
        </div>
    @else
        <div class="product-grid">
     @foreach ($products as $product)
    <div class="product-card">
        <a href="{{ route('products.show', $product) }}" class="product-link">
            <div>
                <span class="product-title">{{ $product->name }}</span>

                @if ($product->description)
                    <p class="product-desc">{{ $product->description }}</p>
                @endif
            </div>

            <div class="product-footer">
                <span class="price">${{ number_format($product->price, 2) }}</span>

                @if ($product->stock > 0)
                    <span class="stock-in">{{ $product->stock }} in stock</span>
                @else
                    <span class="stock-out">Out of stock</span>
                @endif
            </div>
        </a>

        @auth
            @if (auth()->user()->is_admin)
                <div class="card-actions">
                    <a href="{{ route('products.edit', $product) }}" class="btn-muted">Edit</a>

                    <form action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          class="js-confirm-delete"
                          data-confirm-message="Delete this product?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
@endforeach
        </div>
    @endif
@endsection