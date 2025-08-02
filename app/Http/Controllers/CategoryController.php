<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:categories,title|max:25',
            'image' => 'required|image|mimes:jpg,jpeg,png,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagePath = $request->file('image')->store('categories', 'public');

        $category = Category::create([
            'title' => $request->title,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:25|unique:categories,title,' . $category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
        } else {
            $imagePath = $category->image;
        }

        $category->update([
            'title' => $request->title,
            'image' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('query');

        $categories = Category::where('title', 'like', '%' . $search . '%')->get();

        return response()->json([
            'success' => true,
            'results' => $categories,
        ]);
    }
}
