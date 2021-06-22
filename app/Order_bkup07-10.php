<?php

namespace App;

use App\Address;
use App\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use App\Vendor;

class Order extends Model
{
    protected $fillable = [
        'is_processed', 'stock_regained','payment_gatway_amount', 'total', 'tax', 'shipping_cost', 'currency', 'user_id', 'customer_id', 'vendor_id', 'status', 'processed_date', 'payment_method', 'transaction_id', 'address_id', 'location_id', 'paid', 'payment_date', 'coupon_code','coupon_amount', 'wallet_amount', 'receiver_detail', 'quantity', 'tracking_id', 'delivery_service'
    ];

    public function products() {
        return $this->belongsToMany('App\Product')->withPivot(['quantity', 'total', 'unit_price', 'spec']);
    }
    public function vendor() {
        return $this->belongsTo('App\Vendor');
    }

    public function customer() {
        return $this->belongsTo('App\Customer');
    }

    public function address() {
        return $this->belongsTo('App\Address');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function location() {
        return $this->belongsTo('App\Location');
    }

    public function shipments() {
        return $this->belongsToMany('App\Shipment')->withTimestamps()->withPivot(['user_id']);
    }

    public function deliveryLocations() {
        return $this->belongsToMany('App\DeliveryLocation')->withTimestamps()->withPivot(['user_id']);
    }

    public function vendor_amounts() {
        return $this->hasMany('App\VendorAmount');
    }

    public function getOrderId() {
        return $this->id + 10000;
    }

    public static function createOrder($getOrder = false) {
          
        $user = Auth::user();
        if(\Session::get('wallet_amount')) {
            $user->wallet_balance = 0;
            $user->save();
        }
        $customer = Customer::findOrFail(session('customer_id'));
        if(\Session::get('vendor_id')) {
            $vendorID = \Session::get('vendor_id');
        } else {
            $vendorID = array('0'=>'0');
        }
        
        //  session()->forget('coupon_amount');
        //  session()->forget('wallet_used_amt');
        // session()->forget('totalAmount');
         
         
        if(\Session::get('coupon_amount') && $user->wallet_used == 0 ) {
             
            $paymentGatwayAmount = Cart::total(2, '.', '') - \Session::get('coupon_amount') -  $user->wallet_balance;
        } else {
            
            $paymentGatwayAmount = \Session::get('totalAmount');
        }
        
        
        $vendorArr = array_unique($vendorID);
        
        $address = $user->addresses()->save(
            new Address([
                'user_id' => $customer->user_id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'address' => $customer->address,
                'city' => $customer->city,
                'state' => $customer->state,
                'zip' => $customer->zip,
                'country' => $customer->country,
                'phone' => $customer->phone,
                'email' => $customer->email
        ]));
       
        foreach($vendorArr AS $k => $value ) {
            $order = $user->orders()->save(
                new Order ([
                    'total' => Cart::total(2, '.', ''),
                    'payment_method' => 'Razorpay',
                    'tax' => session('tax_rate'),
                    'coupon_code'=> session('coupon_code') ? session('coupon_code') : null,
                    'coupon_amount' => session('coupon_amount') ? session('coupon_amount') : 0,
                    'wallet_amount' => \Session::get('wallet_amount') ? \Session::get('wallet_amount') : 0,
                    'shipping_cost' => session('shipping_cost'),
                    'currency' => config('currency.default'),
                    'is_processed' => 0,
                    'customer_id' => $customer->id,
                    'address_id' => $address->id,
                    
                     
                    'vendor_id' => $value ? $value : 0,
                    'payment_gatway_amount' => $paymentGatwayAmount,
                    'location_id' => session('location_id')
            ]));
        }
        

        if (session('wallet_used_amt') > 0 && session('wallet_used') == 1){
            $txn_description    = 'JewelNidhi '.session('wallet_used_amt').'/- | For Order Number : #'. ($order->id + 1000);
            $txn_code           = config('wallet.codes.order');
            $left_wallet_bal = (int)$user->walletBalance() - (int)\Session::get('wallet_amount');
            
            $wallet = $user->wallets()->save(
                new Wallet ([
                    'user_id'           => $customer->user_id,
                    'transaction_code'  => $txn_code,
                    'transaction_amt'   => session('wallet_used_amt') ? session('wallet_used_amt') : 0,
                    'credit_amt'        => 0,
                    'debit_amt'         => $user->wallet_balance ? $user->wallet_balance : 0,
                    'wallet_balance'    => $left_wallet_bal,
                    'transaction_description'   => $txn_description,
                    'transaction_currency'      => config('currency.default'),
                    'wallet_amt_status'         => 1,
                ]));

            $user->wallet_balance = $left_wallet_bal;
             
            $user->save();
        }
        
        $cartItems = Cart::content();
        $total_qty = 0;
        foreach($cartItems as $cartItem) {
            $order->products()->attach($cartItem->id, [
                'quantity' => $cartItem->qty,
               
                'total' => $cartItem->total,
                'unit_price' => $cartItem->price,
                'spec' => (is_array($cartItem->options->spec) && count($cartItem->options->spec)) ? serialize($cartItem->options->spec) : NULL
            ]);
            $total_qty += $cartItem->qty;
        }
         
        $order->quantity = $total_qty;
        $order->save();
        
        if(!($order->payment_method != 'Cash on Delivery' && $order->paid == 0)) {
            
            foreach($order->products as $product) {
                if($vendor = $product->vendor) {
                    $unit_price = $product->pivot->unit_price;
                    $total_price = $product->pivot->total;
                    $product_quantity = $product->pivot->quantity;
                    if($amount_percentage = $vendor->amount_percentage) {
                        $product->vendor_amounts()->create([
                            'vendor_id' => $vendor->id,
                            'order_id' => $order->id,
                            'product_name' => $product->name,
                            'unit_price' => $unit_price,
                            'total_price' => $total_price,
                            'product_quantity' => $product_quantity,
                            'currency' => $order->currency,
                            'vendor_amount' => $total_price * ($amount_percentage / 100),
                            'status' => 'outstanding',
                            'outstanding_date' => Carbon::now()
                        ]);
                    }
                }
            }
        }

        if($getOrder) {
            return $order;
        }

        return $order->getOrderId();
    }


    public static function createOrderOld($getOrder = false) {
        $user = Auth::user();
        
        //$customer = Customer::findOrFail(session('customer_id'));
        $customer = Customer::where('user_id',$user->id)->first();
        if(!empty($customer)) {
            $address = $user->addresses()->save(
                new Address([
                    'user_id' => $customer->user_id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'address' => $customer->address,
                    'city' => $customer->city,
                    'state' => $customer->state,
                    'zip' => $customer->zip,
                    'country' => $customer->country,
                    'phone' => $customer->phone,
                    'email' => $customer->email
            ]));
        }

        if(!empty(\Session::get('vendor_id')) ) {
            $vendorID = \Session::get('vendor_id');
            $user->update(['vendor_id'=>$vendorID]);
            foreach(array_unique($vendorID) AS $k => $value ) {

                $order = $user->orders()->save(
                    new Order ([
                        'total' => Cart::total(2, '.', ''),
                        'tax' => session('tax_rate'),
                        'coupon_amount' => session('coupon_amount') ? session('coupon_amount') : 0,
                        'wallet_amount' => session('wallet_used_amt') ? session('wallet_used_amt') : 0,
                        'shipping_cost' => session('shipping_cost'),
                        'currency' => config('currency.default'),
                        'is_processed' => 0,
                        'customer_id' => isset($customer->id) !='' ? $customer->id : 1 ,
                        'vendor_id' => $value,
                        'address_id' => isset($address->id) !='' ? $address->id : 1 ,
                        'payment_method' => session('payment_method'),
                        'location_id' => session('location_id')
                ]));

                $cartItems = Cart::content();
                $total_qty = 0;
                foreach($cartItems as $key => $cartItem) {

                    if($cartItem->options->vendor_id == $value) {
                        $order->products()->attach($cartItem->id, [
                            'quantity' => $cartItem->qty,
                            'total' => $cartItem->total,
                            'unit_price' => $cartItem->price,
                            'spec' => (is_array($cartItem->options->spec) && count($cartItem->options->spec)) ? serialize($cartItem->options->spec) : NULL
                        ]);
                        $total_qty += $cartItem->qty;
                        $order->quantity = $total_qty;
                        $order->save();
                    }
                    
                }
                
            }
        }
        
        

        if (session('wallet_used_amt') > 0 && session('wallet_used') == 1){
            $txn_description    = 'Dr. W.A. '.session('wallet_used_amt').'/- | For Order Number : #'. ($order->id + 1000);
            $txn_code           = config('wallet.codes.order');
            $left_wallet_bal = (float)$user->walletBalance() - (float)session('wallet_used_amt');
            $wallet = $user->wallets()->save(
                new Wallet ([
                    'user_id'           => $customer->user_id,
                    'transaction_code'  => $txn_code,
                    'transaction_amt'   => session('wallet_used_amt') ? session('wallet_used_amt') : 0,
                    'credit_amt'        => 0,
                    'debit_amt'         => session('wallet_used_amt') ? session('wallet_used_amt') : 0,
                    'wallet_balance'    => $left_wallet_bal,
                    'transaction_description'   => $txn_description,
                    'transaction_currency'      => config('currency.default'),
                    'wallet_amt_status'         => 1,
                ]));

            $user->wallet_balance = $left_wallet_bal;
            $user->save();
        }

        

        if(!($order->payment_method != 'Cash on Delivery' && $order->paid == 0)) {
            foreach($order->products as $product) {
                if($vendor = $product->vendor) {
                    $unit_price = $product->pivot->unit_price;
                    $total_price = $product->pivot->total;
                    $product_quantity = $product->pivot->quantity;
                    if($amount_percentage = $vendor->amount_percentage) {
                        $product->vendor_amounts()->create([
                            'vendor_id' => $vendor->id,
                            'order_id' => $order->id,
                            'product_name' => $product->name,
                            'unit_price' => $unit_price,
                            'total_price' => $total_price,
                            'product_quantity' => $product_quantity,
                            'currency' => $order->currency,
                            'vendor_amount' => $total_price * ($amount_percentage / 100),
                            'status' => 'outstanding',
                            'outstanding_date' => Carbon::now()
                        ]);
                    }
                }
            }
        }

        if($getOrder) {
            return $order;
        }

        return $order->getOrderId();
    }

    public static function createOrderWithPayment($getOrder = false) {
        $user = Auth::user();
        $customer = Customer::findOrFail(session('customer_id'));

        $address = $user->addresses()->save(
            new Address([
                'user_id' => $customer->user_id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'address' => $customer->address,
                'city' => $customer->city,
                'state' => $customer->state,
                'zip' => $customer->zip,
                'country' => $customer->country,
                'phone' => $customer->phone,
                'email' => $customer->email
        ]));

        $order = $user->orders()->save(
            new Order ([
                'total' => Cart::total(2, '.', ''),
                'tax' => session('tax_rate'),
                'coupon_amount' => session('coupon_amount') ? session('coupon_amount') : 0,
                'wallet_amount' => session('wallet_used_amt') ? session('wallet_used_amt') : 0,
                'shipping_cost' => session('shipping_cost'),
                'currency' => config('currency.default'),
                'is_processed' => 0,
                'paid' => 1,
                'payment_date' => Carbon::now(),
                'customer_id' => $customer->id,
                'address_id' => $address->id,
                'payment_method' => session('payment_method'),
                'location_id' => session('location_id')
        ]));

        $cartItems = Cart::content();

        foreach($cartItems as $cartItem) {
            $order->products()->attach($cartItem->id, [
                'quantity' => $cartItem->qty,
                'total' => $cartItem->total,
                'unit_price' => $cartItem->price,
                'spec' => (is_array($cartItem->options->spec) && count($cartItem->options->spec)) ? serialize($cartItem->options->spec) : NULL
            ]);
        }

        if(!($order->payment_method != 'Cash on Delivery' && $order->paid == 0)) {
            foreach($order->products as $product) {
                if($vendor = $product->vendor) {
                    $unit_price = $product->pivot->unit_price;
                    $total_price = $product->pivot->total;
                    $product_quantity = $product->pivot->quantity;
                    if($amount_percentage = $vendor->amount_percentage) {
                        $product->vendor_amounts()->create([
                            'vendor_id' => $vendor->id,
                            'order_id' => $order->id,
                            'product_name' => $product->name,
                            'unit_price' => $unit_price,
                            'total_price' => $total_price,
                            'product_quantity' => $product_quantity,
                            'currency' => $order->currency,
                            'vendor_amount' => $total_price * ($amount_percentage / 100),
                            'status' => 'earned',
                            'earned_date' => Carbon::now()
                        ]);
                    }
                }
            }
        }

        if($getOrder) {
            return $order;
        }

        return $order->getOrderId();
    }

    public function createVendorPayments() {
        $order = $this;
        if(!($order->payment_method != 'Cash on Delivery' && $order->paid == 0)) {
            foreach($order->products as $product) {
                if($vendor = $product->vendor) {
                    $unit_price = $product->pivot->unit_price;
                    $total_price = $product->pivot->total;
                    $product_quantity = $product->pivot->quantity;
                    if($amount_percentage = $vendor->amount_percentage) {
                        $product->vendor_amounts()->create([
                            'vendor_id' => $vendor->id,
                            'order_id' => $order->id,
                            'product_name' => $product->name,
                            'unit_price' => $unit_price,
                            'total_price' => $total_price,
                            'product_quantity' => $product_quantity,
                            'currency' => $order->currency,
                            'vendor_amount' => $total_price * ($amount_percentage / 100),
                            'status' => 'earned',
                            'earned_date' => Carbon::now()
                        ]);
                    }
                }
            }
        }
    }

    public function saveTransactionID($transaction_id) {
        $this->transaction_id = $transaction_id;
        $this->save();
    }

    public function is_not_online_payment() {
        return (($this->payment_method != 'Cash on Delivery') && ($this->payment_method != 'Bank Transfer'));
    }
}
