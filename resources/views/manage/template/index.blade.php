@extends('layouts.manage')
@section('title')
    @lang('Email Template')
@endsection

@section('page-header-title')
    @lang('Email Template Page') 
@endsection

 

@section('scripts')
    @include('includes.tinymce')
@endsection

@section('content')
    @include('partials.manage.template.index')
@endsection