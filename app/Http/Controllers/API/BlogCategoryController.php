<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = BlogCategory::all();
            return response()->json(['data' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch categories', 'details' => $e->getMessage()], 500);
        }
    }

    // Get a single category
    public function show($id)
    {
        try {
            $category = BlogCategory::with('blogs')->findOrFail($id);
            return response()->json(['data' => $category], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch category', 'details' => $e->getMessage()], 500);
        }
    }

    // Create a new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $category = BlogCategory::create($validated);
            return response()->json(['data' => $category, 'message' => 'Category created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category', 'details' => $e->getMessage()], 500);
        }
    }

    // Update a category
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $category = BlogCategory::findOrFail($id);
            $category->update($validated);
            return response()->json(['data' => $category, 'message' => 'Category updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update category', 'details' => $e->getMessage()], 500);
        }
    }

    // Delete a category
    public function destroy($id)
    {
        try {
            $category = BlogCategory::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete category', 'details' => $e->getMessage()], 500);
        }
    }
}
