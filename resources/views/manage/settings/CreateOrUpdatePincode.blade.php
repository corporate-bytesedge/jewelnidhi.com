@extends('layouts.manage')
 
 

@if(isset($pincode))
    @section('title')
        @lang('Edit Pincode')
    @endsection
@else
    @section('title')
        @lang('Add Pincode')
    @endsection

@endif

@if(isset($pincode))
    @section('page-header-title')
        @lang('Edit Pincode') 
    @endsection
@else

    @section('page-header-title')
        @lang('Add Pincode') 
    @endsection

@endif

@section('page-header-description')
    @lang('Add New Pincode') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdatePincode')
@endsection