@extends('layouts.manage')

@section('title')
        @if(isset($shop_by_metal_stone))
            @lang('Edit Shop By Metal Stone') 
        @else   
            @lang('Add Shop By Metal Stone') 
        @endif
@endsection
@if(isset($shop_by_metal_stone))
    @section('page-header-title')
        @lang('Edit Shop By Metal Stone') 
    @endsection
@else

    @section('page-header-title')
        @lang('Add Shop By Metal Stone') 
    @endsection

@endif

@section('page-header-description')
    @lang('Add New Shop By Metal Stone') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdateShopbyMetal')
@endsection