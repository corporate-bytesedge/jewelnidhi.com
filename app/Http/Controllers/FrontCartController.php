<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Country;

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
}
