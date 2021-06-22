<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $fillable = ['title','content','is_active','meta_title','meta_desc','meta_keywords'];
}
