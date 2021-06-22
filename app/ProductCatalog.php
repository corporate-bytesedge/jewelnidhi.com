<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCatalog extends Model
{
    protected $fillable = ['image','sku','weight','diamond_weight','is_active'];
}
