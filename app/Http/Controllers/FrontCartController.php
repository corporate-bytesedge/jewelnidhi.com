<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Country;
use App\Category;
use App\ProductStyle;
use App\SpecificationType;
use Illuminate\Support\Facades\Route;

class FrontCartController extends Controller
{
    public function index()
    {
       
        $cartItems = Cart::content();
        
        //dd($cartItems);
        return view('front.cart', compact('cartItems'));
    }

    public function ajaxCartData(){
        $cartItems = Cart::content();
        return view('includes.ajax-pages.cart_modal',compact('cartItems'));
//        return response()->json($cartItems);
    }

    public function refreshCartPage(){
        $cartItems = Cart::content();
        return view('partials.front.includes.cart',compact('cartItems'));
    }

     

    public function add($id, Request $request)
    {
      
        
        if($request->input('buynow_button')=='buynow') {
            session()->forget('totalAmount');
            //Cart::destroy();
            if(\Auth::user() && \Auth::user()->verified == 1 && \Auth::user()->is_active == 1) {
                if(!$request->quantity) {
                    $request->quantity = 1;
                }
                $this->validate($request, [
                    'quantity' => 'integer|min:1'
                ]);

                $product = Product::findOrFail($id);

                if($product->in_stock < 1) {
                    session()->flash('product_not_added', __('This product is out of stock.'));
                    session()->forget('product_added');
                    return response()->view('partials.front.cart-message', ['cart_count'=>Cart::content()->count()], 200);
                }
                
                $old_location_id = session('location_id');
            
                session(['location'=>$product->location->name, 'location_id'=>$product->location_id,'singlevendor'=>$product->user_id]);
                
                if($old_location_id != $product->location_id) {
                    Cart::destroy();
                }

                $cartItems = Cart::content();
                $productsInCart = $cartItems->filter(function($cartItem) use($id) {
                    return $cartItem->id == $id;
                });

                if($productsInCart->isNotEmpty()) {
                    $productInCart = $productsInCart->first();
                     if($productInCart->qty >= $product->in_stock) {
                        session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                        session()->forget('product_added');
                        return \Redirect::back()->with('msg', 'Maximum quantity allowed for this product exceeded.');
                    }
                     if($request->quantity > 1) {
                        
                        if($request->quantity + $productInCart->qty > $product->qty_per_order) {
                            session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                            session()->forget('product_added');
                            return \Redirect::back()->with('msg', 'Maximum quantity allowed for this product exceeded.');
                            
                        }
                    }
                } else {
                    
                    if($request->quantity > 1) {
                        if($request->quantity > $product->qty_per_order) {
                            session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                            session()->forget('product_added');
                            return \Redirect::back()->with('msg', 'Maximum quantity allowed for this product exceeded.');
                            
                        }
                    }
                }

                $variants = unserialize($product->variants);
                //$price_with_tax_and_discount = $product->price_with_tax_and_discount();
                
                $price_with_tax_and_discount = isset($request->new_price) ? $request->new_price : $product->new_price;
                //$price_with_discount = $product->price_with_discount();
                $price_with_discount = isset($request->new_price) ? $request->new_price : $product->new_price;
             
                $specArray = [];
                session()->put('totalAmount',$price_with_tax_and_discount);
                Cart::add($product->id,  $product->name, $request->quantity, $price_with_tax_and_discount, ['photo' => $product->photo ? $product->photo->getOriginal('name') : '','vendor_id' => isset($product->vendor_id) ? $product->vendor_id : '', 'unit_price' => $price_with_discount, 'unit_tax' => $product->tax_rate, 'qty_per_order' => ($product->qty_per_order > $product->in_stock) ? $product->in_stock : $product->qty_per_order, 'slug' => $product->slug, 'gst' => $product->gst_three_percent, 'va' => $product->vat_rate, 'spec' => $specArray]);
                $user = \Auth::user();
                $addresses = $user->customers()->get();
                $countries = Country::getAllActiveCountries();
            
                

                if(count($cartItems) > 0 ) {
                    $productIdArr = array();
                    foreach($cartItems AS $cartItem) {
                        if($cartItem->options->vendor_id == '0') {
                            $cartItem->options->vendor_id = '';
                        }
                        array_push($productIdArr,$cartItem->options->vendor_id);
                        
                        session(['vendor_id'=>$productIdArr]);
                    }
                }
                
                
            
                
                return redirect(route('checkout.shipping'));

            }
        }
          
        if(\Illuminate\Support\Facades\Request::ajax()) {
            
            if(!$request->quantity) {
                $request->quantity = 1;
            }
            $this->validate($request, [
                'quantity' => 'integer|min:1'
            ]);
              
            $product = Product::findOrFail($id);
            
            if($product->in_stock < 1) {
                session()->flash('product_not_added', __('This product is out of stock.'));
                session()->forget('product_added');
                return response()->view('partials.front.cart-message', ['cart_count'=>Cart::content()->count()], 200);
            }
             
            
            $old_location_id = session('location_id');
            session(['location'=>$product->location->name, 'location_id'=>$product->location_id,'singlevendor'=>$product->user_id]);
            if($old_location_id != $product->location_id) {
                Cart::destroy();
            }

            $cartItems = Cart::content();
            
            $productsInCart = $cartItems->filter(function($cartItem) use($id) {
                return $cartItem->id == $id;
            });
            
            if($productsInCart->isNotEmpty()) {
                $productInCart = $productsInCart->first();
               //echo $productInCart->qty .' >= '. $product->qty_per_order;
                
                if($productInCart->qty >= $product->in_stock) {
                     
                    session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                    session()->forget('product_added');
                    return response()->view('partials.front.cart-message', ['cart_count'=>Cart::content()->count()], 200);
                }
    			if($request->quantity > 1) {
    				if($request->quantity + $productInCart->qty > $product->qty_per_order) {
    					session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                        session()->forget('product_added');
    					return response()->view('partials.front.cart-message', ['cart_count'=>Cart::content()->count()], 200);
    				}
    			}
            } else {
    			if($request->quantity > 1) {
    				if($request->quantity > $product->qty_per_order) {
    					session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                        session()->forget('product_added');
    					return response()->view('partials.front.cart-message', ['cart_count'=>Cart::content()->count()], 200);
    				}
    			}
    		}
            
            $variants = unserialize($product->variants);
            //$price_with_tax_and_discount = $product->price_with_tax_and_discount();
            
            $price_with_tax_and_discount = isset($request->new_price) ? $request->new_price : $product->new_price;
            //$price_with_discount = $product->price_with_discount();
            $price_with_discount = isset($request->new_price) ? $request->new_price : $product->new_price;
                
            $specArray = [];
           // dd('====='.$request->quantity);
             
            
           
            
             Cart::add($product->id,  $product->name, $request->quantity, $price_with_tax_and_discount, ['photo' => $product->photo ? $product->photo->getOriginal('name') : '','vendor_id' => isset($product->vendor_id) ? $product->vendor_id : '', 'unit_price' => $price_with_discount, 'unit_tax' => $product->tax_rate, 'qty_per_order' => ($product->qty_per_order > $product->in_stock) ? $product->in_stock : $product->qty_per_order, 'slug' => $product->slug, 'gst' => $product->gst_three_percent, 'va' => $product->vat_rate, 'spec' => $specArray]);
            session()->flash('product_added', __(':product_name has been added to cart.', ['product_name'=>$product->name]));
            session()->forget('product_not_added');
            
            $this->clearCoupon();
            
            return response()->view('partials.front.cart-message', ['cart_count'=>Cart::content()->count(), 'product_added'=>true], 200);
        } else {
            return redirect()->route('front.cart.index');
            // return redirect()->route('checkout.shipping');
        }
        
         
    }

