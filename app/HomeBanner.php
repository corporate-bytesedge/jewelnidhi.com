<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    protected $fillable = ['title_one','title_two','title_three','link_one','link_two','link_three','image_one','image_two','image_three','description_one','description_two','description_three'];
}
