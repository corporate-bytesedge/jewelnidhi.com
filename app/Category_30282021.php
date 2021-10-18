<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\ProductCategoryStyle;

class Category extends Model
{
    use Sluggable;
    
    protected $fillable = [
        'name', 'hsn_code','is_active', 'category_id', 'image','slug', 'location_id', 'photo_id', 'meta_desc', 'meta_keywords', 'meta_title', 'priority','top_category_priority','min_price','max_price','above_price', 'show_in_menu', 'show_in_footer', 'show_in_slider','banner','category_img','show_filter_price','show_filter_metal','show_filter_purity','show_filter_gender','show_filter_offers','show_filter_short_by'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // Parent Category
    public function category() {
        return $this->belongsTo('App\Category');
    }

    // Sub Categories
    public function categories() {
        return $this->hasMany('App\Category');
    }

    // public function categorystyle() {
    //     return $this->hasMany('App\ProductStyle');
    // }


    public function products() {
        return $this->hasMany('App\Product');
    }

    public function location() {
        return $this->belongsTo('App\Location');
    }

    public function photo() {
        return $this->belongsTo('App\Photo');
    }

    public function specificationTypes() {
        return $this->belongsToMany('App\SpecificationType');
    }

    public function banners() {
        return $this->hasMany('App\Banner');
    }

    public function product_style() {
        
        return $this->belongsTo('App\ProductStyle');
    }

    public function sections() {
        return $this->hasMany('App\Section');
    }
    public function productcategorystyle() {
        return $this->hasMany('App\ProductCategoryStyle');
    }
    public function all_products()
    {
        
        $categories = [$this];
        $products = [];
        $productsArr = [];
        $location_id = session('location_id');
        $product = array();
         
        while(count($categories) > 0) {
            $nextCategories = [];
              
            foreach ($categories as $category) {
               
                if($category->category_id=='0') {

                  if($category->slug == 'gift-item') {

                     

                    // $product =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    //     $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array(28, 31, 30,42,40,41));
                    // })->where(function ($query) {
                    //     $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    // })->where('is_active','1')->whereIn('category_id', array(1,12))->get();

                    $product =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                     

                     
                   

                  } else {
                    $product =  Product::distinct('product_group')->where(function ($query) use($category) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();


                        

                    break;
                  }

                    
                //   $product =  Product::distinct('product_group')->where(function ($query) use($category) {
                //     $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                //     })->where('is_active','1')->where('category_id',$category->id)->get();
                    
                    
                } else {
                    
                    /* $product =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                         
                       
                        $query->where('category_id', $category->category_id)->where('product_style_id',array($category->id));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
					
					$product =  Product::distinct('product_group')
						->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
						->where('product_category_styles.category_id', $category->category_id)->where('product_category_styles.product_style_id',array($category->id))
						->where(function ($query) {
                        $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                    })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
                    
                   
                }
                
                
                // foreach ($product as $pro) {
                //     $productsArr[] = $pro;
                // }
                
                $nextCategories = array_merge($nextCategories, $category->categories->all());
                // $products = array_merge($products, $productsArr);
            }
            $categories = $nextCategories;
        }
           
        
      
        return new Collection($product);
         
    }
	public function be_all_products()
    {
        
        $categories = [$this];
        $products = [];
        $productsArr = [];
        $location_id = session('location_id');
        $product = array();
         
        while(count($categories) > 0) {
            $nextCategories = [];
              
            foreach ($categories as $category) {
               
                if($category->category_id=='0') {

                  if($category->slug == 'gift-item') {

                     

                    // $product =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                    //     $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array(28, 31, 30,42,40,41));
                    // })->where(function ($query) {
                    //     $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    // })->where('is_active','1')->whereIn('category_id', array(1,12))->get();

                    $product =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                        $query->whereIn('category_id', array(1,12))->whereIn('product_style_id', array( 31, 30,42,40,41,70));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->whereIn('category_id', array(1,12))->orderBy('id','DESC')->get();

                     

                     
                   

                  } else {
                    $product =  Product::distinct('product_group')->where(function ($query) use($category) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                        })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->id)->orderBy('id','DESC')->get();


                        

                    break;
                  }

                    
                //   $product =  Product::distinct('product_group')->where(function ($query) use($category) {
                //     $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                //     })->where('is_active','1')->where('category_id',$category->id)->get();
                    
                    
                } else {
                    
                    /* $product =  Product::distinct('product_group')->whereHas('product_category_styles', function ($query) use($category)  {
                         
                       
                        $query->where('category_id', $category->category_id)->where('product_style_id',array($category->id));
                    })->where(function ($query) {
                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('is_active','1')->where('is_approved','1')->where('category_id',$category->category_id)->orderBy('id','DESC')->get(); */
                    
					
					$product =  Product::select('products.*')
						->leftjoin('product_category_styles', 'product_category_styles.product_id', '=', 'products.id')
						->where('product_category_styles.category_id', $category->category_id)->where('product_category_styles.product_style_id',array($category->id))
						->where(function ($query) {
                        $query->where('products.product_group_default', 1)->where('products.product_group', 1)->orWhere('products.product_group',  null);
                    })->where('products.is_active','1')->where('products.is_approved','1')->where('products.category_id',$category->category_id)->orderBy('products.id','DESC')->get();
                   
                }
                
                
                // foreach ($product as $pro) {
                //     $productsArr[] = $pro;
                // }
                
                $nextCategories = array_merge($nextCategories, $category->categories->all());
                // $products = array_merge($products, $productsArr);
            }
            $categories = $nextCategories;
        }
           
        
      
        return new Collection($product);
         
    }
      
    public function all_products_old()
    {
        
        $categories = [$this];
        $products = [];
        $productsArr = [];
        $location_id = session('location_id');
        $product = array();
         
        while(count($categories) > 0) {
            $nextCategories = [];
              
            foreach ($categories as $category) {

                 if($category->category_id=='0') {
                     
                    $productStyle = ProductCategoryStyle::where('category_id',$category->id)->get();
                     
                 } else {
                     
                    $productStyle = ProductCategoryStyle::where('category_id',$category->category_id)->where('product_style_id',$category->id)->get();
                   
                 }
                
                 
                
                foreach ($productStyle as $style) {

                     
                   $product = Product::distinct('product_group')->where('category_id',$style->category_id)->where('location_id', $location_id)->where(function ($query) {
                                $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                              })->where('is_active', 1)->get();

                    // $product = Product::distinct('product_group')->where('category_id',$style->category_id)->where('location_id', $location_id)->where(function ($query) {
                    // $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    // })->where('is_active', 1)->get();
                   //$product = Product::distinct('product_group')->where('category_id',$category->category_id)->where('location_id', $location_id)->where('is_active', 1)->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',null)->get();
                   
                   foreach ($product as $pro) {
                        $productsArr[] = $pro;
                        
                    }
                    $nextCategories = array_merge($nextCategories, $style->categories->all());
                   
                }
               
            $products = array_merge($products, $productsArr);
            
                
            }
            $categories = $nextCategories;
        }
           
        
          
        return new Collection($product);
         
    }
    public function all_products1()
    {
        $products = [];
        $categories = [$this];
       
        while(count($categories) > 0){
            $nextCategories = [];
            foreach ($categories as $category) {
                 
                $products  = array_merge($products, $category->products->all());
                $nextCategories = array_merge($nextCategories, $category->categories->all());
            }
            $categories = $nextCategories;
        }
        
        return new Collection($products);
    }

    

    // public function childrenRecursive() {
    //     return $this->categories()->orderBy( 'name' )->with('childrenRecursive', 'products');
    // }
     

}