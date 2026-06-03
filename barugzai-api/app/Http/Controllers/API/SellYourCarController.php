<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellYourCar;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;

class SellYourCarController extends Controller
{
    public function index()
    {
        try {
            $sellYourCars = SellYourCar::all();
            return response()->json(['data' => $sellYourCars], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch records', 'details' => $e->getMessage()], 500);
        }
    }
    
    

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'manufacturer' => 'required|string',
        'model' => 'required|string',
        'model_year' => 'required|string',
        'chassis_no' => 'required|string',
        'kilometers' => 'required|string',
        'engine_size' => 'required|string',
        'vehicle_options' => 'nullable|string',
        'expected_price' => 'nullable|string',
        'description' => 'nullable|string',
        'full_name' => 'required|string',
        'mobile_number' => 'required|string',
        'email' => 'required|email',
        // 'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
    }

    try {
        // Create SellYourCar record
        $sellYourCar = SellYourCar::create($validator->validated());

        // Check if images are present
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    // Save image to storage
                    $path = $image->store('sell_your_cars', 'public');
                    \Log::info('Image Path: ' . $path); // Debugging

                    // Create image record
                    $sellYourCar->images()->create(['url' => $path]);
                } catch (\Exception $e) {
                    \Log::error('Image Upload Error: ' . $e->getMessage());
                    return response()->json(['error' => 'Failed to upload image', 'details' => $e->getMessage()], 500);
                }
            }
        } else {
            \Log::warning('No images were uploaded.');
        }

        // Load images
        $sellYourCar->load('images');

        return response()->json([
            'data' => $sellYourCar,
            'message' => 'Record created successfully'
        ], 201);
    } catch (\Exception $e) {
        \Log::error('Store Error: ' . $e->getMessage());
        return response()->json([
            'error' => 'Failed to create record',
            'details' => $e->getMessage()
        ], 500);
    }
}




    public function show($id)
    {
        try {
            $sellYourCar = SellYourCar::findOrFail($id);
            return response()->json(['data' => $sellYourCar], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch record', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'manufacturer' => 'required|string',
            'model' => 'required|string',
            'model_year' => 'required|string',
            'chassis_no' => 'required|string',
            'kilometers' => 'required|string',
            'engine_size' => 'required|string',
            'vehicle_options' => 'nullable|string',
            'expected_price' => 'nullable|string',
            'description' => 'nullable|string',
            'full_name' => 'required|string',
            'mobile_number' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $sellYourCar = SellYourCar::findOrFail($id);
            $sellYourCar->update($validator->validated());
            return response()->json(['data' => $sellYourCar, 'message' => 'Record updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update record', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sellYourCar = SellYourCar::findOrFail($id);
            $sellYourCar->delete();
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete record', 'details' => $e->getMessage()], 500);
        }
    }
}
