<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Scheme;

class FrontSchemeController extends Controller
{
    public function index() {
        $catalogs = Scheme::where('is_active',true);
         
        return view('front.catalog',compact('catalogs'));
    }
}
