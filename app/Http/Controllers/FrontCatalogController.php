<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCatalog;

class FrontCatalogController extends Controller
{
    public function index() {
        $catalogs = ProductCatalog::where('is_active',true)->paginate(10);
        return view('front.catalog',compact('catalogs'));
    }
}
