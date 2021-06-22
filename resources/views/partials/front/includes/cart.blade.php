 
@php  
$rowidArr = array();
$qtyArr = array();
@endphp
@if(Cart::count() > 0)

   <!-- cart main wrapper start -->
   <div class="cart-main-wrapper section">
    <div class="container">
        <div class="section-bg-color">
         
        
            
            <div class="row">
            
           
                <div class="col-lg-12">
                    <!-- Cart Table Area -->
                    <div class="cart-table ">
                        <div class="">
                            <table class="table table-bordered cartDetails" id="">
                                <thead>
                                    <tr>
                                        
                                        <th class="pro-title">@lang('Product')</th>
                                        <th class="pro-price">@lang('Price')</th>
                                        <th class="pro-quantity">@lang('Quantity')</th>
                                        <th class="pro-subtotal">@lang('Total')</th>
                                        <th class="pro-remove">@lang('Remove')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php 
                                    $productIdArr = array(); 
                                    $GST = 0;
                                    $valueAdded = 0;
                                    $cartTotal = 0;
                                    $subTotal = 0;
                                    
                                @endphp
                                @foreach($cartItems as $k=> $cartItem)
                                    @php $rowidArr[] = $cartItem->rowId;
                                    $qtyArr[] = $cartItem->qty;
                                     
                                    array_push($productIdArr,$cartItem->options->vendor_id);

                                    session(['vendor_id'=>$productIdArr]);
                                    
                                    $GST += floor($cartItem->options->gst) * $cartItem->qty ;
                                    $valueAdded += floor($cartItem->options->va) * $cartItem->qty;
                                    
                                    
                                    
                                    @endphp
                                    
                                
                                    <?php 
                                        /* $product_data = \App\Product::select('file_id','virtual','downloadable')->where('id',$cartItem->id)->get()->first()->toArray();*/
                                    ?>
                                    <tr>
                                        
                                        <td>
                                            <div class="pro-title">
                                                    <span class="imgsection">
                                                        @if($cartItem->options->has('photo')) 
                                                         @php 
                                                         $featured = public_path().'/img/'.basename($cartItem->options->photo);
                                                          
                                                         @endphp
                                                         
                                                            @if(file_exists($featured))
                                                                
                                                            <img class="img-fluid" src="{{ asset('img/'.basename($cartItem->options->photo)) }}" width="140px" alt="Product" />
                                                            @else
                                                                <img src="https://via.placeholder.com/150x150?text=No+Image" class="product-image img-responsive" width="100"  alt="" />
                                                            @endif
                                                        @endif
                                                    </span>
                                                    <span class="protitle">
                                                        <a href="/product/{{ $cartItem->options->slug }}">{{$cartItem->name}}</a>
                                                    </span>
                                            </div>
                                        

                                        </td>
                                        <td class="pro-price">
                                        <span>₹ {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($cartItem->options->unit_price) !!}</span></td>
                                        <td class="pro-quantity">
                                        <?php
                                        $productQty = \App\Product::where('id',$cartItem->id)->first();
                                         
                                        ?>
                                            <div class="pro-qty">
                                            <select name="pro_qty" class="pro_qty" id="pro_qty_{{ $k }}">
                                                
                                                <?php 
                                                    for($i = 1; $i <=$productQty->in_stock; $i++) {
                                                ?>
                                                    <option @php echo $cartItem->qty == $i ? 'selected="selected"' : ''; @endphp value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php    }
                                                ?>
                                            </select>
                                            </div>
                                        </td>
                                        <td class="pro-subtotal"><span> <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($cartItem->options->unit_price*$cartItem->qty) !!} </span></td>
                                        <td class="pro-remove">
                                                {!! Form::open(['method'=>'delete','id'=>'cartForm-'.$cartItem->rowId, 'route'=>['front.cart.destroy', $cartItem->rowId]]) !!}
                                                
                                                    {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'button','data-rowid'=>$cartItem->rowId, 'class' => 'btn btn-square removeProduct', 'style' => 'color: #fff'] )  }}
                                            
                                                {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                    
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Cart Update Option -->
                    <div class="cart-update-option d-block d-md-flex justify-content-between">
                        <div class="apply-coupon-wrapper">
                        @if(session()->has('coupon_invalid'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{session('coupon_invalid')}}
                            </div>
                        @endif
                        @if(session()->has('use_coupon_one_time'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Coupon apply only one time
                        </div>
                        {{ session()->forget('use_coupon_one_time') }}
                        @endif
                            @if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total())
                                @if(session()->has('coupon_code'))
                                    <h6 class="text-success">@lang('Coupon Applied:') <span class="text-muted">{{session('coupon_code')}}</span></h6>
                                    <h6 class="text-success">@lang('Discount:') <span class="text-muted"> {{ session('coupon_amount')  }}</span></h6>
                                @else
                                    <h4>@lang('Coupon Discount:') {{ session('coupon_amount') }}</h4>
                                @endif
                                <a id="have-coupon" href="">@lang('Change Coupon?')</a>
                        @else
                            <a id="have-coupon" href="">@lang('Have a Coupon?')</a>
                        @endif
                    
                        {!! Form::open(['method'=>'post', 'action'=>'FrontCouponsController@checkCoupon', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;', 'class'=>'d-block d-md-flex coupon-sec']) !!}
                            
                            
                            {!! Form::text('coupon', null, ['class'=>'form-control', 'placeholder'=>__('Enter Your Coupon Code'), 'required']) !!}
                            @if(\Auth::user()) 
                                {!! Form::submit(__('Apply Coupon'), ['id'=>'coupon-btn', 'class'=>'btn btn-primary', 'name'=>'submit_button']) !!}
                            @else
                                <a href="javascript:void(0)" class="btn btn-sqr d-block" data-toggle="modal" data-target="#login-modal">@lang('Apply Coupon')</a> 
                            @endif
                        {!! Form::close() !!}
                        </div>
                        {!! Form::open(['method'=>'patch','id'=>'updateCart', 'route'=>['front.cart.update']]) !!}
                            @foreach($rowidArr as $k=> $rowitem)
                                <input type="hidden" name="rowid[]" id="rowid_{{$rowitem}}" value="{{  $rowitem}}">
                                <input type="hidden" name="qty[]" id="qty_{{$rowitem}}" value="{{ $qtyArr[$k]}}">
                            @endforeach
                        
                                  
                            <!-- <div class="cart-update">
                                <button type="button" class="btn btn-sqr updateCart">Update</button>
                            </div> -->
                        {!! Form::close() !!}
                    </div>
                </div>
                
                
            </div>
             @php 
             $cal_wallet_amount = 0;
             
            $subTotal = Cart::total() - $GST - $valueAdded;
			
            
			
			
			
				$cartTotal += $subTotal + $GST + $valueAdded ;
                                                                    
        
             @endphp
            <div class="row">
                <div class="col-lg-5 ml-auto">
                    <!-- Cart Calculation Area -->
                    <div class="cart-calculator-wrapper">
                        <div class="cart-calculate-items">
                            <h6>Cart Total</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>@lang('Sub Total')</td>
                                        <td><b><i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal) !!}</b></td>
                                    </tr>

                                    <tr>
                                        <td>@lang('GST')</td>
                                        <td><i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($GST) !!}</td>
                                    </tr>

                                    <tr>
                                        <td>@lang('Value Added')</td>
                                        <td><i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($valueAdded) !!}</td>
                                    </tr>

                                    
                               

                                    
                                     
                                    <tr>
                                        <td>@lang('Shipping Cost')</td>
                                         
                                        @if(config('settings.shipping_cost_valid_below') > Cart::total())
                                            <td> 
                                            <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format(config('settings.shipping_cost')) !!}
                                            </td>
                                         @else
                                             <td> 
                                             <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format(0) !!}
                                            </td>

                                         @endif
                                         
                                    </tr>
                                     
                                    <tr class="total">
                                        <td>@lang('Total')</td>

                                        <!-- @if(session()->has('coupon_amount')) 
                                            <td class="total-amount">&#8377; {{ $cartTotal - session()->has('coupon_amount') }}</td>
                                        @else
                                        <td class="total-amount">&#8377; {{number_format($cartTotal + (Cart::total() * config('settings.tax_rate')) / 100 )}}</td>
                                        @endif -->

                                    @if(config('settings.shipping_cost_valid_below') > $cartTotal && session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < $cartTotal)
                                      <td class="total-amount">
                                      <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format(config('settings.shipping_cost') - $cal_wallet_amount + $cartTotal - session('coupon_amount') + ($cartTotal * config('settings.tax_rate')) / 100) !!}
                                     
                                      @php 
                                        config('settings.shipping_cost') - $cal_wallet_amount + $cartTotal - session('coupon_amount') + ($cartTotal * config('settings.tax_rate')) / 100;
                                      @endphp
                                      
                                      </th>
                                    
                                  @elseif(config('settings.shipping_cost_valid_below') > $cartTotal)
                                      <td class="total-amount">
                                      <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format(config('settings.shipping_cost') + $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100) !!}

                                      
                                        @php 
                                            $totalAmount = config('settings.shipping_cost') + $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100;
                                        @endphp
                                      
                                      </th>
                                  @else
                                      <td class="total-amount">
                                      <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100) !!}
                                      
                                    @php 
                                        $totalAmount = $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100;
                                    @endphp

                                      </th>
                                  @endif
                                        
                                    </tr>

                                    @if(session()->has('coupon_amount') || session()->has('coupon_percent') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total())
                              
                                   
                                        @if(session('coupon_amount'))
                                            <tr>
                                            
                                                <td> @lang('Coupon Amount')</td>
                                                <td>-   
                                                    ₹ {!! \App\Helpers\IndianCurrencyHelper::IND_money_format(session('coupon_amount')) !!}
                                                </td>
                                            </tr>
                                        @else
                                        @php 
                                            $couponAmount  = (Cart::total() * session('coupon_percent'))/100;
                                            
                                            session(['coupon_amount' => $couponAmount]);
                                            
                                        @endphp
                                            <tr>
                                                
                                                <td >@lang('Coupon Amount')</th>
                                                <td>-    
                                                <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format(session('coupon_amount')) !!}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif

                                    @if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < $cartTotal)
                                    <tr class="total">
                                        <td> Total After Discount  
                                        
                                        
                                        </td>
                                        <td class="total-amount">
                                        
                                            <i class="fa fa-rupee"></i> {!! \App\Helpers\IndianCurrencyHelper::IND_money_format($cartTotal - session('coupon_amount')  - $cal_wallet_amount) !!}
                                        </td>
                                    </tr>

                                    @php 
                                    
                                        $totalAmount = $cartTotal - session('coupon_amount')  - $cal_wallet_amount;
                                    @endphp
                                    
                                    @endif

                                    @php 
                                            session()->forget('totalAmount');
                                            session()->put('totalAmount',$totalAmount);
                                        @endphp
                                    
                                </table>
                            </div>
                        </div>
                        @if(\Auth::user()) 
                            <a href="{{route('checkout.shipping')}}" class="btn btn-sqr d-block">@lang('Proceed Checkout')</a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-sqr d-block" data-toggle="modal" data-target="#login-modal">@lang('Proceed Checkout')</a> 
                        @endif
                        <!-- <a href="#" class="btn btn-sqr d-block">@lang('Proceed Checkout')</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@else
