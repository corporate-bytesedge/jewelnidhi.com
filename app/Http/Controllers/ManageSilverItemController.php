<?php

namespace App\Http\Controllers;

use App\SilverItem;
use Illuminate\Http\Request;

class ManageSilverItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $silveritems = SilverItem::all();
        //dd($silveritems);
        return view('manage.silveritem.index',compact('silveritems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manage.silveritem.CreateOrUpdate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        SilverItem::create($request->all());
        return redirect()->route('manage.silveritem.index')->with('message', 'Silver Item save successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SilverItem  $silverItem
     * @return \Illuminate\Http\Response
     */
    public function show(SilverItem $silverItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SilverItem  $silverItem
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $silveritem =  SilverItem::find($id);
        //dd($silveritem);
        return view('manage.silveritem.CreateOrUpdate',compact('silveritem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SilverItem  $silverItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SilverItem $silverItem,$id)
    {
        $silveritem =  SilverItem::find($id);
        $silveritem->update($request->all());
        return redirect()->route('manage.silveritem.index')->with('message', 'Silver Item update successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SilverItem  $silverItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SilverItem $silverItem,$id)
    {
        $silveritem =  SilverItem::find($id);
        $silveritem->delete();
        return redirect()->route('manage.silveritem.index')->with('message', 'Silver Item delete successfully');
        
    }

     
    public function bulkSilverItemDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $silveritem = SilverItem::findOrFail($request->checkboxArray);
            foreach($silveritem as $value) {
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected silver item have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select silver item to be deleted."));
        }
        return redirect(route('manage.silveritem.index'));
    }
}
