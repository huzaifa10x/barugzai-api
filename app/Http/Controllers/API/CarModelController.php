<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarModel;
use App\Models\Manufacturer;


class CarModelController extends Controller
{
    public function index()
    {
        try {
            $carModels = CarModel::with('manufacturer')->get();
            return response()->json(['data' => $carModels], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch car models', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'name' => 'required|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $carModel = CarModel::create($validator->validated());
            return response()->json(['data' => $carModel, 'message' => 'Car model created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create car model', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $carModel = CarModel::with('manufacturer')->findOrFail($id);
            return response()->json(['data' => $carModel], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car model not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch car model', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'name' => 'required|string|max:255',
            'body_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $carModel = CarModel::findOrFail($id);
            $carModel->update($validator->validated());
            return response()->json(['data' => $carModel, 'message' => 'Car model updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car model not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update car model', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $carModel = CarModel::findOrFail($id);
            $carModel->delete();
            return response()->json(['message' => 'Car model deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Car model not found', 'details' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete car model', 'details' => $e->getMessage()], 500);
        }
    }


   

    
    

}
