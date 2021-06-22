<?php

namespace App;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\ProductCategoryStyle;
use App\Product;

class ProductStyle extends Model
{
    use Sluggable;
    protected $fillable = ['category_id','name','slug','image','is_active'];
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function category() {
        return $this->belongsTo('App\Category'); 
    }

    public function categories() {
        return $this->hasMany('App\Category');
    }

     
    public function products() {
        return $this->hasMany('App\Product');
    }

    public function productcategorystyle() {
        return $this->hasMany('App\ProductCategoryStyle');
    }

    public function banners() {
        return $this->hasMany('App\Banner');
    }
    public function sections() {
        return $this->hasMany('App\Section');
    }

    public function all_products()
    {
        
        $categories = [$this];
        $products = [];
        $productsArr = [];
        $location_id = session('location_id');
         
        while(count($categories) > 0) {
            $nextCategories = [];
             
            foreach ($categories as $category) {
                $productStyle = ProductCategoryStyle::where('category_id',$category->category_id)->where('product_style_id',$category->id)->get();
                
                foreach ($productStyle as $style) {
                   $product = Product::where('id',$style->product_id)->where('category_id',$category->category_id)->where('location_id', $location_id)->where('is_active', 1)->get();
                   
                   foreach ($product as $pro) {
                        $productsArr[] = $pro;
                        
                    }
                    $nextCategories = array_merge($nextCategories, $style->categories->all());
                   
                }
                
            $products = array_merge($products, $productsArr);
            
                
            }
            $categories = $nextCategories;
        }
             
         
        return new Collection($products);
         
    }

     

    
     
}
