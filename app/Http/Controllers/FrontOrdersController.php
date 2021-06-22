<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Vendor;
use App\User;
use App\Shipment;
use Carbon\Carbon;
use App\Product;
use App\VendorAmount;

class FrontOrdersController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        
        $vendor = Auth::user()->isApprovedVendor();
       

        if ($vendor){
                
            $vendor_id = $vendor->id;
            
            $orders = Order::whereHas('products', function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })->get();
            
            // $orders = Order::where('location_id', Auth::user()->location_id);
            // $orders->whereHas('products', function($query) use ($vendor_id) {
            //     $query->where('vendor_id', $vendor_id);
             
             
            
        }else{
             
            $orders = Order::where('user_id', $user_id)->where('hide', false)->orderBy('id', 'desc')->paginate(15);
        }
        
         
        return view('front.orders.index', compact('orders'));
    }
    public function edit($id) {
       
        $order = Order::where('id', $id)->first();
        $shipments = Shipment::all();
         
        return view('front.orders.edit', compact('order','shipments'));
    }
    public function show($id)
    {
        
        $order = Order::with('vendor','products')->where('is_processed', 3)->where('hide', false)->findOrFail($id);
        
        //dd($order);
        
    //     $vendor =  Order::whereHas('vendor', function ($query) use($order)  {
    //         $query->where('user_id', $order->vendor_id);
    //     })->where('user_id', Auth::user()->id)->where('vendor_id', $order->vendor_id)->first();
    //    dd($vendor);
        

        return view('front.orders.invoice', compact('order'));
        // if(Auth::user()->can('view', $order)) {
             
        //     return view('front.orders.invoice', compact('order'));
        // } else {
        //     return view('errors.403');
        // }
    }
    public function update(Request $request, $id) {

        $order = Order::find($id);
        if($order->payment_method == 'Bank Transfer') {
            if($request->is_paid || $request->is_processed) {
                if(!$order->paid) {
                    $order->paid = 1;
                    $order->payment_date = Carbon::now();
                    $order->save();
                    $order->createVendorPayments();
                }
            }
        }

        if(count($order->shipments) > 0) {
            if((count(Auth::user()->shipments) < 1) || Auth::user()->can('update', Order::class) && !in_array($order->shipments()->orderBy('id', 'desc')->first()->id, Auth::user()->shipments->pluck('id')->toArray())) {
                session()->flash('order_not_updated', __("You are not allowed to deliver or pass this order to next shipment."));
                return redirect()->back();
            }
        }

        if(Auth::user()->can('manage-shipment-orders', Other::class) && Auth::user()->cannot('update', Order::class)) {
            if((count($order->shipments) < 1) || !in_array($order->shipments()->orderBy('id', 'desc')->first()->id, Auth::user()->shipments->pluck('id')->toArray())) {
                // Redirect to 404 if no permission to update order or manage shipment orders
                return view('errors.404');
            }
        }

        $input['is_processed'] = $request->is_processed ? 1 : 0;
        if($input['is_processed'] == 1) {
            if($order->is_processed != 1) {
                $input['receiver_detail'] = $request->receiver_detail ? $request->receiver_detail : '';
                $input['processed_date'] = Carbon::now();
                if($order->payment_method == 'Cash on Delivery') {
                    $input['payment_date'] = $input['processed_date'];
                    $input['paid'] = 1;
                }
                if($order->stock_regained) {
                    $is_stock_available = Product::isStockAvailable($order->products);
                    if(!$is_stock_available) {
                        session()->flash('order_not_updated', __("Not enough stock to process this order."));
                        return redirect()->back();
                    }
                    foreach($order->products as $product) {
                        $product->decreaseStock($product->pivot->quantity, $order->id);
                    }
                }
            }
        } elseif($request->regain_stock) {
            if($order->is_processed != 1) {
                $input['stock_regained'] = 1;
                foreach($order->products as $product) {
                    $product->increaseStock($product->pivot->quantity, $order->id);
                    if($product->vendor) {
                        $vendor_amount = VendorAmount::where('order_id', $order->id)->where('product_id', $product->id)->where('vendor_id', $product->vendor->id)->where('processed', 0)->first();
                        $vendor_amount->status = 'cancelled';
                        $vendor_amount->cancel_date = Carbon::now();
                        $vendor_amount->save();
                    }
                }
            }
        }
        $input['status'] = $request->status;
        $order->update($input);
        if(!empty($order->processed_date) && !empty($order->payment_date) && $input['is_processed'] == 1) {
           
            foreach($order->products as $product) {
                if($product->vendor_id) {
                   
                    $vendor_amount = VendorAmount::where('order_id', $order->id)->where('product_id', $product->id)->where('vendor_id', $product->vendor_id)->where('processed', 0)->first();
                    
                    // $vendor_amount->status = 'earned';
                    // $vendor_amount->earned_date = Carbon::now();
                    //$vendor_amount->save();
                }
            }
           //die;
            // Send Email for Order Processed
           

            // Order Processed Event
             

            if($order->is_processed == 1) {
                session()->flash('order_passed_to_shipment', __('The order #:order_id has been delivered.', ['order_id'=>$order->getOrderId()]));
                return redirect(route('front.orders.index'));
            }
        } elseif($order->stock_regained) {
            session()->flash('order_passed_to_shipment', __('The order #:order_id has been updated.', ['order_id'=>$order->getOrderId()]));
            return redirect(route('front.orders.index'));
        }

        session()->flash('order_updated', __("The order has been updated."));
        if($input['is_processed'] != 1) {

            if($request->shipment) {
                $shipments = Shipment::pluck('name','id')->toArray();
                if(array_key_exists($request->shipment, $shipments)) {
                    if(!$order->shipments()->wherePivot('user_id', Auth::user()->id)->get()->contains($request->shipment)) {
                        $order->shipments()->attach($request->shipment, ['user_id' => Auth::user()->id]);
                        if(Auth::user()->can('manage-shipment-orders', Other::class) && Auth::user()->cannot('update', Order::class)) {
                            $shipment = Shipment::find($request->shipment);
                            session()->flash('order_passed_to_shipment', 'The order #' .$order->getOrderId(). ' has been passed to shipment location: ' .$shipment->name. ' @ ' .$shipment->address. ', ' .$shipment->city. ', ' .$shipment->state. ' - ' .$shipment->zip. ' ' .$shipment->country);
                            // Redirect after passing shipment
                            return redirect(route('manage.index'));
                        }
                    }
                }
            }

            return redirect()->back();
        }
        return redirect(route('front.orders.index'));
    }
    public function hide($id)
    {
        
        $user_id = Auth::user()->id;
       
        $order = Order::where('id', $id)->first();
        $order->products()->delete();
        $order->delete();
        
        return redirect()->back();
    }

    public function download(Request $request)
    {
        $filename = $request->filename;
        $orderId = $request->order_id;

        $file = \App\File::where('filename', $filename)->first();

        $order = Order::where('id', $orderId)->where('user_id', Auth::user()->id)->where('paid', 1)->first();

        if(!$order) {
            return view('errors.403');
        }

        $product = $order->products()->where('file_id', $file->id)->first();

        if(!$product) {
            return view('errors.403');
        }

        try {
            $pathToFile = storage_path('app/' . $file->filename);
            $headers = array(
                'Content-Type' => 'application/pdf',
            );
            return response()->download($pathToFile, $file->original_filename, $headers);
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }
}