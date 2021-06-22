@extends('layouts.manage')

@section('title')
    @if(isset($puirty))
        @lang('Edit Metal Puirty')
    @else
        @lang('Add Metal Puirty')
    @endif
@endsection

    

    @section('page-header-title')
        @if(isset($puirty))
            @lang('Edit Metal Puirty') 
        @else
            @lang('Add Metal Puirty')
        @endif
    @endsection



@section('page-header-description')
    @lang('Add New Metal') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdatePuirty')
@endsection