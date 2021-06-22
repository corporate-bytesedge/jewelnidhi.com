<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class ProductCategoryStyle extends Model
{
    
    protected $fillable = ['category_id','product_id','product_style_id'];
    
    public function product() {
        return $this->belongsTo('App\Product');
    }
    public function categories() {
        return $this->hasMany('App\Category');
    }
    

    public function products() {
        return $this->hasMany('App\Product');
    }
    public function productstyle() {
        return $this->belongsTo('App\ProductStyle');
    }
     
     
}
