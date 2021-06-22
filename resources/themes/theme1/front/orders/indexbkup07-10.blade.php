@extends('layouts.front')

@section('title')@lang('Track Orders') - {{config('app.name')}}@endsection

@section('styles')
    <link href="{{asset('css/front-sidebar-content.css')}}" rel="stylesheet">
    @include('includes.order_tracker_style')
    <style>
        .orders-details-inner > h3 {
            padding: 6px !important;
            margin: 0px !important;
            font-size: 18px;
            border-bottom: 1px solid #eee;
        }

        .orders-details-inner > ul > li {
            float: left;
            list-style: none;
            margin-bottom: 20px;
            padding: 10px !important;
            border-right: 1px solid #eee;
        }

        .orders-details-inner ul li a {
            color: #333;
            font-size: 16px;
        }

        .orders-details {
            background-color: #f9f9f9;
            padding: 15px;
            margin-top: 15px;
        }

        .bar1, .bar2, .bar3 {
            width: 22px;
            height: 3px;
            background-color: #333;
            margin: 4px 0;
            transition: 0.4s;
        }

        .change .bar1 {
            -webkit-transform: rotate(-45deg) translate(-7px, 3px);
            transform: rotate(-45deg) translate(-7px, 3px);
        }

        .change .bar2 {
            opacity: 0;
        }

        .change .bar3 {
            -webkit-transform: rotate(45deg) translate(-7px, -3px);
            transform: rotate(45deg) translate(-7px, -3px);
        }

        .btn-toggle-close a {
            margin-top: 20px !important;
            display: block;
        }

        .orders-details-inner-sub-2 {
            margin-top: 15px;
        }

        .orders-details-inner-sub-2 img {
            width: 60px;
            float: left;
            margin-right: 15px;
        }

        .orders-details-inner-sub-2 h4 a {
            color: #888;
        }

        .orders-details-inner-sub-2 p {
            padding-top: 0px;
            font-size: 14px;
            color: #333;
        }

        @media (max-width: 767px) {
            .orders-details-inner-sub-2 {
                height: 100%;
                display: block;
                margin: 40px auto;
            }
        }

        .panel-body-row {
            text-align: left;
        }

        .img-box {
            text-align: center;
        }

        .img-box .product-img {
            height: 80px;
            width: auto !important;
        }

        .product-img {
            width: 100% !important;
        }

        .grand-total p {
            text-align: left;
            padding: 0px;
        }

        .panel-heading, .panel-body {
            border: 1px solid #e5e5e5;
        }

        .panel-heading {
            border-bottom: none;
        }

        .orders-details {
            border: 1px solid #158cba;
            border-radius: 5px;
        }

        .media-body {
            text-align: center;
        }

        .order_detail_item_name {
            color: #444;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function myFunction(x) {
            
            x.classList.toggle("change");
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row dashboard-page">
            
                @include('partials.front.sidebar')
                <div class="col-md-9 content">
                        <div class="page-title">
                            <h2>@lang('Your Orders')</h2>
                        </div>
                        <div class="card">
                           
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <table class="table table-borderless ">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>@lang('Order No.')</th>
                                                <th>@lang('Order Date')</th>
                                                <!-- <th>@lang('Products')</th> -->
                                                <th>@lang('Order Status')</th>
                                                <th>@lang('Payment Status')</th>
                                                <th>@lang('Total')</th>
                                                
                                                <th>@lang('Invoice')</th>
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php 
                                            $orderTotal = 0;
                                            $totOrder = 0;
                                            $orderTotalPrice = 0;
                                            
                                        @endphp
                                        @foreach($orders as $key => $order)
                                        
                                      
                                        
                                            <tr>
                                                
                                                <td>
                                                    <a href="javascript:void();" class="btn-toggle-close" data-toggle="modal" data-target="#multiCollapseExample{{$key+1}}">#{{$order->getOrderId()}}</a>
                                                </td>
                                                <td>
                                                    {{$order->created_at->toFormattedDateString()}}
                                                </td>
                                                
                                                <td>
                                                    @if($order->is_processed)
                                                        <span class="text-success">@lang('Delivered')</span>
                                                        @else
                                                            @if($order->stock_regained)
                                                                <span class="text-primary">@lang('Cancelled')</span>
                                                            @elseif($order->is_not_online_payment() && $order->paid == 0)
                                                                <strong class="text-warning">@lang('Failed')</strong>
                                                            @else
                                                                <strong class="text-danger">@lang('Pending')</strong>
                                                            @endif
                                                    @endif
                                                </td>
                                            
                                                <td>
                                                    @if($order->is_not_online_payment() && $order->paid == 0)
                                                        <span class="text-warning">@lang('Failed')</span>
                                                    @else
                                                        @if($order->paid)
                                                            <span class="text-success">@lang('Paid')</span>
                                                        @else
                                                            <span class="text-danger">@lang('Unpaid')</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                @php 
                                                    $orderTotal = $order->shipping_cost + $order->total - $order->wallet_amount - $order->coupon_amount + ($order->total * $order->tax) / 100;
                                                    $totOrder += $orderTotal;
                                                @endphp
                                                ₹ {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($orderTotal) }}
                                                
                                                 
                                                </td>

                                                

                                                <td>
                                                @if($order->is_processed == 1)
                                            
                                                    <a target="_blank" title="View Invoice" class ="btn btn-info btn-sm" href="{{route('front.orders.show', ['id'=>$order->id])}}"><i class="fa fa-eye"></i></a>
                                                @else
                                                    -
                                                @endif
                                                </td>

                                                <!-- <td>
                                                    @if(isset($order->vendor->user_id) && $order->vendor->user_id == \Auth::user()->id && Auth::user()->can('update', App\Order::class)) 
                                                        <a title="Edit" class ="btn btn-info btn-sm" href="{{route('front.orders.edit', ['id'=>$order->id])}}"><i class="fa fa-pencil"></i></a>
                                                    @endif
                                                
                                                    {!! Form::model($order, ['method'=>'patch', 'action'=>['FrontOrdersController@hide', $order->id], 'id'=> 'hide-form-'.$order->id, 'style'=>'display: none;']) !!}
                                                    {!! Form::close() !!}
                                                    @if(Auth::user()->can('delete', App\Order::class))
                                                    <a href="" class='btn btn-sm btn-danger'
                                                    onclick="
                                                            if(confirm('@lang('Are you sure you want to delete this?')')) {
                                                            event.preventDefault();
                                                            $('#hide-form-{{$order->id}}').submit();
                                                            } else {
                                                            event.preventDefault();
                                                            }
                                                            "
                                                    ><i class="fa fa-trash"> </i></a>
                                                    @endif
                                                </td> -->
                                            </tr>
                                            <td>
                                            <div class="modal fade" id="multiCollapseExample{{$key+1}}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        @php
                                                            $tracking_data = '';
                                                            if(!empty( $order->tracking_id ) ){
                                                                $tracking_data = '<span class="pull-right">'.__("Track Your Order Using this Tracking ID :").' <strong>'. $order->tracking_id.'</strong></span>';
                                                            }
                                                        @endphp
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" style="color:#000;" id="exampleModalLabel"><strong> @lang('Order') #{{$order->getOrderId()}} </strong> {!! $tracking_data !!}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead  class="thead-dark">
                                                                        <tr>
                                                                            
                                                                            <th>@lang('Image:')</th>
                                                                            <th>@lang('Name:')</th>
                                                                            <th>@lang('Price:')</th>
                                                                            <th>@lang('Qty:')</th>
                                                                            <th>@lang('Total:')</th>
                                                                            
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @php 
                                                                        $totalPrice = 0;
                                                                        $orderTotal = 0;
                                                                        $subTotal = 0;
                                                                        $GST = 0 ;
                                                                        $valueAdded = 0;
                                                                        $cartTotal = 0;
                                                                        $totalAmount = 0;
                                                                    @endphp
                                                                        @foreach($order->products as $product)
                                                                        
                                                                            @php 
                                                                                $GST += $product->gst_three_percent;
                                                                                $valueAdded += $product->vat_rate;

                                                                                if($product->product_discount != null) {
                                                                                    $totalPrice = $product->new_price * $product->pivot->quantity ;
                                                                                } else {
                                                                                    $totalPrice = $product->old_price * $product->pivot->quantity ;
                                                                                }
                                                                                $orderTotal +=$totalPrice;
                                                                            @endphp

                                                                        <tr>
                                                                        
                                                                            <td>
                                                                                <a target="_blank" href="{{route('front.product.show', [$product->slug])}}">
                                                                                    @if($product->photo)
                                                                                        @php
                                                                                            $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 150);
                                                                                        @endphp
                                                                                        <img class="img-responsive product-img" height="50" width="50"   src="{{$image_url}}" alt="{{$product->name}}"  />
                                                                                    @else
                                                                                        <img src="https://via.placeholder.com/150x150?text=No+Image"  class="img-responsive product-img" alt="{{$product->name}}" />
                                                                                    @endif
                                                                                </a>
                                                                            </td>
                                                                            <td><a target="_blank" href="{{route('front.product.show', [$product->slug])}}">{{$product->name}}</a></td>
                                                                            
                                                                            <td> ₹{{ isset($totalPrice) && $totalPrice!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($totalPrice) : '0' }}
                                                                            </td>
                                                                            <td>{{ $product->pivot->quantity }}</td>
                                                                            <td>
                                                                            @php 
                                                                            $totalPriceAmount =  $totalPrice * $product->pivot->quantity;
                                                                            @endphp
                                                                            ₹{{ isset($totalPriceAmount) && $totalPriceAmount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($totalPriceAmount) : '0' }}
                                                                            </td>
                                                                        </tr>   
                                                                        @endforeach

                                                                        @php 
                                                                         
                                                                             
                                                                            $subTotal = $orderTotal - $GST - $valueAdded;
                                                                            $cartTotal += config('settings.shipping_cost') + $subTotal + $GST + $valueAdded ;
                                                                        @endphp
                                                                        <tr>
                                                                            <td align="right" colspan="4"><strong>@lang('Sub Total:')</strong></td>
                                                                            <th> 
                                                                                ₹{{ isset($subTotal) && $subTotal!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal) : '0' }}
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  align="right" colspan="4"><strong>@lang('GST')</strong></td>
                                                                            <th> 
                                                                                ₹{{ isset($GST) && $GST!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format(round($GST)) : '0' }}
                                                                            </th>
                                                                        </tr>

                                                                        <tr>
                                                                            <td  align="right" colspan="4"><strong>@lang('Value Added')</strong></td>
                                                                            <th> 
                                                                                ₹{{ isset($valueAdded) && $valueAdded!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format(round($valueAdded)) : '0' }}
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  align="right" colspan="4"><strong>@lang('Order Total:')</strong></td>
                                                                            <th> 
                                                                                ₹{{ isset($orderTotal) && $orderTotal!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($orderTotal) : '0' }}
                                                                            
                                                                            </th>
                                                                        </tr>
                                                                        <!-- <tr>
                                                                            <td  align="right" colspan="4"><strong>@lang('Tax:')</strong></td>
                                                                            <th> {{$order->tax}} % </th>
                                                                        </tr> -->
                                                                        <tr>
                                                                            <td  align="right" colspan="4"><strong>@lang('Shipping Cost:')</strong></td>
                                                                            <th> {{isset($order->shipping_cost) ? currency_format($order->shipping_cost, $order->currency) : currency_format(0, $order->currency)}} </th>
                                                                        </tr>
                                                                         
                                                                        @if($order->wallet_amount && $order->wallet_amount > 0)
                                                                            <tr>
                                                                                <td  align="right" colspan="4"><strong>@lang('Wallet Used:')</strong></td>
                                                                                <th> 
                                                                                ₹ {{ isset($order->wallet_amount) && $order->wallet_amount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->wallet_amount) : '0' }}
                                                                                </th>
                                                                            </tr>
                                                                        @endif
                                                                        @if($order->coupon_amount && $order->coupon_amount > 0)
                                                                            <tr>
                                                                                <td  align="right" colspan="4"><strong>@lang('Coupon Discount:')</strong></td>
                                                                                <th> 
                                                                                ₹ {{ isset($order->coupon_amount) && $order->coupon_amount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->coupon_amount) : '0' }}
                                                                                
                                                                                </th>
                                                                            </tr>
                                                                    
                                                                        @endif
                                                                        @php
                                                                        
                                                                            $totalAmount = $orderTotal -  ($order->coupon_amount + $order->wallet_amount);
                                                                            
                                                                        @endphp
                                                                    
                                                                        <tr>
                                                                            <td  align="right" colspan="4"><strong>@lang('Total After Discount:')</strong></td>
                                                                            <th> 
                                                                            
                                                                            ₹{{ isset($totalAmount) && $totalAmount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($totalAmount) : '0' }}
                                                                              </th>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>
                                            </td>                
                                        @endforeach
                                        
                                        <!-- <tr>
                                            <th colspan="3">@lang('Order Total:')</th>
                                            <td ><strong>{{currency_format($totOrder)}}</strong></td>
                                        </tr> -->
                                            
                                            
                                        
                                        </tbody>
                                    </table>
                                </div>
                                
                                        
                            
                                <div class="clearfix"></div>
                                
                            
                            </div>
                        </div>
                </div>
             
        </div>
          
    </div>
    <!-- Modal -->

@endsection
