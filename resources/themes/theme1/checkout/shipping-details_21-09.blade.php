@extends('layouts.front')

@section('title')
    @lang('Shipping Details')
@endsection

@section('styles')
    @include('partials.phone_style')
     
@endsection
@php
  $amount = (int)\Session::get('totalAmount');
  session()->forget('totalAmount');
   
@endphp
 
@section('scripts')
    @include('partials.phone_script')
 
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// var amount = "{{ $amount ? $amount : '' }}";
// var walletAmount = " {{ $amount -\Auth::user()->wallet_balance }} ";
// var total = amount;
 
//      var checked = $("#wallet_balance").is(":checked");
//      if(checked) {
//       total = walletAmount;
//      } 
    

  
// var options = {
//     "key": "rzp_test_ooROqVsEVFJSuw",
//     "amount": total+'00', // Example: 2000 paise = INR 20
//     "callback_url": "{{ route('razorpay.payment') }}",
//     "image": "http://jewelnidhi.com/img/logo_new.gif",// COMPANY LOGO
//     "theme": {
//         "color": "#424244" // screen color
//     },
    
    
     
     
// }
//var propay = new Razorpay(options);

</script>
<script>
    jQuery(document).ready(function() {
      
      
      
      $('#phone-number1').keypress(function(e) {
           
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            $("#errormsg").html("Digits Only").show().fadeOut("slow");
            return false;
        }
        if($(e.target).prop('value').length>=10) {
          if(e.keyCode!=32) {
            $("#errormsg").html("Allow 10 Digits only").show().fadeOut("slow");
            return false;
          } 
        }
          
      });
        
      $("a[data-toggle='tab'").prop('disabled', true);

      

      $('.checkoutBtnPayment').click(function() {
        var check = true;
        $(".addressOption").each(function(){
            var name = $(this).attr("name");
            if($("input:radio[name="+name+"]:checked").length == 0){
                check = false;
            }
        });
        
        if(check) {

          $.ajax({
            url : $("#customerPaymentForm").attr('action'),
            method : $("#customerPaymentForm").attr('method'),
            data : $("#customerPaymentForm").serialize(),
            success:function(result) {
              $("a[data-toggle='tab'").prop('disabled', false);
              $("#shipping-tab").removeClass('active');
              $("#shipping").removeClass('show active');
              $("#payment-tab").addClass('active');
              $("#payment").addClass('show active');
            }
          });

        }else{
          confirm('Plese select shipping address');
              return false;
        }
    });

         

        $(".checkoutBtn").on("click",function() {
            
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          

            $("#customer_form").validate({
              
              submitHandler: function(form) { // ONLY FOR DEMO
                 
                  $.ajax({
                        url : $("#customer_form").attr('action'),
                        method : $("#customer_form").attr('method'),
                        data : $("#customer_form").serialize(),
                        success:function(result) {

                          $("a[data-toggle='tab'").prop('disabled', false);
                          $("#shipping-tab").removeClass('active');
                          $("#shipping").removeClass('show active');
                          $("#payment-tab").addClass('active');
                          $("#payment").addClass('show active');

                        },
                        error :function( data ) {
                          if( data.status === 422 ) {
                             
                           
                             
                            
                              $("#errormsg").html('Invalid mobile format');
                              setTimeout(function() {
                                $("#errormsg").slideUp();
                              }, 2000);
                             
                          }

                        }
                    });
              },
              rules: {
                  
                  "first_name": "required",
                   
                  
                  "address": "required",
                  "phone": {
                    required: true,
                    maxlength:10
                    
                    
                  },
                  
                  "city": "required",
                  "state": "required",
                  "zip": "required"
                  
              },
              ignore: ":hidden:not(.ignore)",
              errorClass: 'error',
              messages: {
                "first_name": {
                  required: "Please enter name"
                },
                
                
                "phone": {
                  required: "Please enter mobile no",
                  maxlength: "Please enter 10 digits mobile no"
                  
                },
                 
                "address": {
                  required: "Please enter address"
                },
                "city": {
                  required: "Please enter city"
                },
                "state": {
                  required: "Please enter state"
                },
                "zip": {
                  required: "Please enter pincode"
                }
               
              },
          });
          
        }); 

        

      $(".checkoutBtn1").on("click",function() {
        
              // if($("#first_name").val()!='' &&  $("#email").val()!='' &&  $("#phone").val()!='' &&  $("#address").val()!='' && $("#city").val()!='' && $("#state").val()!='' && $("#country").val()!='')
              if($("#first_name").val()!='' )  
              {
                    
                    $.ajax({
                        url : $("#customer_form").attr('action'),
                        method : $("#customer_form").attr('method'),
                        data : $("#customer_form").serialize(),
                        success:function(result) {

                          $("a[data-toggle='tab'").prop('disabled', false);
                          $("#shipping-tab").removeClass('active');
                          $("#shipping").removeClass('show active');
                          $("#payment-tab").addClass('active');
                          $("#payment").addClass('show active');

                        }
                    });
                } else {
                  $("a[data-toggle='tab'").prop('disabled', true);
                }

        });

        
        $(".placeOrder").on("click",function() {
          var amount = "{{ $amount ? $amount : '' }}";
          var walletAmount = "{{ $amount - \Auth::user()->wallet_balance }}";
          console.log('walletAmount:- '+walletAmount);
          var total = amount;
          
              var checked = $("#wallet_balance").is(":checked");
              if(checked) {
                total =  walletAmount;
              } 
              

            
          var options = {
              "key": "rzp_test_ooROqVsEVFJSuw",
              "amount": total+'00', // Example: 2000 paise = INR 20
              "callback_url": "{{ route('razorpay.payment') }}",
              "image": "http://jewelnidhi.com/img/logo_new.gif",// COMPANY LOGO
              "theme": {
                  "color": "#424244" // screen color
              },
              
              
              
              
          }
          var propay = new Razorpay(options);
          propay.open();
        });

    });

        var existingAddress = $('#existing-address');
        var panel = $('.panel');
        var existingAddresses = $('.existing-addresses');

        existingAddresses.hide();
        $(document).ready(function() {
            existingAddress.on('change', function() {
                if(existingAddress.is(':checked')) {
                  
                    panel.hide();
                    existingAddresses.fadeIn();
                } else {
                    panel.fadeIn();
                    existingAddresses.hide();
                }  
            });
        });
        function addAddress() {
          console.log('=====');
          $(".addAddress").attr('style',"color:#fff");
          $(".existing-addresses").removeClass('d-block');
          $(".existing-addresses").addClass('d-none');
          $(".shipping-details-form").removeClass('d-none');
          $(".shipping-details-form").show();
          $(".existingAddress").html('<a href="javascript:void(0);" class="existingAdd" style="color:#D3A012;">Use existing address</a>');

          $(".existingAdd").on("click",function() {
            
            $(".existingAdd").attr('style',"color:#fff");
            $(".addAddress").attr('style',"color:#D3A012");
            $(".existing-addresses").removeClass('d-none');
            $(".shipping-details-form").addClass('d-none');
            $(".existing-addresses").show();
          });
        }
    </script>
