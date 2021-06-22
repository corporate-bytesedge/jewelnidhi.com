<div class="row">
    <div class="col-md-12">

        @if(session()->has('order_updated'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('order_updated')}}</strong>
            </div>
        @endif

        @if(session()->has('order_not_updated'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <strong>{{session('order_not_updated')}}</strong>
            </div>
        @endif

        @include('includes.form_errors')

        <!-- @can('read', App\Location::class)
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-12">
                @lang('Location:') 
                <strong>
                    @if($order->location)
                        {{$order->location->name}}
                    @else
                        @lang('None')
                    @endif
                </strong>
            <br><br>
            </div>
        </div>
        @endcan -->

        
            <style>
                .invoice-title h2, .invoice-title h3 {
                    display: inline-block;
                }
                .table > tbody > tr > .no-line {
                    border-top: none;
                }
                .table > thead > tr > .no-line {
                    border-bottom: none;
                }
                .table > tbody > tr > .thick-line {
                    border-top: 2px solid;
                }
                #invoice-logo {
                    width: 70px;
                    height: auto;
                }
            </style>
            <div class="row">
                <div class="col-md-12">
                    
                    
                    <div class="row">
                        <div class="col-md-6">
                            <address>
                            <strong>@lang('Billed To:')</strong><br>
                                {{$order->address->first_name}} {{$order->address->last_name}}<br>
                                <strong>@lang('Phone:')</strong> {{$order->address->phone}}<br>
                                <!-- <strong>@lang('Email:')</strong> {{$order->address->email}} -->
                            </address>
                        </div>
                        <div class="col-md-6 text-right">
                            <address>
                            <strong>@lang('Shipping Address:')</strong><br>
                                {{$order->address->first_name}} {{$order->address->last_name}}<br>
                                {{$order->address->address}}<br>
                                {{$order->address->city}}, {{$order->address->state}} - {{$order->address->zip}}<br>
                                {{$order->address->country}}.
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <address>
                                <strong>@lang('Payment Status:')</strong>
                                 
                                {{$order->paid!='0' ? __('Paid') : __('Failed')}}<br>
                                <strong>@lang('Payment Method:')</strong>
                                {{$order->payment_method}}<br>
                                <strong>@lang('Order Status:')</strong>
                                @if($order->is_processed =='0')
                                    @php  echo 'Pending' @endphp
                                @elseif($order->is_processed =='1')
                                    @php  echo 'Refunded' @endphp
                                    
                                @elseif($order->is_processed =='2')

                                    @php  echo 'Cancelled' @endphp
                                     
                                @elseif($order->is_processed =='3')

                                    @php  echo 'Delivered' @endphp
                                     
                                
                                @elseif($order->is_not_online_payment() && $order->paid == 0)
                                    @php  echo 'Failed' @endphp
                                 
                                @endif

                                 
                                <br>
                            </address>
                        </div>
                        <div class="col-md-6 text-right">
                            <address>
                                <strong>@lang('Order Date:')</strong><br>
                                {{$order->created_at->toFormattedDateString()}}<br><br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>@lang('Order summary')</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <td><strong>@lang('Item')</strong></td>
                                            <td class="text-center"><strong>@lang('Price')</strong></td>
                                            <td class="text-center"><strong>@lang('Quantity')</strong></td>
                                            <td class="text-right"><strong>@lang('Total')</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                    $subTotal = 0 ;
                                    $total = 0; 
                                    $GST = 0 ;
                                    $VA = 0 ;
                                    @endphp
                                        @foreach($order->products as $product)
                                         @php 
                                             $subTotal += $product->pivot->total - ($product->vat_rate + $product->gst_three_percent);
                                             $GST += $product->gst_three_percent;
                                             $total += $product->pivot->total ;
                                             $VA += $product->vat_rate;
                                         @endphp
                                            <tr>
                                                <td>{{$product->name}}
                                                    @if($product->pivot->spec && ($specs = unserialize($product->pivot->spec)))
                                                        <br>
                                                        @foreach($specs as $key => $spec)
                                                            <em>{{$spec['name']}}:</em> {{$spec['value']}}@if(!$loop->last), @endif
                                                        @endforeach
                                                    @endif
                                                    <!-- <br>@lang('SKU'):<strong>{{$product->sku}}</strong> -->
                                                </td>
                                                <td class="text-center"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($product->pivot->unit_price) }} </td>
                                                <td class="text-center">{{$product->pivot->quantity}}</td>
                                                <td class="text-right"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($product->pivot->total) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>@lang('Subtotal')</strong></td>
                                            <td class="thick-line text-right"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>@lang('GST')</strong></td>
                                            <td class="no-line text-right"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($GST) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>@lang('Value Added')</strong></td>
                                            <td class="no-line text-right"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($VA) }}</td>
                                        </tr>

                                        
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>@lang('Shipping Cost')</strong></td>
                                            <td class="no-line text-right">{{isset($order->shipping_cost) ? currency_format($order->shipping_cost, $order->currency) : currency_format(0, $order->currency)}}</td>
                                        </tr>
                                        @if($order->coupon_amount && $order->coupon_amount > 0)
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>@lang('Coupon Discount')</strong></td>
                                            <td class="no-line text-right"><i class="fa fa-rupee"></i> {{ $order->coupon_amount, $order->currency }} </td>
                                        </tr>
                                        @endif
                                        @if($order->wallet_amount && $order->wallet_amount > 0)
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>@lang('Wallet Used')</strong></td>
                                            <td class="no-line text-right"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($order->wallet_amount) }} </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>@lang('Total')</strong></td>
                                            <td class="no-line text-right"><i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($total - $order->wallet_amount - $order->coupon_amount + $order->shipping_cost + ($order->total * $order->tax) / 100) }} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         
        <hr>

        {!! Form::model($order, ['method'=>'patch', 'action'=>['ManageOrdersController@update', $order->id], 'id'=>'update-form-'.$order->id]) !!}

       

        <div class="row">
            <div class="col-md-6">
                <label>Order Process</label>
                <select name="is_processed" class="form-control" id="is_processed">
                    <option {{ $order->is_processed == '0' ? 'selected="selected"' : '' }} value="0">Pending</option>
                    <option  {{ $order->is_processed == '1' ? 'selected="selected"' : '' }} value="1">Refunded</option>
                    <option {{ $order->is_processed == '2' ? 'selected="selected"' : '' }} value="2">Cancelled</option>
                    <option {{ $order->is_processed == '3' ? 'selected="selected"' : '' }} value="3">Delivered</option>
                </select>
            </div>
        </div>
      
        <hr>
        
   

        <a href="" class='btn btn-primary col-xs-3 col-sm-3 pull-left'
            onclick="
                    if(confirm('@lang('Are you sure you want to update this?')')) {
                    event.preventDefault();
                    $('#update-form-{{$order->id}}').submit();
                    } else {
                    event.preventDefault();
                    }
                    "
            >@lang('Update')</a>

        {!! Form::close() !!}

    </div>
</div>