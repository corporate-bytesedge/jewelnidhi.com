<?php

namespace App\Http\Controllers;

use App\Mail\OrderProcessed;
use Illuminate\Support\Facades\Mail;
use App\Product;
use App\Order;
use App\Other;
use App\Shipment;
use App\VendorAmount;
use Illuminate\Http\Request;
use App\Http\Requests\OrdersCreateRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\Order\OrderProcessedEvent;
use App\Vendor;
use App\User;
use DataTables;
use Illuminate\Support\Str; 

class ManageOrdersController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vendor = Auth::user()->isApprovedVendor();
            if($vendor) {
                $orders = Order::where('vendor_id', $vendor->id)->orderBy('id','desc');
                
            } else {
                $orders = Order::where('location_id', Auth::user()->location_id)->orderBy('id','desc');
            }
            $vendorid = false;
            if ($request->has('search') && ! is_null($request->get('search')) ) {
                $vendorid = $request->get('search');
            }
             
                 
                return Datatables::of($orders)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        
                        if ($request->has('search') && ! is_null($request->get('search')) ) {
                           
                             
                             
                            //$this->getVendorTotal($vendorid);
                            $query->where('vendor_id', $request->get('search'));
                        }
                        if ($request->has('web_id') && ! is_null($request->get('web_id'))) {
                            $webid = $request->get('web_id');
                           
                            if(strlen($webid) > 3) {
                                
                                $query->where('id', substr($webid,2,4));

                            } else {

                                $query->where('id', $webid);
                            }
                            
                            
                            
                        }
                        if ($request->has('order_status') && ! is_null($request->get('order_status'))) {
                            $query->where('is_processed', $request->get('order_status'));
                            
                        }
                    })
                    ->addColumn('Ids', function($row){
                        return '<td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="'.$row->id.'"></td>';
                        
                        
                    })
                    ->addColumn('order_no', function($row){
                        return 10000+$row->id;
                        
                        
                    })->addColumn('order_date', function($row){
                        return $row->created_at->toFormattedDateString();
                        
                    })->addColumn('customer_name', function($row) use($orders){

                        $customer = ''; 
                        
                        if(isset($row->customer->first_name)) {
                                
                            $customer = $row->customer->first_name.' '.$row->customer->last_name; 
                                    
                        }
                        return $customer;
                        
                        
                        
                    })->addColumn('payment_status', function($row){
                        if($row->is_not_online_payment() && $row->paid == 0) {
                            
                            return "<strong class='text-warning'>Failed</strong>";
                        } else {
                            if($row->paid) {
                                return "<strong class='text-success'>Paid</strong>";
                            } else {
                                return "<strong class=text-danger'>Unpaid</strong>";
                            }
                        }
                    })->escapeColumns('payment_status')->addColumn('order_status', function($row){
                        if($row->is_processed =='0') {
                                return "<strong class='text-danger'>Pending</strong>";
                        } else if($row->is_processed=='1') {
                            return "<strong class='text-primary'>Refunded</strong>";
                        } else if($row->is_processed=='2') {
                            return "<strong class='text-danger'>Cancelled</strong>";
                        } else if($row->is_processed =='3') {
                            return "<strong class='text-success'>Delivered</strong>";
                        } else if($row->is_not_online_payment() && $row->paid == 0) {
                                return "<strong class='text-warning'>Failed</strong>";
                        } 
                        
                        return $row->customer->first_name;
                        
                    })->escapeColumns('order_status')->addColumn('total', function($row){
						$totalAmount = $row->total + $row->shipping_cost - $row->coupon_amount - $row->wallet_amount;
                        return \App\Helpers\IndianCurrencyHelper::IND_money_format($totalAmount);
                    })->escapeColumns('total')->addColumn('invoice', function($row){
                        
                        if($row->is_processed == 3) {
                            $orderid = route('front.orders.show', [$row->id]);
                            $action = "<a target = '_blank' title = 'View Invoice' class='btn btn-info btn-sm' href=".$orderid."> <i class='fa fa-eye'></i></a>"; 
                            
                            return $action;
                            
                        } else {
                            return '-';
                        }
                    })->escapeColumns('invoice')
                     ->with('totals', $this->getTotal($vendorid))
                    ->make(true);
            
            
        }
        
        if(Auth::user()->can('read', Order::class) || Auth::user()->cannot('update', Order::class)) {
            $vendor = Auth::user()->isApprovedVendor();
            if ($vendor){
                
                $vendor_id = $vendor->id;
                
                $orders = Order::where('location_id', Auth::user()->location_id);
                $orders->whereHas('products', function($query) use ($vendor_id) {
                    $query->where('vendor_id', $vendor_id);
                });
                
            }else{
                 
                $orders = Order::where('location_id', Auth::user()->location_id);
            }
            
            if(!empty(request()->s)) {

                $search = request()->s;
                $search_trimmed = trim(strtolower($search));
                $skip_search = false;
                switch ($search_trimmed) {
                    case 'delivered':
                        $orders = $orders->where('is_processed', 1);
                        $skip_search = true;
                        break;
                    case 'cancelled':
                        $orders = $orders->where('stock_regained', 1);
                        $skip_search = true;
                        break;
                    case 'failed':
                        $orders = $orders->where('payment_method', '!=', 'Cash on Delivery')->where('payment_method', '!=', 'Bank Transfer')->where('paid', 0);
                        $skip_search = true;
                        break;
                    case 'pending':
                        $orders = $orders->where('stock_regained', '!=', 1)
                        ->where('is_processed', '!=', 1);
                        break;
                    default:
                        $orders = $orders->where('status', 'LIKE', $search);
                        break;
                }

                if(!$skip_search) {
                    $orders = $orders->orWhere('id', (int)$search - 10000)
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhereHas('address', function($query) use ($search) {
                        $query->where('first_name', 'LIKE', "%$search%")
                        ->where('last_name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('user', function($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('username', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                    });
                }
            }

            if(Auth::user()->can('manage-shipment-orders', Other::class) && Auth::user()->cannot('update', Order::class)) {
                $orders = $orders->get();
                $orders = $orders->filter(function($order) {
                    return (count($order->shipments) > 0) && in_array($order->shipments()->orderBy('id', 'desc')->first()->id, Auth::user()->shipments->pluck('id')->toArray());
                });
                $sort = true;
            }

            if(isset($sort)) {
                /* Ordering */
                $orders = $orders->sortByDesc(function($order) {
                    return $order->id;
                });
            } else {
                $orders = $orders->orderby('id', 'desc');
            }

            /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $orders->count();
            }
            $orders = $orders->paginate($per_page);
            
            $vendor = Vendor::all();
           
            return view('manage.orders.index', compact('orders','vendor'));
        } else {
            abort(403);
        }
    }
    public function getTotal($vendor_id) {
       
        $vendor = Auth::user()->isApprovedVendor();
        
        if($vendor_id) {
            
            $orderTotal = \DB::table('orders')->select(\DB::raw('SUM(total) as totals'))->where('vendor_id',$vendor_id)->first();
            
        }else if($vendor) {
            $orderTotal = \DB::table('orders')->select(\DB::raw('SUM(total) as totals'))->where('vendor_id',$vendor->id)->first();
        } else {
            $orderTotal = \DB::table('orders')->select(\DB::raw('SUM(total) as totals'))->first();
        } 
         
        
        return $orderTotal;
    }

    public function getVendorTotal($vendor_id='') {
       
        if($vendor_id) {
            $orderTotal = \DB::table('orders')->select(\DB::raw('SUM(total) as totals'))->where('vendor_id',$vendor_id)->first();
            return $orderTotal;
        }
    }
    public function index1()
    {
     
        if(Auth::user()->can('read', Order::class) || Auth::user()->cannot('update', Order::class)) {
            $vendor = Auth::user()->isApprovedVendor();
            if ($vendor){
                
                $vendor_id = $vendor->id;
                
                $orders = Order::where('location_id', Auth::user()->location_id);
                $orders->whereHas('products', function($query) use ($vendor_id) {
                    $query->where('vendor_id', $vendor_id);
                });
                
            }else{
                 
                $orders = Order::where('location_id', Auth::user()->location_id);
            }
            
            if(!empty(request()->s)) {

                $search = request()->s;
                $search_trimmed = trim(strtolower($search));
                $skip_search = false;
                switch ($search_trimmed) {
                    case 'delivered':
                        $orders = $orders->where('is_processed', 1);
                        $skip_search = true;
                        break;
                    case 'cancelled':
                        $orders = $orders->where('stock_regained', 1);
                        $skip_search = true;
                        break;
                    case 'failed':
                        $orders = $orders->where('payment_method', '!=', 'Cash on Delivery')->where('payment_method', '!=', 'Bank Transfer')->where('paid', 0);
                        $skip_search = true;
                        break;
                    case 'pending':
                        $orders = $orders->where('stock_regained', '!=', 1)
                        ->where('is_processed', '!=', 1);
                        break;
                    default:
                        $orders = $orders->where('status', 'LIKE', $search);
                        break;
                }

                if(!$skip_search) {
                    $orders = $orders->orWhere('id', (int)$search - 10000)
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhereHas('address', function($query) use ($search) {
                        $query->where('first_name', 'LIKE', "%$search%")
                        ->where('last_name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('user', function($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('username', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                    });
                }
            }

            if(Auth::user()->can('manage-shipment-orders', Other::class) && Auth::user()->cannot('update', Order::class)) {
                $orders = $orders->get();
                $orders = $orders->filter(function($order) {
                    return (count($order->shipments) > 0) && in_array($order->shipments()->orderBy('id', 'desc')->first()->id, Auth::user()->shipments->pluck('id')->toArray());
                });
                $sort = true;
            }

            if(isset($sort)) {
                /* Ordering */
                $orders = $orders->sortByDesc(function($order) {
                    return $order->id;
                });
            } else {
                $orders = $orders->orderby('id', 'desc');
            }

            /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $orders->count();
            }
            $orders = $orders->paginate($per_page);
            
            $vendor = Vendor::all();
           
            return view('manage.orders.index', compact('orders','vendor'));
        } else {
            return view('errors.403');
        }
    }

    public function invoices()
    {
        if(Auth::user()->can('read', Order::class)) {
            $orders = Order::where('location_id', Auth::user()->location_id)->where('is_processed', true);

            if(!empty(request()->s)) {
                $search = request()->s;
                $orders = $orders->where('id', (int)$search - 10000)
                ->orWhereHas('address', function($query) use ($search) {
                    $query->where('first_name', 'LIKE', "%$search%")
                    ->where('last_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%");
                })
                ->orWhereHas('user', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
                });
            }

            /* Ordering */
            $orders = $orders->orderBy('id', 'desc');

            /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $orders->count();
            }
            $orders = $orders->paginate($per_page);

            return view('manage.orders.invoices', compact('orders'));
        } else {
            return view('errors.403');
        }
    }

    public function pending()
    {
        if(Auth::user()->can('read', Order::class)) {
            $orders = Order::where('location_id', Auth::user()->location_id)->where('is_processed', false)->where('stock_regained', '!=', 1)->where(function($query) {
                return $query->where('payment_method', '!=', 'Cash on Delivery')
                            ->where('payment_method', '!=', 'Bank Transfer')
                            ->where('paid', '!=', 0)
                            ->orWhere('payment_method', 'Cash on Delivery')
                            ->orWhere('payment_method', 'Bank Transfer');
            });
            $vendor = Auth::user()->isApprovedVendor();
            if ($vendor){
                $vendor_id = $vendor->id;
                $orders->whereHas('products', function($query) use ($vendor_id) {
                    $query->where('vendor_id', $vendor_id);
                });
            }
            if(!empty(request()->s)) {
                $search = request()->s;
                $orders = $orders->where('id', (int)$search - 10000)
                ->orWhere('status', 'LIKE', "%$search%")
                ->orWhereHas('address', function($query) use ($search) {
                    $query->where('first_name', 'LIKE', "%$search%")
                    ->where('last_name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%");
                })
                ->orWhereHas('user', function($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
                });
            }

            /* Ordering */
            $orders = $orders->orderBy('id', 'desc');

            /* Pagination */
            if(empty(request()->all)) {
                if(!empty(request()->per_page)) {
                    $per_page = intval(request()->per_page);
                } else {
                    $per_page = 15;
                }
            } else {
                $per_page = $orders->count();
            }
            $orders = $orders->paginate($per_page);

            return view('manage.orders.pending', compact('orders'));
        } else {
            return view('errors.403');
        }
    }

    public function show($id)
    {
        if(Auth::user()->can('read', Order::class) || Auth::user()->can('manage-shipment-orders', Other::class)) {
            $order = Order::where('location_id', Auth::user()->location_id)->where('id', $id)->firstOrFail();
             
            if(Auth::user()->can('manage-shipment-orders', Other::class) && Auth::user()->cannot('update', Order::class)) {
                if((count($order->shipments) < 1) || !in_array($order->shipments()->orderBy('id', 'desc')->first()->id, Auth::user()->shipments->pluck('id')->toArray())) {
                    // Redirect after passing shipment
                    return view('errors.404');
                }
            }

            return view('manage.orders.invoice', compact('order'));
        } else {
            return view('errors.403');
        }
    }

    public function edit($id)
    {
        $order = Order::where('location_id', Auth::user()->location_id)->where('id', $id)->firstOrFail();
        $shipments = Shipment::all();
        return view('manage.orders.edit', compact('order', 'shipments'));
         
    }

    public function update(OrdersCreateRequest $request, $id)
    {
        if(Auth::user()->can('update', Order::class) || Auth::user()->can('manage-shipment-orders', Other::class)) {
            // $order = Order::where(function($query) {
            //     return $query->where('payment_method', '!=', 'Cash on Delivery')
            //                  ->where('payment_method', '!=', 'Bank Transfer')
            //                  ->where('paid', '!=', 0)
            //                  ->orWhere('payment_method', 'Cash on Delivery')
            //                  ->orWhere('payment_method', 'Bank Transfer');
            // })->where('stock_regained', '!=', 1)->findOrFail($id);
            $order = Order::where('location_id', Auth::user()->location_id)->where('stock_regained', '!=', 1)->where('id', $id)->firstOrFail();
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
             
            $input['is_processed'] = $request->is_processed ? $request->is_processed : '';
            $input['status'] = $request->status;
            
            $order->update($input);

            if(!empty($order->processed_date) && !empty($order->payment_date) && $input['is_processed'] == 1) {
                
                foreach($order->products as $product) {
                    if($product->vendor) {
                        $vendor_amount = VendorAmount::where('order_id', $order->id)->where('product_id', $product->id)->where('vendor_id', $product->vendor->id)->where('processed', 0)->first();
                        if($vendor_amount) {

                            $vendor_amount->status = 'earned';
                            $vendor_amount->earned_date = Carbon::now();
                            $vendor_amount->save();
                        }
                        
                    }
                }
                
                // Send Email for Order Processed
                try {
                    Mail::to($order->user->email)->send(new OrderProcessed($order));
                } catch (\Exception $e) {}

                // Order Processed Event
                event(new OrderProcessedEvent($order));

                if($order->is_processed == 1) {
                    session()->flash('order_passed_to_shipment', __('The order #:order_id has been delivered.', ['order_id'=>$order->getOrderId()]));
                    return redirect(route('manage.orders.index'));
                }
            } elseif($order->is_processed == 0) {
                session()->flash('order_passed_to_shipment', __('The order #:order_id has been updated.', ['order_id'=>$order->getOrderId()]));
                return redirect(route('manage.orders.index'));
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
            return redirect(route('manage.orders.index'));
        } else {
            return view('errors.403');
        }
    }

    public function destroy($id)
    {
        
        if(Auth::user()->can('delete', Order::class)) {
            $order = Order::findOrFail($id);
            if($order->is_processed || !($order->stock_regained || ($order->payment_method != 'Cash on Delivery' && $order->paid == 0))) {
                foreach($order->products as $product) {
                    $product->sales = $product->sales - $product->pivot->quantity;
                    $product->save();
                }
            }
            $order->products()->detach();
            $order->shipments()->detach();
            $order->vendor_amounts()->where('processed', 0)->delete();
            $order->delete();
            session()->flash('order_deleted', "The order has been deleted.");
            return redirect(route('manage.orders.index'));
        } else {
            return view('errors.403');
        }
    }

    public function deleteOrders(Request $request)
    {
       
        if(Auth::user()->can('delete', Order::class)) {
            if(isset($request->delete_single)) {
                $order = Order::findOrFail($request->delete_single);
                if($order->is_processed || !($order->stock_regained || ($order->payment_method != 'Cash on Delivery' && $order->paid == 0))) {
                    foreach($order->products as $product) {
                        $product->sales = $product->sales - $product->pivot->quantity;
                        $product->save();
                    }
                }
                $order->products()->detach();
                $order->shipments()->detach();
                $order->vendor_amounts()->where('processed', 0)->delete();
                $order->delete();
                session()->flash('order_deleted', "The order has been deleted.");
            } else {
                if(!empty($request->checkboxArray)) {
                    $orders = Order::findOrFail($request->checkboxArray);
                    
                    foreach($orders as $order) {
                        if($order->is_processed || !($order->stock_regained || ($order->payment_method != 'Cash on Delivery' && $order->paid == 0))) {
                            foreach($order->products as $product) {
                                $product->sales = $product->sales - $product->pivot->quantity;
                                $product->save();
                            }
                        }
                        $order->products()->detach();
                        $order->shipments()->detach();
                        $order->vendor_amounts()->where('processed', 0)->delete();
                        $order->delete();
                    }
                    session()->flash('order_deleted', __("The selected orders have been deleted."));
                } else {
                    // session()->flash('order_not_deleted', __("Please select orders to be deleted."));
                }
            }
            return redirect(route('manage.orders.index'));
        } else {
            return view('errors.403');
        }
    }

    public function getVendorData() {
        dd('=====');
    }
}
