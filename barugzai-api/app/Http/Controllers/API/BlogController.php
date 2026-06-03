<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Initialize query builder
            $query = Blog::with('category')->orderBy('created_at', 'desc');
    
            // Apply search filter if the 'search' parameter is provided
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
                });
            }
    
            // Paginate results
            $blogs = $query->paginate(10);
    
            return response()->json(['data' => $blogs], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch blogs', 'details' => $e->getMessage()], 500);
        }
    }
    

    // Fetch a single blog by ID and increment views
    public function show($id)
    {
        try {
            $blog = Blog::with('category')->findOrFail($id);
            $blog->increment('views_count');
            return response()->json(['data' => $blog], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Blog not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch blog', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'blog_category_id' => 'required|exists:blog_categories,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            try {
                // Handle image upload
                $imageFile = $request->file('image');
                if (!$imageFile->isValid()) {
                    return response()->json(['error' => 'Invalid image file.'], 422);
                }
    
                $path = $imageFile->store('blogs', 'public'); // Save to storage/app/public/blogs
    
                // Create blog
                $blog = Blog::create([
                    'blog_category_id' => $validated['blog_category_id'],
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'image' => $path, // Store the image path in the database
                ]);
    
                // Prepare the response with the actual image file
                $response = $blog->toArray();
                $response['image'] = asset('storage/' . $path); // Add full URL to the image
    
                return response()->json(['data' => $response, 'message' => 'Blog created successfully'], 201);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to upload image or create blog.', 'details' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }
    
    public function destroy($id)
{
    try {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully'], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Blog not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete blog', 'details' => $e->getMessage()], 500);
    }
}

}
