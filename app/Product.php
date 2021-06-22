<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use Sluggable;

    protected $fillable = [
        'is_approved','jn_web_id','product_group_id','name', 'is_active','product_group_default','product_group','group_by_size','group_by_purity', 'in_stock','qty_per_order', 'product_height','product_width','product_discount','product_discount_on','new_price', 'old_price', 'model', 'user_id', 'category_id','style_id', 'brand_id', 'description','metal_id','silver_item_id','purity_id', 'size', 'gender','height','width','features','metal_weight','pearls_weight','diamond_weight','stone_weight','total_weight','type','price','stone_price','va','va_percent','vat_rate','subtotal','gst_three_percent','carat_weight','carat_wt_per_diamond','diamond_wtcarats_earrings','diamond_wtcarats_earrings_price','diamond_wtcarats_nackless','diamond_wtcarats_nackless_price','diamond_necklace_carat_price','diamond_price','carat_price','diamond_price_one','diamond_price_two','total_stone_price','per_carate_cost','current_price','total_price','photo_id', 'overlay_photo_id','tax_rate', 'barcode', 'location_id', 'slug', 'voucher_id', 'file_id', 'virtual', 'downloadable', 'is_new', 'best_seller', 'sku', 'hsn', 'meta_desc', 'meta_keywords', 'meta_title', 'custom_fields', 'vendor_id', 'variants', 'comp_group_id', 'offer','bangles_checked','black_beads_checked','bracelets_checked','chains_checked','chempasaralu_checked','earrings_checked','mangalsutra_checked','nackless_checked','no_of_bangles','black-beads_length','bracelets_length','chains_length','chempasaralu_length','earrings_length','mangalsutra_length','nackless_length'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function vendor() {
        return $this->belongsTo('App\Vendor');
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function brand() {
        return $this->belongsTo('App\Brand');
    }

    public function orders() {
        return $this->belongsToMany('App\Order')->withPivot(['quantity', 'total', 'unit_price', 'spec']);
    }
    public function silverItem() {

        return $this->belongsTo('App\SilverItem');
    }
    public function photos() {
        
        return $this->hasMany('App\Photo');
    }
    public function photo() {
        return $this->belongsTo('App\Photo');
    }

    public function OverlayPhoto() {
         
        return $this->belongsTo('App\OverlayPhoto');
    }

   
    public function product_category_styles() {
        
        return $this->hasMany('App\ProductCategoryStyle');
    }
    

    public function product_style() {
        
        return $this->belongsTo('App\ProductStyle');
    }

    public function sales() {
        return $this->hasMany('App\Sale');
    }

    public function location() {
        return $this->belongsTo('App\Location');
    }

	public function voucher() {
		return $this->belongsTo('App\Voucher');
	}

    public function reviews() {
        return $this->hasMany('App\Review');
    }

    // public function sizeGroups() {
    //     return $this->belongsTo('App\SizeGroup');
    // }

    public function deals() {
        return $this->belongsToMany('App\Deal');
    }

    public function specificationTypes() {
        return $this->belongsToMany('App\SpecificationType')->withPivot(['value', 'unit']);
    }

    public function comparisionGroup() {
        return $this->belongsTo('App\ComparisionGroup');
    }

    public function file() {
        return $this->belongsTo('App\File');
    }

    public function certificate_products() {
        return $this->belongsToMany('App\Certificate');
    }

    public function product_pincodes() {
        return $this->belongsToMany('App\ProductPinCode');
    }

    public function metal() {
        return $this->belongsTo('App\Metal');
    }
    public function puirty() {
        return $this->belongsTo('App\MetalPuirty');
    }

    public function enquiry() {
        return $this->belongsTo('App\Enquiry');
    }

    public function related_products() {
        return $this->belongsToMany('App\Product', 'related_products', 'product_id', 'related_product_id');
    }

    public function favourites() {
        return $this->morphToMany('App\User', 'favouriteable');
    }

    public function favouritedBy(User $user) {
        return $this->favourites->contains($user);
    }

    public function vendor_amounts() {
        return $this->hasMany('App\VendorAmount');
    }

    public function decreaseStock($stock, $order_id = null) {
        $product = Product::findOrFail($this->id);
        if($product->virtual) {
            $product->sales++;
        } else {
            $product->in_stock -= $stock;
            $product->sales += $stock;
        }
        $product->save();
        $product->sales()->create(['date' => Carbon::now(), 'sales' => $stock, 'order_id' => $order_id]);
    }

    public static function isStockAvailable($products) {
        foreach($products as $product) {
            $product->in_stock -= $product->pivot->quantity;
            if($product->in_stock < 0) {
                return false;
            }
        }
        return true;
    }

    public function increaseStock($stock, $order_id = null) {
        $product = Product::findOrFail($this->id);
        if($product->virtual) {
            $product->sales--;
        } else {
            $product->in_stock += $stock;
            $product->sales -= $stock;
        }
        $product->save();
        $total_sales = Product::sum('sales');
        $product->sales()->where('order_id', $order_id)->where('product_id', $product->id)->delete();
    }

    public function price_with_tax($add_price = 0) {
        $price = $this->price;
        $price += $add_price;
        return $price + ($price * $this->tax_rate) / 100;
    }

    public function price_with_discount($add_price = 0) {
        $price = $this->price;
         
        $price += $add_price;
        if($this->voucher && Carbon::now()->gte(Carbon::parse($this->voucher->starts_at)) && Carbon::now()->lte(Carbon::parse($this->voucher->expires_at))) {
            return $price - ($price * $this->voucher->discount_amount) / 100;
        } else {
            return $price;
        }
    }

    public function discount_percentage() {
        if($this->voucher) {
            return $this->voucher->discount_amount;
        } else {
            return 0;
        }
    }

    public function price_with_tax_and_discount($add_price = 0) {
        $price = $this->price;
        $price += $add_price;
        if($this->voucher && Carbon::now()->gte(Carbon::parse($this->voucher->starts_at)) && Carbon::now()->lte(Carbon::parse($this->voucher->expires_at))) {
            return ($price - (($price * $this->voucher->discount_amount) / 100) + (($price * $this->tax_rate) / 100));
        } else {
            return $price + (($price * $this->tax_rate) / 100);
        }
    }
}
