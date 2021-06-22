<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['registration_template','forgot_password_template','order_place_template','order_cancel_template'];
}
