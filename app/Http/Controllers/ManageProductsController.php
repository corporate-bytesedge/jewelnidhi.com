<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\ComparisionGroup;
use App\Http\Requests\ProductsCreateRequest;
use App\Http\Requests\ProductsUpdateRequest;
use App\Location;
use App\Photo;
use App\Product;
use App\Other;
use App\Vendor;
use App\SpecificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Certificate;
use App\Metal;
use App\MetalPuirty;
use App\Setting;
use App\OverlayPhoto;
use App\SilverItem;
use App\SizeGroup;
use App\ProductPinCode;
use App\ProductStyle;
use App\ProductCategoryStyle;
use App\Http\Requests\ProductRequest;
use DataTables;
use Illuminate\Support\Str; 

class ManageProductsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $vendor = Auth::user()->isApprovedVendor();
            $products_vendor = null;
            
            if($vendor) {
                     
                $products = Product::where('location_id', Auth::user()->location_id)->where('vendor_id', $vendor->id);
                
            } else {

                    if(request()->has('stock') && request()->stock == 'low') {
                        $products = Product::where('location_id', Auth::user()->location_id)->where('in_stock', '<=', 10)->where('in_stock', '>=', 1);
                    } else if(request()->has('vendor') && request()->vendor) {
                        $vendor_id = request()->vendor;
                        $products_vendor = Vendor::select('id', 'name','user_id')->whereId($vendor_id)->firstOrFail();
                        $products = Product::where('location_id', Auth::user()->location_id)->where('user_id', $products_vendor->user_id);
                    } else if(request()->has('stock') && request()->stock == 'none') {
                        $products = Product::where('location_id', Auth::user()->location_id)->where('in_stock', '<', 1);
                    } else {
                        $products = Product::where('products.location_id', Auth::user()->location_id)->where('products.is_approved',true);
                    }
                }
                
                /* Ordering */
                $products = $products->orderBy('id', 'desc');
                
                /* Pagination */
                if(empty(request()->all)) {
                    if(!empty(request()->per_page)) {
                        $per_page = intval(request()->per_page);
                    } else {
                        $per_page = 15;
                    }
                } else {
                    $per_page = $products->count();
                }
                // $products = $products->get();
                $i =1; 
                
                return Datatables::of($products)
                    ->addIndexColumn()
                    ->addColumn('Ids', function($row){
                        return '<td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="'.$row->id.'"></td>';
                        
                        
                    })->addColumn('group', function($row) {

                        $disable = $row->product_group_id !='' ? 'disabled ' : '';
                        $groupCheck = '<input id="groupCheckbox_'.$row->id.'" class="groupCheckbox" '.$disable.'  type="checkbox" name="groupCheckbox[]" value="'.$row->id.'">';
                        if($row->product_group_id !='') {
                            $groupCheck .='<span><a style="color:red; text-decoration:none;" href="javascript:void(0);" id="UnGrouped_'.$row->id.'" title="Ungrouped" onclick="unGrouped('.$row->id.');"> <i class="fa fa-unlock-alt" ></i></a> </span>'; 
                            $groupCheck .= '<span style="color:red;">'.($row->product_group_default == 1 ? 'Default' : '').'</span>'; 
                        }
                                                            
                        return $groupCheck;
                        
                        
                    })
                    ->addColumn('category', function($row){
                        return $row->category->name;
                        
                        
                    })->addColumn('style', function($row) { 
                        $arr = array();
                        $styleArr = array();
                        $style = \App\ProductCategoryStyle::where('category_id',$row->category->id)->where('product_id',$row->id)->get();
                        if(!empty($style)) {
                                foreach($style AS $key => $val) {
                                $styles = \App\Category::where('id',$val->product_style_id)->where('category_id',$row->category->id)->first();
                                array_push($styleArr,$styles->name);
                                }
                        }
                        if(!empty($styleArr)) {
                            return  implode(",",$styleArr);
                        }

                        
                    })->addColumn('photo', function($row) use($products) {

                        if($row->photo) {
                            $image_url = \App\Helpers\Helper::check_image_avatar($row->photo->name, 50);
                            $image = '<img src="'.$image_url.'" height="50px" alt="'.$row->name.'"  />';
                        } else {
                            $image = '<img src="https://via.placeholder.com/50x50?text=No+Image" height="50px" alt="'.$row->name.'" />';
                        }
                                      
                        return $image;
                        
                        
                    })->addColumn('name', function($row){
                        return $row->name;
                    })->addColumn('price', function($row) {
                         
                            if($row->product_discount!='') {
                                $price = \App\Helpers\IndianCurrencyHelper::IND_money_format($row->new_price);
                            } else {
                                $price = \App\Helpers\IndianCurrencyHelper::IND_money_format($row->old_price);
                            }
                        return $price;
                    })->addColumn('status', function($row) {
                            if($row->is_active == '1') {
                                $status = '<span class="badge bg-success"><i class="fa fa-check"></i></span>';
                            } else {
                                $status = '<span class="badge bg-danger"><i class="fa fa-times"></i></span>';
                            }
                            
                            
                        return $status;
                    })
                    
                    ->addColumn('approved', function($row) {
                        if($row->is_approved == '1') {
                            $approved = '<span class="badge bg-success"><i class="fa fa-check"></i></span>';
                        } else {
                            $approved = '<span class="badge bg-danger"><i class="fa fa-times"></i></span>';
                        }
                        
                        
                    return $approved;
                })->filter(function ($query) use ($request) {
                    $orderbyColumn = '';
                    if ($request->has('search') && ! is_null($request->get('search')) && is_null($request->get('search_column'))) {
                        $query->where('vendor_id', $request->get('search'));
                       
                    }else if ($request->has('search_column') && ! is_null($request->get('search_column')) &&  is_null($request->get('search')) ) {
                        
                       $stringArr = explode(' ',$request->search_column); 

                       $subQueryarr = array();
                       $productSubQueryArr = array();
                       if(count($stringArr) > 0 ) {
                           foreach($stringArr AS $value) {
                               
                               array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
                               array_push($productSubQueryArr," products.name LIKE '%".$value."%'" );
                           }
                       }
                       
                       $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
                       
                       if(is_null($catSql->rootcatids)) {
                          
                            $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
                            $subproductid = \DB::table('products')->select(\DB::raw("GROUP_CONCAT(id) AS product_id"))->whereRaw('is_approved = 1 AND is_active = 1 AND ('. implode(' and ',$productSubQueryArr).')')->first();  
                       } else {
                          
                        $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
                        $subproductid = \DB::table('products')->select(\DB::raw("GROUP_CONCAT(id) AS product_id"))->whereRaw('category_id IN ('.$catSql->rootcatids.')  AND ('. implode(' and ',$productSubQueryArr).')')->first();
                       }
                       
 
                       $querySql = $query->leftJoin('product_category_styles', function ($join) {
                        $join->on('products.category_id', '=', 'product_category_styles.category_id')
                        ->orOn('product_category_styles.product_id', '=', 'products.id');
                    })
                    ->leftJoin('categories', 'categories.id', '=', 'product_category_styles.product_style_id')
                    ->whereRaw("products.id = product_category_styles.product_id")
                    ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id  "));
                    if(!is_null($subCatSql->subcatids)) {
                        $query->whereRaw('product_category_styles.product_style_id IN('.$subCatSql->subcatids.') ');
                    }
                    if(!is_null($catSql->rootcatids)) {
                        $query->whereRaw('product_category_styles.category_id  IN('.$catSql->rootcatids.')');
                    }
                   
                    if(is_null($subCatSql->subcatids) && is_null($catSql->rootcatids) && is_null($subproductid->product_id)) {
                        $query->where('jn_web_id',$request->get('search_column'));
                    }
                    
                    if(!is_null($subproductid->product_id) && (is_null($catSql->rootcatids) && is_null($subCatSql->subcatids) ) ) {
                        $query->whereRaw('product_category_styles.product_id  IN('.$subproductid->product_id.')');
                    }
                    
                    if(!is_null($subproductid->product_id) && !is_null($catSql->rootcatids) || !is_null($subCatSql->subcatids)) {
                        
                        if(count($stringArr) > 0 ) {
                            
                            $productSearch = array();
                            $i = 1;
                            foreach($stringArr AS $value) {
                                
                                
                                array_push($productSearch," when products.name LIKE '%".$value."%' then ".$i." " );
                            $i++; 
                                 
                            }
                            
                        }
                        $orderbyColumn = "case  ".implode(' ',$productSearch)."   else 3 end ";
                     
                        //$orderbyColumn = "case when products.name LIKE '%".$stringArr[0]."%' then 1  else 3 end ";
                    }
                    
                    $query->groupBy('products.id');
                   
                   if($orderbyColumn) {
                     
                    $query->orderByRaw($orderbyColumn);
                   } else {
                       
                    $query->orderBy('products.id','DESC');
                   }
                   
                   
                  //dd($query->toSql());
                            
                           
                    } else if ($request->has('search') && ! is_null($request->get('search')) && $request->has('search_column') && ! is_null($request->get('search_column')) ) {
                        
                        $stringArr = explode(' ',$request->search_column); 

                        $subQueryarr = array();
                        if(count($stringArr) > 0 ) {
                            foreach($stringArr AS $value) {
                                
                                array_push($subQueryarr," categories.name LIKE '%".$value."%'" );
                            }
                        }
                        
                        $catSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS rootcatids"))->whereRaw('category_id = 0  AND ('. implode(' or ',$subQueryarr).')')->first();
                        
                        if(is_null($catSql->rootcatids)) {
                           
                             $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id <> 0 AND ('. implode(' or ',$subQueryarr).')' )->first();
                        } else {
                           
                         $subCatSql = \DB::table('categories')->select(\DB::raw("GROUP_CONCAT(id) AS subcatids"))->whereRaw('category_id IN ('.$catSql->rootcatids.') AND ('. implode(' or ',$subQueryarr).')' )->first();
                        }
                        
  
                        $querySql = $query->leftJoin('product_category_styles', function ($join) {
                         $join->on('products.category_id', '=', 'product_category_styles.category_id')
                         ->orOn('product_category_styles.product_id', '=', 'products.id');
                     })
                     ->where('vendor_id',$request->get('search'))
                     ->leftJoin('categories', 'categories.id', '=', 'product_category_styles.product_style_id')
                     ->whereRaw("products.id = product_category_styles.product_id")
                     ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id  "));
                     if(!is_null($subCatSql->subcatids)) {
                         $query->whereRaw('product_category_styles.product_style_id IN('.$subCatSql->subcatids.') ');
                     }
                     if(!is_null($catSql->rootcatids)) {
                         $query->whereRaw('product_category_styles.category_id  IN('.$catSql->rootcatids.')');
                     }
                     if(is_null($subCatSql->subcatids) && is_null($catSql->rootcatids)) {
                        $query->where('jn_web_id',$request->get('search_column'));
                    }
                     $query->groupBy('products.id');
                        
                               
                        
                        /* $query->leftJoin('product_category_styles', function ($join) {
                            $join->on('products.category_id', '=', 'product_category_styles.category_id')
                            ->orOn('product_category_styles.product_id', '=', 'products.id');
                        })->where('vendor_id', $request->get('search'))
                        ->whereRaw("products.id = product_category_styles.product_id")
                        ->select(\DB::raw("products.*,product_category_styles.product_style_id AS pro_style_id,product_category_styles.category_id AS cate_id,(select id from categories c where c.name LIKE '".$request->get('search_column')."%' order by rand() limit 1) as cat_id "))
                        ->havingRaw('cat_id = product_category_styles.product_style_id or cat_id = product_category_styles.category_id');
                        */
                        //dd($query1->toSql());
                    }
                    
                })->escapeColumns('status')
                    ->make(true);

        }
        
      
         
        if(Auth::user()->can('read', Product::class) || isset($vendor) && $vendor) {
            $vendor = Auth::user()->isApprovedVendor();
            $products_vendor = null;
            if($vendor) {
                 
               
                $products = Product::where('location_id', Auth::user()->location_id)->where('user_id', $vendor->user_id);
               
            } else {
                if(request()->has('stock') && request()->stock == 'low') {
                    $products = Product::where('location_id', Auth::user()->location_id)->where('in_stock', '<=', 10)->where('in_stock', '>=', 1);
                } else if(request()->has('vendor') && request()->vendor) {
                    $vendor_id = request()->vendor;
                    
                    $products_vendor = Vendor::select('id', 'name','user_id')->whereId($vendor_id)->firstOrFail();
                   
                    $products = Product::where('location_id', Auth::user()->location_id)->where('user_id', $products_vendor->user_id);
                     
                } else if(request()->has('stock') && request()->stock == 'none') {
                    $products = Product::where('location_id', Auth::user()->location_id)->where('in_stock', '<', 1);
                    
                } else {
                    $products = Product::where('location_id', Auth::user()->location_id);
                   
                }
                 
            }

            /* Search */
            if(!empty(request()->s)) {
                $search = request()->s;
                $products = $products->where('name', 'LIKE', "%$search%")->orWhereHas('category', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                })->orWhere('jn_web_id', "$search");
            }

            /* Ordering */
            //$products = $products->orderBy('id', 'desc');

            /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $products->count();
            }
            $products = $products->get();
           
            $vendor = Vendor::all();
            return view('manage.products.index', compact('products', 'vendor', 'products_vendor'));
        } else {
            
            return view('errors.403');
        }
    }

    public function sales()
    {
        
        if(Auth::user()->can('viewSales', Other::class)) {
            
            $vendor = Auth::user()->isApprovedVendor();
            if ($vendor){
                $sales = Product::select('id', 'name', 'sales', 'new_price', 'photo_id', 'location_id')->where('vendor_id', $vendor->id)->where('location_id', Auth::user()->location_id)->orderBy('id', 'desc')->get();
            }else{
                $sales = Product::where('location_id', Auth::user()->location_id)->select('id', 'name', 'sales', 'new_price', 'photo_id', 'location_id')->orderBy('id', 'desc')->get();
            }
            
            return view('manage.sales.sales', compact('sales'));
        } else {
            return view('errors.403');
        }
    }

    public function create()
    {
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('create', Product::class) || $vendor) {
            $location_id = Auth::user()->location_id;
            $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
            $comparision_groups = ComparisionGroup::all();
            $brands = Brand::where('location_id', $location_id)->pluck('name', 'id')->toArray();
            $specification_types = SpecificationType::where('location_id', $location_id)->orderBy('name', 'asc')->pluck('name', 'id')->prepend('Select', '')->toArray();
             
            
            if($vendor) {
                $products = Product::select('id', 'name')->where('location_id', $location_id)->where('vendor_id', $vendor->id)->get();
            } else {
                $products = Product::select('id', 'name')->where('location_id', $location_id)->get();
            }
            $certificates = Certificate::pluck('name','id');
            $metals = Metal::where('is_active',1)->pluck('name','id');
            $puirty = MetalPuirty::where('is_active',1)->pluck('name','id');
            $current_gold_price = Setting::where('key','22_CRT')->first();
            $current_silver_item_price = Setting::where('key','silver_item_rate')->first(); 
            $current_silver_jewellery_price = Setting::where('key','silver_jewellery_rate')->first();  
            $current_platinum_price = Setting::where('key','950_KT')->first();         
            $categories = Category::with('categories')->where('name','LIKE','%GOLD%')->get();
            // $silveritems = SilverItem::all();
            $pincodes = ProductPinCode::pluck('name','id');
            
            return view('manage.products.create', compact('root_categories','metals','puirty', 'brands', 'specification_types', 'products', 'vendor', 'comparision_groups','certificates','current_gold_price','categories','pincodes','current_silver_item_price','current_silver_jewellery_price','current_platinum_price'));

        } else {
            return view('errors.403');
        }
    }

    //public function store(ProductsCreateRequest $request)
    public function store(Request $request,ProductRequest $ProductRequest)
    { 
        
        
        $vendor = Auth::user()->isApprovedVendor();
        if($vendor) {
            $userInput = $request->all();
           
            if($userInput['save_draft_btn'] == 'save_draft') {

                $this->validate($request, [
                    'overlay_photo.*' => 'image'
                ]);
                $input['is_approved'] = false;
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id ? $request->metal_id : null; 
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                $input["in_stock"] = $request->in_stock;
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
               
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('sku', $request->sku)->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
           
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
    
                if(array_key_exists($request->category, $categories)) {
                    $input['category_id'] = $request->category;
                } else {
                    $input['category_id'] = NULL;
                }
                
                
                $input['is_active'] = 0;

                $location_id = Auth::user()->location_id;
                $input['location_id'] = $location_id;
                if($request->file('photo')) {
                    $photo = $request->file('photo');
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                } else {
                    $input['best_seller'] = false;
                }
                if($request->file('overlay_photo')) {
                    $overlay_photo = $request->file('overlay_photo');
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    $overlay_photo = OverlayPhoto::create(['name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
                $product = new Product($input);
                $product->save();
            
                if(!empty($userInput['photos'])) {
                    foreach($userInput['photos'] as $photos) {
                        if($photos!='') {
                            $photosname = $photos->getClientOriginalName();
                            // $photos->move(Photo::getPhotoDirectoryName(), $photosname);
                            $product->photos()->create(['name' => $photosname]);
                        }
                    }
                } 
                if($request->certificates) {
                    $product->certificate_products()->attach($request->certificates);
                }
            
                if($request->style_id) {
                    $styleArr = [];
                    foreach($request->style_id AS $k=> $value) {
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                        ]);
                    }
                    $product->product_category_styles()->saveMany($styleArr);
                }
            
                if($request->pincode) {
                    $product->product_pincodes()->attach($request->pincode);
                }

                
                $specificationData = [];
                if(array_key_exists('specification_type', $input)) {
                    foreach($input['specification_type'] as $key=>$type) {
                        $specificationData[$type] = 
                        [
                            'metal_id'=>$request->metal_id,
                            'value' => $input['specification_type_value'][$key],
                            // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                            'unit'=>$input['specification_type_unit'][$key]
                        ];
                    }
                }
                $product->specificationTypes()->sync($specificationData);
                 

            } else {
               
                $this->validate($request, [
                    'overlay_photo.*' => 'image'
                ]);
                $input['is_approved'] = false;
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                $input["in_stock"] = $request->in_stock;
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('sku', $request->sku)->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
           
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
    
                if(array_key_exists($request->category, $categories)) {
                    $input['category_id'] = $request->category;
                } else {
                    $input['category_id'] = NULL;
                }
                if($request->status == 1) {
                    $input['is_active'] = $request->status;
                } else {
                    $input['is_active'] = 0;
                }
                $location_id = Auth::user()->location_id;
                $input['location_id'] = $location_id;
                if($request->file('photo')) {
                    $photo = $request->file('photo');
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                } else {
                    $input['best_seller'] = false;
                }
                if($request->file('overlay_photo')) {
                    $overlay_photo = $request->file('overlay_photo');
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    $overlay_photo = OverlayPhoto::create(['name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
                $product = new Product($input);
                $product->save();
            
                if(!empty($userInput['photos'])) {
                    foreach($userInput['photos'] as $photos) {
                        if($photos!='') {
                            $photosname = $photos->getClientOriginalName();
                            // $photos->move(Photo::getPhotoDirectoryName(), $photosname);
                            $product->photos()->create(['name' => $photosname]);
                        }
                    }
                } 
                
                    $product->certificate_products()->attach($request->certificates);
                
            
                if($request->style_id) {
                    $styleArr = [];
                    foreach($request->style_id AS $k=> $value) {
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                        ]);
                    }
                    $product->product_category_styles()->saveMany($styleArr);
                }
            
                if($request->pincode) {
                    $product->product_pincodes()->attach($request->pincode);
                }

                
                $specificationData = [];
                if(array_key_exists('specification_type', $input)) {
                    foreach($input['specification_type'] as $key=>$type) {
                        $specificationData[$type] = 
                        [
                            'metal_id'=>$request->metal_id,
                            'value' => $input['specification_type_value'][$key],
                            // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                            'unit'=>$input['specification_type_unit'][$key]
                        ];
                    }
                }
                $product->specificationTypes()->sync($specificationData);
                 
            }
            
        } else {
            $userInput = $request->all();
            
            if($userInput['save_draft_btn'] == 'save_draft') {

                $this->validate($request, [
                    'overlay_photo.*' => 'image'
                ]);
                $input['is_approved'] = true;
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id ? $request->metal_id : null; 
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
                $input["old_price"] = $request->old_price ? $request->old_price : '0';
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                $input["in_stock"] = $request->in_stock;
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                 
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('sku', $request->sku)->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
           
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
    
                if(array_key_exists($request->category, $categories)) {
                    $input['category_id'] = $request->category;
                } else {
                    $input['category_id'] = NULL;
                }
                $input['is_active'] = 0;

                $location_id = Auth::user()->location_id;
                $input['location_id'] = $location_id;
                if($request->file('photo')) {
                    $photo = $request->file('photo');
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                } else {
                    $input['best_seller'] = false;
                }
                if($request->file('overlay_photo')) {
                    $overlay_photo = $request->file('overlay_photo');
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    $overlay_photo = OverlayPhoto::create(['name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
                $product = new Product($input);
                $product->save();
            
                if(!empty($userInput['photos'])) {
                    foreach($userInput['photos'] as $photos) {
                        if($photos!='') {
                            $photosname = $photos->getClientOriginalName();
                            // $photos->move(Photo::getPhotoDirectoryName(), $photosname);
                            $product->photos()->create(['name' => $photosname]);
                        }
                    }
                } 
                if($request->certificates) {
                    $product->certificate_products()->attach($request->certificates);
                }
            
                if($request->style_id) {
                    $styleArr = [];
                    foreach($request->style_id AS $k=> $value) {
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                        ]);
                    }
                    $product->product_category_styles()->saveMany($styleArr);
                }
            
                if($request->pincode) {
                    $product->product_pincodes()->attach($request->pincode);
                }

                
                $specificationData = [];
                if(array_key_exists('specification_type', $input)) {
                    foreach($input['specification_type'] as $key=>$type) {
                        $specificationData[$type] = 
                        [
                            'metal_id'=>$request->metal_id,
                            'value' => $input['specification_type_value'][$key],
                            // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                            'unit'=>$input['specification_type_unit'][$key]
                        ];
                    }
                }
                $product->specificationTypes()->sync($specificationData);
               

            } else {
                 
                $this->validate($request, [
                    'overlay_photo.*' => 'image'
                ]);
                
                $input['is_approved'] = true;
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                $input["in_stock"] = $request->in_stock;
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('sku', $request->sku)->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
                
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
               
                if(array_key_exists($request->category, $categories)) {
                    $input['category_id'] = $request->category;
                } else {
                    $input['category_id'] = NULL;
                }
                if($request->status == 1) {
                    $input['is_active'] = $request->status;
                } else {
                    $input['is_active'] = 0;
                }
                $location_id = Auth::user()->location_id;
                $input['location_id'] = $location_id;
                if($request->file('photo')) {
                    $photo = $request->file('photo');
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                } else {
                    $input['best_seller'] = false;
                }
                if($request->file('overlay_photo')) {
                    $overlay_photo = $request->file('overlay_photo');
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    $overlay_photo = OverlayPhoto::create(['name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
               
                $product = new Product($input);
                
                $product->save();
               
                if(!empty($userInput['photos'])) {
                    foreach($userInput['photos'] as $photos) {
                        if($photos!='') {
                            $photosname = $photos->getClientOriginalName();
                            // $photos->move(Photo::getPhotoDirectoryName(), $photosname);
                            $product->photos()->create(['name' => $photosname]);
                        }
                    }
                } 
               
                    $product->certificate_products()->attach($request->certificates);
                
                    
               
                if($request->style_id) {
                    $styleArr = [];
                    foreach($request->style_id AS $k=> $value) {
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                        ]);
                    }
                     
                    $product->product_category_styles()->saveMany($styleArr);
                    
                }
               
                if($request->pincode) {
                    $product->product_pincodes()->attach($request->pincode);
                }

                
                $specificationData = [];
                if(array_key_exists('specification_type', $input)) {
                    foreach($input['specification_type'] as $key=>$type) {
                        $specificationData[$type] = 
                        [
                            'metal_id'=>$request->metal_id,
                            'value' => $input['specification_type_value'][$key],
                            // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                            'unit'=>$input['specification_type_unit'][$key]
                        ];
                    }
                }
                $product->specificationTypes()->sync($specificationData);
                
            }
        }
        session()->flash('product_created', __("New product has been added."));
        session()->flash('product_view', $product->slug);
        return redirect(route('manage.products.index'));
    }

    public function edit($id)
    {
      
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('update', Product::class) || $vendor) {
            
            $location_id = Auth::user()->location_id;
            
            $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
            $comparision_groups = ComparisionGroup::all();
            $brands = Brand::where('location_id', $location_id)->pluck('name', 'id')->toArray();
            $specification_types = SpecificationType::where('location_id', $location_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
            
            $vendor_prefix = '';
            
            if($vendor) {
                $product = Product::where('location_id', $location_id)->where('id', $id)->firstOrFail();
                $products = Product::select('id', 'name')->whereNotIn('id', [$id])->where('location_id', $location_id)->get();
               
                $sku = $product->sku;
                

                if($sku) {
                    $last_space = strpos($product->sku, '-');
                    $sku = substr($product->sku, $last_space + 1);
                }
                
            } else {
                
                $product = Product::with('specificationTypes')->where('location_id', $location_id)->where('id', $id)->firstOrFail();
                $products = Product::select('id', 'name')->whereNotIn('id', [$id])->where('location_id', $location_id)->get();
                
                $sku = $product->sku;
                

                if($product->vendor) {
                    $vendor_prefix = $product->vendor->id;
                    if($sku) {
                        $last_space = strpos($product->sku, '-');
                        $sku = substr($product->sku, $last_space + 1);
                    }
                   
                }
            }
           
            // $variants = unserialize($product->variants);
            // if(!is_array($variants)) {
            //     $variants = [];
            // }
            
            $certificates = Certificate::pluck('name', 'id')->toArray();
            $selectedcertificates = $product->certificate_products->pluck('id');
            $metals = Metal::where('is_active',1)->pluck('name','id');
            $puirty = MetalPuirty::where('is_active',1)->pluck('name','id');

            $puirtyVal = '';
            $puirtyPlatinumVal ='';
            //dd($product->specificationTypes->toArray());
            if(!empty($product->specificationTypes->toArray())) {

                foreach($product->specificationTypes->toArray() AS $val) {
                    if($val['name'] == 'Purity') {
                        $puirtyVal.= $val['pivot']['value'];
                        $puirtyPlatinumVal.= $val['pivot']['value'];
                    }
                    
                }
            }
            
            
              
            $puirtyVal = str_replace('K','',$puirtyVal);
            $puirtyPlatinumVal = str_replace('KT','',$puirtyPlatinumVal);
            $current_gold_price = Setting::where('key',$puirtyVal.'_CRT')->first();
            
            $current_silver_item_price = Setting::where('key','silver_item_rate')->first();
            $current_silver_jewellery_price = Setting::where('key','silver_jewellery_rate')->first();
            $current_platinum_price = Setting::where('key',$puirtyPlatinumVal.'_KT')->first();
            //dd($puirtyPlatinumVal);
            
            // $silveritems = SilverItem::all();
            $pincodes = ProductPinCode::pluck('name', 'id')->toArray();
            $styles = Category::where('category_id','<>',0)->where('category_id',$product->category_id)->where('is_active',1)->get();
            
            $selectedStyle = ProductCategoryStyle::with('product')->where('product_id',$id)->where('category_id',$product->category_id)->get()->toArray();
            
            return view('manage.products.edit', compact( 'product', 'root_categories', 'brands', 'specification_types', 'products', 'vendor', 'sku',   'vendor_prefix', 'comparision_groups','selectedcertificates','certificates','metals','puirty','current_gold_price','pincodes','styles','selectedStyle','current_silver_item_price','current_silver_jewellery_price','current_platinum_price'));
        } else {
            return view('errors.403');
        }
    }

    public function update(Request $request,ProductRequest $ProductRequest, $id)
    {
       
       
         
        $vendor = Auth::user()->isApprovedVendor();
        $userInput = $request->all();
        $product = Product::findOrFail($id);
        if($vendor) {
           
            if($userInput['save_draft_btn'] == 'save_draft') {
                
               
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["sku"] = $request->sku;
                
                
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;

                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;

                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;

                
                
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;

                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;

                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                $input["in_stock"] = $request->in_stock;
                
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                
                
                $categories = Category::pluck('name','id')->all();
                
                
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('id', '!=', $product->id)->where('sku', $input['sku'])->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }

                $input['brand_id'] = NULL;

                if($request->remove_category) {
                    $input['category_id'] = NULL;
                } else {
                    if(array_key_exists($request->category, $categories)) {
                        $input['category_id'] = $request->category;
                    } else {
                        $input['category_id'] = $product->category_id;
                    }
                }
                
                $input['is_active'] = 0;
                
                if(!$request->file('photo') && $request->remove_photo) {
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                }
    
                if(!$request->file('overlay_photo') && $request->remove_overlay_photo) {
                
                    if($product->OverlayPhoto) {
                        
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                }

                if($photo = $request->file('photo')) {
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }

                if($overlay_photo = $request->file('overlay_photo')) {
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    if($product->OverlayPhoto) {
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                    $overlay_photo = OverlayPhoto::create(['product_id'=>$product->id,'name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }

                if(!$request->file('file') && $request->remove_file) {
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                        $input['file_id'] = NULL;
                        $input['downloadable'] = false;
                        $input['virtual'] = false;
                    }
                }

                if($file = $request->file('file')) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $file->getFilename().time().'.'.$extension;
                    Storage::disk('local')->put($filename,  File::get($file));
                    $entry = new \App\File();
                    $entry->mime = $file->getClientMimeType();
                    $entry->original_filename = $file->getClientOriginalName();
                    $entry->filename = $filename;
                    $entry->save();
                    $input['file_id'] = $entry->id;
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                    }
                }
                
                if($request->remove_images) {
                    
                    foreach($product->photos as $photo) {
                        
                        if(in_array($photo->id, $request->remove_images)) {
                            

                            if(file_exists($photo->getPath())) {
                            
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                    }
                }

                if($request->virtual) {
                    $input['qty_per_order'] = 1;
                    $input['in_stock'] = 999;
                    $input['virtual'] = true;
                }

                if($request->downloadable) {
                    $input['downloadable'] = true;
                }

                if($request->is_new) {
                    $input['is_new'] = true;
                }
                else{
                    $input['is_new'] = false;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                }
                else{
                    $input['best_seller'] = false;
                }
               
                $product->update($input);

                if(array_key_exists('specification_type', $input)) {
                    $specificationData = [];
                    foreach($input['specification_type'] as $key=>$type) {

                        if($input['specification_type_value'][$key]!='') {

                            $specificationData[$type] = 
                            [
                                'metal_id'=>$request->metal_id,
                                'value' => $input['specification_type_value'][$key],
                                'unit'=>$input['specification_type_unit'][$key]
                            ];
                        }
                        
                    }

                    $product->specificationTypes()->sync($specificationData);
                }

                
                
                    $product->certificate_products()->sync($request->certificates);
                

                if($request->style_id) {

                    $styleArr = [];
                    ProductCategoryStyle::where('product_id',$id)->delete();

                    foreach($request->style_id AS $k=> $value) {
                        
                        $styleArr[] =  new ProductCategoryStyle([
                                'category_id' => $userInput['category'],
                                'product_id' => $product->id,
                                'product_style_id' =>  $value,
                                
                            ]);
                            
                    }
                    $product->product_category_styles()->saveMany($styleArr);
                }
                
                if($request->pincode) {
                    $product->product_pincodes()->sync($request->pincode);
                }
                
                if($request->file('photos') && count($request->file('photos')) > 0) {
                    foreach($request->file('photos') as $photo) {
                        $name = 'alt_img_'.time().$photo->getClientOriginalName();
                        $photo->move(Photo::getPhotoDirectoryName(), $name);
                        $product->photos()->create(['name' => $name]);
                    }
                }

                session()->flash('product_updated', __("The product has been saved to draft."));

            } else {
                
                
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
    
                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
    
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
    
                
                
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
    
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
    
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                $input["in_stock"] = $request->in_stock;
                
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                 
    
                
    
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                $comparision_groups = ComparisionGroup::pluck('title','cg_id')->all();
                 
    
                 
    
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('id', '!=', $product->id)->where('sku', $input['sku'])->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
    
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
    
                if($request->remove_category) {
                    $input['category_id'] = NULL;
                } else {
                    if(array_key_exists($request->category, $categories)) {
                        $input['category_id'] = $request->category;
                    } else {
                        $input['category_id'] = $product->category_id;
                    }
                }
    
                 
    
                
    
                if($request->status == 1) {
                    $input['is_active'] = $request->status;
                } else {
                    $input['is_active'] = 0;
                }
                 
    
                
    
                if(!$request->file('photo') && $request->remove_photo) {
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                }
     
                if(!$request->file('overlay_photo') && $request->remove_overlay_photo) {
                  
                    if($product->OverlayPhoto) {
                         
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                }
    
                if($photo = $request->file('photo')) {
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
    
                if($overlay_photo = $request->file('overlay_photo')) {
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    if($product->OverlayPhoto) {
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                    $overlay_photo = OverlayPhoto::create(['product_id'=>$product->id,'name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
    
                if(!$request->file('file') && $request->remove_file) {
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                        $input['file_id'] = NULL;
                        $input['downloadable'] = false;
                        $input['virtual'] = false;
                    }
                }
    
                if($file = $request->file('file')) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $file->getFilename().time().'.'.$extension;
                    Storage::disk('local')->put($filename,  File::get($file));
                    $entry = new \App\File();
                    $entry->mime = $file->getClientMimeType();
                    $entry->original_filename = $file->getClientOriginalName();
                    $entry->filename = $filename;
                    $entry->save();
                    $input['file_id'] = $entry->id;
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                    }
                }
                
                if($request->remove_images) {
                    
                    foreach($product->photos as $photo) {
                         
                        if(in_array($photo->id, $request->remove_images)) {
                              
    
                            if(file_exists($photo->getPath())) {
                              
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                    }
                }
    
                if($request->virtual) {
                    $input['qty_per_order'] = 1;
                    $input['in_stock'] = 999;
                    $input['virtual'] = true;
                }
    
                if($request->downloadable) {
                    $input['downloadable'] = true;
                }
    
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                else{
                    $input['is_new'] = false;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                }
                else{
                    $input['best_seller'] = false;
                }
    
                
                 
                $product->update($input);
    
                if(array_key_exists('specification_type', $input)) {
                    $specificationData = [];
                    foreach($input['specification_type'] as $key=>$type) {
    
                        if($input['specification_type_value'][$key]!='') {
    
                            $specificationData[$type] = 
                            [
                                'metal_id'=>$request->metal_id,
                                'value' => $input['specification_type_value'][$key],
                                // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                                'unit'=>$input['specification_type_unit'][$key]
                            ];
                        }
                        
                    }
    
                    $product->specificationTypes()->sync($specificationData);
                }
               
                
                       
                    $product->certificate_products()->sync($request->certificates);
                
    
                if($request->style_id) {
    
                    $styleArr = [];
                    ProductCategoryStyle::where('product_id',$id)->delete();
                   foreach($request->style_id AS $k=> $value) {
    
                    
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                            
                        ]);
                         
                   }
                    
                   $product->product_category_styles()->saveMany($styleArr);
                    
                }
                if($request->pincode) {
                   
                    $product->product_pincodes()->sync($request->pincode);
                }
                 
                if($request->file('photos') && count($request->file('photos')) > 0) {
                    foreach($request->file('photos') as $photo) {
                        $name = 'alt_img_'.time().$photo->getClientOriginalName();
                        $photo->move(Photo::getPhotoDirectoryName(), $name);
                        $product->photos()->create(['name' => $name]);
                    }
                }
    
                session()->flash('product_updated', __("The product has been update successfully."));
            }
        } else {
            
            if($userInput['save_draft_btn'] == 'save_draft') {
                
                $input['is_approved'] = true;
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["sku"] = $request->sku;
                
                
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;

                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;

                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;

                
                
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;

                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;

                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                $input["in_stock"] = $request->in_stock;
                
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                
                
                $categories = Category::pluck('name','id')->all();
                
                
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('id', '!=', $product->id)->where('sku', $input['sku'])->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }

                $input['brand_id'] = NULL;

                if($request->remove_category) {
                    $input['category_id'] = NULL;
                } else {
                    if(array_key_exists($request->category, $categories)) {
                        $input['category_id'] = $request->category;
                    } else {
                        $input['category_id'] = $product->category_id;
                    }
                }
                
                $input['is_active'] = 0;
                
                if(!$request->file('photo') && $request->remove_photo) {
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                }
    
                if(!$request->file('overlay_photo') && $request->remove_overlay_photo) {
                
                    if($product->OverlayPhoto) {
                        
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                }

                if($photo = $request->file('photo')) {
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }

                if($overlay_photo = $request->file('overlay_photo')) {
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    if($product->OverlayPhoto) {
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                    $overlay_photo = OverlayPhoto::create(['product_id'=>$product->id,'name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }

                if(!$request->file('file') && $request->remove_file) {
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                        $input['file_id'] = NULL;
                        $input['downloadable'] = false;
                        $input['virtual'] = false;
                    }
                }

                if($file = $request->file('file')) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $file->getFilename().time().'.'.$extension;
                    Storage::disk('local')->put($filename,  File::get($file));
                    $entry = new \App\File();
                    $entry->mime = $file->getClientMimeType();
                    $entry->original_filename = $file->getClientOriginalName();
                    $entry->filename = $filename;
                    $entry->save();
                    $input['file_id'] = $entry->id;
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                    }
                }
                
                if($request->remove_images) {
                    
                    foreach($product->photos as $photo) {
                        
                        if(in_array($photo->id, $request->remove_images)) {
                            

                            if(file_exists($photo->getPath())) {
                            
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                    }
                }

                if($request->virtual) {
                    $input['qty_per_order'] = 1;
                    $input['in_stock'] = 999;
                    $input['virtual'] = true;
                }

                if($request->downloadable) {
                    $input['downloadable'] = true;
                }

                if($request->is_new) {
                    $input['is_new'] = true;
                }
                else{
                    $input['is_new'] = false;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                }
                else{
                    $input['best_seller'] = false;
                }
               
                $product->update($input);

                if(array_key_exists('specification_type', $input)) {
                    $specificationData = [];
                    foreach($input['specification_type'] as $key=>$type) {

                        if($input['specification_type_value'][$key]!='') {

                            $specificationData[$type] = 
                            [
                                'metal_id'=>$request->metal_id,
                                'value' => $input['specification_type_value'][$key],
                                'unit'=>$input['specification_type_unit'][$key]
                            ];
                        }
                        
                    }

                    $product->specificationTypes()->sync($specificationData);
                }

                
                
                    $product->certificate_products()->sync($request->certificates);
                

                if($request->style_id) {

                    $styleArr = [];
                    ProductCategoryStyle::where('product_id',$id)->delete();

                    foreach($request->style_id AS $k=> $value) {
                        
                        $styleArr[] =  new ProductCategoryStyle([
                                'category_id' => $userInput['category'],
                                'product_id' => $product->id,
                                'product_style_id' =>  $value,
                                
                            ]);
                            
                    }
                    $product->product_category_styles()->saveMany($styleArr);
                }
                
                if($request->pincode) {
                    $product->product_pincodes()->sync($request->pincode);
                }
                
                if($request->file('photos') && count($request->file('photos')) > 0) {
                    foreach($request->file('photos') as $photo) {
                        $name = 'alt_img_'.time().$photo->getClientOriginalName();
                        $photo->move(Photo::getPhotoDirectoryName(), $name);
                        $product->photos()->create(['name' => $name]);
                    }
                }

                session()->flash('product_updated', __("The product has been saved to draft."));

            } else {

                $input['is_approved'] = true;
                $input['vendor_id'] = isset($vendor->id) ? $vendor->id : null;
                $input['user_id'] = \Auth::user()->id;
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
    
                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
    
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
    
                
                
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
    
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
    
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                $input["in_stock"] = $request->in_stock;
                
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                 
    
                
    
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                $comparision_groups = ComparisionGroup::pluck('title','cg_id')->all();
                 
    
                 
    
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('id', '!=', $product->id)->where('sku', $input['sku'])->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
    
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
    
                if($request->remove_category) {
                    $input['category_id'] = NULL;
                } else {
                    if(array_key_exists($request->category, $categories)) {
                        $input['category_id'] = $request->category;
                    } else {
                        $input['category_id'] = $product->category_id;
                    }
                }
    
                 
    
                
    
                if($request->status == 1) {
                    $input['is_active'] = $request->status;
                } else {
                    $input['is_active'] = 0;
                }
                 
    
                
    
                if(!$request->file('photo') && $request->remove_photo) {
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                }
     
                if(!$request->file('overlay_photo') && $request->remove_overlay_photo) {
                  
                    if($product->OverlayPhoto) {
                         
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                }
    
                if($photo = $request->file('photo')) {
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
    
                if($overlay_photo = $request->file('overlay_photo')) {
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    if($product->OverlayPhoto) {
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                    $overlay_photo = OverlayPhoto::create(['product_id'=>$product->id,'name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
    
                if(!$request->file('file') && $request->remove_file) {
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                        $input['file_id'] = NULL;
                        $input['downloadable'] = false;
                        $input['virtual'] = false;
                    }
                }
    
                if($file = $request->file('file')) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $file->getFilename().time().'.'.$extension;
                    Storage::disk('local')->put($filename,  File::get($file));
                    $entry = new \App\File();
                    $entry->mime = $file->getClientMimeType();
                    $entry->original_filename = $file->getClientOriginalName();
                    $entry->filename = $filename;
                    $entry->save();
                    $input['file_id'] = $entry->id;
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                    }
                }
                
                if($request->remove_images) {
                    
                    foreach($product->photos as $photo) {
                         
                        if(in_array($photo->id, $request->remove_images)) {
                              
    
                            if(file_exists($photo->getPath())) {
                              
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                    }
                }
    
                if($request->virtual) {
                    $input['qty_per_order'] = 1;
                    $input['in_stock'] = 999;
                    $input['virtual'] = true;
                }
    
                if($request->downloadable) {
                    $input['downloadable'] = true;
                }
    
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                else{
                    $input['is_new'] = false;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                }
                else{
                    $input['best_seller'] = false;
                }
    
                
                 
                $product->update($input);
    
                if(array_key_exists('specification_type', $input)) {
                    $specificationData = [];
                    foreach($input['specification_type'] as $key=>$type) {
    
                        if($input['specification_type_value'][$key]!='') {
    
                            $specificationData[$type] = 
                            [
                                'metal_id'=>$request->metal_id,
                                'value' => $input['specification_type_value'][$key],
                                // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                                'unit'=>$input['specification_type_unit'][$key]
                            ];
                        }
                        
                    }
    
                    $product->specificationTypes()->sync($specificationData);
                }
               
                
                       
                    $product->certificate_products()->sync($request->certificates);
                
    
                if($request->style_id) {
    
                    $styleArr = [];
                    ProductCategoryStyle::where('product_id',$id)->delete();
                   foreach($request->style_id AS $k=> $value) {
    
                    
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                            
                        ]);
                         
                   }
                    
                   $product->product_category_styles()->saveMany($styleArr);
                    
                }
                if($request->pincode) {
                   
                    $product->product_pincodes()->sync($request->pincode);
                }
                 
                if($request->file('photos') && count($request->file('photos')) > 0) {
                    foreach($request->file('photos') as $photo) {
                        $name = 'alt_img_'.time().$photo->getClientOriginalName();
                        $photo->move(Photo::getPhotoDirectoryName(), $name);
                        $product->photos()->create(['name' => $name]);
                    }
                }
    
                session()->flash('product_updated', __("The product has been updated successfully."));
            }
        }
        
        return redirect(route('manage.products.edit', $product->id));
    }

    public function destroy($id)
    {
        
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('delete', Product::class) || $vendor) {
            if($vendor) {
                $product = Product::where('vendor_id', $vendor->id)->where('id', $id)->first();
               
            } else {
                $product = Product::findOrFail($id);
            }
            
            
            if($product->photo) {
                if(file_exists($product->photo->getPath())) {
                    unlink($product->photo->getPath());
                    $product->photo()->delete();
                }
            }

            if($product->overlayphoto) {
                if(file_exists($product->overlayphoto->getPath())) {
                    unlink($product->overlayphoto->getPath());
                    $product->OverlayPhoto()->delete();
                }
            }

            if($product->file) {
                Storage::disk('local')->delete($product->file->filename);
                $product->file()->delete();
            }

            foreach($product->photos as $photo) {
                if(file_exists($photo->getPath())) {
                    unlink($photo->getPath());
                    $photo->delete();
                }
            }
            $product->specificationTypes()->detach();
            $product->sales()->delete();
            $product->delete();
            session()->flash('product_deleted', __("The product has been deleted."));
            return redirect(route('manage.products.index'));
        } else {
            return view('errors.403');
        }
    }

    public function deleteProducts(Request $request)
    {
         
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('delete', Product::class) || $vendor) {
            if(isset($request->delete_single)) {
                if($vendor) {
                    $product = Product::where('vendor_id', $vendor->id)->firstOrFail($request->delete_single);
                } else {
                    $product = Product::findOrFail($request->delete_single);
                }
                if($product->orders->count()) {
                    session()->flash('product_not_deleted', __("This product is currently in orders."));
                    return redirect(route('manage.products.index'));
                }
                if($product->photo) {
                    if(file_exists($product->photo->getPath())) {
                        unlink($product->photo->getPath());
                        $product->photo()->delete();
                    }
                }

                if($product->file) {
                    Storage::disk('local')->delete($product->file->filename);
                    $product->file()->delete();
                }

                foreach($product->photos as $photo) {
                    if(file_exists($photo->getPath())) {
                        unlink($photo->getPath());
                        $photo->delete();
                    }
                }
                $product->specificationTypes()->detach();
                $product->sales()->delete();
                $product->delete();
                session()->flash('product_deleted', __("The product has been deleted."));
            } else {
                if(!empty($request->checkboxArray)) {
                    if($vendor) {
                        $vendor_product_ids = $vendor->products()->pluck('id')->toArray();
                        if(!(count(array_intersect($request->checkboxArray, $vendor_product_ids)) == count($request->checkboxArray))) {
                            session()->flash('product_not_deleted', "Invalid product selection.");
                            return redirect(route('manage.products.index'));
                        }
                    }
                    $products = Product::findOrFail($request->checkboxArray);
                    foreach($products as $product) {
                        if($product->orders->count()) {
                            session()->flash('product_not_deleted', __("The products are currently in orders."));
                            return redirect(route('manage.products.index'));
                        }
                    }
                    foreach($products as $product) {
                        if($product->photo) {
                            if(file_exists($product->photo->getPath())) {
                                unlink($product->photo->getPath());
                                $product->photo()->delete();
                            }
                        }

                        if($product->file) {
                            Storage::disk('local')->delete($product->file->filename);
                            $product->file()->delete();
                        }

                        foreach($product->photos as $photo) {
                            if(file_exists($photo->getPath())) {
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                        $product->specificationTypes()->detach();
                        $product->sales()->delete();
                        $product->delete();
                    }
                    session()->flash('product_deleted', __("The selected products have been deleted."));
                } else {
                    // session()->flash('product_not_deleted', __("Please select products to be deleted."));
                }
            }
            return redirect(route('manage.products.index'));
        } else {
            return view('errors.403');
        }
    }

    public function storeMoreImages(Request $request, $id)
    {
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('update', Product::class) || $vendor) {
            $this->validate($request, [
                'file' => 'image'
            ]);

            if($photo = $request->file('file')) {
                if($vendor) {
                    $product = Product::where('vendor_id', $vendor->id)->where('id', $id)->firstOrFail();
                } else {
                    $product = Product::findOrFail($id);
                }
                $name = 'alt_img_'.time().$photo->getClientOriginalName();
                $photo->move(Photo::getPhotoDirectoryName(), $name);
                $product->photos()->create(['name' => $name]);
            }
        } else {
            return view('errors.403');
        }
    }

    public function download($filename)
    {
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('update', Product::class) || $vendor) {

            $file = \App\File::where('filename', $filename)->first();

            if($vendor && $file->product->vendor_id != $vendor->id) {
                return view('errors.403');
            }

            if($file->product->location_id != Auth::user()->location_id) {
                return view('errors.403');
            }

            try {
                $pathToFile = storage_path('app/' . $file->filename);
                $headers = array(
                    'Content-Type' => 'application/pdf',
                );
                return response()->download($pathToFile, $file->original_filename, $headers);
            } catch (\Exception $e) {
                return view('errors.404');
            }
        } else {
            return view('errors.403');
        }
    }

    public function getExistingProduct($id)
    {
        $vendor = Auth::user()->isApprovedVendor();
        if(Auth::user()->can('create', Product::class) || $vendor) {
            if(\Illuminate\Support\Facades\Request::ajax()) {
                $location_id = Auth::user()->location_id;

                if($vendor) {
                    $product = Product::where('location_id', $location_id)->where('vendor_id', $vendor->id)->whereId($id)->first();
                    $products = Product::select('id', 'name')->where('location_id', $location_id)->where('vendor_id', $vendor->id)->get();
                } else {
                    $product = Product::where('location_id', $location_id)->whereId($id)->first();
                    $products = Product::select('id', 'name')->where('location_id', $location_id)->get();
                }

                $sku = $product->sku;
               

                // if($product->vendor) {
                //     if($sku) {
                //         $last_space = strpos($product->sku, '-');
                //         $sku = substr($product->sku, $last_space + 1);
                //     }
                    
                // }

                $variants = unserialize($product->variants);
                if(!is_array($variants)) {
                    $variants = [];
                }

                $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
                $brands = Brand::where('location_id', $location_id)->pluck('name', 'id')->toArray();
                $specification_types = SpecificationType::where('location_id', $location_id)->pluck('name', 'id')->toArray();
                $certificates = Certificate::pluck('name','id');

                return response()->view('partials.manage.existing-product', compact('variants', 'product', 'root_categories', 'brands', 'specification_types', 'products', 'sku', 'certificates'), 200);
            }
        } else {
            return view('errors.403');
        }
    }

    public function getPrority($id) {

        $prority =  MetalPuirty::where('metal_id',$id)->where('is_active',1)->get();
        
          return json_encode($prority);
    }

    public function getProrityRate($key) {
         
      
        $key = str_replace('K','',$key);
        $key = str_replace('k','',$key);
        
        $setting = Setting::where('key', $key)->first();
        return json_encode($setting);
    }
    public function getPlatinumProrityRate($key) {
         
        
        $key = str_replace('KT','',$key);
        $key = str_replace('kt','',$key);
        
        $setting = Setting::where('key', $key.'_KT')->first();
         
        return json_encode($setting);
    }
    public function getSize(Request $request) {
         
        if($request->ajax()) {
             
           $product = Product::with('specificationTypes','category')->where('id',$request->input('product_id'))->first();
          
            if($product->specificationTypes) {
                foreach ($product->specificationTypes as $key => $value) {
                    
                    $specifications[$value->name] = $value->pivot->value;
                    
                }
                 
                $array[] = array('product_id' => $request->input('product_id'),'product_group_default' => $request->input('product_group_default'),'size' => isset($specifications['Size']) && $specifications['Size']!='' ? $specifications['Size'] : '-','purity' => $specifications['Purity'],'price' => $product->total_price);
                
                
                return response()->json($array);
            }
        }
        
    }
    public function saveGroup(Request $request) {
         
        $productID = explode(',',$request->input('product_id'));
        
        if($productID) {

            foreach($productID AS $k=> $value) {
                $product = Product::where('id',$value)->first(); 
                
                $data = array('id'=>$value,'product_group_id' => $request->input('product_id'),'product_group_default' => $request->input('defaultproduct')[$value],'group_by_size' => $request->input('size'),'group_by_purity' => $request->input('purity'),'product_group' => 1);
                $product->update($data);
               //  dump($data);
            }
             //die;
            $json = array('success'=>false,'msg'=>'<p class="alert alert-success">Save successfully</p>');
            return response()->json($json);
        } 
          
        
         
    }
    public function saveGroup1(Request $request) {
        // $jsonArray = array (
        //     array("Volvo",22,18),
        //     array("BMW",15,13),
        //     array("Saab",5,2),
        //     array("Land Rover",17,15)
        //   );
         dd($request->all());
       
        $jsonArray = json_decode($request->input('grouparray'), true);
         
        $data = array();
         
        if($jsonArray) {
            
             foreach($jsonArray AS $k=> $value) {
               if(isset($value['size'])) {
                    $data[] = array('product_id'=>$value['product_id'],'size'=>$value['size'],'total_price'=>$value['total_price']);
               }elseif(isset($value['weight'])) {
                    $data[] = array('product_id'=>$value['product_id'],'weight'=>$value['weight'],'total_price'=>$value['total_price']);
               } else {
                    $data[] = array('product_id'=>$value['product_id'],'length'=>$value['length'],'total_price'=>$value['total_price']);
               }
                
             }

             $sizeGroup = SizeGroup::where('group_name','LIKE',$request->group_name)->first();
             if($sizeGroup) {
                 
                 $arr = json_decode($sizeGroup->group_value, TRUE);
                 $newArr = json_decode($request->grouparray, TRUE);
                 $result = array_merge($arr, $newArr);
                  
                 $productId=array($sizeGroup->product_id);
                 array_push($productId,$request->product_id);
                 
                $sizeGroup->product_id = implode(', ', $productId);
                $sizeGroup->group_name = $request->group_name;
                $sizeGroup->group_value = json_encode($result);
                 
                $sizeGroup->save();
                $json = array('success'=>false,'msg'=>'<p class="alert alert-success">Update successfully</p>');
             }  else {
                 
                $sizeGroup = new SizeGroup;
                $sizeGroup->product_id = $request->product_id;
                $sizeGroup->group_name = $request->group_name;
                $sizeGroup->group_value = json_encode($data);
                if($sizeGroup->save()) {
                    $json = array('success'=>true,'msg'=>'<p class="alert alert-success">Save successfully</p>');
                } else {
                    $json = array('success'=>false,'msg'=>'<p class="alert alert-danger">Some error occured!</p>');
                }
             }
             
             return response()->json($json);
        }
        
    }

    public function getStyleAjax(Request $request) {

        $style = Category::where('category_id',$request->input('id'))->where('is_active',1)->get();
         
        $styleArr = array();
        if($style) {
            foreach($style AS $k=> $value) {
                $styleArr[] = array('id' => $value->id,'name' => $value->name,'image' => $value->image); 
             }
        }
        
        return view('partials.manage.products.styleajax', compact('styleArr'));
    }

    public function getSizeGroup(Request $request) {
        
        //$product = Product::with('specificationTypes','category')->where('id',$request->input('product_id'))->get();
        $product = Product::with('specificationTypes')->whereIn('id',$request->input('product_id'))->get();
        if(!empty($product)) {
            $data = array();
            $productArr = array();
            foreach($product AS $k => $value) {
                foreach($value->specificationTypes AS $key => $val) {
                    $data[$value->name][$value->id][$val->name] = $val->pivot->value;
                   // $data[$value->name][$val->name] =  $val->pivot->value; 
                }
            }
        }
        
        return $data;
    }

    public function getCheckProductJNWEBIDExist(Request $request) {
       
        // $sku = Product::where('jn_web_id',$request->sku)->where('id','!=',$request->id)->count();
        $sku = Product::where('jn_web_id',$request->sku)->where('id','!=',$request->id)->count();
       
        if($sku > 0 ) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function getCheckVendorProductJNWEBIDExist(Request $request) {
       
        // $sku = Product::where('jn_web_id',$request->sku)->where('id','!=',$request->id)->count();
        $sku = Product::where('jn_web_id',$request->sku)->where('id','!=',$request->id)->count();
       
        if($sku > 0 ) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function uploadMultipleImage(Request $request) {

        if ($request->hasFile('file')) {
            
            foreach($request->file('file') as $photos) {

                if($photos!='') {
                    $photosname = $photos->getClientOriginalName();
                    $photos->move(Photo::getPhotoDirectoryName(), $photosname);
                }
                
                 
            }

            $output = array(
                'success'  => '1'
            );
            return response()->json($output);
        }
        
    }

    /* vendor products */
    public function vendorProduct(Request $request) {

        if ($request->ajax()) {
            
            $products = Product::where('location_id', Auth::user()->location_id)->where('is_approved',false);
            
            /* Ordering */
            $products = $products->orderBy('id', 'desc');
                /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $products->count();
            }
                $products = $products->get();
                $i =1; 
                
                return Datatables::of($products)
                    ->addIndexColumn()
                    ->addColumn('Ids', function($row){
                        return '<td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="'.$row->id.'"></td>';
                        
                        
                    })->addColumn('vendor', function($row) {

                       return $row->vendor->name;
                        
                        
                    })
                    ->addColumn('category', function($row){
                        return $row->category->name;
                        
                        
                    })->addColumn('style', function($row) { 
                        $arr = array();
                        $styleArr = array();
                        $style = \App\ProductCategoryStyle::where('category_id',$row->category->id)->where('product_id',$row->id)->get();
                        if(!empty($style)) {
                                foreach($style AS $key => $val) {
                                $styles = \App\Category::where('id',$val->product_style_id)->where('category_id',$row->category->id)->first();
                                array_push($styleArr,$styles->name);
                                }
                        }
                        if(!empty($styleArr)) {
                            return  implode(",",$styleArr);
                        }

                        
                    })->addColumn('photo', function($row) use($products) {

                        if($row->photo) {
                            $image_url = \App\Helpers\Helper::check_image_avatar($row->photo->name, 50);
                            $image = '<img src="'.$image_url.'" height="50px" alt="'.$row->name.'"  />';
                        } else {
                            $image = '<img src="https://via.placeholder.com/50x50?text=No+Image" height="50px" alt="'.$row->name.'" />';
                        }
                                      
                        return $image;
                        
                        
                    })->addColumn('name', function($row){
                        return $row->name;
                    })->addColumn('price', function($row) {
                            if($row->product_discount!='') {
                                $price = number_format($row->new_price);
                            } else {
                                $price = number_format($row->old_price);
                            }
                        return $price;
                    })->addColumn('status', function($row) {
                            if($row->is_active == '1') {
                                $status = '<span class="badge bg-success"><i class="fa fa-check"></i></span>';
                            } else {
                                $status = '<span class="badge bg-danger"><i class="fa fa-times"></i></span>';
                            }
                            
                            
                        return $status;
                    })
                    
                    ->addColumn('approved', function($row) {
                        if($row->is_approved == '1') {
                            $approved = '<span class="badge bg-success"><i class="fa fa-check"></i></span>';
                        } else {
                            $approved = '<span class="badge bg-danger"><i class="fa fa-times"></i></span>';
                        }
                        
                        
                    return $approved;
                
                    })->escapeColumns('status')
                    ->make(true);

        }

        if(Auth::user()->can('read', Product::class) ) {
            
          
            $products = Product::where('location_id', Auth::user()->location_id);
            /* Ordering */
            $products = $products->orderBy('id', 'desc');
            
            /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $products->count();
            }
            $products = $products->get();
           
            
            return view('manage.products.vendor.index', compact('products'));
        } else {
            
            return view('errors.403');
        }

        
    }
    public function vendorProductEdit($id) {
         
        
            
            $location_id = Auth::user()->location_id;
            $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
            $specification_types = SpecificationType::where('location_id', $location_id)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
            $product = Product::with('specificationTypes')->where('location_id', $location_id)->where('id', $id)->firstOrFail();
             
            
            $certificates = Certificate::pluck('name', 'id')->toArray();
            $selectedcertificates = $product->certificate_products->pluck('id');
            $metals = Metal::where('is_active',1)->pluck('name','id');
            $puirty = MetalPuirty::where('is_active',1)->pluck('name','id');

            $puirtyVal = '';
            
            if(!empty($product->specificationTypes->toArray())) {

                foreach($product->specificationTypes->toArray() AS $val) {
                    if($val['name'] == 'Purity') {
                        $puirtyVal.= $val['pivot']['value'];
                    }
                    
                }
            }
            
            
              
            $puirtyVal = str_replace('K','',$puirtyVal);
          
            $current_gold_price = Setting::where('key',$puirtyVal.'_CRT')->first();
            
            $current_silver_item_price = Setting::where('key','silver_item_rate')->first();
            $current_silver_jewellery_price = Setting::where('key','silver_jewellery_rate')->first();
            //dd($current_gold_price);
            
            // $silveritems = SilverItem::all();
            $pincodes = ProductPinCode::pluck('name', 'id')->toArray();
            $styles = Category::where('category_id','<>',0)->where('category_id',$product->category_id)->where('is_active',1)->get();
            
            $selectedStyle = ProductCategoryStyle::with('product')->where('product_id',$id)->where('category_id',$product->category_id)->get()->toArray();
            
            return view('manage.products.vendor.edit', compact( 'product', 'root_categories',  'specification_types',  'selectedcertificates','certificates','metals','puirty','current_gold_price','pincodes','styles','selectedStyle','current_silver_item_price','current_silver_jewellery_price'));
        
        }


        public function updateVendorProduct(Request $request,$id) {
             
            $userInput = $request->all();
            $product = Product::findOrFail($id);
             
            if($userInput['save_draft_btn'] == 'save_draft') {
    
                $input['is_approved'] = $request->is_approved;
                
                $input["sku"] = $request->sku;
                
                
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;

                $input["old_price"] = null;
                $input["new_price"] = null;
                
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;

                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;

                
                
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;

                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;

                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                $input["in_stock"] = $request->in_stock;
                
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                
                
                $categories = Category::pluck('name','id')->all();
                
                
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('id', '!=', $product->id)->where('sku', $input['sku'])->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }

                $input['brand_id'] = NULL;

                if($request->remove_category) {
                    $input['category_id'] = NULL;
                } else {
                    if(array_key_exists($request->category, $categories)) {
                        $input['category_id'] = $request->category;
                    } else {
                        $input['category_id'] = $product->category_id;
                    }
                }
                
                $input['is_active'] = 0;
                
                if(!$request->file('photo') && $request->remove_photo) {
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                }
    
                if(!$request->file('overlay_photo') && $request->remove_overlay_photo) {
                
                    if($product->OverlayPhoto) {
                        
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                }

                if($photo = $request->file('photo')) {
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }

                if($overlay_photo = $request->file('overlay_photo')) {
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    if($product->OverlayPhoto) {
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                    $overlay_photo = OverlayPhoto::create(['product_id'=>$product->id,'name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }

                if(!$request->file('file') && $request->remove_file) {
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                        $input['file_id'] = NULL;
                        $input['downloadable'] = false;
                        $input['virtual'] = false;
                    }
                }

                if($file = $request->file('file')) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $file->getFilename().time().'.'.$extension;
                    Storage::disk('local')->put($filename,  File::get($file));
                    $entry = new \App\File();
                    $entry->mime = $file->getClientMimeType();
                    $entry->original_filename = $file->getClientOriginalName();
                    $entry->filename = $filename;
                    $entry->save();
                    $input['file_id'] = $entry->id;
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                    }
                }
                
                if($request->remove_images) {
                    
                    foreach($product->photos as $photo) {
                        
                        if(in_array($photo->id, $request->remove_images)) {
                            

                            if(file_exists($photo->getPath())) {
                            
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                    }
                }

                if($request->virtual) {
                    $input['qty_per_order'] = 1;
                    $input['in_stock'] = 999;
                    $input['virtual'] = true;
                }

                if($request->downloadable) {
                    $input['downloadable'] = true;
                }

                if($request->is_new) {
                    $input['is_new'] = true;
                }
                else{
                    $input['is_new'] = false;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                }
                else{
                    $input['best_seller'] = false;
                }
               
                $product->update($input);

                if(array_key_exists('specification_type', $input)) {
                    $specificationData = [];
                    foreach($input['specification_type'] as $key=>$type) {

                        if($input['specification_type_value'][$key]!='') {

                            $specificationData[$type] = 
                            [
                                'metal_id'=>$request->metal_id,
                                'value' => $input['specification_type_value'][$key],
                                'unit'=>$input['specification_type_unit'][$key]
                            ];
                        }
                        
                    }

                    $product->specificationTypes()->sync($specificationData);
                }

                
                
                    $product->certificate_products()->sync($request->certificates);
                

                if($request->style_id) {

                    $styleArr = [];
                    ProductCategoryStyle::where('product_id',$id)->delete();

                    foreach($request->style_id AS $k=> $value) {
                        
                        $styleArr[] =  new ProductCategoryStyle([
                                'category_id' => $userInput['category'],
                                'product_id' => $product->id,
                                'product_style_id' =>  $value,
                                
                            ]);
                            
                    }
                    $product->product_category_styles()->saveMany($styleArr);
                }
                
                if($request->pincode) {
                    $product->product_pincodes()->sync($request->pincode);
                }
                
                if($request->file('photos') && count($request->file('photos')) > 0) {
                    foreach($request->file('photos') as $photo) {
                        $name = 'alt_img_'.time().$photo->getClientOriginalName();
                        $photo->move(Photo::getPhotoDirectoryName(), $name);
                        $product->photos()->create(['name' => $name]);
                    }
                }

                session()->flash('product_updated', __("The product has been saved to draft."));

            } else {

                $input['is_approved'] = $request->is_approved;
                
                $input["jn_web_id"] = $request->jn_web_id;
                $input["sku"] = $request->sku;
                $input["name"] = $request->name;
                $input["description"] = $request->description; 
                $input["metal_id"] = $request->metal_id;
                $input["silver_item_id"] = $request->silver_item_id ? $request->silver_item_id : null;
                $input["product_height"] = $request->product_height ? $request->product_height : null;
                $input["product_width"] = $request->product_width ? $request->product_width : null;
                
                $input["product_discount"] = $request->product_discount ? $request->product_discount : null;
                $input["product_discount_on"] = $request->product_discount_on ? $request->product_discount_on : null;
    
                $input["old_price"] = $request->old_price ? $request->old_price : null;
                $input["new_price"] = $request->new_price ? $request->new_price : null;
                
                $input["metal_weight"] = $request->metal_weight ? $request->metal_weight : null;
                $input["stone_weight"] = $request->stone_weight ? $request->stone_weight : null;
                $input["pearls_weight"] = $request->pearls_weight ? $request->pearls_weight : null;
                $input["diamond_weight"] = $request->diamond_weight ? $request->diamond_weight : null;
    
                $input["diamond_wtcarats_earrings"] = $request->diamond_wtcarats_earrings ? $request->diamond_wtcarats_earrings : null;
                $input["diamond_wtcarats_nackless"] = $request->diamond_wtcarats_nackless ? $request->diamond_wtcarats_nackless : null;
    
                
                
                $input["diamond_wtcarats_earrings_price"] = $request->diamond_wtcarats_earrings_price ? $request->diamond_wtcarats_earrings_price : null;
                $input["diamond_wtcarats_nackless_price"] = $request->diamond_wtcarats_nackless_price ? $request->diamond_wtcarats_nackless_price : null;
    
                $input["total_weight"] = $request->total_weight ? $request->total_weight : null;
                $input["price"] = $request->price ? $request->price : null;
                $input["vat_rate"] = $request->vat_rate ? $request->vat_rate : null;
                $input["subtotal"] = $request->subtotal ? $request->subtotal : null;
                $input["gst_three_percent"] = $request->gst_three_percent ? $request->gst_three_percent : null;
                $input["carat_weight"] = $request->carat_weight ? $request->carat_weight : null;
    
                $input["carat_wt_per_diamond"] = $request->carat_wt_per_diamond ? $request->carat_wt_per_diamond : null;
                $input["diamond_price"] = $request->diamond_price ? $request->diamond_price : null;
                $input["diamond_price_one"] = $request->diamond_price_one ? $request->diamond_price_one : null;
                $input["diamond_price_two"] = $request->diamond_price_two ? $request->diamond_price_two : null;
                $input["stone_price"] = $request->stone_price ? $request->stone_price : null; 
                $input["pearls_price"] = $request->pearls_price ? $request->pearls_price : null; 
                $input["watch_price"] = $request->watch_price ? $request->watch_price : null;    
                $input["total_stone_price"] = $request->total_stone_price ? $request->total_stone_price : null;
                $input["carat_price"] = $request->carat_price ? $request->carat_price : null;
                
                $input["per_carate_cost"] = $request->per_carate_cost ? $request->per_carate_cost : null;
                $input["current_price"] = $request->current_price ? $request->current_price : null;
                
                $input["total_price"] = $request->total_price ? $request->total_price : null;
                $input["in_stock"] = $request->in_stock;
                
                $input["qty_per_order"] =  $request->qty_per_order; 
                $input["meta_title"] =  $request->meta_title;  
                $input["meta_desc"] =  $request->meta_desc;
                $input["offer"] = $request->offer;
                $input["meta_keywords"] = $request->meta_keywords;
                
                if($request->specification_type) {
                    $input["specification_type"] = $userInput["specification_type"];
                    $input["specification_type_value"] = $userInput["specification_type_value"];
                    $input["specification_type_unit"] = $userInput["specification_type_unit"];
                }
                 
    
                
    
                $brands = Brand::pluck('name','id')->all();
                $categories = Category::pluck('name','id')->all();
                $comparision_groups = ComparisionGroup::pluck('title','cg_id')->all();
                 
    
                 
    
                if($input['sku']) {
                    $product_exists = Product::select('id')->where('id', '!=', $product->id)->where('sku', $input['sku'])->count();
                    if($product_exists) {
                        return redirect()->back()->withErrors(['sku' => __('SKU already exists.')])->withInput($request->input());
                    }
                }
    
                if(array_key_exists($request->brand, $brands)) {
                    $input['brand_id'] = $request->brand;
                } else {
                    $input['brand_id'] = NULL;
                }
    
                if($request->remove_category) {
                    $input['category_id'] = NULL;
                } else {
                    if(array_key_exists($request->category, $categories)) {
                        $input['category_id'] = $request->category;
                    } else {
                        $input['category_id'] = $product->category_id;
                    }
                }
    
                 
    
                
    
                if($request->status == 1) {
                    $input['is_active'] = $request->status;
                } else {
                    $input['is_active'] = 0;
                }
                 
    
                
    
                if(!$request->file('photo') && $request->remove_photo) {
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                }
     
                if(!$request->file('overlay_photo') && $request->remove_overlay_photo) {
                  
                    if($product->OverlayPhoto) {
                         
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                }
    
                if($photo = $request->file('photo')) {
                    $name = time().$photo->getClientOriginalName();
                    $photo->move(Photo::getPhotoDirectoryName(), $name);
                    if($product->photo) {
                        if(file_exists($product->photo->getPath())) {
                            unlink($product->photo->getPath());
                            $product->photo()->delete();
                        }
                    }
                    $photo = Photo::create(['name'=>$name]);
                    $input['photo_id'] = $photo->id;
                }
    
                if($overlay_photo = $request->file('overlay_photo')) {
                    $name = time().$overlay_photo->getClientOriginalName();
                    $overlay_photo->move(OverlayPhoto::getPhotoDirectoryName(), $name);
                    if($product->OverlayPhoto) {
                        if(file_exists($product->OverlayPhoto->getPath())) {
                            unlink($product->OverlayPhoto->getPath());
                            $product->OverlayPhoto()->delete();
                        }
                    }
                    $overlay_photo = OverlayPhoto::create(['product_id'=>$product->id,'name'=>$name]);
                    $input['overlay_photo_id'] = $overlay_photo->id;
                }
    
                if(!$request->file('file') && $request->remove_file) {
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                        $input['file_id'] = NULL;
                        $input['downloadable'] = false;
                        $input['virtual'] = false;
                    }
                }
    
                if($file = $request->file('file')) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = $file->getFilename().time().'.'.$extension;
                    Storage::disk('local')->put($filename,  File::get($file));
                    $entry = new \App\File();
                    $entry->mime = $file->getClientMimeType();
                    $entry->original_filename = $file->getClientOriginalName();
                    $entry->filename = $filename;
                    $entry->save();
                    $input['file_id'] = $entry->id;
                    if($product->file) {
                        Storage::disk('local')->delete($product->file->filename);
                        $product->file()->delete();
                    }
                }
                
                if($request->remove_images) {
                    
                    foreach($product->photos as $photo) {
                         
                        if(in_array($photo->id, $request->remove_images)) {
                              
    
                            if(file_exists($photo->getPath())) {
                              
                                unlink($photo->getPath());
                                $photo->delete();
                            }
                        }
                    }
                }
    
                if($request->virtual) {
                    $input['qty_per_order'] = 1;
                    $input['in_stock'] = 999;
                    $input['virtual'] = true;
                }
    
                if($request->downloadable) {
                    $input['downloadable'] = true;
                }
    
                if($request->is_new) {
                    $input['is_new'] = true;
                }
                else{
                    $input['is_new'] = false;
                }
                if($request->best_seller) {
                    $input['best_seller'] = true;
                }
                else{
                    $input['best_seller'] = false;
                }
    
                
                 
                $product->update($input);
    
                if(array_key_exists('specification_type', $input)) {
                    $specificationData = [];
                    foreach($input['specification_type'] as $key=>$type) {
    
                        if($input['specification_type_value'][$key]!='') {
    
                            $specificationData[$type] = 
                            [
                                'metal_id'=>$request->metal_id,
                                'value' => $input['specification_type_value'][$key],
                                // 'value'=>str_replace(' ', '-', strtolower($input['specification_type_value'][$key])),
                                'unit'=>$input['specification_type_unit'][$key]
                            ];
                        }
                        
                    }
    
                    $product->specificationTypes()->sync($specificationData);
                }
               
                
                       
                    $product->certificate_products()->sync($request->certificates);
                
    
                if($request->style_id) {
    
                    $styleArr = [];
                    ProductCategoryStyle::where('product_id',$id)->delete();
                   foreach($request->style_id AS $k=> $value) {
    
                    
                        $styleArr[] =  new ProductCategoryStyle([
                            'category_id' => $userInput['category'],
                            'product_id' => $product->id,
                            'product_style_id' =>  $value,
                            
                        ]);
                         
                   }
                    
                   $product->product_category_styles()->saveMany($styleArr);
                    
                }
                if($request->pincode) {
                   
                    $product->product_pincodes()->sync($request->pincode);
                }
                 
                if($request->file('photos') && count($request->file('photos')) > 0) {
                    foreach($request->file('photos') as $photo) {
                        $name = 'alt_img_'.time().$photo->getClientOriginalName();
                        $photo->move(Photo::getPhotoDirectoryName(), $name);
                        $product->photos()->create(['name' => $name]);
                    }
                }
    
                session()->flash('product_updated', __("The product has been update successfully.")); 
            }
            
            return redirect(route('manage.products.vendor_product'));
        }

        public function deleteVendorProduct($id) {
             
            $product = Product::findOrFail($id);
            
            if($product->photo) {
                    if(file_exists($product->photo->getPath())) {
                        unlink($product->photo->getPath());
                        $product->photo()->delete();
                    }
                }
    
                if($product->overlayphoto) {
                    if(file_exists($product->overlayphoto->getPath())) {
                        unlink($product->overlayphoto->getPath());
                        $product->OverlayPhoto()->delete();
                    }
                }
    
                if($product->file) {
                    Storage::disk('local')->delete($product->file->filename);
                    $product->file()->delete();
                }
    
                foreach($product->photos as $photo) {
                    if(file_exists($photo->getPath())) {
                        unlink($photo->getPath());
                        $photo->delete();
                    }
                }
                $product->specificationTypes()->detach();
                $product->sales()->delete();
                $product->delete();
                session()->flash('product_deleted', __("The product has been deleted."));
                return redirect(route('manage.products.vendor_product'));
            
        }

        public function approvedAllProducts(Request $request) {
       
            if(!empty($request->checkboxArray)) {
                
                $products = Product::findOrFail($request->checkboxArray);
                if(count($products) > 0) {
                    $data = array();
                    foreach($products AS $value) {
                        $data['is_approved'] = true;
                        $value->update($data);
                    }
                }
                session()->flash('product_deleted', __("The selected products have been approved."));
                return redirect(route('manage.products.vendor_product'));
            } else {
                return redirect(route('manage.products.vendor_product'));
            }
            
               
        }
    
    /* vendor products */
}
