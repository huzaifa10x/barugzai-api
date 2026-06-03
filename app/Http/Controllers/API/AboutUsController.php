<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutUs;
use App\Models\AboutUsImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class AboutUsController extends Controller
{
    public function get()
    {
        try {
            $aboutUs = AboutUs::with('images')->first();

            if (!$aboutUs) {
                return response()->json(['error' => 'No record found'], 404);
            }

            return response()->json(['data' => $aboutUs], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch About Us', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text_header_1' => 'sometimes|string|max:255',
            'description_1' => 'sometimes|string',
            'text_1' => 'sometimes|string|nullable',
            'points' => 'sometimes|array',
            'points.*' => 'string',
            'header_2' => 'sometimes|string|nullable',
            'description_2' => 'sometimes|string|nullable',
            'header_3' => 'sometimes|string|nullable',
            'description_3' => 'sometimes|string|nullable',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // For updating images
            'image_positions' => 'nullable|array',
            'image_positions.*' => 'integer|min:1|max:5' // Ensure only 5 images exist
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 422);
        }
    
        try {
            $aboutUs = AboutUs::firstOrCreate([]);
            $aboutUs->update($request->only([
                'text_header_1',
                'description_1',
                'text_1',
                'points',
                'header_2',
                'description_2',
                'header_3',
                'description_3'
            ]));
    
            // Update images (replace based on position)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $position = $request->image_positions[$index] ?? null;
                    if (!$position || $position < 1 || $position > 5) continue;
    
                    // Find the existing image at the position
                    $existingImage = AboutUsImage::where('about_us_id', $aboutUs->id)->skip($position - 1)->first();
                    if ($existingImage) {
                        Storage::disk('public')->delete($existingImage->url); // Delete old image
                        $path = $image->store('about_us', 'public'); // Store new image
                        $existingImage->update(['url' => $path]); // Update existing record
                    }
                }
            }
    
            return response()->json([
                'data' => $aboutUs->load('images'),
                'message' => 'About Us updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update About Us', 'details' => $e->getMessage()], 500);
        }
    }
    

}
