<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function index()
{
    $contacts = Contact::where('status', '1')->get();

    if ($contacts->isEmpty()) {
        return response()->json([
            'status'  => false,
            'message' => 'No active contacts found',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data'   => $contacts,
    ]);
}

}
