<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    public function index()
    {
        try {
            $sellers = Seller::all();
            return response()->json(['data' => $sellers], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch sellers', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:sellers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
            ]);

            $seller = Seller::create($validated);
            return response()->json(['data' => $seller, 'message' => 'Seller created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create seller', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $seller = Seller::findOrFail($id);
            return response()->json(['data' => $seller], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Seller not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch seller', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:sellers,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
            ]);

            $seller = Seller::findOrFail($id);
            $seller->update($validated);

            return response()->json(['data' => $seller, 'message' => 'Seller updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'details' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Seller not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update seller', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $seller = Seller::findOrFail($id);
            $seller->delete();
            return response()->json(['message' => 'Seller deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Seller not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete seller', 'details' => $e->getMessage()], 500);
        }
    }
}
