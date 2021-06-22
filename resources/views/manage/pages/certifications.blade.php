@extends('layouts.manage')
@if(isset($certifications))
@section('title')
    @lang('Edit Certifications')
@endsection
@else
@section('title')
    @lang('Add Certifications')
@endsection
@endif

@section('page-header-title')
    @lang('Certifications Page') 
@endsection

 

@section('scripts')
    @include('includes.tinymce')
@endsection

@section('content')
    @include('partials.manage.pages.certifications')
@endsection