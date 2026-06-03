<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageSlider;

class ImageSliderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image_name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        try {
            // Store the image
            $path = $request->file('image')->store('sliders', 'public');

            // Create the record in the database
            $slider = ImageSlider::create([
                'image_name' => $request->input('image_name'),
                'image_path' => $path,
            ]);

            return response()->json(['data' => $slider, 'message' => 'Image slider created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create image slider', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve all image sliders.
     */
    public function index()
    {
        $sliders = ImageSlider::all();

        return response()->json(['data' => $sliders]);
    }

    /**
     * Delete an image slider.
     */
    public function destroy($id)
    {
        try {
            $slider = ImageSlider::findOrFail($id);

            // Delete the image file
            Storage::disk('public')->delete($slider->image_path);

            // Delete the database record
            $slider->delete();

            return response()->json(['message' => 'Image slider deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete image slider', 'details' => $e->getMessage()], 500);
        }
    }
}
