@extends('layouts.manage')

@section('title')
        @if(isset($certificates))
            @lang('Edit Certificate') 
        @else   
            @lang('Add Certificate') 
        @endif
@endsection
@if(isset($certificates))
    @section('page-header-title')
        @lang('Edit Certificate') 
    @endsection
@else

    @section('page-header-title')
        @lang('Add Certificate') 
    @endsection

@endif

@section('page-header-description')
    @lang('Add New Certificate') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdateCertificate')
@endsection