@endsection

@section('content')
<div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">@lang('Checkout')</li>
      </ol>
    </div>
  </div>
 
  

  <div class="checkout-page mt-5">
      <div class="container">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
        @if(!\Auth::user()->id)
          <li class="nav-item">
            <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
          </li>
        @endif
          <li class="nav-item">
            <a class="nav-link active" id="shipping-tab" data-toggle="tab" href="#shipping" role="tab" aria-controls="shipping" aria-selected="false">Shipping Address</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="false">Payment</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
        @if(!\Auth::user()->id)
          <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
          
            <div class="row">
              <div class="col-md-6">
                  <h5 class="mt-4">Login</h5>
                <form class="mt-4">
                  <div class="form-group">
                    <input type="email" class="form-control" name="" placeholder="Email id">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" name="" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary">Login</button>
                  </div>
                  <div class="forgot-link">
                    <a href="#">Forgot Password</a>
                    <p>Don't have an account with us ? <a href="#">Sign Up</a></p>
                  </div>
                </form>
              </div>
            </div>
          

          </div>
          @endif
          <div class="tab-pane fade show active" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
          <div class="row mt-4">
              <div class="col-md-6">
          @if(count($addresses) > 0)
          <div class="custom-control nopadding" >
              <input type="checkbox" class="custom-control-input" id="existing-address"  name="existing-address">
              
              <label class="existingAddress">@lang('Use existing address')  </label> or 
              <a href="javascript:void(0);" class="addAddress" style="color:#D3A012;" onclick="addAddress();">Add Address </a>
              

            </div>
                 
                {!! Form::open(['method'=>'post', 'action'=>'FrontCustomersController@startPaymentSession','id'=>'customerPaymentForm', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;']) !!}
                  <div class="col-md-6 d-block existing-addresses nopadding">
                    
                    @foreach($addresses as $key => $address)
                    <div class="radio">
                        <label>
                            <input type="radio" class="addressOption" id="address_option_{{ $key }}" name="address_option" value="{{$address->id}}">
                            <span class="address-header">{{$key+1}}. @lang('Shipping Address')</span>
                        </label>
                    </div>
                      <div class="form-group">
                         
                            {{ Form::hidden('first_name', $address->first_name, ['id'=>'first_name']) }}
                            {{ Form::hidden('phone', $address->phone, ['id'=>'phone']) }}
                            {{ Form::hidden('city', $address->city, ['id'=>'city']) }}      
                            {{ Form::hidden('address', $address->address, ['id'=>'address']) }}     
                            {{ Form::hidden('city', $address->city, ['id'=>'city']) }} 
                            {{ Form::hidden('state', $address->address, ['id'=>'state']) }}
                            {{ Form::hidden('country', $address->country, ['id'=>'country']) }}               
                        <strong>{{$address->first_name . ' ' . $address->last_name}}</strong>,<br>
                          {{$address->address}}<br>
                          {{$address->city . ', ' . $address->state . ' - ' . $address->zip}}<br>
                          {{$address->country}}.<br>
                          <strong>@lang('Phone:')</strong> {{$address->phone}}<br>
                          
                      </div>
                    @endforeach
                    <div class="text-right">
                        <div class="form-group">
                            <button type="button" name="submit_button" class="btn btn-primary checkoutBtnPayment">@lang('Proceed to Payment')</button>
                        </div>
                    </div>

                    
                  </div>
                  
                   
                  {!! Form::close() !!}
          @endif
          <div class="panel panel-primary {{ count($addresses) > 0 ? 'd-none' : '' }}  shipping-details-form mt-2 nopadding" >
                 
                 
                {!! Form::open(['method'=>'post', 'action'=>'FrontCustomersController@store','id'=>'customer_form']) !!}
                  <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('first_name', __('First Name:')) !!} <em style="color:red;">*</em>
                            {!! Form::text('first_name', old('first_name'), ['class'=>'form-control','id'=>'first_name', 'placeholder'=>__('Enter first name'), 'required'])!!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('last_name', __('Last Name:')) !!} 
                            {!! Form::text('last_name', null, ['class'=>'form-control','id'=>'last_name', 'placeholder'=>__('Enter last name')])!!}
                        </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                      <label>Mobile No.</label> <em style="color:red;">*</em>
                      {!! Form::text('phone', null, ['class'=>'form-control','id'=>'phone-number1', 'placeholder'=>__('Enter Mobile No.'), 'required'])!!}
                       <span id="errormsg" style="color:#D3A012;"></span>
                  </div>
                  <div class="form-group">
                            {!! Form::label('address', __('Address:')) !!} <em style="color:red;">*</em>
                            {!! Form::textarea('address', null, ['class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Enter address'), 'rows'=>'6', 'required'])!!}
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('city', __('City:')) !!} <em style="color:red;">*</em>
                            {!! Form::text('city', null, ['class'=>'form-control','id'=>'city', 'placeholder'=>__('Enter city'), 'required'])!!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('state', __('State:')) !!} <em style="color:red;">*</em>
                            {!! Form::text('state', null, ['class'=>'form-control','id'=>'state', 'placeholder'=>__('Enter state'), 'required'])!!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('zip', __('Zip:')) !!} <em style="color:red;">*</em>
							              {!! Form::text('zip', null, ['class'=>'form-control','id'=>'zip', 'placeholder'=>__('Enter zip'), 'required'])!!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            @include('partials.countries_field')
                        </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <button type="submit" id="checkoutBtn" name="submit_button"  class="btn btn-primary checkoutBtn">@lang('Proceed to Payment')</button>
                  </div>
                  {!! Form::close() !!}
              </div>
              </div>
              <div class="col-md-6">
                  <h5 class="">Order Summary</h5>
                  <div class="table-responsive mt-4">
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th class="pro-title">Product</th>
                                  <th class="pro-subtotal">Total</th>
                              </tr>
                          </thead>
                          <tbody>
                          @php
                            $GST = 0;
                            $valueAdded = 0;
                            $cartTotal = 0;
                            $subTotal = 0;
                          @endphp
                            @foreach($cartItems as $k=> $cartItem)
                              @php $rowidArr[] = $cartItem->rowId;
                                   $qtyArr[] = $cartItem->qty;
                                   $GST += $cartItem->options->gst;
                                   $valueAdded += $cartItem->options->va;
                                    
                                   $productamount = $cartItem->options->unit_price * $cartItem->qty ;
                              @endphp
                              <?php 
                                    $product_data = \App\Product::select('file_id','virtual','downloadable')->where('id',$cartItem->id)->get()->first()->toArray();
                                ?>
                              <tr>
                                  <td class="pro-title"><a target="_blank" href="{{route('front.product.show', [$cartItem->options->slug])}}">{{$cartItem->name}}</a></td>
                                  <td class="pro-subtotal"><span>{{currency_format(number_format($productamount))}}</span></td>
                              </tr>
                               
                            @endforeach
                             
                          </tbody>
                      </table>
                  </div>

                  @php 
                      $cal_wallet_amount = 0;
                      $subTotal = Cart::total() - $GST - $valueAdded;
                      $cartTotal += config('settings.shipping_cost') + $subTotal + $GST + $valueAdded ;
                  @endphp

                  <div class="cart-calculate-items">
                      <h5 class="mt-4">Cart Totals</h5>
                      <div class="table-responsive mt-4">
                          <table class="table table-bordered">
                              <tbody><tr>
                                  <td>Sub Total</td>
                                  <td>{{ currency_format(number_format($subTotal)) }}</td>
                              </tr>
                                    <tr>
                                        <td>@lang('GST')</td>
                                        <td>&#8377; {{number_format($GST)}}</td>
                                    </tr>

                                    <tr>
                                        <td>@lang('Value Added')</td>
                                        <td>&#8377; {{number_format($valueAdded)}}</td>
                                    </tr>
                              
                                      
                              
                                

                                  <tr>
                                    <td>Shipping</td>
                                    @if(config('settings.shipping_cost_valid_below') > $cartTotal)
                                        <td> {{currency_format(number_format(config('settings.shipping_cost'))) }}</td>
                                    @else
                                        <td> {{currency_format(number_format(0)) }}</td>

                                    @endif
                                  </tr>

                              <tr class="total">
                                  <td> <b>Total</b></td>
                                  @if(config('settings.shipping_cost_valid_below') > $cartTotal && session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < $cartTotal)
                                      <td class="total-amount">
                                        {{  currency_format(number_format(config('settings.shipping_cost') - $cal_wallet_amount + $cartTotal - session('coupon_amount') + ($cartTotal * config('settings.tax_rate')) / 100)) }} </span>
                                        @php 
                                          config('settings.shipping_cost') - $cal_wallet_amount + $cartTotal - session('coupon_amount') + ($cartTotal * config('settings.tax_rate')) / 100;
                                        @endphp
                                      </th>
                                  @elseif(config('settings.shipping_cost_valid_below') > $cartTotal)
                                      <td class="total-amount">
                                        {{ currency_format(number_format(config('settings.shipping_cost') + $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100)) }} </span>
                                        @php 
                                            $totalAmount = config('settings.shipping_cost') + $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100;
                                        @endphp
                                      </th>
                                  @else
                                      <td class="total-amount">{{ currency_format(number_format($cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100)) }} </span>
                                        @php 
                                            $totalAmount = $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100;
                                        @endphp
                                      </th>
                                  @endif
                                    
                                
                                

                              @if(session()->has('coupon_amount') || session()->has('coupon_percent') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total())
                              
                                   
                                        @if(session('coupon_amount'))
                                            <tr>
                                            
                                                <td> @lang('Coupon Amount')</td>
                                                <td>-   {{ currency_format(session('coupon_amount')) }}</td>
                                            </tr>
                                        @else
                                        @php 
                                            $couponAmount  = (Cart::total() * session('coupon_percent'))/100;
                                            
                                            session(['coupon_amount' => $couponAmount]);
                                            
                                        @endphp
                                            <tr>
                                                
                                                <td >@lang('Coupon Amount')</th>
                                                <td>-    {{ currency_format(session('coupon_amount')) }} </td>
                                            </tr>
                                        @endif
                                  @endif

                                  
                                  @if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < $cartTotal)
                                  <tr class="total">
                                    <td> <b>Total After Discount</b></td>
                                    <td class="total-amount">{{ currency_format(number_format($cartTotal - session('coupon_amount')  - $cal_wallet_amount)) }} </span></td>
                                  </tr>
                                  @php 
                                        $totalAmount = $cartTotal - session('coupon_amount')  - $cal_wallet_amount;
                                    @endphp
                                  @endif

                                  @php 
                                    session()->put('totalAmount',$totalAmount);
                                  @endphp
                          </tbody></table>
                      </div>
                  </div>
                      <!-- @if(session()->has('coupon_invalid'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{session('coupon_invalid')}}
                        </div>
                    @endif
 
                    @if(session()->has('coupon_uses'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Coupon apply only one time
                        </div>
                    @endif

                    

                      @if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total())
                          @if(session()->has('coupon_code'))
                              <h6 class="text-success">@lang('Coupon Applied:') <span class="text-muted">{{session('coupon_code')}}</span></h6>
                              <h6 class="text-success">@lang('Discount:') <span class="text-muted"> {{ currency_format(session('coupon_amount')) }}</span></h6>
                          @else
                              <h4>@lang('Coupon Discount:') {{currency_format(session('coupon_amount'))}}</h4>
                          @endif
                          <a id="have-coupon" href="">@lang('Change Coupon?')</a>
                      @else
                          <a id="have-coupon" href="">@lang('Have a Coupon?')</a>
                      @endif
                
                      {!! Form::open(['method'=>'post', 'action'=>'FrontCouponsController@checkCoupon', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;', 'class'=>'d-block d-md-flex coupon-sec']) !!}
                           
                           
                          {!! Form::text('coupon', null, ['class'=>'form-control', 'placeholder'=>__('Enter Your Coupon Code'), 'required']) !!}
                          {!! Form::submit(__('Apply Coupon'), ['id'=>'coupon-btn', 'class'=>'btn btn-primary', 'name'=>'submit_button']) !!}
                      {!! Form::close() !!} -->
              </div>
          </div>

          </div>

          <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
           
            <div class="mt-4 payment-method">
                <!-- <h5 class="">Payment method</h5> -->
                <form class="mt-4">
                     
                     
                    <div class="payment-method-name mb-3">
                      <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="wallet_balance" value=""  name="wallet_balance">
                            
                            <label class="custom-control-label" for="wallet_balance">
                            <h6>Wallet Balance</h6>
                            <span> Available Balance &#8377;{{ \Auth::user()->wallet_balance }} 
                            @php 
                             
                           
                            @endphp
                            </span>
                            </label>

                        </div>
                        <div class="custom-control custom-radio mt-4">
                            <input type="radio" id="razorpay" name="options" value="razorpay" class="custom-control-input" checked="">
                            <label class="custom-control-label" for="razorpay"><img src="{{asset('img/razorpay.jpg')}} " class="img-fluid paypal-card" alt="Paypal">
                            </label>
                        </div>
                        
                         
                    </div>
                     
                  <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary placeOrder">Place Order</button>
                    </div>
                  </div>
                    
          
            </div>
           
          </div>
        </div>

      </div>
  </div>

  
 
   
@endsection
 