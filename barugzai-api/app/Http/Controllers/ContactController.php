<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function index()
    {
        $meta = Contact::all();
        return view('contact.index', compact('meta'));
    }

    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone_no' => 'required|string',
            'whatsapp_no' => 'required|string',
            'status' => 'required|string',
        ]);

        Contact::create($validated);
        return redirect('contact');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Contact::find($id);
        return view('contact.edit', compact('data'));
    }

    public function update(Request $request, $id)
{
    $data = Contact::findOrFail($id);

    // If setting this contact to active, set all others inactive first
    if ($request->status === '1') {
        Contact::where('id', '!=', $id)->update(['status' => '0']);
    }

    // Update the current contact
    $data->update([
        'name'        => $request->name,
        'email'       => $request->email,
        'phone_no'    => $request->phone_no,
        'whatsapp_no' => $request->whatsapp_no,
        'status'      => $request->status,
    ]);

    return redirect('contact')->with('success', 'Contact updated successfully.');
}


     public function destroy($id)
    {
        $data = Contact::find($id);
        $data->delete();

        return redirect('contact')->with('success', 'Contact Person deleted successfully.');
    }
}
