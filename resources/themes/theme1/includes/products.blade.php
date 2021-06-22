 
    @extends('layouts.front')
@if(isset($categorys))
 
@section('title'){{isset($category->meta_title) ? $category->meta_title : $category->name." - ".config('app.name')}}@endsection

  @section('meta-tags')<meta name="description" content="{{$category->meta_desc ? $category->meta_desc : __('Showing Products for Category:') .$category->name}}">
  @if($category->meta_keywords)<meta name="keywords" content="{{$category->meta_keywords}}">@endif
  @endsection
  @section('meta-tags-og')<meta property="og:url" content="{{url()->current()}}" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="{{ isset($category->meta_title) ? $category->meta_title : $category->name.' - '.config('app.name')}}" />
      <meta property="og:description" content="{{ isset($category->meta_desc) ? $category->meta_desc : __('Showing Products for Category:') .$category->name}}" />
      @if($category->photo)<meta property="og:image" content="{{$category->photo->name}}" />@endif
  @endsection
@endif
@section('scripts')

<script type="text/javascript">
  jQuery(document).ready(function() {
    var page = 1;
    $(window).scroll(function() {
       
        if($(window).scrollTop() >= $("#contentProduct").height()) {
            page++;
            loadMoreData(page);
        }
        function loadMoreData(page) {
           
          $.ajax({
            
              url: '?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.ajax-load').show();
	            }
	        }).done(function(data) {
	            if(data.html == " "){
	                $('.ajax-load').html("No more records found");
	                return;
	            }
	            $('.ajax-load').hide();
	            $("#contentProduct").append(data.html);
	        }).fail(function(jqXHR, ajaxOptions, thrownError) {
	              alert('server not responding...');
	        });
        }

    });
     

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".priceFilter").on("change",function() {

        
         
        $.ajax({
          type:"POST",
          url:"{{ route('front.price_filter') }}",
          data:{value:$(this).val(),slug:$(this).data('slug'),price:$(this).find(':selected').attr('data-price')},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
          },
          success:function(data) {
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data);
          } 
        });
     

    });

    $("#priceSorting").on("change",function() {
      
      if($(this).val()!='') {
        $.ajax({
          type:"POST",
          url:"{{ route('front.price_sorting') }}",
          data:{value:$(this).val(),slug:$(this).data('slug')},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
            
          },
          success:function(data) {
            
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data);
            
            
          } 
        });
      }

    });

    $("#metalFilter").on("change",function() {
         
      
        $.ajax({
          type:"POST",
          url:"{{ route('front.metal_filter') }}",
          data:{category_id:$(this).val(),slug:$(this).data('slug'),metal:$(this).find(':selected').attr('data-metal')},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
            
          },
          success:function(data) {
            
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data);
            
            
          } 
        });
      

    });

    

    $("#purityFilter").on("change",function() {
         
          
         $.ajax({
           type:"POST",
           url:"{{ route('front.purity_filter') }}",
           data:{value:$(this).val(),slug:$(this).data('slug')},
           beforeSend: function() {
             // Show image container
             $("#contentProduct").hide();
             $("#ajaxcontentProduct").hide();
             $("#loader").show();
             
           },
           success:function(data) {
             
             $("#loader").hide(); 
             $("#ajaxcontentProduct").show();
             $("#ajaxcontentProduct").html(data);
             
             
           } 
         });
        
 
     });


    $("#genderFilter").on("change",function() {
         
          
           $.ajax({
             type:"POST",
             url:"{{ route('front.gender_filter') }}",
             data:{value:$(this).val(),slug:$(this).data('slug')},
             beforeSend: function() {
               // Show image container
               $("#contentProduct").hide();
               $("#ajaxcontentProduct").hide();
               $("#loader").show();
               
             },
             success:function(data) {
               
               $("#loader").hide(); 
               $("#ajaxcontentProduct").show();
               $("#ajaxcontentProduct").html(data);
               
               
             } 
           });
          
   
       });

       $("#offerFilter").on("change",function() {
         
          
         $.ajax({
           type:"POST",
           url:"{{ route('front.offer_filter') }}",
           data:{value:$(this).val(),slug:$(this).data('slug')},
           beforeSend: function() {
             // Show image container
             $("#contentProduct").hide();
             $("#ajaxcontentProduct").hide();
             $("#loader").show();
             
           },
           success:function(data) {
             
             $("#loader").hide(); 
             $("#ajaxcontentProduct").show();
             $("#ajaxcontentProduct").html(data);
             
             
           } 
         });
        
 
     });

       

  });
