@extends('layouts.shop')

@section('content')
    <a href="{{ route('home') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">&larr; Back to products</a>

    <div class="mt-6 max-w-2xl rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $product->name }}</h1>

        @if ($product->description)
            <p class="mt-4 text-slate-600">{{ $product->description }}</p>
        @endif

        <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
            <span class="text-2xl font-semibold text-slate-900">${{ number_format($product->price, 2) }}</span>

            @if ($product->stock > 0)
                <span class="text-sm font-medium text-teal-700">{{ $product->stock }} in stock</span>
            @else
                <span class="text-sm font-medium text-red-600">Out of stock</span>
            @endif
        </div>

        @auth
            @if (auth()->user()->is_admin)
                <div class="mt-6 flex gap-4 border-t border-slate-100 pt-6 text-sm">
                    <a href="{{ route('products.edit', $product) }}" class="font-medium text-slate-600 hover:text-slate-900">
                        Edit product
                    </a>

                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-medium text-red-600 hover:text-red-700">
                            Delete product
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
@endsection
