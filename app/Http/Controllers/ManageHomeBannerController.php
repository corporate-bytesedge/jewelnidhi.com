<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HomeBanner;

class ManageHomeBannerController extends Controller
{
    public function index()
    {
        $banner = HomeBanner::first();
        
   
        return view('manage.home-banner.index',compact('banner'));
    }

    public function store(Request $request) {
        $data = array_except($request->all(), ['_token']);
         
        if($request->hasFile('image_one')) {

            $file = $request->file('image_one');
            $extension = $file->getClientOriginalExtension();

            if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
                $filename = $request->image_one->getClientOriginalName();
                if($extension == 'jpg' || $extension == 'jpeg') {
                    $path = $file->storeAs('public/middle-banner/', $filename);
                } else if($extension == 'png') {
                    $path = $file->storeAs('middle-banner/', $filename);
                }
               
                $data['image_one'] = $filename;
            }
            
             
        }
        if($request->hasFile('image_two')) {
            $file = $request->file('image_two');
            $extension = $file->getClientOriginalExtension();
           
            if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
               
                $image_two = $request->image_two->getClientOriginalName();
                if($extension == 'jpg' || $extension == 'jpeg') {
                    $path = $file->storeAs('public/left-banner/', $image_two);
                } else if($extension == 'png') {
                    $path = $file->storeAs('left-banner/', $image_two);
                }
               
                $data['image_two'] = $image_two;
            }
        }
        if($request->hasFile('image_three')) {
            $file = $request->file('image_three');
            $extension = $file->getClientOriginalExtension();
            if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
               
                $image_three = $request->image_three->getClientOriginalName();
                if($extension == 'jpg' || $extension == 'jpeg') {
                    $path = $file->storeAs('public/right-banner/', $image_three);
                } else if($extension == 'png') {
                    $path = $file->storeAs('right-banner/', $image_three);
                }
               
                $data['image_three'] = $image_three;
            }

             
        }
        HomeBanner::create($data);
        return redirect()->route('manage.home_banner.index')->with('message', 'Banner has been save successfully');
    }

    public function update(Request $request)
    {
        
        $homeBanner = HomeBanner::findOrFail($request->id);
        
        $data = array_except($request->all(), ['_token']);
        
        if($request->hasFile('image_one')) {

            $file = $request->file('image_one');
            $extension = $file->getClientOriginalExtension();

            if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
                $filename = $request->image_one->getClientOriginalName();
                if($extension == 'jpg' || $extension == 'jpeg') {
                    $path = $file->storeAs('public/middle-banner/', $filename);
                } else if($extension == 'png') {
                    $path = $file->storeAs('middle-banner/', $filename);
                }
               
                $data['image_one'] = $filename;
            }
            
             
        }
        if($request->hasFile('image_two')) {
            $file = $request->file('image_two');
            $extension = $file->getClientOriginalExtension();
           
            if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
               
                $image_two = $request->image_two->getClientOriginalName();
                if($extension == 'jpg' || $extension == 'jpeg') {
                    $path = $file->storeAs('public/left-banner/', $image_two);
                } else if($extension == 'png') {
                    $path = $file->storeAs('left-banner/', $image_two);
                }
               
                $data['image_two'] = $image_two;
            }
        }

        if($request->hasFile('image_three')) {
            $file = $request->file('image_three');
             
            
            $extension = $file->getClientOriginalExtension();
            if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg') {
               
                $image_three = $request->image_three->getClientOriginalName();
                if($extension == 'jpg' || $extension == 'jpeg') {
                    $path = $file->storeAs('public/right-banner/', $image_three);
                } else if($extension == 'png') {
                    $path = $file->storeAs('right-banner/', $image_three);
                }
               
                $data['image_three'] = $image_three;
            }

             
        }
         
        $homeBanner->update($data);
        return redirect()->route('manage.home_banner.index')->with('message', 'Banner has been updated successfully');
    }
}
