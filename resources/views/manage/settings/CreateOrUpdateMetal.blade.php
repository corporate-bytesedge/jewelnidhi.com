@extends('layouts.manage')

@section('title')
    @if(isset($metal))
        @lang('Edit Metal')
    @else
        @lang('Add Metal')
    @endif
@endsection

    @section('page-header-title')
        @if(isset($metal))
            @lang('Edit Metal')
        @else
            @lang('Add Metal')
        @endif 
    @endsection


    

@endif

@section('page-header-description')
    @lang('Add New Metal') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdateMetal')
@endsection