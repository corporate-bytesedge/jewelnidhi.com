<?php

namespace App\Providers;

use App\Brand;
use App\Category;
use App\Deal;
use App\Location;
use App\Photo;
use App\Page;
use App\Testimonial;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Igaster\LaravelTheme\Facades\Theme;
use App\Setting;
use App\ProductCategoryStyle;
use App\Scheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // \URL::forceScheme('https');

        Theme::set(config('themeswitcher.current_theme'));

        View::composer(['layouts.manage','front.*'], function($view)
        {
            $default_photo = Photo::getDefaultUserPhoto();
            $view->with( 'default_photo', $default_photo );
        });

        View::composer('layouts.front', function($view)
        {
            
            if(!empty(\Auth::user())) {
                if(\Auth::user()->is_active == false || \Auth::user()->verified == false) {
                   
                \Auth::logout();
                }
            }
             
             
            if(!session()->has('location')) {
                $location = Location::findOrFail(1);
                session(['location'=>$location->name, 'location_id'=>$location->id]);
            }
            $locations = Location::all();
            $view->with( 'locations', $locations );

            $silver_rate = Setting::where('key','silver_item_rate')->first();
            $gold_rate = Setting::where('key','22_CRT')->first();

            $companypage = Page::where('is_active',true)->where('type',1)->get();
            $jewelnidhipage = Page::where('is_active',true)->where('type',2)->get();
            $customerpage = Page::where('is_active',true)->where('type',3)->get();
            
            $view->with( 'silver_rate', $silver_rate );
            $view->with( 'gold_rate', $gold_rate );
            $view->with( 'companypage', $companypage );
            $view->with( 'jewelnidhipage', $jewelnidhipage );
            $view->with( 'customerpage', $customerpage );
        });

        View::composer('layouts.front', function($view)
        {
            $location_id = session('location_id');
            $brands = Brand::where('is_active', 1)->where('location_id', $location_id)->where('show_in_menu', true)->orderby('priority', 'asc')->get();
            $view->with( 'brands', $brands );
        });

        View::composer('layouts.front', function($view)
        {
            $location_id = session('location_id');
            $brands_footer = Brand::where('is_active', 1)->where('location_id', $location_id)->where('show_in_footer', true)->orderby('priority', 'asc')->get();
            $view->with( 'brands_footer', $brands_footer );
        });

        View::composer('front.*', function($view)
        {
            $testimonials = Testimonial::where('is_active', 1)->orderBy('priority', 'asc')->get();
            $view->with( 'testimonials', $testimonials );
        });

        View::composer(['layouts.front', 'front.*'], function($view)
        {
            $location_id = session('location_id');
            $root_categories = Category::where('category_id', 0)->where('is_active', 1)->where('location_id', $location_id)->where('show_in_menu', true)->orderby('priority', 'asc')->get();
             
            $view->with( 'root_categories', $root_categories );
        });

        View::composer('layouts.front', function($view)
        {
            $location_id = session('location_id');
            $root_categories_footer = Category::where('is_active', 1)->where('location_id', $location_id)->where('show_in_footer', true)->orderby('priority', 'asc')->get();
            $view->with( 'root_categories_footer', $root_categories_footer );
        });

        View::composer(['layouts.front', 'front.product'], function($view)
        {
            $location_id = session('location_id');
            $deals = Deal::where('is_active', 1)->where('location_id', $location_id)->where('show_in_menu', true)->orderBy('priority', 'asc')->get();
            $view->with( 'deals', $deals );
        });

        View::composer(['layouts.front', 'front.index', 'front.product'], function($view)
        {
            $location_id = session('location_id');
            $discount_products = Voucher::where('type', 2)->where('location_id', $location_id)->get()->filter(function($discount) {
                return Carbon::now()->gte(Carbon::parse($discount->starts_at)) && Carbon::now()->lte(Carbon::parse($discount->expires_at));
            });
            $view->with( 'discount_products', $discount_products );

            //$categories = Category::with('categorystyle')->where('is_active', 1)->where('category_id','0')->where('location_id', $location_id)->orderby('priority', 'asc')->get();
            $categories = Category::with('categories')->where('is_active', 1)->where('category_id','0')->where('location_id', $location_id)->orderBy('priority', 'asc')->get();
             $scheme = Scheme::where('is_active',true)->first();
             
            $view->with( 'categories', $categories );
            $view->with( 'scheme', $scheme );
        });

        View::composer('layouts.front', function($view)
        {
            $location_id = session('location_id');
            $deals_footer = Deal::where('is_active', 1)->where('location_id', $location_id)->where('show_in_footer', true)->orderBy('priority', 'asc')->get();
            $view->with( 'deals_footer', $deals_footer );
        });

        View::composer('layouts.front', function($view)
        {
            $pages = Page::where('is_active', 1)->where('show_in_menu', true)->orderBy('priority', 'asc')->get();
            $view->with( 'pages', $pages );
        });

        View::composer('layouts.front', function($view)
        {
            $pages_footer = Page::where('is_active', 1)->where('show_in_footer', true)->orderBy('priority', 'asc')->get();
            $view->with( 'pages_footer', $pages_footer );
        });

        Collection::macro('paginate', function( $perPage, $total = null, $page = null, $pageName = 'page' ) {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage( $pageName );

            return new LengthAwarePaginator( $this->forPage( $page, $perPage ), $total ?: $this->count(), $perPage, $page, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        });

        session(['location_id' => 1]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
