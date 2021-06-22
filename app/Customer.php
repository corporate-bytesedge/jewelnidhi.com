<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    protected $fillable = [
        'vendor_id',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'phone',
         
        'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function addCustomerShippingAddress($input){
         
        if(\Session::get('vendor_id')) {
            
            $vendorID = \Session::get('vendor_id');
        }
        
        $vendorArr = array_unique($vendorID);
        foreach($vendorArr AS $k => $value ) {
            $customer = Auth::user()->customers()->save(
                new Customer([
                    "vendor_id"=> $value ? $value : null,
                    "first_name"=> $input["first_name"],
                    "last_name" => $input["last_name"],
                    "address"   => $input["address"],
                    "city"      => $input["city"],
                    "state"     => $input["state"],
                    "zip"       => $input["zip"],
                    "country"   => $input["country"],
                    "phone"     => $input["phone"]
                    
                ]));
        
        }
        return $customer;
    }
}
