@extends('layouts.front')

@section('title')@lang('Shipping Addresses') - {{config('app.name')}}@endsection

@section('styles')
    <style>
        table a:not(.btn), .table a:not(.btn) {
            text-decoration: none;
        }

        .shipin-addres h4 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .shipin-addres strong {
            padding: 10px 0px;
            line-height: 30px;
            font-size: 15px;
        }

        .shipping-address {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 30px;
        }

        .btn-area {
            border-top: 1px solid #ccc;
            padding-top: 15px;
            margin-top: 14px;
        }

        .btn-area .btn {
            padding: 4px 10px;
        }
    </style>
    <link href="{{asset('css/front-sidebar-content.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row dashboard-page">@include('partials.front.sidebar')
        <div class="content col-md-9">
            <div class="page-title">
                <h2 style="color:#fff !important;">@lang('Your Addresses')</h2>
            </div>
            @if(session()->has('address_deleted'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{session('address_deleted')}}
                </div>
            @endif
            <div class="row">
             
            @if(count($customers) > 0 )
                @foreach($customers as $key => $customer)
                     <div class="col-md-6 mb-4"> 
                        <div class="card"> 
                            <div class="card-body">
                                <div class="shipping-address">
                                <div><h4>{{$key+1}}. @lang('Shipping Address') </h4> 
                                    <div class="edit-delete">
                                        @if (Auth::user()->can('update', App\Address::class) ) 
                                            <a class="btn btn-primary btn-xs" href="{{route('front.addresses.edit', $customer->id)}}"><i class="fa fa-pencil"></i></a> 
                                        @endif
                                            @if (Auth::user()->can('delete', App\Address::class) )    
                                            <a href="" class='btn btn-xs btn-danger '
                                                onclick="
                                                        if(confirm('@lang('Are you sure you want to delete this?')')) {
                                                        event.preventDefault();
                                                        $('#delete-form-{{$customer->id}}').submit();
                                                        } else {
                                                        event.preventDefault();
                                                        }
                                                        "
                                                ><i class="fa fa-trash-o"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <strong>{{$customer->first_name . ' ' . $customer->last_name}}</strong>,
                                    <br> {{$customer->address}}<br> 
                                    {{$customer->city . ', ' . $customer->state . ' - ' . $customer->zip}}<br> 
                                    {{$customer->country}}<br> 
                                    <strong>@lang('Phone:')</strong> 
                                    {{$customer->phone}}<br> 
                                    <!-- <strong>@lang('Email:')</strong> {{$customer->email}}<br>  -->
                                    <div class="btn-area"> 
                                        <!-- <a class="btn btn-primary btn-xs pull-left" href="#">Edit</a>  -->
                                        {!! Form::model($customer, ['method'=>'delete', 'action'=>['FrontCustomersController@destroy', $customer->id], 'id'=> 'delete-form-'.$customer->id, 'style'=>'display: none;']) !!}
                                        {!! Form::close() !!}
                                        
                                    </div> 
                                </div> 
                            </div>
                        </div> 
                    </div>
               
                @endforeach
            @else
            <div class="col-md-12"> 
                <div class="card"> 
                    <div class="card-body">
                        <h5 align="center">Record not found</h5>
                    </div>
                </div>
            </div>
            @endif
            </div>

            <!-- @foreach($customers as $key => $customer)
                <div class="col-md-6">
                    <div class="shipping-address">
                        <h4>{{$key+1}}. @lang('Shipping Address')</h4>
                        <strong>{{$customer->first_name . ' ' . $customer->last_name}}</strong>,<br>
                        {{$customer->address}}<br>
                        {{$customer->city . ', ' . $customer->state . ' - ' . $customer->zip}}<br>
                        {{$customer->country}}.<br>
                        <strong>@lang('Phone:')</strong> {{$customer->phone}}<br>
                        <strong>@lang('Email:')</strong> {{$customer->email}}<br>
                        <div class="btn-area">
                            <a class="btn btn-primary btn-xs pull-left"
                               href="{{route('front.addresses.edit', $customer->id)}}">@lang('Edit')</a>
                            {!! Form::model($customer, ['method'=>'delete', 'action'=>['FrontCustomersController@destroy', $customer->id], 'id'=> 'delete-form-'.$customer->id, 'style'=>'display: none;']) !!}
                            {!! Form::close() !!}
                            <a href="" class='btn btn-xs btn-danger pull-right'
                               onclick="
                                       if(confirm('@lang('Are you sure you want to delete this?')')) {
                                       event.preventDefault();
                                       $('#delete-form-{{$customer->id}}').submit();
                                       } else {
                                       event.preventDefault();
                                       }
                                       "
                            >@lang('Remove')</a>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            @endforeach -->
        </div>
    </div>
    </div>
@endsection

