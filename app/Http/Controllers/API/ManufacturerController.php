<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    public function index()
    {
        try {
            $manufacturers = Manufacturer::all();
            return response()->json(['data' => $manufacturers], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch manufacturers', 'details' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $imagePath = $request->file('image')->store('manufacturers', 'public');

            $manufacturer = Manufacturer::create([
                'title' => $request->title,
                'image' => $imagePath,
                'description' => $request->description,
            ]);

            return response()->json(['data' => $manufacturer, 'message' => 'Manufacturer created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create manufacturer', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            return response()->json(['data' => $manufacturer], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Manufacturer not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch manufacturer', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $manufacturer = Manufacturer::findOrFail($id);

            // Update fields
            $manufacturer->title = $request->title;
            $manufacturer->description = $request->description;

            // Handle image update if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($manufacturer->image) {
                    Storage::disk('public')->delete($manufacturer->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('manufacturers', 'public');
                $manufacturer->image = $imagePath;
            }

            $manufacturer->save();

            return response()->json(['data' => $manufacturer, 'message' => 'Manufacturer updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Manufacturer not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update manufacturer', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $manufacturer = Manufacturer::findOrFail($id);
            $manufacturer->delete();
            return response()->json(['message' => 'Manufacturer deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Manufacturer not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete manufacturer', 'details' => $e->getMessage()], 500);
        }
    }


    public function getModelsByManufacturer(Request $request)
{
    try {
        // Validate the manufacturer_id parameter
        $validated = $request->validate([
            'manufacturer_id' => 'required|exists:manufacturers,id',
        ]);

        // Find the manufacturer
        $manufacturer = Manufacturer::findOrFail($validated['manufacturer_id']);

        // Fetch car models associated with the manufacturer
        $carModels = $manufacturer->carModels;

        if ($carModels->isEmpty()) {
            return response()->json([
                "error" => "Car model not found",
                "details" => "No car models found for the given manufacturer_id"
            ], 404);
        }

        return response()->json(['data' => $carModels, 'message' => 'Car models fetched successfully'], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation Error',
            'details' => $e->errors()
        ], 422);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Manufacturer not found',
            'details' => $e->getMessage()
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch car models',
            'details' => $e->getMessage()
        ], 500);
    }
}

    }
