<x-app-layout>
    <x-slot name="header">
        <h2>Owner Dashboard</h2>
    </x-slot>

    <div class="p-6">
        <p>Welcome back, {{ auth()->user()->name }}.</p>

        <a href="{{ route('products.index') }}">View All Products</a><br>  
        <a href="{{ route('products.create') }}">+ Add New Product</a>
    </div>
</x-app-layout>