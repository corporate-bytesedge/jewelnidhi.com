<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = ['product_id','name','email','phone','query'] ;

    public function product() {
        return $this->belongsTo('App\Product');
    }
}