<br>
<div class="cart-main-wrapper section">
        <h2 class="text-center text-muted">
        <img src="{{asset('img/cart-empty1.png')}}">
        <br/>
            <a href="{{url('/')}}" class="btn btn-primary emptyBtn">@lang('Go to Shop')</a>
        </h2>
    </div>
@endif
<!-- login -->
<div class="modal fade login-register" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="LoginMsg"></div>
        <form method="POST" name="loginform" id="loginform" action="{{ route('login') }}">
          
          <div class="form-group  input-group{{ $errors->has('username') || $errors->has('email') ? ' has-error' : '' }}">
            <input type="text" class="form-control " name="username" id="username" placeholder="@lang('Your Username')"/>
              @if ($errors->has('username'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('username') }}
                      </strong>
                  </span>
              @endif
              @if ($errors->has('email'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('email') }}
                      </strong>
                  </span>
              @endif
          </div>
          <div class="form-group input-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" name="password" id="password" placeholder="@lang('Your Password')"/>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong class="text-danger">
                        {{ $errors->first('password') }}
                    </strong>
                </span>
            @endif
          </div>
          <div class="form-group">
              <label class="checkbox-inline">
                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                  @lang('Remember me')
              </label>
          </div>
          <div class="form-group">
            <button id="login-button" type="submit"  class="btn btn-primary">@lang('Login')</button>
          </div>
          <div class="forgot-link">
            <!-- <a href="{{ route('password.request') }}">@lang('Forgot Your Password?')</a> -->
            <a href="#">@lang('Forgot Your Password?')</a>
            <p>Don't have an account with us ? <a href="javascript:void(0)" data-toggle="modal" data-target="#register-modal">@lang('Sign Up')</a></p>
          </div>
        </form>
           
      </div>
     
    </div>
  </div>
