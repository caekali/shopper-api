<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;

class HomeController extends BaseController
{
    public function index()
    {
        $categories = Category::take(8)->get();
        $topSelling = Product::all();
        $newIn = Product::take(8)->get();

        return $this->successResponse([
            'categories' => CategoryResource::collection($categories),
            'top_selling' => ProductResource::collection($topSelling),
            'new_in' => ProductResource::collection($newIn),
        ]);
    }
}
