@extends('layouts.front')

@section('title')
    {{$product->meta_title ? $product->meta_title : $product->name." - ".config('app.name')}}
@endsection

@section('meta-tags')
    <meta name="description" content="{{$product->meta_desc ? $product->meta_desc : StringHelper::truncate(trim(strip_tags($product->description)), 160)}}">
    @if($product->meta_keywords)
        <meta name="keywords" content="{{$product->meta_keywords}}">
    @endif
@endsection

@section('meta-tags-og')
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{$product->meta_title ? $product->meta_title : $product->name.' - '.config('app.name')}}" />
    <meta property="og:description" content="{{$product->meta_desc ? $product->meta_desc : StringHelper::truncate(trim(strip_tags($product->description)), 160)}}" />
    @if($product->photo)
        <meta property="og:image" content="{{$product->photo->name}}" />
    @endif
@endsection

@section('styleg')
<link rel="stylesheet" href="{{ asset('css/nice-select.css') }}" />
<style>
#shipping_success {
  color:green !important;
}
 
</style>
    <!-- <style>
        body {
            background-color: #f1f3f6;
        }
        .spec-type {
            margin-bottom: 1rem;
            margin-top: 1rem;
        }
        .spec-radio {
            margin-right: 1.4rem;
        }
        .spec-radio input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        .spec-radio input[type="radio"] + .spec-radio-label:before {
            content: '';
            background: #f4f4f4;
            border-radius: 100%;
            border: 1px solid #b4b4b4;
            display: inline-block;
            width: 1.2em;
            height: 1.2em;
            position: relative;
            top: .2rem;
            margin-right: .75rem;
            vertical-align: top;
            cursor: pointer;
            text-align: center;
            transition: all 250ms ease;
        }
        .spec-radio input[type="radio"]:checked + .spec-radio-label:before {
            background-color: var(--main-color);
            box-shadow: inset 0 0 0 4px #f4f4f4;
        }
        .spec-radio input[type="radio"]:focus + .spec-radio-label:before {
            outline: none;
            border-color: #3197EE;
        }
        .spec-radio input[type="radio"]:disabled + .spec-radio-label:before {
            box-shadow: inset 0 0 0 4px #f4f4f4;
            border-color: #b4b4b4;
            background: #b4b4b4;
        }
        .spec-radio input[type="radio"] + .spec-radio-label:empty:before {
            margin-right: 0;
        }
    </style> -->
@endsection

@section('above_container')@endsection

