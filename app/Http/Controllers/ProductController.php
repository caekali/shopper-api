<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends BaseController
{
    public function index(Request $request, ?int $categoryId = null)
    {
        if ($categoryId) {
            Category::findOrFail($categoryId);
        }

        $products = Product::search($request->get('q', ''))
            ->query(function ($query) use ($request, $categoryId) {
                $query->when($categoryId ?: $request->category_id, function ($q, $id) {
                    $q->where('category_id', $id);
                })
                    ->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $request->min_price))
                    ->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $request->max_price));
            })
            
            // 3. Apply Sorting
        
            ->when($request->filled('sort'), function ($scoutQuery) use ($request) {
                return match ($request->sort) {
                    'price_asc' => $scoutQuery->orderBy('price', 'asc'),
                    'price_desc' => $scoutQuery->orderBy('price', 'desc'),
                    'latest' => $scoutQuery->orderBy('created_at', 'desc'),
                    default => $scoutQuery
                };
            })
            ->paginate($request->get('per_page', 15))
            ->withQueryString();

        return $this->successResponse(
            ProductResource::collection($products)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:products,name',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'required|string',
            'price' => 'required|decimal:2',
            'stock' => 'required|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = $path;
        }

        Product::create($data);

        return $this->successResponse(message: 'Product added');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrfail($id);

        return $this->successResponse(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:products,name,'.$id,
            'slug' => 'required|string|unique:products,slug,'.$id,
            'description' => 'required|string',
            'price' => 'required|decimal:2',
            'stock' => 'required|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('image')) {

            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            $path = $request->file('image')->store('products', 'public');

            $data['image_url'] = $path;
        }

        $product->update($data);

        return $this->successResponse(message: 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();

            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            return $this->successResponse(message: 'Product Deleted');

        }

        return $this->successResponse(message: 'Product not found with id '.$id);

    }
}
