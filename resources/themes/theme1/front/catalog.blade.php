@extends('layouts.front')

@section('style')
<style>
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}
</style>
@endsection
 
@section('scripts')
<script type="text/javascript">
  jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#priceFilter").on("change",function() {

      if($(this).val()!='') {
        $.ajax({
          type:"POST",
          url:"{{ route('front.price_filter') }}",
          data:{value:$(this).val()},
          beforeSend: function() {
            // Show image container
            $("#loader").show();
          },
          success:function(data) {
            alert(data.success);
          },
          complete:function(data) {
            // Hide image container
            $("#loader").hide();
          }
        });
      }

    });

  });
</script>
     
@endsection

 

@section('content')
 
<div class="inner-banner">
@php 

if(isset($catalog->banner)) {
  $bannerpath = public_path().'/storage/category/banner/'.$catalog->banner;
} else {
  $bannerpath ='';
}

 
@endphp

	<img src="{{ URL::asset('img/catalogue.png') }}">
   </div>
  <div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
         
        <li class="breadcrumb-item">Catalog</li>
         
      
      </ol>
    </div>
  </div>
   
  <div class="category-page">
    <div class="container">
      <div class="category-head">
	  	<h2>Catalog Designs</h2>
	  </div>

       
      <!-- Image loader -->
		<div id='loader' style='display: none;'>
		<img src="{{ URL::asset('img/reload.gif') }}">
		</div>
<!-- Image loader -->

      	<div class="row">
		   
		  @if($catalogs->isNotEmpty())
		  	@foreach($catalogs AS $k=> $value)
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="cat-item catalogItem">
						<figure class="product-thumb">
								@if($value->image)
									@if($value->image)
										 
										<img  src="{{ asset('public/storage/catalog/'.$value->image) }}" alt="{{$value->name}}">
										
										@else
										<img src="https://via.placeholder.com/150x150?text=No+Image" class="img-responsive product-feature-image" alt="{{$product->name}}" />
									@endif
								@endif
						</figure>
						<div class="product-caption">
							<div class="price-box">
								<span class="price-regular"> 18Kt Wt :- {{ $value->weight }} <br/> D Ct Wt :- {{ $value->diamond_weight }} </span>
								 
							</div>
						</div>
					</div>
				</div>
			@endforeach
		  @endif
      
		</div>
    {{ $catalogs->appends(['sort' => 'votes'])->links() }}
     
      
    </div>
  </div>





    

@endsection
 