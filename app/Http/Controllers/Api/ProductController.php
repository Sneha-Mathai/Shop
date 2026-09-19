<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductApiRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request; 

class ProductController extends Controller
{
    /**
     * GET /api/products
     */
    public function index(): JsonResponse
    {
        $products = Product::latest()->paginate(15);

        return ProductResource::collection($products)->response();
    }

    /**
     * GET /api/products/{product}
     */
    public function show(Product $product): JsonResponse
    {
        return (new ProductResource($product))->response();
    }

    /**
     * POST /api/products
     */
    public function store(ProductApiRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED); // 201
    }

    /**
     * PUT/PATCH /api/products/{product}
     */
    public function update(ProductApiRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return (new ProductResource($product))->response(); // 200
    }

    /**
     * DELETE /api/products/{product}
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT); // 204
    }

    use Illuminate\Http\Request;

/**
 * GET /api/products/low-stock?threshold=10
 * Returns products at or below the given stock threshold.
 */
public function lowStock(Request $request): JsonResponse
{
    $threshold = (int) $request->query('threshold', 10);

    $products = Product::where('stock', '<=', $threshold)
        ->orderBy('stock')
        ->get();

    return ProductResource::collection($products)->response();
}

/**
 * PATCH /api/products/{product}/stock
 * Adjusts stock by a relative amount (can be negative).
 * Body: { "adjustment": -5 }  or  { "adjustment": 20 }
 */
public function adjustStock(Request $request, Product $product): JsonResponse
{
    $validated = $request->validate([
        'adjustment' => ['required', 'integer'],
    ]);

    $newStock = $product->stock + $validated['adjustment'];

    if ($newStock < 0) {
        return response()->json([
            'message' => 'Adjustment would result in negative stock.',
            'current_stock' => $product->stock,
        ], 422);
    }

    $product->update(['stock' => $newStock]);

    return (new ProductResource($product))->response();
}
}