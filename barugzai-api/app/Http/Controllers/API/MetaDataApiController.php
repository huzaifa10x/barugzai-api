<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MetaData;
use Illuminate\Http\Request;

class MetaDataApiController extends Controller
{
    public function getBySlug($slug)
    {
        $meta = MetaData::where('slug', $slug)->first();

        if (!$meta) {
            return response()->json([
                'status' => false,
                'message' => 'Meta data not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $meta,
        ]);
    }
}
