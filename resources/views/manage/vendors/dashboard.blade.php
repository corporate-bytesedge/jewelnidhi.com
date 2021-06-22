@extends('layouts.manage')

@section('title')@lang('Vendor Dashboard') - {{config('app.name')}}@endsection

@section('styles')
	<style>
		.action-btn {
			margin-bottom: 5px;
		}
		#message {
			resize: vertical;
		}
		.payout-submission-message {
			margin-top: 25px;
		}
		.venderDescription {
			margin-top: 10px;
		}
		.edit-counter .panel-back {
			background-color: #fff;
			border-radius: 0px;
			box-shadow: 0px 0px 20px 0px #d8d8d8;
		}
		.edit-counter .noti-box {
			min-height: 100px;
			padding: 4px;
		}
		.edit-counter .panel-back>a {
			display: inline-block;
			padding: 7px;
			text-decoration: none
		}
		.edit-counter a {
			color:#656565;
		}
		.edit-counter .noti-box .icon-box {
			display: block;
			line-height: 43px;
			border-radius: 0px;
			box-shadow: 0px 0px 14px 0px #d8d8d8;
			margin-top: -22px;
			margin-left: 6px;
		}
		.edit-counter p.text-muted {
			display : inline;
		}
		.edit-counter .text-box {
			text-align: right;
			padding-right: 10px;
		}
	</style>
@endsection

@section('scripts')
    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('success'))
            toastr.success("{{session('success')}}");
        @endif
    </script>
    @endif
@endsection

@section('page-header-title')
	@lang('Vendor Dashboard')
@endsection



@section('content')
	
	@can('manage-shipment-orders', App\Other::class)
	@if(count(auth()->user()->shipments) > 0)
	<div class="row">
		<div class="col-md-12">
	        @if(session()->has('order_passed_to_shipment'))
	            <div class="alert alert-success alert-dismissible" role="alert">
	                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	                <strong>{{session('order_passed_to_shipment')}}</strong>
	            </div>
	        @endif
			<blockquote>@lang('Orders received to your shipments')</blockquote>
			@foreach(auth()->user()->shipments()->orderBy('id', 'asc')->get() as $shipment)
			<div class="col-md-4 well">
				<h4><div class="label label-primary">{{$shipment->name.' (' . __('ID:') . ' '.$shipment->id.')'}}</div></h4>
				<ul class="list-group" id="orders-list">
					@foreach($shipment->orders()->where('location_id', Auth::user()->location_id)->where('is_processed', 0)->get() as $order)
						@if($order->shipments()->orderBy('id', 'desc')->first()->id == $shipment->id)
						<a href="{{route('manage.orders.edit', $order->id)}}"><li class="list-group-item">
							#{{$order->getOrderId()}}
						<span class="text-muted pull-right">{{$order->created_at->format('H:i M d, Y')}}</span></li></a>
						@endif
					@endforeach
				</ul>
			</div>
			@endforeach
		</div>
	</div>
	<hr>
	@endif
	@endcan



	 <div class="row"> 
	 <div class="col-md-12 edit-counter"> 
	 
		<div class="row">
		@can('read', App\Product::class)  
			<div class="col-lg-4 col-xs-12">
				<div class="panel panel-back panel-success noti-box">
					<span class="icon-box panel-heading set-icon">
						<i class="fa fa-sitemap"></i>
					</span>
					<div class="text-box">
						<p class="main-text"> {{$products_count}} @lang('Products')</p>
						<p class="text-muted">@lang('Total Products!')</p>
					</div>
					<a href="{{route('manage.products.index')}}"> @lang('More Details') </a>
				</div>
				
			</div>
		@endcan
		@can('read', App\Order::class)  
			<div class="col-lg-4 col-xs-12">
				<div class="panel panel-back panel-success noti-box">
					<span class="icon-box panel-heading set-icon">
						<i class="fa fa-sitemap"></i>
					</span>
					<div class="text-box">
						<p class="main-text"> {{ $order_count }} @lang('Order')</p>
						<p class="text-muted">@lang('Total Order!')</p>
					</div>
					<a href="{{route('manage.orders.index')}}"> @lang('More Details') </a>
				</div>
				
			</div>
		</div>
		@endcan
	

	

	@if(Auth::user()->can('read', App\Banner::class) || Auth::user()->can('view-subscribers', App\Other::class) || Auth::user()->can('read-discount', App\Voucher::class))
	<br><br>
	@endif

	@can('view-sales', App\Other::class)
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1 col-md-12">
			<div class="chartjs-top-selling-products">
				<div class="panel panel-primary">
					<div class="panel-heading"><strong>@lang('Top Selling Products')</strong></div>
						{!! $chartjs_top_selling_products->render() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	@endcan
	
	@can('view-sales', App\Other::class)
	<br><br>
	@endcan

	@if(Auth::user()->can('read', App\Category::class) && Auth::user()->can('read', App\Brand::class) && Auth::user()->can('read', App\Product::class) && Auth::user()->can('read', App\User::class) && Auth::user()->can('read', App\Banner::class))
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1 col-md-12">
			<div class="chartjs-count-data">
				{!! $charts_count_data->render() !!}
			</div>
		</div>
	</div>
	@endcan

	@can('read', App\Category::class)
	@if(count($root_categories) > 0)
	<div class="row">
		<div class="col-md-12">
			<h4 class="text-info">@lang('Categories Tree:')</h4>
			<ul id="tree1">
				@foreach($root_categories as $category)
					<li>
						{{ $category->name . ' (' . __('ID:') . ' '.$category->id.')' }}
						@if(count($category->categories))
							@include('partials.manage.subcategories', ['childs' => $category->categories])
						@endif
					</li>
				@endforeach
			</ul>
		</div>
	</div>
	@endif
	@endcan 

@endsection
