<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use App\Setting;
use App\User;
use App\Photo;
use DB;
use App\Other;
use App\Location;
use App\Http\Requests\UsersUpdateRequest;
use App\Http\Requests\MetalRequest;
use App\Http\Requests\MetalPuirtyRequest;
 
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\Auth\UserActivationEmail;
use Illuminate\Support\Facades\Artisan;
use Igaster\LaravelTheme\Facades\Theme;
use App\Certificate;
use App\Metal;
use App\MetalPuirty;
use App\ProductCatalog;
use App\Http\Requests\ProductCatalogRequest;
use App\ProductPinCode;
use App\Http\Requests\ProductPincodeRequest;
use App\ProductStyle;
use App\Http\Requests\ProductStyleRequest;
use App\Category;
use App\Enquiry;
use App\ShopByMetalStone;
use App\Http\Requests\ShopByMetalRequest;

class ManageSettingsController extends Controller
{
    public function index()
    {
        if(Auth::user()->can('update-settings', Other::class)) {
            $settings = Setting::pluck('value', 'key')->all();
            $themes = $this->getThemes();
            return view('manage.settings.index', compact('settings', 'themes'));
        } else {
            return view('errors.403');
        }
    }

    private function getThemes()
    {
        return [
//            'default-theme' => 'Default',
            'theme1' => 'Theme 1',
            'theme2' => 'Theme 2'
        ];
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $locations = Location::pluck('name','id')->all();
        return view('manage.settings.profile', compact('user', 'locations'));
    }
     public function priceSetting(Request $request)
    {
          
        if(Auth::user()->can('update-price-settings', Other::class)) {
            $settings = Setting::pluck('value', 'key')->all();
            $goldpricesetting = [];
            
            foreach($settings AS $k=> $value) {
               if(substr($k, -3)=='CRT') {
                    $goldpricesetting[$k] = array_has($settings, $k) ? $settings[$k] : null;
                    $goldpricesetting[$k] = array_has($settings, $k) ? $settings[$k] : null;
                    $goldpricesetting[$k] = array_has($settings, $k) ? $settings[$k] : null;
                    $goldpricesetting[$k] = array_has($settings, $k) ? $settings[$k] : null;
               }
                
            }
            
            $pricesetting = [];
            $platiumpricesetting = [];
            $pricesetting['silver_jewellery_rate'] = array_has($settings, 'silver_jewellery_rate') ? $settings['silver_jewellery_rate'] : null;
            $pricesetting['silver_item_rate'] = array_has($settings, 'silver_item_rate') ? $settings['silver_item_rate'] : null;

            $platiumpricesetting['platinum_seventy_five'] = array_has($settings, '750_KT') ? $settings['750_KT'] : null;
            $platiumpricesetting['platinum_nigntifive_five'] = array_has($settings, '950_KT') ? $settings['950_KT'] : null;
           
            $metalPurity = MetalPuirty::where('is_active',1)->get();
            // dd($metalPurity);
            
            return view('manage.settings.pricesetting',compact('goldpricesetting','pricesetting','metalPurity','platiumpricesetting'));
        } else {
            return view('errors.403');
        }
        
    }
    public function updatePriceSetting(Request $request) {

        if(Auth::user()->can('update-settings', Other::class)) {
            $dataArr = $request->except('_token','_method','PATCH');
             
            foreach($dataArr as $key => $data) {
                
                $setting = Setting::where('key', $key)->first();
               // dump($setting);
                if($setting) {
                    $setting->delete();
                }
                
                 
                $setting = new Setting;
                if($setting) {
                    $array = array('key'=>$key, 'value'=>$data);
                    
                    $setting::create($array);
                } else {
                    
                     
                     session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                     return redirect()->back();
                }
            }    
            session()->flash('profile_updated', __("The Price has been updated."));
            return redirect(route('manage.settings.pricesetting'));
        } else {
            return view('errors.403');
        }        
    }
    function updateProfile(UsersUpdateRequest $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
 
        $this->validate($request, [
            'email' => 'unique:users,email,'.$user->id
            // 'username' => 'unique:users,username,'.$user->id
        ]);

        if(!$request->password) {
            $userInput = $request->except('password');
            $input["password"] = $user->password;
        } else {
            $userInput = $request->all();
            $input['password'] = bcrypt($request->password);
        }

        $input["name"] = $userInput["name"];
        $input["username"] = $userInput["username"];
        $input["email"] = $userInput["email"];

        $vendorinput['shop_name'] = $userInput["shop_name"];
        $vendorinput['company_name'] = $userInput["company_name"];
        $vendorinput['description'] = $userInput["description"];
        $vendorinput['address'] = $userInput["address"];
        $vendorinput['city'] = $userInput["city"];
        $vendorinput['state'] = $userInput["state"];

        if(!$request->file('photo') && $request->remove_photo) {
            if($user->photo) {
                if(file_exists($user->photo->getPath())) {
                    unlink($user->photo->getPath());
                    $user->photo()->delete();
                }
            }
        }

        if($photo = $request->file('photo')) {
            $name = time().$photo->getClientOriginalName();
            $photo->move(Photo::getPhotoDirectoryName(), $name);
            if($user->photo) {
                if(file_exists($user->photo->getPath())) {
                    unlink($user->photo->getPath());
                    $user->photo()->delete();
                }
            }
            $photo = Photo::create(['name'=>$name]);
            $input['photo_id'] = $photo->id;
        }

        $old_email = $user->email;

        if($old_email != $request->email) {
            if($user->id != 1){
                $input['verified'] = false;
            }
            $input['activation_token'] = str_random(191);
        }
        $user->vendor->update($vendorinput);

        $user->update($input);

        if($request->email != $old_email) {
            event(new UserActivationEmail($user));
        }

        session()->flash('profile_updated', __("The profile has been updated."));

        return redirect(route('manage.settings.profile'));
    }

