<?php

namespace App\Http\Controllers;
 
use App\Category;
use Illuminate\Http\Request;
use App\ProductStyle;
use App\Product;
use App\SpecificationType;

class FrontCategoryController extends Controller
{
     
     
    public function show(Request $request,$slug) {
         
        // $category = ProductStyle::with('category')->where('slug', $slug)->where('is_active', 1)->firstOrFail();
        $category = Category::with('category','products')->where('slug', $slug)->where('is_active', 1)->orderBy('id','DESC')->firstOrFail();
         
       
        $location_id = session('location_id');
         
        $pagination_count =  20;
        $products = $category->all_products();
       
        $product_max_price = $products->max('price');
        $products = $products->paginate($pagination_count);
        $max_page = $products->total() / $pagination_count;
        
        $banners = $category->banners()->where('is_active', 1)->get();
        $sections = $category->sections()->where('is_active', 1)->get();
         
        $banners_main_slider = $banners->filter(function($banner) {
            return $banner->position_category == 'Main Slider';
        });
        $banners_below_filters = $banners->filter(function($banner) {
            return $banner->position_category == 'Below Filters';
        });
        $banners_below_main_slider = $banners->filter(function($banner) {
            return $banner->position_category == 'Below Main Slider';
        });
        $banners_below_main_slider_2_images_layout = $banners->filter(function($banner) {
            return $banner->position_category == 'Below Main Slider - Two Images per row';
        });
        $banners_below_main_slider_3_images_layout = $banners->filter(function($banner) {
            return $banner->position_category == 'Below Main Slider - Three Images Layout';
        });

        $sections_above_main_slider = $sections->filter(function($section) {
            return $section->position_category == 'Above Main Slider';
        });
        $sections_below_main_slider = $sections->filter(function($section) {
            return $section->position_category == 'Below Main Slider';
        });
        $sections_above_side_banners = $sections->filter(function($section) {
            return $section->position_category == 'Above Side Banners';
        });
        $sections_below_side_banners = $sections->filter(function($section) {
            return $section->position_category == 'Below Side Banners';
        });
        $sections_above_footer = $sections->filter(function($section) {
            return $section->position_category == 'Above Footer';
        });
         
        if($category->category_id=='0') {

            $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count();
            
            $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%s%')->where('name','LIKE','%Type%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count();

            $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%b%')->where('name','LIKE','%Type%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count();

        } else {

            $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->category_id)->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->category_id)->count();

