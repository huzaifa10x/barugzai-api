<?php

namespace App\Http\Controllers;

use App\Models\MetaData;
use Illuminate\Http\Request;


class MetaDataController extends Controller
{
    public function index()
    {
        $meta = MetaData::all();
        return view('metaData.index', compact('meta'));
    }

    public function create()
    {
        return view('metaData.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'required|string',
            'meta_description' => 'required|string',
            'slug' => 'required|string',
        ]);

        MetaData::create($validated);
        return redirect('meta-data');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = MetaData::find($id);
        return view('metaData.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = MetaData::find($id);
 
        $data->update([
            'meta_title' => $request->meta_title,
            'meta_description'=> $request->meta_description,
            'slug'=> $request->slug,

            
        ]);
        
        return redirect('meta-data');
    }
}
