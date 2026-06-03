<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    public function show()
    {
        $section = Section::first();

        if (!$section) {
            return response()->json(['message' => 'No section record found'], 404);
        }

        return response()->json(['data' => $section], 200);
    }

    /**
     * Update the single section record.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'new_arrival_title' => 'nullable|string|max:255',
            'new_arrival_description' => 'nullable|string',
            'explore_service_title' => 'nullable|string|max:255',
            'explore_service_subtitle' => 'nullable|string|max:255',
            'explore_service_description' => 'nullable|string',
            'videos_title' => 'nullable|string|max:255',
            'videos_description' => 'nullable|string',
            'youtube_video_1' => 'nullable|string', // Added validation for youtube_video_1
            'youtube_video_2' => 'nullable|string', // Added validation for youtube_video_2
            'youtube_video_3' => 'nullable|string', // Added validation for youtube_video_3
            'youtube_video_4' => 'nullable|string', // Added validation for youtube_video_4
        ]);
    
        $section = Section::first();
    
        if (!$section) {
            $section = Section::create($validated);
        } else {
            $section->update($validated);
        }
    
        return response()->json(['data' => $section, 'message' => 'Section updated successfully'], 200);
    }
}
