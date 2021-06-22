@extends('layouts.front')

@section('title')@lang('Account Overview') - {{config('app.name')}}@endsection

@section('content')
    <div class="container">
        <div class="row dashboard-page">
        @include('partials.front.sidebar')
        <div class="content col-md-9">
            <div class="page-title">
                <h2 style="color:#fff !important;">@lang('Account Overview')</h2>
            </div>
            <div class="content-panel">
                <div class="row">
                
                <div class="col-md-4">
                    <div class="well">
                        <h4>@lang('Orders')</h4>
                        <hr>
                        <a href="{{route('front.orders.index')}}">@lang('Your Orders') <i class="fa fa-shopping-cart"></i></a>
                    </div>
                </div>
                 
                @if ( Auth::user()->can('read', App\Address::class))
                <div class="col-md-4">
                    <div class="well">
                        <h4>@lang('Addresses')</h4>
                        <hr>
                        <a href="{{route('front.addresses.index')}}">@lang('Your Addresses') <i class="fa fa-truck"></i></a>
                    </div>
                </div>
                @endif

                <div class="col-md-4">
                    <div class="well">
                        <h4>@lang('Profile')</h4>
                        <hr>
                        <a href="{{route('front.settings.profile')}}">@lang('Your Profile') <i class="fa fa-wrench"></i></a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

