<h1>Edit Product</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" value="{{ $product->name }}" required><br><br>

    <label>Description:</label>
    <textarea name="description">{{ $product->description }}</textarea><br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="{{ $product->price }}" required><br><br>

    <label>Stock:</label>
    <input type="number" name="stock" value="{{ $product->stock }}" required><br><br>

    <button type="submit">Update Product</button>
</form>