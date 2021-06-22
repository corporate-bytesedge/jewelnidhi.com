@extends('layouts.manage')

@section('title')
    @lang('View Enquiry')
@endsection
@section('page-header-title')
        @lang('View Enquiry') 
    @endsection

@section('page-header-description')
     <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.settings.enquires.view')
@endsection