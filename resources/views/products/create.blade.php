@extends('layouts.shop')

@section('content')
    <a href="{{ route('home') }}" class="back-link">&larr; Back to products</a>

    <div class="card-lg panel-narrow mt-6">
        <h1 class="text-xl font-semibold tracking-tight text-slate-900">Add a product</h1>

        <form action="{{ route('products.store') }}" method="POST" class="mt-6">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input">{{ old('description') }}</textarea>
            </div>

            <div class="form-grid-2 form-group">
                <div>
                    <label for="price" class="form-label">Price</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}" required class="form-input">
                </div>

                <div>
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" min="0" name="stock" id="stock" value="{{ old('stock') }}" required class="form-input">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Save product</button>
                <a href="{{ route('home') }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>
@endsection