<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metal extends Model
{
    protected $fillable = ['name','is_active'];

    public function metalpurity() {
        echo 'fdsfdsfdf';exit;
        return $this->hasOne('App\MetalPuirty');
    }
}