@section('content')
    <div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        @if(isset($product->category->category->name) && $product->category->category->name!='')
        <li class="breadcrumb-item"><a href="/category/{{ strtolower($product->category->name) }}">{{ $product->category->category->name }}</a></li>
        @endif
        @if(isset($product->category->name) && $product->category->name!='')
        <li class="breadcrumb-item"><a href="/category/{{ strtolower($product->category->name) }}">{{ $product->category->name }}</a></li>
        @endif
        @if(isset($product->name) && $product->name!='')
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }} </li>
        @endif
        </li>
      </ol>
    </div>
  </div>

  @if(session()->has('msg'))
        <hr>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <strong>&nbsp;@lang('Error:')</strong> {{session('msg')}}
        </div>
    @endif
  <div class="product-detail-page">
  <div class="cart-message"></div>
    <div class="container">
    
    {!! Form::open(['method'=>'patch', 'route'=>['front.cart.add', $product->id], 'id'=>'cart-form']) !!}
      {{ Form::hidden('product_id',isset($product->id) ? $product->id : '',['id'=>'product_id']) }}
      {{ Form::hidden('new_price',isset($product->new_price) ? $product->new_price : '',['id'=>'new_price']) }}
      <div class="row">
        <div class="col-md-5">
          <div class="img-section">
            
          
          
          @if(count($product->photos) > 0)
            
            <div class="product-large-slider">
                @foreach($product->photos as $photo)
                
                  <div class="pro-large-img img-zoom">
                      @if(isset($photo->name))
                          @php
                              $image_url = \App\Helpers\Helper::check_image_avatar($photo->name, 80);
                          @endphp
                          <img src="{{ $image_url }} " alt="product-details" />
                      @else
                            <img src="https://via.placeholder.com/80x80?text=No+Image" class="img-responsive" width=80 height=80 alt="{{$product->name}}" />
                      @endif
                      
                  </div>
                @endforeach 
            </div>
            @else 
              <div class="pro-large-img img-zoom">
                      @if(isset($product->photo->name))
                          @php
                              $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 80);
                          @endphp
                          <img src="{{ $image_url }} " alt="product-details" />
                      @else
                            <img src="https://via.placeholder.com/80x80?text=No+Image" class="img-responsive" width=80 height=80 alt="" />
                      @endif
                      
                  </div>

          @endif
          @if(count($product->photos) > 0)
          <div class="pro-nav">
            @foreach($product->photos as $photo)
              <div class="pro-nav-thumb">
                    @if($photo->name)
                      @php
                          $image_url = \App\Helpers\Helper::check_image_avatar($photo->name, 80);
                      @endphp
                  <img src="{{ $image_url }}" alt="product-details" />
                  @else
                        <img src="https://via.placeholder.com/80x80?text=No+Image" class="img-responsive" width=80 height=80 alt="" />
                  @endif
              </div>
            @endforeach
             
          </div>
          @endif
          @if(count($product->certificate_products) > 0)
          <div class="certification"> 
            <span>Certified By</span> 
            <ul> 
              <!--<li class="bis"><span class="prcs-d" data-p="bis,bsc" title="Bureau of Indian Standards- HALLMARKING">BIS</span></li> 
              <li class="sgl"><span class="prcs-d" data-p="sgl,bsc" title="Solitaire Gemological Laboratories">SGL</span></li> 
              <li class="igi"><span class="prcs-d center" data-p="igi,bsc" title="International Gemological Institute">IGI</span></li> -->
              
                @foreach($product->certificate_products AS $k => $value)
                  <li><img src="{{asset('storage/certificate/'.$value->image) }}" alt="{{ strtoupper($value->name) }}"/> </li> 
                @endforeach
             
            </ul> 
          </div>
          @endif

          </div>
        </div>

        <div class="col-md-7">
          <div class="product-details-desc">
            <h3 class="product-name">{{ isset($product->name) && $product->name!='' ? $product->name : '' }}</h3>
            <div class="price-charge">
           <div class="price-box">
           @if($product->old_price)
                <span class="price-old"><del>&#8377; {{ number_format($product->old_price) }}</del></span>
            @endif  
                <span class="price-regular">&#8377; {{ isset($product->new_price) ? number_format($product->new_price) : '' }}</span>
           </div>
           @if(isset($product-> product_discount) && $product-> product_discount!='0')
           <div class="making-charge">
              <span> {{ $product-> product_discount .'% '.$product-> product_discount_on }}  charge </span>
           </div>
           @endif
          </div>
            <div class="store-avaliable">
              <div class="your-pincode">
                <div class="static-text">Your Pincode</div>
                <div class="find-nearest-store">
                  <input class="form-control" type="text" name="pincode" id="shipping_pincode"   placeholder="your pincode"/>
                  <button class="btn btn-primary" type="button" id="checkShippingAvailbility" onclick="checkShippingAvailability()">Update</button>
                </div>
              </div>
              <div class="delivery-info">
                <p class="text-success" id="shipping_success" style="display: none;">*Shipping Available To Entered PinCode</p>
                <p class="text-danger" id="shipping_error" style="display: none;">*No Shipping Available To Entered PinCode</p>
                <!-- <p>Delivery By <b>Thu, Jun 25</b> for <b> Pincode 302016 </b></p> -->
              </div>
            </div>
             
            <div class="customize-product">
              <a href="javascript:void(0)" id="toggle">Customize this product <i class="fa fa-plus"></i> <i class="fa fa-minus"></i></a>
              <div class="customize-sec" style="display: none;">
                <div class="box">
                  <select class="nice-select">
                    <option>White</option>
                    <option>Gold</option>
                  </select>
                </div>
                <div class="box">
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="customRadio" name="example1" value="customEx">
                    <label class="custom-control-label" for="customRadio">14kt</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="customRadio1" name="example1" value="customEx">
                    <label class="custom-control-label" for="customRadio1">18kt</label>
                  </div>
                </div>
                <!-- <div class="box">
                  <span><i class="fa fa-diamond"></i>Diamond:</span>
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="customRadio2" name="example2" value="customEx">
                    <label class="custom-control-label" for="customRadio2"> SI IJ</label>
                  </div>
                 
                </div> -->
              </div>
            </div>

            
            @if($groupSizeArr[0]!=null)
                @php $defaultSize = ''; $pivotVal = array(); $array = array(); $productID = array();  @endphp
              <div class="pro-size">
                <h6 class="option-title"> Size :</h6>
                <select class="nice-select groupVal">
                  <option value="">Select Size</option>
                  
                    
                  @foreach($groupSizeArr AS $k=> $value)

                      @php 
                      $productID[] = isset($value->id) ? $value->id : '';
                      $defaultSize .= isset($value->product_group_default) ? $value->product_group_default : '' ;@endphp

                        @if(!empty($value->specificationTypes))
                          @foreach ($value->specificationTypes  as $ky => $val)
                            
                            @if($value->group_by_size == $val->pivot->specification_type_id)
                                
                                  @php
                                    $pivotVal[] = $val->pivot->value;
                                    $specificationVal = collect($pivotVal);
                                    $unique = $specificationVal->unique();
                                    $array = $unique->values()->all();
                                     
                                    
                                  @endphp

                            @endif
                          @endforeach
                        @endif
                    @endforeach 
                    
                    @foreach ($array  as $ky => $val)
                      @foreach ($productID  as $k => $va)
                        @if($k==$ky)
                          <option @php echo ($defaultSize=='1' ? 'selected="selected"' : '' ) @endphp data-product_id = "{{ $va }}" value="{{ $val }}">{{ $val }}</option>
                        @endif
                      @endforeach  
                    @endforeach 
                     
                  
                </select>
              </div>
               
           
            @endif
             
             

              
             
               
            @if($groupPurityArr[0]!=null)
              
                @php $defaultPurity = ''; $pivotVal = array(); $array = array(); @endphp
              <div class="pro-size">
                <h6 class="option-title"> Purity :</h6>
                <select class="nice-select groupVal">
                  <option value="">Select Purity</option>
                  
                    
                  @foreach($groupPurityArr AS $k=> $value)
                      @php 
                      $productID[] = isset($value->id) ? $value->id : '';
                      $defaultPurity .= isset($value->product_group_default) ? $value->product_group_default : '' ; @endphp
                        @if(!empty($value->specificationTypes))
                          @foreach ($value->specificationTypes  as $ky => $val)
                            
                            @if($value->group_by_purity == $val->pivot->specification_type_id)
                                
                                  @php
                                    $pivotVal[] = $val->pivot->value;
                                    $specificationVal = collect($pivotVal);
                                    $unique = $specificationVal->unique();
                                    $array = $unique->values()->all();
                                  @endphp

                            @endif
                          @endforeach
                        @endif
                    @endforeach 
                     
                     
                    @foreach ($array  as $ky => $val)
                      @foreach ($productID  as $k => $va)
                        @if($k==$ky)
                          <option @php echo ($defaultPurity=='1' ? 'selected="selected"' : '' ) @endphp data-product_id = "{{ $va }}" value="{{ $val }}">{{ $val }}</option>
                        @endif
                      @endforeach  
                    @endforeach 
                  
                </select>
              </div>
               
           
            @endif

            
             
           
           <div class="add-to-cart">
              <button class="btn btn-primary" id="add_cart" name="submit_button" type="button">@lang('Add to Cart')</button>
                @if(\Auth::user()) 
                
                  <button class="btn btn-primary" id="buy_now" name="buynow_button" value="buynow" type="submit">@lang('Buy Now')</button>
                   
                @else
                    <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#login-modal">@lang('Buy Now')</a> 
                @endif
               
           </div>
        </div>
        </div>
     </div>
     {!! Form::close() !!}
