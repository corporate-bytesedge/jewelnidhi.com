<?php

namespace App\Http\Controllers;

use App\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Collection::get()->first();
        
        return view('manage.collection.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array_except($request->all(), ['_token']);
         
        if($request->hasFile('image_one')) {
            $file = $request->file('image_one');
            $filename = $request->image_one->getClientOriginalName();
            $path = $file->storeAs('public/collection/', $filename);
            $data['image_one'] = $filename;
        }
        if($request->hasFile('image_two')) {
            $file = $request->file('image_two');
            $image_two = $request->image_two->getClientOriginalName();
            $path = $file->storeAs('public/collection/', $image_two);
            $data['image_two'] = $image_two;
        }
        if($request->hasFile('image_three')) {
            $file = $request->file('image_two');
            $image_three = $request->image_three->getClientOriginalName();
            $path = $file->storeAs('public/collection/', $image_three);
            $data['image_three'] = $image_three;
        }

        Collection::create($data);
        return redirect()->route('manage.collection.index')->with('message', 'Collection has been save successfully');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        
        $collection = Collection::findOrFail($request->id);

        $data = array_except($request->all(), ['_token']);
         
        if($request->hasFile('image_one')) {
            $file = $request->file('image_one');
            $filename = $request->image_one->getClientOriginalName();
            $path = $file->storeAs('public/collection/', $filename);
            $data['image_one'] = $filename;
        }
        if($request->hasFile('image_two')) {
            $file = $request->file('image_two');
            $image_two = $request->image_two->getClientOriginalName();
            $path = $file->storeAs('public/collection/', $image_two);
            $data['image_two'] = $image_two;
        }
        if($request->hasFile('image_three')) {
            $file = $request->file('image_three');
            $image_three = $request->image_three->getClientOriginalName();
            $path = $file->storeAs('public/collection/', $image_three);
            $data['image_three'] = $image_three;
        }
         
        $collection->update($data);
        return redirect()->route('manage.collection.index')->with('message', 'Collection has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
