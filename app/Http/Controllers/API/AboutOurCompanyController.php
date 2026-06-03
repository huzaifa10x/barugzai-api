<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutOurCompany;

class AboutOurCompanyController extends Controller
{
    public function get()
    {
        try {
            // Attempt to fetch the first record
            $data = AboutOurCompany::first();
    
            if (!$data) {
                return response()->json([
                    'error' => 'No data found',
                    'details' => 'The About Our Company record does not exist.'
                ], 404);
            }
    
            return response()->json(['data' => $data], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            return response()->json([
                'error' => 'Database Query Error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json([
                'error' => 'Failed to fetch About Our Company',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'value' => 'required|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors()
            ], 422);
        }
    
        try {
            // Fetch the first record or create one
            $about = AboutOurCompany::first();
    
            if ($about) {
                $about->update($validated);
            } else {
                $about = AboutOurCompany::create($validated);
            }
    
            return response()->json(['data' => $about, 'message' => 'About Our Company updated successfully'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            return response()->json([
                'error' => 'Database Query Error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return response()->json([
                'error' => 'Failed to update About Our Company',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
}
