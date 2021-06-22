<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetalPuirty extends Model
{
    protected $fillable = ['metal_id','name','is_active'];

    public function metal() {

        return $this->belongsTo('App\Metal');
    }
}
