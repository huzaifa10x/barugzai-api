<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\Models\ServiceHeader;

class ServiceController extends Controller
{
    public function index()
    {
        try {
            // Fetch the first service header text (since there's only one record)
            $header = ServiceHeader::first();
            
            // Fetch all services
            $services = Service::all();
    
            return response()->json([
                'header_text' => $header ? $header->header_text : null, // Return header text
                'data' => $services // Return services list
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch services',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'components' => 'nullable|string',
            'styles' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }

        try {
            $data = $request->only(['title', 'description', 'components', 'styles']);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('services', 'public');
            }

            $service = Service::create($data);

            return response()->json(['data' => $service, 'message' => 'Service created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create service', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $service = Service::findOrFail($id);
            return response()->json(['data' => $service], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Service not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming data
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors()
            ], 422);
        }
    
        try {
            // Fetch the existing service
            $service = Service::findOrFail($id);
    
            // Update image if provided in the form-data
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($service->image && \Storage::disk('public')->exists($service->image)) {
                    \Storage::disk('public')->delete($service->image);
                }
    
                // Upload the new image
                $path = $request->file('image')->store('services', 'public');
                $service->image = $path;
            }
    
            // Update fields individually to ensure proper handling of nullable fields
            if ($request->has('title')) {
                $service->title = $request->input('title');
            }
            if ($request->has('description')) {
                $service->description = $request->input('description');
            }
    
            // Save the updated service
            $service->save();
    
            return response()->json(['data' => $service, 'message' => 'Service updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Service not found',
                'details' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update service',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
    



    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);

            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->delete();

            return response()->json(['message' => 'Service deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Service not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete service', 'details' => $e->getMessage()], 500);
        }
    }


    public function updateHeader(Request $request)
    {
        try {
            $validated = $request->validate([
                'header_text' => 'required|string'
            ]);

            $header = ServiceHeader::first();

            if ($header) {
                $header->update($validated);
            } else {
                $header = ServiceHeader::create($validated);
            }

            return response()->json(['data' => $header, 'message' => 'Service header text updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update service header', 'details' => $e->getMessage()], 500);
        }
    }
}
