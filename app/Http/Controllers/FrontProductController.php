<?php

namespace App\Http\Controllers;

use App\ComparisionGroup;
use App\Product;
use App\Section;
use Illuminate\Http\Request;
use App\Category;
use App\SizeGroup;
use App\ProductStyle;
use App\Certificate;
use App\Enquiry;
use App\ProductCategoryStyle;

class FrontProductController extends Controller
{
    public function show($slug) {
		 //\DB::connection()->enableQueryLog();
        $product = Product::with('specificationTypes','product_category_styles','metal','certificate_products','category.categories','silverItem')->where('slug', $slug)->where('is_active', 1)->firstOrFail();
		 
		 
		  
		$styleid = ProductCategoryStyle::where('product_id',$product->id)->where('category_id',$product->category_id)->first();
		 
		$category = Category::with('category','products')->where('id', $styleid->product_style_id)->where('is_active', 1)->firstOrFail();
		
        $variants = unserialize($product->variants);
        if(!is_array($variants)) {
            $variants = [];
        }

		// if(!(($product->related_products()->where('is_active', 1)->count()) > 0)) {
		// 	$related_products_category = Product::whereHas('category', function($query) use($product) {
		// 		$query->where('id', $product->category_id);
		// 	})->whereNotIn('id', [$product->id])->where('is_active', 1)->limit(3)->get();

		// 	$related_products_brand = Product::whereHas('brand', function($query) use($product) {
		// 		$query->where('id', $product->brand_id);
		// 	})->whereNotIn('id', [$product->id])->where('is_active', 1)->limit(3)->get();

		// 	$related_products_category_brand = $related_products_category->merge($related_products_brand);
		// } else {
		// 	$related_products_category_brand = NULL;
		// }
        // $comparision_products = $comparision_group_types = '';
		// if ( $product->comp_group_id ){
        //     $comparision_products_count = $product->where('comp_group_id', $product->comp_group_id)->count();
		//     if ( $comparision_products_count > 1 ){
        //         $comparision_products = $product->where('comp_group_id', $product->comp_group_id)->limit(3)->get();
        //         $comparision_group_types = ComparisionGroup::where('cg_id',$product->comp_group_id)->first();
        //     }
        // }

        $location_id = 1;
        $sections = Section::orderBy('priority', 'asc')->where('is_active', 1)->where('location_id', $location_id)->get();
        $sections_above_deal_slider = $sections->filter(function($section) {
            return $section->position == 'Above Deal Slider';
        });
        $sections_below_deal_slider = $sections->filter(function($section) {
            return $section->position == 'Below Deal Slider';
        });
        $sections_above_footer = $sections->filter(function($section) {
            return $section->position == 'Above Footer';
		});
		$productGroupID = explode(',',$product->product_group_id);
		$productGroupBy = array($product->group_by);
		
		$groupSizeArr = array();
		$groupPurityArr = array();
		if(!empty($productGroupID)) {

			foreach($productGroupID AS $k => $value) {
				  
				$groupSizeArr[] = Product::with('specificationTypes')->where('id',$value)->where('group_by_size',10)->first();
			}
		}
		
		if(!empty($productGroupID)) {
			foreach($productGroupID AS $k => $value) {
				  
				$groupPurityArr[] = Product::with('specificationTypes')->where('id',$value)->where('group_by_purity',9)->distinct()->first();
				  
			}
		}
		
		 
		
		 
		// if(!empty($group)) {
			
		// 	$groupArr[] = json_decode($group->group_value,true);
			 
			 
		// }
		$categoryID = $product->category_id;
		$minWeight = Product::where('category_id',$categoryID)->min('total_weight'); 
		$maxWeight = Product::where('category_id',$categoryID)->max('total_weight');
		$minPrice = Product::where('category_id',$categoryID)->min('new_price'); 
		$maxPrice = Product::where('category_id',$categoryID)->max('new_price');
		$totalWeight = explode('.',$product->total_weight);
		$totWeight = $totalWeight[0];
		 
		//$similarProduct = Product::where('total_weight', 'LIKE', $totWeight.'%')->whereBetween('new_price',[$minPrice, $maxPrice])->get();;
		  $productPrice = $product->product_discount != '' ? $product->new_price : $product->old_price ;
		  $minProductPrice = $productPrice ? $productPrice - 5000 : 1 ;
		  $maxProductPrice = $productPrice ? $productPrice + 5000 : '' ;
		
		/* $SimilarProductID = Product::whereHas('product_category_styles', function($query) use($product) {
			$query->where('category_id', $product->category_id)->where('product_style_id', $product->product_category_styles[0]->product_style_id);
		})->whereNotIn('id', [$product->id])->whereRaw('(`product_discount` is null  && `old_price` >= '.$minProductPrice.' && `old_price` <= '.$maxProductPrice.' ) or (`product_discount` is not null  && `new_price` >= '.$minProductPrice.' &&  `new_price` <= '.$maxProductPrice.')')->where('category_id', $product->category_id)->where('is_active', 1)->get();

		$productIDArr = [];
		if(!empty($SimilarProductID)) {		

			foreach($SimilarProductID AS $value) {
				$productIDArr[]= $value->id;
			}
		}
		 

		$similarProduct = Product::whereHas('product_category_styles', function($query) use($product,$productIDArr) {
			$query->where('category_id', $product->category_id)->where('product_style_id', $product->product_category_styles[0]->product_style_id)->whereIn('product_id',$productIDArr);
		})->whereNotIn('id', [$product->id])->where('is_active', 1)->get(); */
		
		
		
		
		/* $similarProduct = Product::whereHas('product_category_styles', function($query) use($product) {
			$query->where('category_id', $product->category_id)->where('product_style_id', $product->product_category_styles[0]->product_style_id);
		})->whereNotIn('id', [$product->id])->whereRaw('(`product_discount` is null  && `old_price` >= '.$minProductPrice.' && `old_price` <= '.$maxProductPrice.' ) or (`product_discount` is not null  && `new_price` >= '.$minProductPrice.' &&  `new_price` <= '.$maxProductPrice.')')->where('category_id', $product->category_id)->where('is_active', 1)->get(); */
		
		
		$similarProduct =  Product::select('products.*')
				->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
				->leftjoin('products_saleprice', 'products_saleprice.product_id', '=', 'products.id')
				->where('product_category_styles.category_id', $product->category_id)
				->where('product_category_styles.product_style_id', $product->product_category_styles[0]->product_style_id)
				->where('products.is_active','1')->whereNotIn('products.id', [$product->id])
				->whereRaw('products_saleprice.saleprice >= '.$minProductPrice.' && products_saleprice.saleprice <= '.$maxProductPrice.' ')
				->where('products.category_id',$product->category_id)->get();
		  
		
		  //$debugQry = \DB::getQueryLog();
		//print_r($debugQry);
		
		return view('front.product', compact('product', 'variants',   'sections_above_deal_slider', 'sections_below_deal_slider', 'sections_above_footer','groupSizeArr','groupPurityArr','similarProduct','category'));
	}