    public function update(Request $request)
    {
        
         
        if(!empty($request->rowid)) {
            foreach($request->rowid AS $k => $value) {
                
                Cart::update($value, $request['qty'][$k]);
            }
        }
        
        //$cart = Cart::get($id);
        
        
        // if($request->submit == 'increase') {
        //     if($cart->qty >= $cart->options->qty_per_order) {
        //         session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
        //         return back();
        //     }            
        //     Cart::update($id, ++$quantity);
        // } elseif($request->submit == 'decrease') {
        //     Cart::update($id, --$quantity);
        // }
        $this->clearCoupon();
        return redirect()->back()->with('msg', 'Cart update successfully');   
    }

    public function updateAjax(Request $request, $id, $quantity)
        {
    //        print_r($quantity);exit;
            $cart = Cart::get($id);
            if($request->submit == 'increase') {
                if($cart->qty >= $cart->options->qty_per_order) {
                    session()->flash('product_not_added', __('Maximum quantity allowed for this product exceeded.'));
                    return back();
                }
                Cart::update($id, $cart->qty + $quantity);
            } elseif($request->submit == 'decrease') {
                Cart::update($id, $cart->qty - $quantity);
            }
            $this->clearCoupon();
            return back();
        }

    public function destroy($id)
    {
        
        $this->clearCoupon();
        Cart::remove($id);
        return redirect()->back()->with('msg', 'Product delete successfully'); 
    }

    public function emptyCart()
    {
        $this->clearCoupon();
        Cart::destroy();
        return back();
    }

    public function cartCount(){
        $cart_data = array(
            'cart_item_count'  =>  Cart::content()->count(),
            'cart_item_total'  =>  currency_format(Cart::total())
        );
        return json_encode($cart_data);
    }

    private function clearCoupon()
    {
        session()->forget('coupon_amount');
        session()->forget('coupon_valid_above_amount');
        session()->forget('coupon_code');
    }
	
	public function becategory(Request $request,$slug)
    {
       
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
        return view('front.becategory', compact('products','max_page', 'category', 'product_max_price', 'banners_main_slider', 'banners_below_filters', 'banners_below_main_slider', 'banners_below_main_slider_2_images_layout', 'banners_below_main_slider_3_images_layout', 'sections_above_main_slider', 'sections_below_main_slider', 'sections_above_side_banners', 'sections_below_side_banners', 'sections_above_footer','metal','maleProduct','femaleProduct','uniSexProduct','purity_eighteen_carat','purity_fourteen_carat','purity_twenty_two_carat','purity_twenty_four_carat','offerProduct','plain','stone','beads'));
    }
	
	public function beajaxSortingPrice(Request $request) {
       
        
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
		$view = view('front.beajaxfiltersearch',compact('products'))->render();
		//if($request->input('page') != '') {
		
		
        return response()->json(['html'=>$view, 'max_page'=>$max_page]);
		//}else{
		
        //return view('front.beajaxfiltersearch',compact('products'));    
        //}
    }
}