</script>
<script src="{{ asset('js/nice-select.min.js')}}"></script>
<script src="{{ asset('js/xzoom.js')}} "></script>
<script src="{{ asset('js/image-zoom.min.js')}}"></script>
<script src="{{ asset('js/script.js')}} "></script>
@endsection

 

@section('content')
 
<div class="inner-banner">
@php 
if(isset($categorys)) {
  if($category->banner) {
    $bannerpath = public_path().'/storage/category/banner/'.$category->banner;
  } else {
    $bannerpath ='';
  }
}

 
@endphp
    @if(isset($categorys))
      @if(file_exists($bannerpath))
        <img src="{{ asset('storage/category/banner/'.$category->banner) }}">
      @else
    @endif
      <img src="{{ URL::asset('img/nobanner.jpg') }}">
    @endif
   </div>
  <div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        @if(isset($category->category->name) && $category->category->name!='')
        <li class="breadcrumb-item"><a href="{{ strtolower($category->category->name) }}">{{ $category->category->name }}</a></li>
        @endif
        @if(isset($category->name) && $category->name!='')
        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        @endif
      </ol>
    </div>
  </div>
   
  <div class="category-page">
    <div class="container">
      <div class="category-head">
        <h2>{{ $category->name ? ucwords($category->name) : '' }}</h2>
        <span>{{ isset($products) ? count($products) : 0 }} Designs</span>
      </div>
        
      <div class="filter-sec">
        <div class="left">
          <h4>Filter by</h4>
          <div class="block">
            <div class="box">
              @if($category->category!=null)
              <select class="nice-select priceFilter" data-slug="{{ Request::segment(2) }}" >
                <option data-price="all" value="all" >PRICE</option>
                
                    @php 
                      $priceRange = 0;
                      $priceRange = range($category->category->min_price,$category->category->max_price);
                      $minPrice = $category->category->min_price;
                      $maxPrice = $category->category->max_price;
                    @endphp
                    @if($category->category->min_price!='0' && $category->category->max_price!='0')
                        <option data-price="min" value="{{ $minPrice }}">Below ₹ {{ isset($category->category->min_price) ? $category->category->min_price: '' }}  </option>
                          @for($i = $category->category->min_price; $i < $category->category->max_price; $i = $i+10000)
                          
                              @php
                                $minimumPrice = explode('.',$i);
                                $firstNum = $i;
                                $minimumPrice = str_split($firstNum);
                                $lastNum = $i + 10000;
                                $MaximumPrice = str_split($lastNum);
                              @endphp
                            <option  value="{{ $firstNum }}/{{ $lastNum }}"> Between {{ $minimumPrice[0].$minimumPrice[1] }}K -  {{ $MaximumPrice[0].$MaximumPrice[1] }}K  </option>
                          @endfor
                        <option data-price="above" value="{{ $category->category->max_price }}">₹ {{ isset($category->category->max_price) ? $category->category->max_price: '' }} and above </option>
                      @endif
                      </select>
              @else
              <select class="nice-select priceFilter" data-slug="{{ Request::segment(2) }}" >
                <option data-price="all" value="all" >PRICE</option>
                
                     
                    

                    @php  
                          $start = $category->min_price;
                          $end = $category->max_price;
                          $step = 10000;
                        @endphp
                        <option data-price="min" value="0/{{ $start }}">Under ₹ {{ isset($start) ? $start: '' }} </option>
                    
                      @for($i=$start; $i<$end; $i=$i+$step)
                            @php
                            
                              $lastNum = $i + $step;
                              $minimumPrice = explode('.',$i);
                              $firstNum = $i;
                              $minimumPrice = str_split($firstNum);
                              $lastNum = $i + $step;
                              $MaximumPrice = str_split($lastNum);
                            @endphp
                            
                        <option  value="{{ $firstNum }}/{{ $lastNum }}"> Between {{ $minimumPrice[0].$minimumPrice[1] }}K -  {{ $MaximumPrice[0].$MaximumPrice[1] }}K </option>
                      @endfor
                    
                   
                    
                    <option data-price="above" value="{{ $end }}">Above ₹ {{ isset($end) ? $end: '' }} </option>
                   
                    
                    
                   
                 
              </select>
              @endif
            </div>
            <div class="box">
              <select class="nice-select" data-slug="{{ Request::segment(2) }}" id="metalFilter">
                <option data-metal="all" value="all">METAL</option>
                
                @if(!empty($metal))
                  @foreach($metal AS $ke=> $value)
                    @if($ke==0)
                    <option data-metal="{{ $value->category->name }}" value="{{ $value->category->id }}">{{ $value->category->name }} ( {{ count($metal) }} )  </option>
                    @endif
                  @endforeach
                @endif
                
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="{{ Request::segment(2) }}" id="purityFilter">
                <option>PURITY</option>
                <option value="14">14K ( {{ isset($purity_fourteen_carat) ? count($purity_fourteen_carat) : '' }} )  </option>
                <option value="18">18K ( {{ isset($purity_eighteen_carat) ? count($purity_eighteen_carat) : '' }} )  </option>
                <option value="22">22K ( {{ isset($purity_twenty_two_carat) ? count($purity_twenty_two_carat) : '' }} )  </option>
              
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="{{ Request::segment(2) }}" id="genderFilter">
              <option value ="all">GENDER</option>
                <option value="m">Men ( {{ isset($maleProduct) ? $maleProduct : '' }} ) </option>
                <option value="f">Women  ( {{ isset($femaleProduct) ? $femaleProduct : '' }} ) </option>
                 
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="{{ Request::segment(2) }}" id="offerFilter">
                <option value="{{ isset($offerProduct) ? $offerProduct : 0 }}">OFFERS ({{ isset($offerProduct) ? $offerProduct : 0 }})</option>
              </select>
            </div>
           </div>
        </div>

        <div class="right">
          <h4>Sort by</h4>
          <div class="block">
            <div class="box">
              <select class="nice-select" data-slug="{{ Request::segment(2) }}" id="priceSorting">
                <option value ="all" >RELEVANCE</option>
                <option value="asc" >Price (Low To High)</option>
                <option value="desc" >Price (High To Low)</option>
            </select>
            </div>
          </div>
        </div>
        
      </div>

      
      <!-- Image loader -->