</div>

@section('scripts')
<script>
jQuery(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $("#login-button").on('click',function(e) {
        e.preventDefault();
        $.ajax({

            type:$("#loginform").attr('method'),
            url:$("#loginform").attr('action'),
            dataType:'json',
            data:$("#loginform").serializeArray(),
            
            success:function(data) {
                    
                if(data.status==true) {
                    $("#LoginMsg").show();
                    $("#LoginMsg").html(data.msg);
                    setTimeout(function() {
                    $("#login-button").text('{{__('Login')}}').prop('disabled', false);
                    $("#LoginMsg").slideUp();
                    }, 2000); 
                    window.location.reload();
                } else {
                    $("#LoginMsg").show();
                    $("#LoginMsg").html(data.msg);
                    setTimeout(function() {
                    $("#login-button").text('{{__('Login')}}').prop('disabled', false);
                    $("#LoginMsg").slideUp();
                    }, 2000); 
                    return false;
                }
                
            }

        });
    });

    $(".removeProduct").on("click",function() {
        if(confirm('Are you sure you want to delete product from cart?') ) {
            $("#cartForm-"+$(this).data('rowid')).submit();
        } else {
            return false;
        }
    });

    $(".pro_qty").on("change",function() {
        $("#updateCart").submit();
    });

    $(".updateCart").on("click",function() {
        if(confirm('Are you sure you want to update product from cart?') ) {
            $("#updateCart").submit();
        } else {
            return false;
        }
    });


});
</script>
@endsection

 

