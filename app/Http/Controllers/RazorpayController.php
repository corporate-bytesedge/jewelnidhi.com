<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Mail\PaymentFailed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Session;
use Redirect;
use DB;
use App\Product;
use App\Order;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Events\Order\OrderPlacedEvent;

class RazorpayController extends Controller
{ 
    public function payment()
    {
        $user = \Auth::user();
        $totalAmount = \Session::get('totalAmount');

         
        $api = new Api(config('razorpay.razor_key'), config('razorpay.razor_secret'));
        $input = Input::all();
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
         $paymentAmount = $payment['amount']/100;
        if($totalAmount != $paymentAmount ) {
            
            \Session::put('wallet_amount',$user->wallet_balance);
        }
        
        
         
        $order = Order::createOrder(true);
        
        //Input items of form
       
        
        //get API Configuration 
       
        
        try {
            DB::beginTransaction();
            $is_stock_available = Product::isStockAvailable($order->products);
           
            if($is_stock_available) {
                //Fetch payment information by razorpay_payment_id
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                
                if(count($input) && !empty($input['razorpay_payment_id'])) {
                    
                    try {
                        
                        // throw new \Exception();
                        $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                         
                        if($response['status'] =='captured') {

                                // Do something here for store payment details in database...
                                foreach($order->products as $product) {
                                    $product->decreaseStock($product->pivot->quantity, $order->id);
                                }
                                $order = Order::findOrFail($order->id);
								
								if(config('settings.shipping_cost_valid_below') > Cart::total()) {
									$order->shipping_cost = config('settings.shipping_cost');
								}
                             
                                $order->paid = 1;
                                $order->payment_date = Carbon::now();
                                $order->save();
                                $order->createVendorPayments();

                                DB::commit();
                                $this->clearCart();
                                session()->flash('payment_success', __('Your order has been successfully placed. Order No. #:order_id', ['order_id'=>$order->getOrderId()]));

                            // Send Email for Order Placed
                            try {
                                Mail::to('m.bhagya@bytesedge.com')->send(new OrderPlaced($order));
                            } catch (\Exception $e) {

                            }

                            // Order Placed Event
                            event(new OrderPlacedEvent($order));
                            
                            $this->clearCart();
                            return redirect(route('thank-you'));
                            
                        } 

                    } catch (\Exception $e) {
                        dd('here');
                        session()->flash('payment_fail', __('Error processing Razorpay payment for Order No. #:order_id', ['order_id'=>$order->getOrderId()]));
                        DB::rollBack();

                        // Send Email for Payment Failed
                        try {
                            Mail::to(Auth::user()->email)->send(new PaymentFailed($order));
                            $this->clearCart();
                        } catch (\Exception $e) {}

                        return redirect('/');
                    }

                    
                }
            } else {
                throw new \Exception();
            }

        } catch (\Exception $exception) {
            session()->flash('payment_fail', __("Sorry, your order was not placed successfully."));
            DB::rollBack();
            $this->clearCart();
        }

        
    }
    public function failedPayment() {
        //dd('=====');
    }
    public function checkWallet(Request $request) {

        if($request->ajax() ) {

            $user = Auth::user()->where('id',$request->user_id)->first();
            
            if($request->value == 1 ) {
                 
                $amount = $user->wallet_balance;
                
                    
            } 
             
            return response()->json(['status'=>true,'amount' => $amount]);
        }
        
    }

    public function thankYou() {
        return view('front.thankyou');
    }

   

    private function clearCart()
    { 
        Cart::destroy();
        session()->forget('customer_id');
        session()->forget('vendor_id');
        session()->forget('payment_method');
        session()->forget('wallet_used');
        session()->forget('wallet_used_amt');
        session()->forget('singlevendor');
        session()->forget('totalAmount');
        session()->forget('coupon_amount');
        session()->forget('wallet_amount');
        session()->forget('coupon_code');
        
        
        
    }
}
