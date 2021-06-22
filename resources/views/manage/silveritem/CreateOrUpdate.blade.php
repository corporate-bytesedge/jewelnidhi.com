@extends('layouts.manage')

@section('title')

    @if(isset($silveritem))
        @lang('Edit Silver')
    @else 
        @lang('Add Silver')
    @endif
@endsection

    @section('page-header-title')
        @if(isset($silveritem))
            @lang('Edit Silver')
        @else 
            @lang('Add Silver')
        @endif
    @endsection


    



@section('page-header-description')
    @lang('Add New Silver Item') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.silveritem.CreateOrUpdate')
@endsection