	public function store(Request $request, Product $product) {
		 
		if(!$product->is_active) {
			return view('errors.404');
		}
		 
		$request->session()->put('user_id', \Auth::user()->id);
		
		$request->user()->favouriteProducts()->syncWithoutDetaching([$product->id]);
		session()->flash('wishlist_msg', __("Product has been added to your wishlist"));
		return back();
	}

	public function wishlist(Request $request) {
		$products = $request->user()->favouriteProducts()->paginate(9);
		 
		return view('front.wishlist', compact('products'));
	}

	public function destroy(Request $request, Product $product) {
		 
		$request->user()->favouriteProducts()->detach($product);
		return back();
	}

	public function destroyWishlist(Request $request) {
		$product = Product::where('id',$request->id)->first();
		$request->user()->favouriteProducts()->detach($product);
		return back();
		 
	}

	

	public function getVariantData(Request $request, $product_id, $variant_keys, $value_keys) {
		if($product_id) {
			$product = Product::select('id', 'old_price', 'price', 'variants')->findOrFail($product_id);

			if($product->price_with_discount() < $product->price) {
				$product_price = $product->price_with_discount();
			} else {
				if($product->old_price && ($product->price < $product->old_price)) {
					$product_price = $product->price_with_discount();
				} else {
					$product_price = $product->price;
				}
			}

		 	$variants = unserialize($product->variants);

			if(is_array($variants) && count($variants)) {

				$variant_keys = explode(',', $variant_keys);
				$value_keys = explode(',', $value_keys);

				if(is_array($variant_keys) && is_array($value_keys) && count($variant_keys) == count($value_keys)) {

					$price_to_add_array = [];

					foreach($variant_keys as $key => $variant_key) {
						if(isset($variants[$variant_key]) && isset($variants[$variant_key]['v'][$value_keys[$key]])) {
							$price_to_add = $variants[$variant_key]['v'][$value_keys[$key]]['p'];
							array_push($price_to_add_array, $price_to_add);
						}
					}

					if(count($price_to_add_array)) {
						foreach($price_to_add_array as $price_to_add) {
							$product_price += $price_to_add;
						}

						return array('success' => true, 'data' => currency_format($product_price)); 
					}

				}
			}
		}

		return array('success' => false);
	}

