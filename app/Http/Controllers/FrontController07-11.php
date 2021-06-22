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
        $featured_products = $products->sortByDesc(function($product) {return $product->reviews->where('approved', 1)->where('rating', '!=', null)->avg('rating');})->take(10);
        $best_selling_products = $products->where('best_seller',1)->sortByDesc(function($product) {return $product->sales;})->take(10);
         
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
         
        return view('front.index', compact('topcategories','products', 'featured_products', 'best_selling_products', 'brands',  'deals', 'banners_main_slider', 'banners_right_side', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_deal_slider', 'sections_below_deal_slider', 'sections_above_footer', 'sections_above_side_banners', 'sections_below_side_banners', 'testimonials', 'default_photo','middleBanner','collections','certifications'));
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

        $keywordArr = explode(' ',$request->keyword);
        $keyword = $request->keyword;
        $location_id = session('location_id');
        $category = Category::with('category','products')->where('slug', 'LIKE', '%'.$keyword.'%')->where('is_active', 1)->first();
        
        
        // if(count($keywordArr) > 1) {
            
        //     $products = Product::leftJoin('product_category_styles', function ($join) {
        //         $join->on('products.category_id', '=', 'product_category_styles.category_id')
        //         ->orOn('product_category_styles.product_id', '=', 'products.id');
        //     })
        //     ->leftJoin('categories', 'categories.id', '=', 'product_category_styles.product_style_id')
        //     ->whereRaw("products.id = product_category_styles.product_id")
        //     ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id,(select id from categories c where c.name LIKE '".$keywordArr[0]."%' order by id desc limit 1) as cat_id   "))
        //     ->where('categories.name','LIKE',$keywordArr[1].'%')->havingRaw('cat_id = product_category_styles.product_style_id or cat_id = product_category_styles.category_id')->orderBy('products.id','DESC')->get();

        // } else {

        //     $products = Product::leftJoin('product_category_styles', function ($join) {
        //         $join->on('products.category_id', '=', 'product_category_styles.category_id')
        //         ->orOn('product_category_styles.product_id', '=', 'products.id');
        //     })
        //     ->leftJoin('categories', 'categories.id', '=', 'product_category_styles.product_style_id')
        //     ->whereRaw("products.id = product_category_styles.product_id")
        //     ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id,(select id from categories c where c.name LIKE '".$keywordArr[0]."%' and (c.id = `product_category_styles`.`product_style_id` or c.id = `product_category_styles`.`category_id`)  limit 1) as cat_id   "))
        //     ->havingRaw('cat_id = product_category_styles.product_style_id or cat_id = product_category_styles.category_id')->get();
             
        // }
       // dd($products);
        $products = Product::where('location_id', $location_id)->where('name', 'LIKE', '%'.$keyword.'%')->where('is_active', 1)->where('is_approved', 1)->get();
         
        if($category) {
            
             
            $pagination_count = 20;
          
            $product_max_price = $products->max('price');
            $products = $products->paginate($pagination_count);

            $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->category_id)->get();
            
            $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->category_id)->count();

            $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->category_id)->count();

            $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('product_discount','<>',false)->where('is_active','1')->where('category_id',$category->category_id)->count();

            $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('category_id',$category->category_id)->get();

            $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('category_id',$category->category_id)->get();

            $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('product_style_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('category_id',$category->category_id)->get();

        } else {
            
             
            $product_max_price = 0;
            $category = array();
            $metal =  array();
            $maleProduct =   array();
            $femaleProduct =   array();
            $offerProduct =  array();
            $purity_eighteen_carat =  array();
            $purity_fourteen_carat =  array();
            $purity_twenty_two_carat =  array();
        }

        
        
        return view('front.frontsearch', compact('products', 'product_max_price','keyword','category','metal','maleProduct','femaleProduct','offerProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat'));
    }

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

        
        $category = Category::with('category')->where('slug', $request->segment(2))->where('is_active', 1)->firstOrFail();
         
        $metal =  Product::with('category')->where(function ($query) {
            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
        })->where('is_active','1')->where('category_id',$category->id)->get();

        $shopbyMetal = ShopByMetalStone::where('id',$request->segment(3))->first();
     
        

        $products = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category,$shopbyMetal)  {
            $query->where('product_specification_type.value', 'like', strtolower($shopbyMetal->name))->where('name','LIKE','%metal%');
        })->whereHas('product_category_styles', function($query) use($category) {
            $query->where('category_id',$category->id);
        })->where(function ($query) {
            $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
          })->where('is_active','1')->where('category_id',$category->id)->get(); 

        $pagination_count = config('settings.pagination_count') ? config('settings.pagination_count') : 8;
        
        $products = $products->paginate($pagination_count);
        
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

            $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->id)->count();

         } else {

            $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->category_id)->count();



             
         }
         
         if($category->category_id=='0') { 

            $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->id)->count();

            
            

         } else {

            $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->category_id)->count();

            


            
         }

        //dd('male :- '.$maleProduct.'female: - '.$femaleProduct);
        // die;

        
        
        if($category->category_id=='0') { 

            $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('product_discount','<>',false)->where('is_active','1')->where('category_id',$category->id)->count();

        } else {

            $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('product_discount','<>',false)->where('is_active','1')->where('category_id',$category->category_id)->count();
        }

        
        
        //dd($offerProduct);
        
        
        if($category->category_id=='0') {

            $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->get();

        } else {

            $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->get();

        }
        //dd($purity_eighteen_carat);
        if($category->category_id=='0') {

            $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->get();

        } else {
            $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->get();
        }
        
        if($category->category_id=='0') {

            $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->get();

        } else {

            $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->get();

        }

        if($category->category_id=='0') {

            $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->id)->get();

        } else {

            $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->category_id)->get();
        }
          
       return view('front.category', compact('products', 'category','banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','maleProduct','femaleProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','offerProduct','metal'));

         
        
    }

    public function filterPrice(Request $request) {
 
        $category = Category::with('category')->where('slug', $request->segment(2))->where('is_active', 1)->firstOrFail();
        $plain = 0;
        $stone = 0;
        $beads = 0;
        if($request->segment(3)=='men') {

            $products =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->id)->get();

            $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');;
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();
            
            $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%s%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();

            $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%b%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();

        } elseif($request->segment(3)=='women') {

            $products =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->id)->get();

            $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');;
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();
            
            $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%s%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();

            $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%b%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();

        } 
        elseif($request->segment(3)=='kids') {
            
            $products =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->id)->get();
             
            $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');;
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();
            
            $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%s%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();

            $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%b%')->where('name','LIKE','%Type%');
            })->whereHas('specificationTypes', function($query) use($category) {
                $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id', $category->id)->count();


        }
         else {
            if($request->segment(3)!='' && $request->segment(4)=='') {
                $products = Product::where('new_price', '>', $request->segment(3))->where('category_id',$category->id)->get();
            } else {
                $products = Product::whereBetween('new_price', [$request->segment(3), $request->segment(4)])->where('category_id',$category->id)->get();
            }
        }
        
        
        
         
        $pagination_count = 20;
        
        
               
        // $product_max_price = $products->max('price');
        $products = $products->paginate($pagination_count);
        
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
        
        // if($category->slug=='gold') {

        //     if($request->segment('3') == 'women') {
        //         $gender = 'female';
        //     } else {
        //         $gender = $request->segment('3');
        //     } 
             
        //     $plain = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category,$gender)  {
        //         $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%')->where('product_specification_type.value', 'like', '%'.$gender.'%')->where('name','LIKE','%Gender%');
                 
        //     })->whereHas('product_category_styles', function($query) use($category) {
        //         $query->where('category_id', $category->id);
        //     })->where(function ($query) {
        //         $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
        //     })->where('is_active','1')->where('category_id', $category->id)->toSql();
        //     dump($plain); 
        //     $stone = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category,$gender)  {
        //         $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%')->where('product_specification_type.value', 'like', '%'.$gender.'%');
        //     })->whereHas('product_category_styles', function($query) use($category) {
        //         $query->where('category_id', $category->id);
        //     })->where(function ($query) {
        //         $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
        //     })->where('is_active','1')->where('category_id', $category->id)->count();

        //     $beads = Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category,$gender)  {
        //         $query->where('product_specification_type.value', 'like', '%p%')->where('name','LIKE','%Type%')->where('product_specification_type.value', 'like', '%'.$gender.'%');
        //     })->whereHas('product_category_styles', function($query) use($category) {
        //         $query->where('category_id', $category->id);
        //     })->where(function ($query) {
        //         $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
        //     })->where('is_active','1')->where('category_id', $category->id)->count();

        // } 


        if($category->category_id=='0') { 

            $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
            })->where('is_active','1')->where('category_id',$category->id)->count();

         } else {

            $maleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%m%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->category_id)->count();



             
         }
         
         if($category->category_id=='0') { 

            $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->id)->count();

            
            

         } else {

            $femaleProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                $query->where('product_specification_type.value', 'like', '%f%')->where('name','LIKE','%Gender%');
            })->whereHas('product_category_styles', function($query) use($category) {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->category_id)->count();

            


            
         }

         if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $kidsProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $kidsProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('category_id',$category->id)->count();
            }   

            

            
            

        } else {

            if($category->slug == 'gift-item') { 

                $kidsProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $kidsProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%k%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('category_id',$category->category_id)->count();
            }
        }

        if($category->category_id=='0') { 

            if($category->slug == 'gift-item') { 

                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('category_id',$category->id)->count();
            }

            

               
            

         } else {

            if($category->slug == 'gift-item') { 

                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->whereIn('category_id', array(1,12))->count();

            } else {
                $uniSexProduct =  Product::distinct('product_group')->whereHas('specificationTypes', function ($query) use($category)  {
                    $query->where('product_specification_type.value', 'like', '%u%')->where('name','LIKE','%Gender%');
                })->whereHas('product_category_styles', function($query) use($category) {
                    $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
                })->where(function ($query) {
                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                  })->where('is_active','1')->where('category_id',$category->category_id)->count();
            }
            
             
            


            
         }

        //dd('male :- '.$maleProduct.'female: - '.$femaleProduct);
        // die;

        
        
        if($category->category_id=='0') { 

            $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('product_discount','<>',false)->where('is_active','1')->where('category_id',$category->id)->count();

        } else {

            $offerProduct =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('product_discount','<>',false)->where('is_active','1')->where('category_id',$category->category_id)->count();
        }

        
        
        //dd($offerProduct);
        
        
        if($category->category_id=='0') {

            $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->get();

        } else {

            $purity_eighteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '18')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->get();

        }
        //dd($purity_eighteen_carat);
        if($category->category_id=='0') {

            $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->get();

        } else {
            $purity_fourteen_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '14')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->get();
        }
        
        if($category->category_id=='0') {

            $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->id)->get();

        } else {

            $purity_twenty_two_carat = Product::whereHas('specificationTypes', function ($query)  {
                $query->where('value', '22')->where('name','LIKE','%Purity%');
            })->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id',$category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('category_id',$category->category_id)->get();

        }

        if($category->category_id=='0') {

            $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->id)->get();

        } else {

            $metal =  Product::with('category')->whereHas('product_category_styles', function ($query) use($category)  {
                $query->where('category_id', $category->category_id)->where('product_style_id',$category->id);
            })->where(function ($query) {
                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
              })->where('is_active','1')->where('category_id',$category->category_id)->get();
        }
          
        return view('front.category', compact('products', 'category','banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','maleProduct','femaleProduct','uniSexProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','offerProduct','metal','plain','stone','beads'));
         

         
        
        
    }

    

}
