@extends('layouts.manage')
@if(isset($scheme))
@section('title')
    @lang('Edit Schemes')
@endsection
@else
@section('title')
    @lang('Add Schemes')
@endsection
@endif

@section('page-header-title')
    @lang('Schemes Page') 
@endsection

 

@section('scripts')
    @include('includes.tinymce')
@endsection

@section('content')
    @include('partials.manage.pages.schemes')
@endsection