@extends('layouts.front')
@if(count($products) > 0)
  @section('title'){{$keyword}} - {{config('app.name')}}@endsection
  @section('meta-tags')<meta name="description" content="@lang('Search results for'): {{$keyword}}">
  @endsection
  @section('meta-tags-og')<meta property="og:url" content="{{url()->current()}}" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="{{$keyword}} - {{config('app.name')}}" />
      <meta property="og:description" content="@lang('Search results for'): {{$keyword}}" />
  @endsection
@endif
@section('scripts')
 
<script type="text/javascript">
var addWishlist;
var removeWishlist;

  var page = 1;
  var didScroll = false;
  $(window).scroll(function() {
    didScroll = true;
  });

  setInterval(function() {
    if (didScroll){
       didScroll = false;
       var body = window.innerHeight + window.scrollY + 600;
       var footer = document.body.offsetHeight;
        
      
      if ((body) >= document.body.offsetHeight) {
         /* if($(window).scrollTop() + $(window).height() >= $(document).height()) {*/
          pageCountUpdate();
         /* page++; 
          loadMoreData(page);
          */
        }
   }
}, 250)

  function pageCountUpdate() {
    var page = parseInt($('#page').val());
    var max_page = parseInt($('#max_page').val());
    if(page < max_page) {
      $('.ajax-load').show();
      $('#page').val(page+1);
      var pages = $('#page').val();
      var url = window.location.href;
      
      var arr = url.split('?');
      console.log(arr);
      loadMoreData(pages,arr);
        
      } 
  }
  

  function loadMoreData(page,arr) {
      
    $.ajax({
      
        url: '?page=' + page+'&'+arr[1],
        type: "get",
        
        beforeSend: function()
        {
            $('.ajax-load').show();
        }
    }).done(function(data) {
      
      $('[data-toggle="tooltip"]').tooltip();
        if(data.html == " "){
            $('.ajax-load').html("No more records found");
            return;
        }
        $('.ajax-load').hide();
        if($('.listProduct').hasClass('active')) {
          
          $("#contentProduct #itemList").removeClass("col-6");
          $("#contentProduct #itemList").addClass("col-sm-6");
        }

        if($('.gridProduct').hasClass('active')) {
          
          $("#contentProduct #itemList").removeClass("col-sm-6");
          $("#contentProduct #itemList").addClass("col-6");
        }
        
        $("#contentProduct").append(data.html);
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
  }

    
  jQuery(document).ready(function() {
     
    
     

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".priceFilter").on("change",function() {

        
         
        $.ajax({
          type:"POST",
          url:"{{ route('front.search_price_filter') }}",
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
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
          } 
        });
     

    });

    $("#priceSorting").on("change",function() {
      
      if($(this).val()!='') {
        $.ajax({
          type:"POST",
          url:"{{ route('front.search_price_sorting') }}",
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
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
           
            
          } 
        });
      }

    });

    function gridFunction() {

            
           if($(".listProduct").hasClass('active')) {
             $(".gridProduct").removeClass("active");
             $(this).addClass("active");
            
             $("#ajaxcontentProduct #itemList").addClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").removeClass("col-6");
           }
           $(".listProduct").on("click",function() {
              
             $(".gridProduct").removeClass("active");
             $(this).addClass("active");
            
             $("#ajaxcontentProduct #itemList").addClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").removeClass("col-6");
             
           });
           if($(".gridProduct").hasClass('active')) {
             $(".listProduct").removeClass("active");
             $(this).addClass("active");
             $("#ajaxcontentProduct #itemList").removeClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").addClass("col-6");
           }

           $(".gridProduct").on("click",function() {
             $(".listProduct").removeClass("active");
             $(this).addClass("active");
             $("#ajaxcontentProduct #itemList").removeClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").addClass("col-6");
             
           });
    }

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
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
          } 
        });
      

    });
    $("#typeFilter").on("change",function() {
         
      
         $.ajax({
           type:"POST",
           url:"{{ route('front.type_filter') }}",
           data:{type:$(this).val(),slug:$(this).data('slug'),attr:$(this).data('attr')},
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
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction();
           } 
         });
       
 
     });
    

    

    $("#purityFilter").on("change",function() {
         
          
         $.ajax({
           type:"POST",
           url:"{{ route('front.search_purity_filter') }}",
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
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction(); 
           } 
         });
        
 
     });


    $("#genderFilter").on("change",function() {
         
          
           $.ajax({
             type:"POST",
             url:"{{ route('front.search_gender_filter') }}",
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
               $('[data-toggle="tooltip"]').tooltip();
               gridFunction();
             } 
           });
          
   
       });

       $("#offerFilter").on("change",function() {
         
          
         $.ajax({
           type:"POST",
           url:"{{ route('front.search_offer_filter') }}",
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
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction();
           } 
         });
        
 
     });

                                             
     addWishlist =function (id) {
         
       $.ajax({
           type:"POST",
           url:$("#product-favourite-form_"+id).attr('action'),
           data:{id:id},
           
           success:function(data) {
             
             
              $(".toast").toast({
               autohide:true,
               delay:2000
              });
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
            
            $(".toast").toast({
               autohide:true,
               delay:2000
              });
               
            $(".toast").toast('show');
            $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Removed from your wishlist');
              
             
              $("#removeBtn_"+id).hide();
              $("#addBtn_"+id).removeAttr('hidden');
              $("#addBtn_"+id).show();
             
             
           } 
         });
     }
       
     if($(window).width() > 767) {
        
        $(".nice-select").mouseover(
          function(){
            $(this).addClass("open");
          }
        ).mouseout(
          function(){
            $(this).removeClass("open");
          }
        );
      }
      $(".FillterBY").on("click",function(){
        $("#filterDiv").toggle();
         
      });
      $('.SortBy').click(function() { 
        $("#SortDiv").toggle();
      });

      $(".listProduct").on("click",function() {
        $(".gridProduct").removeClass("active");
        $(this).addClass("active");
        if($(this).hasClass('active')) {
          $("#contentProduct #itemList").removeClass("col-6");
          $("#contentProduct #itemList").addClass("col-sm-6");
        }
        

      });
      
      $(".gridProduct").on("click",function() {
        $(".listProduct").removeClass("active");
        $(this).addClass("active");
        if($(this).hasClass('active')) {
          $("#contentProduct #itemList").removeClass("col-sm-6");
          $("#contentProduct #itemList").addClass("col-6");
        }
        
      });
  });
