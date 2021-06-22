<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ManageNumberOfProductsController extends Controller
{
    public function index() {
        $location_id = Auth::user()->location_id;
        $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->where('is_active',true)->get();
 
        return view('manage.no_of_products.index',compact('root_categories'));
    }
    
    public function exportCategoryCSV()
    {
        $location_id = Auth::user()->location_id;
        $root_categories = \App\Category::where('location_id', $location_id)->where('category_id', 0)->get();
        $filename = "categoriesproduct.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('S.No.','Category', 'Total Product'));
        $i = 1;
        foreach($root_categories as $parent) {
            $parent_product =  \App\Product::whereHas('product_category_styles', function ($query) use($parent) {
                $query->where('category_id',$parent->id);
                })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$parent->id)->where('is_active','1')->count();

            fputcsv($handle, array($i,$parent->name,  $parent_product));
                $i++;
        }
        

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
        );

        
        return \Response::download($filename, 'categoriesproduct.csv', $headers);
    }

    public function exportStyleCSV() {

            $location_id = Auth::user()->location_id;
            $root_categories = \App\Category::where('location_id', $location_id)->where('category_id', 0)->get();
            $filename = "styleproduct.csv";
            $handle = fopen($filename, 'w+');
             
            $i = 1;
            $categoryName = array();
            foreach($root_categories as $parent) {
                if(!empty($parent->categories)) {
                    
                    foreach($parent->categories as $k=> $child) {
                       
                        $product =  \App\Product::whereHas('product_category_styles', function ($query) use($child) {
                            $query->where('category_id',$child->category_id)->where('product_style_id', $child->id);
                            })->where(function ($query) {
                                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('category_id',$child->category_id)->where('is_active','1')->count();
                        if(!in_array($parent->name,$categoryName)) {
                            array_push($categoryName,$parent->name);

                            $parent_product =  \App\Product::whereHas('product_category_styles', function ($query) use($parent) {
                                $query->where('category_id',$parent->id);
                                })->where(function ($query) {
                                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                })->where('category_id',$parent->id)->where('is_active','1')->count();

                            fputcsv($handle, array($parent->name.'('.$parent_product.' Products)'));
                        } 
                        fputcsv($handle, array('             '.$child->name.'('.$product.')'));
                    }
                }
                
            }
            

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            
            return \Response::download($filename, 'styleproduct.csv', $headers);
        
    }
}
 