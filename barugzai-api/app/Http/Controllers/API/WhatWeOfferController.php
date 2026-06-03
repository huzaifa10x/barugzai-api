<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatWeOffer;

class WhatWeOfferController extends Controller
{
    public function get()
    {
        try {
            $data = WhatWeOffer::first();
    
            if (!$data) {
                return response()->json([
                    'error' => 'No data found',
                    'details' => 'The What We Offer record does not exist.'
                ], 404);
            }
    
            // Transform images into an array
            $data->images = array_values((array) $data->images); 
    
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch What We Offer',
                'details' => $e->getMessage()
                ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'description' => 'required|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // New images
                'remove_images' => 'nullable|array', // Images to be removed
                'remove_images.*' => 'string', // Paths of images to be removed
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors()
            ], 422);
        }
    
        try {
            // Fetch the record
            $whatWeOffer = WhatWeOffer::first();
    
            if (!$whatWeOffer) {
                return response()->json([
                    'error' => 'Record not found',
                    'details' => 'No record exists for What We Offer.'
                ], 404);
            }
    
            // Handle removing images
            $currentImages = $whatWeOffer->images ?? [];
            if ($request->filled('remove_images')) {
                foreach ($request->input('remove_images') as $imagePath) {
                    // Remove the image file from storage
                    if (in_array($imagePath, $currentImages)) {
                        \Storage::disk('public')->delete($imagePath); // Delete file
                        $currentImages = array_diff($currentImages, [$imagePath]); // Remove from array
                    }
                }
            }
    
            // Handle adding new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('what_we_offer', 'public');
                    $currentImages[] = $path;
                }
            }
    
            // Update the record
            $whatWeOffer->update([
                'description' => $validated['description'],
                'images' => $currentImages,
            ]);
    
            return response()->json(['data' => $whatWeOffer, 'message' => 'What We Offer updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update What We Offer',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    
}
