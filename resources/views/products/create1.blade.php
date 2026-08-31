<h1>Add Product</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <label>Name:</label>
    <input type="text" name="name" required><br><br>

    <label>Description:</label>
    <textarea name="description"></textarea><br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Stock:</label>
    <input type="number" name="stock" required><br><br>

    <button type="submit">Save Product</button>
</form>