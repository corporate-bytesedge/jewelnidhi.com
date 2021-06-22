<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeGroup extends Model
{
    protected $fillable = ['product_id','group_name','group_value'];
}