	public function getProductPrice(Request $request) {
		$product_id = $request->input('product_id');
		$group_val = $request->input('group_val');
		 
		$product = Product::with('specificationTypes')->findOrFail($product_id);
		 

		 
		if($product) {
			 
			$priceArr = [];
			$priceArr['name'] = isset($product->name) ? $product->name : '0';
			if(isset($product->product_discount) && $product->product_discount !='') {
				$priceArr['price'] = isset($product->new_price) ? $product->new_price : '0';
			} else 
			{
				$priceArr['price'] = isset($product->old_price) ? $product->old_price : '0';
			}
			
			 
			$priceArr['product_discount'] = isset($product->product_discount) ? $product->product_discount : '';	 
			  
			$priceArr['discount'] = $product->product_discount .'% '.$product->product_discount_on ;

			if(isset($product->product_discount) && $product->product_discount !='') {
				$priceArr['new_price'] = isset($product->new_price) ? '₹ '. \App\Helpers\IndianCurrencyHelper::IND_money_format($product->new_price): '';
				$priceArr['old_price'] = $product->old_price  ? '₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price): '0';
			} else {
				$priceArr['old_price'] = $product->old_price  ? '₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price): '0';
			}
			
			// $priceArr['sku'] = $product->sku;
			// $priceArr['total_price'] = currency_format(number_format($product->total_price));
			// $priceArr['product_height'] = isset($product->product_height) ? $product->product_height : '-';
			// $priceArr['product_width'] = isset($product->product_width) ? $product->product_width : '-';
			// $priceArr['metal_weight'] = isset($product->metal_weight) ? $product->metal_weight  : '-';
			// $priceArr['diamond_weight'] = isset($product->diamond_weight) ? $product->diamond_weight : '-';
			// $priceArr['stone_weight'] = isset($product->stone_weight) ? $product->stone_weight : '-';
			// $priceArr['pearls_weight'] = isset($product->pearls_weight) ? $product->pearls_weight : '-';

			// $priceArr['total_stone_price'] = isset($product->total_stone_price) ? currency_format(number_format($product->total_stone_price)) : '0';

			// $priceArr['total_price'] = currency_format(number_format($product->new_price));
			// $priceArr['total_weight'] = isset($product->total_weight) ? $product->total_weight : '-';
			// $priceArr['gst_three_percent'] = isset($product->gst_three_percent) ? currency_format(number_format($product->gst_three_percent)) : '-';
			// $priceArr['vat_rate'] =  isset($product->metal_weight) ? currency_format(number_format($product->vat_rate)) : '-';
			 
			if($product->specificationTypes) {
				 $metalInformation = [];
				 $weightDetail = [];
				 $priceBreakup = [];
				foreach($product->specificationTypes AS $k => $value) {
						if($value->id =='9' || $value->id =='21' || $value->id=='11' || $value->id=='28' || $value->id=='12' || $value->id=='13' || $value->id=='10' || $value->id =='25' || $value->id =='41' || $value->id =='29' || $value->id =='37' || $value->id =='39' || $value->id =='53' || $value->id =='55' || $value->id =='35' || $value->id =='30' || $value->id =='31'  || $value->id =='32'  || $value->id =='34'  ) {
							$metalInformation[str_replace(' ', '-', $value->name)] = $value->pivot->value.' '.$value->pivot->unit;
						  }	 else if($value->id =='14' || $value->id =='15' || $value->id =='23'  || $value->id =='38' || $value->id=='27' || $value->id == '40' || $value->id == '52' || $value->id == '55' || $value->id == '57' ) {
							$weightDetail[str_replace(' ', '-', $value->name)] =  $value->pivot->value.' '.$value->pivot->unit;
						  } else if($value->id =='45' || $value->id =='60' || $value->id =='46' || $value->id =='36' || $value->id =='24' || $value->id =='59' ||  $value->id =='62' || $value->id =='63'  || $value->id =='33') {
							$priceBreakup[str_replace(' ', '-', $value->name)] =  $value->pivot->value.' '.$value->pivot->unit;
						  }
						//$specificationTypesArr[str_replace(' ', '-', $value->name)] = $value->pivot->value;
				}
				 
			}
			$metalInfo = ''; 
			if(!empty($metalInformation)) {
				$metalInfo.='<div class="table-responsive" ><h6> Metal Information </h6><table class="table table-bordered detail ">';
				$metalInfo.='<tr> <td>JN Web ID</td><td>'.$product->jn_web_id.'</td></tr>';
				$metalInfo.='<tr> <td>Metal</td><td>'.strtoupper($product->metal->name).'</td></tr>';
				foreach($metalInformation AS $k => $val) {
					$metalInfo.='<tr>
									<td>'.$k.'</td>
									<td>'.strtoupper($val).'</td>
								  </tr>
								  ';
				}
				$metalInfo.= '</table></div>';
			}

			if(!empty($weightDetail)) {
				$metalInfo.='<div class="table-responsive" ><h6> Weight Details </h6><table class="table table-bordered detail ">';
				foreach($weightDetail AS $k => $val) {
					
					$metalInfo.='<tr> <td>'.$k.'</td><td>'.strtoupper($val).'  </td></tr>';
				}
				$metalInfo.= '</table></div>';
			}
			$metalInfo.='<div class="table-responsive" ><h6>Price Breakup</h6><table class="table table-bordered detail ">';

			$metalInfo.='<tr> <td>Gold Price</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->price).'</td></tr>';
			if(!empty($priceBreakup)) {
				
				
				$metalInfo.='<tr> <td>Diamond Price</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->diamond_price) .'</td></tr>';
				foreach($priceBreakup AS $k => $val) {
					
					$metalInfo.='<tr> <td>'.$k.'</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($val) .'</td></tr>';
				}
				
			}
			 
			$metalInfo.='<tr> <td>VA</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->vat_rate).'</td></tr>';
			$metalInfo.='<tr> <td>GST</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->gst_three_percent).'</td></tr>';
			if($product->product_discount!='') {
				$subTotal = $product->subtotal + $product->gst_three_percent;
				$metalInfo.='<tr> <td>Subtotal</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal).'</td></tr><tr> <td>Discount</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price - $product->new_price).'</td></tr><tr> <td>Total</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->new_price).'</td></tr>';
			} else {
				$metalInfo.='<tr> <td>Total</td><td>₹ '.\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price).'</td></tr>';
			}
			
			
			$metalInfo.= '</table></div>';
			//$array = array_merge($priceArr,$metalInformation,$weightDetail,$priceBreakup);
		 
			return \Response::json(array(
				 
				'priceArr' => $priceArr,
				'metalInfo' => $metalInfo,
			));
			  
			// return response()->json($metalInfo);
		}
		
	}

	public function sendEnquiry(Request $request) {

		if($request->ajax()) {
			 
			// $enquiries = Enquiry::where('email',$request->email)->where('product_id',$request->product_id)->first();
			
			$enquiry = Enquiry::create($request->all());
			if($enquiry) {
				$data = array('status' => '1','msg'=>'<p class ="alert alert-success" style="font-size:12px;text-transform:none;">Your enquiry has been submitted successfully</p>');
			} else {
				$data = array('status' => '2','msg'=>'<p  style="font-size:12px;text-transform:none;" class ="alert alert-danger">Some error occured!</p>');
			}
			return response()->json($data);
		}
		 
	}
	public function getRingSizeGuide() {
		return view('front.sizeguide');
	}
}
