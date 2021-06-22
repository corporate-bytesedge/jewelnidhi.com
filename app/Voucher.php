<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
    	'user_id','code', 'name', 'use_coupon_one_time','description', 'uses', 'max_uses', 'max_uses_user', 'type', 'discount_percentage', 'discount_amount', 'is_fixed', 'starts_at', 'expires_at', 'location_id', 'valid_above_amount'
    ];

	public function products() {
        return $this->hasMany('App\Product');
    }

    public function location() {
        return $this->belongsTo('App\Location');
    }

    public function users() {
    	return $this->belongsToMany('App\User')->withPivot(['uses']);
    }
}