<div class="product-description">
@if(isset($product->description) && $product->description!='')
    <div class="row">
      <div class="col-md-12">
        <div class="product-desc">
            <p><center><strong>PRODUCT DESCRIPTION</strong></center> </p>
            <p>{{ isset($product->description) && $product->description!='' ? strip_tags($product->description) : '' }}</p>
        </div>
      </div>
    </div>  
@endif

     <div class="row">
       <div class="col-md-6">
         <div class="product-desc">
           <h4>Product Details</h4>
           <table class="table table-bordered">
             <tr>
               <td>Product Code</td>
               <td id="sku">{{ isset($product->sku) && $product->sku!='' ? $product->sku : '' }}</td>
             </tr>
             @if(isset($product->product_height))
             <tr>
              <td>Height</td>
              <td id="product_height">{{ number_format($product->product_height) }} mm</td>
            </tr>
            @endif
            @if(isset($product->product_width))
            <tr>
              <td>Width</td>
              <td id="product_width">{{ number_format($product->product_width) }} mm</td>
            </tr>
            @endif
            @if(isset($product->total_weight))
            <tr>
              <td>Product Weight (Approx.)</td>
              <td id="total_weight">{{ number_format($product->total_weight) }}  gram</td>
            </tr>
            @endif
           </table>

           <h4>{{ isset($product->metal->name) ? ucwords($product->metal->name) : '' }} Details </h4>
           <div class="table-responsive">
           <table class="table table-bordered">
           @if($product->silverItem)
           <tr>
                <td>{{ $product->silverItem['name'] ? ucwords($product->silverItem['name']) : '' }} </td>
                <td>{{ $product->silverItem['rate'] ? number_format($product->silverItem['rate']) : '0.00'  }}</td>
              </tr>
           @endif
            @if($product->specificationTypes)
              @foreach($product->specificationTypes AS $k=> $value)
               
              <tr>
                <td>{{ $value['name'] ? ucwords($value['name']) : '' }} </td>
                <td id="{{ isset($value['name']) ? $value['name'] : '' }}">{{ $value->pivot->value }} {{ $value->pivot->unit }}</td>
              </tr>
             @endforeach
            @endif
              
             
           </table>
          </div>

           

           <h4>Price Breakup</h4>
           <table class="table table-bordered">
           @if(isset($product->gold_price))
             <tr>
               <td>Gold</td>
               <td> Rs. {{ number_format($product->gold_price)  }} </td>
             </tr>
          @endif
          @if(isset($product->vat_rate))
             <tr>
              <td>VAT</td>
              <td id="vat_rate"> Rs. {{ number_format($product->vat_rate) }} </td>
            </tr>
          @endif
            <!-- <tr>
              <td>0% Making Charge </td>
              <td> Rs. 0/- Rs. 8,533/- </td>
            </tr> -->
          @if(isset($product->total_weight))
            <tr>
              <td>Total Weight</td>
              <td id="approxtotal_weight">  {{ number_format($product->total_weight) }} gram </td>
            </tr>
          @endif
          @if(isset($product->gst_three_percent))
            <tr>
              <td>GST</td>
              <td id="gst_three_percent"> Rs. {{ number_format($product->gst_three_percent) }} </td>
            </tr>
          @endif
          @if(isset($product->carat_weight))
            <tr>
              <td>Carat Weight</td>
              <td id="carat_weight"> Rs. {{ number_format($product->carat_weight) }} </td>
            </tr>
          @endif
          @if(isset($product->per_carate_cost))
            <tr>
              <td>Per Carate Cost </td>
              <td> Rs. {{ number_format($product->per_carate_cost) }} </td>
            </tr>
          @endif
          @if(isset($product->total_price))
            <tr>
              <td>Total</td>
              <td id="total_price"> Rs. {{ number_format($product->total_price) }} </td>
            </tr>
          @endif
           </table>

         </div>
       </div>

       <div class="col-md-6">
         <div class="jewel-promise">
           <h4>Jewelnidhi Promise</h4>
           <div class="policy-detail">
             <ul>
               <li>
                 <i class="fa fa-sign-out"></i>
                 <span>30-Day Money Back</span>
               </li>
               <li>
                <i class="fa fa-support"></i>
                <span>Lifetime Exchange
                  & Buy-Back Policy</span>
              </li>
              <li>
                <i class="fa fa-certificate"></i>
                <span>Certified Jewellery</span>
              </li>
              <li>
                <i class="fa fa-thumbs-up"></i>
                <span>100% Refund</span>
              </li>
              <li>
                <i class="fa fa-truck"></i>
                <span>Free Shipping</span>
              </li>
              <li>
                <i class="fa fa-heart"></i>
                <span>Free Returns</span>
              </li>
             </ul>
           </div>
         </div>

         <div class="have-question">
           <h4>Have a Question?</h4>
           <p>Call us at 1800-419-0066</p>
           <div class="form-group">
             <label>Name</label>
             <input class="form-control" type="text" name="" placeholder="Enter Name"/>
           </div>
           <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="text" name="" placeholder="Enter Email"/>
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input class="form-control" type="text" name="" placeholder="Phone Number"/>
          </div>
          <div class="form-group textarea">
            <label>Query</label>
            <textarea class="form-control" placeholder="Query"></textarea>
          </div>
          <button class="btn btn-primary">Submit</button>
         </div>
       </div>

     </div>
    </div>
    </div>

    <!-- related products area start -->
    @if(count($similarProduct) > 1)

      <section class="section category-page related-products ">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading-sec">
                      <h2>Similar product</h2>
                      <img src="{{ URL::asset('img/home_line.png') }}" alt=""/>
                    </div>
                </div>
            </div>
              
            <div class="row">
                <div class="col-12">
                    <div class="product-carousel-4 slick-row-10 slick-arrow-style">
                    
                      @foreach($similarProduct AS $k => $val)
                      <div class="cat-item">
                        
                        <figure class="product-thumb">
                          <a href="#">
                              @if(isset($val->photo))
                                @php
                                  $image_url = \App\Helpers\Helper::check_image_avatar($val->photo->name, 80);
                                @endphp
                                  <img src="{{ $image_url }} " alt="product-details" />
                              @else
                                  <img src="https://via.placeholder.com/80x80?text=No+Image" class="img-responsive" width=80 height=80 alt="{{$product->name}}" />
                              @endif

                                  
                                 
                                  @if($val->overlayphoto!='')
                                      @if($val->overlayphoto->name)
                                          @php
                                              $image_url = \App\Helpers\Helper::check_overlayimage_avatar($val->overlayphoto->name, 150);
                                          @endphp
                                          <img class="sec-img" src="{{ $image_url }}" alt="{{$val->name}}">
                                      @else
                                          <img src="https://via.placeholder.com/150x150?text=No+Image" class="img-responsive product-feature-image" alt="{{$product->name}}" />
                                    @endif
                                  @endif
                          </a>
                          @if(isset($val->is_new) && $val->is_new=='1')
                              <div class="product-badge">
                                  <div class="product-label new">
                                      <span>new</span>
                                  </div>
                              </div>
                            @endif
                          <div class="button-group">
                          @if(\Auth::user()) 
                                            <a href="#" data-toggle="tooltip" data-placement="left" title="Add to wishlist" onclick="event.preventDefault(); document.getElementById('product-favourite-form_{{ $val->id }}').submit();">
                                                <i class="fa fa-heart-o"></i>
                                            </a>
                                            <form id="product-favourite-form_{{ $val->id }}" class="hidden" action="{{ route('front.product.favourite.store', $val->id) }}" method="POST">
                                              {{ csrf_field() }}
                                            </form>
                                        @else
                                            <a href="javascript:void();"  data-toggle="modal" title="Add to wishlist" data-target="#login-modal"><i class="fa fa-heart-o"></i></a> 
                                        @endif
                                
                          </div>
                          <div class="cart-hover">
                            <a href="/product/{{ $val->slug }}"><button class="btn btn-cart">View Details</button></a>
                          </div>
                      </figure>


                      <div class="product-caption">
              
                        <div class="price-box">
                            <span class="price-regular">₹ {{ number_format($val->new_price) }}</span>
                            @if(isset($val->old_price) && $val->old_price!=null)
                                <span class="price-old"><del style="font-size: 11px;">₹ {{ number_format($val->old_price) }}</del></span>
                              @endif
                        </div>
                          @if(isset($val->old_price) && $val->old_price!=null)
                            <div class="you-save">
                              Save <span style="font-size: 12px;">₹ {{ number_format($val->old_price - $val->new_price)}}</span>
                            </div>
                          @endif
                      </div>
                      </div>
                      @endforeach
                    
                      
                    
                </div>
            </div>
        </div>
      </section>
   

  @endif
  <!-- related products area end -->
  </div>

   
    
    
