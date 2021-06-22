@extends('layouts.manage')

@section('title')
@if(isset($catalog))
    @lang('Edit Catalog')
@else
    @lang('Add Catalog')
@endif

@endsection
@if(isset($catalog))
    @section('page-header-title')
        @lang('Edit Catalog') 
    @endsection
@else

    @section('page-header-title')
    @if(isset($catalog))
        @lang('Edit Catalog') 
    @else
        @lang('Add Catalog')
    @endif
    @endsection

@endif

@section('page-header-description')
    @lang('Add New Catalog') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdateCatalog')
@endsection