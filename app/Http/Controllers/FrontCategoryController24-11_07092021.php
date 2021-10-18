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
         //\DB::connection()->enableQueryLog();
        $category = Category::with('category','products')->where('slug', $slug)->where('is_active', 1)->orderBy('id','DESC')->firstOrFail();
		$location_id = session('location_id');
         
        $pagination_count =  20;
        $products = $category->be_all_products();
       
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

           
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
            		
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

        } else {
             
			 if($category->slug == 'premium-gifting') {
						
				$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })
			->whereRaw('products_saleprice.saleprice > 30000')->where('products.is_active','1')->where('products.is_approved','1')->count();
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->whereRaw('products_saleprice.saleprice > 30000')->where('products.is_active','1')->where('products.is_approved','1')->count();
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->whereRaw('products_saleprice.saleprice > 30000')->where('products.is_active','1')->where('products.is_approved','1')->count();
			
			}else{
			 
			 $plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->count();
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->count();
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->count();
            
			}
        }
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') { 
                $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
                  
                 
                
            } else {
				  
				  $metal =  Product::with('category')
				  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				  ->where('product_category_styles.category_id', $category->id)
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
            }

            

        } else {

            if($category->slug == 'gift-item') { 
                $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();
            }else if($category->slug == 'premium-gifting') {
				$metal =  Product::with('category')
				  ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get();
				
				
			} else {
				  
				  $metal =  Product::with('category')
				  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				  ->where('product_category_styles.category_id', $category->category_id)->where('product_category_styles.product_style_id',$category->id)
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
            }

            
        }
          
         
        
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
				
				$maleProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%m%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
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

            }else if($category->slug == 'premium-gifting') {
			$maleProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->count();
			} else {
                
				  $maleProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->count();
                 
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
               
				  
				  $femaleProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
                   
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

			}else if($category->slug == 'premium-gifting') {
			$femaleProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->count();
		   } else {
               
				
				$femaleProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->count();
                 
                 
                  
                  
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
               
				  
				  $uniSexProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%u%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
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

				}else if($category->slug == 'premium-gifting') {
			$uniSexProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'u%')
			->where('product_specification_type.specification_type_id', '11')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->count();
			} else {
               
				  
				  $uniSexProduct = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'u%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->count();
            }
            
             
            


            
         }

        

        
        
        if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            } else {
              
				  
				  $offerProduct =  Product::distinct('product_group')
				  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				  ->where('product_category_styles.category_id',$category->id)
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();
            }

            

        } else {
            if($category->slug == 'gift-item') { 

                $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->count();

            }else if($category->slug == 'premium-gifting') {
			
			
			$offerProduct =  Product::distinct('product_group')
				  ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->count(); 
			} else {
                 
				  
				   $offerProduct =  Product::distinct('product_group')
				  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				  ->where('product_category_styles.category_id',$category->category_id)->where('product_category_styles.product_style_id',$category->id)
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->category_id)->count(); 
            }
            
        }

        
        
        
        
        
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
                
				  
				  $purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '18%')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();
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

			}else if($category->slug == 'premium-gifting') {
			$purity_eighteen_carat = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '18%')
			->where('product_specification_type.specification_type_id', '9')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->get();
            } else {
                
				  
				  $purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '18%')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->get();
            }
            

        }
        
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
               
				
				$purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '14%')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();
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
			
			}else if($category->slug == 'premium-gifting') {
			$purity_fourteen_carat = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '14%')
			->where('product_specification_type.specification_type_id', '9')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->get();
            } else {
                
				  
				  $purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '14%')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->get();
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
              
				  
				  $purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '22')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();
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

			}else if($category->slug == 'premium-gifting') {
			$purity_twenty_two_carat = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '22')
			->where('product_specification_type.specification_type_id', '9')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->get();
            } else {
                
				  
				  
                $purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '22')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->get();
                  
                  
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
                
				  
				  $purity_twenty_four_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '24')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();
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

            }else if($category->slug == 'premium-gifting') {
			$purity_twenty_four_carat = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			 ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '24')
			->where('product_specification_type.specification_type_id', '9')
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > 30000')->get();
			} else {
                
				  
				  $purity_twenty_four_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '24')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->get();
            }

           

        }
        /* puirty tweenty four carat */
        

            if ($request->ajax() && !empty($request->all()) ) {
                
                $view = view('front.ajaxcategorypagination',compact('products'))->render();
                return response()->json(['html'=>$view]);
            }

         

          
          //$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
        return view('front.category', compact('products','max_page', 'category', 'product_max_price', 'banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','metal','maleProduct','femaleProduct','uniSexProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','purity_twenty_four_carat','offerProduct','plain','stone','beads'));
    }

    public function ajaxFilterPrice(Request $request) {

        
       
        $category = Category::with('category','products')->where('slug', $request->input('slug'))->where('is_active', 1)->firstOrFail();
         
        $minPrice = explode('/',$request->value);

        if($request->input('price')=='min') {

            
             
            if($category->category_id=='0') {
               
                if($category->slug == 'gift-item') {
					
					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice < '.$minPrice[1].' ')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get();

                } else {

					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice < '.$minPrice[1].' ')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
                    
                    
                     
                }
            } else {
				
				if($category->slug == 'premium-gifting') {
					$products =  Product::select('products.*')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice < '.$minPrice[0].' ')
				->orderBy('products.id','DESC')->get();
				}else{

				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice < '.$minPrice[0].' ')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
                }
                
            }
    
        }else if($request->input('price')=='above') {
             
           

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > '.$minPrice[0].' ')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get();

                    
                } else {
                    
					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > '.$minPrice[0].' ')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
                    
                }

               


                 
                 
             } else {
				 
				 if($category->slug == 'premium-gifting') {
					 $products =  Product::select('products.*')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > '.$minPrice[0].' ')
				->orderBy('products.id','DESC')->get();
				 }else{

                $products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id',$category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > '.$minPrice[0].' ')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
                
                 }
               
             }
    
        } else if($request->input('price')=='all') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					$products =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get();

                } else {
                    
					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
                }

                


                 
                 
             } else {
				 
				 if($category->slug == 'premium-gifting') {}else{

				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id',$category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();

                 }
               
             }

            


            

        } else {
             
            $Price = explode('/',$request->input('value'));
           
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id',array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice BETWEEN '.$Price[0].' AND  '.$Price[1].' ')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get();


                } else {

					$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice BETWEEN '.$Price[0].' AND  '.$Price[1].' ')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
                
                
                }

                 
            
            } else {
				
				if($category->slug == 'premium-gifting') {
					 $products =  Product::select('products.*')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice BETWEEN '.$Price[0].' AND  '.$Price[1].' ')
				->orderBy('products.id','DESC')->get();
				}else{

                 $products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice BETWEEN '.$Price[0].' AND  '.$Price[1].' ')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
				}
            }
        
        }
         
        return view('front.ajaxfiltersearch',compact('products'));    
        
         
    }
    
    public function ajaxSortingPrice(Request $request) {
       
        
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
       
        if($request->input('value')=='1') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {
					
					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id',array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_new',true)->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get(); 

                } else {
                    
					$products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_new',true)->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get(); 
                }

                

            } else {
				
				if($category->slug == 'premium-gifting') {
					$products =  Product::select('products.*')->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_new',true)->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get(); 
				}else{

				 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_new',true)->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get(); 
				}
                

            }
            

        } else if($request->input('value')=='2') {
            
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.best_seller',true)->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get(); 

                } else {
                   
					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.best_seller',true)->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get(); 
                }

                

            } else {
				
				if($category->slug == 'premium-gifting') {
					$products =  Product::select('products.*')->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.best_seller',true)->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get(); 
				}else{

				 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.best_seller',true)->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get(); 

                }

            }


        } else if($request->input('value')=='3') {
            
            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.id','DESC')->get(); 

                } else {
                   
					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get(); 
                }

                

            } else {
					if($category->slug == 'premium-gifting') {
						$products =  Product::select('products.*')->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get(); 
					}else{
				  $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get(); 

					}

            }


        } else if($request->input('value')=='asc') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {
                   
					  $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.old_price','ASC')->get();  

                } else {
                   
					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.old_price','ASC')->get(); 
                }

               
                 
             } else {
				 if($category->slug == 'premium-gifting') {
					 $products =  Product::select('products.*')->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.old_price','ASC')->get(); 
				 }else{

				 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.old_price','ASC')->get(); 
				 }
             }

           
     
            
             
        } else if($request->input('value')=='desc') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.old_price','DESC')->get(); 

                } else {
                    
					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.old_price','DESC')->get(); 
                }

               
                 
             } else {
				 if($category->slug == 'premium-gifting') {
					 $products =  Product::select('products.*')->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.old_price','DESC')->get(); 
				 }else{

				 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.old_price','DESC')->get(); 
				 }
             }

             
             
        } else {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereIn('products.category_id',array(1,12))->orderBy('products.old_price','DESC')->get(); 

                } else {
                    
					 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.old_price','DESC')->get(); 
                }   

               
                 
             } else {
				 if($category->slug == 'premium-gifting') {
					 \DB::connection()->enableQueryLog();
					 $products =  Product::select('products.*')->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get(); 
				$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
				 }else{

				 $products =  Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.old_price','DESC')->get(); 
				 }
             }

           
        }
         
		 
		 $pagination_count =  20;
		 
		 $products = $products->paginate($pagination_count);
        $max_page = $products->total() / $pagination_count;
		
         $view = view('front.ajaxfiltersearch',compact('products'))->render();
	
        return response()->json(['html'=>$view, 'max_page'=>$max_page]);
        
    }
    public function ajaxMetalFilter(Request $request) {
       
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();

        if($request->input('metal')=='all') {

            if($category->category_id=='0') {

				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->orderBy('products.id','DESC')->get();
                 
             } else {
				if($category->slug == 'premium-gifting') {
					$products =  Product::select('products.*')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get();
				}else{
				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->orderBy('products.id','DESC')->get();
				}
             }

            
            

        } else {

            if($category->category_id=='0') {

				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
                 
             } else {
				 if($category->slug == 'premium-gifting') {
					 $products =  Product::select('products.*')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > 30000')->orderBy('products.id','DESC')->get();
				 }else{

				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
				 }
             }

           
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxTypeFilter(Request $request) {
       
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
         
        if($request->input('type')=='all') {

            if($category->category_id=='0') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->orWhere('value', 'like', '%s%')->orWhere('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->orWhere('product_specification_type.value', 'like', '%s%')
			->orWhere('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

                 
             } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->orWhere('value', 'like', '%s%')->orWhere('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->orWhere('product_specification_type.value', 'like', '%s%')
			->orWhere('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->category_id)
			->where('product_category_styles.product_style_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();

                
                 
               
             }

            
            

        }else if($request->input('attr')=='men') {
            if($request->input('type')=='p') {
                
                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%m%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            } else if($request->input('type')=='s') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%m%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            } else if($request->input('type')=='b') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%m%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            }
        } else if($request->input('attr')=='women') {
            if($request->input('type')=='p') {
                
                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            } else if($request->input('type')=='s') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            } else if($request->input('type')=='b') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            }
        } else if($request->input('attr')=='kids') {

            if($request->input('type')=='p') {
                
                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            } else if($request->input('type')=='s') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            } else if($request->input('type')=='b') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('specificationTypes', function($query) use($category) {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', '%k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();

            }


        } else if($request->input('type')=='p' && $request->input('attr')=='') {
             
            if($category->category_id=='0') {
                
                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */

               
                 $products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
             } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%p%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->category_id)
			->where('product_category_styles.product_style_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
               
             }

           
        } else if($request->input('type')=='s'  && $request->input('attr')=='') {
            
            if($category->category_id=='0') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
                 
             } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%s%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products= Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%s%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->category_id)
			->where('product_category_styles.product_style_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
               
             }

           
        } else if($request->input('type')=='b'  && $request->input('attr')=='') {

            if($category->category_id=='0') {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
                 
             } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', '%b%')->where('name','LIKE','%Type%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_category_styles.category_id',$category->category_id)
			->where('product_category_styles.product_style_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
                
             }

           
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    

    public function ajaxGenderFilter(Request $request) {
       
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
       
        if($request->input('value')=='m') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    /* $products[] = Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->whereIn('product_category_styles.category_id', array(1,12))
			->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->orderBy('products.id','DESC')->get();

                } else {
                    /* $products[] = Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
			
                }

               
                
                 

            } else {
               /*  $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();

                
            }

            
        
        } else if($request->input('value')=='f') { 

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->whereIn('product_category_styles.category_id', array(1,12))
			->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->orderBy('products.id','DESC')->get();


                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
                }
                
                

            } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
            }

            

        } else if($request->input('value')=='u') { 

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'u%')
			->where('product_specification_type.specification_type_id', '11')
			->whereIn('product_category_styles.category_id', array(1,12))
			->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->orderBy('products.id','DESC')->get();

                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'u%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
                }
                
                

            } else {

               /*  $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'u%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
            }

            

        } else if($request->input('value')=='all') { 
                 
                if($category->category_id=='0') {
                    

                    $products = Product::distinct('product_group')->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                      })->where('category_id',$category->id)->where('is_active', 1)->where('is_approved','1')->orderBy('id','DESC')->get();
					  
					  

                     
                } else {
                    
                    /* $products[] =   Product::whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
                }

                
                
                 
            }
           
         

          
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxOfferFilter(Request $request) {

        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
        
        if($category->category_id=='0') {

            if($category->slug == 'gift-item') {

                /* $products[] = Product::whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('product_discount','<>',false)->whereIn('category_id', array(1,12))->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->whereIn('product_category_styles.category_id',array(1,12))
			->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->orderBy('products.id','DESC')->get();

            } else {
                /* $products[] = Product::whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('product_discount','<>',false)->where('category_id',$category->id)->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->orderBy('products.id','DESC')->get();
            }

            
        
        } else {

            /* $products[] = Product::whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('product_discount','<>',false)->where('category_id',$category->category_id)->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get(); */
			
			$products = Product::select('products.*')->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_category_styles.category_id',$category->category_id)
			->where('product_category_styles.product_style_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->category_id)->orderBy('products.id','DESC')->get();
        
        }
        
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxPurityFilter(Request $request) {

        $slug = $request->input('slug');
         
        $category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
       
        if($request->value=='14') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', array(1,12))->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();

                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id', $category->id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
                }

                
            
            } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '14')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id', $category->category_id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
                 
            }

        } else if ($request->value=='18') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '18')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', array(1,12))->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
                
                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '18')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id', $category->id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
                }

               
            
            } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '18')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id', $category->category_id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
            }

        } else if ($request->value=='22') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', array(1,12))->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();

                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id', $category->id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
                }

                
            
            } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id', $category->category_id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
            }
        
        } else if ($request->value=='24') {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {

                   /*  $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '24')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '24')
				->where('product_specification_type.specification_type_id', '9')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', array(1,12))->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();

                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '24')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '24')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', $category->id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
				
                }

                
            
            } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '24')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '24')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', $category->category_id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
				
				
            }
        
        } else {

            if($category->category_id=='0') {

                if($category->slug == 'gift-item') {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->orWhere('value', '18')->orWhere('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->orWhere('product_specification_type.value', '18')
				->orWhere('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', array(1,12))->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
				
                } else {
                    /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                        $query->where('value', '14')->orWhere('value', '18')->orWhere('value', '22')->where('name','LIKE','%Purity%');
                    })->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->where('category_id', $category->id);
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
					
					$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->orWhere('product_specification_type.value', '18')
				->orWhere('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', $category->id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
                }

               
            
            } else {

                /* $products[] =   Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '14')->orWhere('value', '18')->orWhere('value', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
				
				$products = Product::select('products.*')->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->orWhere('product_specification_type.value', '18')
				->orWhere('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->category_id)
				->where('product_category_styles.product_style_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->whereIn('products.category_id', $category->category_id)->where('products.is_active','1')->where('products.is_approved','1')->orderBy('products.id','DESC')->get();
            }
            
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    

    


    

    
}