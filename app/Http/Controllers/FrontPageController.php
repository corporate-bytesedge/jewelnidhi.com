<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use App\Scheme;
class FrontPageController extends Controller
{
    public function show($slug) {
          
        $page = Page::where('slug', $slug)->first();
       
        return view('front.page', compact('page'));
    }
    public function Schemes() {
        $scheme = Scheme::where('is_active',true)->first();
        
        return view('front.scheme',compact('scheme'));
    }
}
