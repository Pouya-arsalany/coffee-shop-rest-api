<?php


namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // GET /api/menu
    public function index()
    {
        $categories = Category::all();
        $products = Product::all();

        return response()->json([
            'success' => true,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function filterByCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $products = Product::where('category_id', $id)->get();

        return response()->json([
            'success' => true,
            'category' => $category,
            'products' => $products
        ]);
    }
}

