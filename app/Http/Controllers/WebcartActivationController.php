<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Cache\Factory;
use App\Setting;
use Illuminate\Support\Facades\Artisan;

class WebcartActivationController extends Controller
{
    private $api_url = 'https://api.envato.com/v3/market/author/sale';
    private $item_id = 23437961;
    public $error_message = null;

    public function activation() {
        if(config('settings.webcart_package') !== '1') {
        	return view('activate.activate');
        }
        return view('errors.404');
    }

    public function demoData() {
        return view('activate.demo-data');
    }

    public function importDemoData(Request $request) {
        $this->validate($request, [
            'demo_template' => 'required'
        ]);
        try {
            $message = __('Data Reset To Default State Successfully!');
            if ( $request->demo_template != 'default' ){
                Artisan::call('demo:template', [ 'template' => $request->demo_template ] );
                $message = __('Demo Data Imported Successfully!');
            }
            session()->flash('success', $message);
            return redirect('/');
        } catch ( \Exception $e ) {
            $this->error_message = $e->getMessage();
            session()->flash('webcart_not_activated', $this->error_message);
        }

        return redirect()->back();
    }

    public function activate(Request $request, Factory $cache) {
        if(config('settings.webcart_package') === '1') {
            return view('errors.404');
        }

		$messages = [
            'purchase_code.regex'  => __('The purchase code was invalid.'),
        ];

        $this->validate($request, [
            'purchase_code' => 'required|regex:/^([a-z0-9]{8})[-](([a-z0-9]{4})[-]){3}([a-z0-9]{12})$/'
        ], $messages);

        try {
	        $ch = curl_init();
	        curl_setopt_array($ch, array(
	            CURLOPT_URL => "{$this->api_url}?code={$request->purchase_code}",
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_TIMEOUT => 20,
	            CURLOPT_HTTPHEADER => array(
	                "Authorization: Bearer df6ysAKONfdsFz9HOZ3etJ4AvtoYNCAl",
	                "User-Agent: Web-cart -Multi Store eCommerce Shopping Cart Solution by Weblizar activation"
	            )
	        ));
			$response = @curl_exec( $ch );
			if ( curl_errno( $ch ) > 0 ) {
		    	throw new \Exception( __('Failed to query Envato API:') . ' ' . curl_error( $ch ) );
			}
			/* Validation */
			$responseCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			if ($responseCode === 404) {
			    throw new \Exception( __('The purchase code was invalid.') );
			}
			if ($responseCode !== 200) {
			    throw new \Exception( __('Failed to validate code due to an error: HTTP :response_code', ['response_code' => $responseCode] ) );
			}
			$body = json_decode( $response );
			if ( $body->item->id !== $this->item_id ) {
			    throw new \Exception( __('The purchase code provided is for a different item.') );
			}
	        $request->merge(['webcart_package' => '1']);
	        foreach($request->except('_token') as $key => $value) {
	            $setting = Setting::where('key', $key)->first();
	            if($setting) {
	                $setting->value = $value;
	                $setting->save();
	            }
	        }
	        $cache->forget('settings');
	        $message = __('Your package is activated successfully.');
	    	session()->flash('success', $message);
	    	if ( !empty( $request->demo_data ) && $request->demo_data == 'on' ){
                return redirect(route('webcart.demo_data'));
            }
	    	return redirect('/');
        } catch ( \Exception $e ) {
            $this->error_message = $e->getMessage();
    		session()->flash('webcart_not_activated', $this->error_message);
        }

        return redirect()->back();
    }
}
