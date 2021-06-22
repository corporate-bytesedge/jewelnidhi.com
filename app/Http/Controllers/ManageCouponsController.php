<?php

namespace App\Http\Controllers;

use App\Location;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CouponsCreateRequest;
use App\Http\Requests\CouponsUpdateRequest;
use Illuminate\Http\Request;

class ManageCouponsController extends Controller
{
    public function index()
    {
       
        if(Auth::user()->can('read-coupon', Voucher::class)) {
            $vendor = \Auth::user()->isApprovedVendor();
            if($vendor) {
               
                 
                 
                $coupons = Voucher::where('location_id', Auth::user()->location_id)->where('user_id',\Auth::user()->id)->get()->filter(function($coupon) {
                return Carbon::now()->gte(Carbon::parse($coupon->starts_at)) && Carbon::now()->lte(Carbon::parse($coupon->expires_at));
                });
            } else {
                 
                $coupons = Voucher::where('location_id', Auth::user()->location_id)->get();
            }
            return view('manage.coupons.index', compact('coupons'));
        } else {
            return view('errors.403');
        }
    }

    public function create()
    {
        if(Auth::user()->can('create-coupon', Voucher::class)) {

            $location_id = Auth::user()->location_id;
            return view('manage.coupons.create');

        } else {
            return view('errors.403');
        }
    }

    public function store(Request $request)
    {
       
        if(Auth::user()->can('create-coupon', Voucher::class)) {

            $request->validate([
                'name' => 'required',
                'type' => 'required',
                'starts_at' => 'date|required',
                'expires_at' => 'date|required',
                'valid_above_amount' => 'min:0'
            ]);
            
            $userInput = $request->all();
            $input["name"] = $userInput["name"];
            
            $input["user_id"] = \Auth::user()->id;
            $input["code"] = $userInput["code"];
            $input["description"] = $userInput["description"];
            
            $input["starts_at"] = $userInput["starts_at"];
            $input["expires_at"] = $userInput["expires_at"];
            $input["valid_above_amount"] = $userInput["valid_above_amount"];
            $input["use_coupon_one_time"] = isset($userInput["use_coupon_one_time"])  ? $userInput["use_coupon_one_time"] : null;
            
            $input["type"] = isset($userInput["type"]) ? $userInput["type"] : '';
            if($userInput["type"] == 1 ) {
                
                $input["discount_percentage"] = $userInput["discount_percentage"];
                $input["discount_amount"] = null;
            } else {
                
                $input["discount_amount"] = $userInput["discount_amount"];
                $input["discount_percentage"] = null;
            }

             
            $input['is_fixed'] = true;
            $input['uses'] = 0;

            $locations = Location::pluck('name','id')->all();

            $location_id = Auth::user()->location_id;
            $input['location_id'] = $location_id;

            $coupon = Voucher::create($input);

            session()->flash('coupon_created', __("New coupon has been added."));
            return redirect(route('manage.coupons.index'));
        } else {
            return view('errors.403');
        }
    }

    public function edit($id)
    {
        if(Auth::user()->can('update-coupon', Voucher::class)) {

            $coupon = Voucher::where('location_id', Auth::user()->location_id)->where('id', $id)->firstOrFail();
           //dd($coupon);
            return view('manage.coupons.edit', compact('coupon'));
        } else {
            return view('errors.403');
        }
    }

    public function update(Request $request, $id)
    {
         
         
        if(Auth::user()->can('update-coupon', Voucher::class)) {
            
             

            $request->validate([
                'name' => 'required',
                'type' => 'required',
                'starts_at' => 'date|required',
                'expires_at' => 'date|required',
                'valid_above_amount' => 'min:0'
            ]);
               
            $coupon = Voucher::findOrFail($id);
             
            $userInput = $request->all();
             
            $input["name"] = $userInput["name"];
            $input["user_id"] = \Auth::user()->id;
            $input["code"] = $userInput["code"];
           
            $input["description"] = $userInput["description"];
           
            $input["type"] = isset($userInput["type"]) ? $userInput["type"] : '';
           
            $input["use_coupon_one_time"] = isset($userInput["use_coupon_one_time"])  ? $userInput["use_coupon_one_time"] : null;
             
            if(isset($userInput["type"]) && $userInput["type"] == 1 ) {
                
                $input["discount_percentage"] = $userInput["discount_percentage"];
                $input["discount_amount"] = null;
            } else {
                
                $input["discount_amount"] = $userInput["discount_amount"];
                $input["discount_percentage"] = null;
            }
           
           
          
            
            $input["starts_at"] = $userInput["starts_at"];
            $input["expires_at"] = $userInput["expires_at"];
            $input["valid_above_amount"] = $userInput["valid_above_amount"];
             
            $coupon->update($input);
            session()->flash('coupon_updated', __("The coupon has been updated."));
            
            return redirect(route('manage.coupons.edit', $coupon->id));
        } else {
             
            return view('errors.403');
        }
    }

    public function destroy($id)
    {
        if(Auth::user()->can('delete-coupon', Voucher::class)) {
            $coupon = Voucher::findOrFail($id);
            $coupon->delete();
            session()->flash('coupon_deleted', __("The coupon has been deleted."));
            return redirect(route('manage.coupons.index'));
        } else {
            return view('errors.403');
        }
    }

    public function deleteCoupons(Request $request)
    {
        if(Auth::user()->can('delete-coupon', Voucher::class)) {
            if(isset($request->delete_single)) {
                $coupon = Voucher::findOrFail($request->delete_single);
                $coupon->delete();
                session()->flash('coupon_deleted', __("The coupon has been deleted."));
            } else {
                if(isset($request->delete_all) && !empty($request->checkboxArray)) {
                    $coupons = Voucher::findOrFail($request->checkboxArray);
                    foreach($coupons as $coupon) {
                        $coupon->delete();
                    }
                    session()->flash('coupon_deleted', __("The selected coupons have been deleted."));
                } else {
                    session()->flash('coupon_not_deleted', __("Please select coupons to be deleted."));
                }
            }
            return redirect(route('manage.coupons.index'));
        } else {
            return view('errors.403');
        }
    }
}
