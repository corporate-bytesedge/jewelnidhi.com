<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name', 'for'
    ];

    public function roles() {
        return $this->belongsToMany('App\Role');
    }
}