            $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%s%')->where('name','LIKE','%Type%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->category_id)->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->category_id)->count();


            $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%b%')->where('name','LIKE','%Type%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->category_id)->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->category_id)->count();
             
            
        }
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') { 
                $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                  
                 
                
            } else {
                $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
            }

            

        } else {

            if($category->slug == 'gift-item') { 
                $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
            } else {
                $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }

            
        }
          
         
        // $metal = Category::with('products')->whereHas('products.product_category_styles', function ($query) use($category)  {

        //     $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
        //  })->where('id',$category->category_id)->get();
         //dd($metal);
        // $metalCount = $metal->count();
        //dd($category);
        if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count();
            }

            

         } else {
             
            if($category->slug == 'gift-item') { 

                $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();
                 
            }

            



             
         }
         
        if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count();
                   
            }   

            

            
            

        } else {

            
            if($category->slug == 'gift-item') { 

                $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $femaleProduct =  Product::select(\DB::raw('count(*) as totalFemale'))->distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();
                 
                 
                  
                  
            }
        }


         

         if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count();
            }

            

               
            

         } else {

            if($category->slug == 'gift-item') { 

                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();
            }
            
             
            


            
         }

        //dd('male :- '.$maleProduct.'female: - '.$femaleProduct);
        // die;

        
        
        if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count();
            }

            

        } else {
            if($category->slug == 'gift-item') { 

                $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();
            }
            
        }

        
        
        //dd($offerProduct);
        
        
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') { 

                $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'LIKE', '18%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '18%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
            }

           

        } else {

            if($category->slug == 'gift-item') { 

                $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'LIKE', '18%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'LIKE', '18%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }
            

        }
        //dd($purity_eighteen_carat);
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') { 

                $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '14%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '14%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
            }

            

        } else {

            if($category->slug == 'gift-item') { 

                $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '14%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '14%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }
            
        }
        /* puirty tweenty two carat */
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') { 

                $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '22%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value','LIKE', '22%')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
            }

            

        } else {

            if($category->slug == 'gift-item') { 

                $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'LIKE', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_twenty_two_carat = Product::distinct('product_group')->whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'LIKE', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                
                  
                  
            }

           

        }
         
        /* puirty tweenty two carat */

        /* puirty tweenty four carat */
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') { 

                $purity_twenty_four_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '24')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_twenty_four_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '24')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
            }

            

        } else {

            if($category->slug == 'gift-item') { 

                $purity_twenty_four_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '24')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

            } else {
                $purity_twenty_four_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '24')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }

           

        }
        /* puirty tweenty four carat */
        

            if ($request->ajax() && !empty($request->all()) ) {
                
                $view = view('front.ajaxcategorypagination',compact('products'))->render();
                return response()->json(['html'=>$view]);
            }

         

          
         
        return view('front.category', compact('products','max_page', 'category', 'product_max_price', 'banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','metal','maleProduct','femaleProduct','uniSexProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','purity_twenty_four_carat','offerProduct','plain','stone','beads'));
    }

    public function ajaxFilterPrice(Request $request) {

        
       
        $category = Category::with('category','products')->where('slug', $request->input('slug'))->where('is_active', 1)->firstOrFail();
         
        $minPrice = explode('/',$request->value);
        $Price = explode('/',$request->input('value'));
          
        if($request->input('price')=='min') {

            
             
            if($category->category_id=='0') {
               
                if($category->slug == 'gift-item') {

                    $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    
                      
                    $productIDArr = [];
                    if(!empty($ProductID)) {
            
                        foreach($ProductID AS $value) {
                            
                            $productIDArr[]= $value->id;
                        }
                    }
                    
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70))->whereIn('product_id',$productIDArr);
                        })->where(function ($query) use($minPrice) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('id',$productIDArr)->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                    } else {
                        
                        
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70))->whereIn('product_id',$productIDArr);
                        })->where(function ($query) use($minPrice) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` < '.$Price[0].' ) or (`product_discount` is not null  && `new_price` < '.$Price[0].' )')->whereIn('id',$productIDArr)->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    }   

                    

                } else {
                    $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                    
                    
                    $productIDArr = [];
                    if(!empty($ProductID)) {
            
                        foreach($ProductID AS $value) {
                            
                            $productIDArr[]= $value->id;
                        }
                    }

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->where('category_id', $category->id)->whereIn('product_id',$productIDArr);
                        })->where(function ($query)use($minPrice) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                    } else {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->where('category_id', $category->id)->whereIn('product_id',$productIDArr);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` < '.$Price[0].' ) or (`product_discount` is not null  && `new_price` < '.$Price[0].' )')->where('category_id',$category->id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();
                    }
                    
                    
                    
                     
                }
            } else {
               
                $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
               
                
                $productIDArr = [];
                if(!empty($ProductID)) {
        
                    foreach($ProductID AS $value) {
                        
                        $productIDArr[]= $value->id;
                    }
                }

                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id)->whereIn('product_id',$productIDArr);
                    })->where(function ($query)use($minPrice) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                } else {
                      
                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id)->whereIn('product_id',$productIDArr);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` < '.$Price[0].'  or `product_discount` is not null  && `new_price` < '.$Price[0].' )')->where('category_id',$category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();
                }
                 

                
                
                
                
            }
    
        }else if($request->input('price')=='above') {
             
           
            
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    
                    
                    $productIDArr = [];
                    if(!empty($ProductID)) {
            
                        foreach($ProductID AS $value) {
                            
                            $productIDArr[]= $value->id;
                        }
                    }

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70))->whereIn('product_id',$productIDArr);
                        })->where(function ($query) use($minPrice) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('id',$productIDArr)->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                    } else {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70))->whereIn('product_id',$productIDArr);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereIn('id',$productIDArr)->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    }

                   

                    
                } else {
                    
                    $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                    
                    
                    $productIDArr = [];
                    if(!empty($ProductID)) {
            
                        foreach($ProductID AS $value) {
                            
                            $productIDArr[]= $value->id;
                        }
                    }

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->where('category_id', $category->id)->whereIn('product_id',$productIDArr);
                        })->where(function ($query) use($minPrice) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                    } else {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->where('category_id', $category->id)->whereIn('product_id',$productIDArr);
                        })->where(function ($query)  {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();
                    }
                    
                    
                }

               


                 
                 
             } else {
                
                $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) use($minPrice) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                
                
                $productIDArr = [];
                if(!empty($ProductID)) {
        
                    foreach($ProductID AS $value) {
                        
                        $productIDArr[]= $value->id;
                    }
                }

                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id)->whereIn('product_id',$productIDArr);
                    })->where(function ($query)  {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id', $category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                } else {
                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id)->whereIn('product_id',$productIDArr);
                    })->where(function ($query)  {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id', $category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();
                }

                
                
                
                
                 
               
             }
    
        } else if($request->input('price')=='all') {
            
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                    } else {
                        if($request->input('price') != 'all') {
                            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                        } else {
                            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                        }
                        
                    }

                    

                } else {
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->orderBy('id','DESC')->get();

                    } else {
                       
                        if($request->input('price') != 'all') {
                            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                        } else {
                            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                        }
                        
                    }
                    
                }

                


                 
                 
             } else {

                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    
                } else {
                    
                    if($request->input('value') != 'all') {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    } else {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    }
                    
                }

                

                 
               
             }

            


            

        } else {
             
            
             
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    
                    
                    $productIDArr = [];
                    if(!empty($ProductID)) {
            
                        foreach($ProductID AS $value) {
                            
                            $productIDArr[]= $value->id;
                        }
                    }

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                        
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70))->whereIn('product_id',$productIDArr);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('id',$productIDArr)->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                    } else {
                        
                         
    
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70))->whereIn('product_id',$productIDArr);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereIn('id',$productIDArr)->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    }
                   
                    


                } else {

                    $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                    
                    
                    $productIDArr = [];
                    if(!empty($ProductID)) {
            
                        foreach($ProductID AS $value) {
                            
                            $productIDArr[]= $value->id;
                        }
                    }


                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->where('category_id', $category->id)->whereIn('product_id',$productIDArr);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                    } else {
                        
                        $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                            $query->where('category_id', $category->id)->whereIn('product_id',$productIDArr);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();
                    }

                   
                
                
                }

                 
            
            } else {
                $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) use($minPrice) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                
                
                $productIDArr = [];
                if(!empty($ProductID)) {
        
                    foreach($ProductID AS $value) {
                        
                        $productIDArr[]= $value->id;
                    }
                }

                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                    
                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id)->whereIn('product_id',$productIDArr);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                } else {
                    
                    $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category,$productIDArr)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id)->whereIn('product_id',$productIDArr);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

                }

                

                
               
                 
               
            }
        
        }
         
        return view('front.ajaxfiltersearch',compact('products'));    
        
         
    }
    
    public function ajaxSortingPrice(Request $request) {
       
        //dd($request->all());
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
        $Price = explode('/',$request->input('price_filter'));
          
        if($request->input('value')=='1') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_new',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    
                    } else {
                        if($request->input('price_filter') != 'all') {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_new',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                        }
                        
                    }
                   

                } else {
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_new',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                    
                    } else {
                        if($request->input('price_filter') != 'all') {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                        }
                        
                    }
                    
                }

                

            } else {
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                    $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->whereHas('specificationTypes', function($query) use($request) {
                        $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                } else {
                    if($request->input('price_filter') != 'all') {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    } else {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    }
                    
                }
                

                

            }
            

        } else if($request->input('value')=='2') {
            
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('best_seller',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                    } else {

                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('best_seller',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('best_seller',true)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                        }
                        
                    }
                    

                } else {
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('best_seller',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

                    } else {
                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('best_seller',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('best_seller',true)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                        }
                        
                    }
                   
                }

                

            } else {

                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                    $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->whereHas('specificationTypes', function($query) use($request) {
                        $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('best_seller',true)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                } else {
                     if($request->input('price_filter') != 'all') {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('best_seller',true)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                     } else {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('best_seller',true)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                     }
                    
                }

                

                

            }


        } else if($request->input('value')=='3') {
            
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('product_discount','<>',false)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                    
                    } else {
                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('product_discount','<>',false)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                        } else {
                             
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                        }
                        
                    }
                    

                } else {
                    
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('product_discount','<>',false)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                   
                    } else {

                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->orderBy('id','DESC')->get();

                        } else {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                        }
                        
                    }
                    
                }

                

            } else {
                
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {
                    $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                } else {

                    if($request->input('price_filter') != 'all') {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    } else {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    }
                    
                }
                

                

            }


        } else if($request->input('value')=='asc') {
             
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='' && $request->input('price_filter') != 'all') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('category_id', array(1,12))->orderBy('old_price','ASC')->get();

                    } else {
                        if($request->input('price_filter') != 'all') {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereIn('category_id', array(1,12))->orderBy('old_price','ASC')->get();     
                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('old_price','ASC')->get();
                        }
                        
                    }
                    

                } else {
                    
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='' &&  $request->input('price_filter')) {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->orderBy('old_price','ASC')->get();
                   
                    } else {

                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].' ) or (`product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->orderBy('old_price','ASC')->get();

                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('old_price','ASC')->get();
                        }
                        
                       
                        
                    }
                    
                }

               
                 
            } else {

                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='') {

                    $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->whereHas('specificationTypes', function($query) use($request) {
                        $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('old_price','ASC')->get();

                } else {
                     
                    if($request->input('price_filter') != 'all') {
                          
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->orderBy('old_price','ASC')->get();

                    } else {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('old_price','ASC')->get();
                    }
                   
                }

                
               
            }

           
     
            
             
        } else if($request->input('value')=='desc') {
              
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='' && $request->input('price_filter') != 'all') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('category_id', array(1,12))->orderBy('old_price','DESC')->get();

                    } else {
                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereIn('category_id', array(1,12))->orderBy('old_price','DESC')->get();

                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('old_price','DESC')->get();
                        }
                        
                    }

                    

                } else {
                     
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='' && $request->input('price_filter') != 'all') {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->id)->orderBy('old_price','DESC')->get();
                    } else {
                        if($request->input('price_filter') != 'all') {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->id)->orderBy('old_price','DESC')->get();
                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('old_price','DESC')->get();
                        }
                        
                    }   
                    
                }

               
                 
             } else {
                 
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='' && $request->input('price_filter') != 'all') {
                    
                    $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->whereHas('specificationTypes', function($query) use($request) {
                        $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->orderBy('old_price','DESC')->get();
                   // dd($category->category_id);
                } else {
                    if($request->input('price_filter') != 'all') {
                        
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->where('category_id',$category->category_id)->orderBy('old_price','DESC')->get();
                    } else {
                         
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('old_price','DESC')->get();
                    }
                    
                   
                }
                
             }

             
             
        } else {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!='' && $request->input('price_filter') != 'all') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->whereIn('category_id', array(1,12))->orderBy('old_price','DESC')->get();

                    } else {

                        if($request->input('price_filter') != 'all') {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->whereIn('category_id', array(1,12))->orderBy('old_price','DESC')->get();
                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('old_price','DESC')->get();
                        }
                        
                    }

                    

                } else {
                     
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!=''  && $request->input('price_filter') != 'all') {

                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->id);
                        })->whereHas('specificationTypes', function($query) use($request) {
                            $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->orderBy('old_price','DESC')->get();

                    } else {
                        if($request->input('price_filter') != 'all') {

                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->orderBy('old_price','DESC')->get();

                        } else {
                            $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                                $query->where('category_id', $category->id);
                            })->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('old_price','DESC')->get();
                        }
                       
                    }
                    
                }   

               
                 
             } else {
                 
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='' && $request->input('gender_sorting')!=''  && $request->input('price_filter') != 'all') {
                    $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->whereHas('specificationTypes', function($query) use($request) {
                        $query->where('product_specification_type.value', 'like', $request->input('gender_sorting').'%')->where('name','LIKE','%Gender%');
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('old_price','DESC')->get();
                } else {
                    if($request->input('price_filter') != 'all') {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->whereRaw('(`product_discount` is null  && `old_price` > '.$Price[0].'  or `product_discount` is not null  && `new_price` > '.$Price[0].' )')->orderBy('old_price','DESC')->get();
                    } else {
                        $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('old_price','DESC')->get();
                    }
                    
                }
                
               
             }

           
        }
         
        return view('front.ajaxfiltersearch',compact('products'));    
        
    }
    public function ajaxMetalFilter(Request $request) {
       
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();

        if($request->input('metal')=='all') {

            if($category->category_id=='0') {

                $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get();
                 
             } else {

                $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get();
               
             }

            
            

        } else {

            if($category->category_id=='0') {

                $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                 
             } else {

                $products[] =  Product::whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
               
             }

           
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxTypeFilter(Request $request) {
       
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
         
        if($request->input('type')=='all') {

            if($category->category_id=='0') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->orWhere('value', 'like', '%s%')->orWhere('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

                 
             } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->orWhere('value', 'like', '%s%')->orWhere('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                
                 
               
             }

            
            

        }else if($request->input('attr')=='men') {
            if($request->input('type')=='p') {
                
                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            } else if($request->input('type')=='s') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            } else if($request->input('type')=='b') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            }
        } else if($request->input('attr')=='women') {
            if($request->input('type')=='p') {
                
                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            } else if($request->input('type')=='s') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            } else if($request->input('type')=='b') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            }
        } else if($request->input('attr')=='kids') {

            if($request->input('type')=='p') {
                
                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            } else if($request->input('type')=='s') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            } else if($request->input('type')=='b') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

            }


        } else if($request->input('type')=='p' && $request->input('attr')=='') {
             
            if($category->category_id=='0') {
                
                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();

               
                 
             } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
               
             }

           
        } else if($request->input('type')=='s'  && $request->input('attr')=='') {
            
            if($category->category_id=='0') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                 
             } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
               
             }

           
        } else if($request->input('type')=='b'  && $request->input('attr')=='') {

            if($category->category_id=='0') {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                 
             } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                
             }

           
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    

    public function ajaxGenderFilter(Request $request) {
       
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
        $Price = explode('/',$request->input('price'));
         
        if($request->input('value')=='m') {
             
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] = Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                } else {
                    $products[] = Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }

               
                
                 

            } else {
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                } else {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                }

               

                
            }

            
        
        } else if($request->input('value')=='f') { 

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();


                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }
                
                

            } else {
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                }
                
            }

            

        } else if($request->input('value')=='u') { 

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }
                
                

            } else {
                if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                }
                
            }

            

        } else if($request->input('value')=='all') { 
                 
                if($category->category_id=='0') {
                    

                    $products[] = Product::distinct('product_group')->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                      })->where('category_id',$category->id)->where('is_active', 1)->where('is_approved','1')->orderBy('id','DESC')->get();

                     
                } else {
                    if(isset($Price[0]) && $Price[0]!='' && isset($Price[1]) && $Price[1]!='') {

                        $products[] =   Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].' or `product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();

                    } else {
                        $products[] =   Product::whereHas('product_category_styles', function ($query) use($category)  {
                            $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                        })->where(function ($query) {
                            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                    }
                    
                    
                }

                
                
                 
            }
           
         

          
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxOfferFilter(Request $request) {

        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
        
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') {

                $products[] = Product::whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('product_discount','<>',false)->whereIn('category_id', array(1,12))->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get();

            } else {
                $products[] = Product::whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('product_discount','<>',false)->where('category_id',$category->id)->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get();
            }

            
        
        } else {

            $products[] = Product::whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('product_discount','<>',false)->where('category_id',$category->category_id)->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get();
        
        }
        
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxPurityFilter(Request $request) {

        $slug = $request->input('slug');
         
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
       
        if($request->value=='14') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }

                
            
            } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '14')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
                 
            }

        } else if ($request->value=='18') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '18')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                
                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '18')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }

               
            
            } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '18')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }

        } else if ($request->value=='22') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }

                
            
            } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }
        
        } else if ($request->value=='24') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '24')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '24')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }

                
            
            } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '24')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }
        
        } else {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->orWhere('value', '18')->orWhere('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                } else {
                    $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->orWhere('value', '18')->orWhere('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();
                }

               
            
            } else {

                $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '14')->orWhere('value', '18')->orWhere('value', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
            }
            
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    

    


    

    
}