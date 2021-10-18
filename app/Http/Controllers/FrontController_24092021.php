<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Brand;
use App\Category;
use App\Deal;
use App\Location;
use App\Setting;
use App\Vendor;
use App\Testimonial;
use App\Product;
use App\Photo;
use App\Section;
use App\Collection;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\HomeBanner;
use App\Order;
use App\SpecificationType;
use App\Certification;
use App\ShopByMetalStone;

class FrontController extends Controller
{
    public function index($language = null) {
           
        $location_id = 1;
        $topcategories = Category::select('*')->where('is_active', 1)->where('show_in_slider','1')->where('location_id', $location_id)->orderby('top_category_priority')->orderBy('name', 'ASC')->get();
        //$topcategories = Category::select('top_category_priority',\DB::raw("(COUNT(*) - COUNT(DISTINCT top_category_priority)) AS 'duplicate names' "))->where('is_active', 1)->where('show_in_slider','1')->where('location_id', $location_id)->where('top_category_priority','!=',null)->orderby('top_category_priority', 'asc')->groupBy('top_category_priority')->get();
        //$topcategories = Category::where('is_active', 1)->where('show_in_slider','1')->where('location_id', $location_id)->where('top_category_priority','!=','null')->orderby('top_category_priority', 'asc')->get();
     //dd($topcategories);
        
        $products = Product::orderBy('id', 'desc')->where('is_active', 1)->where('location_id', $location_id)->get();
		
        //$featured_products = $products->sortByDesc(function($product) {return $product->reviews->where('approved', 1)->where('rating', '!=', null)->avg('rating');})->take(10);
		\DB::connection()->enableQueryLog();
        $best_selling_products = $products->where('best_seller',1)->sortByDesc(function($product) {return $product->sales;})->take(10);
		$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
         
        $products = $products->take(20);
        $brands = Brand::where('is_active', 1)->where('location_id', $location_id)->where('show_in_slider', true)->orderby('priority', 'asc')->get();
        $deals = Deal::orderBy('priority', 'asc')->where('is_active', 1)->where('location_id', $location_id)->get();
         
        $banners = Banner::where('is_active', 1)->where('location_id', $location_id)->orderBy('priority', 'asc')->get();
        $sections = Section::orderBy('priority', 'asc')->where('is_active', 1)->where('location_id', $location_id)->get();
        $certifications = Certification::where('is_active',true)->first();
        
          
        $banners_main_slider = $banners->filter(function($banner) {
            return $banner->position == 'Main Slider';
        });
        $banners_right_side = $banners->filter(function($banner) {
            return $banner->position == 'Right Side';
        });
        $banners_below_main_slider = $banners->filter(function($banner) {
             
            return $banner->position == 'Below Main Slider';
        });
         
        $banners_below_main_slider_2_images_layout = $banners->filter(function($banner) {
            return $banner->position == 'Below Main Slider - Two Images per row';
        });
        $banners_below_main_slider_3_images_layout = $banners->filter(function($banner) {
            return $banner->position == 'Below Main Slider - Three Images Layout';
        });

        $sections_above_main_slider = $sections->filter(function($section) {
            return $section->position == 'Above Main Slider';
        });
        $sections_below_main_slider = $sections->filter(function($section) {
            return $section->position == 'Below Main Slider';
        });
        $sections_above_deal_slider = $sections->filter(function($section) {
            return $section->position == 'Above Deal Slider';
        });
        $sections_below_deal_slider = $sections->filter(function($section) {
            return $section->position == 'Below Deal Slider';
        });
        $sections_above_footer = $sections->filter(function($section) {
            return $section->position == 'Above Footer';
        });
        $sections_above_side_banners = $sections->filter(function($section) {
            return $section->position == 'Above Side Banners';
        });
        $sections_below_side_banners = $sections->filter(function($section) {
            return $section->position == 'Below Side Banners';
        });
        

        $middleBanner = HomeBanner::first(); 
       // dd($middleBanner);
        // $leftBanner = $banners->filter(function($banner) {
        //     return $banner->position == 'Left Banner';
        // });

        // $rightBanner = $banners->filter(function($banner) {
        //     return $banner->position == 'Right Banner';
        // });

        
        
        $testimonials = Testimonial::where('is_active', 1)->orderBy('priority', 'asc')->get();
        $default_photo = Photo::getDefaultUserPhoto();

        $collections = Collection::get()->first();
         
        return view('front.index', compact('topcategories','products', 'best_selling_products', 'brands',  'deals', 'banners_main_slider', 'banners_right_side', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_deal_slider', 'sections_below_deal_slider', 'sections_above_footer', 'sections_above_side_banners', 'sections_below_side_banners', 'testimonials', 'default_photo','middleBanner','collections','certifications'));
    }

    public function products(Request $request, $type = null, $slug = null) {
        $location_id = 1;

        if($type == 'products' || $type==null) {
            $products = Product::orderBy('id', 'desc')->where('is_active', 1)->where('location_id', $location_id);
            $product_max_price = $products->max('price');
        } elseif($type == 'category') {
            $category = Category::where('slug', $slug)->firstOrFail();
            $products = $category->all_products()->where('location_id', $location_id)->where('is_active', 1);
            $product_max_price = $products->max('price');
        } elseif($type == 'brand') {
            $brand = Brand::where('slug', $slug)->firstOrFail();
            $products = $brand->products()->where('location_id', $location_id)->where('is_active', 1)->orderBy('id', 'desc');
            $product_max_price = $products->max('price');
        } elseif($type == 'deal') {
            $deal = Deal::where('slug', $slug)->firstOrFail();
            $products = $deal->products()->where('location_id', $location_id)->where('is_active', 1)->orderBy('id', 'desc');
            $product_max_price = $products->max('price');
        } elseif($type == 'shop') {
            $shop = Vendor::where('slug', $slug)->firstOrFail();
            $products = $shop->products()->where('location_id', $location_id)->where('is_active', 1)->orderBy('id', 'desc');
            $product_max_price = $products->max('price');
        } elseif($type == 'search') {
            if(isset($request->keyword)) {
                $keyword = trim($request->keyword);
            } else {
                $keyword = '';
            }
            $products = Product::where('location_id', $location_id)->where('name', 'LIKE', '%'.$keyword.'%')->where('is_active', 1)->orderBy('id', 'desc');
            $product_max_price = $products->max('price');
        }

        // Filtering
        if(isset($request->discount) && $request->discount == true) {
            $products = $products->get()->filter(function($product) {
                return $product->price > $product->price_with_discount();
            });
        } else {
            if($type != 'category') {
                $products = $products->get();
            }
        }

        if(isset($request->filter_by) && isset($request->filter_value)) {
            $products = $products->filter(function($product) use ($request) {
                foreach($product->specificationTypes as $specificationType) {
                    if(in_array($specificationType->id, $request->filter_by)) {
                        if(in_array($specificationType->pivot->value, $request->filter_value) && in_array($specificationType->pivot->unit, $request->filter_unit)) {
                            return true;
                        }
                    }
                }
            });
        }

        if(isset($request->filter_by_brand)) {
            $products = $products->filter(function($product) use ($request) {
                if(in_array($product->brand_id, $request->filter_by_brand)) {
                    return true;
                }
            });
        }

        if(isset($request->filter_by_category)) {
            $products = $products->filter(function($product) use ($request) {
                if(in_array($product->category_id, $request->filter_by_category)) {
                    return true;
                }
            });
        }

        // Price Range Filtering
        if(isset($request->min_price) && isset($request->max_price)) {
            $products = $products->filter(function($product) use ($request) {
                return $product->price_with_discount() >= $request->min_price && $product->price_with_discount() <= $request->max_price;
            });
        }

        // Sorting
        if(isset($request->sort_by) && $request->sort_by != '') {
            $sort_by = $request->sort_by;

            if($sort_by == 1) { // By Price Low
                $products = $products->sortBy(function($product) {
                    return $product->price_with_discount();
                });
            } elseif($sort_by == 2) { // By Price High
                $products = $products->sortByDesc(function($product) {
                    return $product->price_with_discount();
                });
            } elseif($sort_by == 3) { // By Popularity
                $products = $products->sortByDesc(function($product) {
                    return $product->sales;
                });
            } elseif($sort_by == 4) { // By Ratings
                $products = $products->sortByDesc(function($product) {
                    return $product->reviews->where('approved', 1)->where('rating', '!=', null)->avg('rating');
                });
            } elseif($sort_by == 5) { // By Reviews
                $products = $products->sortByDesc(function($product) {
                    return count($product->reviews->where('approved', 1)->where('comment', '!=', null));
                });
            }
        }

        // Pagination
        $pagination_count = config('settings.pagination_count') ? config('settings.pagination_count') : 9;
        $products = $products->paginate($pagination_count);

        if(\Illuminate\Support\Facades\Request::ajax()) {
            return response()->view('includes.products_pagination', compact('products'), 200);
        }

        return view('front.products', compact('products','product_max_price'));
    }

    public function search(Request $request) {

        \DB::connection()->enableQueryLog();
		$keywordArr = explode(' ',$request->keyword);
        $keyword = $request->keyword;
        $location_id = session('location_id');
        $pagination_count = 20;
        $stringArr = explode(' ',$request->keyword); 
        $subQueryarr = array();
        $productSubQueryArr = array();
        $orderbyColumn = '';
        if(count($stringArr) > 0 ) {
            foreach($stringArr AS $value) {
                
                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
                array_push($productSubQueryArr," products.name LIKE '%".$value."%'" );
                
                 
            }
        }
        
        $category = Category::with('category')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids,min_price,max_price"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
         
        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
        
        
        if(is_null($catSql->rootcatids)) {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
            $subproductid = \DB::table('products')->select(\DB::raw("GROUP_CONCAT(id) AS product_id"))->whereRaw('is_approved = 1 AND is_active = 1 AND ('. implode(' and ',$productSubQueryArr).')')->first();  
             
        } else {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
            $subproductid = \DB::table('products')->select(\DB::raw("GROUP_CONCAT(id) AS product_id"))->whereRaw('category_id IN ('.$catSql->rootcatids.')  AND ('. implode(' and ',$productSubQueryArr).')')->first();
        }
         
        if(is_null($catSql->rootcatids) && is_null($subCatSql->subcatids) && is_null($subproductid->product_id)) {
           
            $products = array();
            $allProduct = array();
            return view('front.frontsearch', compact('products','allProduct'));
        } else {
            $products = Product::leftJoin('product_category_styles', function ($join) {
                $join->on('products.category_id', '=', 'product_category_styles.category_id')
                ->orOn('product_category_styles.product_id', '=', 'products.id');
            }) 
            ->leftJoin('categories', 'categories.id', '=', 'product_category_styles.product_style_id')
            ->whereRaw("products.id = product_category_styles.product_id")
            ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id  "));
            
            if(!is_null($subCatSql->subcatids)) {
                $products->whereRaw('product_category_styles.product_style_id IN('.$subCatSql->subcatids.') ');
                 
            }
            if(!is_null($catSql->rootcatids) ) {
                $products->whereRaw('product_category_styles.category_id  IN('.$catSql->rootcatids.')');
                
                 
            }
            if(!is_null($subproductid->product_id) && (is_null($catSql->rootcatids) && is_null($subCatSql->subcatids) ) ) {
                $products->whereRaw('product_category_styles.product_id  IN('.$subproductid->product_id.')');
            }
            
            
            if(!is_null($subproductid->product_id) && !is_null($catSql->rootcatids) || !is_null($subCatSql->subcatids)) {

                if(count($stringArr) > 0 ) {
                    $categorySearch= array();
                    $productSearch = array();
                    $i = 1;
                    foreach($stringArr AS $value) {
                        
                        array_push($categorySearch, $value);
                        array_push($productSearch," when products.name LIKE '%".$value."%' then ".$i." " );
                    $i++; 
                         
                    }
                    
                }
                $orderbyColumn = "case  ".implode(' ',$productSearch)."   else 3 end ";
             
                //$orderbyColumn = "case when products.name LIKE '%".$stringArr[0]."%' then 1  else 3 end ";
            }
            $products->where('products.is_active',true);
            $products->where('products.is_approved',true);
            $products->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            //dd($orderbyColumn);
            $products->groupBy('products.id');
            // foreach ($stringArr as $key1 => $value1) {
            //     $products->orderBy($value1, $value1);
            // }
             
           //$products->orderBy('products.id','DESC');
            //orderByRaw('')
            if($orderbyColumn) {
                $products->orderByRaw($orderbyColumn);
            } else {
                $products->orderBy('products.id','DESC');
            }
            
        
            //dd($products->toSql());
             
            
            $allProduct = $products->get();
        $product_max_price = $products->max('price');
        $products->skip(0)->take($pagination_count);
           
        $max_page = count($allProduct) / $pagination_count;
            
        /* $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            $query->whereIn('product_style_id', array($subCatSql->subcatids));
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_active','1')->where('is_approved',true)->where('category_id',$catSql->rootcatids)->get(); */
		$metal =  Product::with('category')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->whereIn('product_category_styles.product_style_id', array($subCatSql->subcatids))
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids)->get();
		

        /* $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
            $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_active','1')->where('is_approved',true)->where('category_id',$catSql->rootcatids)->count(); */
		
		$maleProductQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', 'm%')
		->where('product_specification_type.specification_type_id', '11');
		if($subCatSql->subcatids) {
			$maleProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$maleProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$maleProduct = $maleProductQry->count();
		
        /* $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
            $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null)');
        })->where('is_active','1')->where('is_approved',true)->where('category_id',$catSql->rootcatids)->count(); */
		
		$femaleProductQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', 'f%')
		->where('product_specification_type.specification_type_id', '11');
		if($subCatSql->subcatids) {
			$femaleProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$femaleProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$femaleProduct = $femaleProductQry->count();

        /* $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
            $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_active','1')->where('is_approved',true)->where('category_id',$catSql->rootcatids)->count(); */
		
		$uniSexProductQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', 'u%')
		->where('product_specification_type.specification_type_id', '11');
		if($subCatSql->subcatids) {
			$uniSexProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$uniSexProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$uniSexProduct = $uniSexProductQry->count();

        /* $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved',true)->where('category_id',$catSql->rootcatids)->count(); */
		
		$offerProductQry =  Product::distinct('product_group')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id');
		if($subCatSql->subcatids) {
			$offerProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$offerProductQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$offerProduct = $offerProductQry->count();

        /* $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
            $query->where('value','LIKE', '18%')->where('name','LIKE','%Purity%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_approved','1')->where('is_active','1')->where('category_id',$catSql->rootcatids)->get(); */
		
		$purity_eighteen_caratQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', '18%')
		->where('product_specification_type.specification_type_id', '9');
		if($subCatSql->subcatids) {
			$purity_eighteen_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$purity_eighteen_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$purity_eighteen_carat = $purity_eighteen_caratQry->get();

        
        /* $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
            $query->where('value','LIKE', '14%')->where('name','LIKE','%Purity%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_approved','1')->where('is_active','1')->where('category_id',$catSql->rootcatids)->get(); */
		
		$purity_fourteen_caratQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', '14%')
		->where('product_specification_type.specification_type_id', '9');
		if($subCatSql->subcatids) {
			$purity_fourteen_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$purity_fourteen_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$purity_fourteen_carat = $purity_fourteen_caratQry->get();

        /* $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query) use($catSql)  {
            $query->where('value','LIKE', '22%')->where('name','LIKE','%Purity%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_approved','1')->where('is_active','1')->where('category_id',$catSql->rootcatids)->get(); */
		
		$purity_twenty_two_caratQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', '22%')
		->where('product_specification_type.specification_type_id', '9');
		if($subCatSql->subcatids) {
			$purity_twenty_two_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$purity_twenty_two_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$purity_twenty_two_carat = $purity_twenty_two_caratQry->get();
        

        /* $purity_twenty_four_carat = Product::whereHas('specificationTypes', function ($query) use($catSql)  {
            $query->where('value','LIKE', '24%')->where('name','LIKE','%Purity%');
        })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('is_approved','1')->where('is_active','1')->where('category_id',$catSql->rootcatids)->get(); */

        $purity_twenty_four_caratQry =  Product::distinct('product_group')
		->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		->where('product_specification_type.value', 'like', '24%')
		->where('product_specification_type.specification_type_id', '9');
		if($subCatSql->subcatids) {
			$purity_twenty_four_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."' AND product_category_styles.product_style_id = '".$subCatSql->subcatids."'");
		}
		$purity_twenty_four_caratQry->whereRaw("product_category_styles.category_id = '".$catSql->rootcatids."'")
		->where(function ($query) {
            $query->whereRaw('(products.product_group_default = 1 AND products.product_group = 1 OR  products.product_group is null) ');
        })->where('products.is_active','1')->where('products.is_approved',true)->where('products.category_id',$catSql->rootcatids);
		$purity_twenty_four_carat = $purity_twenty_four_caratQry->get();

        
            if ($request->ajax() && !empty($request->all()) ) {
                if($request->query('keyword')) {
                    $stringArr = explode(' ',$request->query('keyword')); 
                }
                
                
                $ajaxsubQueryarr = array();
                $productSubQueryArr = array();
                $orderbyColumn = '';
                if(count($stringArr) > 0 ) {
                    foreach($stringArr AS $value) {
                        
                        array_push($ajaxsubQueryarr," categories.name LIKE '%".$value."%'" );
                        array_push($productSubQueryArr," products.name LIKE '%".$value."%'" );
                    }
                }
                
                $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$ajaxsubQueryarr).')')->first();
            
                if(is_null($catSql->rootcatids)) {
                    
                    $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$ajaxsubQueryarr).')' )->first();
                    $subproductid = \DB::table('products')->select(\DB::raw("GROUP_CONCAT(id) AS product_id"))->whereRaw('is_approved = 1 AND is_active = 1 AND ('. implode(' and ',$productSubQueryArr).')')->first();  
                } else {
                
                $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$ajaxsubQueryarr).')' )->first();
                $subproductid = \DB::table('products')->select(\DB::raw("GROUP_CONCAT(id) AS product_id"))->whereRaw('category_id IN ('.$catSql->rootcatids.')  AND ('. implode(' and ',$productSubQueryArr).')')->first();
                }
            
                
                $products = Product::leftJoin('product_category_styles', function ($join) {
                    $join->on('products.category_id', '=', 'product_category_styles.category_id')
                    ->orOn('product_category_styles.product_id', '=', 'products.id');
                }) 
                ->leftJoin('categories', 'categories.id', '=', 'product_category_styles.product_style_id')
                ->whereRaw("products.id = product_category_styles.product_id")
                ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id  "));
                
                if(!is_null($subCatSql->subcatids)) {
                    $products->whereRaw('product_category_styles.product_style_id IN('.$subCatSql->subcatids.') ');
                }
                if(!is_null($catSql->rootcatids)) {
                    $products->whereRaw('product_category_styles.category_id  IN('.$catSql->rootcatids.')');
                }

                if(!is_null($subproductid->product_id) && (is_null($catSql->rootcatids) && is_null($subCatSql->subcatids) ) ) {
                    $products->whereRaw('product_category_styles.product_id  IN('.$subproductid->product_id.')');
                }
                
                if(!is_null($subproductid->product_id) && !is_null($catSql->rootcatids) || !is_null($subCatSql->subcatids)) {
    
                    if(count($stringArr) > 0 ) {
                        $categorySearch= array();
                        $productSearch = array();
                        $i = 1;
                        foreach($stringArr AS $value) {
                            
                            array_push($categorySearch, $value);
                            array_push($productSearch," when products.name LIKE '%".$value."%' then ".$i." " );
                        $i++; 
                             
                        }
                        
                    }
                    $orderbyColumn = "case  ".implode(' ',$productSearch)."   else 3 end ";
                 
                    //$orderbyColumn = "case when products.name LIKE '%".$stringArr[0]."%' then 1  else 3 end ";
                }

                $products->where('products.is_active',true);
                $products->where('products.is_approved',true);
                $products->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
                
                $products->groupBy('products.id');
                //$products->orderBy('products.id','DESC');
                if($orderbyColumn) {
                    $products->orderByRaw($orderbyColumn);
                } else {
                    $products->orderBy('products.id','DESC');
                }
                $allProduct = $products->get();
                $product_max_price = $products->max('price');
                $offset = ($request->page - 1) * $pagination_count;
                $products->skip($offset)->take($pagination_count);
                $products = $products->get();     
                //dd($products->toSql());
				$debugQry = \DB::getQueryLog();   
				//print_r($debugQry);
                $view = view('front.ajaxcategorypagination',compact('products'))->render();
                return response()->json(['html'=>$view]);
            }
            $products = $products->get();
			
			$debugQry = \DB::getQueryLog();   
			//print_r($debugQry);

            return view('front.frontsearch', compact('products','allProduct','max_page', 'product_max_price','keyword','category','metal','maleProduct','femaleProduct','uniSexProduct','offerProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','purity_twenty_four_carat'));
        }

        
         
        
        
    }
    
    /* all filters */
    public function ajaxPurityFilter(Request $request) {
        
        //$slug = $request->input('slug');

        $stringArr = explode(' ',$request->input('slug')); 
        
        $subQueryarr = array();
        if(count($stringArr) > 0 ) {
            foreach($stringArr AS $value) {
                
                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
            }
        }

        $category = Category::with('category')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids,min_price,max_price"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
           
        if(is_null($catSql->rootcatids)) {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
        } else {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
        }

         
        //$category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
      
        if($request->value=='14') {

            $products[] =   Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value','LIKE', '14%')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
                
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();

        } else if ($request->value=='18') {
            
            $products[] =   Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value','LIKE', '18%')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
           
            
        } else if ($request->value=='22') {
            
            $products[] =   Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value','LIKE', '22%')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
        
        } else if ($request->value=='24') {

            $products[] =   Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value','LIKE', '24%')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();

        
        } else {
            $products[] =   Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->whereIn('value', array(14, 18, 22,24))->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
        }
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxGenderFilter(Request $request) {

        $stringArr = explode(' ',$request->input('slug')); 
        $subQueryarr = array();
        if(count($stringArr) > 0 ) {
            foreach($stringArr AS $value) {
                
                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
            }
        }

        $category = Category::with('category')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids,min_price,max_price"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
           
        if(is_null($catSql->rootcatids)) {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
        } else {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
        }

       
        //$category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
       
        if($request->input('value')=='m') {

            $products[] = Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value', 'like', 'm%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");

            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
            
        
        } else if($request->input('value')=='f') { 

            $products[] = Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value', 'like', 'f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();

            
            

        } else if($request->input('value')=='u') { 

            $products[] = Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->where('value', 'like', 'u%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();


           

            

        } else if($request->input('value')=='all') { 
                 
            $products[] = Product::whereHas('specificationTypes', function ($query) use($catSql,$subCatSql)  {
                $query->whereRaw("value like 'm%' or value like 'f%' or value like 'u%'")->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
            
        }
           
         

          
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxOfferFilter(Request $request) {

        $stringArr = explode(' ',$request->input('slug')); 
        $subQueryarr = array();
        if(count($stringArr) > 0 ) {
            foreach($stringArr AS $value) {
                
                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
            }
        }

        $category = Category::with('category')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids,min_price,max_price"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
           
        if(is_null($catSql->rootcatids)) {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
        } else {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
        }

        $products[] = Product::whereHas('product_category_styles', function($query) use($catSql,$subCatSql) {
            if($subCatSql->subcatids) {
                $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
            }
            $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            
        })->where(function ($query) {
            $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('product_discount','<>',false)->where('category_id',$catSql->rootcatids)->where('is_active','1')->where('is_approved','1')->orderBy('id','DESC')->get();
        
        return view('front.ajaxfiltersearch',compact('products'));
    }

    public function ajaxSortingPrice(Request $request) {
       
        $stringArr = explode(' ',$request->input('slug')); 
        $subQueryarr = array();
        if(count($stringArr) > 0 ) {
            foreach($stringArr AS $value) {
                
                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
            }
        }

        $category = Category::with('category')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids,min_price,max_price"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
           
        if(is_null($catSql->rootcatids)) {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
        } else {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
        }

        
        //$category = Category::with('category','products')->where('slug', 'LIKE','%'.$request->input('slug').'%')->where('is_active', 1)->firstOrFail();
       // work here
        if($request->input('value')=='1') {

            $products[] =  Product::whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
            
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");

            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_new',true)->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();

            

        } else if($request->input('value')=='2') {
            
            $products[] =  Product::whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('best_seller',true)->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();


        } else if($request->input('value')=='3') {
            
            $products[] =  Product::whereHas('product_category_styles', function ($query)  use($catSql,$subCatSql)  {
                
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
        })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();



        } else if($request->input('value')=='asc') {

            $products[] =  Product::whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('old_price','ASC')->get();
           

        } else if($request->input('value')=='desc') {

            $products[] =  Product::whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
                
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('old_price','DESC')->get();
           
        } else {

            $products[] =  Product::whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
                
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('old_price','DESC')->get();
           
        }
         
        return view('front.ajaxfiltersearch',compact('products'));    
        
    }

    public function ajaxFilterPrice(Request $request) {
       
        $stringArr = explode(' ',$request->input('slug')); 
        $subQueryarr = array();
        if(count($stringArr) > 0 ) {
            foreach($stringArr AS $value) {
                
                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
            }
        }

        $category = Category::with('category')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids,min_price,max_price"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
           
        if(is_null($catSql->rootcatids)) {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
        } else {
            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
        }

       
         
        $minPrice = explode('/',$request->value);

        if($request->input('price')=='min') {

            $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get();
           
             
            $productIDArr = [];
            if(!empty($ProductID)) {
    
                foreach($ProductID AS $value) {
                    
                    $productIDArr[]= $value->id;
                }
            }
            
             
             

            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql,$productIDArr)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query)use($minPrice) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` < '.$minPrice[0].' ) or (`product_discount` is not null  && `new_price` < '.$minPrice[0].' )')->where('category_id',$category->category_id)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();
            
    
        } else if($request->input('price')=='above') {
             
           
            $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) use($minPrice) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
            
            
            $productIDArr = [];
            if(!empty($ProductID)) {
    
                foreach($ProductID AS $value) {
                    
                    $productIDArr[]= $value->id;
                }
            }
        
            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql,$productIDArr)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) use($minPrice) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$minPrice[0].' ) or (`product_discount` is not null  && `new_price` > '.$minPrice[0].' )')->where('category_id', $catSql->rootcatids)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

           
    
        } else if($request->input('price')=='all') {

            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
        
        } else {
             
            $Price = explode('/',$request->input('value'));
           
            $ProductID =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) use($minPrice) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$catSql->rootcatids)->orderBy('id','DESC')->get();
            
            
            $productIDArr = [];
            if(!empty($ProductID)) {
    
                foreach($ProductID AS $value) {
                    
                    $productIDArr[]= $value->id;
                }
            }
            

            $products[] =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($catSql,$subCatSql,$productIDArr)  {
                if($subCatSql->subcatids) {
                    $query->whereRaw("category_id = '".$catSql->rootcatids."' AND product_style_id = '".$subCatSql->subcatids."'");
                }
                $query->whereRaw("category_id = '".$catSql->rootcatids."'");
            })->where(function ($query) {
                $query->whereRaw('(product_group_default = 1 AND product_group = 1 OR  product_group is null) ');
            })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$Price[0].' AND  '.$Price[1].') or (`product_discount` is not null  && `new_price` BETWEEN '.$Price[0].' AND  '.$Price[1].')')->where('category_id',$catSql->rootcatids)->whereIn('id',$productIDArr)->orderBy('id','DESC')->get();

            
        
        }
         
        return view('front.ajaxfiltersearch',compact('products'));    
        
         
    }
    /* all filters */

    public function autocomplete(){
        $keyword = request()->get('term');
        $results = array();
        $location_id = session('location_id');
        $queries = Product::where('location_id', $location_id)->where('name', 'LIKE', '%'.$keyword.'%')->where('is_active', 1)->take(5)->get();

        foreach ($queries as $query)
        {

            $product_id =  $query->id;
            $cartItems = Cart::content();

            $productsInCart = $cartItems->filter(function($cartItem) use($product_id) {
                return $cartItem->id == $product_id;
            });
            $cart_data      = $productsInCart->first();
            $variant = array();
            if(!empty($cart_data)){
 
                $allow_cart_item_qty  = $cart_data->options->qty_per_order;
                $cart_item_qty  = $cart_data->qty;
                $row_id  = $cart_data->rowId;
                if(count($cart_data->options->spec) > 0 ){
                    for ($i = 0 ; $i <  count($cart_data->options->spec) ; $i++){
                        $variant['count']    = count($cart_data->options->spec);
                        $variant['name'][]   = $cart_data->options->spec[$i]['name'];
                        $variant['value'][]  = $cart_data->options->spec[$i]['value'];
                        $variant['amt'][]    = $cart_data->options->unit_price;
                    }
                }

            }else{
                $row_id = null;
                $cart_item_qty = 0;
                $allow_cart_item_qty = $query->qty_per_order;
                $variants = unserialize($query->variants);
                if(!is_array($variants)) {
                    $variants = [];
                }
                if(count($variants) > 0 ){
                    for ($i = 0 ; $i <  count($variants) ; $i++){
                        $variant['count']    = count($variants);
                        $variant['name'][]   = $variants[$i]['n'];
                        $variant['value'][]  = $variants[$i]['v'][0]['n'];
                        $variant['amt'][]    = $variants[$i]['v'][0]['p'];
                    }
                }
            }
            if($query->photo){
                $image_url = Helper::check_image_avatar($query->photo->name, 500);
            } else{
                $image_url = 'https://via.placeholder.com/80x80?text=No+Image';
            }

            $results[] = [
                'id'        => $query->id,
                'row_id'    => $row_id,
                'value'     => $query->name,
                'price'     => $query->price,
                'max_qty'   => $query->in_stock,
                'file_id'   => $query->file_id,
                'virtual'   => $query->virtual,
                'downloadable'          => $query->downloadable,
                'allow_cart_item_qty'   => $allow_cart_item_qty,
                'cart_item_qty'  => $cart_item_qty,
                'product_variants'  => $variant,
                'link'      => route('front.product.show', [$query->slug]),
                'imgsrc'    => $image_url ? $image_url : 'https://via.placeholder.com/80x80?text=No+Image'
            ];
        }
        return response()->json($results);
    }

    public function account() {
        $user_id = \Auth::user()->id;
        $orders = Order::with('products')->where('user_id', $user_id)->where('hide', false)->orderBy('id', 'desc')->paginate(15);
         
        return view('front.account',compact('orders'));
    }

    public function filterMetal(Request $request) {

        //echo "hiiiiii";
		//\DB::connection()->enableQueryLog();
        $category = Category::with('category')->where('slug', $request->segment(2))->where('is_active', 1)->firstOrFail();
         
        $metal =  Product::with('category')->where(function ($query) {
            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->get();

        $shopbyMetal = ShopByMetalStone::where('id',$request->segment(3))->first();
     
        

         /*$products = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category,$shopbyMetal)  {
            $query->where('product_specification_type.value', 'like', strtolower($shopbyMetal->name))->where('name','LIKE','%metal%');
        })->whereHas('product_category_styles', function($query) use($category) {
            $query->where('category_id',$category->id);
        })->where(function ($query) {
            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
          })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->get();  */
		  
		  $products = Product::select('products.*')
		  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
		  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
		  ->where('product_specification_type.value', 'like', strtolower($shopbyMetal->name))
		  ->where('product_specification_type.specification_type_id', '42')
		  ->where('product_category_styles.category_id', $category->id)
		  ->where(function ($query) {
            $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
          })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->get();


        $pagination_count = config('settings.pagination_count') ? config('settings.pagination_count') : 8;
        //echo $pagination_count;
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

            /* $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count(); */
			
			
			
			$maleProduct =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%m%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();

         } else {

            $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();



             
         }
         
         if($category->category_id=='0') { 

            /* $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count(); */

            $femaleProduct =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '%f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();
            

         } else {

            $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();

            


            
         }

        //dd('male :- '.$maleProduct.'female: - '.$femaleProduct);
        // die;

        
        
        if($category->category_id=='0') { 

            /* $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->count(); */
			  
			  
			  $offerProduct =  Product::distinct('product_group')
			  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			  ->where('product_category_styles.category_id',$category->id)
			  ->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
              })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();

        } else {

            $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->count();
        }

        
        
        //dd($offerProduct);
        
        
        if($category->category_id=='0') {

            /* $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->where('is_active','1')->where('is_approved','1')->get(); */
			  
			  
			  $purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '18')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();

        } else {

            $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->where('is_active','1')->where('is_approved','1')->get();

        }
        //dd($purity_eighteen_carat);
        if($category->category_id=='0') {

            /* $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->where('is_active','1')->where('is_approved','1')->get(); */
			  
			  
			  $purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '14')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();

        } else {
            $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->where('is_active','1')->where('is_approved','1')->get();
        }
        
        if($category->category_id=='0') {

            /* $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->where('is_active','1')->where('is_approved','1')->get(); */
			  
			  $purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', '22')
			->where('product_specification_type.specification_type_id', '9')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->get();

        } else {

            $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->where('is_active','1')->where('is_approved','1')->get();

        }

        if($category->category_id=='0') {

            /* $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->id)->where('is_approved','1')->get(); */
			  
			  
			  
			$metal = Product::with('category')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
              })->where('products.is_active','1')->where('products.category_id',$category->id)->where('products.is_approved','1')->get();
			  
			  
			  

        } else {

            $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->get();
        }
          //$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
		
		
		if ($request->ajax() && !empty($request->all()) ) {
                
                $view = view('front.ajaxcategorypagination',compact('products'))->render();
                return response()->json(['html'=>$view]);
            }
			
       return view('front.category', compact('products', 'max_page', 'category','banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','maleProduct','femaleProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','offerProduct','metal'));

         
        
    }

    public function filterPrice(Request $request) {
         //\DB::connection()->enableQueryLog();
        $category = Category::with('category')->where('slug', $request->segment(2))->where('is_active', 1)->firstOrFail();
        $plain = 0;
        $stone = 0;
        $beads = 0;
        if($request->segment(3)=='men') {

            /* $products =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
			
			$products =  Product::select('products.*')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();

            /* $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'p%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
			
			
            
            /* $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 's%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 's%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

            /* $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'b%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

        } elseif($request->segment(3)=='women') {

            /* $products =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
			
			$products =  Product::select('products.*')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();

            /* $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'p%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');;
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
            
            /* $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 's%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 's%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

            /* $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'b%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

        } 
        elseif($request->segment(3)=='kids') {
            
            /* $products =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'k%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get(); */
			
			$products =  Product::select('products.*')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
             
            /* $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'p%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'k%')->where('name','LIKE','%Gender%');;
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
            
            /* $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 's%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'k%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 's%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

            /* $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', 'b%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', 'k%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('is_approved','1')->where('category_id', $category->id)->count(); */
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();


        }
         else {
            if($request->segment(3)!='' && $request->segment(4)=='') {
               
				//\DB::connection()->enableQueryLog();
				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
				
                //$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
            } else {
                
                 
                 
				
				$products =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')
				->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')
				->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
				

                //$products = Product::whereBetween('new_price', [$minPrice, $request->segment(4)])->where('category_id',$category->id)->get();
                //dd($products);
            }
        }
        
        
        
         
        $pagination_count = 20;
        
        
              // print_r($products);
        // $product_max_price = $products->max('price');
        $products = $products->paginate($pagination_count);
		$max_page = $products->total() / $pagination_count;
		//echo $max_page;
		//print_r($products);
        
		//print_r($debugQry);
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
				if($request->segment(3) == 'men'){
					
		}elseif($request->segment(3) == 'women'){
			
		}elseif($request->segment(3) == 'kids'){
			
		}else {

            if($request->segment(3)!='' && $request->segment(4)=='') {
                
                /* $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('category_id',$category->id)->count(); */
				
				$offerProduct =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.category_id',$category->id)->count();
				
				

                /* $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '18')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$category->id)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('is_approved','1')->get(); */
				
				$purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.is_approved','1')->get();
				
				
				
                
                /* $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '14')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$category->id)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('is_approved','1')->get(); */
				
				$purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.is_approved','1')->get();
				

                /* $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$category->id)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('is_approved','1')->get(); */
				
				
				$purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.is_approved','1')->get();
                

                /* $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->get(); */
				
				$metal =  Product::with('category')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->get();
				
                

                /* $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('category_id',$category->id)->count(); */
				
				$maleProduct =  Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'm%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.category_id',$category->id)->count();
				

                /* $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('category_id',$category->id)->count(); */
				
				$femaleProduct =  Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'f%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.category_id',$category->id)->count();


                /* $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` > '.$request->segment(3).' ) or (`product_discount` is not null  && `new_price` > '.$request->segment(3).' )')->where('category_id',$category->id)->count(); */
				
				$uniSexProduct =  Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice > '.$request->segment(3).' ')->where('products.category_id',$category->id)->count();
                
                
            } else {
                /* $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('product_discount','<>',false)->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('category_id',$category->id)->count(); */
				  
				  
				  $offerProduct =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.category_id',$category->id)->count();

                /* $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value',  '18')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id', $category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$category->id)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('is_approved','1')->get(); */
				
				$purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.is_approved','1')->get();
				
                
                /* $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '14')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$category->id)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('is_approved','1')->get(); */
				
				
				$purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.is_approved','1')->get();
            
                
               /*  $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                    $query->where('value', '22')->where('name','LIKE','%Purity%');
                })->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('category_id',$category->id)->where('is_active','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('is_approved','1')->get(); */
				
				$purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.is_approved','1')->get();

                /* $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('category_id',$category->id)->get(); */
				  
				  $metal =  Product::with('category')
				  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				  ->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				  ->where('product_category_styles.category_id',$category->id)
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.category_id',$category->id)->get();
				  
                
                /* $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'm%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('category_id',$category->id)->count(); */
				
				$maleProduct = Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'm%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.is_approved','1')->count();
				

                /* $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'f%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('category_id',$category->id)->count(); */
				
				$femaleProduct = Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'f%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.is_approved','1')->count();
            
                /* $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', 'u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('is_active','1')->where('is_approved','1')->whereRaw('(`product_discount` is null  && `old_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (`product_discount` is not null  && `new_price` BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('category_id',$category->id)->count(); */
				
				$uniSexProduct = Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('products_saleprice.saleprice BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).' ')->where('products.is_approved','1')->count();

            }
            
            
		}  

        } 

        
        
        //dd($offerProduct);
        
        
        
        //dd($purity_eighteen_carat);
       if ($request->ajax() && !empty($request->all()) ) {
                
                $view = view('front.ajaxcategorypagination',compact('products'))->render();
                return response()->json(['html'=>$view]);
            }
        
       

     
          //$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
        return view('front.category', compact('products', 'max_page', 'category','banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','maleProduct','femaleProduct','uniSexProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','offerProduct','metal','plain','stone','beads'));
         

         
        
        
    }

    public function befilterPrice(Request $request) {
         \DB::connection()->enableQueryLog();
        $category = Category::with('category')->where('slug', $request->segment(2))->where('is_active', 1)->firstOrFail();
        $plain = 0;
        $stone = 0;
        $beads = 0;
		$pagination_count = 20;
        if($request->segment(3)=='men') {

            
			
			$products =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();

            
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
			
			
            
            
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 's%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

            
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

        } elseif($request->segment(3)=='women') {

            
			
			$products =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();

            
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
            
            
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 's%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

            
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

        } 
        elseif($request->segment(3)=='kids') {
            
           
			
			$products =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->get();
             
           
			
			$plain = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'p%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
            
            
			
			$stone = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 's%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();

            
			
			$beads = Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'b%')
			->where('product_specification_type.specification_type_id', '20')
			->where('product_specification_type.value', 'like', 'k%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id',$category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();


        }
         else {
            if($request->segment(3)!='' && $request->segment(4)=='') {
              	


				
				
				 $products =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.category_id',$category->id)->skip(0)->take($pagination_count)->orderBy('products.id','DESC')->get();
				
				$productsCount =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.category_id',$category->id)->skip(0)->take($pagination_count)->orderBy('products.id','DESC')->count();
                
            } else {
                
                 
                
                
                $products =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->skip(0)->take($pagination_count)->get();
				
				$productsCount =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.category_id',$category->id)->orderBy('products.id','DESC')->count();
				

            }
        }
        
        
        
         
        
        
        
             
        // $product_max_price = $products->max('price');
        /* $products = $products->paginate($pagination_count);
		$max_page = $products->total() / $pagination_count; */
		
		//echo $productsCount;
		//print_r($products);
		$products = $products->paginate($pagination_count);
		$max_page = $productsCount / $pagination_count;
		//print_r($products);
        $debugQry = \DB::getQueryLog();
		print_r($debugQry);
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

            
			
			
			$maleProduct =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();
			

         } else {

           
			  
			  
			  $maleProduct =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'm%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->category_id)->count();

              



             
         }
         
         if($category->category_id=='0') { 
            
           
             
            $femaleProduct =  Product::distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();

         } else {

            
           

            $femaleProduct =  Product::select(\DB::raw('count(*) as totalFemale'))->distinct('product_group')
			->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
			->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
			->where('product_specification_type.value', 'like', 'f%')
			->where('product_specification_type.specification_type_id', '11')
			->where('product_category_styles.category_id', $category->category_id)
			->where('product_category_styles.product_style_id', $category->id)
			->where(function ($query) {
                $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
            })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->category_id)->count();


            
         }

         if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                
				  
				  
				  $kidsProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'k%')
				->where('product_specification_type.specification_type_id', '11')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->count();
				

            } else {
                
				  $kidsProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'k%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->count();
				
				  
				
            }   

            

            
            

        } else {

            if($category->slug == 'gift-item') { 

               
				  
				  $kidsProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'k%')
				->where('product_specification_type.specification_type_id', '11')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->count();
				  

            } else {
               
				  
				  $kidsProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'k%')
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

                
				  
				  $uniSexProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->count();

            } else {
                
				  
				  
				  $uniSexProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id', $category->id)->count();
            }

            

               
            

         } else {

            if($category->slug == 'gift-item') { 

                
				  
				  $uniSexProduct =  Product::distinct('product_group')
				  ->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->whereIn('product_category_styles.category_id', array(1,12))
				->whereIn('product_category_styles.product_style_id', array( 31, 30,42,40,41,70))
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereIn('products.category_id', array(1,12))->count();

            } else {
                
				  
				  $uniSexProduct =  Product::distinct('product_group')
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
				if($request->segment(3) == 'men'){
					
		}elseif($request->segment(3) == 'women'){
			
		}elseif($request->segment(3) == 'kids'){
			
		}else {

            if($request->segment(3)!='' && $request->segment(4)=='') {
                
                
				$offerProduct =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.category_id',$category->id)->count();
				
				

                
				$purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.is_approved','1')->get();
				
				
				
                
               
				$purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.is_approved','1')->get();
				

                
				
				$purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.is_approved','1')->get();
                

                
				$metal =  Product::with('category')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->id)->get();
				
                

                
				$maleProduct =  Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'm%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.category_id',$category->id)->count();
				

                
				$femaleProduct =  Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'f%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.category_id',$category->id)->count();


                
				$uniSexProduct =  Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price > '.$request->segment(3).' ) or (products.product_discount is not null  && products.new_price > '.$request->segment(3).' )')->where('products.category_id',$category->id)->count();
                
                
            } else {
               
				  
				  $offerProduct =  Product::distinct('product_group')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.product_discount','<>',false)->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.category_id',$category->id)->count();

               
				$purity_eighteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '18')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.is_approved','1')->get();
				
                
               
				
				$purity_fourteen_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '14')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.is_approved','1')->get();
            
                
              
				$purity_twenty_two_carat = Product::leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', '22')
				->where('product_specification_type.specification_type_id', '9')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.is_approved','1')->get();

                 
				  $metal =  Product::with('category')
				  ->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				  ->where('product_category_styles.category_id',$category->id)
				  ->where(function ($query) {
                    $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                  })->where('products.is_active','1')->where('products.is_approved','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.category_id',$category->id)->get();
				  
                
                
				$maleProduct = Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'm%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.is_approved','1')->count();
				

                
				$femaleProduct = Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'f%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.is_approved','1')->count();
            
                
				$uniSexProduct = Product::distinct('product_group')
				->leftjoin('product_specification_type', 'product_specification_type.product_id', '=', 'products.id')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->where('product_specification_type.value', 'like', 'u%')
				->where('product_specification_type.specification_type_id', '11')
				->where('product_category_styles.category_id', $category->id)
				->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                })->where('products.category_id',$category->id)->where('products.is_active','1')->whereRaw('(products.product_discount is null  && products.old_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).') or (products.product_discount is not null  && products.new_price BETWEEN '.$request->segment(3).' AND  '.$request->segment(4).')')->where('products.is_approved','1')->count();

            }
            
            
		}  

        } 

        
        
       
       if ($request->ajax() && !empty($request->all()) ) {
                
                $view = view('front.ajaxcategorypagination',compact('products'))->render();
                return response()->json(['html'=>$view]);
            }
        
       

     
          
        return view('front.category', compact('products', 'max_page', 'category','banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','maleProduct','femaleProduct','uniSexProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','offerProduct','metal','plain','stone','beads'));
         

         
        
        
    }

    

}
