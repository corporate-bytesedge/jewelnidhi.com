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
.error {
    color:red;
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
       
        
        @if(isset($product->category->name) && $product->category->name!='')
          <li class="breadcrumb-item"><a href="/category/{{ strtolower($product->category->name) }}">{{ $product->category->name }}</a></li>
        @endif
 
        @if(isset($category->name) && $category->name!='')
          <li class="breadcrumb-item"><a href="/category/{{ strtolower($category->slug) }}">{{ $category->name }}</a></li>
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
            @if(isset($product->product_discount) && $product->product_discount!='')
              <div class="price-box">
                  @if($product->old_price)
                        <span class="price-old"><del> {{ currency_format(number_format($product->old_price)) }}</del></span>
                    @endif  
                    <span class="price-regular"> {{ isset($product->new_price) ? currency_format(number_format($product->new_price)) : '' }}</span>
              </div>
           @else
                <div class="price-box">
                  <span class="price-regular"> {{ currency_format(number_format($product->old_price)) }}</span>
                </div>
           @endif

           @if(isset($product-> product_discount) && $product-> product_discount!='0')
           <div class="making-charge">
              <span> {{ $product-> product_discount .'% '.$product-> product_discount_on }}  </span>
           </div>
           @endif
          </div>
          
            <div class="store-avaliable">
              <div class="your-pincode">
                <div class="static-text">Your Pincode</div>
                <div class="find-nearest-store">
                  <input class="form-control" type="text" name="pincode" id="shipping_pincode"   placeholder="Please enter your pincode"/>
                  <button class="btn btn-primary" type="button" id="checkShippingAvailbility" onclick="checkShippingAvailability()">Update</button>
                </div>
              </div>
              <div class="delivery-info">
                <p class="text-success" id="shipping_success" style="display: none;">* We ship to your entered pincode</p>
                <p class="text-danger" id="shipping_error" style="display: none;">* Sorry, we don't ship to your entered pincode </p>
                <!-- <p>Delivery By <b>Thu, Jun 25</b> for <b> Pincode 302016 </b></p> -->
              </div>
            </div>
            <!-- <div class="customize-product">
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
                <div class="box">
                  <span><i class="fa fa-diamond"></i>Diamond:</span>
                  <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="customRadio2" name="example2" value="customEx">
                    <label class="custom-control-label" for="customRadio2"> SI IJ</label>
                  </div>
                 
                </div>
              </div>
            </div> -->
             
            <div class="d-flex flex-wrap">
             
               
              @if ( !empty(array_filter($groupSizeArr)))
                  @php 
                    $defaultSize = ''; 
                    $pivotVal = array();
                    $array = array(); 
                    $uniqueSize = array();
                    
                  @endphp
                  <div class="pro-size mr-3">

                      <div class="d-flex justify-content-between groupVal">
                      <h6 class="option-title">Size :</h6>
                      <a href=""   data-toggle="modal" data-target="#exampleModal-bd-example-modal-lg"><strong>Size Guide</strong></a>
                    
                    
                      </div>
                  
                    <select class="nice-select groupVal">
                      <option value="">Select Size</option>
                      @foreach($groupSizeArr AS $k=> $value)
                          @php 
                            $defaultSize = isset($value->product_group_default) ? $value->product_group_default : '' ;
                            
                          @endphp

                            @if(!empty($value->specificationTypes))
                            
                                @foreach ($value->specificationTypes  as $ky => $val)
                                  
                                  @if($value->group_by_size == $val->pivot->specification_type_id && !in_array($val->pivot->value,$uniqueSize))
                                      @php
                                      array_push($uniqueSize,$val->pivot->value);
                                         
                                      @endphp
                                       <option @php echo ($defaultSize==1 ? 'selected="selected"' : '' ) @endphp data-product_id = "{{ $value->id }}" value="{{ $val->pivot->value }}">{{ $val->pivot->value }}</option>
                                  @endif
                                @endforeach
                            @endif
                      @endforeach 
                     
                      
                        
                     
                    </select>
                  </div>
              @endif
             
            @if($product->category->name == 'Diamond')
           
                 
                @if ( !empty(array_filter($groupPurityArr)))
                  @php 
                    $defaultPurity = ''; 
                    $pivotVal = array();
                    $array = array(); 
                    $uniquePurity = array();
                  @endphp
                    <div class="pro-size ">
                      <h6 class="option-title">Purity :</h6>
                      <select class="nice-select groupVal">
                        <option value="">Select Purity</option>
                        @foreach($groupPurityArr AS $k=> $value)
                            @php 
                              $defaultPurity = isset($value->product_group_default) ? $value->product_group_default : '' ; 
                            @endphp
                            @if(!empty($value->specificationTypes))
                              @foreach ($value->specificationTypes  as $ky => $val)
                                @if($value->group_by_purity == $val->pivot->specification_type_id && !in_array($val->pivot->value,$uniquePurity))
                                    @php
                                      array_push($uniquePurity,$val->pivot->value);
                                    @endphp
                                    <option @php echo ($defaultPurity==1 ? 'selected="selected"' : '' ) @endphp data-product_id = "{{ $value->id }}" value="{{ $val->pivot->value }}">{{ $val->pivot->value }}</option>
                                @endif
                              @endforeach
                            @endif
                        @endforeach 
                      </select>
                    </div>
                @endif
            @endif
			    </div>
          
          <div class="add-to-cart">
              <button class="btn btn-primary" id="add_cart" name="submit_button" type="button">@lang('Add to Cart')</button>
                @if(\Auth::user()) 
                
                  <button class="btn btn-primary m-10 buyNow" id="buy_now" name="buynow_button" value="buynow" type="submit">@lang('Buy Now')</button>
                   
                @else
                    <a href="javascript:void(0)" class="btn btn-primary buyNow" data-toggle="modal" data-target="#login-modal">@lang('Buy Now')</a> 
                @endif
               
           </div>
            
           <div class="d-f-detail">
            @if($product->metal_id == '2' || $product->metal_id == '14' )
              <p class="notes">Slight variations in gold weight may be experienced and would be applicable on final prices. For the clear visibility of the product design, few products may appear bigger or smaller than the actual size, though we always ensure to provide the accurate information.</p>
            @else
            <p class="notes">For the clear visibility of the product design, few products may appear bigger or smaller than the actual size, though we always ensure to provide the accurate information</p>
            @endif
              <h6>Need additional information about the product?</h6>
              <p>Please contact our customer care executives @9949016121, <a href="mailto:info@jewelnidhi.com">info@jewelnidhi.com</a></p>
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
           <h4 align="center">Product Details</h4>
           <hr>
           <!-- <table class="table table-bordered detail">
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
            
           </table> -->

            <div  id="metalInfo">
              <div class="table-responsive" >
                  <h6> Metal Information </h6>
                <table class="table table-bordered detail ">
                  @if($product->jn_web_id)
                  <tr>
                    <td> JN Web ID </td>
                    <td> {{ isset($product->jn_web_id) ? $product->jn_web_id : '' }} </td>
                  </tr>
                  @endif
                  <!-- <tr>
                    <td> Metal </td>
                    <td> {{ isset($product->metal->name) ? strtoupper($product->metal->name) : '' }} </td>
                  </tr> -->
                 
                  
                  
                    
                    @if($product->specificationTypes)
                      @foreach($product->specificationTypes AS $k=> $value)
                          @if($value->id =='42' || $value->id =='9' || $value->id =='21' || $value->id=='11' || $value->id=='28' || $value->id=='12' || $value->id=='13' || $value->id=='10' || $value->id =='25' || $value->id =='41' || $value->id =='29' || $value->id =='37' || $value->id =='39' || $value->id =='53'   || $value->id =='35' || $value->id =='30' || $value->id =='31'  || $value->id =='32'  || $value->id =='34' || $value->id =='67' || $value->id =='68' || $value->id =='66' || $value->id =='69' || $value->id =='20'  || $value->id =='26' || $value->id =='58' || $value->id =='54' )
                        
                            <tr>
                              <td>{{ $value['name'] ? ucwords($value['name']) : '' }} </td>
                              <td id="{{ isset($value['name']) ? str_replace(' ', '-', $value['name']) : '' }}">{{ str_replace('-',' ',strtoupper($value->pivot->value))  }} {{ strtoupper($value->pivot->unit) }}</td>
                            </tr>
                          @endif
                      @endforeach
                    @endif

                    

                    
                     
                  </table>
              </div>
 
                <div class="table-responsive">
                    <h6> Weight Details </h6>
                      <table class="table table-bordered detail ">
                         
                    @if($product->specificationTypes)
                      @php 
                      $weightRowID = '';
                      $weightId = ''; @endphp
                      
                      @foreach($product->specificationTypes AS $k=> $value)
                          @if($value->id =='14' || $value->id =='15' || $value->id =='23'  || $value->id =='38' || $value->id=='27' || $value->id == '40' || $value->id == '52' || $value->id == '55' || $value->id == '57' || $value->id == '70' )
                          @php $weightId =  isset($value['name']) ? str_replace(' ', '-', $value['name']) : '' ;
                              
                           @endphp 
                                <tr class="{{ $weightId }}">
                                  <td>{{ $value['name'] ? ucwords($value['name']) : '' }}  </td>
                                  <td id="{{ $weightId }}"> {{ str_replace('-',' ',strtoupper($value->pivot->value))  }} {{ strtoupper($value->pivot->unit) }}</td>
                                
                                </tr>
                          @endif
                      @endforeach
                    @endif
                      
                      <!-- @if(isset($product->carat_wt_per_diamond))
                      <tr>
                        <td>Carat Weight Per Diamond</td>
                        <td id="carat_wt_per_diamond"> {{ $product->carat_wt_per_diamond  }} </td>
                      </tr>
                      @endif -->
                        
                        
                              <!-- @if($product->metal_weight !='0' && $product->metal_weight!=null)
                                <tr>
                                  <td>Metal Weight </td>
                                  <td>{{ number_format($product->metal_weight) }}</td>
                                
                                </tr>
                              @endif

                              @if($product->stone_weight !='0' && $product->stone_weight!=null)
                                <tr>
                                  <td>Stone Weight </td>
                                  <td>{{ number_format($product->stone_weight) }} </td>
                                
                                </tr>
                              @endif
                           
                              @if($product->pearls_weight !='0' && $product->pearls_weight!=null)
                                <tr>
                                  <td>Pearls Weight </td>
                                  <td>{{ number_format($product->pearls_weight) }}</td>
                                
                                </tr>
                              @endif
                              @if($product->total_weight !='0' && $product->total_weight!=null)
                                <tr>
                                  <td>Total Weight </td>
                                  <td>{{ number_format($product->total_weight) }} </td>
                                
                                </tr>
                              @endif -->
                              <!-- @if($product->total_weight !='0' && $product->total_weight!=null)
                                <tr>
                                  <td>Total Weight </td>
                                  <td>{{ $product->total_weight }} </td>
                                
                                </tr>
                              @endif -->
                          
                        
                      </table>
                </div>

            
           
           <h6>Price Breakup</h6>
           <table class="table table-bordered detail">

           @php
            $total = 0;
           @endphp
            
                @if(isset($product->price) && $product->price!='0')
                  <tr>
                    <td> {{ isset($product->metal->name) && $product->metal->name =='Silver' ? 'Silver' : 'Gold ' }} Price </td>
                    <td id="gold_price"> {{ currency_format(number_format($product->price))  }} </td>
                  </tr>
                @endif

                <!-- @if(isset($product->diamond_price_one) && $product->diamond_price_one!='')
                  <tr>
                    <td>Diamond Price One</td>
                    <td id="diamond_price_one"> {{ currency_format(number_format($product->diamond_price_one))  }} </td>
                  </tr>
                @endif

                @if(isset($product->diamond_price_two) && $product->diamond_price_two!='')
                  <tr>
                    <td>Diamond Price Two</td>
                    <td id="diamond_price_two"> {{ currency_format(number_format($product->diamond_price_two))  }} </td>
                  </tr>
                @endif -->

                
                @if(isset($product->diamond_price) && $product->diamond_price!='0')
                  <tr>
                    <td>Diamond Price</td>
                    <td id="diamond_price"> {{ currency_format(number_format($product->diamond_price))  }} </td>
                  </tr>
                @endif


                @if($product->specificationTypes)
                    @php $priceId = ''; @endphp
                    
                      @foreach($product->specificationTypes AS $k=> $value)
                           
                      
                            @if($value->id =='16'  || $value->id =='46' || $value->id =='36' || $value->id =='24' || $value->id =='59'  || $value->id =='63'  || $value->id =='33' || $value->id =='65' )
                                @php $priceId =  isset($value['name']) ? str_replace(' ', '-', $value['name']) : '' ;
                                
                                @endphp 
                                
                                    <tr>
                                      <td>{{ $value['name'] ? ucwords($value['name']) : '' }}  </td>
                                      <td id="{{ $priceId }}"> ₹{{ $value->pivot->value }} </td>
                                    
                                    </tr>
                              
                            @endif
                      @endforeach
                @endif

                <!-- @if(isset($product->stone_price))
                  <tr>
                    <td>Stone Price</td>
                    <td id="stone_price"> {{ currency_format(number_format($product->stone_price))  }} </td>
                  </tr>
                @endif

                @if(isset($product->pearls_price))
                  <tr>
                    <td>Pearls Price</td>
                    <td id="pearls_price"> {{ currency_format(number_format($product->pearls_price))  }} </td>
                  </tr>
                @endif

                @if(isset($product->watch_price))
                  <tr>
                    <td>Watch Price</td>
                    <td id="watch_price"> {{ currency_format(number_format($product->watch_price))  }} </td>
                  </tr>
                @endif

               
                @if(isset($product->carat_price))
                  <tr>
                    <td>Carat Price </td>
                    <td id="carat_price"> {{ currency_format(number_format($product->carat_price))  }} </td>
                  </tr>
                @endif

                @if(isset($product->diamond_wtcarats_earrings_price) && $product->diamond_wtcarats_earrings_price!='0')
                  <tr>
                    <td>Diamond WT Earrings Price </td>
                    <td id="carat_price"> {{ currency_format(number_format($product->diamond_wtcarats_earrings_price))  }} </td>
                  </tr>
                @endif

                @if(isset($product->diamond_wtcarats_nackless_price) && $product->diamond_wtcarats_nackless_price!='0')
                  <tr>
                    <td>Diamond WT Nackless Price </td>
                    <td id="carat_price"> {{ currency_format(number_format($product->diamond_wtcarats_nackless_price))  }} </td>
                  </tr>
                @endif
                 

                @if(isset($product->total_stone_price))
                  <tr>
                    <td>Total Stone Price </td>
                    <td id="carat_price"> {{ currency_format(number_format($product->total_stone_price))  }} </td>
                  </tr>
                @endif -->
                
                @if(isset($product->vat_rate) && $product->vat_rate!='0')
                  <tr>
                    <td>VA</td>
                    <td id="vat_rate"> {{ currency_format(number_format($product->vat_rate)) }} </td>
                  </tr>
                @endif

                @if(isset($product->gst_three_percent) && $product->gst_three_percent!='0')
                  <tr>
                    <td>GST</td>
                    <td id="gst_three_percent"> {{ currency_format(number_format($product->gst_three_percent)) }} </td>
                  </tr>
                @endif
                
                
                @if(isset($product->product_discount) && $product->product_discount != '' && $product->product_discount != '0')
                  @if($product->subtotal!='' && $product->subtotal!='0')

                  @php 
                    $subTotal = $product->subtotal + $product->gst_three_percent;
                  @endphp
                    <tr>
                      <td>Subtotal</td>
                      <td id="subtotal"> {{ currency_format(number_format($subTotal)) }} </td>
                    </tr>
                  @endif
                  <tr>
                    <td>Discount</td>
                    <td id="discount_price"> {{ currency_format(number_format($product->old_price - $product->new_price )) }} </td>
                  </tr>
                @endif

               
          
          
               

                @if(isset($product->old_price) && $product->old_price!='0')
                  <tr>
                    <td>Total</td>
                    @if(isset($product->product_discount) && $product->product_discount!='') 
                      <td id="total_price"> {{ currency_format(number_format($product->new_price)) }} </td>
                    @else
                      <td id="total_price"> {{ currency_format(number_format($product->old_price)) }} </td>
                    @endif
                    
                  </tr>
                @endif
           </table>
          </div>

         </div>
       </div>

       <div class="col-md-6">
         <div class="jewel-promise">
           <h4>JEWELNIDHI BENEFIT</h4>
           <div class="policy-detail">
             <ul>
               <li>
                 <i class="fa fa-sign-out"></i>
                 <span>30-Day Money Back</span>
               </li>
               <li>
                <i class="fa fa-support"></i>
                <span>Lifetime Exchange </span>
              </li>
              <li>
                <i class="fa fa-certificate"></i>
                <span>Lifetime Buyback</span>
              </li>
              <li>
                <i class="fa fa-certificate"></i>
                <span>Certified Jewellery</span>
              </li>
              <li>
                <i class="fa fa-truck"></i>
                <span>Free Shipping</span>
              </li>
              <!-- <li>
                <i class="fa fa-thumbs-up"></i>
                <span>100% Refund</span>
              </li> -->
              
              <li>
                <i class="fa fa-heart"></i>
                <span>100% Returns</span>
              </li>
             </ul>
           </div>
         </div>

          

         <div class="have-question">
         
           <h4>Have a Question?</h4>
           <p>Call us at 9949016121</p>
           <span id="enquiryMsg" ></span>
            {!! Form::open(['method'=>'post', 'route'=>'front.enquiry', 'id'=>'enquiryform', 'onsubmit'=>' submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
            {!! Form::hidden('product_id', $product->id , []) !!}
              
              <div class="form-group">
                {!! Form::label('name', __('Name:')) !!}
                {!! Form::text('name', null , ['class'=>'form-control','id'=>'name','placeholder'=>__("Enter Name")]) !!}
                 
              </div>
              <div class="form-group">
                {!! Form::label('email', __('Email:')) !!}
                {!! Form::email('email', null , ['class'=>'form-control','id'=>'email','placeholder'=>__("Enter Email")]) !!}
                 
              </div>
              <div class="form-group">
                {!! Form::label('phone', __('Mobile:')) !!}
                {!! Form::text('phone', null , ['class'=>'form-control','id'=>'phoneno','placeholder'=>__(" Enter Mobile Number ")]) !!}
                  <span id="errormsg" style="color:#D3A012; margin-left:100px;"></span>
              </div>
              <div class="form-group textarea">
                {!! Form::label('query', __('Query:')) !!}
                {!! Form::textarea('query', null , ['class'=>'form-control','id'=>'query','rows'=> '2','placeholder'=>__(" Enter Query ")]) !!}
            
              </div>
              <button class="btn btn-primary" id="enquiryBtn" type="submit">Submit</button>
            {{ Form::close() }}
         </div>
       </div>

     </div>
    </div>
    </div>

    <!-- related products area start -->
    @if(count($similarProduct) > 0)

      <section class="section category-page related-products ">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading-sec">
                      <h2>Similar Products</h2>
                      
                      <img src="{{ URL::asset('img/home_line.png') }}" alt=""/>
                      
                    </div>
                    <span><center>
                    
                    @if(session()->has('wishlist_msg'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('wishlist_msg')}}</strong> 
            </div>
        @endif

                    </center></span>
                </div>
                
            </div>
              
            <div class="row">
                <div class="col-12">
                    <div class="product-carousel-4 slick-row-10 slick-arrow-style">
                    
                      @foreach($similarProduct AS $k => $val)
                      <div class="cat-item">
                        
                        <figure class="product-thumb">
                          
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
                          
                          @if(isset($val->is_new) && $val->is_new=='1')
                              <div class="product-badge">
                                  <div class="product-label new">
                                      <span>new</span>
                                  </div>
                              </div>
                            @endif
                          <div class="button-group">
                            @if(\Auth::user())
                            @php
                            $selected = false;
                            foreach(\Auth::user()->favouriteProducts as $taxonomy) {
                              if($taxonomy->id == $val->id )
                              {
                              $selected = true;
                              }
                            }
                            
                                
                            @endphp
                               
                               <a href="javascript:void();" @php echo $selected == true ? '' :'hidden' @endphp data-toggle="tooltip" id="removeBtn_{{ $val->id }}" style="color:red " onclick="removeWishlist('{{ $val->id }}')"   data-placement="left" title="Removed from your wishlist">
                                    <i class="fa fa-heart"></i>
                                </a>
                               
                               <a href="javascript:void(0);" @php echo $selected == false ? '' :'hidden' @endphp class="wishlistProduct1"  id="addBtn_{{ $val->id }}" data-toggle="tooltip" data-product_id = "{{ $val->id }}"  onclick="addWishlist('{{ $val->id }}')"   data-placement="left" title="Add to wishlist">
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

   <!-- Modal -->
<div class="modal fade " id="exampleModal-bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
             
<div class="container">

<div class="size-table">
  <div class="content">
    <h4 style="color:#cd1f0f;"><u>Jewel Nidhi Ring Size Guide</u></h4>
    <p>
      JewelNidhi ring size handbook would assist you to measure your ring size right at
      home so you can get your perfect ring to wear comfortably. Below are the common
      and easy ways to measure your ring size with minimal and available things at home.
    </p>
    <h5 style="color:#cd1f0f;">Method #1 : Knowing your ring size with a string or thread or strip of paper</h5>
    <ul>
      <li>Take a thin non-elastic string or thread or strip of paper</li>
      <li> Wrap it around your finger</li>
      <li>Use a pen to mark the non-elastic string or thread or strip of paper where it overlaps or cut the small piece of it with scissors</li>
      <li> Now, measure the marked length of the non-elastic string or thread or strip of paper with a ruler and note the measurement
      </li>
      <li>Compare the measurement from the below chart to know your ring size</li>
    </ul>

    <i style="color:#D3A012;">Useful tip: When you are opting this method, check the table to compare your measurement with the circumference parameter.
    </i>
    <h5 style="color:#cd1f0f;">Method #2 : Knowing your ring size with your old ring</h5>
    <ul>
      <li> Take your already owned ring and a ruler </li>
      <li>Keep your ring on the ruler and measure the inside diameter in mm/in and take a note of it</li>
      <li> Compare the measurement from the below chart to know your ring size </li>
    </ul>
    <i style="color:#D3A012;">
    Useful tip: When you are opting this method, check the table to compare your measurement with the diameter parameter.
    </i>
  </div>



  <table class="table">
    <tr>
      <td style="padding:0;">
        <table>
          <tr class="bg">
            <td><span style="color:#D3A012;">Ring Size(Indian)</span></td>
          </tr>
          <tr class="bg">
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>6</td>
          </tr>
          <tr>
            <td>7</td>
          </tr>
          <tr>
            <td>8</td>
          </tr>
          <tr>
            <td>9</td>
          </tr>
          <tr>
            <td>10</td>
          </tr>
          <tr>
            <td>11</td>
          </tr>
          <tr>
            <td>12</td>
          </tr>
          <tr>
            <td>12</td>
          </tr>
          <tr>
            <td>14</td>
          </tr>
          <tr>
            <td>15</td>
          </tr>
          <tr>
            <td>16</td>
          </tr>
          <tr>
            <td>17</td>
          </tr>
          <tr>
            <td>18</td>
          </tr>
          <tr>
            <td>19</td>
          </tr>
          <tr>
            <td>20</td>
          </tr>
          <tr>
            <td>21</td>
          </tr>
          <tr>
            <td>22</td>
          </tr>
          <tr>
            <td>23</td>
          </tr>
          <tr>
            <td>24</td>
          </tr>
          <tr>
            <td>25</td>
          </tr>
          <tr>
            <td>26</td>
          </tr>
          <tr>
            <td>27</td>
          </tr>
          <tr>
            <td>28</td>
          </tr>
          <tr>
            <td>29</td>
          </tr>
          <tr>
            <td>30</td>
          </tr>
        </table>
      </td>
      <td style="padding:0;">
        <table>
          <tr class="bg">
            <td colspan="2"><span style="color:#D3A012;">Internal Ring Diameter</span></td>
          </tr>
          <tr class="bg">
            <td><span style="color:#D3A012;">(mm)</span></td>
            <td><span style="color:#D3A012;">(in)</span></td>
          </tr>
          <tr>
            <td>14.6</td>
            <td>0.57</td>
          </tr>
          <tr>
            <td>15</td>
            <td>0.59</td>
          </tr>
          <tr>
            <td>15.3</td>
            <td>0.6</td>
          </tr>
          <tr>
            <td>15.6</td>
            <td>0.61</td>
          </tr>
          <tr>
            <td>16</td>
            <td>0.63</td>
          </tr>
          <tr>
            <td>16.2</td>
            <td>0.64</td>
          </tr>
          <tr>
            <td>16.5</td>
            <td>0.65</td>
          </tr>
          <tr>
            <td>16.8</td>
            <td>0.66</td>
          </tr>
          <tr>
            <td>17.2</td>
            <td>0.68</td>
          </tr>
          <tr>
            <td>17.4</td>
            <td>0.69</td>
          </tr>
          <tr>
            <td>17.8</td>
            <td>0.7</td>
          </tr>
          <tr>
            <td>18.1</td>
            <td>0.71</td>
          </tr>
          <tr>
            <td>18.5</td>
            <td>0.73</td>
          </tr>
          <tr>
            <td>18.8</td>
            <td>0.74</td>
          </tr>
          <tr>
            <td>19.1</td>
            <td>0.75</td>
          </tr>
          <tr>
            <td>19.5</td>
            <td>0.77</td>
          </tr>
          <tr>
            <td>19.7</td>
            <td>0.78</td>
          </tr>
          <tr>
            <td>20</td>
            <td>0.79</td>
          </tr>
          <tr>
            <td>20.3</td>
            <td>0.8</td>
          </tr>
          <tr>
            <td>20.6</td>
            <td>0.81</td>
          </tr>
          <tr>
            <td>21</td>
            <td>0.83</td>
          </tr>
          <tr>
            <td>21.2</td>
            <td>0.84</td>
          </tr>
          <tr>
            <td>21.6</td>
            <td>0.85</td>
          </tr>
          <tr>
            <td>22</td>
            <td>0.87</td>
          </tr>
          <tr>
            <td>22.3</td>
            <td>0.88</td>
          </tr>
        </table>
      </td>
      <td style="padding:0;">
        <table>
          <tr class="bg">
            <td colspan="2"><span style="color:#D3A012;">Internal Ring circumference</span></td>
          </tr>
          <tr class="bg">
            <td><span style="color:#D3A012;">(mm)</span></td>
            <td><span style="color:#D3A012;">(in)</span></td>
          </tr>
          <tr>
            <td>45</td>
            <td>1.77</td>
          </tr>
          <tr>
            <td>47.1</td>
            <td>1.85</td>
          </tr>
          <tr>
            <td>48.1</td>
            <td>1.89</td>
          </tr>
          <tr>
            <td>49</td>
            <td>1.93</td>
          </tr>
          <tr>
            <td>50</td>
            <td>1.97</td>
          </tr>
          <tr>
            <td>50.9</td>
            <td>2</td>
          </tr>
          <tr>
            <td>51.8</td>
            <td>2.04</td>
          </tr>
          <tr>
            <td>52.8</td>
            <td>2.08</td>
          </tr>
          <tr>
            <td>54</td>
            <td>2.13</td>
          </tr>
          <tr>
            <td>54.6</td>
            <td>2.15</td>
          </tr>
          <tr>
            <td>55.9</td>
            <td>2.2</td>
          </tr>
          <tr>
            <td>56.8</td>
            <td>2.24</td>
          </tr>
          <tr>
            <td>58.1</td>
            <td>2.29</td>
          </tr>
          <tr>
            <td>59</td>
            <td>2.32</td>
          </tr>
          <tr>
            <td>60</td>
            <td>2.36</td>
          </tr>
          <tr>
            <td>61.2</td>
            <td>2.41</td>
          </tr>
          <tr>
            <td>61.9</td>
            <td>2.44</td>
          </tr>
          <tr>
            <td>62.8</td>
            <td>2.47</td>
          </tr>
          <tr>
            <td>63.8</td>
            <td>2.51</td>
          </tr>
          <tr>
            <td>64.7</td>
            <td>2.55</td>
          </tr>
          <tr>
            <td>66</td>
            <td>2.6</td>
          </tr>
          <tr>
            <td>66.6</td>
            <td>2.62</td>
          </tr>
          <tr>
            <td>67.9</td>
            <td>2.67</td>
          </tr>
          <tr>
            <td>69.1</td>
            <td>2.72</td>
          </tr>
          <tr>
            <td>70</td>
            <td>2.76</td>
          </tr>
        </table>
      </td>
    </tr>
    
  </table>
</div>

</div>               
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
   
   <div class="toast-body">
     
   </div>
 </div>
    
@endsection

@section('scripts')

<script src="{{ asset('js/nice-select.min.js')}}"></script>

<script src="{{ asset('js/xzoom.js')}} "></script>
<script src="{{ asset('js/image-zoom.min.js')}}"></script>
<script src="{{ asset('js/script.js')}} "></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js"></script>
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
    var addWishlist;
    var removeWishlist;

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

                addWishlist =function (id) {
                   
                    $.ajax({
                        type:"POST",
                        url:$("#product-favourite-form_"+id).attr('action'),
                        data:{id:id},
                        
                        success:function(data) {
                          
                          
                             
                      $(".toast").toast('show');
                      $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Added to your wishlist');
                          
                      $("#removeBtn_"+id).removeAttr('hidden');
                      $("#removeBtn_"+id).show();
                      $("#addBtn_"+id).hide();
                          
                        } 
                      });
                    
                  }

                  removeWishlist = function (id) {
                      $.ajax({
                        type:"GET",
                        url:"{{ route('front.product.favourite.destroywishlist') }}"+'?id='+id,
                        
                        
                        success:function(data) {
                        
                          
                            
                          $(".toast").toast('show');
                          $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Removed from your wishlist');
              
             
                          $("#removeBtn_"+id).hide();
                          $("#addBtn_"+id).removeAttr('hidden');
                          $("#addBtn_"+id).show();
                          
                        } 
                      });
                  }
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
                      console.log(result.metalInfo);
                      

                       
                      $(".product-name").html(result.priceArr.name);
                      $(".making-charge").html(result.priceArr.discount);
                      $("#new_price").val(result.priceArr.price);
                       
                      if(result.priceArr.product_discount!='') {
                        $(".price-old").html('<del>'+result.priceArr.old_price+'</del>');
                        $(".price-regular").html(result.priceArr.new_price);
                      } else {
                        $(".price-old").html('');
                        $(".price-regular").html(result.priceArr.old_price);
                      }
                      
                      $("#metalInfo").html(result.metalInfo); 
                       

                       
                       
                      
                      
                    }
                });
              } else {
                confirm('Select ring size');
                return false;
              }
               
            });

            $("#enquiryBtn").on("click",function() {

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

                $("#enquiryform").validate({
                
                  submitHandler: function(form) { // ONLY FOR DEMO
                    
                    $.ajax({

                      method : $("#enquiryform").attr('method'),
                      url : $("#enquiryform").attr('action'),
                      data : $("#enquiryform").serialize(),
                      success:function(response) {
                          if(response.success== '1') {
                            $("#enquiryMsg").html(response.msg);
                            setTimeout(function() {
                              $("#enquiryMsg").slideUp();
                              }, 10000);
                          } else  if(response.success== '2') { 
                            $("#enquiryMsg").html(response.msg);
                            setTimeout(function() {
                              $("#enquiryMsg").slideUp();
                              }, 10000);
                          } else {
                            $("#enquiryMsg").html(response.msg);
                            setTimeout(function() {
                              $("#enquiryMsg").slideUp();
                              }, 10000);
                          }
                      }
                    });
                  
                  },
                  rules: {
                      
                      "name": "required",
                      "email": "required",
                      "phone": "required",
                      "query": "required"
                  },
                  ignore: ":hidden:not(.ignore)",
                  errorClass: 'error',
                  messages: {
                      
                      "name": "Please enter name",
                      "email": "Please enter email",
                      "phone": "Please enter mobile no",
                      "query": "Please enter your query"
                  },
                });
              
            }); 

            $('#phoneno').keypress(function(e) {

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

            $(".wishlistProduct").on("click",function() {
              if(confirm('Are you sure that you want to add product in wishlist') ) {
                $("#product-favourite-form_"+$(this).data('product_id')).submit();
              } else {
                return false;
              }
            });
        });

    </script>

    @include('includes.reviews-submit-script')
    @include('includes.reviews-pagination-script')
     
    @include('includes.cart-submit-script')
    <!-- @if( !empty( $comparision_group_types ) && count( $comparision_products ) > 1)
        <script src="{{asset('css/comparision-group/js/modernizer.js')}}"></script>
        <script src="{{asset('css/comparision-group/js/main.js')}}"></script>
    @endif -->
@endsection