    public function updateStore(SettingsUpdateRequest $request, Factory $cache)
    {
        
        if($photo = $request->file('logo')) {
            $name = $photo->getClientOriginalName();
            if(file_exists(public_path().Photo::$directory.$name)) {
                unlink(public_path().Photo::$directory.$name);
            }
            $photo->move(Photo::getPhotoDirectoryName(), $name);
            $request->merge(['site_logo' => $name]);
        } else {
            $request->site_logo = null;
        }

        // if($photo = $request->file('site_favicon')) {
        //     $name = $photo->getClientOriginalName();
        //     $favicon_extension = $photo->getClientOriginalExtension();
        //     if($favicon_extension == 'ico'){
               
        //         if(file_exists(public_path().'/favicon.ico')) {
        //             unlink(public_path().'/favicon.ico');
        //         }
        //         $photo->move(public_path(), '/favicon.ico');
        //     } else {
        //         return redirect()->back()->withErrors(__('Please upload only ico file for favicon.'));
        //     }
        // }

        if($request->footer_enable) {
            $request->merge(['footer_enable' => true]);
        } else {
            $request->merge(['footer_enable' => false]);
        }

	    if($request->social_share_enable) {
		    $request->merge(['social_share_enable' => true]);
	    } else {
		    $request->merge(['social_share_enable' => false]);
	    }

        if($request->social_link_facebook_enable) {
            $request->merge(['social_link_facebook_enable' => true]);
        } else {
            $request->merge(['social_link_facebook_enable' => false]);
        }

        if($request->social_link_instagram_enable) {
            $request->merge(['social_link_instagram_enable' => true]);
        } else {
            $request->merge(['social_link_instagram_enable' => false]);
        }

        if($request->social_link_twitter_enable) {
            $request->merge(['social_link_twitter_enable' => true]);
        } else {
            $request->merge(['social_link_twitter_enable' => false]);
        }

        if($request->social_link_youtube_enable) {
            $request->merge(['social_link_youtube_enable' => true]);
        } else {
            $request->merge(['social_link_youtube_enable' => false]);
        }
        if($request->social_link_pintrest_enable) {
            $request->merge(['social_link_pintrest_enable' => true]);
        } else {
            $request->merge(['social_link_pintrest_enable' => false]);
        }
        if($request->social_link_whatspp_enable) {
            $request->merge(['social_link_whatsapp_enable' => true]);
        } else {
            $request->merge(['social_link_whatsapp_enable' => false]);
        }
        
	    if($request->social_link_google_plus_enable) {
		    $request->merge(['social_link_google_plus_enable' => true]);
	    } else {
		    $request->merge(['social_link_google_plus_enable' => false]);
	    }

	    if($request->social_link_linkedin_enable) {
		    $request->merge(['social_link_linkedin_enable' => true]);
	    } else {
		    $request->merge(['social_link_linkedin_enable' => false]);
	    }

        if($request->site_logo_enable) {
            $request->merge(['site_logo_enable' => true]);
        } else {
            $request->merge(['site_logo_enable' => false]);
        }

        if($request->main_slider_enable) {
            $request->merge(['main_slider_enable' => true]);
        } else {
            $request->merge(['main_slider_enable' => false]);
        }

        if($request->products_slider_enable) {
            $request->merge(['products_slider_enable' => true]);
        } else {
            $request->merge(['products_slider_enable' => false]);
        }

        if($request->hide_main_slider_in_devices) {
            $request->merge(['hide_main_slider_in_devices' => true]);
        } else {
            $request->merge(['hide_main_slider_in_devices' => false]);
        }

        if($request->categories_slider_enable) {
            $request->merge(['categories_slider_enable' => true]);
        } else {
            $request->merge(['categories_slider_enable' => false]);
        }

        if($request->brands_slider_enable) {
            $request->merge(['brands_slider_enable' => true]);
        } else {
            $request->merge(['brands_slider_enable' => false]);
        }

        if($request->banners_right_side_enable) {
            $request->merge(['banners_right_side_enable' => true]);
        } else {
            $request->merge(['banners_right_side_enable' => false]);
        }

        if($request->enable_google_translation) {
            $request->merge(['enable_google_translation' => true]);
        } else {
            $request->merge(['enable_google_translation' => false]);
        }

        if(Auth::user()->can('update-settings', Other::class)) {
            foreach($request->except('_token') as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                if($setting) {
                    $setting->value = $value;
                    $setting->save();
                }
            }

            $cache->forget('settings');

            if($request->maintenance_enable) {
                Artisan::call('down');
            } else {
                Artisan::call('up');
            }

            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateTheme(Request $request)
    {
        if(Auth::user()->can('update-settings', Other::class)) {
            if($request->theme) {
                if(Theme::exists($request->theme)) {
                    $array = \Config::get('themeswitcher');
                    $array['current_theme'] = $request->theme;
                    $data = var_export($array, 1);
                    if(\File::put(base_path() . '/config/themeswitcher.php', "<?php\n return $data ;")) {
                        session()->flash('settings_saved', __("The settings has been saved."));
                    } else {
                        session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                    }
                } else {
                    session()->flash('settings_not_saved', __("This theme is not supported."));
                }
            }
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateLiveChat(Request $request)
    {
        if(Auth::user()->can('update-settings', Other::class)) {
            $array = \Config::get('livechat');
            $array['tawk_widget_code'] = $request->tawk_widget_code ? $request->tawk_widget_code : "";
            $data = var_export($array, 1);
            if(\File::put(base_path() . '/config/livechat.php', "<?php\n return $data ;")) {
            } else {
                session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                return redirect()->back();
            }
            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateTaxShipping(Request $request, Factory $cache)
    {
        $this->validate($request, [
            'tax_rate'=>'required|integer|min:0|max:100',
            'shipping_cost'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:0',
            'shipping_cost_valid_below'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:0'
        ]);
        if($request->enable_zip_code) {
            $request->merge(['enable_zip_code' => true]);
        } else {
            $request->merge(['enable_zip_code' => false]);
        }
        if(Auth::user()->can('update-settings', Other::class)) {
            foreach($request->except('_token') as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                if($setting) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
    
            $cache->forget('settings');
    
            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateVendor(Request $request)
    {
        if(Auth::user()->can('update-settings', Other::class)) {
            $this->validate($request, [
                'minimum_amount_for_request'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:0'
            ]);

            $array = \Config::get('vendor');
            $array['minimum_amount_for_request'] = $request->minimum_amount_for_request;
            $array['enable_vendor_signup'] = (bool) $request->enable_vendor_signup;
            $data = var_export($array, 1);
            if(\File::put(base_path() . '/config/vendor.php', "<?php\n return $data ;")) {
            } else {
                session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                return redirect()->back();
            }
            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateAdminPanel(Request $request, Factory $cache)
    {
        if($request->export_table_enable) {
            $request->merge(['export_table_enable' => true]);
        } else {
            $request->merge(['export_table_enable' => false]);
        }

        if($request->toast_notifications_enable) {
            $request->merge(['toast_notifications_enable' => true]);
        } else {
            $request->merge(['toast_notifications_enable' => false]);
        }

        if($request->loading_animation_enable) {
            $request->merge(['loading_animation_enable' => true]);
        } else {
            $request->merge(['loading_animation_enable' => false]);
        }

        if(Auth::user()->can('update-settings', Other::class)) {
            foreach($request->except('_token') as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                if($setting) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
    
            $cache->forget('settings');

            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateRecaptcha(Request $request) {
        if(Auth::user()->can('update-settings', Other::class)) {
            $array = \Config::get('recaptcha');
                    $array['enable'] = $request->enable_recaptcha;
                    $array['public_key'] = $request->recaptcha_public_key;
                    $array['private_key'] = $request->recaptcha_private_key;
                    $data = var_export($array, 1);
                    if(\File::put(base_path() . '/config/recaptcha.php', "<?php\n return $data ;")) {
                    } else {
                        session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                        return redirect()->back();
                    }

            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateGoogleMap(Request $request) {
        if(Auth::user()->can('update-settings', Other::class)) {
            $array = \Config::get('googlemap');
            $array['embed_code'] = $request->map_embed_code;
            $array['api_key'] = $request->map_api_key;
            $array['location_name'] = $request->map_location;
            $array['zoom'] = $request->map_zoom;
            $data = var_export($array, 1);
            if(\File::put(base_path() . '/config/googlemap.php', "<?php\n return $data ;")) {
            } else {
                session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                return redirect()->back();
            }

            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateGoogleAnalytics(Request $request) {
        if(Auth::user()->can('update-settings', Other::class)) {
            $array = \Config::get('analytics');
            $array['google_analytics_script'] = $request->tracking_code;
            $data = var_export($array, 1);
            if(\File::put(base_path() . '/config/analytics.php', "<?php\n return $data ;")) {
            } else {
                session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                return redirect()->back();
            }
            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateSiteMap(Request $request) {
        if(Auth::user()->can('update-settings', Other::class)) {
            $array = \Config::get('site_map');
            $array['site_map_url'] = $request->site_map_url;
            $data = var_export($array, 1);
            if(\File::put(base_path() . '/config/site_map.php', "<?php\n return $data ;")) {
            } else {
                session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                return redirect()->back();
            }

            if($file = $request->file('site_map_file')) {
                $file_extension = $file->getClientOriginalExtension();
                if($file_extension == 'xml'){
                    if(file_exists(public_path().'/sitemap.xml')) {
                        unlink(public_path().'/sitemap.xml');
                    }
                    $file->move(public_path(), 'sitemap.xml');
                } else {
                    return redirect()->back()->withErrors(__('Please upload only xml file for Site Map.'));
                }
            }

            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }

    public function updateReferral(Request $request) {
        if(Auth::user()->can('update-settings', Other::class)) {
            $array = \Config::get('referral');
            if($request->enable_referral) { $array['enable'] = true; }
            else { $array['enable'] = false; }
            $array['referral_to_amt']   = $request->referral_to_amt;
            $array['referral_by_amt']   = $request->referral_by_amt;
            $data = var_export($array, 1);
            if(\File::put(base_path() . '/config/referral.php', "<?php\n return $data ;")) {
            } else {
                session()->flash('settings_not_saved', __("Error saving the settings. There may be no permission to modify the file."));
                return redirect()->back();
            }
            session()->flash('settings_saved', __("The settings has been saved."));
            return redirect()->back();
        } else {
            return view('errors.403');
        }
    }
    // Certificate Section
    public function certificate() {
        
        $certificates = Certificate::all();
        
        return view('manage.settings.certificate',compact('certificates'));
    }
    public function createCertificate() {
        return view('manage.settings.CreateOrUpdateCertificate');
    }

    public function editCertificate($id) {
        $certificates = Certificate::find($id);
         
        return view('manage.settings.CreateOrUpdateCertificate',compact('certificates'));
    }

    public function storeCertificate(Request $request) {
        $data = $request->all();
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->image->getClientOriginalName();
            $path = $file->storeAs('public/certificate/', $filename);
            $data['image'] = $filename;
        }
         
        Certificate::create($data);
        return redirect()->route('manage.settings.certificate')->with('message', 'Certificate has been save successfully');
    }

    public function updateCertificate(Request $request) {
        $certificates = Certificate::find($request->id);
        $data = $request->all();
        if($request->hasFile('image')) {
             
            $file = $request->file('image');
            $filename = $request->image->getClientOriginalName();
            $path = $file->storeAs('public/certificate/', $filename);
            $data['image'] = $filename;
             
        } else {
            $data['image'] = $request->input('old_image');
        }
        $certificates->update($data);
        return redirect()->route('manage.settings.certificate')->with('message', 'Certificate has been updated successfully');
    }
    public function deleteCertifcate($id) {

        $certificates = Certificate::findOrFail($id);
        
        if($certificates->image) {

            $path = storage_path().'/app/public/certificate/'.$certificates->image;
            if(file_exists($path)) {
                unlink($path);
                
            }
            
        }
        $certificates->delete();
        return redirect()->route('manage.settings.certificate')->with('message', 'Certificate has been delete successfully');
    }
    public function bulkCertificateDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $certificate = Certificate::findOrFail($request->checkboxArray);
            foreach($certificate as $value) {
                
                 
                if($value->image) {
                    $path = storage_path().'/app/public/certificate/'.$value->image;
                    if(file_exists($path)) {
                        unlink($path);
                        $value->image()->delete();
                    }
                }
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected certificates have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select certificates to be deleted."));
        }
        return redirect(route('manage.settings.certificate'));
    }

    // Certificate Section end

    // Metal Section

    public function Metal() {
        
        $metals = Metal::all();
         
        return view('manage.settings.metal',compact('metals'));
    }

    public function createMetal() {
        
        return view('manage.settings.CreateOrUpdateMetal');
    }

    public function storeMetal(MetalRequest $metalRequest) {
        $data = $metalRequest->all();
        Metal::create($data);
        return redirect()->route('manage.settings.metal')->with('message', 'Metal has been save successfully');
    }

    public function editMetal($id) {
        $metal = Metal::find($id);
        return view('manage.settings.CreateOrUpdateMetal',compact('metal'));
    }
    
    public function updateMetal(MetalRequest $metalRequest) {
        $metal = Metal::find($metalRequest->id);
        $data = $metalRequest->all();
        $metal->update($data);
        return redirect()->route('manage.settings.metal')->with('message', 'Metal has been updated successfully');
    }

    public function deleteMetal($id) {

        $metal = Metal::findOrFail($id);
        $metal->delete();
        return redirect()->route('manage.settings.metal')->with('message', 'Metal has been delete successfully');
    }

    public function bulkMetalDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $metal = Metal::findOrFail($request->checkboxArray);
            foreach($metal as $value) {
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected metal have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select metal to be deleted."));
        }
        return redirect(route('manage.settings.metal'));
    }

      // Metal Puirty Section

    public function Metalpuirty() {
        $metals = MetalPuirty::all();
        return view('manage.settings.metal_puirty',compact('metals'));
    }

    public function createMetalPuirty() {
        $metals = Metal::where('is_active',1)->get()->pluck('name','id');
          
        return view('manage.settings.CreateOrUpdatePuirty',compact('metals'));
    }

    public function storeMetalPuirty(MetalPuirtyRequest $metalPuirtyRequest) {
        $data = $metalPuirtyRequest->all();
        MetalPuirty::create($data);
        return redirect()->route('manage.settings.puirty')->with('message', 'Puirty has been save successfully');
    }

    public function editMetalPuirty($id) {
        $puirty = MetalPuirty::find($id);
        $metals = Metal::where('is_active',1)->get()->pluck('name','id');
 
       
        //$selectedMetals = $metals->metalpurity->pluck('id');
        //dd($selectedMetals);
        return view('manage.settings.CreateOrUpdatePuirty',compact('puirty','metals'));
    }

    public function updateMetalPuirty(MetalPuirtyRequest $metalPuirtyRequest) {
        $puirty = MetalPuirty::find($metalPuirtyRequest->id);
        $data = $metalPuirtyRequest->all();
        $puirty->update($data);
        return redirect()->route('manage.settings.puirty')->with('message', 'Puirty has been updated successfully');
    }

    public function deleteMetalPuirty($id) {

        $puirty = MetalPuirty::findOrFail($id);
        $puirty->delete();
        return redirect()->route('manage.settings.puirty')->with('message', 'Puirty has been delete successfully');
    }

    public function bulkMetalPuirtyDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $metal = Metal::findOrFail($request->checkboxArray);
            foreach($metal as $value) {
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected metal have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select metal to be deleted."));
        }
        return redirect(route('manage.settings.metal'));
    }

    // Catalog Section
    public function getCatalog() {
        $catalogs = ProductCatalog::all();
        return view('manage.settings.catalog',compact('catalogs'));
    }

    public function createCatalog() {
        
          
        return view('manage.settings.CreateOrUpdateCatalog');
    }
    public function storeCatalog(ProductCatalogRequest $productCatalogRequest) {
        $data = $productCatalogRequest->all();
        
        if($productCatalogRequest->hasFile('image')) {
            $file = $productCatalogRequest->file('image');
            $filename = $productCatalogRequest->image->getClientOriginalName();
            $path = $file->storeAs('public/catalog/', $filename);
            $data['image'] = $filename;
        }
         
        ProductCatalog::create($data);
        return redirect()->route('manage.settings.catalog')->with('message', 'Catalog has been save successfully');
    }
    public function editCatalog($id) {
        $catalog = ProductCatalog::find($id);
         
        return view('manage.settings.CreateOrUpdateCatalog',compact('catalog'));
    }
    
    
    public function updateCatalog(ProductCatalogRequest $productCatalogRequest) {
        $certificates = ProductCatalog::find($productCatalogRequest->id);
        $data = $productCatalogRequest->all();
        if($productCatalogRequest->hasFile('image')) {
             
            $file = $productCatalogRequest->file('image');
            $filename = $productCatalogRequest->image->getClientOriginalName();
            $path = $file->storeAs('public/catalog/', $filename);
            $data['image'] = $filename;
             
        } else {
            $data['image'] = $productCatalogRequest->input('old_image');
        }
        $certificates->update($data);
        return redirect()->route('manage.settings.catalog')->with('message', 'Catalog has been updated successfully');
    }

    public function deleteCatalog($id) {
        
        $catalog = ProductCatalog::findOrFail($id);
        if($catalog->image) {

            $path = storage_path().'/app/public/catalog/'.$catalog->image;
            if(file_exists($path)) {
                unlink($path);
                $catalog->delete();
            }
        }
        return redirect()->route('manage.settings.catalog')->with('message', 'Catalog has been delete successfully');
    }

    public function bulkCatalogDelete(Request $request) {
         
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $catalog = ProductCatalog::findOrFail($request->checkboxArray);
            
            foreach($catalog as $value) {
                
                 
                if($value->image) {
                    $path = storage_path().'/app/public/catalog/'.$value->image;
                    if(file_exists($path)) {
                        unlink($path);
                        $value->image()->delete();
                    }
                }
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected catalog have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select catalog to be deleted."));
        }
        return redirect(route('manage.settings.catalog'));
    }
    
     // pincode Section
     public function getPincode() {
        $pincods = ProductPinCode::all();
        return view('manage.settings.pincode',compact('pincods'));
    }

    public function createPincode() {
        return view('manage.settings.CreateOrUpdatePincode');
    }

     

    public function storePincode(ProductPincodeRequest $productPincodeRequest) {
        $data = $productPincodeRequest->all();
        ProductPinCode::create($data);
        return redirect()->route('manage.settings.pincode')->with('message', 'Pincode has been save successfully');
    }

    public function editPincode($id) {
        $pincode = ProductPinCode::find($id);
        return view('manage.settings.CreateOrUpdatePincode',compact('pincode'));
    }
    
    public function updatePincode(ProductPincodeRequest $productPincodeRequest) {
        $pincode = ProductPinCode::find($productPincodeRequest->id);
        $data = $productPincodeRequest->all();
        $pincode->update($data);
        
        return redirect()->route('manage.settings.pincode')->with('message', 'Pincode has been updated successfully');
    }

    public function deletePincode($id) {

        $pincode = ProductPinCode::findOrFail($id);
        $pincode->delete();
        return redirect()->route('manage.settings.pincode')->with('message', 'Pincode has been delete successfully');
    }
    public function bulkPincodeDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $pincode = ProductPinCode::findOrFail($request->checkboxArray);
            foreach($pincode as $value) {
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected pincode have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select pincode to be deleted."));
        }
        return redirect(route('manage.settings.pincode'));
    }


    // style Section
    public function getStyle() {
        $styles = Category::with('category')->where('category_id','<>','0')->get();
       
        return view('manage.settings.style',compact('styles'));
    }

    public function createStyle() {
       
        $location_id = Auth::user()->location_id;
        $category = Category::where('is_active','1')->where('category_id','0')->where('location_id',$location_id)->get()->pluck('name','id');
       
        return view('manage.settings.CreateOrUpdateStyle',compact('category'));
    }

     

    public function storeStyle(Request $request, ProductStyleRequest $productStyleRequest) {
       
        $data = $productStyleRequest->all();
       
        $data['category_id'] =$data['category_id'];
        $data['is_active'] = $data['is_active']; 
        $data['hsn_code'] = $data['hsn_code']; 
        $input["priority"] = isset($productStyleRequest->priority) ? $productStyleRequest->priority : null;
        $input["top_category_priority"] = isset($productStyleRequest->top_category_priority) ? $productStyleRequest->top_category_priority : null;
        // $data['show_in_slider'] = $data['show_in_slider'] ? $data['show_in_slider'] : 0;
        $data["show_in_slider"] = $productStyleRequest->show_in_slider ? true : false;
        $data["show_filter_price"] = $productStyleRequest->show_filter_price ? true : false;
        $data["show_filter_metal"] = $productStyleRequest->show_filter_metal ? true : false;
        
        $data["show_filter_purity"] = $productStyleRequest->show_filter_purity ? true : false;
        $data["show_filter_gender"] = $productStyleRequest->show_filter_gender ? true : false;
        $data["show_filter_offers"] = $productStyleRequest->show_filter_offers ? true : false;
        $data["show_filter_short_by"] = $productStyleRequest->show_filter_short_by ? true : false;
        $data["is_active"] = $productStyleRequest->is_active ? true : false;
        $location_id = Auth::user()->location_id;
        $data['location_id'] = $location_id;
         
        if($productStyleRequest->hasFile('icon')) {
             
            $file = $productStyleRequest->file('icon');
            $filename = $productStyleRequest->icon->getClientOriginalName();
            $path = $file->storeAs('public/style/', $filename);
            $data['image'] = $filename;
             
        } 
        
        if($productStyleRequest->hasFile('style_image')) {
             
            $file = $productStyleRequest->file('style_image');
            $filename = $productStyleRequest->style_image->getClientOriginalName();
            $path = $file->storeAs('public/style/topcategory/', $filename);
            $data['category_img'] = $filename;
             
        }
         
        if($productStyleRequest->hasFile('banner')) {
             
            $file = $productStyleRequest->file('banner');
            $filename = $productStyleRequest->banner->getClientOriginalName();
            $path = $file->storeAs('public/style/banner/', $filename);
            $data['banner'] = $filename;
             
        } 
          
        Category::create($data);

        return redirect()->route('manage.settings.style')->with('message', 'Style has been save successfully');
    }

    public function editStyle($id) {
        $style = Category::find($id);
        $location_id = Auth::user()->location_id;
        $category = Category::where('is_active','1')->where('category_id','0')->where('location_id',$location_id)->get()->pluck('name','id');
        return view('manage.settings.CreateOrUpdateStyle',compact('style','category'));
    }
    
    public function updateStyle(ProductStyleRequest $productStyleRequest) {
         

        $category = Category::find($productStyleRequest->id);
       
        $data = $productStyleRequest->all();
         
        $data['category_id'] =$data['category_id'];
        $data['hsn_code'] = $data['hsn_code'];
        
        $input["priority"] = isset($data["priority"]) ? $data["priority"] : null;
        $input["top_category_priority"] = isset($data["top_category_priority"]) ? $data["top_category_priority"] : null; 
        $data['is_active'] = $data['is_active']; 
        $data['slug'] = '';
        // $data['show_in_slider'] = isset($data['show_in_slider']) &&  $data['show_in_slider'] ? $data['show_in_slider'] : 0;
        $data["show_in_slider"] = $productStyleRequest->show_in_slider ? true : false;
        $data["show_filter_price"] = $productStyleRequest->show_filter_price ? true : false;
        $data["show_filter_metal"] = $productStyleRequest->show_filter_metal ? true : false;
        
        $data["show_filter_purity"] = $productStyleRequest->show_filter_purity ? true : false;
        $data["show_filter_gender"] = $productStyleRequest->show_filter_gender ? true : false;
        $data["show_filter_offers"] = $productStyleRequest->show_filter_offers ? true : false;
        $data["show_filter_short_by"] = $productStyleRequest->show_filter_short_by ? true : false;
        
        if($productStyleRequest->hasFile('icon')) {
             
            $file = $productStyleRequest->file('icon');
            $filename = $productStyleRequest->icon->getClientOriginalName();
            $path = $file->storeAs('public/style/', $filename);
            $data['image'] = $filename;
             
        } else {
            $data['image'] = $productStyleRequest->input('old_icon');
        }
        
        if($productStyleRequest->hasFile('style_image')) {
             
            $file = $productStyleRequest->file('style_image');
            $filename = $productStyleRequest->style_image->getClientOriginalName();
            $path = $file->storeAs('public/style/topcategory/', $filename);
            $data['category_img'] = $filename;
             
        } else {
            $data['category_img'] = $productStyleRequest->input('old_style_image');
        }
         
        if($productStyleRequest->hasFile('banner')) {
             
            $file = $productStyleRequest->file('banner');
            $filename = $productStyleRequest->banner->getClientOriginalName();
            $path = $file->storeAs('public/style/banner/', $filename);
            $data['banner'] = $filename;
             
        } else {
            $data['banner'] = $productStyleRequest->input('old_banner');
        }

        if($productStyleRequest->remove_banner) {
            if($category->banner) {
                
                if(file_exists('storage/style/banner/'.$category->banner)) {
                        
                    unlink('storage/style/banner/'.$category->banner);
                    $data['banner'] = '';
                }
            }
        }
        
        if($productStyleRequest->remove_photo) {
            if($category->image) {
                
                if(file_exists('storage/style/'.$category->image)) {
                        
                    unlink('storage/style/'.$category->image);
                    $data['image'] = '';
                }
            }
        }
        if($productStyleRequest->remove_category_photo) {
            if($category->category_img) {
                
                if(file_exists('storage/style/topcategory/'.$category->category_img)) {
                        
                    unlink('storage/style/topcategory/'.$category->category_img);
                    $data['category_img'] = '';
                }
            }
        }

        $category->update($data);
        return redirect()->route('manage.settings.style')->with('message', 'Style has been updated successfully');
    }

    public function deleteStyle($id) {

         
        $category = Category::findOrFail($id);
         
        if($category->image) {

            $path = storage_path().'/app/public/style/'.$category->image;
            if(file_exists($path)) {
                unlink($path);
                $category->delete();
            }
        }
        $category->delete();
        return redirect()->route('manage.settings.style')->with('message', 'Style has been delete successfully');
    }
    public function bulkStyleDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $style = ProductStyle::findOrFail($request->checkboxArray);
            foreach($style as $value) {
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected style have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select style to be deleted."));
        }
        return redirect(route('manage.settings.style'));
    }

    public function Enquires(Request $request) {
        $enquires = Enquiry::orderBy('id','DESC')->get();
       
        return view('manage.settings.enquires.index', compact('enquires'));
    }

    public function viewEnquires($id) {
        $enquire = Enquiry::with('product')->find($id);
         
        return view('manage.settings.enquires.view', compact('enquire'));
    }

    public function deleteEnquires($id) {

        $enquire = Enquiry::with('product')->find($id);
        $enquire->delete();
        return redirect(route('manage.settings.enquires'));
    }

    public function bulkEnquiryDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $enquiry = Enquiry::findOrFail($request->checkboxArray);
            foreach($enquiry as $value) {
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected enquiry have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select enquiry to be deleted."));
        }
        return redirect(route('manage.settings.enquires'));
    }
    
     
    public function shopByMetalStone()
    {
        $shopByMetal = ShopByMetalStone::all();
        return view('manage.settings.shopbymetalstone',compact('shopByMetal'));
    }
    public function createShopByMetal() {
        $categories = Category::where('is_active',true)->where('category_id','0')->get()->pluck('name','id');

        return view('manage.settings.CreateOrUpdateShopbyMetal',compact('categories'));
    }
    
    public function storeShopByMetalStone(ShopByMetalRequest $ShopByMetalRequest) {
        $data = $ShopByMetalRequest->all();
        ShopByMetalStone::create($data);
        return redirect()->route('manage.settings.shop_by_metal_stone')->with('message', 'Shop Metal has been save successfully');
    }

    public function editShopByMetalStone($id) {
        $shop_by_metal_stone = ShopByMetalStone::find($id);
        $categories = Category::where('is_active',true)->where('category_id','0')->get()->pluck('name','id');

        return view('manage.settings.CreateOrUpdateShopbyMetal',compact('shop_by_metal_stone','categories'));
    }

    

    public function updateShopByMetal(ShopByMetalRequest $ShopByMetalRequest) {
        $shopbymetalstone = ShopByMetalStone::find($ShopByMetalRequest->id);
        $data = $ShopByMetalRequest->all();
        $shopbymetalstone->update($data);
        return redirect()->route('manage.settings.shop_by_metal_stone')->with('message', 'Shop Metal has been updated successfully');
    }

    public function deleteShopByMetal($id) {

        $shopbymetalstone = ShopByMetalStone::findOrFail($id);
        $shopbymetalstone->delete();
        return redirect()->route('manage.settings.shop_by_metal_stone')->with('message', 'Shop Metal has been delete successfully');
    }

    public function bulkShopByMetalDelete(Request $request) {
        if(isset($request->delete_all) && !empty($request->checkboxArray)) {
            $shop_by_metal = ShopByMetalStone::findOrFail($request->checkboxArray);
            
            foreach($shop_by_metal as $value) {
                
                 
                
                $value->delete();
            }
            session()->flash('testimonial_deleted', __("The selected shop metal have been deleted."));
        } else {
            session()->flash('testimonial_not_deleted', __("Please select shop metal to be deleted."));
        }
        return redirect(route('manage.settings.shop_by_metal_stone'));
    }

    

    
    
     
}
