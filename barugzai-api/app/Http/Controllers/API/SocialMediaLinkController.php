<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialMediaLink;

class SocialMediaLinkController extends Controller
{
    public function get()
    {
        try {
            $links = SocialMediaLink::first();

            if (!$links) {
                return response()->json(['error' => 'No data found'], 404);
            }

            return response()->json(['data' => $links], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch social media links',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'facebook' => 'nullable|url',
                'instagram' => 'nullable|url',
                'x' => 'nullable|url',
                'tiktok' => 'nullable|url',
                'youtube' => 'nullable|url',
            ]);

            $links = SocialMediaLink::first();

            if ($links) {
                $links->update($validated);
            } else {
                $links = SocialMediaLink::create($validated);
            }

            return response()->json(['data' => $links, 'message' => 'Social media links updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation Error', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update social media links', 'details' => $e->getMessage()], 500);
        }
    }
}