</script>
<script src="{{ asset('js/nice-select.min.js')}}"></script>
<script src="{{ asset('js/xzoom.js')}} "></script>
<script src="{{ asset('js/image-zoom.min.js')}}"></script>
<script src="{{ asset('js/script.js')}} "></script>
@endsection

@section('content')

<div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"> Search results for " {{ ucwords(request()->query('keyword')) }} " </li>
      </ol>
    </div>
</div>
 
<div class="category-page">
  <div class="container">
  <div class="category-head">
      <h4 style="color:#D3A012">Search results for " {{ ucwords(request()->query('keyword')) }} "</h4>
      <span>{{ count($allProduct) }} Designs</span>
    </div>
     
      @if(!empty($category))
      <div class="filter-sec">
        <div class="left">
          <h4>Filter by</h4>
          <div class="block">
            <div class="box">
              @if($category->category!=null)
              <select class="nice-select priceFilter" data-slug="{{ request()->get('keyword') }}" >
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
              <select class="nice-select priceFilter" data-slug="{{ request()->get('keyword') }}" >
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
              <select class="nice-select" data-slug="{{ request()->get('keyword') }}" id="metalFilter">
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
              <select class="nice-select" data-slug="{{ request()->get('keyword') }}" id="purityFilter">
                <option>PURITY</option>
                <option value="14">14K ( {{ isset($purity_fourteen_carat) ? count($purity_fourteen_carat) : '' }} )  </option>
                <option value="18">18K ( {{ isset($purity_eighteen_carat) ? count($purity_eighteen_carat) : '' }} )  </option>
                <option value="22">22K ( {{ isset($purity_twenty_two_carat) ? count($purity_twenty_two_carat) : '' }} )  </option>
                <option value="24">24K ( {{ isset($purity_twenty_four_carat) ? count($purity_twenty_four_carat) : '' }} )  </option>
                
              
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="{{ request()->get('keyword') }}" id="genderFilter">
              <option value ="all">GENDER</option>
              <option value="m">Men ( {{ isset($maleProduct) ? $maleProduct : '' }} ) </option>
              <option value="f">Women  ( {{ isset($femaleProduct) ? $femaleProduct : '' }} ) </option>
              <option value="u">Unisex ( {{ isset($uniSexProduct) ? $uniSexProduct : '0' }} ) </option>
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="{{ request()->get('keyword') }}" id="offerFilter">
              <option value="{{ isset($offerProduct) ? $offerProduct : 0 }}">OFFERS ({{ isset($offerProduct) ? $offerProduct : 0 }})</option>
              </select>
            </div>
          </div>
        </div>

        <div class="right">
          <h4>Sort by</h4>
          <div class="block">
            <div class="box">
              <select class="nice-select" data-slug="{{ request()->get('keyword') }}" id="priceSorting">
                
                <option value="asc" >Price (Low To High)</option>
                <option value="desc" >Price (High To Low)</option>
                <option value ="all" >Relevance</option>
                <option value="1" >New Arrivals</option>
                <option value="2">Best Selling</option>
                <option value="3" >Discounted</option>
            </select>
            </div>
          </div>
        </div>
        
      </div>
      @endif
  
      
         <!-- Image loader -->
<div id='loader' style='display: none;text-align:center'>
  <img src="{{ URL::asset('img/reload.gif') }}">
</div>
<!-- Image loader -->
<input type="hidden" id="page" value="1" />
<input type="hidden" id="max_page" value="<?php echo isset($max_page) ? ceil($max_page) : '' ?>" />
  
    <div id="ajaxcontentProduct"></div>
     
      <div  class="row" id="contentProduct">
        @if(count($products) > 0 )
         
                @include('front.ajaxcategorypagination')
          @else
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h1 class="mb-2 text-center">Oops!</h1>
              <h2 class="mb-5 text-center"> We could not find what you are looking for!</h2>
              <a href="{{ url('/') }}" class="btn btn-primary ShoppingBtn ">CONTINUE SHOPPING</a>
                
              </div>
        @endif
      </div>
  </div>

  <div class="ajax-load text-center" style="display:none">
	  <p><img src="{{asset('img/loader.gif')}}"></p>
  </div>
</div>

<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-body"></div>
</div>
 
@endsection
