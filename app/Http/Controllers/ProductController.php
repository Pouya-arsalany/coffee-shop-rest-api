<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function viewer()
    {
        $products = Product::all();
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'products' => $products,
            'categories' => $categories,
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:products,title|max:25',
            'price' => 'required|numeric|min:1|max:255',
            'description' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,bmp,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'Image is required.'], 422);
        }

        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:25|unique:products,title,' . $product->id,
            'price' => 'required|numeric|min:1|max:255',
            'description' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,bmp,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'title' => $request->title,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('query');

        $products = Product::where('title', 'like', '%' . $search . '%')->get();

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

}
