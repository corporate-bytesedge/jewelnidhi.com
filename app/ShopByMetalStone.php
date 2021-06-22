<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopByMetalStone extends Model
{
    protected $fillable = ['category_id','name','is_active'];

    public function category() {
        return $this->belongsTo('App\Category');
    }
}