<div id='loader' style='display: none;text-align:center'>
  <img src="{{ URL::asset('img/reload.gif') }}">
</div>
<!-- Image loader -->
      <div id="ajaxcontentProduct"></div>
      <div id="contentProduct">
        <div class="row" >
         
        @if(count($products) > 0)

            @include('includes.form_errors')

                @foreach($products AS $key => $val)
                 
                  
                  
                  <div class="col-lg-3 col-md-4 col-sm-6">
                      <div class="cat-item">
                        @if(isset($val->product_discount) && $val->product_discount!='0')
                          <span class="offers-wrapper"> <span class="offer"> <span>{{ $val->product_discount .'% '.$val->product_discount_on }} </span> </span> </span>
                        @endif
                          <figure class="product-thumb">
                              <a href="/product/{{ $val->slug }}">
                              
                              @if($val->photo!=null)
                                  @if($val->photo->name)
                                    @php
                                        $image_url = \App\Helpers\Helper::check_image_avatar($val->photo->name, 150);
                                    @endphp
                                    <img class="{{ $val->overlayphoto!='' ? 'pri-img' : ''}} " src="{{$image_url}}" alt="{{$val->name}}">
                                  @endif
                              @else
                                  <img src="https://via.placeholder.com/150x150?text=No+Image" class="img-responsive product-feature-image" alt="" />
                              @endif
                              
                                @if($val->overlayphoto!=null)
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
                                      <span data-toggle="modal" data-target="#login-modal">
                                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="left" title="Add to wishlist"  >
                                          <i class="fa fa-heart-o"></i>          
                                        </a>
                                      </span>
                                           
                                      @endif

                                        
                                      
                                   
                              </div>
                              <div class="cart-hover">
                                <a href="/product/{{ $val->slug }}"><button class="btn btn-cart">View Details</button></a>
                              </div>
                          </figure>
                            <div class="product-caption">
              
                              <div class="price-box">
                                  <span class="price-regular" style="font-size: 13px;">₹ {{ number_format($val->new_price) }}</span>
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
                  </div>
                    @endforeach
                   
              @else 
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <h2 class="mb-5 text-center">Oops! We are sorry, <br/>we could not find what you are looking for</h2>
                  <a href="{{ url('/') }}" class="btn btn-primary ShoppingBtn ">CONTINUE SHOPPING</a>

              </div>
              @endif
          </div>
          
        
        </div>
      </div>
  </div>

  <div class="ajax-load text-center" style="display:none">
	  <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading</p>
  </div>





 

@endsection
 
            
    
 