@endsection

@section('scripts')

<script src="{{ asset('js/nice-select.min.js')}}"></script>

<script src="{{ asset('js/xzoom.js')}} "></script>
<script src="{{ asset('js/image-zoom.min.js')}}"></script>
<script src="{{ asset('js/script.js')}} "></script>
<script>
  $(".customize-product a").click(function(){
    $(".customize-sec").slideToggle();
  });
  $(".customize-product a").click(function(){
      $("#toggle").toggleClass("minus");
  });
  $("#shipping_pincode").on('keyup',function(e) {
    if(e.keyCode == 8) {
      $(".delivery-info").fadeOut();
    } else {
      $("#checkShippingAvailbility").on("click",function() {
        $(".delivery-info").fadeIn();
      });
      
    }
  });
  $("#shipping_pincode").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }  
  });
</script>

    <script>
        async function checkShippingAvailability() {
            this.disabled = true;
            this.innerText ='Please Wait...';
            $('#shipping_success').css('display','none');
            $('#shipping_error').css('display','none');
            var shipping_pincode = $('#shipping_pincode').val();
            var product_id = $('#product_id').val();
              
            if(shipping_pincode != ''){
                let response = await fetch("{{url('/ajax/checkShippingAvailability')}}/"+ shipping_pincode+'/'+product_id);
                let output  = await response.json();
                    @if(config('settings.enable_zip_code')){
                    if(output === 1){
                        $('#add_cart').removeClass('btn-danger');
                        $('#add_cart').addClass('btn-success');
                        $('#add_cart').removeAttr('disabled');
                        $('#shipping_success').css('display','block');
                    }else{
                        $('#add_cart').removeClass('btn-success');
                        $('#add_cart').addClass('btn-danger');
                        $('#add_cart').attr('disabled','true');
                        $('#shipping_error').css('display','block');
                    }
                }
                @else
                $('#add_cart').addClass('btn-success');
                $('#add_cart').removeAttr('disabled');
                if(output === 1){
                    $('#shipping_success').css('display','block');
                }else{
                    $('#shipping_error').css('display','block');
                }
                @endif
            }else{
               
                $('#add_cart').removeClass('btn-success');
                $('#add_cart').addClass('btn-danger');
                $('#add_cart').attr('disabled','true');
                $('#shipping_error').css('display','block');
            }

        }
    </script>

    <!-- <script src="{{asset('js/xZoom/xzoom.min.js')}}"></script>
    <script src="{{asset('js/xZoom/jquery.hammer.min.js')}}"></script>
    <script src="{{asset('js/xZoom/magnific-popup.js')}}"></script> -->
    <script>
        (function ($) {
            $(document).ready(function() {
               
                var variant_elem = $(document).find('.variant_input[type="radio"]');
                // var variant_hidden = $(document).find('.variant_input[type="hidden"]');

                if(variant_elem.length !== 0){
                    {{--updateProductAmount("{{$product->id}}");--}}
                }
                $('.xzoom, .xzoom-gallery').xzoom({zoomWidth: 500, title: false, tint: '#333', Xoffset: 28});

                //Integration with hammer.js
                var isTouchSupported = 'ontouchstart' in window;

                if (isTouchSupported) {
                    $('.xzoom').each(function() {
                        var xzoom = $(this).data('xzoom');
                        $(this).hammer().on("tap", function(event) {
                            event.pageX = event.gesture.center.pageX;
                            event.pageY = event.gesture.center.pageY;
                            var s = 1, ls;

                            xzoom.eventmove = function(element) {
                                element.hammer().on('drag', function(event) {
                                    event.pageX = event.gesture.center.pageX;
                                    event.pageY = event.gesture.center.pageY;
                                    xzoom.movezoom(event);
                                    event.gesture.preventDefault();
                                });
                            }

                            var counter = 0;
                            xzoom.eventclick = function(element) {
                                element.hammer().on('tap', function() {
                                    counter++;
                                    if (counter == 1) setTimeout(openmagnific,300);
                                    event.gesture.preventDefault();
                                });
                            }

                            function openmagnific() {
                                if (counter == 2) {
                                    xzoom.closezoom();
                                    var gallery = xzoom.gallery().cgallery;
                                    var i, images = new Array();
                                    for (i in gallery) {
                                        images[i] = {src: gallery[i]};
                                    }
                                    $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                                } else {
                                    xzoom.closezoom();
                                }
                                counter = 0;
                            }
                            xzoom.openzoom(event);
                        });
                    });
                } else {
                    //If not touch device
                    //Integration with magnific popup plugin
                    $('#xzoom-magnific').bind('click', function(event) {
                        var xzoom = $(this).data('xzoom');
                        xzoom.closezoom();
                        var gallery = xzoom.gallery().cgallery;
                        var i, images = new Array();
                        for (i in gallery) {
                            images[i] = {src: gallery[i]};
                        }
                        $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                        event.preventDefault();
                    });
                }

                $(document).on('change', '.variant_input', function() {
                    var product = $(this).data('product');
                    updateProductAmount(product);

                });
            });
        })(jQuery);

        function updateProductAmount(product) {

            var variants = [];
            var values = [];
            $('.variant_input').each(function(input) {
                if($(this).is(':checked')) {
                    var variant = $(this).data('variant');
                    var value = $(this).val();
                    variants.push(variant);
                    values.push(value);
                }
            });

            $.ajax({
                method: 'get',
                url: APP_URL + '/ajax/product/get-variant/' + product + '/' + variants.join(',') + '/' + values.join(','),
                success: function(response) {
                    if(response.success) {
                        $('.product-price-box').html('<span class="product-price">' + response.data + '</span>');
                    }
                }
            });
        }

        $(function(){
            // Product Details Product Color Active Js Code
            $(document).on('click', '.color-variation-list .box', function () {
                colors = $(this).data('color');
                var parent = $(this).parent();
                $('.color-variation-list li').removeClass('active');
                parent.addClass('active');

                var color_var_inp = parent.parent().find('.variant_input[type="radio"]');

                color_var_inp.each(function(input) {
                    this.removeAttribute('checked');
                    // this.setAttribute('value','0');
                });
                // console.log($(this).parent().find('.variant_input[type="hidden"]'));
                var checked_inp = parent.find('.variant_input[type="radio"]');
                checked_inp.attr('checked','true');
                var product = checked_inp.data('product');
                updateProductAmount(product);
                // checked_inp.attr('value','1');
            });

            $(".groupVal").on("change",function() {
              var product_id = $(this).find(':selected').data('product_id');;
               
              if($(this).val()!='') {
                  $.ajax({
                    url:"{{ route('front.product.getproductprice') }}",
                    method:"POST",
                    datatype:"JSON",
                    data:{product_id:product_id,group_val:$(this).val()},
                    success:function(result) {

                      

                      var keys = [];
                      var values = [];

                      $("#new_price").val(result.new_price);
                      $(".price-regular").html('₹ '+result.total_price);
                      $("#sku").html(result.sku);
                      
                      $("#approxtotal_weight").html(result.total_weight+'gram');
                        
                      keys.push(Object.keys(result));
                      values.push(Object.values(result));
                       console.log(keys);
                      $( keys[0]).each(function( index,value ) {
                        $("#"+value).html(values[0][index]);
                          
                      });
                      
                      
                      
                    }
                });
              } else {
                confirm('Please select size');
                return false;
              }
               
            }); 
        });

    </script>

    @include('includes.reviews-submit-script')
    @include('includes.reviews-pagination-script')
     
    @include('includes.cart-submit-script')
    @if( !empty( $comparision_group_types ) && count( $comparision_products ) > 1)
        <script src="{{asset('css/comparision-group/js/modernizer.js')}}"></script>
        <script src="{{asset('css/comparision-group/js/main.js')}}"></script>
    @endif
@endsection