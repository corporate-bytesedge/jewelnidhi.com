<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voucher;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FrontCouponsController extends Controller
{
    public function checkCoupon(Request $request)
    {
        $coupon = Voucher::where('code', $request->coupon)->first();
        $todayDate = Carbon::now();
        $todayDate->toDateString();
        if(isset($coupon->expires_at)) {
            $expiryDate = date('Y-m-d', strtotime($coupon->expires_at));
        }
        //dd($coupon);
        
        if($coupon && ($coupon->location_id == session('location_id')) && $coupon->valid_above_amount < Cart::total() && $coupon->starts_at && $coupon->expires_at && $expiryDate >= $todayDate && $coupon->max_uses < 1   ) {
            
             
            // If user has already used this coupon
            if($coupon->users()->where('user_id', Auth::user()->id)->count() > 0) {
                
               
                $coupon_user = $coupon->users()->where('user_id', Auth::user()->id)->first();
                
               
                $coupon_user->pivot->uses += 1;
                $coupon_user->pivot->save();
                
            // If user has not used this coupon
            } else {
                
                Auth::user()->vouchers()->attach($coupon->id, ['uses'=>1]);
            }

            $coupon->uses += 1;

             
           
            
            session()->forget('use_coupon_one_time');
            $coupon->save();

            if($coupon->use_coupon_one_time == '1') {
                $coupon->max_uses = 1;
                $coupon->uses = 1;
                $coupon->save();
                session(['use_coupon_one_time' => $coupon->use_coupon_one_time]);
            } 
            if($coupon->type == 0) {
                session(['coupon_amount' => $coupon->discount_amount]);
                session(['coupon_percent' => '']);
            } else {
                
                $percent =  (Cart::total() * $coupon->discount_percentage)/100;
                
                session(['coupon_percent' => $coupon->discount_percentage]);
                session(['coupon_amount' => $percent]);
            }
            
            session(['coupon_valid_above_amount' => $coupon->valid_above_amount]);
            session(['coupon_code' => $coupon->code]);
        } else {
           
            $this->clearCoupon();
             
        }
         
        return back();
        
    }

    private function clearCoupon()
    {
        
        session()->forget('coupon_uses');
        session()->forget('use_coupon_one_time');
        
        session()->forget('coupon_amount');
        session()->forget('coupon_valid_above_amount');
        session()->forget('coupon_code');
        session()->flash('coupon_invalid', __('Coupon invalid or expired.'));
    }
}
